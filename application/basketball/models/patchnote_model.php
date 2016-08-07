<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Patchnote_model extends CI_Model
{
    
    //Renvoie tous les patchnotes
    public function getAllPatchnotes(){
        return $this->db->query('
            SELECT patchnote_body,patchnote_version, DATE_FORMAT(patchnote_date,"%d/%m/%Y") as patchnote_formated_date
            FROM patchnote
            WHERE patchnote_version != "draft"
            ORDER BY patchnote_version DESC')->result();
    }
            
    // Renvoie le patchnote brouillon
    public function getDraftPatchnote(){
        return $this->db->from('patchnote')
                        ->where('patchnote_version','draft')
                        ->get()
                        ->result();
    }
    
    //Créer un patchnote (ou met-à-jour le brouillon si $version=draft)
    public function createPatchnote($version,$body,$author){
        $insert_patchnote=array('patchnote_version' => $version,
                                'patchnote_body' => $body,
                                'patchnote_author' => $author);
                    
        if($version=='draft'){
            $this->db->where('patchnote_version', 'draft');
            return $this->db->update('patchnote', $insert_patchnote); 
        }else{
            return $this->db->insert('patchnote', $insert_patchnote);
        }
    }
    
    //Renvoie le dernier patchnote (affiché en page d'accueil)
    public function getLatestPatchnote(){
        return $this->db->query('
            SELECT patchnote_body,patchnote_version, DATE_FORMAT(patchnote_date,"%d/%m/%Y") as patchnote_formated_date
            FROM patchnote
            WHERE patchnote_version != "draft"
            ORDER BY patchnote_version DESC
            LIMIT 1')->result();
    }
    
    
            
}