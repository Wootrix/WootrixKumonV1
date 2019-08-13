<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * @author Techahead
 * 
 */

class Article_detail extends MX_Controller
{

    public function __construct()
    {

        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('email');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->library('session', true);
        $this->load->library('pagination');
        if ($this->session->userdata("langSelect") == "english") {
            $this->lang->load('en');
        } elseif ($this->session->userdata("langSelect") == "spanish") {
            $this->lang->load('sp');
        } elseif ($this->session->userdata("langSelect") == "portuguese") {
            $this->lang->load('po');
        }

        if ($this->session->userdata("user_id") == '') {

            if ($_SERVER['HTTP_REFERER'] != "") {
                $servUrl = explode("index.php/", $_SERVER['HTTP_REFERER']);
                if ($servUrl[0] != "https://linhadireta.unidadekumon.com.br/") {
                    $this->session->set_userdata('referer', $_SERVER['HTTP_REFERER']);
                    $actual_link = explode("index.php/", $_SERVER['REQUEST_URI']);
                    $this->session->set_userdata('redirectUrl', $actual_link[1]);
                }
            }

        }


        //print_r($this->session->userdata);
    }

    public function articleDetailList()
    {

        if ($this->session->userdata('redirectUrl') != "" || $this->session->userdata("user_id") != '') {
            $this->session->unset_userdata('redirectUrl');

//        if($this->session->userdata("user_id") = ''){
//            redirect($this->config->base_url());
//        }
            //echo $this->input->post("searchVal");die;
            //echo ltrim($this->session->userdata('topics'),'|');die;
//        if($_POST){
//            echo "<pre>";print_r($_POST);die;
//        }
            //echo "<pre>";print_r($_POST);die;
            if ($this->input->post("articleHidden") == "valueArticle") {

                $articleCommObj = $this->load->model("webservices/article_comment_model");
                $articleCommObj = new article_comment_model();

                $articleCommObj->set_article_id($this->input->post("articleIdValue"));
                $articleCommObj->set_comment($this->input->post("commentValue"));
                $articleCommObj->set_user_id($this->session->userdata("user_id"));
                if ($this->input->post("magazineId") != '') {
                    $articleCommObj->setType('magazine');
                } else {
                    $articleCommObj->setType('open');
                }

                $articleCommObj->postArticleComments();
                redirect("wootrix-article-detail?source=" . $this->input->post("source") . "&comment=1&articleId=" . $this->input->post("articleIdValue") . "&magazineId=" . $this->input->post("magazineId"));
            }

            $articleObj = $this->load->model("webservices/new_articles_model");
            $articleObj = new new_articles_model();
            $categoryObj = $this->load->model("webservices/category_model");
            $categoryObj = new category_model();

            if ($this->input->post("searchVal") != '') {
                $magId = $this->input->post("searchVal");
                $articleObj->set_language_name("en");
                $getLangId = $articleObj->getLanguageId();
                $articleObj->set_language_id($getLangId['id']);
                $articleObj->set_user_id($this->session->userdata("user_id"));
                $articleObj->set_title($magId);
                $articleObj->set_category_id(ltrim($this->session->userdata('topics'), '|'));
                $getArticleIdUsingTitle = $articleObj->getArticleIdUsingTitle();
                $_GET['articleId'] = $getArticleIdUsingTitle['id'];
                $data['titleName'] = $this->input->post("searchVal");
            }

            if ($_GET['articleId'] == '') {
                redirect('wootrix-articles');
            }

            $articleObj->set_id($_GET['articleId']);
            $articleObj->setSource($_GET['source']);
            $getArticle = $articleObj->getArticleDetail();
            //echo "<pre>";print_r($getArticle);die;
            $data['articleDetail'] = $getArticle;
            if ($_GET['searchId'] == "search") {
                $data['titleName'] = substr($getArticle['title'], 0, 50);
            }
            $getCommCount = $articleObj->articleCommentCountOpen();
            $data['comment'] = $getCommCount;

            $getTags = explode(",", $getArticle['tags']);
            $data['articleTags'] = $getTags;
            if ($_GET['magazineId'] != '') {
                $articleObj->setServer('magazine');
            } else {
                $articleObj->setServer('open');
            }
            $getAllComments = $articleObj->getAllComments();
            $data['allComments'] = $getAllComments;
            // Related Articles
            $articleObj->setServer('web');
            if ($this->session->userdata('topics') != '') {
                $articleObj->set_category_id($this->session->userdata('topics'));
            } else {
                $articleObj->set_category_id($getArticle['category_id']);
            }

            if ($this->session->userdata('languages') != '') {
                $articleObj->set_language_id($this->session->userdata('languages'));
            }

            if ($_GET['magazineId'] != '') {

                $articleObj->set_magazine_id($_GET['magazineId']);

            }
            $articleObj->setSource($_GET['source']);
            $getRelatedArticles = $articleObj->getRelatedArticles();
            //echo "Query=".$this->db->last_query();//die;
            //echo "<pre>";print_r($getRelatedArticles);die;
            $data['relatedArticle'] = $getRelatedArticles;
            $getTopics = $categoryObj->getTopics();
            $data['topics'] = $getTopics;
            //End

            $this->register_access($articleObj->get_magazine_id(), $articleObj->get_id());

            $data['header'] = array('view' => 'templates/header', 'data' => array());
            $data['main_content'] = array('view' => 'all_article_detail', 'data' => array());
            $data['footer'] = array('view' => 'templates/footer', 'data' => array());
            $this->load->view('templates/common_template', $data);
        } else {
            redirect($this->config->base_url());
        }
    }

