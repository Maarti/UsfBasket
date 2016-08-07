<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 /*
  * "state" dans les tables match_arbitre, match_table et match_marqueur correspond à l'état de l'inscription
  * 1 = Confirmé
  * 2 = Inscrit
  * 3 = Refusé
  * 4 = Annulé
  */
if ( ! function_exists('nb_arbitre'))
{
    function nb_arbitre($mid)
    {
        $ci=& get_instance();
	$ci->load->database(); 
 
	$sql = 'SELECT IFNULL(COUNT(match_arbitre_uid),"0") as nb_arbitre FROM `match_arbitre` WHERE `match_arbitre`.`match_arbitre_mid`='.$mid.' AND `match_arbitre`.`match_arbitre_state`=1 GROUP BY `match_arbitre_mid`'; 
	$query = $ci->db->query($sql);
	$row = $query->result();
        if(isset($row[0]))
            return $row[0]->nb_arbitre;
        else
            return 0;
    }
}
 
if ( ! function_exists('nb_marqueur'))
{
    function nb_marqueur($mid)
    {
        $ci=& get_instance();
	$ci->load->database(); 
 
	$sql = 'SELECT IFNULL(COUNT(match_marqueur_uid),"0") as nb_marqueur FROM `match_marqueur` WHERE `match_marqueur`.`match_marqueur_mid`='.$mid.' AND `match_marqueur`.`match_marqueur_state`=1 GROUP BY `match_marqueur_mid`'; 
	$query = $ci->db->query($sql);
	$row = $query->result();
        if(isset($row[0]))
            return $row[0]->nb_marqueur;
        else
            return 0;
    }
}

if ( ! function_exists('nb_chrono'))
{
    function nb_chrono($mid)
    {
        $ci=& get_instance();
	$ci->load->database(); 
 
	$sql = 'SELECT IFNULL(COUNT(match_chrono_uid),"0") as nb_chrono FROM `match_chrono` WHERE `match_chrono`.`match_chrono_mid`='.$mid.' AND `match_chrono`.`match_chrono_state`=1 GROUP BY `match_chrono_mid`'; 
	$query = $ci->db->query($sql);
	$row = $query->result();
        if(isset($row[0]))
            return $row[0]->nb_chrono;
        else
            return 0;
    }
}
?>