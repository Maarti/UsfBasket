<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dailymessage_model extends CI_Model
{
    //Renvoie le message créé le plus récent et dont la date de fin de publication n'est pas passée
    public function getActiveDailyMessage(){
        return $this->db->query('
            SELECT user_firstname, user_lastname, DATE_FORMAT(dailyMessage_date,"%d/%m/%Y") as dailyMessage_dateFr, dailyMessage_body, dailyMessage_type, DATE_FORMAT(dailyMessage_end,"%d/%m/%Y") as dailyMessage_endFr
            FROM dailyMessage
            JOIN user ON dailyMessage.dailyMessage_author = user.user_id
            WHERE dailyMessage_deleted = 0
            AND dailyMessage_end >= CURDATE()
            ORDER BY dailyMessage_date DESC
            LIMIT 1')->result();
    }
        
    //Enregistre un message passé en paramètre
    public function createDailyMessage($uid,$body,$type,$end){
        $msg = array(
            'dailyMessage_author' => $uid,
            'dailyMessage_body' => $body,
            'dailyMessage_type' => $type,
            'dailyMessage_end' => $end,
            'dailyMessage_deleted' => '0');
        return $this->db->insert('dailyMessage', $msg);
    }
    
}