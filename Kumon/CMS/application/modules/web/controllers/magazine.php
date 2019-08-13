<?php

error_reporting(E_ALL ^ E_NOTICE);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class magazine extends CI_Controller
{

    public function __construct()
    {

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
        //$this->lang->line('SUCCESS')
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    }

    /** Action is used for
     * Magazine listing by Admins
     * @author Techahead
     * @access Public
     * @param
     * @return
     * Index function load defaut
     * */
    public function magazine_list()
    {

        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }

        $this->session->unset_userdata('searchUser');
        $this->load->model("web/magazine_model");
        $magazineObj = new magazine_model();

        if ($this->input->post("sa") == "search") {
            $magazineObj->set_title(trim($this->input->post("title")));
            $this->session->set_userdata('searchUser', $_POST['title']);
        }

        $magazine_list = $magazineObj->magazineList();

        $data["locations"] = $magazineObj->getMagazineCountries();
        $data['data_result'] = $magazine_list;
//        $data['roles'] = $magazineObj->getAccessGroups();
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'magazine/magazine_bord', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());

        $this->load->view('templates/common', $data);

    }

    public function add_magazine(){

        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }

        $this->load->model("web/magazine_model");
        $magazineObj = new magazine_model();

        if ($this->session->userdata('lang') != "") {
            $magazineObj->set_language_id($this->session->userdata('lang'));
        }

        if ($this->input->post("save") == "Save") {

            if (!empty($_POST)) {

                $magazineObj->set_id($this->input->post('magazine_id'));

                $magazineObj->set_title($this->input->post('title'));
                $magazineObj->set_publish_date_from($this->input->post('datefrom'));
                $magazineObj->set_publish_date_to($this->input->post('dateto'));
                $magazineObj->set_subscription_password($this->input->post('sub_password'));
                $magazineObj->set_customer_id($this->input->post('usernameHid'));
                $magazineObj->set_bar_color($this->input->post('color'));
                $magazineObj->set_no_of_user($this->input->post('no_user'));
                $magazineObj->setCodeType($this->input->post('codeType'));

                // For Mobile-

                $folder1 = "480800";
                $folder2 = "7201280";
                $folder3 = "10801920";

                // For Tablet:

                $folder4 = "6001024";
                $folder5 = "1024600";
                $folder6 = "8001280";
                $folder7 = "1280800";

                // for iphone

                $folder8 = "320568";
                $folder9 = "1024768";
                $folder10 = "7681024";

                $image_name = time() . "cover_pic";

                if (isset($_FILES['cover_pic1']) && $_FILES['cover_pic1']['name'] != '') {
                    $type = $_FILES['cover_pic1']['type'];
                    $type_array = array();
                    $type_array = explode("/", $type);
                    $image_type = $type_array[1];
                    $image_name1 = $image_name . $folder1 . '.png';
                    $this->imageUpload('cover_pic1', $image_name1);
                }

                if (isset($_FILES['cover_pic2']) && $_FILES['cover_pic2']['name'] != '') {
                    $type = $_FILES['cover_pic2']['type'];
                    $type_array = array();
                    $type_array = explode("/", $type);
                    $image_type = $type_array[1];
                    $image_name2 = $image_name . $folder2 . '.png';
                    $this->imageUpload('cover_pic2', $image_name2);
                }

                if (isset($_FILES['cover_pic3']) && $_FILES['cover_pic3']['name'] != '') {
                    $type = $_FILES['cover_pic3']['type'];
                    $type_array = array();
                    $type_array = explode("/", $type);
                    $image_type = $type_array[1];
                    $image_name3 = $image_name . $folder3 . '.png';
                    $this->imageUpload('cover_pic3', $image_name3);
                }

                if (isset($_FILES['cover_pic4']) && $_FILES['cover_pic4']['name'] != '') {
                    $type = $_FILES['cover_pic4']['type'];
                    $type_array = array();
                    $type_array = explode("/", $type);
                    $image_type = $type_array[1];
                    $image_name4 = $image_name . $folder4 . '.png';
                    $this->imageUpload('cover_pic4', $image_name4);
                }
                if (isset($_FILES['cover_pic5']) && $_FILES['cover_pic5']['name'] != '') {
                    $type = $_FILES['cover_pic5']['type'];
                    $type_array = array();
                    $type_array = explode("/", $type);
                    $image_type = $type_array[1];
                    $image_name5 = $image_name . $folder5 . '.png';
                    $this->imageUpload('cover_pic5', $image_name5);
                }
                if (isset($_FILES['cover_pic6']) && $_FILES['cover_pic6']['name'] != '') {
                    $type = $_FILES['cover_pic6']['type'];
                    $type_array = array();
                    $type_array = explode("/", $type);
                    $image_type = $type_array[1];
                    $image_name6 = $image_name . $folder6 . '.png';
                    $this->imageUpload('cover_pic6', $image_name6);
                }
                if (isset($_FILES['cover_pic7']) && $_FILES['cover_pic7']['name'] != '') {
                    $type = $_FILES['cover_pic7']['type'];
                    $type_array = array();
                    $type_array = explode("/", $type);
                    $image_type = $type_array[1];
                    $image_name7 = $image_name . $folder7 . '.png';
                    $this->imageUpload('cover_pic7', $image_name7);
                }

                if (isset($_FILES['cover_pic8']) && $_FILES['cover_pic8']['name'] != '') {
                    $type = $_FILES['cover_pic8']['type'];
                    $type_array = array();
                    $type_array = explode("/", $type);
                    $image_type = $type_array[1];
                    $image_name8 = $image_name . $folder8 . '.png';
                    $this->imageUpload('cover_pic8', $image_name8);
                }
                if (isset($_FILES['cover_pic9']) && $_FILES['cover_pic9']['name'] != '') {
                    $type = $_FILES['cover_pic9']['type'];
                    $type_array = array();
                    $type_array = explode("/", $type);
                    $image_type = $type_array[1];
                    $image_name9 = $image_name . $folder9 . '.png';
                    $this->imageUpload('cover_pic9', $image_name9);
                }
                if (isset($_FILES['cover_pic10']) && $_FILES['cover_pic10']['name'] != '') {
                    $type = $_FILES['cover_pic10']['type'];
                    $type_array = array();
                    $type_array = explode("/", $type);
                    $image_type = $type_array[1];
                    $image_name10 = $image_name . $folder10 . '.png';
                    $this->imageUpload('cover_pic10', $image_name10);
                }

                $magazineObj->setMagazine_images($image_name);

                if ($this->input->post('magazine_id') != '') {

                    $magazineDetails = $magazineObj->getMagazineDetail();

                    if ($image_name1 == '') {
                        rename("./assets/Magazine_cover/$magazineDetails[magazine_images]$folder1.png", "./assets/Magazine_cover/$image_name$folder1.png");
                    }
                    if ($image_name2 == '') {
                        rename("./assets/Magazine_cover/$magazineDetails[magazine_images]$folder2.png", "./assets/Magazine_cover/$image_name$folder2.png");
                    }
                    if ($image_name3 == '') {
                        rename("./assets/Magazine_cover/$magazineDetails[magazine_images]$folder3.png", "./assets/Magazine_cover/$image_name$folder3.png");
                    }
                    if ($image_name4 == '') {
                        rename("./assets/Magazine_cover/$magazineDetails[magazine_images]$folder4.png", "./assets/Magazine_cover/$image_name$folder4.png");
                    }
                    if ($image_name5 == '') {
                        rename("./assets/Magazine_cover/$magazineDetails[magazine_images]$folder5.png", "./assets/Magazine_cover/$image_name$folder5.png");
                    }
                    if ($image_name6 == '') {
                        rename("./assets/Magazine_cover/$magazineDetails[magazine_images]$folder6.png", "./assets/Magazine_cover/$image_name$folder6.png");
                    }
                    if ($image_name7 == '') {
                        rename("./assets/Magazine_cover/$magazineDetails[magazine_images]$folder7.png", "./assets/Magazine_cover/$image_name$folder7.png");
                    }
                    if ($image_name8 == '') {
                        rename("./assets/Magazine_cover/$magazineDetails[magazine_images]$folder8.png", "./assets/Magazine_cover/$image_name$folder8.png");
                    }
                    if ($image_name9 == '') {
                        rename("./assets/Magazine_cover/$magazineDetails[magazine_images]$folder9.png", "./assets/Magazine_cover/$image_name$folder9.png");
                    }
                    if ($image_name10 == '') {
                        rename("./assets/Magazine_cover/$magazineDetails[magazine_images]$folder10.png", "./assets/Magazine_cover/$image_name$folder10.png");
                    }
                }

                if ($_FILES['cover_pic']['error'] == 0) {

                    $folderName = 'Magazine_cover';
                    $pathToUpload = './assets/' . $folderName;

                    if (!is_dir($pathToUpload)) {
                        mkdir($pathToUpload, 0777, TRUE);
                    }

                    $config['upload_path'] = './assets/Magazine_cover/';

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
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('cover_pic')) {
                        echo $this->upload->display_errors();

                        $this->session->set_flashdata("msg", $this->lang->line("Image_not_uploaded"));
                        redirect("addmagazine");
                    }
                    $file_name = $pathToUpload / $this->upload->file_name;
                    $magazineObj->set_cover_image($this->upload->file_name);
                }

                if ($_FILES['customer_logo']['error'] == 0) {

                    $folderName = 'Magazine_cover/customer_logo/';
                    $pathToUpload = './assets/' . $folderName;
                    if (!is_dir($pathToUpload)) {
                        mkdir($pathToUpload, 0777, TRUE);
                    }

                    $config2['upload_path'] = './assets/Magazine_cover/customer_logo/';
                    // Location to save the image
                    $config2['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config2['overwrite'] = FALSE;
                    $config2['remove_spaces'] = true;
                    $config2['maintain_ratio'] = TRUE;
                    //$config['max_size'] = '0';
                    $config2['create_thumb'] = TRUE;

                    $imgName = date("Y-m-d");
                    $nameImg = "";
                    $nameChar = "ABCDEFGHIJKLMNOPQRSTYUVWXYZabcdefghijklmnopqrstuvwxyz";

                    for ($i = 0; $i < 3; $i++) {

                        $nameImg .= $nameChar[rand(0, strlen($nameChar))];
                    }
                    $config2['file_name'] = $imgName . $nameImg . "_org";

                    $thumbName = $imgName;

                    $this->load->library('upload', $config2);
                    //codeigniter default function
                    $this->upload->initialize($config2);
                    if (!$this->upload->do_upload('customer_logo')) {
                        //echo $this->upload->display_errors(); die;

                        $this->session->set_flashdata("msg", $this->lang->line("Image_not_uploaded"));
                        redirect("addmagazine");
                    }
                    //$file_name =  $pathToUpload/$this->upload->file_name;
                    $magazineObj->set_customer_logo($this->upload->file_name);
                }

                $magazine_result = $magazineObj->addCustomerMagazine();

                $locations = $this->input->post('location');
                $magazineObj->saveMagazineLocations($locations);

//                $arrayGroups = $this->input->post('access_group');
//
//                if( is_array($arrayGroups) && count( $arrayGroups ) > 0 ){
//                    $magazineObj->saveAccessGroup($magazineObj->get_id(), $arrayGroups);
//                }

                if ($this->input->post('magazine_id') != '') {
                    $this->session->set_flashdata("sus_msg", $this->lang->line("Magazine_updated_Successfully"));
                } else {
                    $this->session->set_flashdata("sus_msg", $this->lang->line("Magazine_created_Successfully"));
                }

                redirect("magazinelist");

            }

        }

        $data['data_result'] = $magazine_list;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'magazine/magazine_bord', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);

    }

    /* GET CUSTOMER SUGGEST LIST */

    /** Action is used for
     * @author Techahead
     * @access Public
     * @param
     * @return
     * popdata serch customer result
     * */
    public function customer_suggestion()
    {
        $result = '';
        if ($this->input->is_ajax_request()) {

            $name = $_POST['name'];
            //echo"<pre>"; print_r($_POST); die;
            $magazineObj = $this->load->model("web/magazine_model");
            $magazineObj = new magazine_model();
            $magazineObj->setUserName($name);
            $sujection_list = $magazineObj->customerSuggestionList();
            //echo '<pre>';print_r($sujection_list);die;
            //echo json_encode($sujection_list);
            foreach ($sujection_list as $temp_list) {
                $name = $temp_list['user_name'];
                $id = $temp_list['id'];

                $result .= "<div class='searchBox' onclick='jumpToBox(\"$name\",\"$id\")'>" . $name . "</div>";
            }
            echo $result;
            exit();
        }
    }

    /* GET Magazine Article list */

    /** Action is used for
     * @author Techahead
     * @access Public
     * @param
     * @return
     * magzine article array
     * */
    public function magazine_article_list()
    {
        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }

        $magazineObj = $this->load->model("web/magazine_model");
        $magazineObj = new magazine_model();

        $language_result = $magazineObj->getLanguageList();

        /* language sort by */

        if ($_GET['search'] != "") {
            $status = $magazineObj->set_catagory_id($_GET['search']);
        }

        $catagory_result = $magazineObj->getCatagoryList();

        $magazine_id = $_GET['rowId'];
        $magazineObj->set_id($magazine_id);
        $magazine_article_list = $magazineObj->getMagazineArticleList();
        $magazine_title = $magazineObj->getMagazinetitle();