    public function searchMagzineList()
    {

        $topicExp = explode("|", ltrim($this->session->userdata('topics'), '|'));
        $topicImp = implode(",", $topicExp);
        $articleObj = $this->load->model("webservices/new_articles_model");
        $articleObj = new new_articles_model();

        $articleObj->set_category_id($topicImp);
        $articleObj->set_title($_POST['keyValue']);
        $articleObj->set_language_name("en");
        $getLangId = $articleObj->getLanguageId();
        $articleObj->set_language_id($getLangId['id']);
        if (($_POST['keySeg'] == "wootrix-landing-screen") || ($_POST['keySeg'] == "wootrix-article-detail") || ($_POST['keySeg'] == "wootrix-list-magazines") || ($_POST['keySeg'] == "wootrix-articles")) {
            $getSearchArticle = $articleObj->getSearchOpenArticles();

            //echo $this->db->last_query();die;
            $list = '';

            $list .= "<div id='searchListArticle'>";
            foreach ($getSearchArticle as $g) {
                $titleValue = substr($g['title'], 0, 50);
                $ID = $g['id'];
                $source = $g['source'];
                $list .= "<span style='cursor: pointer;' onclick='getResult(\"$titleValue\",\"$ID\",\"$magId\",\"$source\");' id='searchedValue'>" . $titleValue;

                $list .= "<br></span>";
            }
            $list .= "</div>";
            if (!empty($getSearchArticle)) {

            } else {
                $list = "";
            }
            echo $list;
            exit;
        }
        if (($_POST['keySeg'] == "wootrix-article-list-layout") || ($_POST['keySeg'] == "wootrix-mag-article-detail") || ($_POST['keySeg'] == "wootrix-articles")) {
            $articleObj->set_magazine_id($_POST['keyMagId']);
            $articleObj->set_title($_POST['keyValue']);
            $getSearchArticle = $articleObj->getSearchedMagazineData();
            $list = '';

            $list .= "<div id='searchListArticle'>";
            foreach ($getSearchArticle as $g) {
                $titleValue = substr($g['title'], 0, 50);
                $ID = $g['id'];
                $source = $g['source'];
                $magId = $g['magazine_id'];
                $list .= "<span style='cursor: pointer;' onclick='getResult(\"$titleValue\",\"$ID\",\"$magId\",\"$source\");' id='searchedValue'>" . $titleValue;

                $list .= "<br></span>";
            }
            $list .= "</div>";
            if (!empty($getSearchArticle)) {

            } else {
                $list = "";
            }
            echo $list;
            exit;
        }
    }

    public function getListOfSelectedMagazines()
    {
//        if($this->session->userdata("user_id") = ''){
//            redirect($this->config->base_url());
//        }
        //echo $_GET['magazineId'];die;
        //echo $this->input->post("searchVal");die;
        $categoryObj = $this->load->model("webservices/category_model");
        $categoryObj = new category_model();
        //echo $magId;die;
        $magzineObj = $this->load->model("webservices/magzine_model");
        $magzineObj = new magzine_model();
        $articleObj = $this->load->model("webservices/new_articles_model");
        $articleObj = new new_articles_model();

        if ($this->session->userdata('languages') != '') {
            $magzineObj->set_language_id(ltrim($this->session->userdata('languages'), ","));
        } else {
            $magzineObj->set_language_id('1');
        }

        if ($_GET['magazineId'] != '') {
            $magId = $_GET['magazineId'];
            $magzineObj->set_id($magId);
            $getMagazineDetail = $magzineObj->getMagazineDetail();

            $getTitleName = $magzineObj->getTitleName();
            //$getArticleCount = $magzineObj->getArticleCount();
            $data['titleName'] = $getTitleName['title'];
        }
        if ($this->input->post("searchVal") != '') {
            $magId = $this->input->post("searchVal");
            $magzineObj->set_user_id($this->session->userdata("user_id"));
            $magzineObj->set_title($magId);
            $getMagazineDetail = $magzineObj->getMagazineDetailList();
            $getArticleCount = $magzineObj->getArticleCountUsingTitle();
            $data['titleName'] = $this->input->post("searchVal");
        }
        $data['magazineList'] = $getMagazineDetail;
        //$data['totalArticle'] = $getArticleCount;

        $getTopics = $categoryObj->getTopics();
        $data['topics'] = $getTopics;


        $data['header'] = array('view' => 'templates/header', 'data' => array());
        $data['main_content'] = array('view' => 'all_magazines', 'data' => array());
        $data['footer'] = array('view' => 'templates/footer', 'data' => array());
        $this->load->view('templates/common_template', $data);
    }

