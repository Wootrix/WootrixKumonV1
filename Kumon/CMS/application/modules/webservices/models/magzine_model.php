<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Magzine_model extends CI_Model {
    
            
    private $_tableName = 'tbl_magazine';
    private $_id = "";
    private $_title = "";
    private $_cover_image = "";
    private $_customer_logo = "";
    private $_bar_color = "";
    private $_no_of_user = "";
    private $_publish_date_from = "";
    private $_publish_date_to = "";
    private $_customer_id = "";
    private $_subscription_password = "";
    private $_status = "";
    private $_created_date = "";
    private $_language_id = "";
    private $_token = "";
    private $_magazine_id = "";
    private $_password = "";
    private $_user_id = "";
    
    public function get_user_id() {
        return $this->_user_id;
    }

    public function set_user_id($_user_id) {
        $this->_user_id = $_user_id;
    }
    
    public function get_password() {
        return $this->_password;
    }

    public function set_password($_password) {
        $this->_password = $_password;
    }
    
    
    public function get_magazine_id() {
        return $this->_magazine_id;
    }

    public function set_magazine_id($_magazine_id) {
        $this->_magazine_id = $_magazine_id;
    }
    
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

    public function get_title() {
        return $this->_title;
    }

    public function get_cover_image() {
        return $this->_cover_image;
    }

    public function get_customer_logo() {
        return $this->_customer_logo;
    }

    public function get_bar_color() {
        return $this->_bar_color;
    }

    public function get_no_of_user() {
        return $this->_no_of_user;
    }

    public function get_publish_date_from() {
        return $this->_publish_date_from;
    }

    public function get_publish_date_to() {
        return $this->_publish_date_to;
    }

    public function get_customer_id() {
        return $this->_customer_id;
    }

    public function get_subscription_password() {
        return $this->_subscription_password;
    }

    public function get_status() {
        return $this->_status;
    }

    public function get_created_date() {
        return $this->_created_date;
    }

    public function get_language_id() {
        return $this->_language_id;
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

    public function set_customer_logo($_customer_logo) {
        $this->_customer_logo = $_customer_logo;
    }

    public function set_bar_color($_bar_color) {
        $this->_bar_color = $_bar_color;
    }

    public function set_no_of_user($_no_of_user) {
        $this->_no_of_user = $_no_of_user;
    }

    public function set_publish_date_from($_publish_date_from) {
        $this->_publish_date_from = $_publish_date_from;
    }

    public function set_publish_date_to($_publish_date_to) {
        $this->_publish_date_to = $_publish_date_to;
    }

    public function set_customer_id($_customer_id) {
        $this->_customer_id = $_customer_id;
    }

    public function set_subscription_password($_subscription_password) {
        $this->_subscription_password = $_subscription_password;
    }

    public function set_status($_status) {
        $this->_status = $_status;
    }

    public function set_created_date($_created_date) {
        $this->_created_date = $_created_date;
    }

    public function set_language_id($_language_id) {
        $this->_language_id = $_language_id;
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
    
    public function getUserMagzines(){
        $date = date("Y-m-d");
        $queryGetUserMagId = $this->db->select("magazine_id")
                                      ->from("tbl_user_magazines")
                                        ->join("tbl_users u", "user_id = u.id")
                                        ->join("tbl_magazine_location", "location = country AND id_magazine = magazine_id")
                                      ->where("user_id",  $this->get_token())
                                      ->get();
        $rowMagId = $queryGetUserMagId->result_array();
        
        if($queryGetUserMagId->num_rows()>0){
        foreach($rowMagId as $r){
            
            $magId[] = $r['magazine_id'];
        }
        }else{
            $magId = array(0);
        }
        $query = $this->db->select("*")
                          ->from("tbl_magazine")                          
                          ->where("publish_date_from <=",$date)
                          ->where("publish_date_to >=",$date)
                          ->where("status","1")
                          ->where_in("id",$magId)
                          ->get();
        if($query->num_rows()>0){
           $row = $query->result_array();
           return $row;
        }else{
            return FALSE;
        }
    }
    
    public function getSubscribeMagzineData(){
        $date = date("Y-m-d");
        $queryCheckPass = $this->db->select("*")
            ->from("tbl_magazine_password")
            ->where("read_status","0")
            ->where("password",  $this->get_password())
            ->get();
        if($queryCheckPass->num_rows()>0){
            $rowCheckId = $queryCheckPass->row_array();
            if($rowCheckId['code_type']!='2'){
                $queryUpdate = $this->db->set("read_status","1")
                    ->set("user_id",$this->get_token())
                    ->where("id",$rowCheckId['id'])
                    ->update("tbl_magazine_password");
            }
            $queryCheckMagazineAdded = $this->db->select("*")
                ->from("tbl_user_magazines")
                ->where("user_id",$this->get_token())
                ->where("magazine_id",$rowCheckId['magazine_id'])
                ->get();
            if($queryCheckMagazineAdded->num_rows()>0){
                return FALSE;
            }else{
                $queryInsert = $this->db->set("user_id",  $this->get_token())
                    ->set("magazine_id",$rowCheckId['magazine_id'])
                    ->set("created_on","NOW()",false)
                    ->insert("tbl_user_magazines");

                $query = $this->db->select("*")
                    ->from("tbl_magazine")
                    ->where("id",  $rowCheckId['magazine_id'])
                    ->where("publish_date_from <=",$date)
                    ->where("publish_date_to >=",$date)
                    ->where("status","1")
                    ->get();
                if($query->num_rows()>0){
                    $row = $query->row_array();
                    return $row;
                }else{
                    return FALSE;
                }
            }
        }else{
            return FALSE;
        }
    }

    public function getMagazineData(){

        $magazineId = $this->get_id();

        $query = $this->db->select("*")
            ->from("tbl_magazine")
            ->where("id",  $magazineId)
            ->get();

        $row = $query->row_array();
        return $row;

    }
    
    public function getSearchArticleList(){
        $date = date("Y-m-d");
        $queryGetUserMagId = $this->db->select("magazine_id")
                                      ->from("tbl_user_magazines")
                                      ->where("user_id",  $this->get_token())
                                      ->get();
        $rowMagId = $queryGetUserMagId->result_array();
        
        if($queryGetUserMagId->num_rows()>0){
        foreach($rowMagId as $r){
            
            $magId[] = $r['magazine_id'];
        }
        }else{
            $magId = array(0);
        }
        $queryGetUserMagId = $this->db->select("magazine_id")
                                      ->from("tbl_user_magazines")
                                      ->where("user_id",  $this->get_user_id())
                                      ->get();
        $rowMagId = $queryGetUserMagId->result_array();
        if($queryGetUserMagId->num_rows()>0){
        foreach($rowMagId as $r){
            
            $magId[] = $r['magazine_id'];
        }
        }else{
            $magId = array(0);
        }
        if($this->get_title() != ''){
        $query = $this->db->select("title,id")
                        ->from("tbl_magazine")                        
                        ->where("publish_date_from <=",$date)
                        ->where("publish_date_to >=",$date)
                        ->where("status","1")
                        ->where_in("id",$magId)
                        ->like("title",  $this->get_title(),"both")
                        ->get();
        //echo $this->db->last_query();die;
        $row = $query->result_array();
        return $row;
        }
    }
    
    public function getMagazineDetail(){
        $query = $this->db->select("*")
                          ->from("tbl_magazine")
                          ->where("id",  $this->get_id())
                          ->get();
        $row = $query->result_array();
        $i=0;
        foreach($row as $r){
           $date = date("Y-m-d");

        $queryArt = $this->db->select("count(id) as totalArticle")
                          ->from("tbl_magazine_articles")
                          ->where("magazine_id",  $r['id'])
                          ->where("status","2")
                          ->where("publish_from <=",$date)
                          ->where("publish_to >=",$date)
                          ->get();
        $rowArt = $queryArt->row_array();
        $row[$i]['totalArticle'] = $rowArt['totalArticle'];
        $i++;}
        return $row;
    }
    
    public function getArticleCount(){
        $date = date("Y-m-d");
        $query = $this->db->select("count(id) as totalArticle")
                          ->from("tbl_magazine_articles")
                          ->where("magazine_id",  $this->get_id())
                          ->where("status","2")
                          ->where("publish_from <=",$date)
                          ->where("publish_to >=",$date)
                          ->get();
        $row = $query->row_array();
        return $row;
    }
    
    public function getMagazineDetailList(){
        $queryGetUserMagId = $this->db->select("magazine_id")
                                    ->from("tbl_user_magazines")
                                    ->join("tbl_users u", "user_id = u.id")
                                    ->join("tbl_magazine_location", "location = country AND id_magazine = magazine_id")
                                      ->where("user_id",  $this->get_user_id())
                                      ->get();
        $rowMagId = $queryGetUserMagId->result_array();
        
        if($queryGetUserMagId->num_rows()>0){
        foreach($rowMagId as $r){
            
            $magId[] = $r['magazine_id'];
        }
        }else{
            $magId = array(0);
        }
        
        $query = $this->db->select("*")
                          ->from("tbl_magazine")
                          ->like("title",  $this->get_title(),"both")
                          ->where_in("id",$magId)
                          ->get();
        //echo $this->db->last_query();die;
        if($query->num_rows()>0){
        $row = $query->result_array();
        $i=0;
        foreach($row as $r){
           $date = date("Y-m-d");
        $queryArt = $this->db->select("count(id) as totalArticle")
                          ->from("tbl_magazine_articles")
                          ->where("magazine_id",  $r['id'])
                          ->where("status","2")
                          ->where("publish_from <=",$date)
                          ->where("publish_to >=",$date)
                          ->get();
        $rowArt = $queryArt->row_array();
        $row[$i]['totalArticle'] = $rowArt['totalArticle'];
        $i++;}
        //echo "<pre>";print_r($row);die;
        return $row;
        }
    }
    
    public function getArticleCountUsingTitle(){
       $date = date("Y-m-d");
        $query = $this->db->select("count(id) as totalArticle")
                          ->from("tbl_magazine_articles")
                          ->where("magazine_id",  $this->get_id())
                          ->where("status","2")
                          ->where("publish_from <=",$date)
                          ->where("publish_to >=",$date)
                          ->get();
        $row = $query->row_array();
        return $row; 
    }
    
    public function getTitleName(){
        $query = $this->db->select("title")
                          ->from("tbl_magazine")
                          ->where("id",  $this->get_id())
                          ->get();
        $row = $query->row_array();
        return $row;
    }

    public function magzinedelete($userid,$magzineid){
        $this->db->where('user_id',$userid);
        $this->db->where('magazine_id',$magzineid);
        $delete=$this->db->delete('tbl_user_magazines');
        if($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        else
        {
            $this->db->where('user_id',$userid);
            $this->db->where('magazine_id',$magzineid);
            $row=$this->db->get('tbl_user_magazines')->row();
            if(count($row)==0)
            {
                echo json_encode(array('data' => $row, 'message' => 'ID Empty', 'success' => false));
                exit();
            }
            return FALSE;
        }
    }

    public function userHasMagazine(){

        $userId = $this->get_user_id();
        $magazineId = $this->get_id();

        $query = "SELECT * FROM tbl_user_magazines WHERE user_id = $userId AND magazine_id = $magazineId";
        $sql = $this->db->query($query);

        $result = $sql->row();

        $hasMagazine = false;

        if( isset( $result->id ) ){
            $hasMagazine = true;
        }

        return $hasMagazine;

    }

    public function getMagazineByCode(){

        $magazineCode = $this->get_password();

        $query = "SELECT magazine_id FROM tbl_magazine_password WHERE password = '$magazineCode'";
        $sql = $this->db->query($query);

        $result = $sql->row();

        $magazineId = -1;

        if( isset( $result->magazine_id ) ){
            return $result->magazine_id;
        }

        return $magazineId;

    }

}