//        echo "<pre>";
//        print_r($magazine_article_list); die;


        /* -------header value results------------ */
        $data['all_article'] = $magazineObj->getMagazineAllArticleCount();
        $data['magazine_title'] = $magazine_title;
        $data['magazine_publish_article'] = $magazineObj->getMagazinePublishArticle();
        $data['magazine_deleted_article'] = $magazineObj->getMagazineDeletedArticle();
        $data['magazine_review_article'] = $magazineObj->getMagazineReviewArticle();
        /* ---------------------------------------- */

        $data['data_result'] = $magazine_article_list;
        $data['language_result'] = $language_result;
        $data['magazine_title'] = $magazine_title;
        $data['magazine_id'] = $magazine_id;
        $data['catagory_result'] = $catagory_result;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'magazine/magazine_article', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    public function change_order(){

        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }

        $articleId = $_POST["articleId"];
        $magazineId = $_POST["magazineId"];
        $oldOrder = $_POST["oldOrder"];
        $newOrder = $_POST["newOrder"];

        $this->load->model("web/magazine_model");
        $magazineObj = new magazine_model();

        $magazineObj->changeArticleOrder($articleId, $magazineId, $oldOrder, $newOrder);


    }

    /* Magazine published article list */

    /**
     * used for admin login
     * GET MAGAZINE IN PUBLISHED STATUS
     * @author Techahead
     * @access Public
     * @param
     * @return
     * Index function load defaut
     * */
    public function magazine_published_article_list()
    {
        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }

        $magazineObj = $this->load->model("web/magazine_model");
        $magazineObj = new magazine_model();

        /* check Language Session */
        if ($this->session->userdata('lang') != "") {
            $magazineObj->set_language_id($this->session->userdata('lang'));
        }
        /* ------------ */

        $language_result = $magazineObj->getLanguageList();

        /* language sort by */
        if ($_GET['search'] != "") {
            $status = $magazineObj->set_catagory_id($_GET['search']);
        }

        $catagory_result = $magazineObj->getCatagoryList();

        $magazine_id = $_GET['rowId'];
        $magazineObj->set_id($magazine_id);
        $magazine_article_list = $magazineObj->getMagazinePublishedArticleList();
        $magazine_title = $magazineObj->getMagazinetitle();


        /* -------header value results------------ */
        $data['all_article'] = $magazineObj->getMagazineAllArticleCount();
        $data['magazine_publish_article'] = $magazineObj->getMagazinePublishArticle();
        $data['magazine_deleted_article'] = $magazineObj->getMagazineDeletedArticle();
        $data['magazine_review_article'] = $magazineObj->getMagazineReviewArticle();
        /* ---------------------------------------- */

        $data['data_result'] = $magazine_article_list;
        $data['magazine_title'] = $magazine_title;
        $data['language_result'] = $language_result;
        $data['magazine_title'] = $magazine_title;
        $data['magazine_id'] = $magazine_id;
        $data['catagory_result'] = $catagory_result;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'magazine/magazine_publish_article', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    /* Magazine deleted article list */

    /**
     * Magazine deleted Article list
     * @author Techahead
     * @access Public
     * @param
     * @return
     * Index function load defaut
     * */
    public function magazine_deleted_article_list()
    {
        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }

        $magazineObj = $this->load->model("web/magazine_model");
        $magazineObj = new magazine_model();

        $language_result = $magazineObj->getLanguageList();
        $catagory_result = $magazineObj->getCatagoryList();

        /* language sort by */
        if ($_GET['search'] != "") {
            $status = $magazineObj->set_catagory_id($_GET['search']);
        }


        $magazine_id = $_GET['rowId'];
        $magazineObj->set_id($magazine_id);
        $magazine_article_list = $magazineObj->getMagazineDeletedArticleList();
        $magazine_title = $magazineObj->getMagazinetitle();


        /* -------header value results------------ */
        $data['all_article'] = $magazineObj->getMagazineAllArticleCount();
        $data['magazine_publish_article'] = $magazineObj->getMagazinePublishArticle();
        $data['magazine_deleted_article'] = $magazineObj->getMagazineDeletedArticle();
        $data['magazine_review_article'] = $magazineObj->getMagazineReviewArticle();
        /* ---------------------------------------- */

        $data['data_result'] = $magazine_article_list;
        $data['language_result'] = $language_result;
        $data['magazine_title'] = $magazine_title;
        $data['magazine_id'] = $magazine_id;
        $data['catagory_result'] = $catagory_result;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'magazine/magazine_deleted_article', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    /* Magazine review article list */

    /**
     * Magzine article list
     * in Review state
     * @author Techahead
     * @access Public
     * @param
     * @return
     * Index function load defaut
     * */
    public function magazine_review_article_list()
    {
        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }

        $magazineObj = $this->load->model("web/magazine_model");
        $magazineObj = new magazine_model();

        $language_result = $magazineObj->getLanguageList();
        $catagory_result = $magazineObj->getCatagoryList();

        /* language sort by */
        if ($_GET['search'] != "") {
            $status = $magazineObj->set_catagory_id($_GET['search']);
        }


        $magazine_id = $_GET['rowId'];
        $magazineObj->set_id($magazine_id);
        $magazine_article_list = $magazineObj->getMagazineReviewArticleList();
        $magazine_title = $magazineObj->getMagazinetitle();

        /* -------header value results------------ */
        $data['all_article'] = $magazineObj->getMagazineAllArticleCount();
        $data['magazine_publish_article'] = $magazineObj->getMagazinePublishArticle();
        $data['magazine_deleted_article'] = $magazineObj->getMagazineDeletedArticle();
        $data['magazine_review_article'] = $magazineObj->getMagazineReviewArticle();
        /* ---------------------------------------- */

        $data['data_result'] = $magazine_article_list;
        $data['language_result'] = $language_result;
        $data['magazine_title'] = $magazine_title;
        $data['magazine_id'] = $magazine_id;
        $data['catagory_result'] = $catagory_result;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'magazine/magazine_review_article', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    /* DELETE A MAGAZINE */

    /**
     * used for delete a magazine
     * @author Techahead
     * @access Public
     * @param
     * @return
     * Index function load defaut
     * */
    public function delete_magazine()
    {

        $magazineObj = $this->load->model("web/magazine_model");
        $magazineObj = new magazine_model();
        $magazineObj->set_id($_POST['val']);
        $data_result = $magazineObj->deleteMagazine();
        exit();
    }

    /* Delete magzine Ads */

    /** Action is used for
     * @author Techahead
     * @access Public
     * @param
     * @return
     * true/false
     * */
    public function delete_magzine_ads()
    {

        $magazineObj = $this->load->model("web/magazine_model");
        $magazineObj = new magazine_model();
        $magazineObj->set_id($_GET['val']);
        $magazineObj->setSource($_GET['source']);
        $data_result = $magazineObj->deleteMagazineArticle();
        //echo $this->session->userdata('user_id');die;
        if ($this->session->userdata('user_id') != '') {
            redirect('customer_articlelist?&rowId=' . $_GET['rowId']);
        } else {
            redirect('magazinearticlelist?&rowId=' . $_GET['rowId']);
        }
    }

    /* REVIEW MAGAZINE ARTICLE */

    /** Action is used for review
     * magazine Article
     * @author Techahead
     * @access Public
     * @param
     * @return
     * Index function load defaut
     * */
    public function review_magazine_article()
    {
        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }

        $magazineObj = $this->load->model("web/magazine_model");
        $magazineObj = new magazine_model();

        /* GET ARTICLE ID */
        $article_id = $_GET['rowId'];
        $magazineObj->set_id($article_id);
        $magazineObj->setSource($_GET['source']);
        $data_result = $magazineObj->GetMagazineArticleDetails();

        $data['data_result'] = $data_result;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'magazine/review_article', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    /* Approved Magazine Article */

    /** Action is used for
     * @author Techahead
     * @access Public
     * @param
     * @return
     * true/false
     * */
    public function approve_magaz_article()
    {
        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }
        $magazineObj = $this->load->model("web/magazine_model");
        $magazineObj = new magazine_model();
        //echo"<pre>"; print_r($_REQUEST); die;
        $magazineObj->set_id($_REQUEST['rowId']);
        $magazineObj->setSource($_REQUEST['source']);
        $data_result = $magazineObj->ApproveMagazineArticle();
        if ($data_result == TRUE) {
            redirect("magazinereviewarticlelist?rowId=" . $_REQUEST['magId']);
        } else {
            $this->session->set_flashdata("msg", "Magazine article not approved");
            redirect("reviewmagazinearticle?rowId=" . $_REQUEST['rowId'] . "&magId=" . $_REQUEST['magId']);
        }

        exit();
    }

    /* Reject Magazine Article */

    /** Action is used for
     * @author Techahead
     * @access Public
     * @param
     * @return
     * true/false
     * */
    public function reject_magazine_article()
    {

        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }

        $magazineObj = $this->load->model("web/magazine_model");
        $magazineObj = new magazine_model();
        //echo"<pre>"; print_r($_POST); die;

        if ($this->input->post("send") == "Send") {
            $this->form_validation->set_rules('rejection_report', 'rejection_report', 'required');
            $this->form_validation->set_rules('article_id', 'article_id', 'required');
            if ($this->form_validation->run() == TRUE) {

                $magazineObj->set_article_id($this->input->post('article_id'));
                $magazineObj->set_article_report($this->input->post('rejection_report'));
                $data_result = $magazineObj->DeclineMagazineArticle();
                $this->session->set_flashdata("sus_msg", $this->lang->line("Article_decline_report_submitted"));
                redirect("magazinereviewarticlelist?rowId=" . $_REQUEST['magId']);
            } else {
                //redirect("reviewmagazinearticle?rowId=" . $this->input->post('article_id'));
                redirect("reviewmagazinearticle?rowId=" . $_REQUEST['rowId'] . "&magId=" . $_REQUEST['magid']);
            }
        } else {
            //redirect("reviewmagazinearticle?rowId=" . $this->input->post('article_id'));
            redirect("reviewmagazinearticle?rowId=" . $_REQUEST['rowId'] . "&magId=" . $_REQUEST['magid']);
        }
    }

    /* EDIT CUSTOMER MAGAZINE ARTICLE */

    public function edit_customer_magazine_article()
    {
        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }

        $magazineObj = $this->load->model("customer/customer_magazine_model");
        $magazineObj = new customer_magazine_model();
        $language_result = $magazineObj->getLanguageList();
        //$catagory_list = $magazineObj->getFormCatagoryList();
        $magazine_list = $magazineObj->getCustomerMagazine();
        //echo"<pre>"; print_r($magazine_list); die;
        $article_id = $_REQUEST['artId'];
        $mag_id = $_REQUEST['rowId'];
        $magazineObj->set_magazine_id($mag_id);
        $magazine_info = $magazineObj->getMagazineInfo();

        $magazineObj->set_id($article_id);
        $magazineObj->setSource($_GET['source']);
        $article_data = $magazineObj->getArticleDetails();