    public function articleLayoutsList()
    {

        if ($this->session->userdata("user_id") == '') {
            redirect($this->config->base_url());
        }

        $userId = $this->session->userdata("user_id");

        $this->load->model("webservices/category_model");
        $categoryObj = new category_model();

        $this->load->model("website/magazine_advertise_model");
        $magAdvObj = new magazine_advertise_model();

        $checkMagazine = $magAdvObj->getUserMagazinesRedirect();
        if ($checkMagazine == FALSE) {
            redirect("wootrix-landing-screen");
        }

        $data['magzineDetailsChanges'] = $magAdvObj->getUserMagazinesDetailsForHeader();

        $magAdvObj->set_magazine_id($_GET['magazineId']);
        if ($this->session->userdata('topics') != '') {
            $category = $this->session->userdata('topics');
            $magAdvObj->setWeb_category($category);
        }
        if ($this->session->userdata('languages') != '') {
            $magAdvObj->set_language_id($this->session->userdata('languages'));
        }

        if ($this->session->userdata('langSelect') != '') {
            if ($this->session->userdata('langSelect') == 'english') {
                $langMagId = '1';
            } else if ($this->session->userdata('langSelect') == 'spanish') {
                $langMagId = '3';
            } else if ($this->session->userdata('langSelect') == 'portuguese') {
                $langMagId = '2';
            }
            $magAdvObj->set_langMag($langMagId);
        } else {
            $magAdvObj->set_langMag("1");
        }

        $magAdvObj->set_article_id($_GET['articleId']);
        $magAdvObj->set_search_key($this->input->post("searchVal"));
        $getMagazineAdvPaging = $magAdvObj->getAdvertiseDetailPaging();

        $this->load->model("webservices/category_model");
        $categoryObj = new category_model();

        if ($this->session->userdata('langSelect') != '') {
            if ($this->session->userdata('langSelect') == 'english') {
                $languageId = '1';
            } else if ($this->session->userdata('langSelect') == 'spanish') {
                $languageId = '3';
            } else if ($this->session->userdata('langSelect') == 'portuguese') {
                $languageId = '2';
            }
            $categoryObj->set_language($languageId);
        } else {
            $categoryObj->set_language('1');
        }
        $data['topics'] = $categoryObj->getTopics();

        if ($_GET['page'] != '') {
            $pageCount = $_GET['page'];

            $pageNumber = $pageCount % 4;

            if ($pageNumber == 1) {
                $perPage = 3;
            } else if ($pageNumber == 2) {
                $perPage = 1;
            } else if ($pageNumber == 3) {
                $perPage = 5;
            } else if ($pageNumber == 0) {
                $perPage = 6;
            } else {
                $perPage = 3;
            }

        } else {
            $perPage = 3;
        }

        $totalPage = $getMagazineAdvPaging['pageCount'];
        $allPages = range(1, $totalPage);
        $j = 0;


        $pageNumberCount = $totalPage % 15;
        //echo $pageNumberCount;die;
        $round = (int)($totalPage / 15);
        //echo $round;die;
        if ($pageNumberCount <= 3) {
            $j = 1 + ($round * 4);
        } else if ($pageNumberCount > 3 && $pageNumberCount <= 4) {
            $j = 2 + ($round * 4);
        } else if ($pageNumberCount > 4 && $pageNumberCount <= 9) {
            $j = 3 + ($round * 4);
        } else if ($pageNumberCount > 9) {
            $j = 4 + ($round * 4);
        }

        $pageDemo = 0;
        $config['base_url'] = base_url() . 'index.php/wootrix-article-list-layout?magazineId=' . $_GET['magazineId'];
        $config['total_rows'] = $getMagazineAdvPaging['pageCount'];
        $config['per_page'] = $totalPage / $j;
        $config['full_tag_open'] = "<p class='pagination_bottom'>";
        $config['full_tag_close'] = "</p>";
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers'] = TRUE;
        $config['next_link'] = $this->lang->line("next_page");
        $config['prev_link'] = $this->lang->line("prev_page");
        $config['first_link'] = $this->lang->line("first_page");
        $config['last_link'] = $this->lang->line("last_page");

        $this->pagination->initialize($config);
        if ($_GET['page'] != '') {
            $page = $_GET['page'];
        } else {
            $page = 1;
        }
        if ($page == 1) {
            $limitPage = 0;
        } else {

            $allNumber = range(1, $page);

            foreach ($allNumber as $num) {

                $pageChk = $num % 4;

                if ($pageChk == 1 && $num > 1) {
                    $pageDemo = $pageDemo + 6;
                }
                if ($pageChk == 2) {
                    $pageDemo = $pageDemo + 3;
                }
                if ($pageChk == 3) {
                    $pageDemo = $pageDemo + 1;
                }
                if ($pageChk == 0) {
                    $pageDemo = $pageDemo + 5;
                }
            }
            //echo 'pagec='.$pageDemo;
            $limitPage = $pageDemo;
        }
        if ($this->session->userdata('topics') != '') {
            $category = $this->session->userdata('topics');
            $magAdvObj->setWeb_category($category);
        }
        if ($this->session->userdata('languages') != '') {
            $magAdvObj->set_language_id($this->session->userdata('languages'));
        }
        //echo $category;die;
        $data['magID'] = $_GET['magazineId'];
        $magAdvObj->set_magazine_id($_GET['magazineId']);
        $magAdvObj->set_limit($limitPage);
        $magAdvObj->set_page($perPage);

        $this->load->model("web/group_model");
        $model = new group_model();

//        echo "<pre>"; print_r($_GET); exit;

        $groups = [];
        $locations = [];
        $disciplines = [];

        $this->load->model("webservices/users_model");
        $userModel = new users_model();

        $userModel->set_token($userId);
        $user = $userModel->getUserAccountDetails();

        $userGroups = $model->getUserGroups($userId);

        if( $userGroups !== false ) {

            foreach ($userGroups as $group) {
                $groups[] = $group["id_group"];
            }

        }

        $articleLocation = $model->getUserLocation($user["user_location_id"]);

        if( $articleLocation !== false ){

            foreach( $articleLocation as $location ){
                $locations[] = $location["id"];
            }

        }

        $articleDiscipline = $model->getUserDiscipline($userId);

        if( $articleDiscipline !== false ){

            foreach( $articleDiscipline as $discipline ){
                $disciplines[] = $discipline["discipline_id"];
            }

        }

        $articleBranch = $model->getUserBranch($userId);
        $userBranch = $articleBranch["branch"];

        $groupsString = implode(",", $groups);
        $locationsString = implode(",", $locations);
        $disciplinesString = implode(",", $disciplines);

        $getMagazineAdv = $magAdvObj->getAdvertiseDetail($groupsString, $locationsString, $disciplinesString, $userBranch);
        //echo "<pre>";print_r($getMagazineAdv);die;
        if ($getMagazineAdv != FALSE) {
            $data['getMagazineAdv'] = $getMagazineAdv;
        } else {
            $data['getMagazineAdv'] = array();
        }
        //echo "<pre>";print_r($getMagazineAdv);die;
        if ($_GET['searchId'] == "search") {
            $data['titleName'] = $getMagazineAdv['articles'][0]['title'];
        }
        if ($_GET['searchKey'] == "web") {
            $data['titleName'] = $this->input->post("searchVal");
        }
        $pageShow = $page % 4;

//        echo "<pre>";
//        print_r($data);
//        echo "</pre>"; exit;

        $data['header'] = array('view' => 'templates/header', 'data' => array());

        if ($pageShow == 1) {
            $data['main_content'] = array('view' => 'article_layout_one', 'data' => array());
        } else if ($pageShow == 2) {
            $data['main_content'] = array('view' => 'article_layout_two', 'data' => array());
        } else if ($pageShow == 3) {
            $data['main_content'] = array('view' => 'article_layout_three', 'data' => array());
        } else if ($pageShow == 0) {
            $data['main_content'] = array('view' => 'article_layout_four', 'data' => array());
        }

        $data['footer'] = array('view' => 'templates/footer', 'data' => array());
        $this->load->view('templates/common_template', $data);
    }

