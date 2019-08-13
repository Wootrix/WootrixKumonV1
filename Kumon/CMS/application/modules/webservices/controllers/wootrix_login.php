<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * @author Techahead
 * 
 */

class Wootrix_login extends CI_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session', true);

    }

    public function wootrixLogin() {

        $authValidValue = $this->config->item("validValue");

        if ($authValidValue != '') {

            $json = trim(file_get_contents('php://input'));

            if (!empty($json)) {

                $json = json_decode($json, true);

                if ($json['appLanguage'] == "en") {
                    $this->lang->load('en');
                } elseif ($json['appLanguage'] == "pt") {
                    $this->lang->load('po');
                } elseif ($json['appLanguage'] == "es") {
                    $this->lang->load('sp');
                }

                $this->load->model("webservices/users_model");
                $userObj = new users_model();

                $kumonUser = $this->getKumonUser($json['email']);

                if( !$kumonUser ){
                    echo json_encode(array('data' => array(), 'message' => "Token inválido", 'success' => false));
                    exit;
                } else {

                    $userObj->set_email($kumonUser['email']);
                    $userObj->set_photoUrl($json['photoUrl']);

                    if ($json['device'] == "iPhone") {
                        $userObj->set_device_type("1");
                    } elseif ($json['device'] == "Android") {
                        $userObj->set_device_type("2");
                    } elseif ($json['device'] == "Website") {
                        $userObj->set_device_type("3");
                    }

                    $userObj->set_osVersion($json['osVersion']);

                    $userObj->set_latitude($json['latitude']);
                    $userObj->set_longitude($json['longitude']);
                    $userObj->set_UserId($kumonUser['id']);
                    $userObj->set_DeviceId($json['deviceId']);
                    $userObj->storeDeviceLocation();

                    if ($kumonUser['token'] != '') {
                        $this->db->set("token_expiry_date", "now()", false)
                            ->where('token', $kumonUser['token'])->update("tbl_users");
                    }

                    $i = 0;
                    $result = array();
                    $result[$i]['socialAccountToken'] = $kumonUser['socialAccountToken'];
                    $result[$i]['user']['name'] = $kumonUser['name'];
                    $exp = explode(":", $kumonUser['photoUrl']);
                    if ($exp[0] == "http" || $exp[0] == "https") {
                        $url = $kumonUser['photoUrl'];
                    } else {
                        if ($kumonUser['photoUrl']) {
                            $url = $this->config->base_url() . "assets/user_image/" . $kumonUser['photoUrl'];
                        } else {
                            $url = "";
                        }
                    }
                    $result[$i]['user']['photoUrl'] = $url;
                    $result[$i]['user']['email'] = $kumonUser['email'];
                    $result[$i]['requireEmail'] = "N";
                    $result[$i]['requirePassword'] = "N";
                    $result[$i]['token'] = $kumonUser['token'];
                    $result[$i]['tokenExpiryDate'] = $kumonUser['token_expiry_date'];
                    $result[$i]['user_id'] = $kumonUser['id'];

                    $result[$i]['is_email'] = ($kumonUser['email'] != '' ? TRUE : '');
                    $result[$i]['is_facebook'] = ($kumonUser['facebook_id'] != '' ? TRUE : FALSE);
                    $result[$i]['is_twitter'] = ($kumonUser['twitter_id'] != '' ? TRUE : FALSE);
                    $result[$i]['is_google'] = ($kumonUser['google_id'] != '' ? TRUE : FALSE);
                    $result[$i]['is_linkedin'] = ($kumonUser['linkedin_id'] != '' ? TRUE : FALSE);

                    echo json_encode(array('data' => $result, 'message' => $this->lang->line("login_webservice"), 'success' => true));
                    exit;

                }

            } else {
                echo json_encode(array('data' => array(), 'message' => $this->lang->line("input_data_webservice"), 'success' => false));
                exit;
            }
        } else {
            echo json_encode(array('data' => array(), 'message' => $this->lang->line("input_data_webservice"), 'success' => false));
            exit;
        }
    }

    private function getKumonUser($email){

        $this->load->model("webservices/users_model");
        $userObj = new users_model();

        $response_xml = $this->getKumonResponse($email);

        $xml = new SimpleXMLElement($response_xml);
        $xml->registerXPathNamespace("diffgr", "urn:schemas-microsoft-com:xml-diffgram-v1");
        $body = $xml->xpath("//diffgr:diffgram");

        if( isset( $body[0]->NewDataSet->Table ) ){

//            $kumonId = $body[0]->NewDataSet->Table->UserID;

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
//                $userObj->set_location($body[0]->NewDataSet->Table->Branch->__toString());
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
                return $userObj->getUserByKumonEmail($email);
            }

        } else {
            return false;
        }

    }

    private function getKumonResponse($email){

        //DEV
//        $url = 'http://201.91.129.22/wsKumon01/PortalOrientador.asmx?WSDL';

        //PROD
        $url = 'http://ws01.unidadekumon.com.br/weborder/PortalOrientador.asmx';

        $client = new SoapClient($url . "?WSDL", array("trace" => 1, "exception" => 1));

        $function = 'getDataUserExternal';

        $intEntityID = 28;

        if( $_SERVER['SERVER_NAME'] == "endirecto.unidadkumon.com"){
            $intEntityID = 29;
        }

        $arguments = array( "getDataUserExternal" => array(
            'strApp' => "41883FA5-D29A-439B-9186-6E8E9000572D",
            'strToken' => '1234567890',
            "strEmail" => $email,
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

    public function signupInApp() {
        $authValidValue = $this->config->item("validValue");
        if ($authValidValue != '') {
//            $json = '{
//            "name":"ghjghj",
//            "email":"w@w.com",
//            "password":"123456",
//            "appLanguage":"en"
//            }';

            $json = trim(file_get_contents('php://input'));

            if (!empty($json)) {
                $json = json_decode($json, true);
                if ($json['appLanguage'] == "en") {
                    $this->lang->load('en');
                } elseif ($json['appLanguage'] == "pt") {
                    $this->lang->load('po');
                } elseif ($json['appLanguage'] == "es") {
                    $this->lang->load('sp');
                }
                $userObj = $this->load->model("webservices/users_model");
                $userObj = new users_model();

                $userObj->set_email($json['email']);
                $userObj->set_name($json['name']);
                $userObj->set_password($json['password']);

                $userObj->signupInWootrix();

                //echo "<pre>";print_r($result);die;
                echo json_encode(array('data' => array(), 'message' => $this->lang->line("signup_webservice"), 'success' => true));
                exit;
            } else {
                echo json_encode(array('data' => array(), 'message' => $this->lang->line("input_data_webservice"), 'success' => false));
                exit;
            }
        } else {
            echo json_encode(array('data' => array(), 'message' => $this->lang->line("input_data_webservice"), 'success' => false));
            exit;
        }
    }

    public function forgotPassword() {

        if ($this->input->post('loginHidden') == 'websiteForm') {

            $userObj = $this->load->model("webservices/users_model");
            $userObj = new users_model();
            $userObj->set_email($this->input->post('forgetEmail'));
            $userObj->set_check_website('website');
            $userObj->changePassword();
            $this->session->set_userdata('errorLoginMsg', $this->lang->line("password_sent_webservice"));
            redirect(base_url());
        }

        $authValidValue = $this->config->item("validValue");
        if ($authValidValue != '') {
//            $json = '{
//            "email":"ketan@techaheadcorp.com"
//            }';

            $json = trim(file_get_contents('php://input'));

            if (!empty($json)) {
                $json = json_decode($json, true);
                if ($json['appLanguage'] == "en") {
                    $this->lang->load('en');
                } elseif ($json['appLanguage'] == "pt") {
                    $this->lang->load('po');
                } elseif ($json['appLanguage'] == "es") {
                    $this->lang->load('sp');
                } else {
                    $this->lang->load('en');
                }
                $userObj = $this->load->model("webservices/users_model");
                $userObj = new users_model();

                $userObj->set_email($json['email']);
                $result = $userObj->changePassword();
                //die("ksjhdj");
            } else {
                echo json_encode(array('data' => array(), 'message' => $this->lang->line("input_data_webservice"), 'success' => false));
                exit;
            }
        } else {
            echo json_encode(array('data' => array(), 'message' => $this->lang->line("input_data_webservice"), 'success' => false));
            exit;
        }
    }

    public function changeUserPhoto() {

        $authValidValue = $this->config->item("validValue");
        if ($authValidValue != '') {

//            $json = '{
//            "token":"1",
//            "appLanguage":"en"
//            }';
            //$json = trim(file_get_contents('php://input'));
            $json = $this->input->get_post('json');
            //print_r($json);die;
            if (!empty($json)) {

                $json = json_decode($json, true);
                if ($json['appLanguage'] == "en") {
                    $this->lang->load('en');
                } elseif ($json['appLanguage'] == "pt") {
                    $this->lang->load('po');
                } elseif ($json['appLanguage'] == "es") {
                    $this->lang->load('sp');
                }

                //print_r($json);die;
                $userObj = $this->load->model("webservices/users_model");
                $userObj = new users_model();

                $userObj->set_token($json['token']);

                    if ($_FILES['photo']['error'] == 0) {

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

                        if (!$this->upload->do_upload('photo')) {

                            $msg = "Image not uploaded";
                            // redirect  page if the load fails.
                        } else {

                            $relativeUrl = $this->upload->file_name;
                        }
                    }
                    $userObj->set_photoUrl($this->upload->file_name);
                    $getUpdate = $userObj->updateUserPhoto();
                    $exp = explode(":", $getUpdate['photoUrl']);
                    if ($exp[0] == "http" || $exp[0] == "https") {
                        $url = $getUpdate['photoUrl'];
                    } else {
                        if ($getUpdate['photoUrl'] != '') {
                            $url = $this->config->base_url() . "assets/user_image/" . $getUpdate['photoUrl'];
                        } else {
                            $url = "";
                        }
                    }
                    $result = array();
                    $i = 0;
                    $result['user'][$i]['name'] = $getUpdate['name'];
                    $result['user'][$i]['photoUrl'] = $url;
                    $result['user'][$i]['email'] = $getUpdate['email'];
                    $i++;

                    $result1[] = $result;
                    echo json_encode(array('data' => $result1, 'message' => $this->lang->line("user_photo_webservice"), 'success' => true));
                    exit;

            } else {
                echo json_encode(array('data' => array(), 'message' => $this->lang->line("input_data_webservice"), 'success' => false));
                exit;
            }
        } else {
            echo json_encode(array('data' => array(), 'message' => $this->lang->line("input_data_webservice"), 'success' => false));
            exit;
        }
    }

    public function changeUserEmail() {
        $authValidValue = $this->config->item("validValue");
        if ($authValidValue != '') {
//            $json = '{
//            "token":"1",
//            "oldEmail":"fdsd@fdgd.com",
//            "newEmail":"s@s.com",
//            "appLanguage":"en"
//            }';

            $json = trim(file_get_contents('php://input'));
            //echo $json;die;
            if (!empty($json)) {
                $json = json_decode($json, true);
                if ($json['appLanguage'] == "en") {
                    $this->lang->load('en');
                } elseif ($json['appLanguage'] == "pt") {
                    $this->lang->load('po');
                } elseif ($json['appLanguage'] == "es") {
                    $this->lang->load('sp');
                }
                $userObj = $this->load->model("webservices/users_model");
                $userObj = new users_model();

                $userObj->set_token($json['token']);

                $verifyToken = $userObj->verifyTokenValue();
                $userObj->set_old_email($json['oldEmail']);
                $userObj->set_new_email($json['newEmail']);
                if ($verifyToken == TRUE) {
                    if ($json['oldEmail'] != '') {
                        $getUserDetails = $userObj->updateNewEmailAndCheckOld();
                        $result = array();
                        $i = 0;
                        $result['user'][$i]['name'] = $getUserDetails['name'];
                        $result['user'][$i]['photoUrl'] = $getUserDetails['photoUrl'];
                        $result['user'][$i]['email'] = $getUserDetails['email'];
                        $i++;

                        $result1[] = $result;
                        echo json_encode(array('data' => $result1, 'message' => $this->lang->line("user_updated_email_webservice"), 'success' => true));
                        exit;
                    } else {
                        $getUpdateNewEmail = $userObj->getUpdateNewEmail();
                        if ($getUpdateNewEmail != FALSE) {
                            $i = 0;
                            $result['user'][$i]['name'] = $getUpdateNewEmail['name'];
                            $result['user'][$i]['photoUrl'] = $getUpdateNewEmail['photoUrl'];
                            $result['user'][$i]['email'] = $getUpdateNewEmail['email'];
                            $i++;

                            $result1[] = $result;
                            echo json_encode(array('data' => $result1, 'message' => $this->lang->line("user_updated_email_webservice"), 'success' => true));
                            exit;
                        } else {
                            echo json_encode(array('data' => array(), 'message' => $this->lang->line("user_updated_email_webservice_exist"), 'success' => false));
                            exit;
                        }
                    }

                    echo json_encode(array('data' => $result1, 'message' => $this->lang->line("user_photo_webservice"), 'success' => true));
                    exit;
                } else {
                    echo json_encode(array('data' => array(), 'message' => $this->lang->line("token_expire_webservice"), 'success' => false));
                    exit;
                }
            } else {
                echo json_encode(array('data' => array(), 'message' => $this->lang->line("input_data_webservice"), 'success' => false));
                exit;
            }
        } else {
            echo json_encode(array('data' => array(), 'message' => $this->lang->line("input_data_webservice"), 'success' => false));
            exit;
        }
    }

    public function changeUserPassword() {
        $authValidValue = $this->config->item("validValue");
        if ($authValidValue != '') {
//            $json = '{
//            "token":"1",
//            "oldPassword":"123456",
//            "newPassword":"1234567",
//            "appLanguage":"en"
//            }';

            $json = trim(file_get_contents('php://input'));
            //echo $json;die;
            if (!empty($json)) {
                $json = json_decode($json, true);
                if ($json['appLanguage'] == "en") {
                    $this->lang->load('en');
                } elseif ($json['appLanguage'] == "pt") {
                    $this->lang->load('po');
                } elseif ($json['appLanguage'] == "es") {
                    $this->lang->load('sp');
                }
                $userObj = $this->load->model("webservices/users_model");
                $userObj = new users_model();

                $userObj->set_token($json['token']);

                $verifyToken = $userObj->verifyTokenValue();
                $userObj->set_old_password($json['oldPassword']);
                $userObj->set_new_password($json['newPassword']);
                if ($verifyToken == TRUE) {
                    if ($json['oldPassword'] != '') {
                        $getUserDetails = $userObj->updateNewPasswordAndCheckOld();

                        echo json_encode(array('data' => array(), 'message' => $this->lang->line("user_password_webservice"), 'success' => true));
                        exit;
                    } else {
                        $getUpdateNewPassword = $userObj->getUpdateNewPassword();

                        echo json_encode(array('data' => array(), 'message' => $this->lang->line("user_password_webservice"), 'success' => true));
                        exit;
                    }
                } else {
                    echo json_encode(array('data' => array(), 'message' => $this->lang->line("token_expire_webservice"), 'success' => false));
                    exit;
                }
            } else {
                echo json_encode(array('data' => array(), 'message' => $this->lang->line("input_data_webservice"), 'success' => false));
                exit;
            }
        } else {
            echo json_encode(array('data' => array(), 'message' => $this->lang->line("input_data_webservice"), 'success' => false));
            exit;
        }
    }

    public function adsReportOnClick() {
        $authValidValue = $this->config->item("validValue");
        if ($authValidValue != '') {
//            $json = '{
//            "token":"1",
//            "latitude":"42.2423",
//            "longitude":"-100.84",
//            "advertisementId":"2",
//            "device":"Android",
//            "appLanguage":"en",
//            "type":"open"
//            }';
            // Device are iPhone, Android, Website
            $json = trim(file_get_contents('php://input'));
            //echo $json;die;
            if (!empty($json)) {
                $json = json_decode($json, true);
                if ($json['appLanguage'] == "en") {
                    $this->lang->load('en');
                } elseif ($json['appLanguage'] == "pt") {
                    $this->lang->load('po');
                } elseif ($json['appLanguage'] == "es") {
                    $this->lang->load('sp');
                }
                $adsReportObj = $this->load->model("webservices/ads_report_model");
                $adsReportObj = new ads_report_model();

                $adsReportObj->set_token($json['token']);
                $adsReportObj->set_latitude($json['latitude']);
                $adsReportObj->set_longitude($json['longitude']);
                $adsReportObj->set_article_id($json['advertisementId']);
                if ($json['device'] == "iPhone") {
                    $adsReportObj->set_device_type("2");
                } elseif ($json['device'] == "Android") {
                    $adsReportObj->set_device_type("1");
                } elseif ($json['device'] == "Website") {
                    $adsReportObj->set_device_type("3");
                }
                $adsReportObj->setType($json['type']);
                $adsReportObj->insertAdsReportData();
                echo json_encode(array('data' => array(), 'message' => $this->lang->line("ads_report_webservice"), 'success' => true));
                exit;
            } else {
                echo json_encode(array('data' => array(), 'message' => $this->lang->line("input_data_webservice"), 'success' => false));
                exit;
            }
        } else {
            echo json_encode(array('data' => array(), 'message' => $this->lang->line("input_data_webservice"), 'success' => false));
            exit;
        }
    }

    public function verifyTokenValue() {

        $authValidValue = $this->config->item("validValue");

        if ($authValidValue != '') {

            $json = trim(file_get_contents('php://input'));

            if (!empty($json)) {

                $json = json_decode($json, true);

                $this->load->model("webservices/users_model");
                $userObj = new users_model();

                $token = $json["token"];
                $user = $userObj->getUserByToken($token);

                if( $user != null ){

                    $now = new DateTime();
                    $month = $now->format("m");

                    $userTokenDate = $user["token_expiry_date"];
                    $userExpiryDate = DateTime::createFromFormat("Y-m-d H:i:s", $userTokenDate);
                    $lastMonthLogged = $userExpiryDate->format("m");

                    if( $lastMonthLogged != $month ){
                        echo json_encode(array('data' => array(), 'message' => $this->lang->line("token_invalid_webservice"), 'success' => false));
                        exit;
                    }

                    $response_xml = $this->getKumonResponse($user["email"]);

                    $xml = new SimpleXMLElement($response_xml);
                    $xml->registerXPathNamespace("diffgr", "urn:schemas-microsoft-com:xml-diffgram-v1");
                    $body = $xml->xpath("//diffgr:diffgram");

                    if( isset( $body[0]->NewDataSet->Table ) ){
                        echo json_encode(array('data' => array(), 'message' => $this->lang->line("token_valid_webservice"), 'success' => true));
                    } else {
                        echo json_encode(array('data' => array(), 'message' => $this->lang->line("token_invalid_webservice"), 'success' => false));
                    }

                    exit;

                } else {
                    echo json_encode(array('data' => array(), 'message' => $this->lang->line("token_invalid_webservice"), 'success' => false));
                    exit;
                }

            } else {
                echo json_encode(array('data' => array(), 'message' => $this->lang->line("input_data_webservice"), 'success' => false));
                exit;
            }

        } else {
            echo json_encode(array('data' => array(), 'message' => $this->lang->line("input_data_webservice"), 'success' => false));
            exit;
        }

    }

    /* ADD user selected tab in Db */

    public function insertUserSelectedTab() {
        $authValidValue = $this->config->item("validValue");
        if ($authValidValue != '') {
//            $json = '{
//            "category":"14|17|19","user_id":"1","web_language":"english/spanish/portuguese","article_language":"1/3/2"
//            }';

            $json = trim(file_get_contents('php://input'));

            if (!empty($json)) {
                $json = json_decode($json, true);

                $userObj = $this->load->model("webservices/users_model");
                $userObj = new users_model();

                $userObj->set_category($json['category']);
                $userObj->set_website_language($json['web_language']);
                $userObj->set_id($json['user_id']);
                $userObj->set_article_language($json['article_language']);

                //$userObj->set_token($json['token']);
                $validValue = $userObj->insertUserSelectedTabInfo();
                if ($validValue == TRUE) {
                    echo json_encode(array('data' => array(), 'message' => $this->lang->line("token_valid_webservice"), 'success' => true));
                    exit;
                } else {
                    echo json_encode(array('data' => array(), 'message' => $this->lang->line("token_invalid_webservice"), 'success' => false));
                    exit;
                }
                //echo "<pre>";print_r($result);die;
            } else {
                echo json_encode(array('data' => array(), 'message' => $this->lang->line("input_data_webservice"), 'success' => false));
                exit;
            }
        } else {
            echo json_encode(array('data' => array(), 'message' => $this->lang->line("input_data_webservice"), 'success' => false));
            exit;
        }
    }

    /* GET USER SELECTED TAB INFORMATION */

    public function getUserSelectedTab() {
        $authValidValue = $this->config->item("validValue");
        if ($authValidValue != '') {
//            $json = '{"user_id":"1" }';
            $json = trim(file_get_contents('php://input'));

            if (!empty($json)) {
                $json = json_decode($json, true);

                $userObj = $this->load->model("webservices/users_model");
                $userObj = new users_model();
                $userObj->set_id($json['user_id']);

                //$userObj->set_token($json['token']);
                $user_tab = $userObj->getUserSelectedTabInfo();
                if ($user_tab != "") {
                    echo json_encode(array('data' => $user_tab, 'message' => $this->lang->line("Sucess"), 'success' => true));
                    exit;
                } else {
                    echo json_encode(array('data' => array(), 'message' => $this->lang->line("Sucess"), 'success' => false));
                    exit;
                }
                //echo "<pre>";print_r($result);die;
            } else {
                echo json_encode(array('data' => array(), 'message' => $this->lang->line("input_data_webservice"), 'success' => false));
                exit;
            }
        } else {
            echo json_encode(array('data' => array(), 'message' => $this->lang->line("input_data_webservice"), 'success' => false));
            exit;
        }
    }

    public function addAccount() {

        $authValidValue = $this->config->item("validValue");
       // $authValidValue="ghdgf";
        if ($authValidValue != '') {
//            $json = '{
//                "token": "1",
//                "email": "anshul@techaheadcorp.com",
//                "socialAccountType": "fb",
//                "socialAccountId": "sdsdssdsdsdsdsdsdssdds65476467dsdsdsd",
//                "socialAccountToken": "1",
//                "appLanguage":"en"
//            }';

            $json = trim(file_get_contents('php://input'));
            //echo $json;die;
            if (!empty($json)) {
                $json = json_decode($json, true);
                if ($json['appLanguage'] == "en") {
                    $this->lang->load('en');
                } elseif ($json['appLanguage'] == "pt") {
                    $this->lang->load('po');
                } elseif ($json['appLanguage'] == "es") {
                    $this->lang->load('sp');
                }
                $userObj = $this->load->model("webservices/users_model");
                $userObj = new users_model();
                $userObj->set_token($json['token']);
                $userObj->set_email($json['email']);
                $userObj->set_socialAccountToken($json['socialAccountToken']);
                if ($json['socialAccountType'] == "fb") {
                    $userObj->set_facebook_id($json['socialAccountId']);
                    $userObj->set_registration_type("1");
                } elseif ($json['socialAccountType'] == "tw") {
                    $userObj->set_twitter_id($json['socialAccountId']);
                    $userObj->set_registration_type("3");
                } elseif ($json['socialAccountType'] == "in") {
                    $userObj->set_linkedin_id($json['socialAccountId']);
                    $userObj->set_registration_type("2");
                } elseif ($json['socialAccountType'] == "gPlus") {
                    $userObj->set_google_id($json['socialAccountId']);
                    $userObj->set_registration_type("4");
                }
                $userObj->set_socialAccountToken($json['socialAccountToken']);
                $result = $userObj->addAnotherAccountOfUser();
                //print_r($result);die;
                if($result!=FALSE){
                $response['is_email'] = ($result['email'] != '' ? TRUE : '');
                $response['is_facebook'] = ($result['facebook_id'] != '' ? TRUE : FALSE);
                $response['is_twitter'] = ($result['twitter_id'] != '' ? TRUE : FALSE);
                $response['is_google'] = ($result['google_id'] != '' ? TRUE : FALSE);
                $response['is_linkedin'] = ($result['linkedin_id'] != '' ? TRUE : FALSE);
                echo json_encode(array('id' => '0','data'=>$response, 'message' => $this->lang->line("account_added"), 'success' => true));
                exit;
                }else{
                echo json_encode(array('id' => '1', 'message' => $this->lang->line("account_merge_request"), 'success' => true));
                exit;
                }
            } else {
                echo json_encode(array('data' => array(), 'message' => $this->lang->line("input_data_webservice"), 'success' => false));
                exit;
            }
        } else {
            echo json_encode(array('data' => array(), 'message' => $this->lang->line("input_data_webservice"), 'success' => false));
            exit;
        }
    }
    
    
    public function mergeAccounts(){
        
        $authValidValue = $this->config->item("validValue");
        if ($authValidValue != '') {
//            $json = '{
//                "token": "1",
//                "email": "",
//                "socialAccountType": "fb",
//                "socialAccountId": "14316094071664913434",
//                "appLanguage":"en"
//            }';

            $json = trim(file_get_contents('php://input'));
            //echo $json;die;
            if (!empty($json)) {
                $json = json_decode($json, true);
                if ($json['appLanguage'] == "en") {
                    $this->lang->load('en');
                } elseif ($json['appLanguage'] == "pt") {
                    $this->lang->load('po');
                } elseif ($json['appLanguage'] == "es") {
                    $this->lang->load('sp');
                }
                $userObj = $this->load->model("webservices/users_model");
                $userObj = new users_model();
                $userObj->set_token($json['token']);
                if ($json['socialAccountType'] == "fb") {
                    $userObj->set_facebook_id($json['socialAccountId']);
                    $userObj->set_registration_type("1");
                } elseif ($json['socialAccountType'] == "tw") {
                    $userObj->set_twitter_id($json['socialAccountId']);
                    $userObj->set_registration_type("3");
                } elseif ($json['socialAccountType'] == "in") {
                    $userObj->set_linkedin_id($json['socialAccountId']);
                    $userObj->set_registration_type("2");
                } elseif ($json['socialAccountType'] == "gPlus") {
                    $userObj->set_google_id($json['socialAccountId']);
                    $userObj->set_registration_type("4");
                }
                $result = $userObj->mergeAccountOfUser();
                if($result!=''){
                $response['is_email'] = ($result['email'] != '' ? TRUE : '');
                $response['is_facebook'] = ($result['facebook_id'] != '' ? TRUE : FALSE);
                $response['is_twitter'] = ($result['twitter_id'] != '' ? TRUE : FALSE);
                $response['is_google'] = ($result['google_id'] != '' ? TRUE : FALSE);
                $response['is_linkedin'] = ($result['linkedin_id'] != '' ? TRUE : FALSE);
                echo json_encode(array('data' => $response, 'message' => $this->lang->line("account_added"), 'success' => true));
                exit;
                }
            } else {
                echo json_encode(array('data' => array(), 'message' => $this->lang->line("input_data_webservice"), 'success' => false));
                exit;
            }
        } else {
            echo json_encode(array('data' => array(), 'message' => $this->lang->line("input_data_webservice"), 'success' => false));
            exit;
        }
        
    }
    
    public function getAccounts(){
        
        $authValidValue = $this->config->item("validValue");
        if ($authValidValue != '') {
//            $json = '{
//                "token": "1",
//                "appLanguage":"en"
//            }';

            $json = trim(file_get_contents('php://input'));
            //echo $json;die;
            if (!empty($json)) {
                $json = json_decode($json, true);
                if ($json['appLanguage'] == "en") {
                    $this->lang->load('en');
                } elseif ($json['appLanguage'] == "pt") {
                    $this->lang->load('po');
                } elseif ($json['appLanguage'] == "es") {
                    $this->lang->load('sp');
                }
                $userObj = $this->load->model("webservices/users_model");
                $userObj = new users_model();
                $userObj->set_token($json['token']);
                
                $result = $userObj->getUserAccountDetails();
                if($result!=''){
                $response['is_email'] = ($result['email'] != '' ? TRUE : '');
                $response['is_facebook'] = ($result['facebook_id'] != '' ? TRUE : FALSE);
                $response['is_twitter'] = ($result['twitter_id'] != '' ? TRUE : FALSE);
                $response['is_google'] = ($result['google_id'] != '' ? TRUE : FALSE);
                $response['is_linkedin'] = ($result['linkedin_id'] != '' ? TRUE : FALSE);
                echo json_encode(array('data' => $response, 'message' => '', 'success' => true));
                exit;
                }else{
                echo json_encode(array('data' => array(), 'message' => $this->lang->line("input_data_webservice"), 'success' => false));
                exit;
                }
            } else {
                echo json_encode(array('data' => array(), 'message' => $this->lang->line("input_data_webservice"), 'success' => false));
                exit;
            }
        } else {
            echo json_encode(array('data' => array(), 'message' => $this->lang->line("input_data_webservice"), 'success' => false));
            exit;
        }
        
    }
    

}
