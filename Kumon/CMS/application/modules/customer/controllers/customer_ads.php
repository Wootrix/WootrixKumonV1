<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
error_reporting(E_ALL ^ E_NOTICE);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class customer_ads extends CI_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('s3_bucket_helper');
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

    /**
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Index function load defaut 
     * */
    public function add_customer_advertise() {

        if ($this->session->userdata('user_id') == "") {
            redirect("customer_login");
        }

        $advertiseObj = $this->load->model("customer/customer_advertise_model");
        $advertiseObj = new customer_advertise_model();

        $advertiseObj->set_customer_id($this->session->userdata('user_id'));
        $language_result = $advertiseObj->getLanguageList();
        $magazine_list = $advertiseObj->getMagazineList();
      
        //echo"<pre>"; print_r($_FILES); die;
        if ($this->input->post("save") == "Save" || $this->input->post("save") == "Publish") {

            $this->form_validation->set_rules('media_type', 'media_type', 'required');
            // for phase2 change
            //$this->form_validation->set_rules('language', 'language', 'required');
            // end phase2 change
            $this->form_validation->set_rules('magazine', 'magazine', 'required');
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
                $advertiseObj->set_media_type($this->input->post('media_type'));
                $advertiseObj->set_language_id($this->input->post('language'));
                $advertiseObj->set_layout_type($this->input->post('layout_value'));
                $advertiseObj->set_link($this->input->post('link'));
                $advertiseObj->set_customer_id($this->session->userdata('user_id'));
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
                $magazine_id = $this->input->post('magazine');
                $magazine_id_list = implode(",", $magazine_id);
                $advertiseObj->set_magazine_id($magazine_id_list);
                /* ----- */
                
                if($this->input->post('embed')!=''){
                    $url =$this->input->post('embed');
                    $advertiseObj->set_cover_image('embed');
                    $advertiseObj->set_embed_video($url);
                    $advertiseObj->set_media_type('2');
                    if($_FILES['thumb_embeded']['size'] > 0){
                    $embed_thumb=$this->uploadEmbededThumb('Advertise');
                    
                    $advertiseObj->setEmbed_thumb($embed_thumb);
                    }
                    
                    
                }else{
                
                $advertiseObj->set_size($_FILES['cover_pic']['size']);
                /* FILE UPLOAD COVER PIC */              

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
                            redirect("addcustomeradvertise");
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

                            //$uploadFile = $pathToUpload.$this->upload->file_name;
                            //$this->uploadImageVideoToS3($uploadFile);
                            //$this->uploadvideoToS3($uploadFile);
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
                                redirect("addcustomeradvertise");
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
                                $this->load->library('image_lib', $config2);

                                if (!$this->image_lib->resize()) {

                                    echo $this->image_lib->display_errors();
                                    $msg = "Image resize failed";
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
                                redirect("addcustomeradvertise");
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
                            }
                         }}else {
                             $this->session->set_flashdata("msg", $this->lang->line("Please_upload_correct_file"));
                             redirect("addcustomeradvertise");}
                    } elseif ($this->input->post('media_type') == '2') {
                        if ($_FILES['cover_pic']['error'] == 0) {
                        $folderName = 'Advertise';
                        $pathToUpload = './assets/' . $folderName;
                        if (!is_dir($pathToUpload)) {
                            mkdir($pathToUpload, 0777, TRUE);
                        }

                        /* UPLOAD  VEDIO TYPE */
                        $config1['upload_path'] = './assets/Advertise/';
                        //$config1['allowed_types'] = 'mov|mpeg|mp4|avi';
                        $config1['allowed_types'] = '*';
                        $config1['max_size'] = '500000000';
                        $config1['overwrite'] = FALSE;
                        $config1['remove_spaces'] = TRUE;
                        $config1['encrypt_name'] = TRUE;


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

                            /* FILE NAME AS ARRAY */
                            $advertiseObj->set_cover_image($this->upload->file_name);
                        }
                    }else {
                             $this->session->set_flashdata("msg", $this->lang->line("Please_upload_correct_file"));
                             redirect("addcustomeradvertise");}
                    }
                }
                $advertiseObj->addCustomerAdvertise();
                $this->session->set_flashdata("susmsg", $this->lang->line("Sucess"));
                redirect("customeradvertiselisting");
            }
        }
        $data['data_result'] = $magazine_list;
        $data['language_result'] = $language_result;
        $data['header'] = array('view' => 'templates/customerheader', 'data' => array());
        $data['main_content'] = array('view' => 'customer_ads/add_customer_ads', 'data' => array());
        $data['footer'] = array('view' => 'templates/customerfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    /* EDIT ADVERTISE */

    /**
     * @author Techahead
     * @access Public
     * @param 
     * @return
     * true/false/message     
     * */
    public function edit_customer_advertise() {

        if ($this->session->userdata('user_id') == "") {
            redirect("customer_login");
        }

        $advertiseObj = $this->load->model("customer/customer_advertise_model");
        $advertiseObj = new customer_advertise_model();
        $advertiseObj->set_customer_id($this->session->userdata('user_id'));
        $language_result = $advertiseObj->getLanguageList();
        $magazine_list = $advertiseObj->getMagazineList();

        if ($this->input->post("save") == "Save" || $this->input->post("save") == "Publish") {
            //echo"<pre>"; print_r($_POST); die;
            $this->form_validation->set_rules('media_type', 'media_type', 'required');
            //$this->form_validation->set_rules('language', 'language', 'required');
            $this->form_validation->set_rules('magazine', 'magazine', 'required');
            $this->form_validation->set_rules('layout_value', 'choose layout', 'required');


            if ($this->form_validation->run() == TRUE) {
                /* SET ALL VALUES */
                if ($this->input->post('link') != "") {

                    $url_pattern = '/^http:\/\/|(www\.)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/';
                    if (!preg_match($url_pattern, $this->input->post('link'))) {

                        $this->session->set_flashdata("msg", "Please use a valid link");
                        redirect("editcustomeradvertise?rowId=" . $this->input->post('ads_id'));
                    }
                }

                //echo"<pre>"; print_r($_FILES['cover_pic']); die;
                //$advertiseObj->set_title($this->input->post(''));
                $advertiseObj->set_media_type($this->input->post('media_type'));
                $advertiseObj->set_language_id($this->input->post('language'));
                $advertiseObj->set_layout_type($this->input->post('layout_value'));
                $advertiseObj->set_link($this->input->post('link'));
                //$advertiseObj->set_status($this->input->post('status'));
                if ($this->input->post("save") == "Publish") {
                    $advertiseObj->set_status('1');
                } else {
                    $advertiseObj->set_status('0');
                }
                
                $advertiseObj->set_customer_id($this->session->userdata('user_id'));
                $advertiseObj->set_id($this->input->post('ads_id'));
                $advertiseObj->set_publish_date_to($this->input->post('dateto'));
                $advertiseObj->set_publish_date_from($this->input->post('datefrom'));
                $advertiseObj->set_display_time($this->input->post('time'));
                /* SET CATAGORY */
                $magazine_id = $this->input->post('magazine');
                $magazine_id_list = implode(",", $magazine_id);
                $advertiseObj->set_magazine_id($magazine_id_list);
                /* ----- */
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
                /* FILE UPLOAD COVER PIC */
                if ($this->input->post('media_type') == '1') {
                    /* FILE UPLOAD COVER PIC */
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

                            $this->session->set_flashdata("msg", "Image not uploaded");
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
                        $advertiseObj->set_size($_FILES['cover_pic']['size']);
                        $advertiseObj->UpdateImagePath();
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

                            $this->session->set_flashdata("msg", "Image not uploaded");
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

                            $this->session->set_flashdata("msg", "Image not uploaded");
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
                    if ($_FILES['cover_pic1']['error'] == 0) {
                    $folderName = 'Advertise';
                    $pathToUpload = './assets/' . $folderName;
                    if (!is_dir($pathToUpload)) {
                        mkdir($pathToUpload, 0777, TRUE);
                    }

                    /* UPLOAD  VEDIO TYPE */
                    $config1['upload_path'] = './assets/Advertise/';
                    $config1['allowed_types'] = '*';
                    //$config1['allowed_types'] = 'mov|mpeg|mp4|avi';
                    $config1['max_size'] = '0';
                    $config1['overwrite'] = FALSE;
                    $config1['remove_spaces'] = TRUE;
                    $config1['encrypt_name'] = TRUE;


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
                        $advertiseObj->set_size($_FILES['cover_pic']['size']);
                        $advertiseObj->UpdateImagePath();
                    }
                }
                }
                }
                $advertiseObj->UpdateCustomerAdvertise();
                $this->session->set_flashdata("susmsg", "Advertise Updated Successfully");
                redirect("customeradvertiselisting");
            }redirect("editcustomeradvertise?rowId=" . $this->input->post('ads_id'));
        }
        $advertise_id = $advertiseObj->set_id($_REQUEST['rowId']);
        $get_ad_details = $advertiseObj->getAdvertiseDetails();        

        $data['data_result'] = $magazine_list;
        $data['language_result'] = $language_result;
        $data['ad_result'] = $get_ad_details;
        $data['header'] = array('view' => 'templates/customerheader', 'data' => array());
        $data['main_content'] = array('view' => 'customer_ads/edit_customer_ads', 'data' => array());
        $data['footer'] = array('view' => 'templates/customerfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    /**
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Index function load defaut 
     * */
    public function customer_advertise_listing() {

        if ($this->session->userdata('user_id') == "") {
            redirect("customer_login");
        }

        $advertiseObj = $this->load->model("customer/customer_advertise_model");
        $advertiseObj = new customer_advertise_model();
        $advertiseObj->set_customer_id($this->session->userdata('user_id'));

        $language_result = $advertiseObj->getLanguageList();
        $magazine_list = $advertiseObj->getMagazineList();


        /* --------------------------- SORT BY STATUS------------------------ */
        //echo"<pre>"; print_r($_POST); 
        if ($_GET['catagory']) {
            $status = $advertiseObj->set_catagory_id($_GET['catagory']);
        }

        /* language sort by */
        if ($_GET['lang']) {
            $advertiseObj->set_language_id($_GET['lang']);
        }
        $data_result = $advertiseObj->getAdvertiseList();

        /* SOME FIELDS VALUE */
        $data['all_advertise'] = $advertiseObj->allAdvertise();
        $data['all_deleted_advertise'] = $advertiseObj->allDeletedAdvertise();
        $data['all_publish_advertise'] = $advertiseObj->allPublishedAdvertise();
        $data['all_drafted_advertise'] = $advertiseObj->allDraftedAdvertise();
        //$data['all_new_advertise'] = $advertiseObj->allNewAdvertise();
        $data['all_review_advertise'] = $advertiseObj->allReviewAdvertise();
        /* --ALL FOR ADVERTISE INFO---- */

        $data['data_result'] = $data_result;
        $data['magazine_list'] = $magazine_list;
        $data['language_result'] = $language_result;
        $data['header'] = array('view' => 'templates/customerheader', 'data' => array());
        $data['main_content'] = array('view' => 'customer_ads/customer_ads_list', 'data' => array());
        $data['footer'] = array('view' => 'templates/customerfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    /* ALL DELETED ADVERTISE LISTING */

    /**
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Index function load defaut 
     * */
    public function customer_deleted_article_list() {

        if ($this->session->userdata('user_id') == "") {
            redirect("customer_login");
        }

        $advertiseObj = $this->load->model("customer/customer_advertise_model");
        $advertiseObj = new customer_advertise_model();
        $advertiseObj->set_customer_id($this->session->userdata('user_id'));

        $language_result = $advertiseObj->getLanguageList();
        $magazine_list = $advertiseObj->getMagazineList();


        /* --------------------------- SORT BY STATUS------------------------ */
        //echo"<pre>"; print_r($_POST); die;
        if ($_GET['catagory']) {
            $status = $advertiseObj->set_catagory_id($_GET['catagory']);
        }

        /* language sort by */
        if ($_GET['lang']) {
            $advertiseObj->set_language_id($_GET['lang']);
        }

        $data_result = $advertiseObj->getCustomerDeletedAdvertiseList();

        /* SOME FIELDS VALUE */
        $data['all_advertise'] = $advertiseObj->allAdvertise();
        $data['all_deleted_advertise'] = $advertiseObj->allDeletedAdvertise();
        $data['all_publish_advertise'] = $advertiseObj->allPublishedAdvertise();
        $data['all_drafted_advertise'] = $advertiseObj->allDraftedAdvertise();
        //$data['all_new_advertise'] = $advertiseObj->allNewAdvertise();
        $data['all_review_advertise'] = $advertiseObj->allReviewAdvertise();
        /* --ALL FOR ADVERTISE INFO---- */

        $data['data_result'] = $data_result;
        $data['magazine_list'] = $magazine_list;
        $data['language_result'] = $language_result;
        $data['header'] = array('view' => 'templates/customerheader', 'data' => array());
        $data['main_content'] = array('view' => 'customer_ads/customer_delete_ads_list', 'data' => array());
        $data['footer'] = array('view' => 'templates/customerfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    /* ALL PUBLISHED ADVERTISE LISTING */

    /**
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Index function load defaut 
     * */
    public function customer_published_article_list() {

        if ($this->session->userdata('user_id') == "") {
            redirect("customer_login");
        }

        $advertiseObj = $this->load->model("customer/customer_advertise_model");
        $advertiseObj = new customer_advertise_model();
        $advertiseObj->set_customer_id($this->session->userdata('user_id'));


        $language_result = $advertiseObj->getLanguageList();
        $magazine_list = $advertiseObj->getMagazineList();


        /* --------------------------- SORT BY STATUS------------------------ */
        //echo"<pre>"; print_r($_POST); die;
        if ($_GET['catagory']) {
            $status = $advertiseObj->set_catagory_id($_GET['catagory']);
        }
        /* SERCH USER NAME */
        if ($_POST['sa'] == 'search' && $_POST['search'] != "") {
            $advertiseObj->set_name($_POST['search']);
        }
        /* language sort by */
        if ($_GET['lang']) {
            $advertiseObj->set_language_id($_GET['lang']);
        }

        $data_result = $advertiseObj->getCustomerPublishAdvertiseList();

        /* SOME FIELDS VALUE */
        $data['all_advertise'] = $advertiseObj->allAdvertise();
        $data['all_deleted_advertise'] = $advertiseObj->allDeletedAdvertise();
        $data['all_publish_advertise'] = $advertiseObj->allPublishedAdvertise();
        $data['all_drafted_advertise'] = $advertiseObj->allDraftedAdvertise();
        //$data['all_new_advertise'] = $advertiseObj->allNewAdvertise();
        $data['all_review_advertise'] = $advertiseObj->allReviewAdvertise();
        /* --ALL FOR ADVERTISE INFO---- */

        $data['data_result'] = $data_result;
        $data['magazine_list'] = $magazine_list;
        $data['language_result'] = $language_result;
        $data['header'] = array('view' => 'templates/customerheader', 'data' => array());
        $data['main_content'] = array('view' => 'customer_ads/customer_publish_ads_list', 'data' => array());
        $data['footer'] = array('view' => 'templates/customerfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    /* ALL PUBLISHED ADVERTISE LISTING */

    /**
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Index function load defaut 
     * */
    public function customer_drafted_article_list() {

        if ($this->session->userdata('user_id') == "") {
            redirect("customer_login");
        }

        $advertiseObj = $this->load->model("customer/customer_advertise_model");
        $advertiseObj = new customer_advertise_model();
        $advertiseObj->set_customer_id($this->session->userdata('user_id'));

        $language_result = $advertiseObj->getLanguageList();
        $magazine_list = $advertiseObj->getMagazineList();


        /* --------------------------- SORT BY STATUS------------------------ */
        //echo"<pre>"; print_r($_POST); die;
        if ($_GET['catagory']) {
            $status = $advertiseObj->set_catagory_id($_GET['catagory']);
        }
        /* SERCH USER NAME */
        if ($_POST['sa'] == 'search' && $_POST['search'] != "") {
            $advertiseObj->set_name($_POST['search']);
        }
        /* language sort by */
        if ($_GET['lang']) {
            $advertiseObj->set_language_id($_GET['lang']);
        }

        $data_result = $advertiseObj->getCustomerDraftedAdvertiseList();

        /* SOME FIELDS VALUE */
        $data['all_advertise'] = $advertiseObj->allAdvertise();
        $data['all_deleted_advertise'] = $advertiseObj->allDeletedAdvertise();
        $data['all_publish_advertise'] = $advertiseObj->allPublishedAdvertise();
        $data['all_drafted_advertise'] = $advertiseObj->allDraftedAdvertise();
        $data['all_review_advertise'] = $advertiseObj->allReviewAdvertise();
        /* --ALL FOR ADVERTISE INFO---- */

        $data['data_result'] = $data_result;
        $data['magazine_list'] = $magazine_list;
        $data['language_result'] = $language_result;
        $data['header'] = array('view' => 'templates/customerheader', 'data' => array());
        $data['main_content'] = array('view' => 'customer_ads/customer_draft_ads_list', 'data' => array());
        $data['footer'] = array('view' => 'templates/customerfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    /* ALL PUBLISHED ADVERTISE LISTING */

    /**
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Index function load defaut 
     * */
    public function customer_review_article_list() {

        if ($this->session->userdata('user_id') == "") {
            redirect("customer_login");
        }

        $advertiseObj = $this->load->model("customer/customer_advertise_model");
        $advertiseObj = new customer_advertise_model();
        $advertiseObj->set_customer_id($this->session->userdata('user_id'));

        $language_result = $advertiseObj->getLanguageList();
        $magazine_list = $advertiseObj->getMagazineList();


        /* --------------------------- SORT BY STATUS------------------------ */
        //echo"<pre>"; print_r($_POST); die;
        if ($_GET['catagory'] != "") {
            $status = $advertiseObj->set_catagory_id($_GET['catagory']);
        }

        /* language sort by */
        if ($_GET['lang'] != "") {
            $advertiseObj->set_language_id($_GET['lang']);
        }

        $data_result = $advertiseObj->getCustomerReviewAdvertiseList();

        /* SOME FIELDS VALUE */
        $data['all_advertise'] = $advertiseObj->allAdvertise();
        $data['all_deleted_advertise'] = $advertiseObj->allDeletedAdvertise();
        $data['all_publish_advertise'] = $advertiseObj->allPublishedAdvertise();
        $data['all_drafted_advertise'] = $advertiseObj->allDraftedAdvertise();
        $data['all_review_advertise'] = $advertiseObj->allReviewAdvertise();
        /* --ALL FOR ADVERTISE INFO---- */

        $data['data_result'] = $data_result;
        $data['magazine_list'] = $magazine_list;
        $data['language_result'] = $language_result;
        $data['header'] = array('view' => 'templates/customerheader', 'data' => array());
        $data['main_content'] = array('view' => 'customer_ads/customer_review_ads_list', 'data' => array());
        $data['footer'] = array('view' => 'templates/customerfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    /* VIEW A ADVERTISE REPORT */

    /**
     * @author Techahead
     * @access Public
     * @param 
     * @return
     * true/false/message     
     * */
    public function view_custo_advertise() {
        if ($this->session->userdata('user_id') == "") {
            redirect("customer_login");
        }

        $advertiseObj = $this->load->model("customer/customer_advertise_model");
        $advertiseObj = new customer_advertise_model();

        /* GETTING AD ID */
        $advertiseObj->set_id($_GET['rowId']);

        $get_ad_details = $advertiseObj->getAdvertiseDetails();
        $adsReport = $advertiseObj->adsReport();

        $data['ad_result'] = $get_ad_details;
        $data['ads_report'] = $adsReport;
        $data['language_result'] = $language_result;
        $data['header'] = array('view' => 'templates/customerheader', 'data' => array());
        $data['main_content'] = array('view' => 'customer_ads/view_custo_advertise', 'data' => array());
        $data['footer'] = array('view' => 'templates/customerfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    /* Publish a advertise */

    public function publish_customer_advertise() {
        if ($this->session->userdata('user_id') == "") {
            redirect("customer_login");
        }

        $advertiseObj = $this->load->model("customer/customer_advertise_model");
        $advertiseObj = new customer_advertise_model();
        $advertiseObj->set_customer_id($this->session->userdata('user_id'));

        //echo"<pre>"; print_r($_POST); die;
        $advertiseObj->set_publish_date_to($this->input->post('dateto'));
        $advertiseObj->set_publish_date_from($this->input->post('datefrom'));
        $advertiseObj->set_display_time($this->input->post('time'));
        $advertiseObj->set_id($this->input->post('advertise_id'));
        $sucess = $advertiseObj->publishCustomerAdvertise();
        if ($sucess) {
            redirect("customeradvertiselisting");
        }
    }

    /* CUSTOMER ADVERTISE DELETE */

    /** DELETE A ADVERTISE
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Index function load defaut 
     * */
    public function customer_advertise_delete() {

        if ($this->session->userdata('user_id') == "") {
            redirect("customer_login");
        }

        $advertiseObj = $this->load->model("customer/customer_advertise_model");
        $advertiseObj = new customer_advertise_model();
        $advertiseObj->set_customer_id($this->session->userdata('user_id'));

        $advertise_id = $_POST['Id'];
        $advertiseObj->set_id($advertise_id);

        $advertiseObj->deleteAdvertise();

        exit();
    }

    /* CUSTOMER ADVERTISE SHIFT DELETE */

    /** DELETE A ADVERTISE
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Index function load defaut 
     * */
    public function customer_ads_shiftdelete() {

        $advertiseObj = $this->load->model("customer/customer_advertise_model");
        $advertiseObj = new customer_advertise_model();
        $advertiseObj->set_customer_id($this->session->userdata('user_id'));

        $advertise_id = $_POST['Id'];
        $advertiseObj->set_id($advertise_id);

        $advertiseObj->shiftDeleteAdvertise();

        exit();
    }

    /* RESTORE A ADS */

    public function restore_customer_ads() {

        $advertiseObj = $this->load->model("customer/customer_advertise_model");
        $advertiseObj = new customer_advertise_model();
        $advertiseObj->set_customer_id($this->session->userdata('user_id'));

        $advertise_id = $_POST['Id'];
        $advertiseObj->set_id($advertise_id);

        $advertiseObj->restoreCustomerAdvertise();

        exit();
    }

    /* SEND FOR REVIEW */

    public function send_ads_forreview() {

        $advertiseObj = $this->load->model("customer/customer_advertise_model");
        $advertiseObj = new customer_advertise_model();
        $advertiseObj->set_customer_id($this->session->userdata('user_id'));

        $advertise_id = $_POST['Id'];
        $advertiseObj->set_id($advertise_id);

        $advertiseObj->sendAdsForReview();

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