    public function changeEmailOfUser()
    {

        $objUsersModel = $this->load->model('webservices/users_model');
        $objUsersModel = new users_model();
        $objUsersModel->set_check_website("web");
        $objUsersModel->set_token($this->session->userdata('user_id'));
        $objUsersModel->set_old_email($_GET['email']);
        $objUsersModel->set_new_email($_GET['newEmail']);
        $result = $objUsersModel->updateNewEmailAndCheckOld();
        if ($result != FALSE) {
            $this->session->set_flashdata('error', 'Email changed successfully');
            redirect('wootrix-landing-screen');
        } else {
            $this->session->set_flashdata('error', $this->lang->line("email_address_wrong_web"));
            redirect('wootrix-landing-screen?email=' . $_GET['email'] . '&newEmail=' . $_GET['newEmail']);
        }
    }

    public function changePasswordOfUser()
    {

        $objUsersModel = $this->load->model('webservices/users_model');
        $objUsersModel = new users_model();
        $objUsersModel->set_check_website("web");
        $objUsersModel->set_token($this->session->userdata('user_id'));
        $objUsersModel->set_old_password($_GET['password']);
        $objUsersModel->set_new_password($_GET['newpassword']);
        $result = $objUsersModel->updateNewPasswordAndCheckOld();
        //echo $this->db->last_query();die;
        if ($result != FALSE) {
            $this->session->set_flashdata('errorPassword', $this->lang->line("new_pass_changed_success"));
            redirect('wootrix-landing-screen');
        } else {
            $this->session->set_flashdata('errorPassword', $this->lang->line("old_pass_wrong_web"));
            redirect('wootrix-landing-screen');
        }
    }

