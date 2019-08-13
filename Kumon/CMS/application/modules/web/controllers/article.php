<?php

error_reporting(E_ALL ^ E_NOTICE);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class article extends CI_Controller {

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

    /**This Action is used for
     * add new article
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     message
     * */
    public function add_new_article() {

        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }

        $this->load->model("web/article_model");
        $articleObj = new article_model();

        $language_result = $articleObj->getLanguageList();
        $catagory_list = $articleObj->getFormCatagoryList();

        $this->load->model("customer/customer_magazine_model");
        $magazineObj = new customer_magazine_model();

        $data['customer_magazine']=$magazineObj->getCustomerMagazine();
//        echo "<pre>";
//        print_r($data['customer_magazine']); exit;

        if ($this->input->post("save") == "Save in Draft" || $this->input->post("publish") == "Publish") {
            
            $this->form_validation->set_rules('title', 'title', 'required');

            if ($this->form_validation->run() == TRUE) {

//                echo"<pre>"; print_r($_POST); die;

                $articleObj->set_title(trim($this->input->post("title")));
                $articleObj->set_media_type($this->input->post("media_type"));
                $articleObj->set_language($this->input->post("language"));
                $articleObj->set_description($this->input->post("description"));
                $articleObj->set_tags($this->input->post("tags"));
                $articleObj->set_publish_from($this->input->post("datefrom"));
                $articleObj->set_publish_to($this->input->post("dateto"));
                $articleObj->set_article_language($this->input->post("articlelang"));
                $articleObj->setVia_url($this->input->post("url"));

                $groupsParam =  $this->input->post("group");
                $locationsParam =  $this->input->post("location");
                $disciplineParam =  $this->input->post("discipline");
                $branchParam =  $this->input->post("branch");
                
                if($this->input->post('embed')!=''){

                    $articleObj->set_embed_video(trim($this->input->post('embed')));

                    if($_FILES['thumb_embeded']['size']>0){
                        $embed_video_thumb=  $this->uploadEmbededThumb('Article');
                        $articleObj->setEmbed_video_thumb($embed_video_thumb);
                    }

                }
                
//                if ($this->input->post("share")) {
//                    $articleObj->set_allow_share($this->input->post("share"));
//                } else {
                    $articleObj->set_allow_share('0');
//                }
//                if ($this->input->post("comments")) {
//                    $articleObj->set_allow_comment($this->input->post("comments"));
//                } else {
                    $articleObj->set_allow_comment('0');
//                }
                if ($this->input->post("publish")) {
                    $articleObj->set_status('2');
                } else {
                    $articleObj->set_status('2');
                  /*  $articleObj->set_status('1'); 1 to sent to approve article */
					
                }
                if ($this->input->post('catagory') != "") {
                    $catagory_id = $this->input->post('catagory');
                    $catagory_id_list = implode(",", $catagory_id);
                    $articleObj->set_catagory_id($catagory_id_list);
                } else {
                    $articleObj->set_catagory_id('1');
                }
                if ($this->input->post('magazine') != "") {
                    $magzine_id = $this->input->post('magazine');
                    $magzine_id_list = implode(",", $magzine_id);
                    $articleObj->set_magazine_id($magzine_id_list);
                }
                
                $articleObj->set_customer_id($this->session->userdata('id'));

                /* cover_image['images'] */
                /* FILE UPLOAD COVER PIC */
                if ($_FILES['cover_pic']['error'] == 0) {

                    $folderName = 'Article';
                    $pathToUpload = './assets/' . $folderName;
                    if (!is_dir($pathToUpload)) {
                        mkdir($pathToUpload, 0777, TRUE);
                    }

                    $config['upload_path'] = './assets/Article/';
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
                        echo $this->upload->display_errors();

                        $this->session->set_flashdata("msg", $this->lang->line("Image_not_uploaded"));
                        redirect("addarticle");
                        // redirect  page if the load fails.
                    } else {
                        /* -------- CREATE THUMB ----------- */
                        $config2['image_library'] = 'gd2';
                        //$imgName . $nameImg . "_org";
                        $config2['file_name'] = $this->upload->file_name;
                        $config2['source_image'] = $this->upload->upload_path . $this->upload->file_name;
                        $config2['new_image'] = './assets/Article/thumbs/';
                        $config2['maintain_ratio'] = TRUE;
                        //	$config2 ['create_thumb'] = TRUE;
                        $config2['width'] = 80;
                        $config2['height'] = 50;
                        $config2['upload_path'] = './assets/Article/thumbs/';

                        $folderName = 'thumbs';
                        $pathToUpload = './assets/Article/' . $folderName;
                        if (!is_dir($pathToUpload)) {
                            mkdir($pathToUpload, 0777, TRUE);
                        }
                        $this->load->library('image_lib', $config2);

                        if (!$this->image_lib->resize()) {

                            echo $this->image_lib->display_errors();
                            $msg = "Image resize failed";
                        }
                    }

                    $articleObj->set_image($this->upload->file_name);
                }

                /* UPLOAD  VEDIO TYPE */
                if ($_FILES['video']['error'] == 0) {
                    
                    $folderName = 'Article';
                    $pathToUpload = './assets/' . $folderName;
                    if (!is_dir($pathToUpload)) {
                        mkdir($pathToUpload, 0777, TRUE);
                    }


                    $config1['upload_path'] = './assets/Article/';
                    //$config['allowed_types'] = 'mov|mpeg|mp3|avi';
                    $config1['allowed_types'] = '*';
                    $config1['max_size'] = '50000';
                    $config1['overwrite'] = FALSE;
                    $config1['remove_spaces'] = TRUE;
                    $config1['encrypt_name'] = TRUE;
                    
                    //echo"<pre>"; print_r($_FILES); die;
                    
                    $this->load->library('upload', $config1);
                    $this->upload->initialize($config1);
                    if (!$this->upload->do_upload('video')) {
                        // If there is any error
                        $err_msgs .= 'Error in Uploading video ' . $this->upload->display_errors() . '<br />';
                    } else {
                        /*$data=array('upload_data' => $this->upload->data());
                        $video_path = $data['upload_data']['file_name'];
                        $directory_path = $data['upload_data']['file_path'];
                        $directory_path_full = $data['upload_data']['full_path'];
                        $file_name 		= $data['upload_data']['raw_name'];
                        // ffmpeg command to convert video
                        	exec("ffmpeg -i ".$directory_path_full." ".$directory_path.$file_name.".flv"); 
                            // $file_name is same file name that is being uploaded but you can give your custom video name after converting So use something like myfile.flv.
                            /// In the end update video name in DB 
                            $array = array(
                                'video' => $file_name.'.'.'flv',
                                	);                           
                        /* FILE NAME AS ARRAY */
                        
                            $currentTime = date("YmdHis");
                            //echo $currentTime;die;
                            $thumb_pathVideo = "./assets/Article/thumbs/";
                            $baseUrlForVideo = base_url() . "assets/Article/" . $this->upload->file_name;
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
                            
                        $articleObj->set_video_path($this->upload->file_name);
                    }
                }

                $insert_article = $articleObj->InsertArticle($groupsParam, $locationsParam, $disciplineParam, $branchParam);

                if ($insert_article == TRUE) {
                    $this->session->set_flashdata("sus_msg", $this->lang->line("Article_created_Successfully"));
                    redirect("openarticlelist");
                } else {
                    $this->session->set_flashdata("msg",  $this->lang->line("Article_not_created"));
                    redirect("getnewarticlelist");
                }
            }else {
                    $this->session->set_flashdata("msg",  $this->lang->line("Article_not_created"));
                    redirect("addarticle");
                }
        }

        $this->load->model("web/group_model");
        $model = new group_model();

        $groups = $model->getAllGroups();
        $locations = $model->getAllLocations();
        $disciplines = $model->getAllDisciplines();
        $branches = $model->getAllBranches();

        $data["groups"] = $groups;
        $data["locations"] = $locations;
        $data["branches"] = $branches;
        $data["disciplines"] = $disciplines;
        $data['language_result'] = $language_result;
        $data['catagory_result'] = $catagory_list;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'article/add_article', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    /* EDIT ARTICLE_____ */
    /**This Action is used for
     * edit any article
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     message
     * */

    public function edit_open_article() {

        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }
        $articleObj = $this->load->model("web/article_model");
        $articleObj = new article_model();

        $language_result = $articleObj->getLanguageList();
        $catagory_list = $articleObj->getFormCatagoryList();
        //echo"<pre>"; print_r($_REQUEST); die;
        
        $this->load->model("customer/customer_magazine_model");
        $magazineObj = new customer_magazine_model();
        $data['customer_magazine']=$magazineObj->getCustomerMagazine();
        //print_r($data);die;
        $article_id = $_REQUEST['rowId'];
        $articleObj->set_id($article_id);
        $article_data = $articleObj->getArticleDetails();
        
        if($this->input->post("cancel") == "cancel"){
            redirect("openarticlelist");
        }

        if ($this->input->post("save") == "Save in Draft" || $this->input->post("publish") == "Publish") {
            //echo"<pre>"; print_r($_POST); die;

            $this->form_validation->set_rules('title', 'title', 'required');

            if ($this->form_validation->run() == TRUE) {

                //echo "<pre>";print_r($_POST);die;
                //$articleObj->set_language(trim(addslashes(htmlentities(strip_tags($this->input->post("language"))))));
                $articleObj->set_title(trim($this->input->post("title")));
                $articleObj->set_media_type($this->input->post("media_type"));
                $articleObj->set_language($this->input->post("language"));
                $articleObj->set_description($this->input->post("description"));
                $articleObj->set_tags($this->input->post("tags"));
                $articleObj->set_publish_from($this->input->post("datefrom"));
                $articleObj->set_publish_to($this->input->post("dateto"));
                $articleObj->setVia_url($this->input->post("url"));
                $articleObj->set_created_by($this->input->post("crawlerData"));

                $groupsParam =  $this->input->post("group");
                $locationsParam =  $this->input->post("location");
                $disciplineParam =  $this->input->post("discipline");
                $branchParam =  $this->input->post("branch");

//                echo "<pre>"; print_r($_POST); exit;

                /*new change*/
                $articleObj->set_article_language($this->input->post("articlelang"));
                /*--------*/
//                if ($this->input->post("share")) {
//                    $articleObj->set_allow_share($this->input->post("share"));
//                } else {
                    $articleObj->set_allow_share('0');
//                }
//                if ($this->input->post("comments")) {
//                    $articleObj->set_allow_comment($this->input->post("comments"));
//                } else {
                    $articleObj->set_allow_comment('0');
//                }
                if ($this->input->post("publish")) {
                    $articleObj->set_status('2');
                } else {
                    $articleObj->set_status('2');
					/*  $articleObj->set_status('1'); 1 to sent to approve article */
                }
                if ($this->input->post('catagory') != "") {
                    $catagory_id = $this->input->post('catagory');
                    $catagory_id_list = implode(",", $catagory_id);
                    $articleObj->set_catagory_id($catagory_id_list);
                } else {
                    $articleObj->set_catagory_id('0');
                }
                
                if ($this->input->post('magazine') != "") {
                    $magazine_id = $this->input->post('magazine');
                    $magazine_id_list = implode(",", $magazine_id);
                    
                    $articleObj->set_magazine_id($magazine_id_list);
                } else {
                    $articleObj->set_magazine_id('0');
                }
                if($this->input->post('embed')!=''){ 
                $articleObj->set_embed_video(trim($this->input->post('embed')));
                if($_FILES['thumb_embeded']['size']>0){ 
                $embed_video_thumb=  $this->uploadEmbededThumb('Article');
                
                $articleObj->setEmbed_video_thumb($embed_video_thumb);
                }
                }
                $articleObj->set_id($this->input->post("article_id"));

                /* FILE UPLOAD COVER PIC */
                if ($_FILES['cover_pic']['error'] == 0) {

                    $folderName = 'Article';
                    $pathToUpload = './assets/' . $folderName;
                    if (!is_dir($pathToUpload)) {
                        mkdir($pathToUpload, 0777, TRUE);
                    }

                    $config['upload_path'] = './assets/Article/';
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
                        echo $this->upload->display_errors();

                        $this->session->set_flashdata("msg", $this->lang->line("Image_not_uploaded"));
                        redirect("addarticle");
                        // redirect  page if the load fails.
                    } else {
                        /* -------- CREATE THUMB ----------- */
                        $config2['image_library'] = 'gd2';
                        //$imgName . $nameImg . "_org";
                        $config2['file_name'] = $this->upload->file_name;
                        $config2['source_image'] = $this->upload->upload_path . $this->upload->file_name;
                        $config2['new_image'] = './assets/Article/thumbs/';
                        $config2['maintain_ratio'] = TRUE;
                        //	$config2 ['create_thumb'] = TRUE;
                        $config2['width'] = 80;
                        $config2['height'] = 50;
                        $config2['upload_path'] = './assets/Article/thumbs/';

                        $folderName = 'thumbs';
                        $pathToUpload = './assets/Article/' . $folderName;
                        if (!is_dir($pathToUpload)) {
                            mkdir($pathToUpload, 0777, TRUE);
                        }
                        $this->load->library('image_lib', $config2);

                        if (!$this->image_lib->resize()) {

                            echo $this->image_lib->display_errors();
                            $msg = $this->lang->line("Image_resize_failed");
                        }
                    }

                    $articleObj->set_image($this->upload->file_name);
                }

                /* UPLOAD  VEDIO TYPE */
                if ($_FILES['video']['error'] == 0) {

                    $config['upload_path'] = './assets/Article/';
                    $config['allowed_types'] = '*';
                    $config['max_size'] = '';
                    $config['overwrite'] = FALSE;
                    $config['remove_spaces'] = TRUE;
                    $config['encrypt_name'] = TRUE;

                    
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('video')) {
                        // If there is any error
                        $err_msgs .= 'Error in Uploading video ' . $this->upload->display_errors() . '<br />';
                    } else {
                        $currentTime = date("YmdHis");
                            //echo $currentTime;die;
                            $thumb_pathVideo = "./assets/Article/thumbs/";
                            $baseUrlForVideo = base_url() . "assets/Article/" . $this->upload->file_name;
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
                            
                        $articleObj->set_video_path($this->upload->file_name);
                        $articleObj->UpdateVideoPath();
                    }
                }

                $update_article = $articleObj->UpdateArticle($groupsParam, $locationsParam, $disciplineParam, $branchParam);

                if ($update_article == TRUE) {
                    $this->session->set_flashdata("sus_msg", $this->lang->line("Article_updated_Successfully"));
                    //$url = $_SERVER['HTTP_REFERER'];
                    //redirect($url);
                    redirect("openarticlelist");
                } else {
                    $this->session->set_flashdata("msg", $this->lang->line("Article_not_updated"));
                    redirect("editopenarticle?rowId=" . $article_id);
                }
            }
        }

        $this->load->model("web/group_model");
        $model = new group_model();

        $groups = $model->getAllGroups();
        $locations = $model->getAllLocations();
        $disciplines = $model->getAllDisciplines();
        $branches = $model->getAllBranches();

        $model->setId($article_id);

        $articleGroups = $model->getArticleGroups();
        $arrayGroups = [];

        if( $articleGroups !== false ) {

            foreach ($articleGroups as $group) {
                $arrayGroups[] = $group["group_id"];
            }

        }

        $articleLocation = $model->getArticleLocations();
        $arrayLocations = [];

        if( $articleLocation !== false ){

            foreach( $articleLocation as $location ){
                $arrayLocations[] = $location["location_id"];
            }

        }

        $articleDiscipline = $model->getArticleDisciplines();
        $arrayDisciplines = [];

        if( $articleDiscipline !== false ){

            foreach( $articleDiscipline as $discipline ){
                $arrayDisciplines[] = $discipline["discipline_id"];
            }

        }

        $articleBranch = $model->getArticleBranches();
        $arrayBranches = [];

        if( $articleBranch !== false ){

            foreach( $articleBranch as $branch ){
                $arrayBranches[] = $branch["branch"];
            }

        }

        $data["groups"] = $groups;
        $data["locations"] = $locations;
        $data["disciplines"] = $disciplines;
        $data["branches"] = $branches;
        $data["articleGroups"] = $arrayGroups;
        $data["articleLocation"] = $arrayLocations;
        $data["articleDiscipline"] = $arrayDisciplines;
        $data["articleBranch"] = $arrayBranches;
        $data['language_result'] = $language_result;
        $data['catagory_result'] = $catagory_list;
        $data['article_data'] = $article_data;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'article/edit_open_article', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    /*     * Open Article list
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Index function load defaut 
     * */

    public function open_article() {
        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }
        $articleObj = $this->load->model("web/article_model");
        $articleObj = new article_model();

        $this->load->model("customer/customer_magazine_model");
        $customerMagazineModel = new customer_magazine_model();

        $language_result = $articleObj->getLanguageList();

        /* --------------------------- SORT BY STATUS------------------------ */

        if ($_GET['catagory']!='') {
            $catagory_id = $articleObj->set_catagory_id($_REQUEST['catagory']);

        }
        /* language sort by */
        if ($_GET['lang']!='') {
            $articleObj->set_language($_REQUEST['lang']);
        }
        /*cat_lang*/
        if($_GET['cat_lang']!=''){
           $articleObj->set_article_language($_REQUEST['cat_lang']);
        }

        $catagory_result = $articleObj->getCatagoryList();

        $article_result = $articleObj->articleList();

        $article["magazines"] = "";

        if( !empty($article["magazine_id"]) ){

            foreach( $article_result as &$article ){
                $article["magazines"] = $customerMagazineModel->getMagazinesNameByMaganizeId($article["magazine_id"]);
            }

        }

        /* Banner field value */
        $data['all_article'] = $articleObj->getAllArticle();
        $data['all_publish_article'] = $articleObj->getAllPublishArticle();
        $data['all_new_article'] = $articleObj->getAllNewArticle();
        $data['all_deleted_article'] = $articleObj->getAlldeletedArticle();
        $data['all_drafted_article'] = $articleObj->getAllDraftedArticle();
        /* --------------- */

        $data['data_result'] = $article_result;
        $data['language_result'] = $language_result;
        $data['catagory_result'] = $catagory_result;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'article/open_article_list', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
        //redirect("openarticlelist?lang=".$_REQUEST['lang']."&catagory=".$_REQUEST['catagory']);
    }

    /**/

    /*     * Open ALL NEW Article list
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Index function load defaut 
     * */

    public function get_new_article_list() {
        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }

        $articleObj = $this->load->model("web/article_model");
        $articleObj = new article_model();

        $language_result = $articleObj->getLanguageList();        

        /* --------------------------- SORT BY STATUS------------------------ */
        //echo"<pre>"; print_r($_POST); die;
        if ($_GET['catagory'] != "") {
            $catagory_id = $articleObj->set_catagory_id($_GET['catagory']);
        }
        /* language sort by */
        if ($_GET['lang'] != "") {
            $articleObj->set_language($_GET['lang']);
        }
        $catagory_result = $articleObj->getCatagoryList();

        $article_result = $articleObj->getNewArticleList();

        /* Banner field value */
        $data['all_article'] = $articleObj->getAllArticle();
        $data['all_publish_article'] = $articleObj->getAllPublishArticle();
        $data['all_new_article'] = $articleObj->getAllNewArticle();
        $data['all_deleted_article'] = $articleObj->getAlldeletedArticle();
        $data['all_drafted_article'] = $articleObj->getAllDraftedArticle();
        /* --------------- */


        $data['data_result'] = $article_result;
        $data['language_result'] = $language_result;
        $data['catagory_result'] = $catagory_result;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'article/new_article_list', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    /*     * Open ALL DELETED Article list
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Index function load defaut 
     * */

    public function get_deleted_article_list() {
        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }

        $articleObj = $this->load->model("web/article_model");
        $articleObj = new article_model();

        $language_result = $articleObj->getLanguageList();        

        /* --------------------------- SORT BY STATUS------------------------ */
        
        if ($_GET['catagory'] != "") {
            $catagory_id = $articleObj->set_catagory_id($_GET['catagory']);
        }
        /* language sort by */
        if ($_GET['lang'] != "") {
            $articleObj->set_language($_GET['lang']);
        }
        
        $catagory_result = $articleObj->getCatagoryList();

        $article_result = $articleObj->getDeletedArticleList();

        /* Banner field value */
        $data['all_article'] = $articleObj->getAllArticle();
        $data['all_publish_article'] = $articleObj->getAllPublishArticle();
        $data['all_new_article'] = $articleObj->getAllNewArticle();
        $data['all_deleted_article'] = $articleObj->getAlldeletedArticle();
        $data['all_drafted_article'] = $articleObj->getAllDraftedArticle();
        /* --------------- */


        $data['data_result'] = $article_result;
        $data['language_result'] = $language_result;
        $data['catagory_result'] = $catagory_result;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'article/deleted_article_list', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    /*     * Open ALL Published Article list
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Index function load defaut 
     * */

    public function get_published_article_list() {
        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }

        $articleObj = $this->load->model("web/article_model");
        $articleObj = new article_model();

        $language_result = $articleObj->getLanguageList();       

        /* --------------------------- SORT BY STATUS------------------------ */
       
        if ($_GET['catagory'] != "") {
            $catagory_id = $articleObj->set_catagory_id($_GET['catagory']);
        }
        /* language sort by */
        if ($_GET['lang'] != "") {
            $articleObj->set_language($_GET['lang']);
        }
        /*cat_lang*/
        if($_GET['cat_lang']!=''){
           $articleObj->set_article_language($_REQUEST['cat_lang']); 
        }
        
         $catagory_result = $articleObj->getCatagoryList();

        $article_result = $articleObj->getPublishedArticleList();

        /* Banner field value */
        $data['all_article'] = $articleObj->getAllArticle();
        $data['all_publish_article'] = $articleObj->getAllPublishArticle();
        $data['all_new_article'] = $articleObj->getAllNewArticle();
        $data['all_deleted_article'] = $articleObj->getAlldeletedArticle();
        $data['all_drafted_article'] = $articleObj->getAllDraftedArticle();
        /* --------------- */


        $data['data_result'] = $article_result;
        $data['language_result'] = $language_result;
        $data['catagory_result'] = $catagory_result;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'article/published_article_list', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    /*     * Open ALL Drafted Article list
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Index function load defaut 
     * */

    public function get_draft_article_list() {
        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }

        $articleObj = $this->load->model("web/article_model");
        $articleObj = new article_model();

        $language_result = $articleObj->getLanguageList();        

       /* --------------------------- SORT BY STATUS------------------------ */
       
        if ($_GET['catagory'] != "") {
            $catagory_id = $articleObj->set_catagory_id($_GET['catagory']);
        }
        /* language sort by */
        if ($_GET['lang'] != "") {
            $articleObj->set_language($_GET['lang']);
        }
        
        $catagory_result = $articleObj->getCatagoryList();

        $article_result = $articleObj->getDraftedArticleList();

        /* Banner field value */
        $data['all_article'] = $articleObj->getAllArticle();
        $data['all_publish_article'] = $articleObj->getAllPublishArticle();
        $data['all_new_article'] = $articleObj->getAllNewArticle();
        $data['all_deleted_article'] = $articleObj->getAlldeletedArticle();
        $data['all_drafted_article'] = $articleObj->getAllDraftedArticle();
        /* --------------- */


        $data['data_result'] = $article_result;
        $data['language_result'] = $language_result;
        $data['catagory_result'] = $catagory_result;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'article/drafted_article_list', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    /* DELETE OPEN ARTICLE */    
    /**This Action is used for
     * delete any article
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     message
     * */

    public function delete_open_article() {

        $articleObj = $this->load->model("web/article_model");
        $articleObj = new article_model();
        //echo"<pre>"; print_r($_POST); die;
        $article_id = $_POST['val'];
        $articleObj->set_id($article_id);

        $articleObj->deleteOpenArticle();

        exit();
    }
    
    /*RESTORE A ARTICLE*/     
    /**This Action is used for
     * restore any article
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     message
     * */
    
    public function restore_open_article(){
        
        $articleObj = $this->load->model("web/article_model");
        $articleObj = new article_model();
        //echo"<pre>"; print_r($_POST); die;
        $article_id = $_POST['val'];
        $articleObj->set_id($article_id);

        $articleObj->restoreOpenArticle();

        exit();
   
        
    }
    
    /*PERMANENTLY DELETE*/       
    /**This Action is used for
     * delete from system
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     message
     * */
    
    public function permanent_delete_openarticle(){
        
        $articleObj = $this->load->model("web/article_model");
        $articleObj = new article_model();
        //echo"<pre>"; print_r($_POST); die;
        $article_id = $_POST['val'];
        $articleObj->set_id($article_id);

        $articleObj->shiftDeleteOpenArticle();

        exit();
        
    }
    
    /*PUBLISH ARTICLE*/          
    /**This Action is used for
     * push article as approve
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     message
     * */
    public function publish_article(){
        
        $articleObj = $this->load->model("web/article_model");
        $articleObj = new article_model();
        //echo"<pre>"; print_r($_POST); die;
        $article_id = $_POST['val'];
        $articleObj->set_id($article_id);

        $articleObj->publishOpenArticle();

        exit();
        
    }
    
    /*PuBLISH A ARTICLE AS EDIT PAGE*/
    
    public function publish_open_article(){
        
        $articleObj = $this->load->model("web/article_model");
        $articleObj = new article_model();
        //echo"<pre>"; print_r($_POST); die;
        
        if($this->input->post("cancel") == "cancel"){
            redirect("openarticlelist");
        }

        if ($this->input->post("publish") == "publish") {             
              //echo"<pre>"; print_r($_POST); die;
                $articleObj->set_title(trim($this->input->post("title")));
                //$articleObj->set_media_type($this->input->post("media_type"));
                $articleObj->set_language($this->input->post("language"));
                $articleObj->set_description($this->input->post("description"));
                $articleObj->set_tags($this->input->post("tags"));
                $articleObj->set_publish_from($this->input->post("datefrom"));
                $articleObj->set_publish_to($this->input->post("dateto"));
                $articleObj->set_article_language($this->input->post("articlelang"));
                if ($this->input->post("share")) {
                    $articleObj->set_allow_share($this->input->post("share"));
                } else {
                    $articleObj->set_allow_share('0');
                }
                if ($this->input->post("comments")) {
                    $articleObj->set_allow_comment($this->input->post("comments"));
                } else {
                    $articleObj->set_allow_comment('0');
                }
                if ($this->input->post("publish")) {
                    $articleObj->set_status('2');
                } else {
                    $articleObj->set_status('2');
					/*  $articleObj->set_status('1'); 1 to sent to approve article */
                }
                if ($this->input->post('catagory') != "") {
                    $catagory_id = $this->input->post('catagory');
                    $catagory_id_list = implode(",", $catagory_id);
                    $articleObj->set_catagory_id($catagory_id_list);
                } else {
                    $articleObj->set_catagory_id('1');
                }

                $articleObj->set_id($this->input->post("article_id"));

                //$update_article = $articleObj->UpdateArticle();
                $update_article = $articleObj->UpdateOPENArticle();
                if ($update_article == TRUE) {
                    $this->session->set_flashdata("sus_msg", $this->lang->line("Article_published_Successfully"));
                    //$url = $_SERVER['HTTP_REFERER'];
                    //redirect($url);
                    redirect("openarticlelist");
                }
            }
        }
        
        
        /*SELECT CATAGORY BY LANGUGE NAME*/
        
        public function selectCategoryFromLanguage(){
            $response='';
            if($this->input->is_ajax_request()){
              $id=$this->input->post('id');
              //echo $id;die;
              $selected_category=$this->input->post('selec_cat');
              $selected_catagoryArray = explode(',', $selected_category);
              if($id=='1'){
              $res=$this->db->query("select id,category_name,status,created_date from tbl_category where status='1' AND category_name !=''  ORDER BY category_name");
              }else if($id=='2'){
              $res=$this->db->query("select id,category_name_portuguese as category_name,status,created_date from tbl_category where status='1' AND category_name_portuguese !='' ORDER BY category_name");
              }else if($id=='3'){
              $res=$this->db->query("select id,category_name_spanish as category_name,status,created_date from tbl_category where status='1' AND category_name_spanish !='' ORDER BY category_name");
              }
              //echo $this->db->last_query();die;
              if($res->num_rows()>0){
                  $catagory_result=$res->result_array();
              }else{
                  $catagory_result=array();
              }
              
              if(count($catagory_result)>0){
              foreach ($catagory_result as $value) {
                                $catagory_id = $value['id'];
                                $catagory_name = $value['category_name'];
                                $response.= "<div class=\"cbox\"><input type=\"checkbox\" id=\"catagory[]\" name=\"catagory[]\" ";
                                if (in_array($catagory_id, $selected_catagoryArray)) {  
                                  $response.=  " checked=\"checked\"";  
                                  
                                }   
                                $response.= " class=\"catagory_class\"  value=\"$catagory_id\"/>$catagory_name</div>";
                            }
              
            }
            
            echo $response;die;
              }
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
