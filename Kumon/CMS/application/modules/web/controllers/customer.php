<?php

error_reporting(E_ALL ^ E_NOTICE);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class customer extends CI_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session', true);
        $this->load->library('pagination');
        $this->load->library('form_validation');
         $languge = $this->session->userdata('language');
        if($languge == ""){
          $this->lang->load('en', 'english');
        }else{
           $this->lang->load($languge, 'english');
        }
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    }



    /* CUSTOMER LIST */
     /**
     * @author Techahead
     * @access Public
     * @param
     * @return array
     * */


    public function customer_list() {
        if ($this->session->userdata('id') == ""){
              redirect("login_check");
          }
        $this->session->unset_userdata('searchUser');
        $customerObj = $this->load->model("web/customer_model");
        $customerObj = new customer_model();

         /* SORT BY STATUS*/
        if($_GET['perId'] != "" && $_GET['perId'] == is_numeric($_GET['perId'])){
            $status = $customerObj->set_status($_GET['perId']);
        }

         /* SERCH USER NAME*/
        if($_POST['sa'] == 'search' && $_POST['username'] !=""){
            $customerObj->set_name(trim($_POST['username']));
            $this->session->set_userdata('searchUser',$_POST['username']);
        }

        $data_result = $customerObj->getCustomerList();

        $data['data_result'] = $data_result;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'admin/customer_bord', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

     /* ADD CUSTOMER */
     /**
     * @author Techahead
     * @access Public
     * @param
     * @return true/false
     * */

    public function add_customer() {
        if ($this->session->userdata('id') == ""){
              redirect("login_check");
          }

        $customerObj = $this->load->model("web/customer_model");
        $customerObj = new customer_model();

        if ($this->input->post("save") == "save") {
            $this->form_validation->set_rules('user_name', 'user_name', 'required');
            $this->form_validation->set_rules('password', 'password', 'required');

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
            $customerObj->set_password(trim($this->input->post("password")));

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

            $user_email_exist_check = $customerObj->userEmailCheck();
             if ($user_email_exist_check == TRUE) {
                $this->session->set_flashdata("msg", $this->lang->line("Email_already_exist"));
                redirect("addcustomer");
             }else{
                 $user_name_exist_check = $customerObj->userNameExistCheck();
                if($user_name_exist_check == TRUE){
                $this->session->set_flashdata("msg", $this->lang->line("User_name_already_exist"));
                redirect("addcustomer");
                }   else{
                $customerObj->InsertCustomerDetails();
                $this->session->set_flashdata("msg", $this->lang->line("USER_ADDED_SUCCESSFULLY"));
                redirect("customers");
            }

             }
        }

        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'admin/add_customer', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }


    /* ADD NEW SUB ADMIN */
     /**
     * @author Techahead
     * @access Public
     * @param
     * @return true/false
     * */


    public function customer_details() {

        $result = '';
        $customerObj = $this->load->model("web/customer_model");
        $customerObj = new customer_model();
        $customerObj->set_id($_POST['Id']);

        $data_result = $customerObj->customerInformation();


        $name = $data_result['name'];
        $gender = $data_result['gender'];
        if ($gender == m) {
            $gender = 'Male';
        } else {
            $gender = 'Female';
        }
        //$dob = $data_result['dob'];
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


    /* DELETE CUSTOMER */
     /**
     * @author Techahead
     * @access Public
     * @param
     * @return true/false
     * */


    public function customer_delete() {

        $customerObj = $this->load->model("web/customer_model");
        $customerObj = new customer_model();
        $customerObj->set_id($_POST['val']);
        //echo"<pre>"; print_r($_POST); die;
        $data_result = $customerObj->deleteCustomer();
        exit();
    }


    /* EDIT CUSTOMER*/
     /**
     * @author Techahead
     * @access Public
     * @param
     * @return true/false
     * */

    public function edit_customer(){
        if ($this->session->userdata('id') == ""){
              redirect("login_check");
          }

        $customerObj = $this->load->model("web/customer_model");
        $customerObj = new customer_model();


        $customerObj->set_id($_GET['rowId']);

        $data_result = $customerObj->customerInformation();

        if ($this->input->post("save") == "save") {

//            print_r( $_POST ); exit;

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
            $customerObj->set_password(trim($this->input->post("password")));

            $autoAcceptArticle = $this->input->post("auto_accept_article");
            $customerObj->setAutoAcceptArticle($autoAcceptArticle);

//            print_r($customerObj); exit;

            $id= $customerObj->set_id($this->input->post("customer_id"));

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
                $customerObj->set_image($this->upload->file_name);
                $customerObj->UpdateCustomerProfileImg();
            }
            $user_name_exist_check = $customerObj->perticuilarUserNameExistCheck();
            if($user_name_exist_check == TRUE){
                $this->session->set_flashdata("msg", $this->lang->line("User_name_already_exist"));
                redirect("editcustomer?rowId=".$_GET['rowId']);
            }else{
                $user_email_exist_check = $customerObj->perticularUserEmailExistCheck();
                if ($user_email_exist_check == TRUE) {
                $this->session->set_flashdata("msg", $this->lang->line("Email_already_exist"));
                redirect("editcustomer?rowId=".$_GET['rowId']);
                }else{
                $customerObj->UpdateCustomerDetails();
                $this->session->set_flashdata("msg", $this->lang->line("User_Edited_Sucessfully"));
                redirect("customers");
                }
            }


        }

        $data['data_result'] = $data_result;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'admin/edit_customer', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);

    }

    /*CUSTOMER LISTING */

    public function customer_magazine_list(){

       if ($this->session->userdata('id') == ""){
              redirect("login_check");
          }

        $customerObj = $this->load->model("web/customer_model");
        $customerObj = new customer_model();

        if($_REQUEST['rowId'] !=""){
           $customerObj->set_id($_REQUEST['rowId']);
        }


        $data_result = $customerObj->getCustomerMagazineList();

        $data['data_result'] = $data_result;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'admin/customer_magazine_list', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);

    }

    public function get_use_report_data_1(){

        $magazines = isset( $_POST["magazines"] ) ? implode( "|", $_POST["magazines"] ) : "";
        $articles = isset( $_POST["articles"] ) ? implode( "|", $_POST["articles"] ) : "";
        $groups = isset( $_POST["groups"] ) ? implode( ",", $_POST["groups"] ) : "";
        $locations = isset( $_POST["locations"] ) ? implode( ",", $_POST["locations"] ) : "";
        $disciplines = isset( $_POST["disciplines"] ) ? implode( ",", $_POST["disciplines"] ) : "";
        $branches = isset( $_POST["branches"] ) ? implode( ",", $_POST["branches"] ) : "";

        $this->load->model("webservices/magazine_access_model");
        $obj = new magazine_access_model();

        $data_result = $obj->getAccessBySoAdmin($magazines, $articles, $groups, $locations, $disciplines, $branches);

        echo json_encode($data_result);
        exit;

    }

    public function get_use_report_data_2(){

        $magazines = isset( $_POST["magazines"] ) ? implode( "|", $_POST["magazines"] ) : "";
        $articles = isset( $_POST["articles"] ) ? implode( "|", $_POST["articles"] ) : "";
        $groups = isset( $_POST["groups"] ) ? implode( ",", $_POST["groups"] ) : "";
        $locations = isset( $_POST["locations"] ) ? implode( ",", $_POST["locations"] ) : "";
        $disciplines = isset( $_POST["disciplines"] ) ? implode( ",", $_POST["disciplines"] ) : "";
        $branches = isset( $_POST["branches"] ) ? implode( ",", $_POST["branches"] ) : "";

        $this->load->model("webservices/magazine_access_model");
        $obj = new magazine_access_model();

        $data_result = $obj->getAccessByTypeAdmin($magazines, $articles, $groups, $locations, $disciplines, $branches);

        echo json_encode($data_result);
        exit;

    }

    public function get_use_report_data_3(){

        $magazines = isset( $_POST["magazines"] ) ? implode( "|", $_POST["magazines"] ) : "";
        $articles = isset( $_POST["articles"] ) ? implode( "|", $_POST["articles"] ) : "";
        $groups = isset( $_POST["groups"] ) ? implode( ",", $_POST["groups"] ) : "";
        $locations = isset( $_POST["locations"] ) ? implode( ",", $_POST["locations"] ) : "";
        $disciplines = isset( $_POST["disciplines"] ) ? implode( ",", $_POST["disciplines"] ) : "";
        $branches = isset( $_POST["branches"] ) ? implode( ",", $_POST["branches"] ) : "";

        $this->load->model("webservices/magazine_access_model");
        $obj = new magazine_access_model();

        $data_result = $obj->getAccessByStateAdmin($magazines, $articles, $groups, $locations, $disciplines, $branches);

        echo json_encode($data_result);
        exit;

    }

    public function get_use_report_data_4(){

        $magazines = isset( $_POST["magazines"] ) ? implode( "|", $_POST["magazines"] ) : "";
        $articles = isset( $_POST["articles"] ) ? implode( "|", $_POST["articles"] ) : "";
        $groups = isset( $_POST["groups"] ) ? implode( ",", $_POST["groups"] ) : "";
        $locations = isset( $_POST["locations"] ) ? implode( ",", $_POST["locations"] ) : "";
        $disciplines = isset( $_POST["disciplines"] ) ? implode( ",", $_POST["disciplines"] ) : "";
        $branches = isset( $_POST["branches"] ) ? implode( ",", $_POST["branches"] ) : "";

        $this->load->model("webservices/magazine_access_model");
        $obj = new magazine_access_model();

        $data_result = $obj->getAccessByMagazineArticleAdmin($magazines, $articles, $groups, $locations, $disciplines, $branches);

        echo json_encode($data_result);
        exit;

    }

    public function get_use_report_data_5(){

        $magazines = isset( $_POST["magazines"] ) ? implode( "|", $_POST["magazines"] ) : "";
        $articles = isset( $_POST["articles"] ) ? implode( "|", $_POST["articles"] ) : "";
        $groups = isset( $_POST["groups"] ) ? implode( ",", $_POST["groups"] ) : "";
        $locations = isset( $_POST["locations"] ) ? implode( ",", $_POST["locations"] ) : "";
        $disciplines = isset( $_POST["disciplines"] ) ? implode( ",", $_POST["disciplines"] ) : "";
        $branches = isset( $_POST["branches"] ) ? implode( ",", $_POST["branches"] ) : "";

        $this->load->model("webservices/magazine_access_model");
        $obj = new magazine_access_model();

        $data_result = $obj->getClosedAccessUsers($magazines, $articles, $groups, $locations, $disciplines, $branches);

        $data = array();

        $data["result"] = $data_result;
        $data['main_content'] = array('view' => 'admin/list_user_use_report', 'data' => array());
        $this->load->view('templates/only_content', $data);

    }

    public function get_use_report_data_6(){

        $categoryId = $_POST["categoryId"];
        $languageId = $_POST["languageId"];
        $articleId = $_POST["articleId"];
        $customerId = $_POST["customerId"];
        $userId = $_POST["userId"];
        $closed = isset( $_POST["closed"] ) ? true : false;

        $this->load->model("webservices/magazine_access_model");
        $obj = new magazine_access_model();

        $obj->setIdArticle($articleId);
        $obj->setIdUser($userId);

        if( $closed ){
            $data_result = $obj->getClosedClientAccess($categoryId, $languageId, $customerId);
        } else {
            $data_result = $obj->getClientAccess($categoryId, $languageId, $customerId);
        }

        echo json_encode($data_result);
        exit;

    }

    public function get_customer_filter(){

        if ($this->session->userdata('id') == ""){
            redirect("login_check");
        }

        $this->load->model("web/customer_model");
        $customerModel = new customer_model();

        $data_result = $customerModel->getCustomerFilterList();

        $data = array();

        $data["show_default_value"] = true;
        $data["result"] = $data_result;
        $data['main_content'] = array('view' => 'admin/select_filter', 'data' => array());
        $this->load->view('templates/only_content', $data);

    }

    public function get_customer_users_filter(){

        if ($this->session->userdata('id') == ""){
            redirect("login_check");
        }

        $customerId = $_POST["customerId"];

        $this->load->model("web/customer_model");
        $customerModel = new customer_model();

        $customerModel->set_id($customerId);

        $data_result = $customerModel->getUsersFilterList();

        $data = array();

        $data["show_default_value"] = true;
        $data["result"] = $data_result;
        $data['main_content'] = array('view' => 'admin/select_filter', 'data' => array());
        $this->load->view('templates/only_content', $data);

    }

}

?>
