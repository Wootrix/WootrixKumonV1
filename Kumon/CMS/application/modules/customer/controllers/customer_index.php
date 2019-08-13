<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class customer_index extends CI_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session', true);
        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->helper('cookie');
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

    /* SELECT LANGUAGE */

    public function select_customer_language() {

        $languageObj = $this->load->model("web/language_model");
        $languageObj = new language_model();
       //echo"<pre>"; print_r($_POST); die;
        $language = $_POST['lang'];
        if ($language == "") {
            $language = 'en';
        }
        $redirectPage='';
        if($_POST['redirectPage']!=''){
        $redirectPage=$_POST['redirectPage'];
        }
        //echo 'lan='.$language;die;
        
        $languageObj->set_customer_id($this->session->userdata('id'));
        $languageObj->set_language_code($language);
        $languageObj->updateCustomerLanguageCode();

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
    public function customer_login() {

        if ($this->input->post("login") == "Login") {

            $this->form_validation->set_rules('username', 'Username', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required');
            if ($this->form_validation->run() == TRUE) {

                $customerObj = $this->load->model("customer/customers_model");
                $customerObj = new customers_model();
                $customerObj->set_user_name(trim(addslashes(htmlentities(strip_tags($this->input->post("username"))))));
                $customerObj->set_password(trim(addslashes(htmlentities(strip_tags($this->input->post("password"))))));

                $status = $customerObj->customerBlockStatus();
                if($status == TRUE){
                  $this->session->set_flashdata("msg", $this->lang->line("user_is_blocked"));
                    redirect("customer_login");  
                 }
                $row = $customerObj->customerLogin(); 

                if ($row == FALSE) {
                    $this->session->set_flashdata("msg", $this->lang->line("Please_enter_the_correct_username_and_password"));
                    redirect("customer_login");
                } else {
                    //echo"<pre>"; print_r($row); die;
                    $this->session->set_userdata('user_id', $row ['id']);
                    //$this->session->set_userdata('role', $row ['role']);
                    $this->session->set_userdata('name', $row ['user_name']);
                    //$this->session->set_userdata('name', $row ['name']);
                    $this->session->set_userdata('image', $row ['image']);
                    $this->session->userdata('language', $row ['language_code']);
                    
                    if($this->input->post('remembermewoo') == "1") {
                       $year = time() + 31536000;
                $this->input->set_cookie('remember_me_customer', $this->input->post("username"), $year);
                }elseif(!$this->input->post('remembermewoo')) {
                    if(isset($_COOKIE['remember_me_customer'])) {
                        delete_cookie("remember_me_customer");
                    }
                }
                elseif(!$_POST['remember']) {
                    if(isset($_COOKIE['remember_me_customer'])) {
                        $past = time() - 100;
                        setcookie(remember_me_wootrix, gone, $past);
                    }
                }
                    
                    redirect("customerdashbord");
                }
            }
        }

        $this->load->view('customer_view/customer_login', $data);
    }

    /* CUSTOMER LANDING PAGE */
    /**
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Customer dashbord 
     * */

    public function customer_dashbord() {
        if ($this->session->userdata('user_id') == "") {
            redirect("customer_login");
        }
        
        $customerObj = $this->load->model("customer/customers_model");
        $customerObj = new customers_model(); //$this->session->userdata('user_id')
        $customerObj->set_id($this->session->userdata('user_id'));
        $allNewOrReviedArticles=$customerObj->getCustomerReviewArticle();
        
        foreach ($allNewOrReviedArticles as $anora){
            $magazines .=$anora['magazine_id'].",";
        }
        $explode= array_filter(explode(',', $magazines));
        
        $unique=  array_unique($explode);
        $data['revied_ads'] = $customerObj->allReviewAdvertise();
        $data['revied_mag_article'] = count($unique);
        $data['header'] = array('view' => 'templates/customerheader', 'data' => array());
        $data['main_content'] = array('view' => 'customer_view/customer_dashbord', 'data' => array());
        $data['footer'] = array('view' => 'templates/customerfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    /* CUSTOMER EDIT PROFILE */

    /**
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Index function load defaut 
     * */
    public function customer_edit_profile() {
        if ($this->session->userdata('user_id') == "") {
            redirect("customer_login");
        }

        $customerObj = $this->load->model("customer/customers_model");
        $customerObj = new customers_model(); //$this->session->userdata('user_id')
        $customerObj->set_id($this->session->userdata('user_id'));
        $data_result = $customerObj->customerInformation();

        if ($this->input->post("save") == "save") {


            $customerObj->set_name(trim(addslashes(htmlentities(strip_tags($this->input->post("name"))))));

            $customerObj->set_gender(trim(addslashes(htmlentities(strip_tags($this->input->post("gender"))))));
            $customerObj->set_dob($this->input->post("dob"));
            $customerObj->set_email($this->input->post("email"));
            $customerObj->set_company_name($this->input->post("company_name"));
            $customerObj->set_work_phone($this->input->post("work_phone"));
            $customerObj->set_mobile($this->input->post("mobile"));
            $customerObj->set_city($this->input->post("city"));
            $customerObj->set_country($this->input->post("country"));
            $customerObj->set_address($this->input->post("address"));
            $customerObj->set_user_name($this->input->post("user_name"));
            $id = $customerObj->set_id($this->input->post("customer_id"));
            
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
                    //echo $this->upload->display_errors();

                    $this->session->set_flashdata("msg", $this->lang->line("Image_not_uploaded"));
                    // redirect  page if the load fails.
                }
                //$file_name =  $pathToUpload/$this->upload->file_name;
                $customerObj->set_image($this->upload->file_name);
            }
            $customerObj->UpdateCustomerDetails();
            $this->session->set_flashdata("susmsg", $this->lang->line("User_Edited_Sucessfully"));
            redirect("customereditprofile");
        }

        $data['data_result'] = $data_result;
        $data['header'] = array('view' => 'templates/customerheader', 'data' => array());
        $data['main_content'] = array('view' => 'customer_view/customer_edit_profile', 'data' => array());
        $data['footer'] = array('view' => 'templates/customerfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    /* CUSTOMER UPDATE PASSWORD */

    public function customer_change_Password() {
        $customerObj = $this->load->model("customer/customers_model");
        $customerObj = new customers_model(); //$this->session->userdata('user_id')
        $customerObj->set_id($this->session->userdata('user_id'));
        if ($this->input->post("save") == "save") {
            //echo"<pre>"; print_r($_POST);
            $customerObj->set_password($this->input->post("password"));
            //$customerObj->set_password($this->input->post("password_confirm"));
            $id = $customerObj->set_id($this->input->post("customer_id"));
            $user_name = $customerObj->set_user_name($this->input->post("user_name"));

            $user_name_exist_check = $customerObj->checkUserAndPassword();
            if ($user_name_exist_check == FALSE) {
                $this->session->set_flashdata("msg", $this->lang->line("User_name_and_password_not_matched"));
                redirect("customereditprofile");
            } else {
                $customerObj->set_password($this->input->post("password_confirm"));
                $customerObj->UpdateCustomerPassword();
                $this->session->set_flashdata("susmsg", $this->lang->line("password_updated_Sucessfully"));
                redirect("customereditprofile");
            }
        } else {
            redirect("customereditprofile");
        }
    }

    /* CUSTOMER FORGOT PASSWORD */

    public function customer_forgot_password() {
        
       
        if ($this->input->post("Submit") == "Submit") {

            $this->form_validation->set_rules('username', 'username', 'required');
            $this->form_validation->set_rules('email', 'email', 'required');
            if ($this->form_validation->run() == TRUE) {

                $customerObj = $this->load->model("customer/customers_model");
                $customerObj = new customers_model();
                //$customerObj->set_user_name(trim(addslashes(htmlentities(strip_tags($this->input->post("username"))))));
                $customerObj->set_email(trim(addslashes(htmlentities(strip_tags($this->input->post("email"))))));

                $check_user_exist = $customerObj->checkUserExist();


                if ($check_user_exist == FALSE) {
                    $this->session->set_flashdata("msg", $this->lang->line("Please_enter_the_correct_email_and_username"));
                    redirect("admin_forgot_password");
                } else {

                    $magPassword = "";
                    $nameChar = "ABCDEFGHIJKLMNOPQRSTYUVWXYZabcdefghijklmnopqrstuvwxyz";
                    //echo $no_of_user;
                    for ($i = 0; $i < 5; $i++) {
                        $magPassword .= $nameChar[rand(0, strlen($nameChar))];
                    }
                    $customerObj->set_password($magPassword);
                    $sucess = $customerObj->UpdateForgotPassword();
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

                        $this->session->set_flashdata("susmsg", $this->lang->line("your_new_password_is_send_to_your_email"));
                        redirect("customer_login");
                    } else {
                        $this->session->set_flashdata("msg", $this->lang->line("some_problem_occurs_Please_try_some_time_later"));
                        redirect("customerforgotpassword");
                    }
                }
            }
        }


        $data['title'] = 'Wootrix';
        $data['header'] = array('view' => 'templates/customerheader', 'data' => array());
        $data['main_content'] = array('view' => 'customer_view/forget_password', 'data' => array());
        $data['footer'] = array('view' => 'templates/customerfooter', 'data' => array());
        $this->load->view('customer_view/forget_password');
    }

    /* ------------logout--------- */

    public function customer_logout() {
        $adminObj = $this->load->model("web/super_admin_model");
        $adminObj = new super_admin_model();

        $new_data = array(
            'role' => '',
            'image' => FALSE,
            'language' => '',
            'user_id' => ''
        );
        $this->session->unset_userdata($new_data);
        $this->session->sess_destroy();
        redirect("customer_login");
    }

    public function use_report(){

        if ($this->session->userdata('user_id') == "") {
            redirect("customer_login");
        }

        $customerId = $this->session->userdata('user_id');

        $this->load->model("web/customer_metric_model");
        $obj = new customer_metric_model();

        $obj->setIdCustomer($customerId);

        $metrics = $obj->getMetrics();

        $data = array();

        $data["metrics"] = $metrics;
        $data['header'] = array('view' => 'templates/customerheader', 'data' => array());
        $data['main_content'] = array('view' => 'customer_view/customer_use_report', 'data' => array());
        $data['footer'] = array('view' => 'templates/customerfooter', 'data' => array());
        $this->load->view('templates/common', $data);

    }

    public function get_customer_magazine_filter(){

        if ($this->session->userdata('user_id') == "") {
            redirect("customer_login");
        }

        $customerId = $this->session->userdata('user_id');

        $this->load->model("web/magazine_model");
        $obj = new magazine_model();

        $obj->set_id($customerId);

        $data_result = $obj->getCustomerMagazinesFilter();

        $data = array();

        $data["show_default_value"] = true;
        $data["result"] = $data_result;
        $data['main_content'] = array('view' => 'customer_view/select_filter', 'data' => array());
        $this->load->view('templates/only_content', $data);

    }

    public function get_magazine_content_filter(){

        if ($this->session->userdata('user_id') == "") {
            redirect("customer_login");
        }

        $magazineId = $_POST['magazineId'];

        $this->load->model("web/magazine_model");
        $magazineObj = new magazine_model();

        $magazineObj->set_customer_id($this->session->userdata('user_id'));
        $magazineObj->set_id($magazineId);

        $data_result = $magazineObj->getArticlesByMagazineFilter();

        $data = array();

        $data["show_default_value"] = true;
        $data["result"] = $data_result;
        $data['main_content'] = array('view' => 'customer_view/select_filter', 'data' => array());
        $this->load->view('templates/only_content', $data);

    }

    public function get_magazine_users_filter(){

        if ($this->session->userdata('user_id') == "") {
            redirect("customer_login");
        }

        $magazineId = $_POST['magazineId'];

        $this->load->model("web/magazine_model");
        $magazineObj = new magazine_model();

        $magazineObj->set_customer_id($this->session->userdata('user_id'));
        $magazineObj->set_id($magazineId);

        $data_result = $magazineObj->getMagazineUsersFilter();

        $data = array();

        $data["show_default_value"] = true;
        $data["result"] = $data_result;
        $data['main_content'] = array('view' => 'customer_view/select_filter', 'data' => array());
        $this->load->view('templates/only_content', $data);

    }

    public function get_use_report_data_1(){

        if ($this->session->userdata('user_id') == "") {
            redirect("customer_login");
        }

        $magazineId = $_POST["magazineId"];
        $articleId = $_POST["articleId"];
        $userId = $_POST["userId"];

        $this->load->model("webservices/magazine_access_model");
        $obj = new magazine_access_model();

        $obj->setIdMagazine($magazineId);
        $obj->setIdArticle($articleId);
        $obj->setIdUser($userId);

        $data_result = $obj->getAccessBySo($this->session->userdata('user_id'));

        echo json_encode($data_result);
        exit;

    }

    public function get_use_report_data_2(){

        if ($this->session->userdata('user_id') == "") {
            redirect("customer_login");
        }

        $magazineId = $_POST["magazineId"];
        $articleId = $_POST["articleId"];
        $userId = $_POST["userId"];

        $this->load->model("webservices/magazine_access_model");
        $obj = new magazine_access_model();

        $obj->setIdMagazine($magazineId);
        $obj->setIdArticle($articleId);
        $obj->setIdUser($userId);

        $data_result = $obj->getAccessByType($this->session->userdata('user_id'));

        echo json_encode($data_result);
        exit;

    }

    public function get_use_report_data_3(){

        if ($this->session->userdata('user_id') == "") {
            redirect("customer_login");
        }

        $magazineId = $_POST["magazineId"];
        $articleId = $_POST["articleId"];
        $userId = $_POST["userId"];

        $this->load->model("webservices/magazine_access_model");
        $obj = new magazine_access_model();

        $obj->setIdMagazine($magazineId);
        $obj->setIdArticle($articleId);
        $obj->setIdUser($userId);

        $data_result = $obj->getAccessByState($this->session->userdata('user_id'));

        echo json_encode($data_result);
        exit;

    }

    public function get_use_report_data_4(){

        if ($this->session->userdata('user_id') == "") {
            redirect("customer_login");
        }

        $magazineId = $_POST["magazineId"];
        $articleId = $_POST["articleId"];
        $userId = $_POST["userId"];

        $this->load->model("webservices/magazine_access_model");
        $obj = new magazine_access_model();

        $obj->setIdMagazine($magazineId);
        $obj->setIdArticle($articleId);
        $obj->setIdUser($userId);

        $data_result = $obj->getAccessByMagazineArticle($this->session->userdata('user_id'));

        echo json_encode($data_result);
        exit;

    }

    public function get_use_report_data_5(){

        if ($this->session->userdata('user_id') == "") {
            redirect("customer_login");
        }

        $customerId = $this->session->userdata('user_id');

        $magazineId = $_POST["magazineId"];
        $articleId = $_POST["articleId"];
        $userId = $_POST["userId"];

        $this->load->model("webservices/magazine_access_model");
        $obj = new magazine_access_model();

        $obj->setIdMagazine($magazineId);
        $obj->setIdArticle($articleId);
        $obj->setIdUser($userId);

        $data_result = $obj->getAccessUsers($customerId);

        $this->load->model("web/customer_metric_model");
        $metricModel = new customer_metric_model();

        $metricModel->setIdCustomer($customerId);

        $metrics = $metricModel->getMetrics();

        $data = array();

        $data["metrics"] = $metrics;
        $data["result"] = $data_result;
        $data['main_content'] = array('view' => 'customer_view/list_user_use_report', 'data' => array());
        $this->load->view('templates/only_content', $data);

    }

    public function magazineCodes(){

        if ($this->session->userdata('user_id') == "") {
            redirect("customer_login");

        }

        $customerId = $this->session->userdata('user_id');

        $this->load->model("web/customer_metric_model");
        $obj = new customer_metric_model();

        $obj->setIdCustomer($customerId);

        $metrics = $obj->getMetrics();

        $data = array();

        $data["metrics"] = $metrics;
        $data['header'] = array('view' => 'templates/customerheader', 'data' => array());
        $data['main_content'] = array('view' => 'customer_view/customer_magazine_codes', 'data' => array());
        $data['footer'] = array('view' => 'templates/customerfooter', 'data' => array());
        $this->load->view('templates/common', $data);

    }

    public function magazine_code_list(){

        if ($this->session->userdata('user_id') == "") {
            redirect("customer_login");
        }

        $customerId = $this->session->userdata('user_id');

        $magazineId = $_POST["magazineId"];
        $codeUsed = $_POST["codeUsed"];
        $page = $_POST["page"];

        $this->load->model("customer/customer_magazine_model");
        $obj = new customer_magazine_model();

        $obj->set_magazine_id($magazineId);
        $obj->set_customer_id($customerId);

        $data_result = $obj->getMagazineCodeListUpdated($codeUsed, $page);

//        print_r($data_result); exit;

        $data = array();

        $data["result"] = $data_result["result"];
        $data["totalValues"] = $data_result["totalValues"];
        $data['main_content'] = array('view' => 'customer_view/list_magazine_code', 'data' => array());
        $this->load->view('templates/only_content', $data);

    }

    public function edit_magazine_code(){

        if ($this->session->userdata('user_id') == "") {
            redirect("customer_login");
        }

        $this->load->model("web/magazine_model");
        $obj = new magazine_model();

        $obj->set_id($_GET['rowId']);
        $magazineCode = $obj->getMagazineCode($_GET['rowId']);

        if ($this->input->post("save") == "save") {

            $code = $this->input->post("password");

            $codeIsUnique = $obj->checkUniqueCode($_GET['rowId'], $code);

            if(!$codeIsUnique){
                $this->session->set_flashdata("msg", "Código já existente");
                redirect("edit_magazine_code?rowId=" . $_GET['rowId']);
            } else {

                $obj->updateMagazineCode($_GET['rowId'], $code);

                $this->session->set_flashdata("msg", "Código editado com sucesso");
                redirect("magazineCodes");

            }

        }

        $data["code"] = $magazineCode->password;
        $data['header'] = array('view' => 'templates/customerheader', 'data' => array());
        $data['main_content'] = array('view' => 'customer_view/edit_magazine_code', 'data' => array());
        $data['footer'] = array('view' => 'templates/customerfooter', 'data' => array());
        $this->load->view('templates/common', $data);

    }

    public function tvCampaigns(){

        if ($this->session->userdata('user_id') == "") {
            redirect("customer_login");
        }

        $customerId = $this->session->userdata('user_id');

        $this->load->model("customer/customers_model");
        $obj = new customers_model();

        $obj->set_id($customerId);

        $campaigns = $obj->getCampaigns();

        $data = array();

        $data["campaigns"] = $campaigns;
        $data['header'] = array('view' => 'templates/customerheader', 'data' => array());
        $data['main_content'] = array('view' => 'customer_view/customer_tv_campaigns', 'data' => array());
        $data['footer'] = array('view' => 'templates/customerfooter', 'data' => array());
        $this->load->view('templates/common', $data);

    }

    public function customer_campaign(){

        if ($this->session->userdata('user_id') == "") {
            redirect("customer_login");
        }

        $customerId = $this->session->userdata('user_id');
        $campaignId = $_GET['id'];

        $this->load->model("customer/customers_model");
        $obj = new customers_model();

        $idGroup = "";

        $obj->set_id($customerId);

        if( isset( $campaignId ) ){

            $hasCampaign = $obj->hasCampaign($campaignId);

            if( !$hasCampaign ){
                redirect("tvCampaigns");
            }

            $campaign = $obj->getCampaign($campaignId);
            $campaignContent = $obj->getCampaignContent($campaignId);

            $idGroup = $campaign["id_group"];

        } else {
            redirect("tvCampaigns");
        }

        if( $this->input->post("action") == "save" ){

            $layout = $_POST["layout"];
            $magazines = $_POST["magazine"];
            $magazineArticles = $_POST["magazine_article"];
            $banner1 = $_POST["banner1"];
            $banner2 = $_POST["banner2"];

            $arrayData = array();

            foreach( $magazines as $k => $magazine ){

                $arrayData[$magazine] = array();

                foreach( $magazineArticles[$k] as $article ){
                    $arrayData[$magazine][] = $article;
                }

            }

            $obj->set_id($customerId);
            $idCampaign = $obj->saveCampaign($layout, $idGroup, $campaignId, $banner1, $banner2);
            $obj->saveCampaignContent($idCampaign, $arrayData);

            $this->session->set_flashdata("msg", "Campanha salva com sucesso.");
            redirect("tvCampaigns");

        }

        $data["campaignId"] = $campaignId;
        $data["campaign"] = $campaign;
        $data["campaignContent"] = $campaignContent;
        $data['header'] = array('view' => 'templates/customerheader', 'data' => array());
        $data['main_content'] = array('view' => 'customer_view/customer_campaign', 'data' => array());
        $data['footer'] = array('view' => 'templates/customerfooter', 'data' => array());
        $this->load->view('templates/common', $data);

    }

    public function campaign_group(){

        if ($this->session->userdata('user_id') == "") {
            redirect("customer_login");
        }

        $customerId = $this->session->userdata('user_id');
        $campaignGroupId = !empty( $_GET['id'] ) ? $_GET['id'] : "";

        $this->load->model("customer/customers_model");
        $obj = new customers_model();

        $obj->set_id($customerId);
        $campaigns = $obj->getCampaigns();

        if( !empty( $campaignGroupId ) ){

            $hasCampaign = $obj->hasCampaignGroup($campaignGroupId);

            if( !$hasCampaign ){
                redirect("tvCampaigns");
            }

            $group = $obj->getCampaignGroup($campaignGroupId);
            $groupCampaigns = $obj->getGroupCampaings($campaignGroupId);

            $arrayGroupCampaignId = array();

            foreach( $groupCampaigns as $campaign ){
                $arrayGroupCampaignId[] = $campaign["id"];
            }

        }

        if( $this->input->post("action") == "save" ){

            $name = $_POST["name"];
            $campaigns = $_POST["campaigns"];
            $copyCampaign = $_POST["copyCampaign"];

            $obj->set_id($customerId);
            $obj->saveCampaignGroup($name, $campaigns, $copyCampaign, $campaignGroupId);

            $this->session->set_flashdata("msg", "Agrupamento salvo com sucesso.");
            redirect("tvCampaigns");

        }

        $data["groupCampaigns"] = $arrayGroupCampaignId;
        $data["group"] = $group;
        $data["campaigns"] = $campaigns;
        $data['header'] = array('view' => 'templates/customerheader', 'data' => array());
        $data['main_content'] = array('view' => 'customer_view/customer_campaign_group', 'data' => array());
        $data['footer'] = array('view' => 'templates/customerfooter', 'data' => array());
        $this->load->view('templates/common', $data);

    }

    public function get_magazine_content(){

        if ($this->session->userdata('user_id') == "") {
            redirect("customer_login");
        }

        $counter = $_POST['counter'];

        $this->load->model("customer/customer_magazine_model");
        $obj = new customer_magazine_model();

        $data_result = $obj->getCustomerMagazine();

        $data["magazines"] = $data_result;
        $data["counter"] = $counter;
        $data['main_content'] = array('view' => 'customer_view/select_magazine_content', 'data' => array());
        $this->load->view('templates/only_content', $data);

    }

    public function get_magazine_article_content(){

        if ($this->session->userdata('user_id') == "") {
            redirect("customer_login");
        }

        $customerId = $this->session->userdata('user_id');

        $magazineId = $_POST['magazineId'];
        $counterMagazine = $_POST['counterMagazine'];
        $counterMagazineArticle = $_POST['counterMagazineArticle'];

        $this->load->model("web/magazine_model");
        $obj = new magazine_model();

        $obj->set_id($magazineId);
        $obj->set_customer_id($customerId);

        $data_result = $obj->getArticlesByMagazineFilter();

        $data["articles"] = $data_result;
        $data["counterMagazine"] = $counterMagazine;
        $data["counterMagazineArticle"] = $counterMagazineArticle;
        $data['main_content'] = array('view' => 'customer_view/select_magazine_article_content', 'data' => array());
        $this->load->view('templates/only_content', $data);

    }

}

?>
