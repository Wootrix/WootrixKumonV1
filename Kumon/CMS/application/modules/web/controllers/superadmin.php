<?php

error_reporting(E_ALL ^ E_NOTICE);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class superadmin extends CI_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('language');
        $this->load->library('session', true);
        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->helper('cookie');
        //$this->lang->load('en', 'english');
        $languge = $this->session->userdata('language');
        if ($languge == "") {
            $this->lang->load('en', 'english');
        } else {
            $this->lang->load($languge, 'english');
        }

        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    }

    /* GLOBLY SELECT LANGUAGE IN SESSION WORKING IN EVERY WHERE */

    public function select_language() {

        $languageObj = $this->load->model("web/language_model");
        $languageObj = new language_model();

       $language = $_POST['lang'];
        if ($language == "") {
            $language = 'en';
        }
        $redirectPage='';
        if($_POST['redirectPage']!=''){
        $redirectPage=$_POST['redirectPage'];
        }
        $languageObj->set_customer_id($this->session->userdata('id'));
        $languageObj->set_language_code($language);
        $languageObj->updateLanguageCode();
        //$this->lang->load('en', 'english');
       $this->session->set_userdata('language', $language);
       // echo '<pre>';print_r($this->session->all_userdata());die;

        $this->session->unset_userdata('lang');
        redirect("$redirectPage");        
    }

    /**
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Index function load defaut 
     * */
    public function index() {
        $this->load->lang('en', 'english');

        //echo"hello"; die;

        if ($this->session->userdata('id')) {

            if ($this->session->userdata('role') == '1') {
                redirect($this->config->base_url() . "superadmin");
            } else if ($this->session->userdata('role') == '2') {
                redirect($this->config->base_url() . "subadmin");
            } else {
                redirect($this->config->base_url() . "customer");
            }
        }

        $data['title'] = 'Wootrix';
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'admin/admin_login', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('admin/admin_login', $data);
    }

    /**
     * @author Techahead
     * login function
     * @access Public
     * @param 
     * @return as admin, superadmin
     * dashbord     
     * */
    public function get_admin_login() {

        if ($this->session->userdata('id')) {

            if ($this->session->userdata('role') == '1') {
                redirect("superadmin");
            } else if ($this->session->userdata('role') == '2') {
                redirect("subadmin");
            } 
        }
        /* Login function Start */
        if ($this->input->post("login") == "Login") {

            $this->form_validation->set_rules('username', 'Username', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required');
            if ($this->form_validation->run() == TRUE) {

                $adminObj = $this->load->model("web/super_admin_model");
                $adminObj = new super_admin_model();
                $adminObj->set_user_name(trim(addslashes(htmlentities(strip_tags($this->input->post("username"))))));
                $adminObj->set_password(trim(addslashes(htmlentities(strip_tags($this->input->post("password"))))));


                $row = $adminObj->adminLogin();
                if ($row == FALSE) {
                    $this->session->set_flashdata("msg", $this->lang->line("Please_enter_the_correct_username_and_password"));
                    redirect("login_check");
                } else {                    
                    $this->session->set_userdata('id', $row ['id']);
                    $this->session->set_userdata('role', $row ['role']);
                    $this->session->set_userdata('name', $row ['name']);
                    $this->session->set_userdata('image', $row ['image']);
                    $this->session->set_userdata('language', $row ['language_code']);
                    //echo"hello"; die;
                    if($this->input->post('remembermewoo') == "1") {
                       $year = time() + 31536000;
                $this->input->set_cookie('remember_me_wootrix', $this->input->post("username"), $year);
                }elseif(!$this->input->post('remembermewoo')) {
                    if(isset($_COOKIE['remember_me_wootrix'])) {
                        delete_cookie("remember_me_wootrix");
                    }
                }
                elseif(!$_POST['remember']) {
                    if(isset($_COOKIE['remember_me_wootrix'])) {
                        $past = time() - 100;
                        setcookie(remember_me_wootrix, gone, $past);
                    }
                }
                    if ($this->session->userdata('role') == '1') {
                        redirect("superadmin");
                    } else if ($this->session->userdata('role') == '2') {
                        redirect("subadmin");
                    } else {
                        redirect("customer");
                    }
                }
            }
        }

        $data['title'] = 'Wootrix';
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'admin/admin_login', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('admin/admin_login', $data);
    }

    /* SUPER ADMIN DASHBORD */
    /*     * is used for super admin
     * dashbord
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Index function load defaut 
     * */

    public function superadmin_dashbord() { 

        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }
        
        $adminObj = $this->load->model("web/super_admin_model");
        $adminObj = new super_admin_model();
        //$reviw_article_count = $adminObj->getMagazineReviewArticle();
        $new_ads_count = $adminObj->getNewAdvertise(); // ARTICLE
        $del_magazin_count = $adminObj->getAllDeletedMagazine(); // HISTORY
        $all_review_add_count = $adminObj->allReviewAdsCount();  //magazine ADS FOR REVIEW
        $mag_rev_count = $adminObj->getAllReviewMagazineArticle();
        //echo "<pre>";print_r($mag_rev_count);
        $magazines="";
        if(!empty($mag_rev_count))
        foreach ($mag_rev_count as $mag){
            $magazines .= $mag['magazine_id'].",";
        }
        $explode= array_filter(explode(',', $magazines));
        
        $unique=  ($explode);
        //print_r($explode);die;
        //$data['mag_review_article'] = $reviw_article_count;
        $data['mag_review_ads'] = $all_review_add_count; //magazine ADS FOR REVIEW
        $data['all_new_ads'] = $new_ads_count; //open article
        $data['mag_del_count'] = $del_magazin_count;//History
        $data['mag_review_article'] = count($unique);
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'admin/superadmin_dashbord', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    /**
     * used for admin dashbord
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Index function load defaut 
     * */
    public function subadmin_dashbord() {
         if ($this->session->userdata('id') == ""){
              redirect("login_check");
          }


        $adminObj = $this->load->model("web/super_admin_model");
        $adminObj = new super_admin_model();
        $adminObj->set_id($this->session->userdata['id']);        
       
        $new_ads_count = $adminObj->getNewAdvertise(); // ARTICLE
        $del_magazin_count = $adminObj->getAllDeletedMagazine(); // HISTORY
        $all_review_add_count = $adminObj->allReviewAdsCount();  //magazine ADS FOR REVIEW
        $mag_rev_count = $adminObj->getAllReviewMagazineArticle();
        //print_r($mag_rev_count);die;
        $magazines="";
        if(!empty($mag_rev_count)){
        foreach ($mag_rev_count as $mag){
            $magazines .= @$mag['magazine_id'].",";
        }
        
        $explode= array_filter(explode(',', $magazines));
        
        $unique=  array_unique($explode);
        }else{
            $unique=array();
        }
        
        //$data['mag_review_article'] = $reviw_article_count;
        $data['mag_review_ads'] = $all_review_add_count; //magazine ADS FOR REVIEW
        $data['all_new_ads'] = $new_ads_count; //open article
        $data['mag_del_count'] = $del_magazin_count;//History
        $data['mag_review_article'] = count($unique);

        $data_result = $adminObj->adminInformation();

        $data['data_result'] = $data_result;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'admin/admin_dashboard', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    /* ADMIN  LISTING */

    /**
     * used for admin listing
     * superadmin and admin
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Index function load defaut 
     * */
    public function sub_admin_list() {
        //unset($_SESSION['searchUser']);
        $this->session->unset_userdata('searchUser');
        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }

        $adminObj = $this->load->model("web/super_admin_model");
        $adminObj = new super_admin_model();
        
        /* PERMISSION DATA */
        if ($_GET['perId'] != "" && $_GET['perId'] == is_numeric($_GET['perId'])) {
            $selected_permission = $adminObj->set_permission($_GET['perId']);            
        }
        /* SERCH DATA */
        if ($_POST['sa'] == 'search' && $_POST['username'] != "") {
            $this->session->set_userdata('searchUser',$_POST['username']);
            $adminObj->set_name(trim($_POST['username']));
        }

        $permission = $adminObj->getPermissionList();
        $data_result = $adminObj->getAdminList();


        $data['data_result'] = $data_result;
        $data['permission'] = $permission;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'admin/subadmin_bord', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    /* ADMIN INFORMATION */

    /**
     * used for get admin information    
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Index function load defaut 
     * */
    public function admin_information() {
        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }
        $result = '';
        $adminObj = $this->load->model("web/super_admin_model");
        $adminObj = new super_admin_model();
        $adminObj->set_id($_POST['Id']);

        $data_result = $adminObj->adminInformation();


        $name = $data_result['name'];
        $gender = $data_result['gender'];
        if ($gender == m) {
            $gender = 'Male';
        } else {
            $gender = 'Female';
        }        
        if(!$data_result['dob'] == '0000-00-00'){
            $dob = $data_result['dob'];
        }else{
          $dob = "";  
        }
        $company = $data_result['company_name'];
        $mobile = $data_result['mobile'];
        $country = $data_result['country'];
        $email = $data_result['email'];
        $work_phn = $data_result['work_phone'];
        $city = $data_result['city'];
        $address = $data_result['address'];
        $image = $data_result['image'];
        if($image == ""){
            $path = $this->config->base_url() . 'images/profile-pic.png';
        }else{
            $path = $this->config->base_url() . 'assets/customer_img/' . $image;
        }
        
        /* SENDING STRING IN POP UP */
        $result = "<ul>
            <li><div class='rounded-image-big'><img src ='$path'></div> </li>
            <li><label>Name</label>  $name</li>
            <li><label>Born</label> $dob</li>
            <li><label>Gender</label> $gender</li>
            <li> <label>Email</label> $email</li>
            <li><label>Company</label> $company</li>
            <li> <label>Work Phn </label> $work_phn</li>
            <li><label>Mobile</label> $mobile</li>
            <li> <label>City</label> $city</li>
            <li class='full-width'> <label>Country</label> $country</li>
             <li class='full-width' > <label>Address</label> $address</li>
        </ul>";
        echo $result;
        exit();
    }

    /* DELETE A ADMIN BY SUPER ADMIN */

    /**
     * used for delete a admin    
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Index function load defaut 
     * */
    public function admin_delete() {
        //echo"<pre>"; print_r($_POST); die;
        $adminObj = $this->load->model("web/super_admin_model");
        $adminObj = new super_admin_model();
        $adminObj->set_id($_POST['val']);       
        $data_result = $adminObj->deleteAdmin();
        exit();
    }

    /* ADD SUB ADMIN */

    /**
     * used for add new admin    
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Index function load defaut 
     * */
    public function add_sub_admin() {
        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }
        $adminObj = $this->load->model("web/super_admin_model");
        $adminObj = new super_admin_model();
        $permission = $adminObj->getPermissionList();
       

        if ($this->input->post("save") == "save") {
            //echo"<pre>"; print_r($_POST); die;
            
            $this->form_validation->set_rules('user_name', 'user_name', 'required');
            $this->form_validation->set_rules('password', 'password', 'required');

            $adminObj->set_name(trim(addslashes(htmlentities(strip_tags($this->input->post("name"))))));
            $adminObj->set_gender(trim(addslashes(htmlentities(strip_tags($this->input->post("gender"))))));
            $adminObj->set_dob($this->input->post("dob"));
            $adminObj->set_email($this->input->post("email"));
            $adminObj->set_company_name($this->input->post("company_name"));
            $adminObj->set_work_phone($this->input->post("work_phone"));
            $adminObj->set_mobile($this->input->post("mobile"));
            $adminObj->set_city($this->input->post("city"));
            $adminObj->set_country($this->input->post("country"));
            $adminObj->set_address($this->input->post("address"));
            $adminObj->set_user_name($this->input->post("user_name"));
            $adminObj->set_password(trim($this->input->post("password")));
            $adminObj->set_permission1($this->input->post("permission1"));
            $adminObj->set_permission2($this->input->post("permission2"));
            $adminObj->set_permission3($this->input->post("permission3"));
            $adminObj->set_permission4($this->input->post("permission4"));
            $adminObj->set_permission5($this->input->post("permission5"));
            $adminObj->set_permission6($this->input->post("permission6"));
            $adminObj->set_permission7($this->input->post("permission7"));
            $adminObj->set_permission8($this->input->post("permission8"));

            if ($_FILES['profilepic']['error'] == 0) {

                $folderName = 'customer_img';
                $pathToUpload = './assets/' . $folderName;
                if (!is_dir($pathToUpload)) {
                    mkdir($pathToUpload, 0777, TRUE);
                }

                $config['upload_path'] = './assets/customer_img/';
                // Location to save the image
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['overwrite'] = FALSE;
                $config['remove_spaces'] = true;
                $config['maintain_ratio'] = TRUE;
                //$config['max_size'] = '0';
                $config['create_thumb'] = TRUE;

                $imgName = date("Y-m-d");
                $nameImg = "";
                $nameChar = "ABCDEFGHIJKLMNOPQRSTYUVWXYZabcdefghijklmnopqrstuvwxyz";

                for ($i = 0; $i < 3; $i++) {

                    $nameImg .= $nameChar[rand(0, strlen($nameChar))];
                }
                $config['file_name'] = $imgName . $nameImg . "_org";

                $thumbName = $imgName;
                $this->load->library('upload', $config);
                //codeigniter default function

                if (!$this->upload->do_upload('profilepic')) {
                    echo $this->upload->display_errors();

                    $this->session->set_flashdata("msg", $this->lang->line("Image_not_uploaded"));
                    // redirect  page if the load fails.
                }
                //$file_name =  $pathToUpload/$this->upload->file_name;
                $adminObj->set_image($this->upload->file_name);
            }
            $user_name_exist_check = $adminObj->userNameExistCheck();
            $user_email_exist_check = $adminObj->userEmailCheck();
             if ($user_email_exist_check == TRUE) {
                $this->session->set_flashdata("msg", $this->lang->line("Email_already_exist"));
                redirect("addsubadmin");
             }
             if ($user_name_exist_check == TRUE) {
                $this->session->set_flashdata("msg", $this->lang->line("User_name_already_exist"));
                redirect("addsubadmin");
             } else {
                $adminObj->InsertUserDetails();
                $this->session->set_flashdata("msg", $this->lang->line("ADMIN_ADDED_SUCCESSFULLY"));
                redirect("admin");
             }
        }

        $data['permission'] = $permission;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'admin/add_sub_admin', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    /* EDIT SUB ADMIN */
     /**
     * @author Techahead
     * login function
     * @access Public
     * @param 
     * @return 
     * true/false     
     * */

    public function edit_sub_admin() {
        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }

        $adminObj = $this->load->model("web/super_admin_model");
        $adminObj = new super_admin_model();
        $permission = $adminObj->getPermissionList();
        $adminObj->set_id($_GET['rowId']);
        $data_result = $adminObj->adminInformation();

        /* EDIT INFORMATION */
        if ($this->input->post("save") == "save") { 
            //echo"<pre>"; print_r($_POST); die;
            $this->form_validation->set_rules('user_name', 'user_name', 'required');
            $adminObj->set_name(trim(addslashes(htmlentities(strip_tags($this->input->post("name"))))));
            $adminObj->set_gender(trim(addslashes(htmlentities(strip_tags($this->input->post("gender"))))));
            $adminObj->set_dob($this->input->post("dob"));
            $adminObj->set_email($this->input->post("email"));
            $adminObj->set_company_name($this->input->post("company_name"));
            $adminObj->set_work_phone($this->input->post("work_phone"));
            $adminObj->set_mobile($this->input->post("mobile"));
            $adminObj->set_city($this->input->post("city"));
            $adminObj->set_country($this->input->post("country"));
            $adminObj->set_address($this->input->post("address"));
            $adminObj->set_user_name($this->input->post("user_name"));
            $adminObj->set_password(trim($this->input->post("password")));
            $adminObj->set_permission1($this->input->post("permission1"));
            $adminObj->set_permission2($this->input->post("permission2"));
            $adminObj->set_permission3($this->input->post("permission3"));
            $adminObj->set_permission4($this->input->post("permission4"));
            $adminObj->set_permission5($this->input->post("permission5"));
            $adminObj->set_permission6($this->input->post("permission6"));
            $adminObj->set_permission7($this->input->post("permission7"));
            $adminObj->set_permission8($this->input->post("permission8"));
            $adminObj->set_id($this->input->post("admin_id"));
            //$adminObj->set_image("");


            if ($_FILES['profilepic']['error'] == 0) {

                $folderName = 'customer_img';
                $pathToUpload = './assets/' . $folderName;
                if (!is_dir($pathToUpload)) {
                    mkdir($pathToUpload, 0777, TRUE);
                }

                $config['upload_path'] = './assets/customer_img/';
                // Location to save the image
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['overwrite'] = FALSE;
                $config['remove_spaces'] = true;
                $config['maintain_ratio'] = TRUE;
                //$config['max_size'] = '0';
                $config['create_thumb'] = TRUE;

                $imgName = date("Y-m-d");
                $nameImg = "";
                $nameChar = "ABCDEFGHIJKLMNOPQRSTYUVWXYZabcdefghijklmnopqrstuvwxyz";

                for ($i = 0; $i < 3; $i++) {

                    $nameImg .= $nameChar[rand(0, strlen($nameChar))];
                }
                $config['file_name'] = $imgName . $nameImg . "_org";

                $thumbName = $imgName;
                $this->load->library('upload', $config);
                //codeigniter default function

                if (!$this->upload->do_upload('profilepic')) {
                    echo $this->upload->display_errors();
                    die;

                    $this->session->set_flashdata("msg", $this->lang->line("Image_not_uploaded"));
                    // redirect  page if the load fails.
                }
                //$file_name =  $pathToUpload/$this->upload->file_name;
                $adminObj->set_image($this->upload->file_name);
            }
            $user_name_exist_check = $adminObj->perticularUserNameExistCheck();
            $user_email_exist_check = $adminObj->perticularUserEmailExistCheck();
             if ($user_email_exist_check == TRUE) {
                $this->session->set_flashdata("msg", $this->lang->line("Email_already_exist"));
                redirect("editsubadmin?rowId=".$_GET['rowId']);
             }
            if ($user_name_exist_check == TRUE) {
               
                $this->session->set_flashdata("msg", $this->lang->line("User_name_already_exist"));
                redirect("editsubadmin?rowId=".$_GET['rowId']);
            } else {
                
                $adminObj->UpdateUserDetails();
                $this->session->set_flashdata("msg", $this->lang->line("Admin_edited_sucessfully"));
                redirect("admin");
            }
        }
        /* -------------- */




        $data['data_result'] = $data_result;
        $data['permission'] = $permission;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'admin/edit_sub_admin', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    /* FORGOT PASSWORD */
     /**
     * @author Techahead
     * login function
     * @access Public
     * @param 
     * @return 
      * true/false
      */    
    

    public function admin_forgot_password() {       

        if ($this->input->post("Submit") == "Submit") {

            //$this->form_validation->set_rules('username', 'username', 'required');
            $this->form_validation->set_rules('email', 'email', 'required');
            if ($this->form_validation->run() == TRUE) {

                $adminObj = $this->load->model("web/super_admin_model");
                $adminObj = new super_admin_model();
                //$adminObj->set_user_name(trim(addslashes(htmlentities(strip_tags($this->input->post("username"))))));
                $adminObj->set_email(trim(addslashes(htmlentities(strip_tags($this->input->post("email"))))));

                $check_user_exist = $adminObj->checkUserExist();


                if ($check_user_exist == FALSE) {
                    $this->session->set_flashdata("msg", $this->lang->line("Please_enter_the_correct_email_and_username"));
                    redirect("admin_forgot_password");
                } else {

                    $magPassword = "";
                    $nameChar = "ABCDEFGHIJKLMNOPQRSTYUVWXYZabcdefghijklmnopqrstuvwxyz";                   
                    for ($i = 0; $i < 5; $i++) {
                        $magPassword .= $nameChar[rand(0, strlen($nameChar))];
                    }
                    $adminObj->set_password($magPassword);
                    $sucess = $adminObj->UpdateForgotPassword();
                    if ($sucess == TRUE) {
                        $to = $this->input->post("email");  //admin
                        $subject = 'Password update';
                        $from = EMAIL;
                        $from_title = EMAIL_TITLE;

                        $email_message = "Your New Updated Password <br/>";
                        $email_message .="Password :   $magPassword  <br/>";
                        $email_message .= "Thanks <br/>";
                        $email_message .= "Wootrix Team";

                        $this->email->from($from, $from_title);
                        $this->email->to($to);
                        $this->email->subject($subject);
                        $this->email->message($email_message);
                        $this->email->send();

                        $this->session->set_flashdata("msg", "your new password is send to your email");
                        redirect("login_check");
                    } else {
                        $this->session->set_flashdata("msg", "some problem occurs Please try some time later");
                        redirect("admin_forgot_password");
                    }
                }
            }
        }


        $data['title'] = 'Wootrix';
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'admin/forget_password', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('admin/forget_password', $data);
    }

    /* ADMIN UPDATE PROFILE */

    /**
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Index function load defaut 
     * */
    public function admin_edit_profile() {
         if ($this->session->userdata('id') == ""){
              redirect("login_check");
          }

        $adminObj = $this->load->model("web/super_admin_model");
        $adminObj = new super_admin_model();
        $adminObj->set_id($this->session->userdata('id'));
        $data_result = $adminObj->customerInformation();

        if ($this->input->post("save") == "save") {

            $adminObj->set_name(trim(addslashes(htmlentities(strip_tags($this->input->post("name"))))));

            $adminObj->set_gender(trim(addslashes(htmlentities(strip_tags($this->input->post("gender"))))));
            $adminObj->set_dob($this->input->post("dob"));
            $adminObj->set_email($this->input->post("email"));
            $adminObj->set_company_name($this->input->post("company_name"));
            $adminObj->set_work_phone($this->input->post("work_phone"));
            $adminObj->set_mobile($this->input->post("mobile"));
            $adminObj->set_city($this->input->post("city"));
            $adminObj->set_country($this->input->post("country"));
            $adminObj->set_address($this->input->post("address"));
            $adminObj->set_user_name($this->input->post("user_name"));           
            $id = $adminObj->set_id($this->input->post("customer_id"));
            //echo"<pre>"; print_r($_POST); die;
            if ($_FILES['profilepic']['error'] == 0) {

                $folderName = 'customer_img/';
                $pathToUpload = './assets/' . $folderName;
                if (!is_dir($pathToUpload)) {
                    mkdir($pathToUpload, 0777, TRUE);
                }

                $config['upload_path'] = './assets/customer_img/';
                // Location to save the image
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['overwrite'] = FALSE;
                $config['remove_spaces'] = true;
                $config['maintain_ratio'] = TRUE;
                //$config['max_size'] = '0';
                $config['create_thumb'] = TRUE;

                $imgName = date("Y-m-d");
                $nameImg = "";
                $nameChar = "ABCDEFGHIJKLMNOPQRSTYUVWXYZabcdefghijklmnopqrstuvwxyz";

                for ($i = 0; $i < 3; $i++) {

                    $nameImg .= $nameChar[rand(0, strlen($nameChar))];
                }
                $config['file_name'] = $imgName . $nameImg . "_org";

                $thumbName = $imgName;
                $this->load->library('upload', $config);
                //codeigniter default function

                if (!$this->upload->do_upload('profilepic')) {
                    //echo $this->upload->display_errors();

                    $this->session->set_flashdata("msg", $this->lang->line("Image_not_uploaded"));
                    // redirect  page if the load fails.
                }
                //$file_name =  $pathToUpload/$this->upload->file_name;
                $adminObj->set_image($this->upload->file_name);
            }
            //$user_name_exist_check = $customerObj->perticuilarUserNameExistCheck();
            $adminObj->UpdateCustomerDetails();
            $this->session->set_flashdata("susmsg", $this->lang->line("User_Edited_Sucessfully"));
            redirect("admineditprofile");
        }

        $data['data_result'] = $data_result;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'admin/edit_profile', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    /* ADMIN UPDATE PASSWORD */
     /**
     * @author Techahead    
     * @access Public
     * @param 
     * @return
      * true/false
      */  
     

    public function admin_change_Password() {
         if ($this->session->userdata('id') == ""){
              redirect("login_check");
          }

        $adminObj = $this->load->model("web/super_admin_model");
        $adminObj = new super_admin_model();
        $adminObj->set_id($this->session->userdata('id'));
        if ($this->input->post("save") == "save") {
           //echo"<pre>"; print_r($_POST); die;
            $adminObj->set_password(trim($this->input->post("password")));
            //$customerObj->set_password($this->input->post("password_confirm"));
            $id = $adminObj->set_id($this->input->post("customer_id"));
            $user_name = $adminObj->set_user_name($this->input->post("user_name"));

            $user_name_exist_check = $adminObj->checkUserAndPassword();
            if ($user_name_exist_check == FALSE) {
                $this->session->set_flashdata("msg", $this->lang->line("Current_User_name_and_password_not_matched"));
                redirect("admineditprofile");
            } else {
                $adminObj->set_password($this->input->post("password_confirm"));
                $adminObj->UpdateCustomerPassword();
                $this->session->set_flashdata("susmsg", $this->lang->line("password_updated_Sucessfully"));
                redirect("admineditprofile");
            }
        } else {
            redirect("admineditprofile");
        }
    }

//*********************function for admin logout*************//
    public function logout() {
        $adminObj = $this->load->model("web/super_admin_model");
        $adminObj = new super_admin_model();

        $new_data = array(
            'id' => '',
            'role' => '',
            'image' => FALSE,
            'language' => ''
        );
        $this->session->unset_userdata($new_data);
        $this->session->sess_destroy();
        redirect("login_check");
    }

    public function report_permission(){

        if ($this->session->userdata('id') == ""){
            redirect("login_check");
        }

        $customerId = $_GET["rowId"];

        $data["idCustomer"] = $customerId;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'admin/admin_report_permission', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);

    }

    public function get_report_permission_data(){

        if ($this->session->userdata('id') == ""){
            redirect("login_check");
        }

        $customerId = $_GET["id_customer"];

        $data = array();

        $this->load->model("web/customer_metric_model");
        $obj = new customer_metric_model();

        $obj->setIdCustomer($customerId);

        $result = $obj->getMetrics();

        $data["metrics"] = array();

        foreach( $result as $r ){
            $data["metrics"][] = $r["metric"];
        }

        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'admin/report_permission_data', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/only_content', $data);

    }

    public function edit_metrics(){

        if ($this->session->userdata('id') == ""){
            redirect("login_check");
        }

        $this->load->model("web/customer_metric_model");
        $obj = new customer_metric_model();

        $obj->insert($_POST);

        $this->session->set_flashdata("msg", "Métricas salvas com sucesso.");
        redirect("customers");

    }

    public function public_article_report(){

        if ($this->session->userdata('id') == ""){
            redirect("login_check");
        }

        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'admin/public_article_report', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);

    }

    public function closed_article_report(){

        if ($this->session->userdata('id') == "" && $this->session->userdata('user_id') == ""){
            redirect("login_check");
        }

        $this->load->model("web/group_model");
        $model = new group_model();

        $this->load->model("web/magazine_model");
        $obj = new magazine_model();

        $magazines = $obj->getCustomerMagazinesFilter($this->session->userdata('user_id'));
        $articles = $obj->getArticlesByMagazineFilter();
        $groups = $model->getAllGroups();
        $locations = $model->getAllLocations();
        $disciplines = $model->getAllDisciplines();
        $branches = $model->getAllBranches();

        $data["magazines"] = $magazines;
        $data["articles"] = $articles;
        $data["groups"] = $groups;
        $data["locations"] = $locations;
        $data["disciplines"] = $disciplines;
        $data["branches"] = $branches;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'admin/closed_article_report', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);

    }

    public function language_filter(){

        if ($this->session->userdata('id') == ""){
            redirect("login_check");
        }

        $this->load->model("web/language_model");
        $obj = new language_model();

        $data_result = $obj->getAllFilter();

        $data = array();

        $data["show_default_value"] = true;
        $data["result"] = $data_result;
        $data['main_content'] = array('view' => 'admin/select_filter', 'data' => array());
        $this->load->view('templates/only_content', $data);

    }

    public function listDeepLink(){

        if ($this->session->userdata('id') == ""){
            redirect("login_check");
        }

        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'admin/list_deep_links', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);

    }

    public function campaign_layout_list(){

        if ($this->session->userdata('id') == ""){
            redirect("login_check");
        }

        $this->load->model("web/customer_model");
        $obj = new customer_model();

        $customers = $obj->getLayoutCustomer();

        $data["customers"] = $customers;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'admin/list_campaign_layout', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);

    }

    public function campaign_layout_detail(){

        if ($this->session->userdata('id') == ""){
            redirect("login_check");
        }

        $idCustomer = $this->input->get("id");

        $this->load->model("web/customer_model");
        $obj = new customer_model();

        $customers = $obj->getCustomerFilterList();
        $data["customers"] = $customers;

        if( $this->input->post("action") == "save" ){

            $customerId = $this->input->post("customer_id");
            $urlSignage = $this->input->post("url_signage");

            $obj->set_id($customerId);
            $obj->saveCampaign($urlSignage);

            $this->session->set_flashdata("msg", "Layout salvo com sucesso.");
            redirect("campaignLayoutList");

        }

        if( !empty( $idCustomer ) ){

            $obj->set_id($idCustomer);

            $campaigns = $obj->getCustomerCampaigns();
            $data["campaigns"] = $campaigns;

        }

        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'admin/campaign_layout_detail', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);

    }

    public function get_text_device(){

        if ($this->session->userdata('id') == ""){
            redirect("login_check");
        }

        $idCustomer = $_POST["idCustomer"];
        $nrDevices = $_POST['nrDevices'];

        $this->load->model("web/customer_model");
        $obj = new customer_model();


        $obj->set_id($idCustomer);
        $campaigns = $obj->getCustomerCampaigns();

        $data["campaigns"] = $campaigns;
        $data["nrDevices"] = $nrDevices;
        $data['main_content'] = array('view' => 'admin/text_device', 'data' => array());
        $this->load->view('templates/only_content', $data);

    }

}

?>
