<?php
class Admin extends CI_Controller
{
     public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('team_model');
        $this->load->model('category_model');
        $this->load->model('place_model');
        $this->load->model('menu_model');
        $this->load->model('match_model');        
        $this->load->helper('countuser');
        $this->load->library('form_validation');
        $this->load->library('input');
        
        $this->data['session']=$this->session->all_userdata();        
        $this->data['menus']= $this->menu_model->getTopMenu();
        $this->data['adminMenu'] = $this->menu_model->getAdminMenu();
        $this->data['teams'] = $this->team_model->getAllTeams();
        $this->data['categories']= $this->category_model->getAllCategories();
        $this->data['places']= $this->place_model->getAllPlaces();
    }
        
    function index()
    {
        redirect('admin/menu', 'refresh');
    }
    
    function menu()
    {
        if($this->session->userdata('rank')>=90)
        {
            $this->load->view('site/head');
            $this->load->view('site/header',$this->data);
            $this->load->view('admin/menu');
            $this->load->view('site/footer');
        } else {
            redirect('site/homepage', 'refresh');
        }
    }
    
    function team()
    {
        if($this->session->userdata('rank')>=90)
        {
            $this->load->view('site/head');
            $this->load->view('site/header',$this->data);
            $this->load->view('admin/team',$this->data);
            $this->load->view('site/footer');
        } else {
            redirect('site/homepage', 'refresh');
        }        
    } 
    
    function team_create()
    {
        if($this->session->userdata('rank')>=90)
        {
            $this->load->view('site/head');
            $this->load->view('site/header',$this->data);
            $this->load->view('admin/team_create',$this->data);
            $this->load->view('site/footer');
        } else {
            redirect('site/homepage', 'refresh');
        }            
    } 
    
    function team_create_submit()
    {   
        if($this->session->userdata('rank')>=90)
        {
            $this->form_validation->set_rules('team_name_input', '"Nom"', 'required|min_length[2]|max_length[32]|xss_clean|callback_teamname_check');
            $this->form_validation->set_rules('team_label_input', '"Label"', 'required|min_length[2]|max_length[15]|xss_clean');
            $this->form_validation->set_rules('team_category_select', '"Catégorie"', 'required|xss_clean');

            if($this->form_validation->run())
            {
                //  Le formulaire est valide
                $name = $this->input->post('team_name_input');
                $label = $this->input->post('team_label_input');
                $category = $this->input->post('team_category_select');

                $this->team_model->createTeam($name,$label,$category); 
                redirect('admin/team', 'refresh');
            }
            else
            {
                //  Le formulaire est invalide ou vide
                $this->load->view('site/head');
                $this->load->view('site/header',$this->data);
                $this->load->view('admin/team_create');
                $this->load->view('site/footer');
            }
        } else {
            redirect('site/homepage', 'refresh');
        }
    }
    
    function teamname_check($team_name)    // fonction utilisée dans le validator name de team_create pour vérifier si un nom est déjà présent dans la BDD
    {
        if($this->session->userdata('rank')>=90)
        {
            $name_exists = $this->team_model->teamExists($team_name);
            if ($name_exists)
            {
                    $this->form_validation->set_message('teamname_check', '<span class="form-error">Une équipe porte déjà ce nom.</span>');
                    return FALSE;
            }
            else
            {
                    return TRUE;
            }
        } else {
            redirect('site/homepage', 'refresh');
        }
    }
    
    function team_delete($tid)
    {   
        if($this->session->userdata('rank')>=90)
        {
            $this->team_model->deleteTeam($tid);
            redirect('admin/team', 'refresh');
        } else {
            redirect('site/homepage', 'refresh');
        }            
    }
    
    function team_edit($tid){
       if($this->session->userdata('rank')>=90)
        {
            $this->form_validation->set_rules('team_name_input'.$tid, '"Nom"', 'required|min_length[2]|max_length[32]|xss_clean|callback_teamname_check');
            $this->form_validation->set_rules('team_label_input'.$tid, '"Label"', 'required|min_length[2]|max_length[15]|xss_clean');
            $this->form_validation->set_rules('team_category_select'.$tid, '"Catégorie"', 'required|xss_clean');

            if($this->form_validation->run())
            {
                //  Le formulaire est valide
                $name = $this->input->post('team_name_input'.$tid);
                $label = $this->input->post('team_label_input'.$tid);
                $category = $this->input->post('team_category_select'.$tid);

                $this->team_model->editTeam($tid,$name,$label,$category);
                
                redirect('admin/team?edit=success', 'refresh');
            }
            else
            {
                redirect('admin/team?edit=error', 'refresh');
            }
        } else {
            redirect('site/homepage', 'refresh');
        }            
    }
    
   
    function match($home=1,$rows_to_ignore=0)
    {
        if($this->session->userdata('rank')>=90 AND ($home==0 OR $home==1))
        {
            $this->load->library('pagination');
            $config['base_url'] = site_url('admin/match/'.$home);
            $config['total_rows'] = $this->match_model->getFuturMatchsNumber($home);
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
            $this->data['usersformatchs']= $this->match_model->getAllFuturMatchs($config['per_page'],$rows_to_ignore,$home);

            $this->load->view('site/head');
            $this->load->view('site/header',$this->data);
            $this->load->view('admin/match',$this->data);
            $this->load->view('site/footer');
        } else {
            redirect('site/homepage', 'refresh');
        }            
    } 
    
        
    function match_create()
    {
        if($this->session->userdata('rank')>=90)
        {
            $this->load->view('site/head');
            $this->load->view('site/header',$this->data);
            $this->load->view('admin/match_create',$this->data);
            $this->load->view('site/footer');
        } else {
            redirect('site/homepage', 'refresh');
        }            
    } 
    
    function match_create_submit()
    {   
        if($this->session->userdata('rank')>=90)
        {
            $this->form_validation->set_rules('match_home_radios', '"Type de match"', 'required|exact_length[1]|xss_clean|numeric');
            $this->form_validation->set_rules('match_oponent_input', '"Adversaire"', 'required|min_length[2]|max_length[40]|xss_clean');
            $this->form_validation->set_rules('match_team_select', '"Équipe"', 'required|min_length[1]|max_length[5]|xss_clean|numeric');
            $this->form_validation->set_rules('match_date_input', '"Date"', 'required|xss_clean');
            $this->form_validation->set_rules('match_hour_input', '"Heure"', 'required|exact_length[5]|xss_clean');
            $this->form_validation->set_rules('match_place_select', '"Lieu"', 'required|max_length[5]|xss_clean|a');
            if($this->input->post('match_place_select')=='other'){
                $this->form_validation->set_rules('match_new_place_input', '"Nouveau lieu"', 'required|min_length[2]|max_length[40]|xss_clean|callback_placeNameIsUnique');
            }else{
                $this->form_validation->set_rules('match_new_place_input', '"Nouveau lieu"', 'required|min_length[2]|max_length[40]|xss_clean');
            }
            $this->form_validation->set_rules('match_new_placeAddress_input', '"Adresse"', 'required|min_length[2]|max_length[200]|xss_clean');
            $this->form_validation->set_rules('match_ar_radios', '"Arbitre(s)"', 'required|exact_length[1]|xss_clean|numeric');
            $this->form_validation->set_rules('match_cr_radios', '"Chronométreur"', 'required|exact_length[1]|xss_clean|numeric');
            $this->form_validation->set_rules('match_mr_radios', '"Marqueur"', 'required|exact_length[1]|xss_clean|numeric');
            
            if($this->form_validation->run())
            {
                //  Le formulaire est valide
                $home = $this->input->post('match_home_radios');
                $oponent = $this->input->post('match_oponent_input');
                $team = $this->input->post('match_team_select');
                
                $date = $this->input->post('match_date_input');
                    $year = substr($date, 6);
                    $month = substr($date, 3,-5);
                    $day = substr($date, 0,-8);
                    $date = $year.'-'.$month.'-'.$day;
                    
                    $hour = $this->input->post('match_hour_input');
                    $hour = substr($hour, 0,-3).':'.substr($hour, 3).':00';
                    $date=$date.' '.$hour;
                
                $place = $this->input->post('match_place_select');
                $newPlace = $this->input->post('match_new_place_input');
                $newPlaceAddress = $this->input->post('match_new_placeAddress_input');
                $ar = $this->input->post('match_ar_radios');
                $mr = $this->input->post('match_mr_radios');
                $cr = $this->input->post('match_cr_radios');
                $uid =  $this->session->userdata('id'); 
                
                $this->match_model->createMatch($home,$oponent,$team,$date,$place,$newPlace,$newPlaceAddress,$ar,$mr,$cr,$uid);
                redirect('admin/match', 'refresh');
            }
            else
            {
                //  Le formulaire est invalide ou vide
                $this->load->view('site/head');
                $this->load->view('site/header',$this->data);
                $this->load->view('admin/match_create');
                $this->load->view('site/footer');
            }
        } else {
            redirect('site/homepage', 'refresh');
        }
    }
    
    function placeNameIsUnique($placeName){
            if ($this->place_model->placeExists($placeName)){
                $this->form_validation->set_message('placeNameIsUnique', '<span class="form-error">Ce lieu existe déjà.</span>');
                return FALSE;
            }else{
                return TRUE;
            }
    }
    
    function match_moderation($this_state,$mid,$uid,$role){
        if($this->session->userdata('logged_in')) { // L'utilisateur doit être connecté et administrateur
            if($this->session->userdata('rank')>=90) {
                if($this_state=='confirm')
                    $state=1;
                else
                    $state=3;
                $this->match_model->setMatchModeration($uid,$mid,$role,$state);
                redirect('match/view/'.$mid, 'refresh');
            }
            else // si l'utilisateur n'est pas admin
            {
                redirect('match/match_list', 'refresh');
            }
        }
        else // si l'utilisateur n'est pas connecté
        {
            redirect('user/connect', 'refresh');
        }
    }
    
    
    function match_edit($mid){
        if($this->session->userdata('rank')>=90)
        {
            $this->data['thisMatch'] = $this->match_model->getThisMatch($mid);
            if (!empty($this->data['thisMatch'])){
                $this->load->view('site/head');
                $this->load->view('site/header',$this->data);
                $this->load->view('admin/match_edit',$this->data);
                $this->load->view('site/footer');
            } else {
                redirect('admin/match', 'refresh');
            }            
        } else {
            redirect('site/homepage', 'refresh');
        }        
    } 
    
    
    function match_edit_submit($mid)
    {   
        if($this->session->userdata('rank')>=90)
        {
            $this->form_validation->set_rules('match_oponent_input', '"Adversaire"', 'required|min_length[2]|max_length[40]|xss_clean');
            $this->form_validation->set_rules('match_team_select', '"Équipe"', 'required|min_length[1]|max_length[5]|xss_clean|numeric');
            $this->form_validation->set_rules('match_date_input', '"Date"', 'required|xss_clean');
            $this->form_validation->set_rules('match_hour_input', '"Heure"', 'required|exact_length[5]|xss_clean');
            $this->form_validation->set_rules('match_place_select', '"Lieu"', 'required|exact_length[1]|xss_clean|numeric');
            $this->form_validation->set_rules('match_ar_radios', '"Arbitre(s)"', 'required|exact_length[1]|xss_clean|numeric');
            $this->form_validation->set_rules('match_cr_radios', '"Chronométreur"', 'required|exact_length[1]|xss_clean|numeric');
            $this->form_validation->set_rules('match_mr_radios', '"Marqueur"', 'required|exact_length[1]|xss_clean|numeric');
            
            if($this->form_validation->run())
            {
                //  Le formulaire est valide

                $oponent = $this->input->post('match_oponent_input');
                $team = $this->input->post('match_team_select');
                
                $date = $this->input->post('match_date_input');
                    $year = substr($date, 6);
                    $month = substr($date, 3,-5);
                    $day = substr($date, 0,-8);
                    $date = $year.'-'.$month.'-'.$day;
                    
                    $hour = $this->input->post('match_hour_input');
                    $hour = substr($hour, 0,-3).':'.substr($hour, 3).':00';
                    $date=$date.' '.$hour;
                
                $place = $this->input->post('match_place_select');
                $ar = $this->input->post('match_ar_radios');
                $mr = $this->input->post('match_mr_radios');
                $cr = $this->input->post('match_cr_radios');
                $author = $this->session->userdata('id');

                $this->match_model->editMatch($mid, $oponent, $team, $date, $place, $ar, $mr, $cr, $author);
                redirect('admin/match', 'refresh');
            }
            else
            {
                //  Le formulaire est invalide ou vide
                $this->load->view('site/head');
                $this->load->view('site/header',$this->data);
                $this->load->view('admin/match_create');
                $this->load->view('site/footer');
            }
        } else {
            redirect('site/homepage', 'refresh');
        }
    }
    
    
    function match_delete($mid)
    {   
        if($this->session->userdata('rank')>=90)
        {
            $this->match_model->deleteMatch($mid);
            redirect('admin/match', 'refresh');
        } else {
            redirect('site/homepage', 'refresh');
        }            
    }
    
    function dailymsg(){
         if($this->session->userdata('logged_in')) { // L'utilisateur doit être connecté et super-administrateur
            if($this->session->userdata('rank')>=90) {
                //$this->load->model('dailymessage_model');
                //$this->data['last_dailymsg']= $this->dailymessage_model->getLastDailyMsgs();
                $this->load->view('site/head');
                $this->load->view('site/header',$this->data);
                $this->load->view('admin/dailymsg',$this->data);
                $this->load->view('site/footer');
            }else{ // si l'utilisateur n'est pas admin
                redirect('site/homepage', 'refresh');
            }
         }else{ // si l'utilisateur n'est pas connecté
            redirect('user/connect', 'refresh');
        }
    }
    
    function dailymsg_submit(){
        if($this->session->userdata('logged_in')) { // L'utilisateur doit être connecté et super-administrateur
            if($this->session->userdata('rank')>=90) {
                $this->load->model('dailymessage_model');
                $this->form_validation->set_rules('dailymsg_end_input', '"Date"', 'max_length[15]|required|xss_clean');
                $this->form_validation->set_rules('dailymsg_type', '"Type"', 'numeric|exact_length[1]|required|xss_clean');
                $this->form_validation->set_rules('dailymsg_body', '"Message"', 'required|min_length[10]|max_length[300]|xss_clean');

                 if($this->form_validation->run()){
                    $uid = $this->session->userdata('id');
                    $body = $this->input->post('dailymsg_body');
                    $type = $this->input->post('dailymsg_type');
                        $end = $this->input->post('dailymsg_end_input');
                        $year = substr($end, 6);
                        $month = substr($end, 3,-5);
                        $day = substr($end, 0,-8);
                    $end = $year.'-'.$month.'-'.$day;

                    $this->dailymessage_model->createDailyMessage($uid,$body,$type,$end);
                    redirect('admin/dailymsg?insert=success');
                 }else{
                    //$this->data['last_dailymsg']= $this->dailymessage_model->getLastDailyMsgs();
                    $this->load->view('site/head');
                    $this->load->view('site/header',$this->data);
                    $this->load->view('admin/dailymsg',$this->data);
                    $this->load->view('site/footer');
                 }
            }else{ // si l'utilisateur n'est pas admin
                redirect('admin/menu?error=sa');
            }
         }else{ // si l'utilisateur n'est pas connecté
            redirect('user/connect');
        }
    }
    
    function patchnote(){
         if($this->session->userdata('logged_in')) { // L'utilisateur doit être connecté et super-administrateur
            if($this->session->userdata('rank')>90) {
                $this->load->model('patchnote_model');
                $this->data['draft_patchnote']= $this->patchnote_model->getDraftPatchnote();
                $this->load->view('site/head');
                $this->load->view('site/header',$this->data);
                $this->load->view('admin/patchnote',$this->data);
                $this->load->view('site/footer');
            }else{ // si l'utilisateur n'est pas admin
                redirect('admin/menu?error=sa', 'refresh');
            }
         }else{ // si l'utilisateur n'est pas connecté
            redirect('user/connect', 'refresh');
        }
    }
    
    function patchnote_submit(){
        if($this->session->userdata('logged_in')) { // L'utilisateur doit être connecté et super-administrateur
            if($this->session->userdata('rank')>90) {
                $this->load->model('patchnote_model');
                $this->form_validation->set_rules('patchnote_version_input', '"Version"', 'required|min_length[5]|max_length[6]|xss_clean');
                $this->form_validation->set_rules('patchnote_body_textarea', '"Note de mise-à-jour"', 'required|min_length[1]|xss_clean');

                 if($this->form_validation->run()){
                    $version = $this->input->post('patchnote_version_input');
                    $body = $this->input->post('patchnote_body_textarea');
                    $author=$this->session->userdata('id');

                    $this->patchnote_model->createPatchnote($version,$body,$author);
                    redirect('admin/patchnote?insert=success');
                 }else{
                    $this->data['draft_patchnote']= $this->patchnote_model->getDraftPatchnote();
                    $this->load->view('site/head');
                    $this->load->view('site/header',$this->data);
                    $this->load->view('admin/patchnote',$this->data);
                    $this->load->view('site/footer');
                 }
            }else{ // si l'utilisateur n'est pas admin
                redirect('admin/menu?error=sa');
            }
         }else{ // si l'utilisateur n'est pas connecté
            redirect('user/connect');
        }
    }

    function logs($rows_to_ignore=0){
         if($this->session->userdata('logged_in')) { // L'utilisateur doit être connecté et administrateur
            if($this->session->userdata('rank')>=90) {
                $this->load->model('log_model');
                $this->load->library('pagination');
                $config['base_url'] = site_url('admin/logs');
                $config['total_rows'] = $this->log_model->countLogs();
                $config['per_page'] = 20;
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
                $config['display_pages'] = FALSE;
                $config['last_link'] = FALSE;
                $config['first_link'] = FALSE;
                $this->pagination->initialize($config); 
                $this->data['pagination']=$this->pagination->create_links();
                
                $this->data['logs']= $this->log_model->getAllLogs($config['per_page'],$rows_to_ignore);
                
                $this->load->view('site/head');
                $this->load->view('site/header',$this->data);
                $this->load->view('admin/logs',$this->data);
                $this->load->view('site/footer');
            }else{ // si l'utilisateur n'est pas admin
                redirect('site/homepage', 'refresh');
            }
         }else{ // si l'utilisateur n'est pas connecté
            redirect('user/connect', 'refresh');
        }
    }
    
    function user(){
        if($this->session->userdata('logged_in')) { // L'utilisateur doit être connecté et super-administrateur
            if($this->session->userdata('rank')>=90) {
                $this->load->model('user_model');
                $this->load->model('role_model');
                $this->data['roles']= $this->role_model->getAllRoles();
                $this->data['activeUsers']= $this->user_model->adminGetAllUsers(TRUE);
                $this->data['inactiveUsers']= $this->user_model->adminGetAllUsers(FALSE);
                $this->load->view('site/head');
                $this->load->view('site/header',$this->data);
                $this->load->view('admin/user',$this->data);
                $this->load->view('site/footer');
            }else{ // si l'utilisateur n'est pas admin
                redirect('site/homepage', 'refresh');
            }
        }else{ // si l'utilisateur n'est pas connecté
            redirect('user/connect', 'refresh');
        }
    }
    
    function activateUser($code){
        if($this->session->userdata('logged_in')) { // L'utilisateur doit être connecté et super-administrateur
            if($this->session->userdata('rank')>=90) {
                $this->load->model('user_model');
                $this->user_model->activeAccount($code);
                redirect('admin/user', 'refresh');
            }else{ // si l'utilisateur n'est pas admin
                redirect('site/homepage', 'refresh');
            }
        }else{ // si l'utilisateur n'est pas connecté
            redirect('user/connect', 'refresh');
        }
    }
}
?>
                