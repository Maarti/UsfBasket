<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Category_model extends CI_Model
{
    public function getAllCategories(){
        $query = $this->db->select('category_id,category_name')
                        ->from('category')
                        ->get()
                        ->result();
        return $query;
    }
}