<?php

class loginWithLinkedIn extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('email');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->library('session', true);
    }

    public function index() {
        
        $callbackURL = base_url().'index.php/website/loginWithLinkedIn';
        $linkedinApiKey = '78mqq80rpy81un';
        $linkedinApiSecret = '6Aw7k7FsyaT2v3io';
        $linkedinScope = 'r_basicprofile r_emailaddress';
        include_once("LinkedIn/http.php");
        include_once("LinkedIn/oauth_client.php");

        if($_GET['add']=='1'){
            $this->session->set_userdata('addLinkedIn',$_GET['add']);
        }
        
        if (isset($_GET["oauth_problem"]) && $_GET["oauth_problem"] <> "") {
            // in case if user cancel the login. redirect back to home page.
            $this->session->set_userdata('err_msg',$_GET["oauth_problem"]);
            redirect(base_url());
        }

        $client = new oauth_client_class;

        $client->debug = false;
        $client->debug_http = true;
        $client->redirect_uri = $callbackURL;

        $client->client_id = $linkedinApiKey;
        $application_line = __LINE__;
        $client->client_secret = $linkedinApiSecret;

        if (strlen($client->client_id) == 0 || strlen($client->client_secret) == 0)
            die('Please go to LinkedIn Apps page https://www.linkedin.com/secure/developer?newapp= , ' .
                            'create an application, and in the line ' . $application_line .
                            ' set the client_id to Consumer key and client_secret with Consumer secret. ' .
                            'The Callback URL must be ' . $client->redirect_uri) . ' Make sure you enable the ' .
                    'necessary permissions to execute the API calls your application needs.';

        /* API permissions
         */
        $client->scope = $linkedinScope;
        if (($success = $client->Initialize())) {
            if (($success = $client->Process())) {
                if (strlen($client->authorization_error)) {
                    $client->error = $client->authorization_error;
                    $success = false;
                } elseif (strlen($client->access_token)) {
                    $success = $client->CallAPI(
                            'http://api.linkedin.com/v1/people/~:(id,email-address,first-name,last-name,location,picture-url,public-profile-url,formatted-name)', 'GET', array(
                        'format' => 'json'
                            ), array('FailOnAccessError' => true), $user);
                }
                $success = $client->Finalize($success);
            }
            
            if ($client->exit) exit;
            if ($success) {
            $user_array=(array)$user;
            $userObj = $this->load->model("webservices/users_model");
            $userObj = new users_model();
            //print '<pre>';print_r($user_array);die;
            if($this->session->userdata('addLinkedIn')!=''){
                $userObj->set_linkedin_id($user_array['id']);
                $userObj->addAccountWeb();
            }
            $linId = $user_array['id'];
            $linName = $user_array['formattedName'];
            $linPic = $user_array['pictureUrl'];
            $linEmail = $user_array['emailAddress'];

            $userObj->set_email($linEmail);
            $userObj->set_name($linName);
            $userObj->set_photoUrl($linPic);
            $userObj->set_device_type("3");
            $userObj->set_linkedin_id($linId);
            $userObj->set_registration_type("2");
            $userObj->signupAndLoginInWootrix();
            $getLogin = $userObj->getLinkedinUserData();
            
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
        }
        }
        
    }

}
