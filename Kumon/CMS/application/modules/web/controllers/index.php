<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class index extends CI_Controller {

    public function __construct() {

        parent::__construct();
        //$this->lang->load('en', 'en');
       $this->load->library('form_validation');
        $this->load->library('email');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->library('session', true);
      // $this->load->library('billCom');
        $this->load->library("pagination");
    }
    
    public function preRegister(){
        $data['select'] = 'tap-home';
        $data['header'] = array('view' => 'templates/header', 'data' => array());
        $data['main_content'] = array('view' => 'admin/homepage', 'data' => array());
        $data['footer'] = array('view' => 'templates/footer', 'data' => array());
        $this->load->view('templates/common_template', $data);
    }
    
    
    public function welcome(){
        echo "here";die;
        $obj = $this->load->model('user_model');
        $obj = new user_model();
        echo $obj->hello();
    }
    
}
