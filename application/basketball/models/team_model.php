<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Team_model extends CI_Model
{
    // Renvoie toutes les équipes non supprimées et leur nombre de joueurs
    public function getAllTeams(){
        $query = $this->db->query('
            SELECT `team_id`, `team_name`, `team_category`, `team_label`, `category_name`, IFNULL(COUNT(user_deleted=0),"0") as nb_user
            FROM (`team`)
            JOIN `category` ON `category`.`category_id` = `team`.`team_category`
            LEFT JOIN `user` ON `user`.`user_team` = `team`.`team_id`
            WHERE `team_deleted`=0
            GROUP BY `team_id`')->result();
        return $query;
    }
    
    // Renvoie les id et noms des équipes non supprimées (pour les listes déroulantes)
    public function getListTeams(){
        return $this->db->select('team_id,team_name')
                            ->from('team')
                            ->where('team_deleted', 0)
                            ->get()
                            ->result();
    }
    
    // renvoie l'équipe passée en paramètre
    public function getTeam($tid){
        $query = $this->db->query('
            SELECT `team_id`, `team_name`, `category_name`
            FROM (`team`)
            JOIN `category` ON `category`.`category_id` = `team`.`team_category`
            WHERE `team_deleted`=0
            AND `team_id`='.$tid)->result();
        return $query;
    }
    
    // Renvoie tous les membres de l'équipe passée en paramètre
    public function getMembers($tid){
        $query = $this->db->query('
            SELECT user_id, user_firstname, user_lastname
            FROM user
            JOIN team ON user.user_team=team.team_id
            WHERE team_id='.$tid.' AND user_deleted=0
            AND user_active=1
            AND team_deleted=0
            ORDER BY user_lastname')->result();
        return $query;
    }
    
    // Vérifie si le nom d'une équipe est déjà utilisé (parmis les équipes non supprimées), renvoit un booleen
    public function teamExists($team_name){
        $name_exists=FALSE;
        $name_exists_query = $this->db->get_where('team', array('team_name' => $team_name, 'team_deleted' => 0));
        foreach ($name_exists_query->result() as $row)
        {
            $name_exists=TRUE;
        }
        return $name_exists;
    }
    
    // Supprime une équipe (deleted=1) passée en paramètre
    public function deleteTeam($tid){
        $delete_this_team = array('team_deleted' => '1');
        $this->db->where('team_id', $tid);
        return $this->db->update('team', $delete_this_team);
    }

    // édite l'équipe (dont le $tid est passé en paramètre) avec les autres paramètres
    public function editTeam($tid, $name, $label, $category){
        $edit_this_team = array('team_name' => $name, 'team_label' => $label, 'team_category' => $category);
        $this->db->where('team_id', $tid);
        return $this->db->update('team', $edit_this_team);
    }
    
    // Créer une team à partir des paramètres
    public function createTeam($name,$label,$category){
        $team_create_datas_submited = array(
                    'team_name' => $name ,
                    'team_label' => $label ,
                    'team_category' => $category); 

        return $this->db->insert('team', $team_create_datas_submited);
    }
    
}
       
?>