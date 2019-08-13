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
    
    public function getUserLogin(){
        $query  = $this->db->select("*")
                           ->from("tbl_users")
                           ->where("email",  $this->get_email())
                           ->where("password",md5($this->get_password()))
                           ->get();
        if($query->num_rows()>0){
           $row = $query->row_array();
           return $row;
        }else{
            return FALSE;
        }
    }
    
   
    
   
    
    

    
}
