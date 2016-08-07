<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
if ( ! function_exists('is_arb'))
{
    function is_arb($uid)
    {
        $ci=& get_instance();
	$ci->load->database(); 
 
	$sql = 'SELECT IFNULL(COUNT(user_role_usermail),"0") as is_arb FROM (`user_role`) LEFT JOIN `user` ON `user`.`user_mail` = `user_role`.`user_role_usermail` WHERE `user`.`user_id`='.$uid.' AND `user_role_roleid`=1 GROUP BY `user_role_usermail`'; 
	$query = $ci->db->query($sql);
	$row = $query->result();
        if(isset($row[0]->is_arb))
            return TRUE;
        else
            return FALSE;
    }
}

if ( ! function_exists('is_tab'))
{
    function is_tab($uid)
    {
        $ci=& get_instance();
	$ci->load->database(); 
 
	$sql = 'SELECT IFNULL(COUNT(user_role_usermail),"0") as is_tab FROM (`user_role`) LEFT JOIN `user` ON `user`.`user_mail` = `user_role`.`user_role_usermail` WHERE `user`.`user_id`='.$uid.' AND `user_role_roleid`=2 GROUP BY `user_role_usermail`'; 
	$query = $ci->db->query($sql);
	$row = $query->result();
        if(isset($row[0]->is_tab))
            return TRUE;
        else
            return FALSE;
    }
}

if ( ! function_exists('is_coa'))
{
    function is_coa($uid)
    {
        $ci=& get_instance();
	$ci->load->database(); 
 
	$sql = 'SELECT IFNULL(COUNT(user_role_usermail),"0") as is_coa FROM (`user_role`) LEFT JOIN `user` ON `user`.`user_mail` = `user_role`.`user_role_usermail` WHERE `user`.`user_id`='.$uid.' AND `user_role_roleid`=3 GROUP BY `user_role_usermail`'; 
	$query = $ci->db->query($sql);
	$row = $query->result();
        if(isset($row[0]->is_coa))
            return TRUE;
        else
            return FALSE;
    }
}

if ( ! function_exists('is_ent'))
{
    function is_ent($uid)
    {
        $ci=& get_instance();
	$ci->load->database(); 
 
	$sql = 'SELECT IFNULL(COUNT(user_role_usermail),"0") as is_ent FROM (`user_role`) LEFT JOIN `user` ON `user`.`user_mail` = `user_role`.`user_role_usermail` WHERE `user`.`user_id`='.$uid.' AND `user_role_roleid`=4 GROUP BY `user_role_usermail`'; 
	$query = $ci->db->query($sql);
	$row = $query->result();
        if(isset($row[0]->is_ent))
            return TRUE;
        else
            return FALSE;
    }
}

if ( ! function_exists('is_jou'))
{
    function is_jou($uid)
    {
        $ci=& get_instance();
	$ci->load->database(); 
 
	$sql = 'SELECT IFNULL(COUNT(user_role_usermail),"0") as is_jou FROM (`user_role`) LEFT JOIN `user` ON `user`.`user_mail` = `user_role`.`user_role_usermail` WHERE `user`.`user_id`='.$uid.' AND `user_role_roleid`=5 GROUP BY `user_role_usermail`'; 
	$query = $ci->db->query($sql);
	$row = $query->result();
        if(isset($row[0]->is_jou))
            return TRUE;
        else
            return FALSE;
    }
}
 

?>