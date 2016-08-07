<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Match {
	
    function get_next_match(){
        $ci=& get_instance();
	$ci->load->database(); 
 
	$sql = 'SELECT match_id, team_id, team_name, match_oponent, DATE_FORMAT(match_date,"%d/%m/%y") as match_datefr, DATE_FORMAT(match_date,"%Hh%i") as match_hour, place_name
                FROM `match`
                JOIN `place` ON `match`.`match_place`=`place`.`place_id`
                JOIN `team` ON `match`.`match_team`=`team`.`team_id`
                WHERE match_deleted=0
                AND match_date > NOW()
                ORDER BY match_date
                LIMIT 1'; // ne fonctionne pas sans les guillemets
        
	$query = $ci->db->query($sql);
	$row = $query->result();
        if(isset($row[0])){
            $next_match = array('match_id' => $row[0]->match_id,
                                'team_id' => $row[0]->team_id,
                                'team_name' => $row[0]->team_name,
                                'match_oponent' => $row[0]->match_oponent,
                                'match_datefr' => $row[0]->match_datefr,
                                'match_hour' => $row[0]->match_hour,
                                'place_name' => $row[0]->place_name);
            return $next_match;
        }
        else{
            return FALSE;
        }
    }

}

?>