    public function magazineArticlesDetailList()
    {

        $magArtObj = $this->load->model('website/magazine_articles_model');
        $magArtObj = new magazine_articles_model();
        $categoryObj = $this->load->model("webservices/category_model");
        $categoryObj = new category_model();
//        echo $_GET['magazineId']."<br>";
//        echo $_GET['magArtId'];die;
        $data['magID'] = $_GET['magazineId'];
        $magArtObj->set_id($_GET['magArtId']);
        $magArtDetail = $magArtObj->getMagazineArticleDetail();
        $data['articleDetail'] = $magArtDetail;

        $getTags = explode(",", $magArtDetail['tags']);
        $data['articleTags'] = $getTags;

        $getAllComments = $magArtObj->getAllComments();
        $data['allComments'] = $getAllComments;

        $magArtObj->set_server('web');
        if ($this->session->userdata('topics') != '') {
            $magArtObj->set_category_id($this->session->userdata('topics'));
        } else {
            $magArtObj->set_category_id($magArtDetail['category_id']);
        }

        if ($this->session->userdata('languages') != '') {
            $magArtObj->set_language_id($this->session->userdata('languages'));
        }

        $getRelatedArticles = $magArtObj->getRelatedArticles();
        //echo "<pre>";print_r($getRelatedArticles);die;
        $data['relatedArticle'] = $getRelatedArticles;
        $getTopics = $categoryObj->getTopics();
        $data['topics'] = $getTopics;

        if ($this->input->post("articleHidden") == "valueArticle") {

            $magArtObj->set_id($this->input->post("articleIdValue"));
            $magArtObj->set_comment($this->input->post("commentValue"));
            $magArtObj->set_user_id($this->session->userdata("user_id"));
            $magArtObj->postMagazineArtComments();


            redirect("wootrix-mag-article-detail?comment=1&magArtId=" . $this->input->post("articleIdValue") . "&magazineId=" . $this->input->post("magId"));
        }

        $data['magArtId'] = $_GET['magArtId'];
        $data['magId'] = $_GET['magazineId'];
        //redirect("wootrix-mag-article-detail?magArtId=".$_GET['magArtId']."&magazineId=".$_GET['magazineId']);


        $data['header'] = array('view' => 'templates/header', 'data' => array());
        $data['main_content'] = array('view' => 'magazine_article_detail', 'data' => array());
        $data['footer'] = array('view' => 'templates/footer', 'data' => array());
        $this->load->view('templates/common_template', $data);
    }

    public function AdvertiseOnly()
    {
        if ($_POST['advetiseValue'] != '') {
            $magazineId = $_POST['magazineIdSend'];
            $pageShow = ($_POST['advetiseValue']) % 4;
            if ($pageShow == '0') {
                $pageShow = '4';
            }
//        echo $magazineId;
//        echo $pageShow;die;
            $magAdvObj = $this->load->model("website/magazine_advertise_model");
            $magAdvObj = new magazine_advertise_model();
            $magAdvObj->set_magazine_id($magazineId);
            $magAdvObj->set_layout_type($pageShow);
            $advertise = $magAdvObj->getAdvertiseOnly();


            $returnString = "<script>$( document ).ready(function() {
    $('iframe', window.parent.document).width('300px');
    $('iframe', window.parent.document).height('190px');
});</script>";

            $returnString .= '<a href="' . $advertise['link'] . '" target="_blank" onclick = "remoteaddr(' . $advertise['adsid'] . ');">';
            if ($advertise['media_type'] == '1') {
                if ($advertise['cover_image'] != '') {
                    $returnString .= '<img src="' . $this->config->base_url() . 'assets/Advertise/' . $advertise['cover_image'] . '" alt=""/>';
                } else {
                    $returnString .= '<img src="' . $this->config->base_url() . 'images/website_images/placeholder-3.jpg" alt=""/>';
                }
            } else if ($advertise['media_type'] == '2') {
                if ($advertise['cover_image'] != '') {
                    if ($advertise['cover_image'] == 'embed') {
                        //echo $advertise['embed_video'];
                    } else {
                        $returnString .= '<video>
                                        <source src="' . $this->config->base_url() . 'assets/Advertise/thumbs/' . $advertise['cover_image'] . 'demo.jpeg" type="video/mp4">
                                    </video>
                                    <div class="video-overlay">
                                        <a href="' . $this->config->base_url() . 'assets/Advertise/' . $advertise['cover_image'] . '" target="_blank"><img src="http://103.25.130.197/wootrix__OldToClient/images/website_images/pause-icon.png"></a>
                            
                            
                        </div>
 <img src="' . $this->config->base_url() . 'assets/Advertise/thumbs/' . $advertise['cover_image'] . 'demo.jpeg" class="videoThumbnal" />
';
                    }
                } else {
                    $returnString .= '<img src="' . $this->config->base_url() . 'images/website_images/placeholder-3.jpg" alt=""/>';
                }

            } else {
                $returnString .= '<img src="' . $this->config->base_url() . 'images/website_images/placeholder-3.jpg" alt=""/>';
            }
            if ($advertise['cover_image'] == 'embed') {
                preg_match('/src="([^"]+)"/', $advertise['embed_video'], $match);

                $returnString .= '<a href="' . $match[1] . '" target="_blank" >
                        <div id="openEmbed">
                        <img src="' . base_url() . $advertise['embed_thumb'] . '" />
                        </div>
                        
                        </a>';
            }
            $returnString .= '<div class="articleTitle"><h4>' . $this->lang->line("advertisement_text") . '</h4></div>
                                
