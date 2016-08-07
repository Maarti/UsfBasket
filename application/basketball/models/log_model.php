<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Log_model extends CI_Model
{

    //Retourne la liste des logs (affiche "Utilisateur supprimé" si il n'y a plus de jointure avec un user)
        public function getAllLogs($limit,$offset){
            return $this->db->query('
                SELECT log_id, IFNULL(user_firstname,"Supprimé") as user_firstname, IFNULL(user_lastname,"Utilisateur") as user_lastname, DATE_FORMAT(log_date,"%d/%m/%y (%Hh%i)") as log_date, log_ip, log_agent, log_platform
                FROM log
                LEFT JOIN user ON log.log_user = user.user_id
                ORDER BY log_id DESC
                LIMIT '.$limit.'
                OFFSET '.$offset)->result();
        }
        
        //Compte le nombre total de logs (pour la pagination)
        public function countLogs(){
            return $this->db->count_all_results('log');
        }
        
        //Enregistre un log passé en paramètre
        public function createLog($uid,$ip,$agent,$platform){
            $connexion_log = array(
                'log_user' => $uid ,
                'log_ip' => $ip,
                'log_agent' => $agent,
                'log_platform' => $platform);
            if($ip!='127.0.0.1') // On n'enregistre pas les log qui se font en local (localhost)
                return $this->db->insert('log', $connexion_log);
            else
                return NULL;
        }


}