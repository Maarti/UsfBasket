<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Match_model extends CI_Model
{
    // Retourne la listes matchs (non-supprimés) qui n'ont pas encore eu lieu, à partir de $offset jusqu'à $limit (pour la pagination)
    public function getAllFuturMatchs($limit,$offset,$onlyhome) {
        if($onlyhome==1)
            $home='AND `match_home`=1';
        else
            $home ='';
        return $this->db->query('
            SELECT `match_id`,`match_oponent`,`match_date`,DATE_FORMAT(`match_date`,"%d/%m/%Y %Hh%i") as match_datefr,`match_deleted`,`team_id`,`team_name`,`team_label`,`place_address`,`place_name`, `match_ar`, `match_mr`, `match_cr`
            FROM (`match`)
            JOIN `place` ON `match`.`match_place`=`place`.`place_id`
            JOIN `team` ON `match`.`match_team`=`team`.`team_id`
            WHERE `match_date` > NOW()
            AND `match_deleted`=0
            '.$home.'
            GROUP BY `match_id`
            ORDER BY match_date, match_oponent
            LIMIT '.$limit.'
            OFFSET '.$offset)->result();
    }
    
    // Compte le nombre de matchs futurs qui ne sont pas supprimés (utile pour la pagination)
    public function getFuturMatchsNumber($home){
        $where = "`match_date` > NOW()";
        $this->db->where($where);
        $this->db->where('match_deleted', 0);
        $this->db->where('match_home', $home);
        $this->db->from('match');
        return $this->db->count_all_results();
    }
    
    public function getThisMatch($mid,$onlyFutur=FALSE) {
        if($onlyFutur){
            $time=' AND `match_date` > NOW()';                    
        }else{
            $time='';
        }
        return $this->db->query('
            SELECT `match_id`,`match_oponent`, `match_team`, `match_date`, DATE_FORMAT(`match_date`,"%d/%m/%Y %Hh%i") as match_datefr,`match_place`,`team_name`,`team_label`,`place_address`,`place_name`, `match_ar`, `match_mr`, `match_cr`
            FROM (`match`)
            JOIN `place` ON `match`.`match_place`=`place`.`place_id`
            JOIN `team` ON `match`.`match_team`=`team`.`team_id`
            WHERE `match_deleted`=0'.$time.'
            AND `match_id`='.$mid)->result();
    }
    
    // Retourne les futurs match d'une équipe passée en paramètre, classés par dates croissantes (tableau myMatch_list)
    public function getFuturTeamMatchs($tid){
        $where = "TO_DAYS(NOW()) - TO_DAYS(`match_date`) <= 60";    // Dont la date est future ou dans les 60 derniers jours
        return $this->db->select('match_id, match_date, DATE_FORMAT(`match_date`,"%d/%m/%Y") as match_datefr',FALSE)->where('match_deleted', 0)->where('match_team', $tid)->where($where)->from('match')->order_by('match_date','asc')->get()->result();
        
    }
    
    //Retourne une matrice indéxée par les match_id et user_id et contenant l'état de la présence du joueur au match (tableau mymatchs_list)
    public function getTeamRegisterMatch($tid){
        $query = $this->db->query('
            SELECT *
            FROM `match_presence`
            WHERE `match_presence_mid` IN (
                SELECT `match_id`
                FROM `match`
                WHERE `match_team` = '.$tid.' 
                AND `match_deleted` = 0
                AND TO_DAYS(NOW()) - TO_DAYS(`match_date`) <= 60
            )');
        $registerMatrix=array();
        foreach($query->result() as $register){
            $registerMatrix[$register->match_presence_mid][$register->match_presence_uid]=$register->match_presence_state;
        }
        return $registerMatrix;
    }
    
        
    //Créer un match à partir des paramètres
    public function createMatch($home,$oponent,$team,$date,$place,$newPlace,$newPlaceAddress,$ar,$mr,$cr,$uid){
        // On créer la place si elle n'existe pas
        if($place=='other'){
            $ci =& get_instance();
            $ci->load->model('place_model');
            $ci->place_model->createPlace($newPlace,$newPlaceAddress);
            $place = $ci->place_model->getPlaceId($newPlace);
        }
        
        $match_create_data = array(
            'match_home' => $home,
            'match_oponent' => $oponent,
            'match_team' => $team,
            'match_date' => $date,
            'match_place' => $place,
            'match_ar' => $ar,
            'match_mr' => $mr,
            'match_cr' => $cr,
            'match_author' => $uid); 

        log_message('info', 'Match created : '.$oponent.' on '.$date.' by : '.$uid.'(user_id)');
        return $this->db->insert('match', $match_create_data); 
    }
    
    // Supprime le match (deleted=1) passé en paramètre
    public function deleteMatch($mid){
        $delete_this_match = array('match_deleted' => '1');
        $this->db->where('match_id', $mid);
        log_message('info', 'Match deleted : '.$mid.'(match_id)');
        return $this->db->update('match', $delete_this_match);
    }

    // édite le match (dont l'id est passé en paramètre) avec les autres paramètres
    public function editMatch($mid, $oponent, $team, $date, $place, $ar, $mr, $cr, $author){
        $edit_this_match = array('match_oponent' => $oponent, 'match_team' => $team, 'match_date' => $date, 'match_place' => $place, 'match_ar' => $ar, 'match_mr' => $mr, 'match_cr' => $cr, 'match_author' => $author);
        $this->db->where('match_id', $mid);
        return $this->db->update('match', $edit_this_match);
    }
    
    //Modère l'inscription d'un user à un match avec son id, l'id du match, son role (arbitre, chrono, marqueur) et l'état de son inscription
    public function setMatchModeration($uid,$mid,$role,$state){
        $this->match_model->sendModerationConfirmationMail($uid,$mid,$state,$role);
        return $this->db->query('
            UPDATE match_'.$role.'
            SET  match_'.$role.'_state ='.$state.'
            WHERE  match_'.$role.'.match_'.$role.'_mid ='.$mid.'
            AND  match_'.$role.'.match_'.$role.'_uid ='.$uid);
    }
    
    // Envoie un mail de confirmation lorsqu'un admin confirme l'inscription d'un volontaire à l'organisation d'un match
    public function sendModerationConfirmationMail($uid,$mid,$state,$role){
        $CI =& get_instance();
        $CI->load->model('user_model');
        $mail = $CI->user_model->getUserMail($uid);
        $firstname = $CI->user_model->getUserFirstname($uid);
        $place = $this->match_model->getMatchPlace($mid);
        $address = $this->match_model->getMatchAddress($mid);
        $date = $this->match_model->getMatchDate($mid);
        
        if($state==1){
            $subject='Confirmation de votre inscription pour l\'organisation du match';
            $message='  <html><body>Bonjour '.htmlspecialchars($firstname).',<br/>
                        Votre inscription pour l\'organisation du match a été confirmée par un administrateur.<br/>
                        <br/>
                        <u>Rappel des informations :</u>
                        <ul>
                        <li>Lieu : <b>'.htmlspecialchars($place).'</b></li>
                        <li>Adresse : <b>'.htmlspecialchars($address).'</b></li>
                        <li>Date : <b>'.htmlspecialchars($date).'</b></li>
                        <li>Rôle : <b>'.htmlspecialchars($role).'</b></li>
                        </ul><br/>
                        Inutile de répondre à cet e-mail. En cas de problème, merci d\'utiliser <a href="http://bryan.maarti.net#contact">ce formulaire</a>.</body></html>';
        }else if($state==3){
            $subject='Inscription pour l\'organisation du match refusée';
            $message='  <html><body>Bonjour '.htmlspecialchars($firstname).',<br/>
                        Votre inscription pour l\'organisation du match du '.htmlspecialchars($date).' à '.htmlspecialchars($place).' pour le rôle de '.$role.' a été réfusée par un administrateur.<br/>
                        <br/><br/>
                        Inutile de répondre à cet e-mail. En cas de problème, merci d\'utiliser <a href="http://bryan.maarti.net#contact">ce formulaire</a>.</body></html>';
        }else{
            log_message('error', 'Tentative d\'envoi de mail de confirmation de modération d\'inscription à un match dot l\'état est ni accepté, ni refusé.');
            return null;
        }
        return $CI->user_model->sendMail($mail, $subject, $message);
    }
    
    //Inscrit un utilisateur à un match pour un rôle donné
    public function registerToMatch($mid,$uid,$role){
        //return $this->db->query('INSERT INTO match_'.$role.' (match_'.$role.'_mid, match_'.$role.'_uid, match_'.$role.'_state)VALUES ('.$mid.', '.$uid.', 2)');
        
         $match_register_data = array(
            'match_'.$role.'_mid' => $mid,
            'match_'.$role.'_uid' => $uid,
            'match_'.$role.'_state' => 2); 

         return $this->db->insert('match_'.$role, $match_register_data); 
    }
    
    //Annule l'inscription d'un utilisateur à un match
    public function cancelMatchRegistration($uid,$mid,$role) {
        return $this->db->query('
            UPDATE match_'.$role.'
            SET  match_'.$role.'_state =  4
            WHERE  match_'.$role.'.match_'.$role.'_mid ='.$mid.'
            AND  match_'.$role.'.match_'.$role.'_uid ='.$uid);
    }
    
    // Vérifie si une entrée de register n'est pas déjà présente dans la base
    public function entryExistsRegister($mid,$uid,$role){
        $entry_exists_query = $this->db->select('match_'.$role.'_uid')
                                        ->from('match_'.$role)
                                        ->where('match_'.$role.'_uid',$uid)
                                        ->where('match_'.$role.'_mid',$mid)
                                        ->get()
                                        ->result();
        if (empty($entry_exists_query)){
            return FALSE;
        }else{
            return TRUE;
        }
    }
    
    //Vérifie si l'id d'un match existe et s'il n'est pas supprimé (fonction utilisée dans le validator de register)
    public function checkMatchId($mid){
        $query = $this->db->get_where('match', array('match_id' => $mid, 'match_deleted'=>0));
        if ($query->num_rows() > 0)
            return TRUE;
        else
            return FALSE;
    }
    
    //Renvoie les inscrits à l'arbitrage d'un match
    public function getMatchArbitres($mid){
        return $this->db->query('
            SELECT user_lastname, user_firstname, match_arbitre_mid, match_arbitre_uid, match_arbitre_state
            FROM match_arbitre
            JOIN user ON user.user_id=match_arbitre.match_arbitre_uid
            WHERE match_arbitre_mid='.$mid.'
            AND user_deleted=0
            AND user_active=1
            ORDER BY match_arbitre_state')->result(); 
    }
    
    //Renvoie les inscrits au marquage d'un match
    public function getMatchMarqueurs($mid){
         return $this->db->query('
            SELECT user_lastname, user_firstname, match_marqueur_mid, match_marqueur_uid, match_marqueur_state
            FROM match_marqueur
            JOIN user ON user.user_id=match_marqueur.match_marqueur_uid
            WHERE match_marqueur_mid='.$mid.'
            AND user_deleted=0
            AND user_active=1
            ORDER BY match_marqueur_state')->result(); 
    }
    
    //Renvoie les inscrits au chronometrage d'un match
    public function getMatchChrono($mid){
       return $this->db->query('
           SELECT user_lastname, user_firstname, match_chrono_mid, match_chrono_uid, match_chrono_state
           FROM match_chrono
           JOIN user ON user.user_id=match_chrono.match_chrono_uid
           WHERE match_chrono_mid='.$mid.'
           AND user_deleted=0
           AND user_active=1
           ORDER BY match_chrono_state')->result(); 
    }
    
    //Associe un joueur à un match avec l'état de présence passé en paramètre 1=present 0=absent
    public function setMatchPresence($uid,$mid,$state){
        $match_presence_data = array(
            'match_presence_mid' => $mid,
            'match_presence_uid' => $uid,
            'match_presence_state' => $state);
        
         $entryExistsQuery = $this->db->select('match_presence_uid')
                                        ->from('match_presence')
                                        ->where('match_presence_uid',$uid)
                                        ->where('match_presence_mid',$mid)
                                        ->get()
                                        ->result();
        if (empty($entryExistsQuery)){  // Si l'entrée n'existe pas encore on l'a créer
            return $this->db->insert('match_presence', $match_presence_data);
        }else{                          // sinon, on l'update
            $this->db->where('match_presence_uid', $uid);
            $this->db->where('match_presence_mid', $mid);
            return $this->db->update('match_presence', $match_presence_data);
        }
    }
         
    //Retourne vrai si le match passé en paramètre existe et n'a pas encore eu lieu
    public function matchPresenceRegisterable($mid){
        $where = "`match_date` > NOW()";
        $query = $this->db->where('match_id', $mid)
                ->where($where)
                ->where('match_deleted', 0)
                ->from('match')
                ->get()
                ->result();
        if (empty($query)){
            return FALSE;
        }else{
            return TRUE;
        }
    }
    
    // renvoie les infos d'un utilisateur à partir de son mail (pour les passer en variable session)
    public function getMatchPlace($mid){
        $place = $this->db->query('
            SELECT `place_name`
            FROM (`place`)
            JOIN `match` ON `match`.`match_place` = `place`.`place_id`
            WHERE `match_id`='.$mid.'
            AND `match_deleted`=0')->result();
        return $place[0]->place_name;
    }
    
    // renvoie les infos d'un utilisateur à partir de son mail (pour les passer en variable session)
    public function getMatchAddress($mid){
        $address = $this->db->query('
            SELECT `place_address`
            FROM (`place`)
            JOIN `match` ON `match`.`match_place` = `place`.`place_id`
            WHERE `match_id`='.$mid.'
            AND `match_deleted`=0')->result();
        return $address[0]->place_address;
    }
    
    // renvoie les infos d'un utilisateur à partir de son mail (pour les passer en variable session)
    public function getMatchDate($mid){
        $date = $this->db->query('
            SELECT DATE_FORMAT(`match_date`,"%d/%m/%Y %Hh%i") as match_datefr
            FROM (`match`)
            WHERE `match_id`='.$mid.'
            AND `match_deleted`=0')->result();
        return $date[0]->match_datefr;
    }
}