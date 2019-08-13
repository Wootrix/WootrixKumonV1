<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * @author Techahead
 * 
 */

class Login_facebook extends MX_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('email');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->library('session', true);
        $this->load->library('facebook');
    }
    
    

    public function loginWithFacebook() {
        //session_unset();
        //die;
        $userObj = $this->load->model("webservices/users_model");
        $userObj = new users_model();
        $facebook = new Facebook(array(
            'appId' => '872055026158811', // Facebook App ID 
            'secret' => 'eec3b3a30428e9ba1e799408755e8915', // Facebook App Secret
            'cookie' => true,
        ));

        $user = $facebook->getUser();

        if ($user) {
            try {
                $user_profile = $facebook->api('/me');
                //echo "<pre>";print_r($user_profile);die;
                
                $fbId = $user_profile['id'];                 // To Get Facebook ID
                $fbEmail = $user_profile['email'];
                $fbFirstName = $user_profile['first_name'];
                $fbLastName = $user_profile['last_name'];
                $fbGender = $user_profile['gender'];                
                $fbName = $user_profile['name'];
                $fbImage = "https://graph.facebook.com/".$user_profile['id']."/picture";
                
                $userObj->set_email($fbEmail);
                $userObj->set_name($fbName);
                $userObj->set_photoUrl($fbImage);
                $userObj->set_device_type("3");
                $userObj->set_facebook_id($fbId);
                $userObj->set_registration_type("1");
                $getSignupLogin = $userObj->signupAndLoginInWootrix();
                
                
            } catch (FacebookApiException $e) {
                
                error_log($e);
                $user = null;
            }
        }
        if ($user) {
            redirect("wootrix-landing-screen");
        } else {
            $loginUrl = $facebook->getLoginUrl(array(
                'scope' => 'email', // Permissions to request from the user
            ));
            $data['fbLogin'] = $loginUrl;
            //redirect("website/login_facebook/loginWithFacebook");
        }



        $data['header'] = array('view' => 'templates/header', 'data' => array());
        $data['main_content'] = array('view' => 'website_login/login', 'data' => array());
        $data['footer'] = array('view' => 'templates/footer', 'data' => array());
        $this->load->view('templates/common_template', $data);
    }

}
