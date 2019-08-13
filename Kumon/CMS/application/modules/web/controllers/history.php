<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
error_reporting(E_ALL ^ E_NOTICE);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class history extends CI_Controller {

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
    
    /*GET DELETE MAGAZINE*/
    
    public function delete_history(){
        if ($this->session->userdata('id') == ""){
              redirect("login_check");
          }
        $this->session->unset_userdata('searchUser');
        $historyObj = $this->load->model("web/history_model");
        $historyObj = new history_model();
       
        if($this->input->post("sa") == "search"){
            $historyObj->set_title(trim($this->input->post("title")));
            $this->session->set_userdata('searchUser',$_POST['title']);
        }
        //$language_result = $historyObj->getLanguageList();
        $data_result = $historyObj->getMagazineHistoryList();
        
        //echo"<pre>"; print_r($_POST);
        
        
      
       
        /* --ALL FOR ADVERTISE INFO---- */

        $data['data_result'] = $data_result;
        
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'magazine/history_list', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
        
    }
    
    
    
    
     public function deleted_magizne_artcle(){
        
        $historyObj = $this->load->model("web/history_model");
        $historyObj = new history_model();
        $language_result = $historyObj->getLanguageList();
        $this->session->unset_userdata('searchArticle');
        
        //echo"<pre>"; print_r($_POST); 
        
        if($this->input->post("sa") == "search"){
            $historyObj->set_title(trim($this->input->post("title")));
            $this->session->set_userdata('searchArticle',$_POST['title']);
            $historyObj->set_id($this->input->post("magazine_id"));            
        }
        /* language sort by */
        if ($_GET['lang'] != "") {
            $historyObj->set_language_id($_GET['lang']);
        }
        $magazine_id = $_GET['rowId'];        
        $historyObj->set_id($magazine_id);
        
       
        $data_result = $historyObj->getMagazineArticle();
        $magazine_title = $historyObj->getMagazinetitle();
        /* --ALL FOR ADVERTISE INFO---- */

        $data['data_result'] = $data_result;
        $data['magazine_title'] = $magazine_title;
        $data['language_result'] = $language_result;
        $data['magazine_id'] = $magazine_id;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'magazine/deleted_magazine_article', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
        
    }
    
    
    
    
}
?>
