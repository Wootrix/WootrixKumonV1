<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class users_model extends CI_Model {

    private $_tableName = 'tbl_users';
    private $_id = "";
    private $_type = "";
    private $_email = "";
    private $_location = "";
    private $_designation = "";
    private $_created_on = "";
    private $_name = "";
    private $_linkedin_id = "";
    private $_facebook_id = "";
    private $_google_id = "";
    private $_twitter_id = "";
    private $_registration_type = "";
    private $_modified_on = "";
    private $_modified_by = "";
    private $_gender = "";
    private $_image = "";
    private $_device_token = "";
    private $_device_type = "";
    private $_latitude = "";
    private $_longitude = "";
    private $_photoUrl = "";
    private $_password = "";
    private $_osVersion = "";
    private $_socialAccountToken = "";
    private $_requireEmail = "";
    private $_requirePassword = "";
    private $_token = "";
    private $_token_expiry_date = "";
    private $_old_email = "";
    private $_new_email = "";
    private $_old_password = "";
    private $_new_password = "";
    private $_check_website = "";
    private $_category = "";
    private $_website_language = "";
    private $_website_language_id = "";
    private $_article_language = "";
    private $_deviceId = "";
    private $_userId = "";

    public function get_check_website() {
        return $this->_check_website;
    }

    public function set_check_website($_check_website) {
        $this->_check_website = $_check_website;
    }

    public function get_old_password() {
        return $this->_old_password;
    }

    public function get_new_password() {
        return $this->_new_password;
    }

    public function set_old_password($_old_password) {
        $this->_old_password = $_old_password;
    }

    public function set_new_password($_new_password) {
        $this->_new_password = $_new_password;
    }

    public function get_old_email() {
        return $this->_old_email;
    }

    public function get_new_email() {
        return $this->_new_email;
    }

    public function set_old_email($_old_email) {
        $this->_old_email = $_old_email;
    }

    public function set_new_email($_new_email) {
        $this->_new_email = $_new_email;
    }

    public function get_token() {
        return $this->_token;
    }

    public function get_token_expiry_date() {
        return $this->_token_expiry_date;
    }

    public function set_token($_token) {
        $this->_token = $_token;
    }

    public function set_token_expiry_date($_token_expiry_date) {
        $this->_token_expiry_date = $_token_expiry_date;
    }

    public function get_requireEmail() {
        return $this->_requireEmail;
    }

    public function get_requirePassword() {
        return $this->_requirePassword;
    }

    public function set_requireEmail($_requireEmail) {
        $this->_requireEmail = $_requireEmail;
    }

    public function set_requirePassword($_requirePassword) {
        $this->_requirePassword = $_requirePassword;
    }

    public function get_socialAccountToken() {
        return $this->_socialAccountToken;
    }

    public function set_socialAccountToken($_socialAccountToken) {
        $this->_socialAccountToken = $_socialAccountToken;
    }

    public function get_osVersion() {
        return $this->_osVersion;
    }

    public function set_osVersion($_osVersion) {
        $this->_osVersion = $_osVersion;
    }

    public function get_password() {
        return $this->_password;
    }

    public function set_password($_password) {
        $this->_password = $_password;
    }

    public function get_photoUrl() {
        return $this->_photoUrl;
    }

    public function set_photoUrl($_photoUrl) {
        $this->_photoUrl = $_photoUrl;
    }

    public function get_name() {
        return $this->_name;
    }

    public function set_name($_name) {
        $this->_name = $_name;
    }

    public function get_tableName() {
        return $this->_tableName;
    }

    public function get_id() {
        return $this->_id;
    }

    public function get_type() {
        return $this->_type;
    }

    public function get_email() {
        return $this->_email;
    }

    public function get_location() {
        return $this->_location;
    }

    public function get_designation() {
        return $this->_designation;
    }

    public function get_created_on() {
        return $this->_created_on;
    }

    public function get_linkedin_id() {
        return $this->_linkedin_id;
    }

    public function get_facebook_id() {
        return $this->_facebook_id;
    }

    public function get_google_id() {
        return $this->_google_id;
    }

    public function get_twitter_id() {
        return $this->_twitter_id;
    }

    public function get_registration_type() {
        return $this->_registration_type;
    }

    public function get_modified_on() {
        return $this->_modified_on;
    }

    public function get_modified_by() {
        return $this->_modified_by;
    }

    public function get_gender() {
        return $this->_gender;
    }

    public function get_image() {
        return $this->_image;
    }

    public function get_device_token() {
        return $this->_device_token;
    }

    public function get_device_type() {
        return $this->_device_type;
    }

    public function get_latitude() {
        return $this->_latitude;
    }

    public function get_longitude() {
        return $this->_longitude;
    }

    public function set_tableName($_tableName) {
        $this->_tableName = $_tableName;
    }

    public function set_id($_id) {
        $this->_id = $_id;
    }

    public function set_type($_type) {
        $this->_type = $_type;
    }

    public function set_email($_email) {
        $this->_email = $_email;
    }

    public function set_location($_location) {
        $this->_location = $_location;
    }

    public function set_designation($_designation) {
        $this->_designation = $_designation;
    }

    public function set_created_on($_created_on) {
        $this->_created_on = $_created_on;
    }

    public function set_linkedin_id($_linkedin_id) {
        $this->_linkedin_id = $_linkedin_id;
    }

    public function set_facebook_id($_facebook_id) {
        $this->_facebook_id = $_facebook_id;
    }

    public function set_google_id($_google_id) {
        $this->_google_id = $_google_id;
    }

    public function set_twitter_id($_twitter_id) {
        $this->_twitter_id = $_twitter_id;
    }

    public function set_registration_type($_registration_type) {
        $this->_registration_type = $_registration_type;
    }

    public function set_modified_on($_modified_on) {
        $this->_modified_on = $_modified_on;
    }

    public function set_modified_by($_modified_by) {
        $this->_modified_by = $_modified_by;
    }

    public function set_gender($_gender) {
        $this->_gender = $_gender;
    }

    public function set_image($_image) {
        $this->_image = $_image;
    }

    public function set_device_token($_device_token) {
        $this->_device_token = $_device_token;
    }

    public function set_device_type($_device_type) {
        $this->_device_type = $_device_type;
    }

    public function set_latitude($_latitude) {
        $this->_latitude = $_latitude;
    }

    public function set_longitude($_longitude) {
        $this->_longitude = $_longitude;
    }

    public function get_category() {
        return $this->_category;
    }

    public function set_category($_category) {
        $this->_category = $_category;
    }

    public function get_website_language() {
        return $this->_website_language;
    }

    public function set_website_language($_website_language) {
        $this->_website_language = $_website_language;
    }

    public function get_website_language_id() {
        return $this->_website_language_id;
    }

    public function set_website_language_id($_website_language_id) {
        $this->_website_language_id = $_website_language_id;
    }

    public function get_article_language() {
        return $this->_article_language;
    }

    public function set_article_language($_article_language) {
        $this->_article_language = $_article_language;
    }
    
    public function set_UserId($_userId) {
        $this->_userId = $_userId;
    }
    
    function get_user_id() {
        return $this->_userId;
    }
    
    public function set_DeviceId($_deviceId) {
        $this->_deviceId = $_deviceId;
    }
    
    function get_device_id() {
        return $this->_deviceId;
    }
    
    public function signupAndLoginInWootrix() {
        $data = array();
        $data['email'] = $this->get_email();
        $data['name'] = $this->get_name();
        $data['photoUrl'] = $this->get_photoUrl();
        if ($this->get_password() != '') {
            $data['password'] = md5($this->get_password());
        }
        $data['device_type'] = $this->get_device_type();
        $data['latitude'] = $this->get_latitude();		
        $data['longitude'] = $this->get_longitude();		
        $data['deviceId'] = $this->get_device_id();
        $data['osVersion'] = $this->get_osVersion();
        $data['registration_type'] = $this->get_registration_type();
        if ($this->get_registration_type() == "1") {
            $data['facebook_id'] = $this->get_facebook_id();
        } elseif ($this->get_registration_type() == "2") {
            $data['linkedin_id'] = $this->get_linkedin_id();
        } elseif ($this->get_registration_type() == "3") {
            $data['twitter_id'] = $this->get_twitter_id();
        } elseif ($this->get_registration_type() == "4") {
            $data['google_id'] = $this->get_google_id();
        }
        $data['socialAccountToken'] = $this->get_socialAccountToken();
        foreach ($data as $key => $value) {
            if (($value == '')) {
                unset($data[$key]);
            }
        }
        //echo $this->get_email();die;
        if ($this->get_email() != '') { 
            $this->set_email($data['email']);
            $getEmailCheck = $this->checkEmailExists();
            
            if ($getEmailCheck != FALSE) {
                if($this->get_registration_type()!=''){
                    $queryCheckSocialIdNew = $this->db->select("*")
                        ->from("tbl_users")
                        ->where("facebook_id", $data['facebook_id'])
                        ->or_where("linkedin_id", $data['linkedin_id'])
                        ->or_where("twitter_id", $data['twitter_id'])
                        ->or_where("google_id", $data['google_id'])
                        ->get();
                    
                 if ($queryCheckSocialIdNew->num_rows() > 0) {
                    $rowCheckSocialNew = $queryCheckSocialIdNew->row_array();
                    $this->set_id($rowCheckSocialNew['id']);
                    $this->db->set("modified_on", "NOW()", false)
                            ->set("token_last_used", "NOW()", false)
                            ->where("id", $rowCheckSocialNew['id'])
                            ->update("tbl_users");
                    $this->set_token($rowCheckSocialNew['token']);
                    $this->set_token_expiry_date($rowCheckSocialNew['token_expiry_date']);
                    $queryCheckEmailNew = $this->db->select("email,password")
                            ->from("tbl_users")
                            ->where("id", $rowCheckSocialNew['id'])
                            ->get();
                    $rowEmailPassNew = $queryCheckEmailNew->row_array();
                    if ($rowEmailPassNew['email'] != '') {
                        $this->set_requireEmail("N");
                    } else {
                        $this->set_requireEmail("Y");
                    }

                    if ($rowEmailPassNew['password'] != '') {
                        $this->set_requirePassword("N");
                    } else {
                        $this->set_requirePassword("Y");
                    }
                    $this->set_email($rowCheckSocialNew['email']);
                    $this->set_facebook_id($rowCheckSocialNew['facebook_id']);
                    $this->set_twitter_id($rowCheckSocialNew['twitter_id']);
                    $this->set_google_id($rowCheckSocialNew['google_id']);
                    $this->set_linkedin_id($rowCheckSocialNew['linkedin_id']);
                    return TRUE;
                }   
                    
                }
                
                
                
                $this->set_id($getEmailCheck['id']);
                
                
                
                $queryUpdate = $this->db->set($data)
                        ->set("modified_on", "NOW()", false)
                        ->set("token_last_used", "NOW()", false)
                        ->where("id", $getEmailCheck['id'])
                        ->update("tbl_users");
                $queryGetPass = $this->db->select("password")
                        ->from("tbl_users")
                        ->where("id", $getEmailCheck['id'])
                        ->get();

                $rowGetPass = $queryGetPass->row_array();
                if ($rowGetPass['password'] != '') {
                    $passBlank = "N";
                } else {
                    $passBlank = "Y";
                }
                $this->set_token($getEmailCheck['token']);
                $this->set_token_expiry_date($getEmailCheck['token_expiry_date']);
                $this->set_requireEmail("N");
                $this->set_requirePassword($passBlank);
                $this->set_email($getEmailCheck['email']);
                $this->set_facebook_id($getEmailCheck['facebook_id']);
                $this->set_twitter_id($getEmailCheck['twitter_id']);
                $this->set_google_id($getEmailCheck['google_id']);
                $this->set_linkedin_id($getEmailCheck['linkedin_id']);
                return TRUE;
            } else {
                $queryCheckSocialId = $this->db->select("*")
                        ->from("tbl_users")
                        ->where("facebook_id", $data['facebook_id'])
                        ->or_where("linkedin_id", $data['linkedin_id'])
                        ->or_where("twitter_id", $data['twitter_id'])
                        ->or_where("google_id", $data['google_id'])
                        ->get();
                if ($queryCheckSocialId->num_rows() > 0) {
                    $rowCheckSocial = $queryCheckSocialId->row_array();
                    $this->set_id($rowCheckSocial['id']);
                    $querySocialUpdate = $this->db->set($data)
                            ->set("modified_on", "NOW()", false)
                            ->set("token_last_used", "NOW()", false)
                            ->where("id", $rowCheckSocial['id'])
                            ->update("tbl_users");
                    $this->set_token($rowCheckSocial['token']);
                    $this->set_token_expiry_date($rowCheckSocial['token_expiry_date']);
                    $queryCheckEmail = $this->db->select("email,password")
                            ->from("tbl_users")
                            ->where("id", $rowCheckSocial['id'])
                            ->get();
                    $rowEmailPass = $queryCheckEmail->row_array();
                    if ($rowEmailPass['email'] != '') {
                        $this->set_requireEmail("N");
                    } else {
                        $this->set_requireEmail("Y");
                    }

                    if ($rowEmailPass['password'] != '') {
                        $this->set_requirePassword("N");
                    } else {
                        $this->set_requirePassword("Y");
                    }
                    $this->set_email($rowCheckSocial['email']);
                    $this->set_facebook_id($rowCheckSocial['facebook_id']);
                    $this->set_twitter_id($rowCheckSocial['twitter_id']);
                    $this->set_google_id($rowCheckSocial['google_id']);
                    $this->set_linkedin_id($rowCheckSocial['linkedin_id']);
                    return TRUE;
                } else {
                    $querySocialInsert = $this->db->set($data)
                            ->set("created_on", "NOW()", false)
                            ->set("token_expiry_date", "DATE_ADD(now(),INTERVAL 2 WEEK)", false)
                            ->insert("tbl_users");
                    $last_id = $this->db->insert_id();
                    $this->set_id($last_id);
                    $queryTokenUpdate = $this->db->set("token", $last_id)
                            ->where("id", $last_id)
                            ->update("tbl_users");
                    $queryCheckEmailPass = $this->db->select("*")
                            ->from("tbl_users")
                            ->where("id", $last_id)
                            ->get();
                    $rowEmailPassCheck = $queryCheckEmailPass->row_array();
                    $this->set_token($rowEmailPassCheck['token']);
                    $this->set_token_expiry_date($rowEmailPassCheck['token_expiry_date']);

                    if ($rowEmailPassCheck['email'] != '') {
                        $this->set_requireEmail("N");
                    } else {
                        $this->set_requireEmail("Y");
                    }

                    if ($rowEmailPassCheck['password'] != '') {
                        $this->set_requirePassword("N");
                    } else {
                        $this->set_requirePassword("Y");
                    }
                    $this->set_email($rowEmailPassCheck['email']);
                    $this->set_facebook_id($rowEmailPassCheck['facebook_id']);
                    $this->set_twitter_id($rowEmailPassCheck['twitter_id']);
                    $this->set_google_id($rowEmailPassCheck['google_id']);
                    $this->set_linkedin_id($rowEmailPassCheck['linkedin_id']);

                    return TRUE;
                }
            }
        } else {
            $queryCheckSocialId = $this->db->select("*")
                    ->from("tbl_users")
                    ->where("facebook_id", $data['facebook_id'])
                    ->or_where("linkedin_id", $data['linkedin_id'])
                    ->or_where("twitter_id", $data['twitter_id'])
                    ->or_where("google_id", $data['google_id'])
                    ->get();
            //echo $this->db->last_query();die;
            if ($queryCheckSocialId->num_rows() > 0) {
                $rowCheckSocial = $queryCheckSocialId->row_array();
                $this->set_id($rowCheckSocial['id']);
                $querySocialUpdate = $this->db->set($data)
                        ->set("modified_on", "NOW()", false)
                        ->set("token_last_used", "NOW()", false)
                        ->where("id", $rowCheckSocial['id'])
                        ->update("tbl_users");
                $this->set_token($rowCheckSocial['token']);
                $this->set_token_expiry_date($rowCheckSocial['token_expiry_date']);
                $queryCheckEmail = $this->db->select("email,password")
                        ->from("tbl_users")
                        ->where("id", $rowCheckSocial['id'])
                        ->get();
                $rowEmailPass = $queryCheckEmail->row_array();
                if ($rowEmailPass['email'] != '') {
                    $this->set_requireEmail("N");
                } else {
                    $this->set_requireEmail("Y");
                }

                if ($rowEmailPass['password'] != '') {
                    $this->set_requirePassword("N");
                } else {
                    $this->set_requirePassword("Y");
                }
                $this->set_email($rowCheckSocial['email']);
                $this->set_facebook_id($rowCheckSocial['facebook_id']);
                $this->set_twitter_id($rowCheckSocial['twitter_id']);
                $this->set_google_id($rowCheckSocial['google_id']);
                $this->set_linkedin_id($rowCheckSocial['linkedin_id']);
                return TRUE;
            } else {
                $querySocialInsert = $this->db->set($data)
                        ->set("created_on", "NOW()", false)
                        ->set("token_expiry_date", "DATE_ADD(now(),INTERVAL 2 WEEK)", false)
                        ->insert("tbl_users");
                //echo $this->db->last_query();
                $last_id = $this->db->insert_id();
                $this->set_id($last_id);
                $queryTokenUpdate = $this->db->set("token", $last_id)
                        ->where("id", $last_id)
                        ->update("tbl_users");
                //echo $this->db->last_query();die;
                $queryCheckEmailPass = $this->db->select("*")
                        ->from("tbl_users")
                        ->where("id", $last_id)
                        ->get();
                $rowEmailPassCheck = $queryCheckEmailPass->row_array();

                $this->set_token($rowEmailPassCheck['token']);
                $this->set_token_expiry_date($rowEmailPassCheck['token_expiry_date']);
                if ($rowEmailPassCheck['email'] != '') {
                    $this->set_requireEmail("N");
                } else {
                    $this->set_requireEmail("Y");
                }

                if ($rowEmailPassCheck['password'] != '') {
                    $this->set_requirePassword("N");
                } else {
                    $this->set_requirePassword("Y");
                }
                $this->set_email($rowEmailPassCheck['email']);
                $this->set_facebook_id($rowEmailPassCheck['facebook_id']);
                $this->set_twitter_id($rowEmailPassCheck['twitter_id']);
                $this->set_google_id($rowEmailPassCheck['google_id']);
                $this->set_linkedin_id($rowEmailPassCheck['linkedin_id']);
                return TRUE;
            }
        }
    }

    public function checkEmailExists() {
        $query = $this->db->select("*")
                ->from("tbl_users")
                ->where("email", $this->get_email())
                ->get();
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row;
        } else {
            return FALSE;
        }
    }

    public function signupInWootrix($group, $country, $state, $city, $disciplines, $branch) {

        $this->set_email($this->get_email());
        $checkEmail = $this->checkEmailExists();

        if ($checkEmail != FALSE) {

            if ($this->get_check_website() != "Website") {
                echo json_encode(array('data' => array(), 'message' => $this->lang->line("email_id_exists_web"), 'success' => false));
                exit;
            } else {
                return FALSE;
            }

        } else {

            $data = array();

            $categories = $this->getAllcategories();

            $data['email'] = $this->get_email();
            $data['name'] = $this->get_name();
            $data['location'] = $country;
            $data['password'] = md5($this->get_password());
            $data['category'] = $categories;
            $data['article_language']='1,2,3';
            $data['website_language']= $this->get_website_language();
            $data['website_language_id']= $this->get_website_language_id();
            $data["branch"] = $branch;

            $this->db->set($data)
                    ->set("token_expiry_date", "NOW()", false)
                    ->set("created_on", "NOW()", false)
                    ->insert("tbl_users");

            $last_id = $this->db->insert_id();

            $this->db->set("token", $last_id)
                    ->where("id", $last_id)
                    ->update("tbl_users");

            $queryMagazines = $this->db->select("*")
                ->from("tbl_magazine")
                ->get();

            if ($queryMagazines->num_rows() > 0) {

                $resultMagazine = $queryMagazines->result_array();

                foreach( $resultMagazine as $magazine ){

                    $this->db->set("user_id", $last_id)
                        ->set("magazine_id", $magazine["id"])
                        ->set("created_on","NOW()",false)
                        ->insert("tbl_user_magazines");

                }

            }

//            echo "grupo " . $group; exit;

            $sqlGroup = $this->db->query("select id FROM tbl_group WHERE short_name = '$group'");
            $rowGroup = $sqlGroup->row_array();

            $groupId = $rowGroup["id"];

//            var_dump($last_id);
//            var_dump($groupId); exit;

            $this->db->query("INSERT INTO tbl_user_group (id_user, id_group) VALUES( $last_id, $groupId );");

            $sqlLocation = $this->db->query("select id FROM tbl_user_location WHERE city = '$city'");
            $rowLocation = $sqlLocation->row_array();

            if( !isset( $rowLocation["id"] ) ){
                $this->db->query("INSERT INTO tbl_user_location (country, state, city) VALUES( '$country', '$state', '$city' );");
                $locationId = $this->db->insert_id();
            } else {
                $locationId = $rowLocation["id"];
            }

            $this->db->set("user_location_id", $locationId)
                ->where("id", $last_id)
                ->update("tbl_users");

            if( count( $disciplines ) > 0 ){

                foreach($disciplines as $discipline){
                    $sqlDiscipline = $this->db->query("select id FROM tbl_discipline WHERE short_name = '$discipline'");
                    $rowDiscipline = $sqlDiscipline->row_array();
                    $idDiscipline = $rowDiscipline["id"];
                    $this->db->query("INSERT INTO tbl_user_discipline (user_id, discipline_id) VALUES( $last_id, '$idDiscipline' );");
                }

            }

            return TRUE;

        }

    }

    public function checkLoginCredentials() {
        $query = $this->db->select("*")
                ->from("tbl_users")
                ->where("email", $this->get_email())
                ->where("password", md5($this->get_password()))
                ->get();
        if ($query->num_rows() > 0) {

            $row = $query->row_array();
            return $row;
        } else {

            echo json_encode(array('data' => array(), 'message' => $this->lang->line("email_password_incorrect_webservice"), 'success' => false));
            exit;
        }
    }
    
    public function storeDeviceLocation(){
        
        $indata = array();
        $indata['latitude'] = $this->get_latitude();
        $indata['longitude'] = $this->get_longitude();
        $deviceType = $this->get_device_type();
        
        if($deviceType == "1")
        {
            $query = $this->db->select("deviceId_ios")
                ->from("tbl_users")
                ->where("id", $this->get_user_id())
                ->get();
            if ($query->num_rows() > 0) {
            $row = $query->row_array();
            }
            $expdevid = explode(",",$row["deviceId_ios"]);
        }
        else if($deviceType == "2")
        {
            $query = $this->db->select("deviceId_android")
                ->from("tbl_users")
                ->where("id", $this->get_user_id())
                ->get();
            if ($query->num_rows() > 0) {
            $row = $query->row_array();
            }
            $expdevid = explode(",",$row["deviceId_android"]);
        }
        
        if($row["deviceId_ios"] != '' || $row["deviceId_android"] != '') {
            if(!in_array($this->get_device_id(), $expdevid))
            {
                if($deviceType == "1")
                {
                    $indata['deviceId_ios'] = $row["deviceId_ios"].",".$this->get_device_id();
                }
                else if($deviceType == "2")
                {
                    $indata['deviceId_android'] = $row["deviceId_android"].",".$this->get_device_id();
                }
            }
        }
        else
        {
            if($deviceType == "1")
            {
                $indata['deviceId_ios'] = $this->get_device_id();
            }
            else if($deviceType == "2")
            {
                $indata['deviceId_android'] = $this->get_device_id();
            }
        }
        $geolocation = $indata['latitude'].','.$indata['longitude'];
        $request = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.$geolocation.'&sensor=false'; 
        $file_contents = file_get_contents($request);
        $json_decode = json_decode($file_contents);
        if(isset($json_decode->results[0])) {
            $response = array();
            foreach($json_decode->results[0]->address_components as $addressComponet) {
                if(in_array('political', $addressComponet->types) && (in_array('administrative_area_level_1', $addressComponet->types) || in_array('locality', $addressComponet->types) || in_array('country', $addressComponet->types))) {
                        $response[] = $addressComponet->long_name;                         
                }
            }
            $location = $response[0].", ".$response[1].", ".$response[2];
        } 

        $query = $this->db->set($indata)->where('id',$this->get_user_id())->update('tbl_users',$data);
        //echo $this->db->last_query();die;
        return TRUE;
    }

    public function storeUserLocation(){

        $indata = array();
        $indata['latitude'] = $this->get_latitude();
        $indata['longitude'] = $this->get_longitude();

        $geolocation = $indata['latitude'].','.$indata['longitude'];
        $request = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.$geolocation.'&sensor=false';
        $file_contents = file_get_contents($request);
        $json_decode = json_decode($file_contents);
        if(isset($json_decode->results[0])) {
            $response = array();
            foreach($json_decode->results[0]->address_components as $addressComponet) {
                if(in_array('political', $addressComponet->types) && (in_array('administrative_area_level_1', $addressComponet->types) || in_array('locality', $addressComponet->types) || in_array('country', $addressComponet->types))) {
                    $response[] = $addressComponet->long_name;
                }
            }
            $location = $response[0].", ".$response[1].", ".$response[2];
        }
        $indata['location'] = $location;

        $query = $this->db->set($indata)->where('id',$this->get_user_id())->update('tbl_users',$data);
        //echo $this->db->last_query();die;
        return TRUE;
    }

    public function storeUserDeviceID()
    {
        $ttindata = array();
        $deviceID = $this->get_device_id();
        $userID = $this->get_user_id();
        $deviceType = $this->get_device_type();

        $query = $this->db->select("id")
            ->from("tbl_users")
            ->where("token", $userID)
            ->where("token_expiry_date >=", "NOW()", false)
            ->get();
        $rowUser = $query->row_array();
        $userID = $rowUser['id'];

        if($deviceType == "1")
        {
            $query = $this->db->select("deviceId_ios")
                ->from("tbl_users")
                ->where("id", $userID)
                ->get();
            if ($query->num_rows() > 0) {
                $row = $query->row_array();
            }
            $expdevid = explode(",",$row["deviceId_ios"]);
        }
        else if($deviceType == "2")
        {
            $query = $this->db->select("deviceId_android")
                ->from("tbl_users")
                ->where("id", $userID)
                ->get();
            if ($query->num_rows() > 0) {
                $row = $query->row_array();
            }
            $expdevid = explode(",",$row["deviceId_android"]);
        }
        if($row["deviceId_ios"] != '' || $row["deviceId_android"] != '') {
            if(!in_array($deviceID, $expdevid))
            {
                if($deviceType == "1")
                {
                    $ttindata['deviceId_ios'] = $row["deviceId_ios"].",".$deviceID;
                }
                else if($deviceType == "2")
                {
                    $ttindata['deviceId_android'] = $row["deviceId_android"].",".$deviceID;
                }
            }
        }
        else
        {
            if($deviceType == "1")
            {
                $ttindata['deviceId_ios'] = $deviceID;
            }
            else if($deviceType == "2")
            {
                $ttindata['deviceId_android'] = $deviceID;
            }
        }
        if($ttindata['deviceId_ios'] != "" || $ttindata["deviceId_android"] != '') {
            $query = $this->db->set($ttindata)->where('id',$userID)->update('tbl_users'); }
        //echo $this->db->last_query();die;
        return TRUE;
    }

    public function changePassword() {

        $queryCheckEmail = $this->db->select("id,email,name")
                ->from("tbl_users")
                ->where("email", $this->get_email())
                ->get();
				
				$this->db->where('email', $this->get_email());
				$emailid=$this->db->get('tbl_users')->row();
				
        if ($queryCheckEmail->num_rows() > 0) {
           $rowEmail = $queryCheckEmail->row_array();
            /* $nameChar = "ABCDEFGHIJKLMNOPQRSTYUVWXYZabcdefghijklmnopqrstuvwxyz";
            for ($i = 0; $i < 6; $i++) {

                $nameImg .= $nameChar[rand(0, strlen($nameChar))];
            }
            $password = md5($nameImg);
            $queryUpdate = $this->db->set("password", $password)
                    ->where("id", $rowEmail['id'])
                    ->update("tbl_users");*/
            $this->load->library('email');

            $this->email->from('wootrix@wootrix.com', 'Wootrix');
            $this->email->to($rowEmail['email']);
            $this->email->subject('Forgot Password Mail');

            $message = "";
            $message .= "Dear " . $rowEmail['name'] . ",<br><br>";
          $message .= 'Please use the link to <a href="'.$this->config->base_url().'index.php/resetpassword/'.$emailid->id.'">reset the password</a> .<br><br>';
            $message .= 'Thanks & Regards<br>';
            $message .= 'Wootrix Team<br>';

            //echo $message;die;
            $this->email->message($message);

            $this->email->send();
            if ($this->get_check_website() != '') {
                redirect('website/website_login/getResetPassword?res=sent');
            }

            echo json_encode(array('data' => array(), 'message' => $this->lang->line("password_sent_webservice"), 'success' => true));
            exit;
        } else {
            if ($this->get_check_website() != '') {
                redirect('website/website_login/getResetPassword?res=notsent');
            }
            echo json_encode(array('data' => array(), 'message' => $this->lang->line("email_not_exists_webservice"), 'success' => false));
            exit;
        }
    }

    public function verifyTokenValue() {
        $query = $this->db->select("id")
                ->from("tbl_users")
                ->where("token", $this->get_token())
                ->where("token_expiry_date >=", "NOW()", false)
                ->get();
        //secho $this->db->last_query();die;
        if ($query->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function updateUserPhoto() {
        $query = $this->db->set("photoUrl", $this->get_photoUrl())
                ->where("id", $this->get_token())
                ->update("tbl_users");

        $queryUser = $this->db->select("name,photoUrl,email")
                ->from("tbl_users")
                ->where("id", $this->get_token())
                ->get();
        $row = $queryUser->row_array();
        return $row;
    }

    public function updateNewEmailAndCheckOld() {
        $server = $this->get_check_website();
        $queryCheckOld = $this->db->select("id")
                ->from("tbl_users")
                ->where("email", $this->get_old_email())
                ->where("id", $this->get_token())
                ->get();
        if ($queryCheckOld->num_rows() > 0) {
            $queryUpdate = $this->db->set("email", $this->get_new_email())
                    ->where("id", $this->get_token())
                    ->update("tbl_users");



            $queryUser = $this->db->select("*")
                    ->from("tbl_users")
                    ->where("id", $this->get_token())
                    ->get();
            $row = $queryUser->row_array();
            return $row;
        } else {
            if ($server == 'web') {
                return FALSE;
            } else {
                echo json_encode(array('data' => array(), 'message' => $this->lang->line("old_email_wrong_webservice"), 'success' => false));
                exit;
            }
        }
    }

    public function getUpdateNewEmail() {

        $query = $this->db->select("*")
                ->from("tbl_users")
                ->where("email", $this->get_new_email())
                ->get();
        //echo $this->db->last_query();die;
        if ($query->num_rows() > 0) {
            return FALSE;
        } else {
            $queryUpdate = $this->db->set("email", $this->get_new_email())
                    ->where("id", $this->get_token())
                    ->update("tbl_users");

            $queryUser = $this->db->select("*")
                    ->from("tbl_users")
                    ->where("id", $this->get_token())
                    ->get();
            $row = $queryUser->row_array();
            return $row;
        }
    }

    public function updateNewPasswordAndCheckOld() {
        $server = $this->get_check_website();
        $queryCheckOld = $this->db->select("id")
                ->from("tbl_users")
                ->where("password", md5($this->get_old_password()))
                ->where("id", $this->get_token())
                ->get();
        if ($queryCheckOld->num_rows() > 0) {
            $queryUpdate = $this->db->set("password", md5($this->get_new_password()))
                    ->where("id", $this->get_token())
                    ->update("tbl_users");

            return TRUE;
        } else {
            if ($server == 'web') {
                return FALSE;
            } else {
                echo json_encode(array('data' => array(), 'message' => $this->lang->line("old_pass_wrong_webservice"), 'success' => false));
                exit;
            }
        }
    }

    public function getUpdateNewPassword() {
        $queryUpdate = $this->db->set("password", md5($this->get_new_password()))
                ->where("id", $this->get_token())
                ->update("tbl_users");


        return TRUE;
    }

    public function getTokenValidOrNot() {

        $date = date("Y-m-d H:i:s");

        $query = $this->db->select("id")
                ->from("tbl_users")
                ->where("token_expiry_date >=", $date)
                ->get();

        if ($query->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }

    }

    /* INSERT SELECTED TAB */

    public function insertUserSelectedTabInfo() {
        $user_id = $this->get_id();
        $category_id = $this->get_category();
        $web_lang = $this->get_website_language();
        $article_language = $this->get_article_language();
        
        if($web_lang=='english'){
        $website_language_id = '1';
        }elseif($web_lang=='portuguese'){
        $website_language_id = '2';
        }elseif($web_lang=='spanish'){
        $website_language_id = '3';
        }

        $queryUpdate = $this->db->query("UPDATE tbl_users SET category ='$category_id',website_language='$web_lang',website_language_id='$website_language_id',
                article_language ='$article_language' WHERE id = '$user_id'");
        if ($queryUpdate) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /* GET SELECTED TAB */

    public function getUserSelectedTabInfo() {
        $user_id = $this->get_id();
        $query = $this->db->select("website_language,article_language,category")
                ->from("tbl_users")
                ->where("id", $user_id)
                ->get();
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            $result = array();
            $result['website_language'] = $row['website_language'];
            $result['article_language'] = implode(",", array_filter(explode(",", $row['article_language'])));
            $result['category'] = $row['category'];

            return $result;
        } else {
            return FALSE;
        }
    }
    
     public function getUserAccountDetails(){
        
        $query=  $this->db->select()
                ->from('tbl_users')
                ->where('id',  $this->get_token())
                ->get();
        if($query->num_rows()>0){
            return $query->row_array();
        }else{
            return FALSE;
        }
        
    }
    
    public function getAllcategories(){
        
        $query=  $this->db->select('id')
                ->from('tbl_category')
                ->where('status','1')
                ->get();
        $row=$query->result_array();
        foreach ($row as $r){
            $categoryArray[]=$r['id'];
        }
        //print_r($categoryArray);die;
        return implode("|", $categoryArray);
        
    }
    
     public function addAccountWeb(){
        $query = $this->db->select("*")
                    ->from("tbl_users");
        if($this->get_facebook_id()!=''){
        $query = $this->db->where("facebook_id", $this->get_facebook_id());
        $data['facebook_id']=  $this->get_facebook_id();
        }
        if($this->get_linkedin_id()!=''){
        $query = $this->db->where("linkedin_id", $this->get_linkedin_id());
        $data['linkedin_id']=  $this->get_linkedin_id();
        }
        if($this->get_twitter_id()!=''){
        $query = $this->db->where("twitter_id", $this->get_twitter_id());
        $data['twitter_id']=  $this->get_twitter_id();
        }
        if($this->get_google_id()!=''){
        $query = $this->db->where("google_id", $this->get_google_id());
        $data['google_id']=  $this->get_google_id();
        }
        $query = $this->db->get();
        
        $row=$query->row_array();
        //echo $this->db->last_query();die;
        //echo $query->num_rows();die;
        if($query->num_rows()>0){
            
            
            
            
              $queryToAdd=  $this->db->select()
                      ->from('tbl_users')
                      ->where('id',$this->session->userdata('user_id'))
                      ->get();
            $rowQueryToAdd=$queryToAdd->row_array();
            if($rowQueryToAdd['facebook_id']==''){
            $data1['facebook_id']=$row['facebook_id'];
            }
            if($rowQueryToAdd['linkedin_id']==''){
            $data1['linkedin_id']=$row['linkedin_id'];
            }
            if($rowQueryToAdd['twitter_id']==''){
            $data1['twitter_id']=$row['twitter_id'];
            }
            if($rowQueryToAdd['google_id']==''){
            $data1['google_id']=$row['google_id'];
            }
            if($rowQueryToAdd['password']==''){
            $data1['email']=$row['email'];
            $data1['password']=$row['password'];
            }
            
            
            $this->db->set($data1)->where('id',$this->session->userdata('user_id'))->update('tbl_users');
            $this->db->set('customer_id',$this->session->userdata('user_id'))->where('customer_id',$row['id'])->update('tbl_magazine_articles');
            $this->db->set('customer_id',$this->session->userdata('user_id'))->where('customer_id',$row['id'])->update('tbl_magazine_advertisement');
            $this->db->where('id',$row['id'])->delete('tbl_users');
            
            
//            $this->db->set($data)->where('id', $this->session->userdata('user_id'))->update('tbl_users');
//            $this->db->set('customer_id',$this->session->userdata('user_id'))->where('customer_id',$row['id'])->update('tbl_magazine_articles');
//            $this->db->set('customer_id',$this->session->userdata('user_id'))->where('customer_id',$row['id'])->update('tbl_magazine_advertisement');
//            $this->db->where('id',$row['id'])->delete('tbl_users');    
//            $this->db->set($data)->where('id', $this->session->userdata('user_id'))->update('tbl_users');
            
        }else{
            
            $this->db->set($data)->where('id', $this->session->userdata('user_id'))->update('tbl_users');
            
        }
        $this->session->set_userdata('saved','1');
        //echo $this->db->last_query();die;
        redirect('wootrix-articles');
        
        
    }
    
    public function resetpost() {
        $data=array('password'=>md5($this->get_password()));
        $this->db->where('id', $this->get_id());
        return $update= $this->db->update('tbl_users',$data);
    }

    public function getUserByKumonEmail($email){

        $query  = $this->db->select("*")
            ->from("tbl_users")
            ->where("email", $email)
            ->get();

        if($query->num_rows() > 0){
            $row = $query->row_array();
            return $row;
        } else {
            return FALSE;
        }

    }

    public function getUserByToken($token){

        $user = null;

        $query = $this->db->select("token_expiry_date, email")
            ->from("tbl_users")
            ->where("token", $token)
            ->get();

        if($query->num_rows() > 0) {
            $user = $query->row_array();
        }

        return $user;

    }

    public function getArticles(){

        $sql1 = $this->db->query("select id FROM tbl_new_articles");
        $result1 = $sql1->result_array();

        $sql2 = $this->db->query("select id from tbl_user_location;");
        $result2 = $sql2->result_array();

        foreach( $result1 as $article ){

            $articleId = $article["id"];

            foreach( $result2 as $city ){
                $locationId = $city["id"];
                $this->db->query("INSERT INTO tbl_article_location (article_id, location_id) VALUES( $articleId, $locationId );");
            }

        }

        echo "done";

//        echo "<pre>";
//        print_r($result2);
//        echo "</pre>"; exit;

    }

    public function getArticlesSpecific(){

        $sql1 = $this->db->query("select id FROM tbl_new_articles");
        $result1 = $sql1->result_array();

        $sql2 = $this->db->query("select id from tbl_user_location WHERE id IN(566, 567)");
        $result2 = $sql2->result_array();

        foreach( $result1 as $article ){

            $articleId = $article["id"];

            foreach( $result2 as $city ){
                $locationId = $city["id"];
                $this->db->query("INSERT INTO tbl_article_location (article_id, location_id) VALUES( $articleId, $locationId );");
            }

        }

        echo "done";

//        echo "<pre>";
//        print_r($result2);
//        echo "</pre>"; exit;

    }

    public function setUserMagazines(){

        $sql1 = $this->db->query("select id FROM tbl_users");
        $result1 = $sql1->result_array();

        $sql2 = $this->db->query("select id from tbl_magazine");
        $result2 = $sql2->result_array();

        foreach( $result1 as $user ){

            $userId = $user["id"];

            foreach( $result2 as $magazine ){
                $magazineId = $magazine["id"];
                $this->db->query("INSERT INTO tbl_user_magazines (user_id, magazine_id) VALUES( $userId, $magazineId );");
            }

        }

        echo "done";

//        echo "<pre>";
//        print_r($result2);
//        echo "</pre>"; exit;

    }

    public function setArticleOrder(){

        $sql1 = $this->db->query("select id, magazine_id FROM tbl_new_articles WHERE FIND_IN_SET('26', magazine_id) > 0 ORDER BY publish_date desc");
        $result1 = $sql1->result_array();

        $arrayOrder = array();

        foreach( $result1 as $article ){

            $articleId = $article["id"];
            $magazines = explode(",", $article["magazine_id"]);

            foreach( $magazines as $magazine ){

                if( !isset( $arrayOrder[$magazine] ) ){
                    $arrayOrder[$magazine] = 1;
                }

                $order = $arrayOrder[$magazine];
                $this->db->query("INSERT INTO tbl_article_order (article_id, magazine_id, nr_order) VALUES( $articleId, $magazine, $order );");
                $arrayOrder[$magazine]++;

            }

        }

        echo "done";

    }

    public function updateUserBranch($branch) {

        $sql1 = $this->db->query("select id FROM tbl_new_articles");
        $result1 = $sql1->result_array();

//        echo "<pre>";
//        print_r($result1);

        foreach( $result1 as $article ){
            $this->db->query("INSERT INTO tbl_article_branch (article_id, branch) VALUES( " . $article["id"] . ", '$branch' );");
        }



//            $data = array("branch" => $branch);

//            $this->db->set($data)
//                ->set("created_on", "NOW()", false)
//                ->set("token_expiry_date", "DATE_ADD(now(),INTERVAL 2 WEEK)", false)
//                ->insert("tbl_users");

    }

}
