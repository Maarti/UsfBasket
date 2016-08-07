<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User_model extends CI_Model
{
    // Renvoie tous les utilisateurs actif et non supprimés ainsi que leur équipe
    public function getAllUsers(){
        return $this->db->query('
            SELECT `user_id`, `user_lastname`, `user_firstname`, `user_team`,`user_score`,  DATE_FORMAT(`user_lastconnect`,"%d/%m/%Y") as user_lastconnect, `user_isarbitre`, `user_istable`, `user_iscoach`, `user_istrainer`, `user_isplayer`, `team_label`, `team_name`
            FROM (`user`)
            LEFT JOIN `team` ON `user`.`user_team` = `team`.`team_id`
            WHERE `user_deleted`=0
            AND `user_active`=1
            ORDER BY `user_firstname`')->result();
    }
    
    //Renvoie toutes les infos des utilisateurs actifs (pour la partie admin)
    public function adminGetAllUsers($active){
        if($active)
            $sql='AND `user_active`=1';
        else
            $sql='AND `user_active`=0';
        return $this->db->query('
            SELECT `user_id`, `user_mail`,`user_lastname`, `user_firstname`,`user_cellphone`, DATE_FORMAT(`user_birthdate`,"%d/%m/%Y") as `user_birthdate`,`user_team`,`user_score`,  DATE_FORMAT(`user_lastconnect`,"%d/%m/%Y") as user_lastconnect, DATE_FORMAT(`user_registerdate`,"%d/%m/%Y") as user_registerdate,`user_isarbitre`, `user_istable`, `user_iscoach`, `user_istrainer`, `user_isplayer`, `rank_label`,`team_label`, `team_name`, `user_activating_code`
            FROM (`user`)
            LEFT JOIN `team` ON `user`.`user_team` = `team`.`team_id`
            JOIN `rank` ON `user`.`user_rank` = `rank`.`rank_id`
            WHERE `user_deleted`=0 '.$sql.'            
            ORDER BY user_rank DESC, user_lastname')->result();
        
    }
    
    // renvoie les infos d'un utilisateur à partir de son mail (pour les passer en variable session)
    public function getUserFromMail($mail){
        return $this->db->query('
            SELECT `user_id`, `user_mail`, `user_lastname`, `user_firstname`, `user_team`, `user_rank`, `team_id`, `team_label`, `team_name`
            FROM (`user`)
            LEFT JOIN `team` ON `user`.`user_team` = `team`.`team_id`
            WHERE `user_mail`=\''.$mail.'\'
            AND `user_deleted`=0')->result();
    }
    
    // renvoie le mail d'un user non supprimé a partir de son uid
    public function getUserMail($uid){
        $mail = $this->db->query('
            SELECT `user_mail`
            FROM (`user`)
            WHERE `user_id`='.$uid.'
            AND `user_deleted`=0')->result();
        return $mail[0]->user_mail;
    }
    
    // renvoie le prénom d'un user non supprimé a partir de son uid
    public function getUserFirstname($uid){
        $firstname = $this->db->query('
            SELECT `user_firstname`
            FROM (`user`)
            WHERE `user_id`='.$uid.'
            AND `user_deleted`=0')->result();
        return $firstname[0]->user_firstname;
    }
    
    
    // Renvoie les infos d'un user non supprimé à partir de son id
    public function getUser($uid){
        return $this->db->query('
            SELECT user_id, user_lastname, user_firstname, user_cellphone, user_birthdate, user_isarbitre, user_iscoach, user_istable, user_istrainer, user_isplayer, user_team, user_score, DATE_FORMAT(`user_registerdate`,"%d/%m/%Y") as user_registerdate,DATE_FORMAT(`user_lastconnect`,"%d/%m/%Y") as user_lastconnect, team_name, team_label
            FROM user
            LEFT JOIN team ON user.user_team = team.team_id
            WHERE user_deleted =0
            AND user_active =1
            AND user_id='.$uid)->result();
    }
    
    // Met à jour la date de derniere connexion d'un utilisateur
    public function updateLastConnect($mail){
        $edit_this_user = array('user_lastconnect' => date("Y-m-d H:i:s"));
        $this->db->where('user_mail', $mail);
        return $this->db->update('user', $edit_this_user);
    }
    
    // Renvoie un boolean pour savoir si un compte est actif à partir de son mail
    public function accountIsActive($mail){
        $query = $this->db->query('SELECT user_id FROM user WHERE user_mail="'.$mail.'" AND user_deleted=0 AND user_active=1');
        if ($query->num_rows() > 0)
            return TRUE;
        else
            return FALSE;
    }
    
    
    // Envoie un mail
    public function sendMail($to, $subject, $message, $fromMail = 'no-reply@maarti.net', $fromName = 'Maarti.net'){
        $CI =& get_instance();
        $CI->load->library('email');
        $this->email->set_mailtype("html");
        $this->email->from($fromMail, $fromName);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);
        log_message('info', 'SendMail to: '.$to);
        return $this->email->send();
    }
    
    // Envoie le mail d'activation d'un compte
    public function sendActivationMail($mail,$code,$firstname){
        $subject = 'Activation de votre compte Maarti';
        $message = '<html><body>Bonjour '.htmlspecialchars($firstname).',<br/>
                    Merci de cliquer sur le lien suivant afin de finaliser votre inscription sur '.$_SERVER['HTTP_HOST'].' :<br/>
                    <a href="'.site_url('user/active/'.$code).'">Lien d\'activation</a></br>
                    Ou recopiez le lien suivant dans votre navigateur :<br/>
                    '.site_url('user/active/'.$code).'<br/><br/>
                    Inutile de répondre à cet e-mail. En cas de problème, merci d\'utiliser <a href="http://bryan.maarti.net#contact">ce formulaire</a>.</body></html>';
        return $this->sendMail($mail, $subject, $message);
    }
    
    // Créer un utilisateur
    public function createUser($mail,$pass,$lastname,$firstname,$cellphone,$birthdate,$team,$ip,$roles){
        $this->load->library('encrypt');
        $encrypted_pass = $this->encrypt->encode($pass);
        $this->load->library('format_string');
        $lastname=$this->format_string->format_lastname($lastname);
        $firstname=$this->format_string->format_firstname($firstname);
        $activating_code=random_string('alnum', 32);                        
        $user_register_datas_submited = array(
            'user_mail' => $mail ,
            'user_pass' => $encrypted_pass ,
            'user_lastname' => $lastname ,
            'user_firstname' => $firstname ,
            'user_cellphone' => $cellphone ,
            'user_birthdate' => $birthdate ,
            'user_team' => $team ,
            'user_lastconnect' => date("Y-m-j H:i:s"),
            'user_activating_code'=>$activating_code,
            'user_ip' => $ip);
        if (!empty($roles)){
         foreach($roles as $role_submited) {
            switch ($role_submited) {
                case '1':
                    $therole='arbitre';
                    break;
                case '2':
                    $therole='table';
                    break;
                case '3':
                    $therole='coach';
                    break;
                case '4':
                    $therole='trainer';
                    break;
                case '5':
                    $therole='player';
                    break;
            }
            $user_register_datas_submited['user_is'.$therole] = 1;
         }
       }
       $this->user_model->sendActivationMail($mail,$activating_code,$firstname);
       log_message('info', 'User created : '.$mail);
       return $this->db->insert('user', $user_register_datas_submited);
    }
    
    
     // Éditer un utilisateur
    public function editUser($uid,$pass,$cellphone,$birthdate,$team,$roles){                                
        $this->load->library('encrypt');
        $encrypted_pass = $this->encrypt->encode($pass);
        $user_edit_data = array(
            'user_pass' => $encrypted_pass ,
            'user_cellphone' => $cellphone ,
            'user_birthdate' => $birthdate ,
            'user_team' => $team,
            'user_isarbitre' => 0 ,
            'user_istable' => 0 ,
            'user_iscoach' => 0 ,
            'user_istrainer' => 0 ,
            'user_isplayer' => 0);
        if (!empty($roles)){
         foreach($roles as $role_submited) {
            switch ($role_submited) {
                case '1':
                    $therole='arbitre';
                    break;
                case '2':
                    $therole='table';
                    break;
                case '3':
                    $therole='coach';
                    break;
                case '4':
                    $therole='trainer';
                    break;
                case '5':
                    $therole='player';
                    break;
            }
            $user_edit_data['user_is'.$therole] = 1;
          }
        }
        $this->db->where('user_id',$uid);
        return $this->db->update('user', $user_edit_data); 
    }
    
    // Savoir si un mail existe déjà dans la BDD
    public function userMailExists($mail){
        $query = $this->db->get_where('user', array('user_mail' => $mail));
        if ($query->num_rows() > 0)
            return TRUE;
        else
            return FALSE;
    }
   
    // Savoir si une combinaison mail/pass est correcte
    // Renvoie 0 si mail n'existe pas
    // Renvoie 1 si combinaison correcte
    // Renvoie 2 si combinaison incorrecte
    public function passwordIsCorrect($mail,$pass){
        $this->load->library('encrypt');
        $realEncryptedPassword = $this->db->select('user_pass')
                        ->from('user')
                        ->where('user_mail',$mail)
                        ->where('user_deleted',0)
                        ->where('user_active',1)
                        ->get()
                        ->result();
        if (empty($realEncryptedPassword)){
            return 0;
        }else{
            if ($this->encrypt->decode($realEncryptedPassword[0]->user_pass)==$pass) {
                return 1;
            } else {
                return 2; }
        }
    }
    
    //Active le compte d'un utilisateur correspondant au code donné en paramètre
    public function activeAccount($code){
         $this_user= $this->db->query('SELECT user_id
                                                    FROM user
                                                    WHERE user_deleted =0
                                                    AND user_activating_code="'.$code.'"')->result();
        
        if (!empty($this_user)) {
            $active_user=array('user_active'=>1);
            $this->db->where('user_activating_code',$code);
            $this->db->update('user', $active_user);
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    //Renvoie tous les membres d'une équipe passée en paramètre, par ordre alphabetique (pour le tableau myMatch_list)
    public function getTeamPlayers($tid){
        return $this->db->select('user_firstname,user_lastname,user_team,user_id')
                ->from('user')
                //->join('team', 'user.team = team.id')
                ->where('user.user_team',$tid)
                ->where('user_deleted','0')
                ->order_by('user_firstname', 'asc')
                ->get()
                ->result();
    }

}
       
?>