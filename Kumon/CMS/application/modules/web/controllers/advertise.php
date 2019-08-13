<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
error_reporting(E_ALL ^ E_NOTICE);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class advertise extends CI_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session', true);
        $this->load->library('pagination');
        $this->load->library('form_validation');
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

    /*     * ADD NEW OPEN ADVERTISE
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Index function load defaut 
     * */

    public function add_advertise() {
        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }
        $advertiseObj = $this->load->model("web/advertise_model");
        $advertiseObj = new advertise_model();

        $language_result = $advertiseObj->getLanguageList();
        $catagory_list = $advertiseObj->getFormCatagoryList();
        $data['catagory_result'] = $catagory_list;
        $magazine_list = $advertiseObj->getMagazineList();
       
        if ($this->input->post("save") == "Save" || $this->input->post("save") == "Publish") {
        //echo '<pre>';print_r($_POST); die;
        //echo '<pre>';print_r($_FILES);die;
            $this->form_validation->set_rules('media_type', 'media_type', 'required');
            // phase 2 remove language field
            //$this->form_validation->set_rules('language', 'language', 'required');
            
           // $this->form_validation->set_rules('catagory', 'catagory', 'required');
            // end phase 2
            $this->form_validation->set_rules('layout_value', 'choose layout', 'required');


            if ($this->form_validation->run() == TRUE) {
                /* SET ALL VALUES */
                if ($this->input->post('link') != "") {

                    $url_pattern = '/^http:\/\/|(www\.)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/';
                    if (!preg_match($url_pattern, $this->input->post('link'))) {

                        $this->session->set_flashdata("msg", $this->lang->line("Please_use_a_valid_link"));
                        redirect("addadvertise");
                    }
                }

                //echo"<pre>"; print_r($_FILES['cover_pic']); die;
                //$advertiseObj->set_title($this->input->post(''));
                $advertiseObj->set_media_type($this->input->post('media_type'));
                $advertiseObj->set_language_id($this->input->post('language'));
                $advertiseObj->set_layout_type($this->input->post('layout_value'));
                $advertiseObj->set_link($this->input->post('link'));
                $advertiseObj->set_customer_id($this->session->userdata('id'));
                
                // phase 2
                $advertiseObj->set_embed_video(trim($this->input->post('embed')));
                if ($this->input->post("save") == "Publish") {
                    $advertiseObj->set_status('1');
                } else {
                    $advertiseObj->set_status('0');
                }
                $advertiseObj->set_publish_date_to($this->input->post('dateto'));
                $advertiseObj->set_publish_date_from($this->input->post('datefrom'));
                $advertiseObj->set_display_time($this->input->post('time'));

                /* SET CATAGORY */
//                $catagory_id = $this->input->post('catagory');
//                $catagory_id_list = implode(",", $catagory_id);
//                $advertiseObj->set_catagory_id($catagory_id_list);
                // for phase 2 work
                $magazine_id = $this->input->post('magazine');
                $magazine_id_list = implode(",", $magazine_id);
                $advertiseObj->set_magazine_id($magazine_id_list);
                
                
                $category_id = $this->input->post('catagory');
                $category_id_list = implode(",", $category_id);
                $advertiseObj->set_catagory_id($category_id_list);
                
                
                /* ----- */
                $advertiseObj->set_size($_FILES['cover_pic']['size']);
                if($this->input->post('embed')!=''){
                    $url =$this->input->post('embed');
                    $advertiseObj->set_cover_image('embed');
                    $advertiseObj->set_embed_video($url);
                    $advertiseObj->set_media_type('2');
                    //uploadEmbededThumb
                    if($_FILES['thumb_embeded']['size'] > 0){
                    $embed_thumb=$this->uploadEmbededThumb('Advertise');
                    //echo $embed_thumb;die;
                    $advertiseObj->setEmbed_thumb($embed_thumb);
                    }
                }else{
                /* FILE UPLOAD COVER PIC */

                //echo "<pre>";print_r($_FILES['cover_pic']);die;
                if ($this->input->post('media_type') == '1') {
                    if ($_FILES['cover_pic']['error'] == 0) {
                        
                        $folderName = 'Advertise';
                        $pathToUpload = './assets/' . $folderName;
                        if (!is_dir($pathToUpload)) {
                            mkdir($pathToUpload, 0777, TRUE);
                        }

                        $config['upload_path'] = './assets/Advertise/';
                        // Location to save the image
                        $config['allowed_types'] = 'gif|jpg|png|jpeg';
                        $config['overwrite'] = FALSE;
                        $config['remove_spaces'] = true;
                        $config['maintain_ratio'] = TRUE;
                        //$config['max_size'] = '0';
                        $config['create_thumb'] = TRUE;
                        //$config['width']  = 300;
                        //$config['height'] = 200;

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

                        if (!$this->upload->do_upload('cover_pic')) {
                            //echo $this->upload->display_errors();

                            $this->session->set_flashdata("msg", $this->lang->line("Image_not_uploaded"));
                            redirect("addadvertise");
                            // redirect  page if the load fails.
                        } else {
                            /* -------- CREATE THUMB ----------- */
                            $config2['image_library'] = 'gd2';
                            //$imgName . $nameImg . "_org";
                            $config2['file_name'] = $this->upload->file_name;
                            $config2['source_image'] = $this->upload->upload_path . $this->upload->file_name;
                            $config2['new_image'] = './assets/Advertise/thumbs/';
                            $config2['maintain_ratio'] = TRUE;
                            //	$config2 ['create_thumb'] = TRUE;
                            $config2['width'] = 80;
                            $config2['height'] = 50;
                            $config2['upload_path'] = './assets/Advertise/thumbs/';

                            $folderName = 'thumbs';
                            $pathToUpload = './assets/Advertise/' . $folderName;
                            if (!is_dir($pathToUpload)) {
                                mkdir($pathToUpload, 0777, TRUE);
                            }
                            $this->load->library('image_lib', $config2);

                            if (!$this->image_lib->resize()) {

                                echo $this->image_lib->display_errors();
                                $msg = $this->lang->line("Image_resize_failed");
                            }
                            $advertiseObj->set_cover_image($this->upload->file_name);
                        }
                        /* SECOND FILE FOR UPLOAD */
                        if ($_FILES['cover_pic1']['error'] == 0) {
                            $folderName = 'Advertise';
                            $pathToUpload = './assets/' . $folderName;
                            if (!is_dir($pathToUpload)) {
                                mkdir($pathToUpload, 0777, TRUE);
                            }

                            $config3['upload_path'] = './assets/Advertise/';
                            // Location to save the image
                            $config3['allowed_types'] = 'gif|jpg|png|jpeg';
                            $config3['overwrite'] = FALSE;
                            $config3['remove_spaces'] = true;
                            $config3['maintain_ratio'] = TRUE;
                            //$config['max_size'] = '0';
                            $config3['create_thumb'] = TRUE;
                            //$config['width']  = 300;
                            //$config['height'] = 200;

                            $imgName = date("Y-m-d");
                            $nameImg = "";
                            $nameChar = "ABCDEFGHIJKLMNOPQRSTYUVWXYZabcdefghijklmnopqrstuvwxyz";

                            for ($i = 0; $i < 3; $i++) {

                                $nameImg .= $nameChar[rand(0, strlen($nameChar))];
                            }
                            $config3['file_name'] = $imgName . $nameImg . "_org";

                            $thumbName = $imgName;
                            $this->load->library('upload', $config3);
                            //codeigniter default function

                            if (!$this->upload->do_upload('cover_pic1')) {
                                //echo $this->upload->display_errors();

                                $this->session->set_flashdata("msg", $this->lang->line("Image_not_uploaded"));
                                redirect("addadvertise");
                                // redirect  page if the load fails.
                            } else {
                                /* -------- CREATE THUMB ----------- */
                                $config4['image_library'] = 'gd2';
                                //$imgName . $nameImg . "_org";
                                $config4['file_name'] = $this->upload->file_name;
                                $config4['source_image'] = $this->upload->upload_path . $this->upload->file_name;
                                $config4['new_image'] = './assets/Advertise/thumbs/';
                                $config4['maintain_ratio'] = TRUE;
                                //	$config2 ['create_thumb'] = TRUE;
                                $config4['width'] = 80;
                                $config4['height'] = 50;
                                $config4['upload_path'] = './assets/Advertise/thumbs/';

                                $folderName = 'thumbs';
                                $pathToUpload = './assets/Advertise/' . $folderName;
                                if (!is_dir($pathToUpload)) {
                                    mkdir($pathToUpload, 0777, TRUE);
                                }
                                $this->load->library('image_lib', $config4);

                                if (!$this->image_lib->resize()) {

                                    echo $this->image_lib->display_errors();
                                    $msg = $this->lang->line("Image_resize_failed");
                                }
                                $advertiseObj->set_portrait_image($this->upload->file_name);
                            }
                        }



                        /* THRID FILE FOR UPLOAD */
                        if ($_FILES['cover_pic2']['error'] == 0) {
                            $folderName = 'Advertise';
                            $pathToUpload = './assets/' . $folderName;
                            if (!is_dir($pathToUpload)) {
                                mkdir($pathToUpload, 0777, TRUE);
                            }

                            $config5['upload_path'] = './assets/Advertise/';
                            // Location to save the image
                            $config5['allowed_types'] = 'gif|jpg|png|jpeg';
                            $config5['overwrite'] = FALSE;
                            $config5['remove_spaces'] = true;
                            $config5['maintain_ratio'] = TRUE;
                            //$config['max_size'] = '0';
                            $config5['create_thumb'] = TRUE;
                            //$config['width']  = 300;
                            //$config['height'] = 200;

                            $imgName = date("Y-m-d");
                            $nameImg = "";
                            $nameChar = "ABCDEFGHIJKLMNOPQRSTYUVWXYZabcdefghijklmnopqrstuvwxyz";

                            for ($i = 0; $i < 3; $i++) {

                                $nameImg .= $nameChar[rand(0, strlen($nameChar))];
                            }
                            $config5['file_name'] = $imgName . $nameImg . "_org";

                            $thumbName = $imgName;
                            $this->load->library('upload', $config5);
                            //codeigniter default function

                            if (!$this->upload->do_upload('cover_pic2')) {
                                //echo $this->upload->display_errors();

                                $this->session->set_flashdata("msg", $this->lang->line("Image_not_uploaded"));
                                redirect("addadvertise");
                                // redirect  page if the load fails.
                            } else {
                                /* -------- CREATE THUMB ----------- */
                                $config6['image_library'] = 'gd2';
                                //$imgName . $nameImg . "_org";
                                $config6['file_name'] = $this->upload->file_name;
                                $config6['source_image'] = $this->upload->upload_path . $this->upload->file_name;
                                $config6['new_image'] = './assets/Advertise/thumbs/';
                                $config6['maintain_ratio'] = TRUE;
                                //	$config2 ['create_thumb'] = TRUE;
                                $config6['width'] = 80;
                                $config6['height'] = 50;
                                $config6['upload_path'] = './assets/Advertise/thumbs/';

                                $folderName = 'thumbs';
                                $pathToUpload = './assets/Advertise/' . $folderName;
                                if (!is_dir($pathToUpload)) {
                                    mkdir($pathToUpload, 0777, TRUE);
                                }
                                $this->load->library('image_lib', $config6);

                                if (!$this->image_lib->resize()) {

                                    echo $this->image_lib->display_errors();
                                    $msg = $this->lang->line("Image_resize_failed");
                                }
                                $advertiseObj->set_landscape_image($this->upload->file_name);
                            }
                        }
                    } else {
                        $this->session->set_flashdata("msg", $this->lang->line("Please_upload_correct_file"));
                        redirect("addadvertise");
                    }
                } 
                elseif ($this->input->post('media_type') == 2) {                    
                  //ini_set('display_errors', 'On');
                  //error_reporting(E_ALL);
                    if ($_FILES['cover_pic']['error'] == 0)
                    $folderName = 'Advertise';
                    $pathToUpload = './assets/' . $folderName;
                    if (!is_dir($pathToUpload)) {
                        mkdir($pathToUpload, 0777, TRUE);
                    }

                    /* UPLOAD  VEDIO TYPE */
                    $config1['upload_path'] = './assets/Advertise/';
                    $config1['allowed_types'] = '*';
                    $config1['max_size'] = '0';
                    $config1['overwrite'] = FALSE;
                    $config1['remove_spaces'] = TRUE;
                    $config1['encrypt_name'] = TRUE;


                    $this->load->library('upload', $config1);
                    $this->upload->initialize($config1);
                    if (!$this->upload->do_upload("cover_pic")) {
                        // If there is any error
                        $err_msgs .= 'Error in Uploading video ' . $this->upload->display_errors() . '<br />';
                        //echo $err_msgs;die;
                    } else {

                        // Thumb
                        $currentTime = date("YmdHis");
                        //echo $currentTime;die;
                        $thumb_pathVideo = "./assets/Advertise/thumbs/";
                        $baseUrlForVideo = base_url() . "assets/Advertise/" . $this->upload->file_name;
                        //echo $baseUrlForVideo;die;
                        $ffmpeg = 'ffmpeg';
                        $video = $baseUrlForVideo;
                        //$image = $this->upload->file_name . 'demo.jpeg';
                        $image = $thumb_pathVideo . $this->upload->file_name . 'demo.jpeg';
                        $thumbUrlPath = $image;

                        // default time to get the image
                        $second = 1;
                        // get the duration and a random place within that
                        $cmd = "$ffmpeg -i $video 2>&1";
                        if (preg_match('/Duration: ((\d+):(\d+):(\d+))/s', `$cmd`, $time)) {
                            $total = ($time[2] * 3600) + ($time[3] * 60) + $time[4];
                            // $second = rand(1, ($total - 1));
                            $second = 1;
                        }
                        // get the screenshot
                        $cmd = shell_exec("$ffmpeg -i $video -deinterlace -an -ss $second -t 00:00:01 -r 1 -y -vcodec mjpeg -f mjpeg  $image 2>&1");
                        //print_r($cmd);die;
                        //End

                        /* FILE NAME AS ARRAY */
                        $advertiseObj->set_cover_image($this->upload->file_name);
                    }
                }
                }
                $advertiseObj->set_language_id($this->input->post('articlelang'));
                $advertiseObj->addAdvertise();
                $this->session->set_flashdata("sus_msg", $this->lang->line("Advertise_created_Successfully"));
                redirect("advertiselisting");
            }
        }
        $data['data_result'] = $magazine_list;
        $data['language_result'] = $language_result;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'advertise/add_advertise', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    /* Advertise listing */

    /**
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Index function load defaut 
     * */
    public function advertise_listing() {
        site_url();

        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }

        $this->load->model("customer/customer_magazine_model");
        $customerMagazineModel = new customer_magazine_model();

        $advertiseObj = $this->load->model("web/advertise_model");
        $advertiseObj = new advertise_model();

        $language_result = $advertiseObj->getLanguageList();        

        /* --------------------------- SORT BY STATUS------------------------ */

        if ($_GET['catagory'] != '') {
            $status = $advertiseObj->set_catagory_id($_GET['catagory']);
        }      
        /* language sort by */

        if ($_GET['lang'] != '') {
            $advertiseObj->set_language_id($_GET['lang']);
        }
        
        $catagory_list = $advertiseObj->getCatagoryList();
        
        /* ----------------- */
        $data_result = $advertiseObj->getAdvertiseList();

        foreach( $data_result as &$article ){
            $article["magazines"] = $customerMagazineModel->getMagazinesNameByMaganizeId($article["magazine_id"]);
        }

        //echo "<pre>";print_r($data_result);die;
        /* SOME FIELDS VALUE */
        $data['all_advertise'] = $advertiseObj->allAdvertise();
        $data['all_deleted_advertise'] = $advertiseObj->allDeletedAdvertise();
        $data['all_publish_advertise'] = $advertiseObj->allPublishedAdvertise();
        $data['all_drafted_advertise'] = $advertiseObj->allDraftedAdvertise();
        $data['all_new_advertise'] = $advertiseObj->allNewAdvertise();
        $data['all_review_advertise'] = $advertiseObj->allReviewAdvertise();
        /* --ALL FOR ADVERTISE INFO---- */

        $data['data_result'] = $data_result;
        $data['catagory_list'] = $catagory_list;
        $data['language_result'] = $language_result;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'advertise/advertise_bord', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    /* Advertise listing */

    /**
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Index function load defaut 
     * */
    public function published_advertise_listing() {
        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }

        $this->load->model("customer/customer_magazine_model");
        $customerMagazineModel = new customer_magazine_model();

        $advertiseObj = $this->load->model("web/advertise_model");
        $advertiseObj = new advertise_model();

        $language_result = $advertiseObj->getLanguageList();
        

        /* --------------------------- SORT BY STATUS------------------------ */

        if ($_GET['catagory'] != '') {
            $status = $advertiseObj->set_catagory_id($_GET['catagory']);
        }
        /* language sort by */

        if ($_GET['lang'] != '') {
            $advertiseObj->set_language_id($_GET['lang']);
        }
        /* SERCH USER NAME */
        if ($_POST['sa'] == 'search' && $_POST['search'] != "") {
            $advertiseObj->set_name($_POST['search']);
        }
        $catagory_list = $advertiseObj->getCatagoryList();
        $advertiseObj->set_status('1');
        $data_result = $advertiseObj->getAdvertiseList();

        foreach( $data_result as &$article ){
            $article["magazines"] = $customerMagazineModel->getMagazinesNameByMaganizeId($article["magazine_id"]);
        }

        /* SOME FIELDS VALUE */
        $data['all_advertise'] = $advertiseObj->allAdvertise();
        $data['all_deleted_advertise'] = $advertiseObj->allDeletedAdvertise();
        $data['all_publish_advertise'] = $advertiseObj->allPublishedAdvertise();
        $data['all_drafted_advertise'] = $advertiseObj->allDraftedAdvertise();
        $data['all_new_advertise'] = $advertiseObj->allNewAdvertise();
        $data['all_review_advertise'] = $advertiseObj->allReviewAdvertise();
        /* --ALL FOR ADVERTISE INFO---- */

        $data['data_result'] = $data_result;
        $data['catagory_list'] = $catagory_list;
        $data['language_result'] = $language_result;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'advertise/publesed_advertise', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    /* Advertise listing */

    /**
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Index function load defaut 
     * */
    public function draft_advertise_listing() {

        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }

        $advertiseObj = $this->load->model("web/advertise_model");
        $advertiseObj = new advertise_model();
        $language_result = $advertiseObj->getLanguageList();        
        /* --------------------------- SORT BY STATUS------------------------ */
        if ($_GET['catagory'] != '') {
            $status = $advertiseObj->set_catagory_id($_GET['catagory']);
        }
        /* language sort by */

        if ($_GET['lang'] != '') {
            $advertiseObj->set_language_id($_GET['lang']);
        }
        $catagory_list = $advertiseObj->getCatagoryList();
        $advertiseObj->set_status('0');
        $data_result = $advertiseObj->getAdvertiseList();




        /* SOME FIELDS VALUE */
        $data['all_advertise'] = $advertiseObj->allAdvertise();
        $data['all_deleted_advertise'] = $advertiseObj->allDeletedAdvertise();
        $data['all_publish_advertise'] = $advertiseObj->allPublishedAdvertise();
        $data['all_drafted_advertise'] = $advertiseObj->allDraftedAdvertise();
        $data['all_new_advertise'] = $advertiseObj->allNewAdvertise();
        $data['all_review_advertise'] = $advertiseObj->allReviewAdvertise();
        /* --ALL FOR ADVERTISE INFO---- */

        $data['data_result'] = $data_result;
        $data['language_result'] = $language_result;
        $data['catagory_list'] = $catagory_list;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'advertise/draft_advertise', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    /** ALL NEW ADVERTISE LIST
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * array of data 
     * */
    public function new_advertise_listing() {

        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }

        $advertiseObj = $this->load->model("web/advertise_model");
        $advertiseObj = new advertise_model();
        /* check Language Session */
        if ($this->session->userdata('lang') != "") {
            $advertiseObj->set_language_id($this->session->userdata('lang'));
        }
        /* ------------ */

        $language_result = $advertiseObj->getLanguageList();        

        /* --------------------------- SORT BY STATUS------------------------ */

        if ($_POST['catagory'] != "") {
            $status = $advertiseObj->set_catagory_id($_POST['catagory']);
        }
        /* SERCH USER NAME */
        if ($_POST['sa'] == 'search' && $_POST['search'] != "") {
            $advertiseObj->set_name($_POST['search']);
        }
        /* language sort by */
        if ($_POST['lang']) {
            $advertiseObj->set_language_id($_POST['lang']);
            $this->session->set_userdata('lang', $_POST['lang']);
        }
        $catagory_list = $advertiseObj->getCatagoryList();

        $data_result = $advertiseObj->getNewAdvertiseList();




        /* SOME FIELDS VALUE */
        $data['all_advertise'] = $advertiseObj->allAdvertise();
        $data['all_deleted_advertise'] = $advertiseObj->allDeletedAdvertise();
        $data['all_publish_advertise'] = $advertiseObj->allPublishedAdvertise();
        $data['all_drafted_advertise'] = $advertiseObj->allDraftedAdvertise();
        $data['all_new_advertise'] = $advertiseObj->allNewAdvertise();
        $data['all_review_advertise'] = $advertiseObj->allReviewAdvertise();
        /* --ALL FOR ADVERTISE INFO---- */

        $data['data_result'] = $data_result;
        $data['language_result'] = $language_result;
        $data['catagory_list'] = $catagory_list;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'advertise/new_advertise', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    /** DELETED ADVERTISE LISTING
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * array 
     * */
    public function deleted_advertise_listing() {
        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }

        $advertiseObj = $this->load->model("web/advertise_model");
        $advertiseObj = new advertise_model();

        $language_result = $advertiseObj->getLanguageList();
        

        /* --------------------------- SORT BY STATUS------------------------ */

        if ($_GET['catagory'] != '') {
            $status = $advertiseObj->set_catagory_id($_GET['catagory']);
        }
        /* language sort by */

        if ($_GET['lang'] != '') {
            $advertiseObj->set_language_id($_GET['lang']);
        }
        $catagory_list = $advertiseObj->getCatagoryList();
        $advertiseObj->set_status('3');
        $data_result = $advertiseObj->getAdvertiseList();

        /* SOME FIELDS VALUE */
        $data['all_advertise'] = $advertiseObj->allAdvertise();
        $data['all_deleted_advertise'] = $advertiseObj->allDeletedAdvertise();
        $data['all_publish_advertise'] = $advertiseObj->allPublishedAdvertise();
        $data['all_drafted_advertise'] = $advertiseObj->allDraftedAdvertise();
        $data['all_new_advertise'] = $advertiseObj->allNewAdvertise();
        $data['all_review_advertise'] = $advertiseObj->allReviewAdvertise();
        /* --ALL FOR ADVERTISE INFO---- */

        $data['data_result'] = $data_result;
        $data['language_result'] = $language_result;
        $data['catagory_list'] = $catagory_list;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'advertise/deleted_advertise', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    /* REview ADvertise Listing */

    /**
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Index function load defaut 
     * */
    public function review_advertise_listing() { 
        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }

        $advertiseObj = $this->load->model("web/advertise_model");
        $advertiseObj = new advertise_model();


        $language_result = $advertiseObj->getLanguageList();
        $magazine_list   = $advertiseObj->getMagazineList();

        /* --------------------------- SORT BY STATUS------------------------ */
        if ($_GET['catagory'] != '') {
            $status = $advertiseObj->set_catagory_id($_GET['catagory']);
        }
        /* language sort by */

        if ($_GET['lang'] != '') {
            $advertiseObj->set_language_id($_GET['lang']);
        }

        $data_result = $advertiseObj->getReviewAdvertiseList();

        /* SOME FIELDS VALUE */
        $data['all_advertise'] = $advertiseObj->allAdvertise();
        $data['all_deleted_advertise'] = $advertiseObj->allDeletedAdvertise();
        $data['all_publish_advertise'] = $advertiseObj->allPublishedAdvertise();
        $data['all_drafted_advertise'] = $advertiseObj->allDraftedAdvertise();
        $data['all_new_advertise']     = $advertiseObj->allNewAdvertise();
        $data['all_review_advertise'] = $advertiseObj->allReviewAdvertise();
        /* --ALL FOR ADVERTISE INFO---- */

        $data['data_result'] = $data_result;
        $data['language_result'] = $language_result;
        $data['magazine_list'] = $magazine_list;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'advertise/review_advertise', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    /** Publish a Ad
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Index function load defaut 
     * */
    public function publish_advertise() {
        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }

        $advertiseObj = $this->load->model("web/advertise_model");
        $advertiseObj = new advertise_model();
        /* check Language Session */
        if ($this->session->userdata('lang') != "") {
            $advertiseObj->set_language_id($this->session->userdata('lang'));
        }
        if ($this->session->userdata('catagory') != "") {
            $advertiseObj->set_language_id($this->session->userdata('catagory'));
        }
        /* ------------ */
        //echo"<pre>"; print_r($_POST); die;
        $advertiseObj->set_publish_date_to($this->input->post('dateto'));
        $advertiseObj->set_publish_date_from($this->input->post('datefrom'));
        $advertiseObj->set_display_time($this->input->post('time'));
        $advertiseObj->set_id($this->input->post('advertise_id'));
        $advertiseObj->publishAAdvertise();
        redirect("publishadvertiselisting");
    }

    /** DELETE A ADVERTISE
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Index function load defaut 
     * */
    public function advertise_delete() {
        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }
        
        $advertiseObj = $this->load->model("web/advertise_model");
        $advertiseObj = new advertise_model();

        $advertise_id = $_GET['rowId'];
        $sourcePath=$_GET['source'];
        $advertiseObj->set_id($advertise_id);
        $advertiseObj->setPath($sourcePath);
        $advertiseObj->deleteAdvertise();
        //echo $this->db->last_query();die;
        if(isset($_GET['page']) && $_GET['page']!=''){
         redirect($_GET['page']);
        }else{
        redirect('advertiselisting');
        }
    }

    /** EDIT A ADVERTISE
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Index function load defaut 
     * */
    public function edit_advertise() {
        //echo"<pre>"; print_r($_REQUEST); die;
        
        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }
        $advertiseObj = $this->load->model("web/advertise_model");
        $advertiseObj = new advertise_model();
        /* check Language Session */
        if ($this->session->userdata('lang') != "") {
            $advertiseObj->set_language_id($this->session->userdata('lang'));
        }
        /* ------------ */
        $language_result = $advertiseObj->getLanguageList();
        $catagory_list = $advertiseObj->getCatagoryList();
        
        $magazine_list = $advertiseObj->getMagazineList();
        //echo "<pre>";print_r($catagory_list);print_r($magazine_list);die;
        $advertise_id = $advertiseObj->set_id($_REQUEST['rowId']);
        $advertiseObj->setPath($_REQUEST['source']);
        $get_ad_details = $advertiseObj->getAdvertiseDetails();
        //echo"<pre>"; print_r($get_ad_details); die;
        if ($this->input->post("save") == "Save" || $this->input->post("save") == "Publish") {
            ///echo "<pre>";print_r($_POST);die;
            $this->form_validation->set_rules('media_type', 'media_type', 'required');
            //$this->form_validation->set_rules('catagory', 'catagory', 'required');
            $this->form_validation->set_rules('layout_value', 'choose layout', 'required');


            if ($this->form_validation->run() == TRUE) {
                /* SET ALL VALUES */
                if ($this->input->post('link') != "") {

                    $url_pattern = '/^http:\/\/|(www\.)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/';
                    if (!preg_match($url_pattern, $this->input->post('link'))) {

                        $this->session->set_flashdata("msg", $this->lang->line("Please_use_a_valid_link"));
                        redirect("advertiselisting");
                    }
                }
                //$advertiseObj->set_title($this->input->post(''));
                $advertiseObj->set_media_type($this->input->post('media_type'));
                $advertiseObj->set_language_id($this->input->post('language'));
                $advertiseObj->set_layout_type($this->input->post('layout_value'));
                $advertiseObj->set_link($this->input->post('link'));
                if ($this->input->post("save") == "Publish") {
                    $advertiseObj->set_status('1');
                } else {
                    $advertiseObj->set_status('0');
                }
                $advertiseObj->set_publish_date_to($this->input->post('dateto'));
                $advertiseObj->set_publish_date_from($this->input->post('datefrom'));
                $advertiseObj->set_display_time($this->input->post('time'));
                /* SET CATAGORY */
                $catagory_id = $this->input->post('catagory');
                $catagory_id_list = implode(",", $catagory_id);
                $advertiseObj->set_catagory_id($catagory_id_list);
                
                $magazine_id = $this->input->post('magazine');
                $magazine_id_list = implode(",", $magazine_id);
                $advertiseObj->set_magazine_id($magazine_id_list);
                $advertiseObj->setPath($this->input->post('sourcePath'));
                $advertiseObj->set_language_id($this->input->post('articlelang'));
                if($this->input->post('embed')!='' && $this->input->post('media_type')=='2'){
                    $url =$this->input->post('embed');
                    
                    $advertiseObj->set_cover_image('embed');
                    $advertiseObj->set_embed_video($url);
                    $advertiseObj->set_media_type('2');
                    if($_FILES['thumb_embeded']['size'] > 0){
                    $embed_thumb=$this->uploadEmbededThumb('Advertise');
                    
                    $advertiseObj->setEmbed_thumb($embed_thumb);
                    }
                    
                }else{ 
                //$advertiseObj->set_size($_FILES['size']);
                /* ----- */
                if ($this->input->post('media_type') == '1') {
                    /* FILE UPLOAD COVER PIC */
                    if ($_FILES['cover_pic']['error'] == 0) {
                        //echo "<pre>";print_r($_FILES);die;
                        $advertiseObj->set_size($_FILES['cover_pic']['size']);

                        $folderName = 'Advertise';
                        $pathToUpload = './assets/' . $folderName;
                        if (!is_dir($pathToUpload)) {
                            mkdir($pathToUpload, 0777, TRUE);
                        }

                        $config['upload_path'] = './assets/Advertise/';
                        // Location to save the image
                        $config['allowed_types'] = 'gif|jpg|png|jpeg';
                        $config['overwrite'] = FALSE;
                        $config['remove_spaces'] = true;
                        $config['maintain_ratio'] = TRUE;
                        //$config['max_size'] = '0';
                        $config['create_thumb'] = TRUE;
                        //$config['width']  = 300;
                        //$config['height'] = 200;

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

                        if (!$this->upload->do_upload('cover_pic')) {
                            //echo $this->upload->display_errors();

                            $this->session->set_flashdata("msg", $this->lang->line("Image_not_uploaded"));
                            redirect("addadvertise");
                            // redirect  page if the load fails.
                        } else {
                            /* -------- CREATE THUMB ----------- */
                            $config2['image_library'] = 'gd2';
                            //$imgName . $nameImg . "_org";
                            $config2['file_name'] = $this->upload->file_name;
                            $config2['source_image'] = $this->upload->upload_path . $this->upload->file_name;
                            $config2['new_image'] = './assets/Advertise/thumbs/';
                            $config2['maintain_ratio'] = TRUE;
                            //	$config2 ['create_thumb'] = TRUE;
                            $config2['width'] = 80;
                            $config2['height'] = 50;
                            $config2['upload_path'] = './assets/Advertise/thumbs/';

                            $folderName = 'thumbs';
                            $pathToUpload = './assets/Advertise/' . $folderName;
                            if (!is_dir($pathToUpload)) {
                                mkdir($pathToUpload, 0777, TRUE);
                            }
                            $this->load->library('image_lib', $config2);

                            if (!$this->image_lib->resize()) {

                                echo $this->image_lib->display_errors();
                                $msg = "Image resize failed";
                            }
                        }

                        $advertiseObj->set_cover_image($this->upload->file_name);                        
                        $advertiseObj->UpdateCoverImage();
                    }
                    /* SECOND FILE FOR UPLOAD */
                    if ($_FILES['cover_pic1']['error'] == 0) {
                        $folderName = 'Advertise/';
                        $pathToUpload = './assets/' . $folderName;
                        if (!is_dir($pathToUpload)) {
                            mkdir($pathToUpload, 0777, TRUE);
                        }

                        $config3['upload_path'] = './assets/Advertise/';
                        // Location to save the image
                        $config3['allowed_types'] = 'gif|jpg|png|jpeg';
                        $config3['overwrite'] = FALSE;
                        $config3['remove_spaces'] = true;
                        $config3['maintain_ratio'] = TRUE;
                        //$config['max_size'] = '0';
                        $config3['create_thumb'] = TRUE;
                        //$config['width']  = 300;
                        //$config['height'] = 200;

                        $imgName = date("Y-m-d");
                        $nameImg = "";
                        $nameChar = "ABCDEFGHIJKLMNOPQRSTYUVWXYZabcdefghijklmnopqrstuvwxyz";

                        for ($i = 0; $i < 3; $i++) {

                            $nameImg .= $nameChar[rand(0, strlen($nameChar))];
                        }
                        $config3['file_name'] = $imgName . $nameImg . "_org";

                        $thumbName = $imgName;
                        $this->load->library('upload', $config3);
                        //codeigniter default function

                        if (!$this->upload->do_upload('cover_pic1')) {
                            //echo $this->upload->display_errors();

                            $this->session->set_flashdata("msg", $this->lang->line("Image_not_uploaded"));
                            redirect("addadvertise");
                            // redirect  page if the load fails.
                        } else {
                            /* -------- CREATE THUMB ----------- */
                            $config4['image_library'] = 'gd2';
                            //$imgName . $nameImg . "_org";
                            $config4['file_name'] = $this->upload->file_name;
                            $config4['source_image'] = $this->upload->upload_path . $this->upload->file_name;
                            $config4['new_image'] = './assets/Advertise/thumbs/';
                            $config4['maintain_ratio'] = TRUE;
                            //	$config2 ['create_thumb'] = TRUE;
                            $config4['width'] = 80;
                            $config4['height'] = 50;
                            $config4['upload_path'] = './assets/Advertise/thumbs/';

                            $folderName = 'thumbs';
                            $pathToUpload = './assets/Advertise/' . $folderName;
                            if (!is_dir($pathToUpload)) {
                                mkdir($pathToUpload, 0777, TRUE);
                            }
                            $this->load->library('image_lib', $config4);

                            if (!$this->image_lib->resize()) {

                                echo $this->image_lib->display_errors();
                                $msg = "Image resize failed";
                            }
                            $advertiseObj->set_portrait_image($this->upload->file_name);
                            $advertiseObj->UpdatePortableImage();
                        }
                    }
                    /* THRID FILE FOR UPLOAD */
                    if ($_FILES['cover_pic2']['error'] == 0) {
                        $folderName = 'Advertise/';
                        $pathToUpload = './assets/' . $folderName;
                        if (!is_dir($pathToUpload)) {
                            mkdir($pathToUpload, 0777, TRUE);
                        }

                        $config5['upload_path'] = './assets/Advertise/';
                        // Location to save the image
                        $config5['allowed_types'] = 'gif|jpg|png|jpeg';
                        $config5['overwrite'] = FALSE;
                        $config5['remove_spaces'] = true;
                        $config5['maintain_ratio'] = TRUE;
                        //$config['max_size'] = '0';
                        $config5['create_thumb'] = TRUE;
                        //$config['width']  = 300;
                        //$config['height'] = 200;

                        $imgName = date("Y-m-d");
                        $nameImg = "";
                        $nameChar = "ABCDEFGHIJKLMNOPQRSTYUVWXYZabcdefghijklmnopqrstuvwxyz";

                        for ($i = 0; $i < 3; $i++) {

                            $nameImg .= $nameChar[rand(0, strlen($nameChar))];
                        }
                        $config5['file_name'] = $imgName . $nameImg . "_org";

                        $thumbName = $imgName;
                        $this->load->library('upload', $config5);
                        //codeigniter default function

                        if (!$this->upload->do_upload('cover_pic2')) {
                            //echo $this->upload->display_errors();

                            $this->session->set_flashdata("msg", $this->lang->line("Image_not_uploaded"));
                            redirect("addadvertise");
                            // redirect  page if the load fails.
                        } else {
                            /* -------- CREATE THUMB ----------- */
                            $config6['image_library'] = 'gd2';
                            //$imgName . $nameImg . "_org";
                            $config6['file_name'] = $this->upload->file_name;
                            $config6['source_image'] = $this->upload->upload_path . $this->upload->file_name;
                            $config6['new_image'] = './assets/Advertise/thumbs/';
                            $config6['maintain_ratio'] = TRUE;
                            //	$config2 ['create_thumb'] = TRUE;
                            $config6['width'] = 80;
                            $config6['height'] = 50;
                            $config6['upload_path'] = './assets/Advertise/thumbs/';

                            $folderName = 'thumbs';
                            $pathToUpload = './assets/Advertise/' . $folderName;
                            if (!is_dir($pathToUpload)) {
                                mkdir($pathToUpload, 0777, TRUE);
                            }
                            $this->load->library('image_lib', $config6);

                            if (!$this->image_lib->resize()) {

                                echo $this->image_lib->display_errors();
                                $msg = "Image resize failed";
                            }
                            $advertiseObj->set_landscape_image($this->upload->file_name);
                            $advertiseObj->UpdateLandscapeImage();
                        }
                    }
                } else {
                    $folderName = 'Advertise';
                    $pathToUpload = './assets/' . $folderName;
                    if (!is_dir($pathToUpload)) {
                        mkdir($pathToUpload, 0777, TRUE);
                    }

                    /* UPLOAD  VEDIO TYPE */
                    $config1['upload_path'] = './assets/Advertise/';
                    //$config1['allowed_types'] = 'mov|mpeg|mp4|avi';
                    $config1['allowed_types'] = '*';
                    $config1['max_size'] = '0';
                    $config1['overwrite'] = FALSE;
                    $config1['remove_spaces'] = TRUE;
                    $config1['encrypt_name'] = TRUE;

                    $advertiseObj->set_size($_FILES['cover_pic']['size']);
                    $this->load->library('upload', $config1);
                    $this->upload->initialize($config1);
                    if (!$this->upload->do_upload("cover_pic")) {
                        // If there is any error
                        $err_msgs .= 'Error in Uploading video ' . $this->upload->display_errors() . '<br />';
                    } else {

                        // Thumb
                        $currentTime = date("YmdHis");
                        //echo $currentTime;die;
                        $thumb_pathVideo = "./assets/Advertise/thumbs/";
                        $baseUrlForVideo = base_url() . "assets/Advertise/" . $this->upload->file_name;
                        //echo $baseUrlForVideo;die;
                        $ffmpeg = 'ffmpeg';
                        $video = $baseUrlForVideo;
                        //$image = $this->upload->file_name . 'demo.jpeg';
                        $image = $thumb_pathVideo . $this->upload->file_name . 'demo.jpeg';
                        $thumbUrlPath = $image;

                        // default time to get the image
                        $second = 1;
                        // get the duration and a random place within that
                        $cmd = "$ffmpeg -i $video 2>&1";
                        if (preg_match('/Duration: ((\d+):(\d+):(\d+))/s', `$cmd`, $time)) {
                            $total = ($time[2] * 3600) + ($time[3] * 60) + $time[4];
                            // $second = rand(1, ($total - 1));
                            $second = 1;
                        }
                        // get the screenshot
                        $cmd = shell_exec("$ffmpeg -i $video -deinterlace -an -ss $second -t 00:00:01 -r 1 -y -vcodec mjpeg -f mjpeg  $image 2>&1");
                        //print_r($cmd);die;
                        //End


                        /* FILE NAME AS ARRAY */
                        $advertiseObj->set_cover_image($this->upload->file_name);
                        $advertiseObj->UpdateCoverImage();
                        
                    }
                }
                }
                
                $advertiseObj->UpdateAdvertise();
                $this->session->set_flashdata("sus_msg", $this->lang->line("Advertise_Updated_Successfully"));
                redirect("advertiselisting");
            }
        }

        $data['ad_result'] = $get_ad_details;
        $data['data_result'] = $catagory_list;
        $data['magazine_list'] = $magazine_list;
        $data['language_result'] = $language_result;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'advertise/edit_advertise', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    /* VIEW A ADVERTISE details
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * array of data 
     * */

    public function view_advertise() { 
        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }

        $advertiseObj = $this->load->model("web/advertise_model");
        $advertiseObj = new advertise_model();
        /* check Language Session */
        if ($this->session->userdata('lang') != "") {
            $advertiseObj->set_language_id($this->session->userdata('lang'));
        }
        /* ------------ */
        /* GETTING AD ID */
        $advertiseObj->set_id($_GET['rowId']);
        $advertiseObj->setPath($_GET['source']);
        $get_ad_details = $advertiseObj->getAdvertiseDetails();
        $adsReport = $advertiseObj->adsReport();
        //print_r($get_ad_details);die;
        $data['ad_result'] = $get_ad_details;
        $data['ads_report'] = $adsReport;
        $data['language_result'] = $language_result;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'advertise/view_advertises', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    /* RESTORE ADVERTISE */
    /* RESTORE AS NEW
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * array of data 
     * */

    public function advertise_restore() {

        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }

        $advertiseObj = $this->load->model("web/advertise_model");
        $advertiseObj = new advertise_model();

        $advertise_id = $_GET['rowId'];
        $sourcePath = $_GET['source'];
        $advertiseObj->set_id($advertise_id);
        $advertiseObj->setPath($sourcePath);
        $advertiseObj->restoreAdvertise();

        redirect('advertiselisting');
    }

    /* PERMANENTLY DELETE ADVERTISE  */
    /* DELETE FROM SYSTEM
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * array of data 
     * */

    public function advertise_perma_delete() {

        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }

        $advertiseObj = $this->load->model("web/advertise_model");
        $advertiseObj = new advertise_model();

        $advertise_id = $_GET['rowId'];
        $sourcePath = $_GET['source'];
        $advertiseObj->set_id($advertise_id);
        $advertiseObj->setPath($sourcePath);
        $advertiseObj->shiftDeleteAdvertise();

        redirect('advertiselisting');
    }

    /* FUNCTION FOR APPROVE OR DECLINE ANY ADVERTISE */
    /* GOES TO APPROVE PAGE
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * array of data 
     * */

    public function approve_decline_article() {

        $advertiseObj = $this->load->model("web/advertise_model");
        $advertiseObj = new advertise_model();
        /* check Language Session */
        if ($this->session->userdata('lang') != "") {
            $advertiseObj->set_language_id($this->session->userdata('lang'));
        }
        /* ------------ */
        /* GETTING AD ID */
        $advertiseObj->set_id($_GET['rowId']);

        $get_ad_details = $advertiseObj->getCustomerAdvertiseDetails();
        //$adsReport = $advertiseObj->adsReport();

        $data['ad_result'] = $get_ad_details;
        $data['ads_report'] = $adsReport;
        $data['language_result'] = $language_result;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'advertise/customer_review_ads', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    /* APPROVE CUSTOMER ADVERTISE */

    /** Action is used for approve a ads
     * comming for review   
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * true/false 
     * */
    public function approve_magazi_advertise() {
        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }
        $advertiseObj = $this->load->model("web/advertise_model");
        $advertiseObj = new advertise_model();
        //echo"<pre>"; print_r($_REQUEST); die;
        $advertiseObj->set_id($_REQUEST['rowId']);
        $data_result = $advertiseObj->ApproveCustomerMagazineAds();
        if ($data_result == TRUE) {
            redirect("reviewadvertiselisting");
        } else {
            $this->session->set_flashdata("msg", $this->lang->line("Magazine_article_not_approved"));
            redirect("reviewadvertiselisting");
        }

        exit();
    }

    /* DECLINE MAGAZINE ADS FOR REVIEW */

    /** Action is used for decline ads
     * from review state   
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * true/false 
     * */
    public function decline_magazi_advertise() {
        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }
        $advertiseObj = $this->load->model("web/advertise_model");
        $advertiseObj = new advertise_model();
        //echo"<pre>"; print_r($_REQUEST); die;
        $advertiseObj->set_id($_REQUEST['rowId']);
        $data_result = $advertiseObj->DeclineCustomerMagazineAds();
        if ($data_result == TRUE) {
            redirect("reviewadvertiselisting");
        } else {
            $this->session->set_flashdata("msg", $this->lang->line("Magazine_ads_not_approved"));
            redirect("reviewadvertiselisting");
        }

        exit();
    }
    
    public function uploadEmbededThumb($folderName) {                              
        $folderRegister = "./assets/$folderName/";                      //folder is used for upload path.

        if (!is_dir($folderRegister)) {
            mkdir($folderRegister, 0777, TRUE);            // providing permission 
        }

        $imgNameRegister = date("Y-m-d");
        $nameImgRegister = "";
        $nameCharRegister = "ABCDEFGHIJKLMNOPQRSTYUVWXYZabcdefghijklmnopqrstuvwxyz";

        for ($i = 0; $i < 3; $i++) {

            $nameImgRegister .= $nameCharRegister[rand(0, strlen($nameCharRegister))];
        }
        $imagenameActual = $_FILES['thumb_embeded']['name'];
        $imagenameRegister = $imgNameRegister . $nameImgRegister . $imagenameActual;

        $serverUrlRegister = $folderRegister . $imagenameRegister;
        //print_r($_FILES);die;
        if ($_FILES['thumb_embeded']['size'] > 0) {                              //checking the condition for uploading of file.
            if (move_uploaded_file($_FILES['thumb_embeded']['tmp_name'], $serverUrlRegister)) {

                $baseUrlForImage = "assets/$folderName/" . $imagenameRegister;
            }
        }
        //echo $baseUrlForImage;die;
        return $baseUrlForImage;
    }

}

?>
