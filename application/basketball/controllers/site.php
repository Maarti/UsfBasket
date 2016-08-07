<?php
class Site extends CI_Controller
{
    
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('menu_model');  
        $this->load->helper('assets');
        $this->load->helper('string');     
        $this->load->database();
        $this->load->library('encrypt');
        
        
        $this->data['adminMenu'] = $this->menu_model->getAdminMenu();
        $this->data['menus']= $this->menu_model->getTopMenu();
        $this->data['session']=$this->session->all_userdata();
    }
    
    function index()
    {
        redirect('site/homepage', 'refresh');
    }
    
    function homepage(){
        $this->load->model('patchnote_model');
        $this->load->library('match');
        $this->data['patchnotes']= $this->patchnote_model->getLatestPatchnote();
        $this->data['next_match'] = $this->match->get_next_match();
        $this->load->view('site/head');
        $this->load->view('site/header',$this->data);
        $this->load->view('site/homepage',$this->data);
        $this->load->view('site/footer');
    }

    function contact(){
        $this->load->view('site/head');
        $this->load->view('site/header',$this->data);
        $this->load->view('site/contact');
        $this->load->view('site/footer');
    }
    
    function patchnote(){
        $this->load->model('patchnote_model');
        $this->data['patchnotes']= $this->patchnote_model->getAllPatchnotes();
        $this->load->view('site/head');
        $this->load->view('site/header',$this->data);
        $this->load->view('site/patchnote',$this->data);
        $this->load->view('site/footer');
    }

                
}
?>
                