<?php
class Match extends CI_Controller
{
        
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('menu_model');
        $this->load->model('match_model');
        $this->load->helper('countuser');        
        $this->load->library('form_validation');
        $this->load->library('input');
        
        $this->data['session']=$this->session->all_userdata();        
        $this->data['adminMenu'] = $this->menu_model->getAdminMenu();
        $this->data['menus']= $this->menu_model->getTopMenu();
        
    }
    
    function index()
    {
        redirect('site/homepage', 'refresh');
    }
    
    function homematch_list($rows_to_ignore=0)
    {
        $this->load->library('pagination');
        $config['base_url'] = site_url('match/homematch_list');
        $config['total_rows'] = $this->match_model->getFuturMatchsNumber(1);
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;
        $config['use_page_numbers'] = FALSE;
        $config['full_tag_open'] = '<div class="pagination"><ul>';
        $config['full_tag_close'] = '</ul></div>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['display_pages'] = TRUE;
        $config['last_link'] = FALSE;
        $config['first_link'] = FALSE;
        $this->pagination->initialize($config); 
        $this->data['pagination']=$this->pagination->create_links();
        
        $this->data['usersformatchs']= $this->match_model->getAllFuturMatchs($config['per_page'],$rows_to_ignore,1);
        
        $this->load->view('site/head');
        $this->load->view('site/header',$this->data);
        $this->load->view('match/homematch_list',$this->data);
        $this->load->view('site/footer');
    }
    
    function mymatch_list(){
        if($this->session->userdata('logged_in')) {
            if($this->session->userdata('team_id')!=NULL) {
                $this->load->model('user_model');
                $this->data['teamPlayers']=$this->user_model->getTeamPlayers($this->session->userdata('team_id'));
                $this->data['teamMatchs']=$this->match_model->getFuturTeamMatchs($this->session->userdata('team_id'));
                $this->data['registerMatrix']=$this->match_model->getTeamRegisterMatch($this->session->userdata('team_id'));
                $this->load->view('site/head');
                $this->load->view('site/header',$this->data);
                $this->load->view('match/mymatch_list',$this->data);
                $this->load->view('site/footer');
            }else{  // Si l'utilisateur n'a pas d'équipe
                redirect('site/homepage?error=mustHaveTeam', 'refresh');
            }
        }else{ // si l'utilisateur n'est pas connecté
            redirect('user/connect/mustRegister', 'refresh');
        }
    }
    
    function view($mid=0)
    {
        if($this->session->userdata('logged_in')) { // L'utilisateur doit être connecté
            $this->data['this_match']= $this->match_model->getThisMatch($mid,TRUE);
            $this->data['this_matchs_a']=$this->match_model->getMatchArbitres($mid);
            $this->data['this_matchs_m']=$this->match_model->getMatchMarqueurs($mid);
            $this->data['this_matchs_c']= $this->match_model->getMatchChrono($mid);
            if (!empty($this->data['this_match'])){ // Si le match existe
                $this->load->view('site/head');
                $this->load->view('site/header',$this->data);
                $this->load->view('match/view',$this->data);
                $this->load->view('site/footer');
            }else{ // si le match n'existe pas
                redirect('match/homematch_list', 'refresh');
            }
        }else{ // si l'utilisateur n'est pas connecté
            redirect('user/connect/mustRegister', 'refresh');
        }
    }

    function register()
    {
        if($this->session->userdata('logged_in')) { // L'utilisateur doit être connecté
            $this->form_validation->set_rules('uid_input', '"User id"', 'required|numeric|max_length[4]|xss_clean|callback_entry_exists');
            $this->form_validation->set_rules('mid_input', '"Match id"', 'required|numeric|max_length[5]|xss_clean|callback_matchid_check');
            $this->form_validation->set_rules('registered_role_input', '"Registered role"', 'required|max_length[8]|xss_clean|alpha|callback_role_check');
            if($this->form_validation->run())
            {
                //  Le formulaire est valide
                $uid = $this->input->post('uid_input');
                $mid = $this->input->post('mid_input');
                $role = $this->input->post('registered_role_input');
                $cancel_input = $this->input->post('cancel_input');
                if($cancel_input=='TRUE')
                    $this->match_model->cancelMatchRegistration($uid,$mid,$role);
                else
                    $this->match_model->registerToMatch($mid,$uid,$role);

                redirect('match/view/'.$mid, 'refresh');
            }
            else // Le formulaire est invalide
            {
                redirect('match/homematch_list?error=match_register', 'refresh');
            }
        }else{ // L'utilisateur n'est pas connecté
                redirect('user/connect/mustRegister', 'refresh');
        }
    }

    function matchid_check($mid)    // fonction utilisée dans le validator de register pour vérifier si le match existe et est valide
    {
        return $this->match_model->checkMatchId($mid);
    }
    
    function role_check($role)  // fonction utilisée dans le validator de register pour vérifier si le role correspond aux types attendus
    {
        $valid_role=FALSE;
        if($role=='arbitre'|$role=='marqueur'|$role=='chrono')
            $valid_role=TRUE;
        return $valid_role;
    }
    
    function entry_exists()    // fonction utilisée dans le validator de register pour savoir si l'entrée n'est pas déjà présente dans la base
    {
        $uid = $this->input->post('uid_input');
        $mid = $this->input->post('mid_input');
        $role = $this->input->post('registered_role_input');
        $cancel_input = $this->input->post('cancel_input');
            
        if(($cancel_input==TRUE))
                    return TRUE;                    
        else{
            if ($this->match_model->entryExistsRegister($mid,$uid,$role)){
                return FALSE;
            }else{
                return TRUE;
            }
        }
    }
    
    function setPresence($mid=0,$state=-1){
        if($this->session->userdata('logged_in')) { // L'utilisateur doit être connecté
            if($this->match_model->matchPresenceRegisterable($mid) && ($state==0 OR $state==1)){ // Si le match existe, qu'il n'a pas déjà eu lieu et que $state a une valeur correcte
                $this->match_model->setMatchPresence($this->session->userdata('id'),$mid,$state);
                redirect('match/mymatch_list', 'refresh');
            }else{ // Les paramètres URL ne sont pas valide
                redirect('match/mymatch_list?error=invalidParam', 'refresh');
            }
        }else{ // L'utilisateur n'est pas connecté
            redirect('user/connect/mustRegister', 'refresh');
        }
    }
}
?>
                