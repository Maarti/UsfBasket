<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Place_model extends CI_Model
{
    //Retourne toutes les places
    public function getAllPlaces(){
        $query = $this->db->from('place')
                         ->get()
                         ->result();
        return $query;
    }
    
    //Vérifie si le nom d'une place n'existe pas déjà (validator création match)
    public function placeExists($name){
        $query = $this->db->get_where('place', array('place_name' => $name));
        if ($query->num_rows() > 0)
            return TRUE;
        else
            return FALSE;
    }
    
    //Créer une place à partir des paramètres
    public function createPlace($name,$address){
         $place_create_data = array(
            'place_name' => $name,
            'place_address' => $address); 

         return $this->db->insert('place', $place_create_data); 
    }
    
    //Retourne l'id d'une place à partir de son nom
    public function getPlaceId($name){
        $query = $this->db->get_where('place', array('place_name' => $name));
        if ($query->num_rows() < 0)
            return FALSE;
        else
            $query=$query->result();
            return $query[0]->place_id;
    }
}