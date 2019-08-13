<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * @author Techahead
 * 
 */

class Website_login extends MX_Controller
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
//        $this->load->library('facebook');
        if ($this->session->userdata("langSelect") == "english") {
            $this->lang->load('en');
        } elseif ($this->session->userdata("langSelect") == "spanish") {
            $this->lang->load('sp');
        } elseif ($this->session->userdata("langSelect") == "portuguese") {
            $this->lang->load('po');
        }
        if ($this->session->userdata("langSelect") == '') {
            $this->session->set_userdata("langSelect", "english");
            $this->lang->load('en');
        }
    }

    public function redirectAccess()
    {

        $data['header'] = array('view' => 'templates/header', 'data' => array());
        $data['main_content'] = array('view' => 'website_login/redirect_access', 'data' => array());
        $data['footer'] = array('view' => 'templates/footer', 'data' => array());
        $this->load->view('templates/common_template', $data);

    }

    public function saveDeepData()
    {

        $magazineCode = $_POST["deepMagazineCode"];
        $magazineId = $_POST["deepMagazineId"];
        $articleId = $_POST["deepArticleId"];
        $adId = $_POST["deepAdId"];
        $showInputDialog = $_POST["showInputDialog"];

        $this->session->set_userdata("deepMagazineCode", $magazineCode);
        $this->session->set_userdata("deepMagazineId", $magazineId);
        $this->session->set_userdata("deepArticleId", $articleId);
        $this->session->set_userdata("deepAdId", $adId);
        $this->session->set_userdata("deepShowInputDialog", $showInputDialog);

        $this->session->set_userdata("socialMagazineCode", $magazineCode);
        $this->session->set_userdata("socialMagazineId", $magazineId);
        $this->session->set_userdata("socialArticleId", $articleId);
        $this->session->set_userdata("socialAdId", $adId);
        $this->session->set_userdata("socialShowInputDialog", $showInputDialog);

        exit;

    }

    public function webLogin()
    {
        if ($this->session->userdata("user_id") != '') {
            redirect("wootrix-landing-screen");
        }
        else {

            if( $_SERVER['SERVER_NAME'] == "linhadiretatst.unidadekumon.com.br" || $_SERVER['SERVER_NAME'] == "localhost" ){

                $this->load->model("webservices/users_model");
                $usersObj = new users_model();

                $getLogin = $usersObj->getUserByKumonEmail("professor.teste06@unidadekumon.com.br");

                $this->session->set_userdata('user_id', $getLogin['id']);
                $this->session->set_userdata('user_email', $getLogin['email']);
                $this->session->set_userdata('user_name', $getLogin['name']);
                $this->session->set_userdata("langSelect", $getLogin['website_language']);
                $this->session->set_userdata('languagesWeb', $getLogin['website_language_id']);
                $this->session->set_userdata('languages', $getLogin['article_language']);

                $this->session->set_userdata('topics', $getLogin['category']);

                $deepMagazineCode = $this->input->post("deepMagazineCode");
                $deepMagazineId = $this->input->post("deepMagazineId");
                $deepArticleId = $this->input->post("deepArticleId");
                $deepAdId = $this->input->post("deepAdId");
                $deepShowInputDialog = $this->input->post("deepShowInputDialog");

                $this->session->set_userdata("loggedDeepMagazineCode", $deepMagazineCode);
                $this->session->set_userdata("loggedDeepMagazineId", $deepMagazineId);
                $this->session->set_userdata("loggedDeepArticleId", $deepArticleId);
                $this->session->set_userdata("loggedDeepAdId", $deepAdId);
                $this->session->set_userdata("loggedDeepShowInputDialog", $deepShowInputDialog);

                redirect("wootrix-landing-screen");

            }

            $token = $this->input->get('t');

            if(!$token){
                redirect('https://portal.unidadekumon.com.br', 'refresh');
            } else {

                $getLogin = $this->getKumonUser($token);

                if ($getLogin) {

                    $this->session->set_userdata('user_id', $getLogin['id']);
                    $this->session->set_userdata('user_email', $getLogin['email']);
                    $this->session->set_userdata('user_name', $getLogin['name']);
                    $this->session->set_userdata("langSelect", $getLogin['website_language']);
                    $this->session->set_userdata('languagesWeb', $getLogin['website_language_id']);
                    $this->session->set_userdata('languages', $getLogin['article_language']);

                    $this->session->set_userdata('topics', $getLogin['category']);

                    $deepMagazineCode = $this->input->post("deepMagazineCode");
                    $deepMagazineId = $this->input->post("deepMagazineId");
                    $deepArticleId = $this->input->post("deepArticleId");
                    $deepAdId = $this->input->post("deepAdId");
                    $deepShowInputDialog = $this->input->post("deepShowInputDialog");

                    $this->session->set_userdata("loggedDeepMagazineCode", $deepMagazineCode);
                    $this->session->set_userdata("loggedDeepMagazineId", $deepMagazineId);
                    $this->session->set_userdata("loggedDeepArticleId", $deepArticleId);
                    $this->session->set_userdata("loggedDeepAdId", $deepAdId);
                    $this->session->set_userdata("loggedDeepShowInputDialog", $deepShowInputDialog);

                    redirect("wootrix-landing-screen");
                    exit;

                } else {
                    redirect('https://portal.unidadekumon.com.br', 'refresh');
                    exit;
                }

            }

        }

//        $deepMagazineCode = $this->session->userdata('socialMagazineCode');
//        $deepMagazineId = $this->session->userdata('socialMagazineId');
//        $deepArticleId = $this->session->userdata('socialArticleId');
//        $deepAdId = $this->session->userdata('socialAdId');
//        $showInputDialog = $this->session->userdata('socialShowInputDialog');
//
//        $data['header'] = array('view' => 'templates/header', 'data' => array());
//        $data['main_content'] = array('view' => 'website_login/login', 'data' => array());
//        $data['footer'] = array('view' => 'templates/footer', 'data' => array());
//        $this->load->view('templates/common_template', $data);

    }

    function index()
    {

    }

    function initiate()
    {
        //session_unset();die;
        // setup before redirecting to Linkedin for authentication.
        $linkedin_config = array(
            'appKey' => '78mqq80rpy81un',
            'appSecret' => '6Aw7k7FsyaT2v3io',
            'callbackUrl' => $this->config->base_url() . 'index.php/website/website_login/data/'
        );
        //echo "<pre>";print_r($linkedin_config);die;
        $this->load->library('linkedin', $linkedin_config);
        $this->linkedin->setResponseFormat(LINKEDIN::_RESPONSE_JSON);
        $token = $this->linkedin->retrieveTokenRequest();
        //echo "<pre>";print_r($token);die;
        $this->session->set_flashdata('oauth_request_token_secret', $token['linkedin']['oauth_token_secret']);
        $this->session->set_flashdata('oauth_request_token', $token['linkedin']['oauth_token']);

        $link = "https://api.linkedin.com/uas/oauth/authorize?oauth_token=" . $token['linkedin']['oauth_token'];
        redirect($link);
    }

    function cancel()
    {

        // See https://developer.linkedin.com/documents/authentication
        // You need to set the 'OAuth Cancel Redirect URL' parameter to <your URL>/linkedin_signup/cancel

        echo 'Linkedin user cancelled login';
    }

    function logout()
    {
        session_unset();
        $_SESSION = array();
        echo "Logout successful";
    }

    function data()
    {

        $linkedin_config = array(
            'appKey' => '78mqq80rpy81un',
            'appSecret' => '6Aw7k7FsyaT2v3io',
            'callbackUrl' => $this->config->base_url() . 'index.php/website/website_login/data/'
        );

        $this->load->library('linkedin', $linkedin_config);
        $this->linkedin->setResponseFormat(LINKEDIN::_RESPONSE_JSON);

        $oauth_token = $this->session->flashdata('oauth_request_token');
        $oauth_token_secret = $this->session->flashdata('oauth_request_token_secret');

        $oauth_verifier = $this->input->get('oauth_verifier');
        $response = $this->linkedin->retrieveTokenAccess($oauth_token, $oauth_token_secret, $oauth_verifier);

        // ok if we are good then proceed to retrieve the data from Linkedin
        if ($response['success'] === TRUE) {

            // From this part onward it is up to you on how you want to store/manipulate the data 
            $oauth_expires_in = $response['linkedin']['oauth_expires_in'];
            $oauth_authorization_expires_in = $response['linkedin']['oauth_authorization_expires_in'];

            $response = $this->linkedin->setTokenAccess($response['linkedin']);
            $profile = $this->linkedin->profile('~:(id,first-name,last-name,picture-url,email-address)');
            $profile_connections = $this->linkedin->profile('~/connections:(id,first-name,last-name,picture-url,industry)');
            $user = json_decode($profile['linkedin']);
            $user_array = array('linkedin_id' => $user->id, 'second_name' => $user->lastName, 'profile_picture' => $user->pictureUrl, 'first_name' => $user->firstName, 'email-address' => $user->emailAddress);

            $userObj = $this->load->model("webservices/users_model");
            $userObj = new users_model();
            //print '<pre>';print_r($user_array);

            $linId = $user_array['linkedin_id'];
            $linName = $user_array['first_name'] . ' ' . $user_array['second_name'];
            $linPic = $user_array['profile_picture'];
            $linEmail = $user_array['email-address'];

            $userObj->set_email($linEmail);
            $userObj->set_name($linName);
            $userObj->set_photoUrl($linPic);
            $userObj->set_device_type("3");
            $userObj->set_linkedin_id($linId);
            $userObj->set_registration_type("2");
            $getSignupLogin = $userObj->signupAndLoginInWootrix();
            $getLogin = $userObj->getLinkedinUserData();
            if ($getLogin != FALSE) {
                $this->session->set_userdata('user_id', $getLogin['id']);
                $this->session->set_userdata('user_email', $getLogin['email']);
                $this->session->set_userdata('user_name', $getLogin['name']);
                $this->session->set_userdata("langSelect", $getLogin['website_language']);
                $this->session->set_userdata('languages', $getLogin['article_language']);
                $this->session->set_userdata('topics', $getLogin['category']);

                $this->session->set_userdata('languagesWeb', $getLogin['website_language_id']);
                redirect('wootrix-landing-screen');
            }
            // Example of company data
            //$company = $this->linkedin->company('1337:(id,name,ticker,description,logo-url,locations:(address,is-headquarters))');
        } else {
            // bad token request, display diagnostic information
            echo "Request token retrieval failed:<br /><br />RESPONSE:<br /><br />" . print_r($response, TRUE);
        }
    }

    public function getDashboardLandingPage()
    {

        //echo $this->session->userdata("langSelect");die;
//        echo "===========" . $this->session->userdata('topics');
//        echo "===========" . $this->session->userdata('languages');

        if ($this->session->userdata('user_id') == '') {
            redirect(base_url());
        }

        $articleObj = $this->load->model("webservices/new_articles_model");
        $articleObj = new new_articles_model();

        $categoryObj = $this->load->model("webservices/category_model");
        $categoryObj = new category_model();

        $articleObj->set_token($this->session->userdata("user_id"));
        $articleObj->set_language_name("en");
        $getLangId = $articleObj->getLanguageId();
        if ($articleObj->get_lang_param() == "2") {
            foreach ($getLangId as $l) {
                $lang[] = $l['id'];
            }
        } else {
            $lang = $getLangId['id'];
        }
        $articleObj->set_language_id($lang);
        $articleObj->setServer('web');
        if ($this->session->userdata('topics') != '') {
            $articleObj->setWe_category($this->session->userdata('topics'));
        }

        if ($this->session->userdata('languages') != '') {
            $articleObj->set_language_id($this->session->userdata('languages'));
            if ($this->session->userdata('langSelect') == 'english') {
                $languageIdWeb = '1';
            } else if ($this->session->userdata('langSelect') == 'spanish') {
                $languageIdWeb = '3';
            } else if ($this->session->userdata('langSelect') == 'portuguese') {
                $languageIdWeb = '2';
            }
            $articleObj->set_app_lang_name($languageIdWeb);
        }
        $getArticle = $articleObj->getNewArticle();
        //echo "<pre>";print_r($getArticle);die;
        $data['recentArticle'] = $getArticle;
        $getUserMagzines = $articleObj->getUserArticles();
        ///echo "<pre>";print_r($getUserMagzines);die;
//        if($getUserMagzines==FALSE){
//            redirect('wootrix-articles');
//        }
        //echo "<pre>";print_r($getArticle);die;
        $data['userMagazines'] = $getUserMagzines;
        //echo "<pre>";print_r($getUserMagzines);die;

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
//        if($this->session->userdata("langSelect")=='english'){
//        $categoryObj->set_language_name('en');
//        }else if($this->session->userdata("langSelect")=='portuguese'){
//            $categoryObj->set_language_name('pt');
//        }else if($this->session->userdata("langSelect")=='spanish'){
//            $categoryObj->set_language_name('es');
//        }
        //$getTopics = $categoryObj->getTopics();
        //$data['topics'] = $getTopics;
        //echo "<pre>";print_r($getTopics);die;


        $data['header'] = array('view' => 'templates/header', 'data' => array());
        $data['main_content'] = array('view' => 'landing_screen', 'data' => array());
        $data['footer'] = array('view' => 'templates/footer', 'data' => array());
        $this->load->view('templates/common_template', $data);
    }

    public function userRegistration()
    {
        $userObj = $this->load->model("webservices/users_model");
        $userObj = new users_model();

        if ($this->input->post("userRegHidden") == "saveReg") {
            $userObj->set_name($this->input->post("userName"));
            $userObj->set_email($this->input->post("userEmail"));
            $userObj->set_password($this->input->post("confirmPass"));
            $userObj->set_check_website("Website");
            $signup = $userObj->signupInWootrix();
            if ($signup == FALSE) {
                $this->session->set_flashdata("userName", $this->input->post("userName"));
                $this->session->set_flashdata("userEmail", $this->input->post("userEmail"));

                $this->session->set_flashdata("loginError", $this->lang->line("email_id_exists_web"));
                $this->session->set_flashdata("error", "1");
                redirect($this->config->base_url());
            } else {
                $this->session->set_flashdata("regMsg", $this->lang->line("user_reg_success_web"));
                $this->session->set_flashdata("error", "2");
                redirect($this->config->base_url());
            }
        }

        $data['header'] = array('view' => 'templates/header', 'data' => array());
        $data['main_content'] = array('view' => 'website_login/login', 'data' => array());
        $data['footer'] = array('view' => 'templates/footer', 'data' => array());
        $this->load->view('templates/common_template', $data);
    }

    public function logOutUser()
    {
        $this->session->sess_destroy();
        session_destroy();
        redirect($this->config->base_url());
    }

    public function userLogin() {

        $this->load->model("webservices/users_model");
        $usersObj = new users_model();

        $getLogin = $this->getKumonUser($this->input->post("userEmailLogin"));

        if ($getLogin) {

            $this->session->set_userdata('user_id', $getLogin['id']);
            $this->session->set_userdata('user_email', $getLogin['email']);
            $this->session->set_userdata('user_name', $getLogin['name']);
            $this->session->set_userdata("langSelect", $getLogin['website_language']);
            $this->session->set_userdata('languagesWeb', $getLogin['website_language_id']);
            $this->session->set_userdata('languages', $getLogin['article_language']);

            $this->session->set_userdata('topics', $getLogin['category']);

            $deepMagazineCode = $this->input->post("deepMagazineCode");
            $deepMagazineId = $this->input->post("deepMagazineId");
            $deepArticleId = $this->input->post("deepArticleId");
            $deepAdId = $this->input->post("deepAdId");
            $deepShowInputDialog = $this->input->post("deepShowInputDialog");

            $this->session->set_userdata("loggedDeepMagazineCode", $deepMagazineCode);
            $this->session->set_userdata("loggedDeepMagazineId", $deepMagazineId);
            $this->session->set_userdata("loggedDeepArticleId", $deepArticleId);
            $this->session->set_userdata("loggedDeepAdId", $deepAdId);
            $this->session->set_userdata("loggedDeepShowInputDialog", $deepShowInputDialog);

            echo json_encode(array('success' => true));
            exit;

        } else {
            echo json_encode(array('success' => false, 'message' => "Token inválido"));
            exit;
        }

    }

    private function getKumonUser($token){

        $this->load->model("webservices/users_model");
        $userObj = new users_model();

        $response_xml = $this->getKumonResponse($token);

//        header ("Content-Type:text/xml");
//        print_r($response_xml); exit;

        $xml = new SimpleXMLElement($response_xml);
        $xml->registerXPathNamespace("diffgr", "urn:schemas-microsoft-com:xml-diffgram-v1");
        $body = $xml->xpath("//diffgr:diffgram");

        if( isset( $body[0]->NewDataSet->Table ) ){

//            $kumonId = $body[0]->NewDataSet->Table->UserID;

            $email = $body[0]->NewDataSet->Table->Email->__toString();
            $kumonUser = $userObj->getUserByKumonEmail($email);

            if( !$kumonUser ){

                $name = "Colaborador";

                if( $body[0]->NewDataSet->Table->CustomerType == "O" ){
                    $name = "Orientador";
                }

                $group = $body[0]->NewDataSet->Table->CustomerType;
                $country = $body[0]->NewDataSet->Table->Country->__toString();
                $state =  $body[0]->NewDataSet->Table->State;
                $city =  $body[0]->NewDataSet->Table->City;
                $branch = $body[0]->NewDataSet->Table->Branch->__toString();

                $disciplines = [];

                if( $body[0]->NewDataSet->Table->Mat == "1" ){
                    $disciplines[] = "mat";
                }

                if( $body[0]->NewDataSet->Table->Por == "1" ){
                    $disciplines[] = "por";
                }

                if( $body[0]->NewDataSet->Table->Nih == "1" ){
                    $disciplines[] = "nih";
                }

                if( $body[0]->NewDataSet->Table->Kok == "1" ){
                    $disciplines[] = "kok";
                }

                if( $body[0]->NewDataSet->Table->Ing == "1" ){
                    $disciplines[] = "ing";
                }

                $language = "portuguese";
                $languageId = 2;

                if($country != "BR"){
                    $language = "spanish";
                    $languageId = 3;
                }

                $userObj->set_name($name);
                $userObj->set_email($email);
                $userObj->set_password($this->generateRandomString());
                $userObj->set_check_website("Website");
                $userObj->set_website_language($language);
                $userObj->set_website_language_id($languageId);
                $signup = $userObj->signupInWootrix($group, $country, $state, $city, $disciplines, $branch);

                if (!$signup) {
                    return false;
                } else {
                    return $userObj->getUserByKumonEmail($email);
                }

            } else {
                $branch = $body[0]->NewDataSet->Table->Branch->__toString();
                $userObj->updateUserBranch($email, $branch);
                return $userObj->getUserByKumonEmail($email);
            }

        } else {
            return false;
        }

    }

    private function getKumonResponse($token){

        //DEV
//        $url = 'http://201.91.129.22/wsKumon01/PortalOrientador.asmx';

        //PROD
        $url = 'http://ws01.unidadekumon.com.br/weborder/PortalOrientador.asmx';

        $client = new SoapClient($url . "?WSDL", array("trace" => 1, "exception" => 1));

        $function = 'getEntityTokenExternal';

        $intEntityID = 28;

        if( $_SERVER['SERVER_NAME'] == "endirecto.unidadkumon.com"){
            $intEntityID = 29;
        }

        $arguments = array( "getEntityTokenExternal" => array(
            'strApp' => "41883FA5-D29A-439B-9186-6E8E9000572D",
            'strToken' => '1234567890',
            "strEntityToken" => $token,
            'intEntityID' => $intEntityID
        ) );

        $options = array('location' => $url);

        $client->__soapCall($function, $arguments, $options);
        $response_xml = $client->__getLastResponse();

        return $response_xml;

    }

    private function generateRandomString($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function saveSearchCriteria()
    {


        if ($_GET['sessionData'] != '') {

            $explode = explode(',', $_GET['sessionData']);
            $langEn = in_array("a", $explode);
            $langSp = in_array("b", $explode);
            $langPo = in_array("c", $explode);
            if ($langEn == "1") {
                $langEn = ",1";
            }
            if ($langSp == "1") {
                $langSp = ",3";
            }
            if ($langPo == "1") {
                $langPo = ",2";
            }
            $finalLang = $langEn . $langSp . $langPo;
            $this->session->set_userdata('languages', $finalLang);
            $this->db->set('article_language', $finalLang)->where('id', $this->session->userdata('user_id'))->update('tbl_users');
            //print_r($explode);die;
            array_pop($explode);
            array_pop($explode);
            $langArr = array("a", "b", "c");
            $finalTopics = array_diff($explode, $langArr);
            //echo "<pre>";print_r($finalTopics);die;
            $implode = implode("|", $finalTopics);

            if ($this->session->userdata('topics') != '') {
                $this->session->unset_userdata('topics');
                $this->session->set_userdata('topics', $implode);
            } else {
                $this->session->set_userdata('topics', $implode);
            }

            $this->db->set('category', $implode)->where('id', $this->session->userdata('user_id'))->update('tbl_users');
        }

        if ($_GET['languageData'] != '') {


            //if ($_GET['checkStatus'] == 'true') {


            if ($this->session->userdata('languages') != '') {
                if ($_GET['checkStatus'] == 'true') {

                    $languageData = $this->session->userdata('languages');
                    $languageSessionData = $languageData . "," . $_GET['languageData'];
                    $this->session->set_userdata('languages', $languageSessionData);
                    $this->db->set('article_language', $languageSessionData)->where('id', $this->session->userdata('user_id'))->update('tbl_users');
                } else {

                    $languageData = $this->session->userdata('languages');

                    $sessData12 = str_replace(',' . $_GET['languageData'], "", $languageData);
                    if ($sessData12 == "") {
                        $sessData12 = ",1";
                    }
                    $this->session->set_userdata('languages', $sessData12);
                    $this->db->set('article_language', $sessData12)->where('id', $this->session->userdata('user_id'))->update('tbl_users');
                }


            } else {

                $this->session->set_userdata('languages', "," . $_GET['languageData']);
                $this->db->set('article_language', "," . $_GET['languageData'])->where('id', $this->session->userdata('user_id'))->update('tbl_users');

            }
//            } else {
//                $this->session->unset_userdata('languages');
//            }
        }


        if ($_GET['magazineId'] != '') {
            if ($_GET['sessionData'] != '') {
                //$this->session->set_flashdata("topicShowBox", "open");
            }
            if ($_GET['languageData'] != '') {
                $this->session->set_flashdata("languageBox", "open");
            }
            redirect($_GET['pageName'] . "?magazineId=" . $_GET['magazineId'] . "&magArtId=" . $_GET['magArtId']);
        } else {
            if ($_GET['sessionData'] != '') {
                // $this->session->set_flashdata("topicShowBox", "open");
            }
            if ($_GET['languageData'] != '') {
                $this->session->set_flashdata("languageBox", "open");
            }
            redirect($_GET['pageName'] . "?articleId=" . $_GET['articleId']);
        }
    }

    public function aboutUsPage()
    {

        $categoryObj = $this->load->model("webservices/category_model");
        $categoryObj = new category_model();

        $getTopics = $categoryObj->getTopics();
        $data['topics'] = $getTopics;
        $data['header'] = array('view' => 'templates/header', 'data' => array());
        if ($this->uri->segment(2) == 'en') {
            $data['main_content'] = array('view' => 'about_us_en', 'data' => array());
        }
        if ($this->uri->segment(2) == 'pt') {
            $data['main_content'] = array('view' => 'about_us_po', 'data' => array());
        }
        if ($this->uri->segment(2) == 'es') {
            $data['main_content'] = array('view' => 'about_us_es', 'data' => array());
        }
        $data['footer'] = array('view' => 'templates/footer', 'data' => array());
        $this->load->view('templates/common_template', $data);
    }

    public function getSubscribeMagazinePassword()
    {
        //echo $this->input->post("segValue");die;
        $magzineObj = $this->load->model("webservices/magzine_model");
        $magzineObj = new magzine_model();

        $magzineObj->set_token($this->session->userdata("user_id"));

        $magzineObj->set_password($this->input->post("subsPassword"));
        $getSubsMag = $magzineObj->getSubscribeMagzineData();

        $deepLink = $this->input->post("deepLink");

        if (!empty($deepLink)) {

            if ($getSubsMag != FALSE) {
                echo json_encode(array('success' => true, 'magazineId' => $getSubsMag["id"]));
            } else {

                $magazineId = $magzineObj->getMagazineByCode();

                $magzineObj->set_id($magazineId);
                $magzineObj->set_user_id($this->session->userdata("user_id"));
                $userHasMagazine = $magzineObj->userHasMagazine();

                echo json_encode(array('success' => false, 'hasMagazine' => $userHasMagazine, 'magazineId' => $magazineId));

            }

            exit;

        } else {

            if ($getSubsMag != FALSE) {
                $this->session->set_flashdata("subsMsg", $this->lang->line("magazine_added_web"));
                redirect($this->input->post("segValue"));
            } else {
                $this->session->set_flashdata("subsMsgError", $this->lang->line("check_subs_pass_web"));
                $this->session->set_flashdata("errorVal", "11");
                redirect($this->input->post("segValue"));
            }

        }

    }

    public function uploadUserImage()
    {
        //echo 'hello';die;
        //if($this->input->is_ajax_request()){
        // echo '<pre>';print_r($_FILES);die;
        if ($_FILES['file']['error'] == 0) {


            //upload and update the file
            $folderName = 'user_image';
            $pathToUpload = './assets/' . $folderName;
            if (!is_dir($pathToUpload)) {
                mkdir($pathToUpload, 0777, TRUE);
            }

            $config1['upload_path'] = './assets/user_image/';
            // Location to save the image
            $config1['allowed_types'] = 'gif|jpg|png|jpeg';
            $config1['overwrite'] = FALSE;
            $config1['remove_spaces'] = true;
            $config1['maintain_ratio'] = TRUE;
            $config1['max_size'] = '0';
            // $config1['create_thumb'] = TRUE;

            $imgName = date("Y-m-d");
            $nameImg = "";
            $nameChar = "ABCDEFGHIJKLMNOPQRSTYUVWXYZabcdefghijklmnopqrstuvwxyz";

            for ($i = 0; $i < 3; $i++) {

                $nameImg .= $nameChar[rand(0, strlen($nameChar))];
            }
            $config1['file_name'] = $imgName . $nameImg . "_org";

            $thumbName = $imgName;
            $this->load->library('upload', $config1);
            //codeigniter default function

            if (!$this->upload->do_upload('file')) {
                // echo '<pre>';print_r($this->upload);die;

                $msg = "Image not uploaded";
                // redirect  page if the load fails.
            } else {

                $relativeUrl = $this->upload->file_name;
                $this->db->set('photoUrl', $relativeUrl)->where('id', $this->session->userdata('user_id'))->update('tbl_users');
                //echo $this->db->last_query();die;
            }
            // }

        }
    }

    public function changeWebLanguage()
    {
        //print_r($_POST);die;

        $this->session->set_userdata("langSelect", $this->input->post('lang'));
        if ($this->input->post('lang') == 'english') {
            $this->session->set_userdata('languagesWeb', '1');
        } elseif ($this->input->post('lang') == 'portuguese') {
            $this->session->set_userdata('languagesWeb', '2');
        } elseif ($this->input->post('lang') == 'spanish') {
            $this->session->set_userdata('languagesWeb', '3');
        }
        $this->db->set('website_language', $this->input->post('lang'))->set('website_language_id', $this->session->userdata('languagesWeb'))->where('id', $this->session->userdata('user_id'))->update('tbl_users');
        //$this->db->set('article_language',$this->session->userdata('languages'))->where('id',$this->session->userdata('user_id'))->update('tbl_users');
        //print_r($this->session->userdata);die;
        redirect("wootrix-landing-screen");
    }

    public function contactUsPage()
    {
        $contactObj = $this->load->model("website/contact_us_model");
        $contactObj = new contact_us_model();

        $categoryObj = $this->load->model("webservices/category_model");
        $categoryObj = new category_model();

        $getTopics = $categoryObj->getTopics();
        $data['topics'] = $getTopics;


        if ($this->input->post("contactHidden") == "saveHidden") {
            $contactObj->set_first_name($this->input->post("first_name"));
            $contactObj->set_last_name($this->input->post("last_name"));
            $contactObj->set_email($this->input->post("email"));
            $contactObj->set_phone_no($this->input->post("contact_no"));
            $contactObj->set_comment($this->input->post("comment"));
            $contactObj->insertContactData();

            $this->session->set_flashdata("contactMsg", $this->lang->line("query_contact_web"));
            redirect("wootrix-contact-us");
        }

        $data['header'] = array('view' => 'templates/header', 'data' => array());
        $data['main_content'] = array('view' => 'contact_us', 'data' => array());
        $data['footer'] = array('view' => 'templates/footer', 'data' => array());
        $this->load->view('templates/common_template', $data);
    }

    public function privacyPage()
    {

        $categoryObj = $this->load->model("webservices/category_model");
        $categoryObj = new category_model();

        $getTopics = $categoryObj->getTopics();
        $data['topics'] = $getTopics;
        $data['header'] = array('view' => 'templates/header', 'data' => array());
        if ($this->uri->segment(2) == 'en') {
            $data['main_content'] = array('view' => 'privacy_page', 'data' => array());
        }
        if ($this->uri->segment(2) == 'pt') {
            $data['main_content'] = array('view' => 'privacy_page_pt', 'data' => array());
        }
        if ($this->uri->segment(2) == 'es') {
            $data['main_content'] = array('view' => 'privacy_page_es', 'data' => array());
        }
        $data['footer'] = array('view' => 'templates/footer', 'data' => array());
        $this->load->view('templates/common_template', $data);
    }

    public function servicesPage()
    {

        $categoryObj = $this->load->model("webservices/category_model");
        $categoryObj = new category_model();

        $getTopics = $categoryObj->getTopics();
        $data['topics'] = $getTopics;
        $data['header'] = array('view' => 'templates/header', 'data' => array());
        $data['main_content'] = array('view' => 'service_page', 'data' => array());
        $data['footer'] = array('view' => 'templates/footer', 'data' => array());
        $this->load->view('templates/common_template', $data);
    }

    public function getResetPassword()
    {
        //echo $_GET['res'];die;
        if ($_GET['res'] == 'sent') {
            $this->session->set_flashdata('errorLoginMsg1', $this->lang->line("password_sent_webservice"));

        } elseif ($_GET['res'] == 'notsent') {
            $this->session->set_flashdata('errorLoginMsg2', $this->lang->line("email_not_exists_webservice"));

        }
        redirect(base_url());
    }

    public function articleListLayout()
    {

        if ($this->session->userdata("user_id") == '') {
            redirect($this->config->base_url());
        }

//        $this->load->model("webservices/users_model");
//        $userObj = new users_model();
//
//        $name = "Teste";
//        $email = "teste@teste.com.br";
//
//        $userObj->set_name($name);
//        $userObj->set_email($email);
//        $userObj->set_password($this->generateRandomString());
//        $userObj->set_check_website("Website");
//        $signup = $userObj->signupInWootrix();

        $magAdvObj = $this->load->model("website/magazine_advertise_model");
        $magAdvObj = new magazine_advertise_model();

        $categoryObj = $this->load->model("webservices/category_model");
        $categoryObj = new category_model();

        $articleObj = $this->load->model("webservices/new_articles_model");
        $articleObj = new new_articles_model();


        $articleObj->set_token($this->session->userdata("user_id"));
        $articleObj->set_language_name("en");
        $getLangId = $articleObj->getLanguageId();
        if ($articleObj->get_lang_param() == "2") {
            foreach ($getLangId as $l) {
                $lang[] = $l['id'];
            }
        } else {
            $lang = $getLangId['id'];
        }

        $articleObj->set_language_id($lang);
        $articleObj->setServer('web');
        if ($this->session->userdata('topics') != '') {
            $articleObj->setWe_category($this->session->userdata('topics'));
        }
        if ($this->session->userdata('languages') != '') {
            $articleObj->set_language_id($this->session->userdata('languages'));
        }
        $getArticle = $articleObj->getNewArticle();
        //echo "<pre>";print_r($getArticle);die;
        $data['recentArticle'] = $getArticle;
        $getUserMagzines = $articleObj->getUserArticles();

        $magAdvObj->set_magazine_id($_GET['magazineId']);
        if ($this->session->userdata('topics') != '') {
            $category = $this->session->userdata('topics');
            $magAdvObj->setWeb_category($category);
        }
        if ($this->session->userdata('languages') != '') {
            $magAdvObj->set_language_id($this->session->userdata('languages'));
        }

        $magAdvObj->set_article_id($_GET['articleId']);
        $magAdvObj->set_search_key($this->input->post("searchVal"));
        $getMagazineAdvPaging = $magAdvObj->getArticleLayoutCount();

        $categoryObj = $this->load->model("webservices/category_model");
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


        if ($_GET['page'] != '') {//echo 'ghi';die;

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
            //echo 'page='.$perPage;
        } else {
            $perPage = 3;
        }
        //echo 'perpage='.$perPage;die;

        $totalPage = $getMagazineAdvPaging['pageCount'];
        //$allPages=  range(1, $totalPage);
        //echo "Total".$totalPage."<br>";die;
        $j = 0;


        $pageNumberCount = $totalPage % 15;
        //echo $pageNumberCount."<br>";
        $round = (int)($totalPage / 15);
        //echo $round."<br>";die;
        if ($pageNumberCount < 3) {
            $j = 1 + (($round) * 4);
        } else if ($pageNumberCount > 3 && $pageNumberCount <= 4) {
            $j = 2 + (($round) * 4);
        } else if ($pageNumberCount > 4 && $pageNumberCount <= 9) {
            $j = 3 + (($round) * 4);
        } else if ($pageNumberCount > 9) {
            $j = 4 + (($round) * 4);
        }
        //echo "<br>".$pageNumberCount."<br>Page count".$j;

        $pageDemo = 0;
        $config['base_url'] = base_url() . 'index.php/wootrix-articles?';
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
//
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

        $getMagazineAdv = $magAdvObj->getArticleLayoutDetails();

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
        $data['header'] = array('view' => 'templates/header', 'data' => array());

        if ($pageShow == 1) {
            $data['main_content'] = array('view' => 'article_home_screen_1', 'data' => array());
        } else if ($pageShow == 2) {
            $data['main_content'] = array('view' => 'article_home_screen_2', 'data' => array());
        } else if ($pageShow == 3) {
            $data['main_content'] = array('view' => 'article_home_screen_3', 'data' => array());
        } else if ($pageShow == 0) {
            $data['main_content'] = array('view' => 'article_home_screen_4', 'data' => array());
        }

        $data['footer'] = array('view' => 'templates/footer', 'data' => array());
        $this->load->view('templates/common_template', $data);
    }

    public function AdvertiseOnly()
    {
        if ($_POST['advetiseValue'] != '') {
            $pageShow = ($_POST['advetiseValue']) % 4;
            if ($pageShow == '0') {
                $pageShow = '4';
            }
//        echo $magazineId;
//        echo $pageShow;die;
            $magAdvObj = $this->load->model("website/magazine_advertise_model");
            $magAdvObj = new magazine_advertise_model();
            $magAdvObj->set_layout_type($pageShow);
            $advertise = $magAdvObj->getArticlesAdvertiseOnly();


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
                                        <source src="' . $this->config->base_url() . 'assets/Advertise/' . $advertise['cover_image'] . '" type="video/mp4">
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
            /*embed*/
            if ($advertise['cover_image'] == 'embed') {
                preg_match('/src="([^"]+)"/', $advertise['embed_video'], $match);

                $returnString .= '<a href="' . $match[1] . '" target="_blank" >
                        <div id="openEmbed">
                        <img src="' . base_url() . $advertise['embed_thumb'] . '" />
                        </div>
                        <div class="video-overlay">
                              <a href="' . $match[1] . '" target="_blank"><img src="http://103.25.130.197/wootrix__OldToClient/images/website_images/pause-icon.png"></a>
                            </div>
                        </a>';
            }
            /*embed*/
            $returnString .= '<div class="articleTitle">
                                <h4>' . $this->lang->line("advertisement_text") . '</h4>
                               
                            </div>
                        </a>';

            echo $returnString;


            exit();
        }
    }


    public function AdvertiseOnlyLayout2()
    {
        if ($_POST['advetiseValue'] != '') {
            $pageShow = ($_POST['advetiseValue']) % 4;
            if ($pageShow == '0') {
                $pageShow = '4';
            }
//        echo $magazineId;
//        echo $pageShow;die;
            $magAdvObj = $this->load->model("website/magazine_advertise_model");
            $magAdvObj = new magazine_advertise_model();
            $magAdvObj->set_layout_type($pageShow);
            $getMagazineAdv = $magAdvObj->getArticlesAdvertiseOnly();


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
                                    <source src="' . $getMagazineAdv['cover_image'] . '" type="video/mp4">
                                </video>';
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
            /*embed*/
            if ($getMagazineAdv['cover_image'] == 'embed') {
                preg_match('/src="([^"]+)"/', $getMagazineAdv['embed_video'], $match);

                $returnString .= '<a href="' . $match[1] . '" target="_blank" >
                        <div id="openEmbed">
                        <img src="' . base_url() . $getMagazineAdv['embed_thumb'] . '" />
                            </div>
                        </a>';
            }
            /*embed*/
            echo $returnString;


            exit();
        }
    }

    public function AdvertiseOnlyLayout3()
    {
        if ($_POST['advetiseValue'] != '') {
            $pageShow = ($_POST['advetiseValue']) % 4;
            if ($pageShow == '0') {
                $pageShow = '4';
            }
//        echo $magazineId;
//        echo $pageShow;die;
            $magAdvObj = $this->load->model("website/magazine_advertise_model");
            $magAdvObj = new magazine_advertise_model();
            $magAdvObj->set_layout_type($pageShow);
            $getMagazineAdv['advertisement'] = $magAdvObj->getArticlesAdvertiseOnly();


            $returnString = "<script>$( document ).ready(function() {
    $('iframe', window.parent.document).width('580px');
    $('iframe', window.parent.document).height('380px');
});</script>";

            $returnString .= '<a href="' . $getMagazineAdv['advertisement']['link'] . '" target="_blank onclick = "remoteaddr(' . $getMagazineAdv['adsid'] . ');">';

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
                                    <source src="' . $getMagazineAdv['advertisement']['cover_image'] . '" type="video/mp4">
                                </video>';
                    }
                } else {
                    $returnString .= '<img src="' . $this->config->base_url() . 'images/website_images/placeholder-1.jpg" alt=""/>';
                }

            } else {
                $returnString .= '<img src="' . $this->config->base_url() . 'images/website_images/placeholder-1.jpg" alt=""/>';
            }
            $returnString .= '</a>';
            /*embed*/
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
            /*embed*/

            echo $returnString;


            exit();
        }
    }

    public function AdvertiseOnlyLayout4()
    {
        if ($_POST['advetiseValue'] != '') {
            $pageShow = ($_POST['advetiseValue']) % 4;
            if ($pageShow == '0') {
                $pageShow = '4';
            }
//        echo $magazineId;
//        echo $pageShow;die;
            $magAdvObj = $this->load->model("website/magazine_advertise_model");
            $magAdvObj = new magazine_advertise_model();
            $magAdvObj->set_layout_type($pageShow);
            $getMagazineAdv['advertisement'] = $magAdvObj->getArticlesAdvertiseOnly();


            $returnString = "<script>$( document ).ready(function() {
    $('iframe', window.parent.document).width('580px');
    $('iframe', window.parent.document).height('190px');
});</script>";

            $returnString .= '<figure>
                                <a href="' . $getMagazineAdv['advertisement']['link'] . '" target="_blank" onclick = "remoteaddr(' . $getMagazineAdv['adsid'] . ');">';

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
                                </video>';
                    }
                } else {
                    $returnString .= '<img src="' . $this->config->base_url() . 'images/website_images/placeholder-2.jpg" alt=""/>';
                }

            } else {
                $returnString .= '<img src="' . $this->config->base_url() . 'images/website_images/placeholder-2.jpg" alt=""/>';
            }
            $returnString .= '</a>
                                
                                
                            </figure>';
            if ($getMagazineAdv['advertisement']['cover_image'] == 'embed') {
                preg_match('/src="([^"]+)"/', $getMagazineAdv['advertisement']['advertisement']['embed_video'], $match);

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


    /*save ads report lat/long*/
    public function saveLatLong()
    {
        //echo"<pre>"; print_r($_POST); die;

        $dlocation = $_SERVER['REMOTE_ADDR'];
        //$address = '203.123.36.130'; // Google HQ
        $address = $dlocation; // Google HQ
        $prepAddr = str_replace(' ', '+', $address);
        //$geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
        $geocode = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $prepAddr));
        //echo"<pre>"; print_r($geocode);
        $latitude = $geocode['geoplugin_latitude'];
        $longitude = $geocode['geoplugin_longitude'];
        $user_id = $this->session->userdata("user_id");
        $ads_id = $_POST['ads'];
        $ads_type = $_POST['val'];


        $data = array('user_id' => $user_id, 'latitude' => $latitude, 'longitude' => $longitude, 'article_id' => $ads_id, 'device_type' => '3');
        if ($ads_type == 1) {
            $sql = $this->db->set('created_date', 'now()', FALSE)->insert('tbl_ads_report', $data);
        } else {
            $sql = $this->db->set('created_date', 'now()', FALSE)->insert('tbl_magazine_advertisement', $data);
        }

        //echo $this->db->last_query(); die;


        exit();
    }

    public function resetpassword($id)
    {
        $userObj = $this->load->model("webservices/users_model");
        $userObj = new users_model();
        $userObj->set_id($id);
        $this->session->set_userdata('resetdata', true);
        $data['id'] = $userObj->get_id();
        $data['header'] = array('view' => 'templates/header', 'data' => array());
        $data['main_content'] = array('view' => 'reset', 'data' => array());
        $data['footer'] = array('view' => 'templates/footer', 'data' => array());
        $this->load->view('templates/common_template', $data);
    }

    public function resetpost()
    {
        $userObj = $this->load->model("webservices/users_model");
        $userObj = new users_model();
        $userObj->set_id($this->input->post("id"));
        $userObj->set_password($this->input->post("password"));
        $ret = $userObj->resetpost();
        if ($ret) {
            $this->session->set_flashdata("success_msg", "<font class='success'>Reset Password Successfully......</font>");
        } else {
            $this->session->set_flashdata("error_msg", "<font class='success'>Error!!!!!!!!</font>");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function register_access()
    {

        if ($this->session->userdata('user_id') == "") {
            redirect("login_check");
        }

        $this->load->helper('general_helper');

        $url = $_GET["url"];
        $magazineId = $_GET["magazineId"];
        $articleId = $_GET["articleId"];
        $token = $this->session->userdata('user_id');

        if (empty($url) || empty($articleId)) {
            redirect("wootrix-articles");
        }

        $this->load->model("webservices/users_model");
        $userObj = new users_model();
        $userObj->set_token($token);
        $user = $userObj->getUserAccountDetails();

        $this->load->model("webservices/magazine_access_model");
        $magazineAccessModel = new magazine_access_model();

        $country = "";
        $state = "";
        $city = "";

        if ($user["latitude"] != 0 && $user["longitude"] != 0) {

            $latitude = $user["latitude"];
            $longitude = $user["longitude"];

            $dataLocation = getLocationByLatLng($latitude, $longitude);

            $country = $dataLocation["country"];
            $state = $dataLocation["state"];
            $city = $dataLocation["city"];

        }

        $dateTime = new DateTime('now');
        $dateFormatted = $dateTime->format("Y-m-d H:i:s");

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

        redirect($url);

    }

    public function getSignageContent() {

        $this->load->model("web/magazine_model");
        $obj = new magazine_model();

        $this->load->model("customer/customers_model");
        $customerObj = new customers_model();

        $campaignId = $_GET["campaign"];

        $allContent = $customerObj->getCampaignContent($campaignId);

        $campaign = $obj->getCampaign($campaignId);
        $counter = $campaign->counter;

        if( $counter >= count($allContent) ){
            $counter = 0;
        }

        $article = $obj->getSignageArticle($campaignId, $counter);

        $dateFormat = new DateTime($article[0]["publish_date"]);
        $article[0]["publish_date"] = $dateFormat->format("d/m/Y");

        $data["campaign"] = $campaign;
        $data["result"] = $article;
        $data['header'] = array('view' => 'templates/header_signage', 'data' => array());
        $data['main_content'] = array('view' => 'signage_content_' . $campaign->layout);
        $data['footer'] = array('view' => 'templates/footer_signage', 'data' => array());
        $this->load->view('templates/common_template', $data);

        $counter++;

        $obj->updateItemCounter($campaignId, $counter);

//        $data["result"] = $magazineArticles;


//        $filename = FCPATH . "assets\Article\\" . $magazineArticles[0]["image"]; //<-- specify the image  file
//
//        if(file_exists($filename)){
//            header('Content-Length:' . filesize($filename)); //<-- sends filesize header
//            header('Content-Type: image/jpg'); //<-- send mime-type header
//            header('Content-Disposition: inline; filename="'.$filename.'";'); //<-- sends filename header
//            readfile($filename); //<--reads and outputs the file onto the output buffer
//            die(); //<--cleanup
//            exit; //and exit
//        }

    }

    public function getSignageText() {

        $this->load->model("web/magazine_model");
        $obj = new magazine_model();

        $magazineId = $_GET["magazineId"];
        $item = $_GET["item"];

        $obj->set_id($magazineId);
        $magazineArticles = $obj->getArticlesByMagazineFilter("", $item);

//        echo '<pre>';
//        print_r( $magazineArticles );
//        echo '</pre>';

        $data["result"] = $magazineArticles;
        $data['header'] = array('view' => 'templates/header_signage', 'data' => array());
        $data['main_content'] = array('view' => 'text_1');
        $data['footer'] = array('view' => 'templates/footer_signage', 'data' => array());
        $this->load->view('templates/common_template', $data);
    }

    public function getSignageQrCode() {

        require_once 'src/phpqrcode.php';

        $this->load->model("web/magazine_model");
        $obj = new magazine_model();

        $magazineId = $_GET["magazineId"];
        $item = $_GET["item"];

        $obj->set_id($magazineId);
        $magazineArticles = $obj->getArticlesByMagazineFilter("", $item);

        QRcode::png('teste');

    }

}



