<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * @author Techahead
 * 
 */

class Login_twitter extends CI_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('email');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->library('session', true);
    }

    public function index() {
        //session_unset();die;
        //$this->config->load('twitter');
        $this->load->library('twitteroauth');
        //include_once("inc/twitteroauth.php");
        $CONSUMER_KEY = $this->config->item('CONSUMER_KEY');
        $CONSUMER_SECRET = $this->config->item('CONSUMER_SECRET');
        $OAUTH_CALLBACK = $this->config->base_url() . 'index.php/website/login_twitter/index';
        if($_GET['add']=='1'){
        $this->session->set_userdata('addTwitter','1');
        }
        
        if (isset($_REQUEST['oauth_token']) && $this->session->userdata('token') !== $_REQUEST['oauth_token']) {
            
            // if token is old, distroy any session and redirect user to index.php
            session_destroy();

            redirect('website/login_twitter/index');
        } elseif (isset($_REQUEST['oauth_token']) && $this->session->userdata('token') == $_REQUEST['oauth_token']) {
            //echo $this->session->userdata('token');die;
            // everything looks good, request access token
            //successful response returns oauth_token, oauth_token_secret, user_id, and screen_name
            $connection = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET, $this->session->userdata('token'), $this->session->userdata('token_secret'));
            $access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
            //echo "<pre>";print_r($access_token);die;

            if ($connection->http_code == '200') {
                //redirect user to twitter
                $this->session->set_userdata('status', 'verified');
                $this->session->set_userdata('request_vars', $access_token);

                // unset no longer needed request tokens
                $this->session->unset_userdata('token');
                $this->session->unset_userdata('token_secret');
                /* save records into database */

                $userObj = $this->load->model("webservices/users_model");
                $userObj = new users_model();
                
                
                $twId = $access_token['user_id'];
                if($this->session->userdata('addTwitter')=='1'){ 
                    $userObj->set_twitter_id($twId);
                    $userObj->addAccountWeb();
                    
                }
                $twName = $access_token['screen_name'];
                
                
                $userObj->set_name($twName);                
                $userObj->set_device_type("3");
                $userObj->set_twitter_id($twId);
                $userObj->set_registration_type("3");
                $getSignupLogin = $userObj->signupAndLoginInWootrix();
                $getLogin = $userObj->getTwitterUserData();
                if($getLogin != FALSE){
                    $this->session->set_userdata('user_id', $getLogin['id']);
                $this->session->set_userdata('user_email', $getLogin['email']);
                $this->session->set_userdata('user_name', $getLogin['name']);
                $this->session->set_userdata("langSelect",$getLogin['website_language']);
                $this->session->set_userdata('languages',$getLogin['article_language']);
                $this->session->set_userdata('topics',$getLogin['category']);
                $this->session->set_userdata('languagesWeb',$getLogin['website_language_id']);
                redirect('wootrix-landing-screen');
                }
                
            } else {
                $this->session->set_flashdata('error_msg', 'connection falied');
                redirect($this->config->base_url());
            }
        } else {

            if (isset($_GET["denied"])) {
                $this->session->set_flashdata('error_msg', 'connection falied');
                redirect($this->config->base_url());
            }

            //fresh authentication
            $connection = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET);

            $request_token = $connection->getRequestToken($OAUTH_CALLBACK);
            //echo "<pre>";print_r($request_token);die;
            //received token info from twitter
            $this->session->set_userdata('token', $request_token['oauth_token']);
            $this->session->set_userdata('token_secret', $request_token['oauth_token_secret']);
            //echo $connection->http_code;die;
            // any value other than 200 is failure, so continue only if http code is 200
            if ($connection->http_code == '200') {
                //redirect user to twitter
                $twitter_url = $connection->getAuthorizeURL($request_token['oauth_token']);
                //echo $twitter_url;die;
                redirect($twitter_url);
            } else {
                $this->session->set_flashdata('error_msg', 'connection falied');
                redirect('sign-in');
            }
        }
        //$this->load->view("website_login/twitter_login");
    }
    
    public function twitterCallBack(){
        echo "11";die;
    }
    
    

}
