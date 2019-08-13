<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Article_comment_model extends CI_Model {
    
       
    private $_tableName = 'tbl_article_comment';
    private $_id = "";
    private $_article_id = "";
    private $_comment = "";
    private $_user_id = "";
    private $_created_date = "";
    private $_status = "";
    private $_token = "";
    private $_type="";


    public function get_tableName() {
        return $this->_tableName;
    }

    public function get_id() {
        return $this->_id;
    }

    public function get_article_id() {
        return $this->_article_id;
    }

    public function get_comment() {
        return $this->_comment;
    }

    public function get_user_id() {
        return $this->_user_id;
    }

    public function get_created_date() {
        return $this->_created_date;
    }

    public function get_status() {
        return $this->_status;
    }

    public function get_token() {
        return $this->_token;
    }

    public function set_tableName($_tableName) {
        $this->_tableName = $_tableName;
    }

    public function set_id($_id) {
        $this->_id = $_id;
    }

    public function set_article_id($_article_id) {
        $this->_article_id = $_article_id;
    }

    public function set_comment($_comment) {
        $this->_comment = $_comment;
    }

    public function set_user_id($_user_id) {
        $this->_user_id = $_user_id;
    }

    public function set_created_date($_created_date) {
        $this->_created_date = $_created_date;
    }

    public function set_status($_status) {
        $this->_status = $_status;
    }

    public function set_token($_token) {
        $this->_token = $_token;
    }
    
    function getType() {
        return $this->_type;
    }

    function setType($type) {
        $this->_type = $type;
    }

    
    public function verifyTokenValue(){
        $query = $this->db->select("id")
                          ->from("tbl_users")
                          ->where("token",  $this->get_token())
                          ->where("token_expiry_date >=","NOW()",false)
                          ->get();
        //echo $this->db->last_query();die;
        if($query->num_rows()>0){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    public function getArticleComments(){
        $type=  $this->getType();
        if($type=='open'){
        $query = $this->db->select("ac.*,tu.name,tu.photoUrl")
                          ->from("tbl_article_comment as ac")
                          ->join("tbl_users as tu","tu.id=ac.user_id",'left')
                          ->where("ac.article_id",  $this->get_article_id())
                          ->where("ac.status","1")
                          ->get();
        }else if($type=='magazine'){
        $query = $this->db->select("ac.*,tu.name,tu.photoUrl")
                          ->from("tbl_magazine_article_comment as ac")
                          ->join("tbl_users as tu","tu.id=ac.user_id",'left')
                          ->where("ac.article_id",  $this->get_article_id())
                          ->where("ac.status","1")
                          ->get();
        }
        //echo $this->db->last_query();die;
        if($query->num_rows()>0){
           $row = $query->result_array();
           return $row;
        }else{
            return FALSE; 
        }
    }
    
    public function postArticleComments(){
        $data = array();
        $type=  $this->getType();
        $data['article_id'] = $this->get_article_id();
        $data['comment'] = $this->get_comment();
        $data['user_id'] = $this->get_user_id();
        foreach ($data as $key => $value) {
            if (($value == '')) {
                unset($data[$key]);
            }
        }
        if($type=='open'){
        $query = $this->db->set($data)->set("created_date","NOW()",false)
                          ->insert("tbl_article_comment");
                      return TRUE;
        }elseif ($type=='magazine') {
        $query = $this->db->set($data)->set("created_date","NOW()",false)
                          ->insert("tbl_magazine_article_comment");
                      return TRUE;
        }
        
    }

    
}

