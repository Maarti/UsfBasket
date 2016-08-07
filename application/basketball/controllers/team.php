<?php
class Team extends CI_Controller
{
     public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('team_model');
        $this->load->model('menu_model');  
        
        $this->data['session']=$this->session->all_userdata();
        $this->data['adminMenu'] = $this->menu_model->getAdminMenu();        
        $this->data['menus']= $this->menu_model->getTopMenu();
    }
        
    function index()
    {
        redirect('team/team_list', 'refresh');
    }
    
    function team_list()
    {
        $this->data['teams'] = $this->team_model->getAllTeams();
        $this->load->view('site/head');
        $this->load->view('site/header',$this->data);
        $this->load->view('team/team_list',$this->data);
        $this->load->view('site/footer');
    }
    
    function view($tid=0)
    {
        $this->data['this_team']= $this->team_model->getTeam($tid);
        $this->data['this_members']= $this->team_model->getMembers($tid);
        
        if (!empty($this->data['this_team'])) { // si l'équipe existe bien
            $this->load->view('site/head');
            $this->load->view('site/header',$this->data);
            $this->load->view('team/view',$this->data);
            $this->load->view('site/footer');
        }
        else // si l'équipe n'existe pas
            redirect('team/team_list', 'refresh');
    } 
}
?>
                