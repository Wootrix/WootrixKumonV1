<?php

error_reporting(E_ALL ^ E_NOTICE);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class catagory extends CI_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session', true);
        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->helper('language');
        $this->lang->load('en', 'english');
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    }

    /**
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Index function load defaut 
     * */
    public function index() {

          if ($this->session->userdata('id') == ""){
              redirect("login_check");
          }
    }

    /**
     * THIS ACTION IS USED 
     * FOR CATAGORY LISTING
     * @author Techahead
     * @access Public
     * @param 
     * @return

     * */
    public function catagory_list() {
        
         /*login check*/
        if ($this->session->userdata('id') == ""){
              redirect("login_check");
          }
        /* login check*/
        $catagoryObj = $this->load->model("web/catagory_model");
        $catagoryObj = new catagory_model();
        
        
              
        if ($_GET['lang'] != "" && $_GET['lang'] == is_numeric($_GET['lang'])) {
            $selected_language = $catagoryObj->set_language($_GET['lang']);
            //$this->session->set_userdata('lang', $_POST['lang']);
        }
        $catagory_result = $catagoryObj->getCatagoryList();
        $language_data = $catagoryObj->getLanguageList();

        $data['language_result'] = $language_data;
        $data['data_result'] = $catagory_result;
        //$data['selected_language'] = $selected_language;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'admin/catagory_bord', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    /**
     * THIS ACTION IS USED 
     * FOR CATAGORY LISTING
     * @author Techahead
     * @access Public
     * @param 
     * @return

     * */
    public function language_base_catagory_list() {
        /*login check*/
        if ($this->session->userdata('id') == ""){
              redirect("login_check");
          }
        /* login check*/ 

        $catagoryObj = $this->load->model("web/catagory_model");
        $catagoryObj = new catagory_model();
        $language_data = $catagoryObj->getLanguageList();
        
        $catagoryObj->set_language($_POST['val']);
        if($_GET['lang'] !=""){
             $catagoryObj->set_language($_GET['lang']);
        }
        $catagory_result = $catagoryObj->getCatagoryList();

        $data['language_result'] = $language_data;
        $data['data_result'] = $catagory_result;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'admin/catagory_bord', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    /**
     * THIS ACTION IS USED 
     * ADD NEW CATEGORY
     * @author Techahead
     * @access Public
     * @param 
     * @return

     * */
    public function add_new_catagory() {
        if ($this->session->userdata('id') == ""){
              redirect("login_check");
          }
        $catagoryObj = $this->load->model("web/catagory_model");
        $catagoryObj = new catagory_model();
        
        if($_GET['lang'] !=""){
             $catagoryObj->set_language($_GET['lang']);
        }

        if ($this->input->post("save") == "Save") { 
            //echo "<pre>";print_r($_POST);die;
            $this->form_validation->set_rules('catagory_name_english', 'catagory_name', 'required');
            //if ($this->form_validation->run() == TRUE) {
                //echo "<pre>";print_r($_POST);die;
                
                $catagoryObj->set_category_name($this->input->post("catagory_name_english"));
                $catagoryObj->setCategory_name_spanish($this->input->post("catagory_name_spanish"));
                $catagoryObj->setCategory_name_portuguese($this->input->post("catagory_name_portuguese"));
                
                if($this->input->post("catagory_name_english") !=""){
                    $english_category= $catagoryObj->checkEnglishLanguage();
                    if($english_category != TRUE){
                        $this->session->set_flashdata("msg", $this->lang->line('Catagory_already_exist_in_language'));
                    redirect("languagecatagory?lang=".$_GET['lang']);
                    }
                }
                if($this->input->post("catagory_name_spanish") !=""){
                    $english_category= $catagoryObj->checkSpanishLanguage();
                    if($english_category != TRUE){
                        $this->session->set_flashdata("msg", $this->lang->line('Catagory_already_exist_in_language'));
                    redirect("languagecatagory?lang=".$_GET['lang']);
                    }
                }
                if($this->input->post("catagory_name_portuguese") !=""){
                    $english_category= $catagoryObj->checkPortugeLanguage();
                    if($english_category != TRUE){
                        $this->session->set_flashdata("msg", $this->lang->line('Catagory_already_exist_in_language'));
                    redirect("languagecatagory?lang=".$_GET['lang']);
                    }
                }
                
                
                $catagory_result = $catagoryObj->addNewCatagory();                
                     
                if ($catagory_result == TRUE) { 
                    
                    $this->session->set_flashdata("suc_msg", $this->lang->line('Catagory_added_successfully'));
                    
                    redirect("languagecatagory?lang=".$_GET['lang']); 
                }else{  
                   $this->session->set_flashdata("msg", $this->lang->line('Catagory_already_exist_in_language'));
                    redirect("languagecatagory?lang=".$_GET['lang']); 
                }
            //}
        }

        $catagory_result = $catagoryObj->getCatagoryList();
        $language_data = $catagoryObj->getLanguageList();

        $data['language_result'] = $language_data;
        $data['data_result'] = $catagory_result;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'admin/catagory_bord', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }
   /* public function add_new_catagory() {
        if ($this->session->userdata('id') == ""){
              redirect("login_check");
          }
        $catagoryObj = $this->load->model("web/catagory_model");
        $catagoryObj = new catagory_model();
        
        if($_GET['lang'] !=""){
             $catagoryObj->set_language($_GET['lang']);
        }

        if ($this->input->post("save") == "Save") {   
            $this->form_validation->set_rules('catagory_name', 'catagory_name', 'required');
            if ($this->form_validation->run() == TRUE) {
                //echo"<pre>"; print_r($_POST); die;
                $cat_lang = $_POST['language']['0'];
                $catagoryObj->set_language($cat_lang);
                $catagoryObj->set_category_name(trim(addslashes(strip_tags($this->input->post("catagory_name")))));
                $catagory_check = $catagoryObj->CatagoryExistCheck();
                
                if($catagory_check == True){
                     $catagory_result = $catagoryObj->addNewCatagory();
                if ($catagory_result == TRUE) {
                    
                    $this->session->set_flashdata("suc_msg", $this->lang->line('Catagory_added_successfully'));
                    
                    redirect("languagecatagory?lang=".$_GET['lang']);
                }
                    
                }else{
                   $this->session->set_flashdata("msg", $this->lang->line('Catagory_already_exist_in_language'));
                    redirect("languagecatagory?lang=".$_GET['lang']); 
                }
            }
        }

        $catagory_result = $catagoryObj->getCatagoryList();
        $language_data = $catagoryObj->getLanguageList();

        $data['language_result'] = $language_data;
        $data['data_result'] = $catagory_result;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'admin/catagory_bord', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }
 */
    /**
     * THIS ACTION IS USED 
     * DELETE A CATEGORY
     * @author Techahead
     * @access Public
     * @param 
     * @return

     * */
    public function delete_catagory() {

        $catagoryObj = $this->load->model("web/catagory_model");
        $catagoryObj = new catagory_model();
        $catagoryObj->set_id($_POST['val']);
        $data_result = $catagoryObj->deleteCatagory();
        exit();
    }

    public function get_open_magazine_filter(){

        if ($this->session->userdata('id') == ""){
            redirect("login_check");
        }

        $this->load->model("web/catagory_model");
        $obj = new catagory_model();

        $data_result = $obj->getOpenMagazinesFilterList();

        $data = array();

        $data["show_default_value"] = true;
        $data["result"] = $data_result;
        $data['main_content'] = array('view' => 'admin/select_filter', 'data' => array());
        $this->load->view('templates/only_content', $data);

    }

    public function category_article_filter(){

        if ($this->session->userdata('id') == ""){
            redirect("login_check");
        }

        $categoryId = $_POST["categoryId"];
        $languageId = $_POST["languageId"];

        $this->load->model("web/catagory_model");
        $obj = new catagory_model();

        $obj->set_id($categoryId);

        $data_result = $obj->getCategoryArticlesFilterList($languageId);

        $data = array();

        $data["show_default_value"] = true;
        $data["result"] = $data_result;
        $data['main_content'] = array('view' => 'admin/select_filter', 'data' => array());
        $this->load->view('templates/only_content', $data);

    }

}

?>
