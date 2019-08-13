<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class contact_us_model extends CI_Model {
    
    private $_tableName = 'tbl_contact_us';
    private $_id = "";
    private $_first_name = "";
    private $_last_name = "";
    private $_email = "";
    private $_phone_no = "";
    private $_comment = "";
    private $_created_on = "";
    
    public function get_tableName() {
        return $this->_tableName;
    }

    public function get_id() {
        return $this->_id;
    }

    public function get_first_name() {
        return $this->_first_name;
    }

    public function get_last_name() {
        return $this->_last_name;
    }

    public function get_email() {
        return $this->_email;
    }

    public function get_phone_no() {
        return $this->_phone_no;
    }

    public function get_comment() {
        return $this->_comment;
    }

    public function get_created_on() {
        return $this->_created_on;
    }

    public function set_tableName($_tableName) {
        $this->_tableName = $_tableName;
    }

    public function set_id($_id) {
        $this->_id = $_id;
    }

    public function set_first_name($_first_name) {
        $this->_first_name = $_first_name;
    }

    public function set_last_name($_last_name) {
        $this->_last_name = $_last_name;
    }

    public function set_email($_email) {
        $this->_email = $_email;
    }

    public function set_phone_no($_phone_no) {
        $this->_phone_no = $_phone_no;
    }

    public function set_comment($_comment) {
        $this->_comment = $_comment;
    }

    public function set_created_on($_created_on) {
        $this->_created_on = $_created_on;
    }
    
    public function insertContactData(){
        $data = array();
        $data['first_name'] = $this->get_first_name();
        $data['last_name'] = $this->get_last_name();
        $data['email'] = $this->get_email();
        $data['phone_no'] = $this->get_phone_no();
        $data['comment'] = $this->get_comment();
        
        $this->db->set($data)->set("created_on","NOW()",false)->insert("tbl_contact_us");
        return TRUE;
    }

    
}
