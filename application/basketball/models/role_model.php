<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Role_model extends CI_Model
{

    //Retourne tous les roles et leurs infos
    public function getAllRoles(){
        return $this->db->get('role')->result();
    }



}