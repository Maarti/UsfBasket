<?php
class User extends CI_Controller
{
    
    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('menu_model');
        $this->load->model('user_model');
        $this->load->model('role_model');
        $this->load->library('form_validation');
        $this->load->library('input');
        $this->load->library('format_string');
        $this->load->library('user_agent');
        $this->load->library('email');
        
        $this->data['session']=$this->session->all_userdata();        
        $this->data['adminMenu'] = $this->menu_model->getAdminMenu();
        $this->data['menus']= $this->menu_model->getTopMenu();
        $this->data['roles']= $this->role_model->getAllRoles();
    }
    
    function index()
    {
        redirect('user/user_list', 'refresh');
    }
    
    function disconnect()
    {
        $this->session->unset_userdata($this->session->all_userdata());
        redirect('user/connect', 'refresh');
    }

    function connect($msg='')
    {
        switch ($msg) { // Gestion des messages (error,success,info) à afficher en page d'accueil
            case 'ActivatingSuccess':
                $alert='success';
                $message='Activation réussie.<br/>Vous pouvez désormais vous connecter avec vos identifiants.';
                break;
            case 'ActivatingFailed':
                $alert='error';
                $message='Échec de l\'activation.<br/>Ce lien d\'activation ne correspond à aucun compte.<br/>Merci de contacter l\'administrateur grâce au menu "Contact" si le porblème persiste.';
                break;
            case 'RegisterSuccess':
                $alert='success';
                $message='Inscription réussie.<br/>Vous devez maintenant activer votre compte en cliquant sur le lien qui vous a été envoyé par e-mail.';
                break;
            case 'mustRegister':
                $alert='error';
                $message='Vous devez d\'abord vous connecter pour faire cela.';
                break;
            default:
                $alert=NULL;
                $message=NULL;
        }
        if(!is_null($alert)&&!is_null($message))
            $this->data['msg']= '<div class="row-fluid"><div class="span12"><p class="alert alert-'.$alert.'">'.$message.'</p></div></div>';
        $this->load->view('site/head');
        $this->load->view('site/header',$this->data);
        $this->load->view('user/connect');
        $this->load->view('site/footer');
    }
    
    function connect_submit()
    {
        $this->form_validation->set_rules('connect_mail_input', '"E-mail"', 'required|valid_email|max_length[40]|xss_clean|callback_userconnect_check|callback_account_is_active');
        $this->form_validation->set_rules('connect_pass_input', '"Mot de passe"', 'required|min_length[2]|max_length[30]|xss_clean');
        $this->load->model('log_model');
        if($this->form_validation->run())
        {
            //  Le formulaire est valide
            $userinfo= $this->user_model->getUserFromMail($this->input->post('connect_mail_input'));
           
            // On crée les variables de session
            $sessiondata = array(
                   'id'  => $userinfo[0]->user_id,
                   'mail'=> $userinfo[0]->user_mail,
                   'lastname'=>$userinfo[0]->user_lastname,
                   'firstname'=>$userinfo[0]->user_firstname,
                   'rank'=>$userinfo[0]->user_rank,
                   'team_id'=>$userinfo[0]->user_team,
                   'team_label'=>$userinfo[0]->team_label,
                   'team_name'=>$userinfo[0]->team_name,
                   'logged_in' => TRUE
               );
            $this->session->set_userdata($sessiondata);
            $this->data['session']=$this->session->all_userdata();
            
            // On enregistre les logs de la connection dans la DB
            $ip=$this->input->ip_address();
            $uid=$userinfo[0]->user_id;
            $this->load->library('user_agent');
            if ($this->agent->is_browser())
            {
                $agent = 'Browser - '.$this->agent->browser().' '.$this->agent->version();
            }
            elseif ($this->agent->is_robot())
            {
                $agent = 'Robot - '.$this->agent->robot();
            }
            elseif ($this->agent->is_mobile())
            {
                $agent = 'Mobile - '.$this->agent->mobile();
            }
            else
            {
                $agent = 'Unidentified User Agent';
            }
            $platform=$this->agent->platform();
            
            $this->log_model->createLog($uid,$ip,$agent,$platform);
            
            // On met à jour la date de dernière connexion de l'utilisateur
            $this->user_model->updateLastConnect($this->input->post('connect_mail_input'));
            
            redirect('');
            
        }
        else
        {
            //  Le formulaire est invalide ou vide
            $this->load->view('site/head');
            $this->load->view('site/header',$this->data);
            $this->load->view('user/connect');
            $this->load->view('site/footer');
        }

    }
    
    function account_is_active($mail){ // form validation pour savoir si le compte est actif
        if($this->user_model->accountIsActive($mail))
        {
            return TRUE;
        }else{
            $this->form_validation->set_message('account_is_active', '<span class="form-error">Vous n\'avez pas encore activé votre compte ou celui-ci a été supprimé.</span>');
            return FALSE;
        }
    }
        
    function register()
    {
        if($this->session->userdata('logged_in')) { // Si l'utilisateur est déjà connecté
            redirect('', 'refresh');
        }else{
            $this->load->model('team_model');
            $this->data['teams']= $this->team_model->getListTeams();
            $this->load->view('site/head');
            $this->load->view('site/header',$this->data);
            $this->load->view('user/register',$this->data);
            $this->load->view('site/footer');
        }
    }
    
    function register_submit()
    {   
        $this->form_validation->set_rules('user_mail_input', '"E-mail"', 'required|valid_email|max_length[40]|xss_clean|callback_usermail_check');
        $this->form_validation->set_rules('user_pass_input', '"Mot de passe"', 'required|min_length[2]|max_length[30]|xss_clean');
        $this->form_validation->set_rules('user_passconfirm_input', '"Confirmation"', 'required|matches[user_pass_input]|min_length[2]|max_length[30]|xss_clean');
        $this->form_validation->set_rules('user_lastname_input', '"Nom"', 'required|min_length[2]|max_length[40]|regex_match[/^([-a-z_éèàêâùïüë ])+$/i]|xss_clean');
        $this->form_validation->set_rules('user_firstname_input', '"Prénom"', 'required|min_length[2]|max_length[40]|regex_match[/^([-a-z_éèàêâùïüë ])+$/i]|xss_clean');
        $this->form_validation->set_rules('user_cellphone_input', '"N° Port."', 'exact_length[10]|numeric|xss_clean');
        $this->form_validation->set_rules('user_birthdate_input', '"Date de naissance"', 'exact_length[10]|xss_clean');
     
        if($this->form_validation->run())
        {
            //  Le formulaire est valide
            $mail = $this->input->post('user_mail_input');
            $pass = $this->input->post('user_pass_input');
            $lastname = $this->input->post('user_lastname_input');
            $firstname = $this->input->post('user_firstname_input');
            $cellphone = $this->input->post('user_cellphone_input');
            $birthdate = $this->input->post('user_birthdate_input');
                $year = substr($birthdate, 6);
                $month = substr($birthdate, 3,-5);
                $day = substr($birthdate, 0,-8);
                if ($year||$month||$day)
                    $birthdate = $year.'-'.$month.'-'.$day;
                else
                    $birthdate = '0000-00-00';
            if($this->input->post('user_team_select')=="NULL")
                $team=NULL;
            else
                $team = $this->input->post('user_team_select');
            $roles=$this->input->post('user_role_checkboxes');
            $ip=$this->input->ip_address();
           
            $this->user_model->createUser($mail,$pass,$lastname,$firstname,$cellphone,$birthdate,$team,$ip,$roles);
                         
            redirect('user/connect/RegisterSuccess', 'refresh');
        }
        else
        {
            //  Le formulaire est invalide
            $this->load->model('team_model');
            $this->load->view('site/head');
            $this->load->view('site/header',$this->data);
            $this->load->view('user/register');
            $this->load->view('site/footer');
        }
    }

    function usermail_check($user_mail){    // fonction utilisée dans le validator mail de user/register pour vérifier si une adresse mail est déjà présente dans la BDD
        if ($this->user_model->userMailExists($user_mail)) {
            $this->form_validation->set_message('usermail_check', '<span class="form-error">Cette adresse e-mail est déjà utilisée sur le site.</span>');
            return FALSE;
        }else{
            return TRUE;
        }
    }
    
    function userconnect_check(){   // fonction utilisée dans le validator mail de user/connect_submit pour vérifier si le mot de passe est correct
        $inputmail = $this->input->post('connect_mail_input');
        $inputpass = $this->input->post('connect_pass_input');
            
        $checkPassword = $this->user_model->passwordIsCorrect($inputmail,$inputpass);
        if ($checkPassword==0){
            $this->form_validation->set_message('userconnect_check', '<span class="form-error">Adresse e-mail incorrecte ou compte non validé.</span>');
            return FALSE;
        }elseif($checkPassword==1){
            return TRUE;
        }else{
            $this->form_validation->set_message('userconnect_check', '<span class="form-error">Mot de passe incorrect pour cette adresse.</span>');
            return FALSE;
        }
    }
    
    function user_list(){
        $this->data['users']= $this->user_model->getAllUsers();
        $this->load->view('site/head');
        $this->load->view('site/header',$this->data);
        $this->load->view('user/user_list',$this->data);
        $this->load->view('site/footer');
    }
    
    function view($uid=0){
        if($this->session->userdata('logged_in')) {
            if(is_numeric($uid)){
                $this->data['this_user']= $this->user_model->getUser($uid);
                $this->load->model('team_model');
                $this->data['teams']= $this->team_model->getListTeams();


                if (!empty($this->data['this_user'])) {                    
                    if($this->session->userdata('id')==$uid)
                        $this->data['my_profile']=TRUE; // Si l'utilisateur regarde son propre profil, on affichera le menu d'édition du profil
                    else
                        $this->data['my_profile']=FALSE;     
                    
                    $this->load->view('site/head');
                    $this->load->view('site/header',$this->data);
                    $this->load->view('user/view',$this->data);
                    $this->load->view('site/footer');
                }
                else // si l'utilisateur n'existe pas
                    redirect('user/user_list', 'refresh');
            } else { // si l'id n'est pas numérique
                redirect('user/user_list', 'refresh');
            }
        }else{  // Si l'utilisateur n'est pas connecté
            redirect('user/connect/mustRegister');
        }
    }
    
    function editprofile_submit(){   
        if($this->session->userdata('logged_in')) {
            $this->form_validation->set_rules('user_pass_input', '"Mot de passe"', 'required|min_length[2]|max_length[30]|xss_clean|callback_userpass_check');
            $this->form_validation->set_rules('user_newpass_input', '"Nouveau mot de passe"', 'min_length[2]|max_length[30]|xss_clean');
            $this->form_validation->set_rules('user_passconfirm_input', '"Confirmation"', 'matches[user_newpass_input]|min_length[2]|max_length[20]|xss_clean');
            $this->form_validation->set_rules('user_cellphone_input', '"N° Port."', 'exact_length[10]|numeric|xss_clean');
            $this->form_validation->set_rules('user_birthdate_input', '"Date de naissance"', 'exact_length[10]|xss_clean');

            if($this->form_validation->run()) {
                //  Le formulaire est valide
                $pass = $this->input->post('user_pass_input');
                $newpass = $this->input->post('user_newpass_input');
                    if($newpass=='')
                        $newpass=$pass;
                $cellphone = $this->input->post('user_cellphone_input');
                $birthdate = $this->input->post('user_birthdate_input');
                    $year = substr($birthdate, 6);
                    $month = substr($birthdate, 3,-5);
                    $day = substr($birthdate, 0,-8);
                    if ($year||$month||$day)
                        $birthdate = $year.'-'.$month.'-'.$day;
                    else
                        $birthdate = '0000-00-00';
                if($this->input->post('user_team_select')=='NULL')
                    $team = NULL;
                else
                    $team = $this->input->post('user_team_select');
                $roles_submited=$this->input->post('user_role_checkboxes');

                $this->user_model->editUser($this->session->userdata('id'),$newpass,$cellphone,$birthdate,$team,$roles_submited);

                redirect('user/view/'.$this->session->userdata('id').'?edit=success', 'refresh');
            } else{
                
                $this->data['this_user']= $this->data['this_user']= $this->user_model->getUser($this->session->userdata('id'));
                $this->load->model('team_model');
                $this->data['teams']= $this->team_model->getListTeams();
                $this->load->view('site/head');
                $this->load->view('site/header',$this->data);
                $this->load->view('user/edit_profile');
                $this->load->view('site/footer');
            }
        }else{  // Si l'utilisateur n'est pas connecté
            redirect('user/connect');
        }
    }
    
    function userpass_check($pass){    // fonction utilisée dans le validator mail de user/editprofile_submit pour vérifier si le mot de passe est correct
        $this_mail=$this->session->userdata('mail');
        $checkPassword = $this->user_model->passwordIsCorrect($this_mail,$pass);
        if ($checkPassword==0){
            $this->form_validation->set_message('userpass_check', '<span class="form-error">Adresse e-mail incorrecte ou compte non validé.</span>');
            return FALSE;
        }elseif($checkPassword==1){
            return TRUE;
        }else{
            $this->form_validation->set_message('userpass_check', '<span class="form-error">Mot de passe incorrect.</span>');
            return FALSE;
        }
    }
    
    function active($activating_code=0){
        if($this->user_model->activeAccount($activating_code))       
            redirect('user/connect/ActivatingSuccess', 'refresh');
        else // si l'utilisateur n'existe pas
            redirect('user/connect/ActivatingFailed', 'refresh');
    }
                
}
?>
                