                            </div>
                        </a>';

            echo $returnString;


            exit();
        }
    }


    public function AdvertiseOnlyLayout2()
    {
        if ($_POST['advetiseValue'] != '') {
            $magazineId = $_POST['magazineIdSend'];
            $pageShow = ($_POST['advetiseValue']) % 4;
            if ($pageShow == '0') {
                $pageShow = '4';
            }
//        echo $magazineId;
//        echo $pageShow;die;
            $magAdvObj = $this->load->model("website/magazine_advertise_model");
            $magAdvObj = new magazine_advertise_model();
            $magAdvObj->set_magazine_id($magazineId);
            $magAdvObj->set_layout_type($pageShow);
            $getMagazineAdv = $magAdvObj->getAdvertiseOnly();


            $returnString = "<script>$( document ).ready(function() {
    $('iframe', window.parent.document).width('585px');
    $('iframe', window.parent.document).height('570px');
});</script>";

            $returnString .= '<figure>
                            <a href="' . $getMagazineAdv['link'] . '" target="_blank" onclick = "remoteaddr(' . $getMagazineAdv['adsid'] . ');">';

            if ($getMagazineAdv['media_type'] == '1') {
                if ($getMagazineAdv['cover_image'] != '') {
                    $returnString .= '<img src="' . $this->config->base_url() . 'assets/Advertise/' . $getMagazineAdv['cover_image'] . '" alt=""/>';
                } else {
                    $returnString .= '<img src="' . $this->config->base_url() . 'images/website_images/placeholder-8.png" alt=""/>';
                }

            } else if ($getMagazineAdv['media_type'] == '2') {
                if ($getMagazineAdv['cover_image'] != '') {
                    if ($getMagazineAdv['cover_image'] == 'embed') {
                        //echo $getMagazineAdv['embed_video'];
                    } else {
                        $returnString .= '<video>
                                    <source src="' . $this->config->base_url() . 'assets/Advertise/thumbs/' . $getMagazineAdv['cover_image'] . 'demo.jpeg" type="video/mp4">
                                </video>
                                    <div class="video-overlay">
                                        <a href="' . $this->config->base_url() . 'assets/Advertise/' . $getMagazineAdv['cover_image'] . '" target="_blank"><img src="http://103.25.130.197/wootrix__OldToClient/images/website_images/pause-icon.png"></a>
                            
                            
                        </div>';
                    }
                } else {
                    $returnString .= '<img src="' . $this->config->base_url() . 'images/website_images/placeholder-8.png" alt=""/>';
                }
            } else {

                $returnString .= '<img src="' . $this->config->base_url() . 'images/website_images/placeholder-8.png" alt=""/>';
            }
            $returnString .= '</a>
                            
                            <figcaption>
                                <a href="' . $getMagazineAdv['link'] . '" target="_blank">
                                ' . $getMagazineAdv['title'] . '
                                </a>
                            </figcaption
                            
                        </figure>
                    ';
            if ($getMagazineAdv['cover_image'] == 'embed') {
                preg_match('/src="([^"]+)"/', $getMagazineAdv['embed_video'], $match);

                $returnString .= '<a href="' . $match[1] . '" target="_blank" >
                        <div id="openEmbed">
                        <img src="' . base_url() . $getMagazineAdv['embed_thumb'] . '" />
                                </div>
                        </a>';
            }
            echo $returnString;


            exit();
        }
    }

    public function AdvertiseOnlyLayout3()
    {
        if ($_POST['advetiseValue'] != '') {
            $magazineId = $_POST['magazineIdSend'];
            $pageShow = ($_POST['advetiseValue']) % 4;
            if ($pageShow == '0') {
                $pageShow = '4';
            }
//        echo $magazineId;
//        echo $pageShow;die;
            $magAdvObj = $this->load->model("website/magazine_advertise_model");
            $magAdvObj = new magazine_advertise_model();
            $magAdvObj->set_magazine_id($magazineId);
            $magAdvObj->set_layout_type($pageShow);
            $getMagazineAdv['advertisement'] = $magAdvObj->getAdvertiseOnly();


            $returnString = "<script>$( document ).ready(function() {
    $('iframe', window.parent.document).width('580px');
    $('iframe', window.parent.document).height('380px');
});</script>";

            $returnString .= '<a href="' . $getMagazineAdv['advertisement']['link'] . '" target="_blank" onclick = "remoteaddr(' . $getMagazineAdv['adsid'] . ');">';

            if ($getMagazineAdv['advertisement']['media_type'] == '1') {
                if ($getMagazineAdv['advertisement']['cover_image'] != '') {
                    $returnString .= '<img src="' . $this->config->base_url() . 'assets/Advertise/' . $getMagazineAdv['advertisement']['cover_image'] . '" alt=""/>';
                } else {
                    $returnString .= '<img src="' . $this->config->base_url() . 'images/website_images/placeholder-1.jpg" alt=""/>';
                }

            } elseif ($getMagazineAdv['advertisement']['media_type'] == '2') {

                if ($getMagazineAdv['advertisement']['cover_image'] != '') {
                    if ($getMagazineAdv['advertisement']['cover_image'] == 'embed') {
                        //echo $getMagazineAdv['advertisement']['embed_video'];
                    } else {
                        $returnString .= '<video>
                                    <source src="' . $this->config->base_url() . 'assets/Advertise/thumbs/' . $getMagazineAdv['advertisement']['cover_image'] . 'demo.jpeg" type="video/mp4">
                                </video>
                                <div class="video-overlay">
                                        <a href="' . $this->config->base_url() . 'assets/Advertise/' . $getMagazineAdv['advertisement']['cover_image'] . '" target="_blank"><img src="http://103.25.130.197/wootrix__OldToClient/images/website_images/pause-icon.png"></a>
                            
                            
                        </div>
                ';
                    }
                } else {
                    $returnString .= '<img src="' . $this->config->base_url() . 'images/website_images/placeholder-1.jpg" alt=""/>';
                }

            } else {
                $returnString .= '<img src="' . $this->config->base_url() . 'images/website_images/placeholder-1.jpg" alt=""/>';
            }
            $returnString .= '</a>';
            if ($getMagazineAdv['advertisement']['cover_image'] == 'embed') {
                preg_match('/src="([^"]+)"/', $getMagazineAdv['advertisement']['embed_video'], $match);

                $returnString .= '<a href="' . $match[1] . '" target="_blank" >
            <div id="openEmbed">
                        <img src="' . base_url() . $getMagazineAdv['advertisement']['embed_thumb'] . '" />
                            </div>
                            
                            <div class="video-overlay">
                              <a href="' . $match[1] . '" target="_blank"><img src="http://103.25.130.197/wootrix__OldToClient/images/website_images/pause-icon.png"></a>
                            </div>
                        </a>';
            }
            echo $returnString;


            exit();
        }
    }

    public function AdvertiseOnlyLayout4()
    {
        if ($_POST['advetiseValue'] != '') {
            $magazineId = $_POST['magazineIdSend'];
            $pageShow = ($_POST['advetiseValue']) % 4;
            if ($pageShow == '0') {
                $pageShow = '4';
            }
//        echo $magazineId;
//        echo $pageShow;die;
            $magAdvObj = $this->load->model("website/magazine_advertise_model");
            $magAdvObj = new magazine_advertise_model();
            $magAdvObj->set_magazine_id($magazineId);
            $magAdvObj->set_layout_type($pageShow);
            $getMagazineAdv['advertisement'] = $magAdvObj->getAdvertiseOnly();


            $returnString = "<script>$( document ).ready(function() {
    $('iframe', window.parent.document).width('580px');
    $('iframe', window.parent.document).height('190px');
});</script>";

            $returnString .= '<figure>
                                <a href="' . $getMagazineAdv['advertisement']['link'] . '" target="_blank"onclick = "remoteaddr(' . $getMagazineAdv['adsid'] . ');">';

            if ($getMagazineAdv['advertisement']['media_type'] == '1') {
                if ($getMagazineAdv['advertisement']['cover_image'] != '') {
                    $returnString .= '<img src="' . $this->config->base_url() . 'assets/Advertise/' . $getMagazineAdv['advertisement']['cover_image'] . '" alt=""/>';
                } else {
                    $returnString .= '<img src="' . $this->config->base_url() . 'images/website_images/placeholder-2.jpg" alt=""/>';
                }

            } else if ($getMagazineAdv['advertisement']['media_type'] == '2') {
                if ($getMagazineAdv['advertisement']['cover_image'] != '') {
                    if ($getMagazineAdv['advertisement']['cover_image'] == 'embed') {
                        //echo $getMagazineAdv['advertisement']['embed_video'];
                    } else {
                        $returnString .= '<video>
                                    <source src="' . $this->config->base_url() . 'assets/Advertise/' . $getMagazineAdv['advertisement']['cover_image'] . '" type="video/mp4">
                                </video><div class="video-overlay">
                                        <a href="' . $this->config->base_url() . 'assets/Advertise/thumbs/' . $getMagazineAdv['advertisement']['cover_image'] . '" target="_blank"><img src="http://103.25.130.197/wootrix__OldToClient/images/website_images/pause-icon.png"> class="PauseThumbnal</a>
                            
                            
                        </div>';
                    }
                } else {
                    $returnString .= '<img src="' . $this->config->base_url() . 'images/website_images/placeholder-2.jpg" alt=""/>';
                }

            } else {
                $returnString .= '<img src="' . $this->config->base_url() . 'images/website_images/placeholder-2.jpg" alt=""/>';
            }
            $returnString .= '</a>
                                
                                <figcaption>
                                    <a href="' . $getMagazineAdv['advertisement']['link'] . '" target="_blank">
                                    ' . $getMagazineAdv['advertisement']['title'] . '
                                    </a>
                                </figcaption>
                            </figure>';
            if ($getMagazineAdv['advertisement']['cover_image'] == 'embed') {
                preg_match('/src="([^"]+)"/', $getMagazineAdv['advertisement']['embed_video'], $match);

                $returnString .= '<a href="' . $match[1] . '" target="_blank" >
                        <div id="openEmbed">
                        <img src="' . base_url() . $getMagazineAdv['advertisement']['embed_thumb'] . '" />
                            </div>
                            <div class="video-overlay">
                              <a href="' . $match[1] . '" target="_blank"><img src="http://103.25.130.197/wootrix__OldToClient/images/website_images/pause-icon.png"></a>
                            </div>
                        </a>';
            }
            echo $returnString;


            exit();
        }
    }

    public function test()
    {
        echo "here";
        die;
    }

    public function addGoogleAccount()
    {

        $clientId = '205502865022-g58lfmmpsc163cbt4p7s2fd5tibr3s2p.apps.googleusercontent.com';
        $clientSecret = 'WTyUMV3j-yPfNaZ-kZxQJVUv';
        //$redirectUrl = $this->config->base_url()."index.php/website/login_google/loginWithGooglePlus";
        $redirectUrl = "http://fbh.wootrix.com/index.php/website/article_detail/addGoogleAccount";

        require_once 'src/Google_Client.php';
        require_once 'src/service/Oauth2.php';

        $client = new Google_Client();
        $client->setClientId($clientId);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri($redirectUrl);
        $client->setScopes("https://www.googleapis.com/auth/userinfo.email");
        $objOAuthService = new Google_Service_Oauth2($client);

        if (isset($_GET['code'])) {
            $client->authenticate($_GET['code']);
        }


        if ($client->getAccessToken()) {

            $userData = $objOAuthService->userinfo->get();
            //echo "<pre>";print_r($userData);die;
            $userObj = $this->load->model("webservices/users_model");
            $userObj = new users_model();

            $goId = $userData['id'];
            $goEmail = $userData['email'];
            $goName = $userData['name'];
            $goPic = $userData['picture'];
            $goGender = $userData['gender'];

            $userObj->set_email($goEmail);
            $userObj->set_name($goName);
            $userObj->set_photoUrl($goPic);
            $userObj->set_device_type("3");
            $userObj->set_google_id($goId);
            $userObj->set_registration_type("4");
            $userObj->addAccountWeb();
        }

    }

    private function register_access($magazineId, $articleId){

        if ($this->session->userdata('user_id') == ""){
            redirect("login_check");
        }

        $this->load->helper('general_helper');

        $token = $this->session->userdata('user_id');

        $this->load->model("webservices/users_model");
        $userObj = new users_model();
        $userObj->set_token($token);
        $user = $userObj->getUserAccountDetails();

        if( empty( $magazineId ) || empty( $articleId ) ){
            redirect("wootrix-articles");
        }

        $this->load->model("webservices/magazine_access_model");
        $magazineAccessModel = new magazine_access_model();

        $country = "";
        $state = "";
        $city = "";

        if( $user["latitude"] != 0 && $user["longitude"] != 0 ){

            $latitude = $user["latitude"];
            $longitude = $user["longitude"];

            $dataLocation = getLocationByLatLng($latitude, $longitude);

            $country = $dataLocation["country"];
            $state = $dataLocation["state"];
            $city = $dataLocation["city"];

        }

        $dateTime = new DateTime( 'now' );
        $dateFormatted = $dateTime->format( "Y-m-d H:i:s" );

        $magazineAccessModel->setIdMagazine($magazineId);
        $magazineAccessModel->setIdArticle($articleId);
        $magazineAccessModel->setDateAccess($dateFormatted);
        $magazineAccessModel->setIdUser($token);
        $magazineAccessModel->setSoAccess(getOS());
        $magazineAccessModel->setTypeDeviceAccess("Desktop/Browser");
        $magazineAccessModel->setCountry($country);
        $magazineAccessModel->setState($state);
        $magazineAccessModel->setCity($city);

        $inserted = $magazineAccessModel->insert();

    }


}
