<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * @author Techahead
 * 
 */

class Login_google extends MX_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('email');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->library('session', true);
    }

    public function loginWithGooglePlus() {

//        $clientId = '1016469935217-a908rduo1uvbsg7oocihe3ogc6ifnlu6.apps.googleusercontent.com';
//        $clientSecret = 'gExF8DhAR52j_79Q9Aa9P79J';
//        //$redirectUrl = $this->config->base_url()."index.php/website/login_google/loginWithGooglePlus";
//        $redirectUrl = "http://localhost/wootrix";
        
        $clientId = '181469443324-j5neoccka33afjuvm4gnu2itnn07oo0n.apps.googleusercontent.com';
        $clientSecret = 'w3LvrZ2JAHl5Zlg6ZicI-San';
        //$redirectUrl = $this->config->base_url()."index.php/website/login_google/loginWithGooglePlus";
        $redirectUrl = "http://fbh.wootrix.com/";
        

// -----------------------------------------------------------------------------
// DO NOT EDIT BELOW THIS LINE
// -----------------------------------------------------------------------------


        require_once 'src/Google_Client.php';
        require_once 'src/service/Oauth2.php';


        session_start();

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
            $getSignupLogin = $userObj->signupAndLoginInWootrix();
            redirect("wootrix-landing-screen");
        }
    }

    public function callBack() {
        echo "11";
        die;
    }

}
