<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class advertisement_model extends CI_Model {
    
    private $_tableName = 'tbl_advertisement';
    private $_id = "";
    private $_title = "";
    private $_cover_image = "";
    private $_portrait_image = "";
    private $_landscape_image = "";
    private $_media_type = "";
    private $_publish_date_from = "";
    private $_publish_date_to = "";
    private $_size = "";
    private $_link = "";
    private $_catagory_id = "";
    private $_language_id = "";
    private $_display_time = "";
    private $_layout_type = "";
    private $_status = "";
    private $_created_date = "";
    private $_is_approved = "";
    private $_customer_id = "";
    private $_lang = "";
    private $_magazine_id = "";
    private $_lang_param = "";
    public function get_lang_param() {
        return $this->_lang_param;
    }

    public function set_lang_param($_lang_param) {
        $this->_lang_param = $_lang_param;
    }
    
    public function get_magazine_id() {
        return $this->_magazine_id;
    }

    public function set_magazine_id($_magazine_id) {
        $this->_magazine_id = $_magazine_id;
    }
    
    
    public function get_lang() {
        return $this->_lang;
    }

    public function set_lang($_lang) {
        $this->_lang = $_lang;
    }
    
    
    public function get_tableName() {
        return $this->_tableName;
    }

    public function get_id() {
        return $this->_id;
    }

    public function get_title() {
        return $this->_title;
    }

    public function get_cover_image() {
        return $this->_cover_image;
    }

    public function get_portrait_image() {
        return $this->_portrait_image;
    }

    public function get_landscape_image() {
        return $this->_landscape_image;
    }

    public function get_media_type() {
        return $this->_media_type;
    }

    public function get_publish_date_from() {
        return $this->_publish_date_from;
    }

    public function get_publish_date_to() {
        return $this->_publish_date_to;
    }

    public function get_size() {
        return $this->_size;
    }

    public function get_link() {
        return $this->_link;
    }

    public function get_catagory_id() {
        return $this->_catagory_id;
    }

    public function get_language_id() {
        return $this->_language_id;
    }

    public function get_display_time() {
        return $this->_display_time;
    }

    public function get_layout_type() {
        return $this->_layout_type;
    }

    public function get_status() {
        return $this->_status;
    }

    public function get_created_date() {
        return $this->_created_date;
    }

    public function get_is_approved() {
        return $this->_is_approved;
    }

    public function get_customer_id() {
        return $this->_customer_id;
    }

    public function set_tableName($_tableName) {
        $this->_tableName = $_tableName;
    }

    public function set_id($_id) {
        $this->_id = $_id;
    }

    public function set_title($_title) {
        $this->_title = $_title;
    }

    public function set_cover_image($_cover_image) {
        $this->_cover_image = $_cover_image;
    }

    public function set_portrait_image($_portrait_image) {
        $this->_portrait_image = $_portrait_image;
    }

    public function set_landscape_image($_landscape_image) {
        $this->_landscape_image = $_landscape_image;
    }

    public function set_media_type($_media_type) {
        $this->_media_type = $_media_type;
    }

    public function set_publish_date_from($_publish_date_from) {
        $this->_publish_date_from = $_publish_date_from;
    }

    public function set_publish_date_to($_publish_date_to) {
        $this->_publish_date_to = $_publish_date_to;
    }

    public function set_size($_size) {
        $this->_size = $_size;
    }

    public function set_link($_link) {
        $this->_link = $_link;
    }

    public function set_catagory_id($_catagory_id) {
        $this->_catagory_id = $_catagory_id;
    }

    public function set_language_id($_language_id) {
        $this->_language_id = $_language_id;
    }

    public function set_display_time($_display_time) {
        $this->_display_time = $_display_time;
    }

    public function set_layout_type($_layout_type) {
        $this->_layout_type = $_layout_type;
    }

    public function set_status($_status) {
        $this->_status = $_status;
    }

    public function set_created_date($_created_date) {
        $this->_created_date = $_created_date;
    }

    public function set_is_approved($_is_approved) {
        $this->_is_approved = $_is_approved;
    }

    public function set_customer_id($_customer_id) {
        $this->_customer_id = $_customer_id;
    }
    
    public function getLanguageId(){
        if($this->get_lang() != ''){
        $exp = explode(",",$this->get_lang());
        $expCount = count($exp);
        
            if($exp[0] == "pt"){
                $exp[0] = "po";
            }elseif($exp[1] == "pt"){
                $exp[1] = "po";
            }elseif($exp[2] == "pt"){
                $exp[2] = "po";
            }
        }else{
            $exp = "en";
        }
        
        
        $query = $this->db->select("id")
                          ->from("tbl_language")
                          ->where_in("language_code",  $exp)
                          ->get();
        //echo $this->db->last_query();die;
        if($query->num_rows()>1){
        $row = $query->result_array();
        $this->set_lang_param("2");
        
        return $row;
        }else{
          $row = $query->row_array();
        $this->set_lang_param("1");
        return $row;  
        }
        
    }
    
    public function getAllAdvertiseOpen(){
        $date = date("Y-m-d");
        $langId = $this->get_language_id();
//        $imp = implode(",", $langId);
//        $langId = $imp;
        if($langId == ''){
            $langId = '1';
        }
        if($this->get_catagory_id() != ''){
        $topicId = explode(",",  ltrim($this->get_catagory_id(),","));
        $topicImplode = implode("|", $topicId);
        $query = "";
        $query .= "SELECT 
    *
FROM
    (tbl_advertisement)
WHERE
    language_id IN ($langId) AND publish_date_from <= '$date' AND publish_date_to >= '$date'"
            . " AND catagory_id REGEXP '(^|,)($topicImplode)(,|$)' AND status = '1' AND is_approved = '1'";
        }else{
           $query = "";
        $query .= "SELECT 
    *
FROM
    (tbl_advertisement)
WHERE
    language_id IN ($langId) AND publish_date_from <= '$date' AND publish_date_to >= '$date'"
            . " AND status = '1' AND is_approved = '1'"; 
        }
        $queryRes = $this->db->query($query);
        //echo $this->db->last_query();
        $row = $queryRes->result_array();
        return $row;
        //echo "<pre>";print_r($row);die;
    }
    
    public function getAllAdvertiseMagazine(){
        $date = date("Y-m-d");
        $magId = $this->get_magazine_id();
        
        $query = "";
        $query .= "SELECT 
    id,
source,
title,
cover_image,
portrait_image,
landscape_image,
media_type,
embed_video,
magazine_id,
publish_date_from,
publish_date_to,
size,
link,
catagory_id,
language_id,
display_time,
layout_type,
status,
created_date,
is_approved,
customer_id,embed_thumb
FROM
    (tbl_magazine_advertisement)
WHERE
     publish_date_from <= '$date' AND publish_date_to >= '$date'"
            . "AND FIND_IN_SET('$magId', magazine_id) > 0 AND status = '1' AND is_approved = '1' 
                UNION
                SELECT 
    id,
source,
title,
cover_image,
portrait_image,
landscape_image,
media_type,
embed_video,
magazine_id,
publish_date_from,
publish_date_to,
size,
link,
catagory_id,
language_id,
display_time,
layout_type,
status,
created_date,
is_approved,
customer_id,embed_thumb
FROM
    (tbl_advertisement)
WHERE
     publish_date_from <= '$date' AND publish_date_to >= '$date'"
            . "AND FIND_IN_SET('$magId', magazine_id) > 0 AND status = '1' AND is_approved = '1'
                ";
        $queryRes = $this->db->query($query);
        //echo $this->db->last_query();die;
        $row = $queryRes->result_array();
        return $row;
    }

    
}
