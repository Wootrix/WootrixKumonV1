<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class ads_report_model extends CI_Model {
    
     
    private $_tableName = 'tbl_ads_report';
    private $_id = "";
    private $_user_id = "";
    private $_latitude = "";
    private $_longitude = "";
    private $_article_id = "";
    private $_device_type = "";
    private $_created_date = "";
    private $_token = "";
    private $_type="";


    public function get_token() {
        return $this->_token;
    }

    public function set_token($_token) {
        $this->_token = $_token;
    }
    
    
    public function get_tableName() {
        return $this->_tableName;
    }

    public function get_id() {
        return $this->_id;
    }

    public function get_user_id() {
        return $this->_user_id;
    }

    public function get_latitude() {
        return $this->_latitude;
    }

    public function get_longitude() {
        return $this->_longitude;
    }

    public function get_article_id() {
        return $this->_article_id;
    }

    public function get_device_type() {
        return $this->_device_type;
    }

    public function get_created_date() {
        return $this->_created_date;
    }

    public function set_tableName($_tableName) {
        $this->_tableName = $_tableName;
    }

    public function set_id($_id) {
        $this->_id = $_id;
    }

    public function set_user_id($_user_id) {
        $this->_user_id = $_user_id;
    }

    public function set_latitude($_latitude) {
        $this->_latitude = $_latitude;
    }

    public function set_longitude($_longitude) {
        $this->_longitude = $_longitude;
    }

    public function set_article_id($_article_id) {
        $this->_article_id = $_article_id;
    }

    public function set_device_type($_device_type) {
        $this->_device_type = $_device_type;
    }

    public function set_created_date($_created_date) {
        $this->_created_date = $_created_date;
    }
    
    function getType() {
        return $this->_type;
    }

    function setType($type) {
        $this->_type = $type;
    }
    
    public function insertAdsReportData(){
        $data = array();
        $type=  $this->getType();
        $data['user_id'] = $this->get_token();
        $data['latitude'] = $this->get_latitude();
        $data['longitude'] = $this->get_longitude();
        $data['article_id'] = $this->get_article_id();
        $data['device_type'] = $this->get_device_type();
        if($type=='open'){
        $query = $this->db->set($data)->set("created_date","NOW()",false)
                          ->insert("tbl_ads_report");
        }else if($type=='magazine'){
        $query = $this->db->set($data)->set("created_date","NOW()",false)
                          ->insert("tbl_magazine_ads_report");
        }
         return TRUE;
    }
}