//echo"<pre>"; print_r($article_data); die;
        if ($this->input->post("cancel") == "cancel") {
            redirect("magazinearticlelist?rowId=" . $this->input->post("magazine_id"));
        }

        if ($this->input->post("save") == "saveindraft" || $this->input->post("publish") == "publish") {
            //echo"<pre>"; print_r($_POST); die;

            $this->form_validation->set_rules('title', 'title', 'required');

            if ($this->form_validation->run() == TRUE) {
                //echo"<pre>"; print_r($_POST); die;
                $magazineObj->set_title(trim($this->input->post("title")));
                $magazineObj->set_media_type($this->input->post("media_type"));

                $magazineObj->set_language($this->input->post("language"));
                $magazineObj->set_description($this->input->post("description"));
                $magazineObj->set_tags($this->input->post("tags"));
                $magazineObj->set_publish_from($this->input->post("datefrom"));
                $magazineObj->set_publish_to($this->input->post("dateto"));
                $magazineObj->set_magazine_id($this->input->post("magazine"));
                $magazineObj->set_customer_id($this->session->userdata('user_id'));
                $magazineObj->set_id($this->input->post("article_id"));
                $magazineObj->set_article_language($this->input->post("articlelang"));
                $magazineObj->set_link_url($this->input->post("url"));
                if ($this->input->post("share")) {
                    $magazineObj->set_allow_share($this->input->post("share"));
                } else {
                    $magazineObj->set_allow_share('0');
                }
                if ($this->input->post("comments")) {
                    $magazineObj->set_allow_comment($this->input->post("comments"));
                } else {
                    $magazineObj->set_allow_comment('0');
                }
                if ($this->input->post("publish")) {
                    $magazineObj->set_status('2');
                } else {
                    $magazineObj->set_status('4');
                }
                if ($this->input->post('catagory') != "") {
                    $catagory_id = $this->input->post('catagory');
                    $catagory_id_list = implode(",", $catagory_id);
                    $magazineObj->set_catagory_id($catagory_id_list);
                } else {
                    $magazineObj->set_catagory_id('1');
                }

                if ($this->input->post('embed') != '') {
                    $magazineObj->setEmbedVideo($this->input->post('embed'));
                    if ($_FILES['thumb_embeded']['size'] > 0) {
                        $embed_video_thumb = $this->uploadEmbededThumb('Article');
                        $magazineObj->setEmbedThumbVideo($embed_video_thumb);
                    }
                }


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

                        $this->session->set_flashdata("msg", "Image not uploaded");
                        redirect("editMagazinearticle?rowId=" . $this->input->post("magazine_id") . "&artId=" . $this->input->post("article_id"));
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

                    $magazineObj->set_image($this->upload->file_name);
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


                        /* FILE NAME AS ARRAY */
                        $magazineObj->set_video_path($this->upload->file_name);
                        //$magazineObj->set_video_path($array);
                        $magazineObj->UpdateVideoPath();
                    }
                }


                $update_article = $magazineObj->UpdateCustomerArticle();
                if ($update_article == TRUE) {
                    $this->session->set_flashdata("sus_msg", "Article updated Successfully");
                    //$url = $_SERVER['HTTP_REFERER'];
                    //redirect($url);
                    redirect("magazinearticlelist?rowId=" . $this->input->post("magazine_id"));
                } else {
                    $this->session->set_flashdata("msg", "Article not updated");
                    //redirect("editMagazinearticle?rowId=".$this->input->post("article_id"));
                    redirect("editMagazinearticle?rowId=" . $this->input->post("magazine_id") . "&artId=" . $this->input->post("article_id"));
                }
            }
        }

        $data['language_result'] = $language_result;
        $data['magazine_result'] = $magazine_list;
        $data['article_data'] = $article_data;
        $data['magazine_info'] = $magazine_info;


        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'magazine/edit_magazine_article', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    /** Action is used for
     * create new magazine for customer
     * @author Techahead
     * @access Public
     * @param
     * @return
     * Index function load defaut
     * */
    public function edit_magazine()
    {
        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }
        $magazineObj = $this->load->model("web/magazine_model");
        $magazineObj = new magazine_model();

        /* check Language Session */
        if ($this->session->userdata('lang') != "") {
            $magazineObj->set_language_id($this->session->userdata('lang'));
        }
        /* ------------ */


        if ($this->input->post("save1") == "Save") {
            //echo"<pre>"; print_r($_POST); die;

            $magazineObj->set_bar_color($this->input->post('color'));
            $magazineObj->set_no_of_user($this->input->post('no_user'));
            $magazineObj->set_id($this->input->post('magazine_id'));
            $magazineDetail = $magazineObj->getMagazineDetail();

            $old_images = $magazineDetail['magazine_images']; //die;
            // $magazineObj->getMag


            /*             * * phase 2 work for different resolution images
             * 
             * 
             * 
             */
            // For Mobile-

            $folder1 = "480800";
            $folder2 = "7201280";
            $folder3 = "10801920";

            // For Tablet:

            $folder4 = "6001024";
            $folder5 = "1024600";
            $folder6 = "8001280";
            $folder7 = "1280800";

            // for iphone
            $folder8 = "320568";
            $folder9 = "1024768";
            $folder10 = "7681024";


            if ($old_images != '') {
                $image_name = $old_images;
            } else {
                $image_name = time() . "cover_pic";
            }

            if (isset($_FILES['cover_pic1']) && $_FILES['cover_pic1']['name'] != '') {
                $type = $_FILES['cover_pic1']['type'];
                $type_array = array();
                $type_array = explode("/", $type);
                $image_type = $type_array[1];
                $image_name1 = $image_name . "." . $image_type;

                $this->imageUpload('cover_pic1', $image_name1);
            }
            if (isset($_FILES['cover_pic2']) && $_FILES['cover_pic2']['name'] != '') {
                $type = $_FILES['cover_pic2']['type'];
                $type_array = array();
                $type_array = explode("/", $type);
                $image_type = $type_array[1];
                $image_name2 = $image_name . "." . $image_type;
                $this->imageUpload('cover_pic2', $image_name2);
            }
            if (isset($_FILES['cover_pic3']) && $_FILES['cover_pic3']['name'] != '') {
                $type = $_FILES['cover_pic3']['type'];
                $type_array = array();
                $type_array = explode("/", $type);
                $image_type = $type_array[1];
                $image_name3 = $image_name . "." . $image_type;
                $this->imageUpload('cover_pic3', $image_name3);
            }
            if (isset($_FILES['cover_pic4']) && $_FILES['cover_pic4']['name'] != '') {
                $type = $_FILES['cover_pic4']['type'];
                $type_array = array();
                $type_array = explode("/", $type);
                $image_type = $type_array[1];
                $image_name4 = $image_name . "." . $image_type;
                $this->imageUpload('cover_pic4', $image_name4);
            }
            if (isset($_FILES['cover_pic5']) && $_FILES['cover_pic5']['name'] != '') {
                $type = $_FILES['cover_pic5']['type'];
                $type_array = array();
                $type_array = explode("/", $type);
                $image_type = $type_array[1];
                $image_name5 = $image_name . "." . $image_type;
                $this->imageUpload('cover_pic5', $image_name5);
            }
            if (isset($_FILES['cover_pic6']) && $_FILES['cover_pic6']['name'] != '') {
                $type = $_FILES['cover_pic6']['type'];
                $type_array = array();
                $type_array = explode("/", $type);
                $image_type = $type_array[1];
                $image_name6 = $image_name . "." . $image_type;
                $this->imageUpload('cover_pic6', $image_name6);
            }
            if (isset($_FILES['cover_pic7']) && $_FILES['cover_pic7']['name'] != '') {
                $type = $_FILES['cover_pic7']['type'];
                $type_array = array();
                $type_array = explode("/", $type);
                $image_type = $type_array[1];
                $image_name7 = $image_name . "." . $image_type;
                $this->imageUpload('cover_pic7', $image_name7);
            }

            if (isset($_FILES['cover_pic8']) && $_FILES['cover_pic8']['name'] != '') {
                $type = $_FILES['cover_pic8']['type'];
                $type_array = array();
                $type_array = explode("/", $type);
                $image_type = $type_array[1];
                $image_name8 = $image_name . "." . $image_type;
                $this->imageUpload('cover_pic8', $image_name8);
            }
            if (isset($_FILES['cover_pic9']) && $_FILES['cover_pic9']['name'] != '') {
                $type = $_FILES['cover_pic9']['type'];
                $type_array = array();
                $type_array = explode("/", $type);
                $image_type = $type_array[1];
                $image_name9 = $image_name . "." . $image_type;
                $this->imageUpload('cover_pic9', $image_name9);
            }
            if (isset($_FILES['cover_pic10']) && $_FILES['cover_pic10']['name'] != '') {
                $type = $_FILES['cover_pic10']['type'];
                $type_array = array();
                $type_array = explode("/", $type);
                $image_type = $type_array[1];
                $image_name10 = $image_name . "." . $image_type;
                $this->imageUpload('cover_pic10', $image_name10);
            }
            //echo 'image_name='.$image_name;
            //die;
            $magazineObj->setMagazine_images($image_name1);
            // echo $image_name;die;
//                rename("./assets/$folder1/$old_images", "./assets/$folder1/$image_name");
//                rename("./assets/$folder2/$old_images", "./assets/$folder2/$image_name");
//                rename("./assets/$folder3/$old_images", "./assets/$folder3/$image_name");
//                rename("./assets/$folder4/$old_images", "./assets/$folder4/$image_name");
//                rename("./assets/$folder5/$old_images", "./assets/$folder5/$image_name");
//                rename("./assets/$folder6/$old_images", "./assets/$folder6/$image_name");
//                rename("./assets/$folder7/$old_images", "./assets/$folder7/$image_name");


            /* FOR COVER PIC FILE */
            if ($_FILES['cover_pic']['error'] == 0) {

                $folderName = 'Magazine_cover';
                $pathToUpload = './assets/' . $folderName;
                if (!is_dir($pathToUpload)) {
                    mkdir($pathToUpload, 0777, TRUE);
                }

                $config['upload_path'] = './assets/Magazine_cover/';
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
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('cover_pic')) {
                    //echo $this->upload->display_errors();

                    $this->session->set_flashdata("msg", $this->lang->line("Image_not_uploaded"));
                    redirect("edit_magazine");
                }
                //$file_name =  $pathToUpload/$this->upload->file_name;
                $magazineObj->set_cover_image($this->upload->file_name);
            }

            /* -------------------------- FOR CUSTOMER LOGO IMAGE -------------------------- */
            if ($_FILES['customer_logo']['error'] == 0) {

                $folderName = 'Magazine_cover/customer_logo/';
                $pathToUpload = './assets/' . $folderName;
                if (!is_dir($pathToUpload)) {
                    mkdir($pathToUpload, 0777, TRUE);
                }

                $config2['upload_path'] = './assets/Magazine_cover/customer_logo/';
                // Location to save the image
                $config2['allowed_types'] = 'gif|jpg|png|jpeg';
                $config2['overwrite'] = FALSE;
                $config2['remove_spaces'] = true;
                $config2['maintain_ratio'] = TRUE;
                //$config['max_size'] = '0';
                $config2['create_thumb'] = TRUE;

                $imgName = date("Y-m-d");
                $nameImg = "";
                $nameChar = "ABCDEFGHIJKLMNOPQRSTYUVWXYZabcdefghijklmnopqrstuvwxyz";

                for ($i = 0; $i < 3; $i++) {

                    $nameImg .= $nameChar[rand(0, strlen($nameChar))];
                }
                $config2['file_name'] = $imgName . $nameImg . "_org";

                $thumbName = $imgName;

                $this->load->library('upload', $config2);
                //codeigniter default function
                $this->upload->initialize($config2);
                if (!$this->upload->do_upload('customer_logo')) {
                    //echo $this->upload->display_errors(); die;

                    $this->session->set_flashdata("msg", $this->lang->line("Image_not_uploaded"));
                    redirect("edit_magazine");
                }
                //$file_name =  $pathToUpload/$this->upload->file_name;
                $magazineObj->set_customer_logo($this->upload->file_name);
            }

            $magazine_result = $magazineObj->UpdateCustomerMagazine();

            $locations = $this->input->post('location');
            $magazineObj->saveMagazineLocations($locations);

            $this->session->set_flashdata("sus_msg", $this->lang->line("Magazine_updated_Successfully"));
            redirect("magazinelist");
        }
        $data['data_result'] = $magazine_list;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'magazine/magazine_bord', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    private function imageUpload($file_name, $output_image_name)
    {
        //echo "$file_name,$folderName,$output_image_name";echo '<br>';
        //die;
        //echo '<br>';
        $folderName = 'Magazine_cover';
        $config = array();
        $image = '';

        if ($_FILES["$file_name"]['error'] == 0) {
            $pathToUpload = './assets/' . $folderName;
            if (!is_dir($pathToUpload)) {
                mkdir($pathToUpload, 0777, TRUE);
            }


            //$prefix=time(),2);
            $config['upload_path'] = "./assets/$folderName/";
            // Location to save the image
            $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
            $config['overwrite'] = FALSE;
            $config['remove_spaces'] = true;
            $config['maintain_ratio'] = TRUE;
            $config['max_size'] = '0';
            $config['file_name'] = str_replace(' ', '_', $output_image_name);


            $this->load->library('upload', $config);
            //echo '<pre>';print_r($config);

            $errorsUpload = '';
            $errorResize = '';
            $this->upload->initialize($config);
            if (!$this->upload->do_upload("$file_name")) {
                $msg = "Image not uploaded";
                $errorsUpload = $this->upload->display_errors();
                //echo '<pre>';print_r($errorsUpload);die;
            } else {
                $image = $this->upload->file_name;     //echo $image;die;
                //echo 'dd='.$image;die;
            }
            //echo 'image='.$image;  die;
        }

        return $image;
    }

    public function magazine_details()
    {

        $magazineObj = $this->load->model("web/magazine_model");
        $magazineObj = new magazine_model();
        $magazineObj->set_id($_GET['Id']);
        $magazineDetails = $magazineObj->getMagazineDetailAjax();
        $implode = implode(',', $magazineDetails);
        echo $implode;
    }

    public function uploadEmbededThumb($folderName)
    {
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

    public function get_closed_magazine_filter()
    {

        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }

        $id = $_POST['customer_id'];

        $this->load->model("web/magazine_model");
        $obj = new magazine_model();

        $obj->set_id($id);

        $data_result = $obj->getCustomerMagazinesFilter();

        $data = array();

        $data["show_default_value"] = true;
        $data["result"] = $data_result;
        $data['main_content'] = array('view' => 'admin/select_filter', 'data' => array());
        $this->load->view('templates/only_content', $data);

    }

    public function get_magazine_articles_filter()
    {

        if ($this->session->userdata('id') == "" && $this->session->userdata('user_id') == "") {
            redirect("login_check");
        }

        $id = $_POST['magazine_id'];

        $this->load->model("web/magazine_model");
        $obj = new magazine_model();

        $data_result = $obj->getArticlesByMagazineFilter($id, $this->session->userdata('user_id'));

        $data = array();

        $data["show_default_value"] = true;
        $data["result"] = $data_result;
        $data['main_content'] = array('view' => 'admin/select_filter', 'data' => array());
        $this->load->view('templates/only_content', $data);

    }

}

?>
