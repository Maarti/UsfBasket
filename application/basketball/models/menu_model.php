<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Menu_model extends CI_Model
{
    public function getTopMenu(){
        return array(
           // 'Matchs' => array(                "match/homematch_list" => "Organisation des matchs",                "match/mymatch_list" => "Mes matchs"),
            'Membres'=>'user/user_list',
            'Équipes'=>'team/team_list',
            'Contact'=>'site/contact');
    }
    
    public function getAdminMenu(){
        return array(
            10 => array(
                "link" => "admin/user",
                "label" => "Membres",
                "disabled" => FALSE),
            15 => array(
                "link" => "admin/match",
                "label" => "Matchs",
                "disabled" => FALSE),
            20 => array(
                "link" => "admin/team",
                "label" => "Équipes",
                "disabled" => FALSE),
            25 => array(
                "link" => "admin/dailymsg",
                "label" => "Message du jour",
                "disabled" => FALSE),
            30 => array(
                "link" => "admin/patchnote",
                "label" => "Patchnotes",
                "disabled" => FALSE),
            40 => array(
                "link" => "admin/logs",
                "label" => "Logs",
                "disabled" => FALSE),
            50 => array(
                "link" => "admin/contact",
                "label" => "Contact Admin",
                "disabled" => TRUE),
            60 => array(
                "link" => "admin/stats",
                "label" => "Statistiques",
                "disabled" => TRUE)
            );

    }
}