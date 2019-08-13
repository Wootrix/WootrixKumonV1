<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class language_model extends CI_Model {
   
        private $_tableName = 'tbl_admin';
    private $_language_code= "";
    private $_customer_id = "";
    
    
    public function get_language_code() {
        return $this->_language_code;
    }

    public function set_language_code($_language_code) {
        $this->_language_code = $_language_code;
    }
     
    public function get_customer_id() {
        return $this->_customer_id;
    }

    public function set_customer_id($_customer_id) {
        $this->_customer_id = $_customer_id;
    }

    
     /* ADMIN LANGUAGE CODE*/
    
     public function updateLanguageCode(){
         $customer_id = $this->get_customer_id();
         $language_code = $this->get_language_code();         
         $sql = $this->db->query("UPDATE tbl_admin SET language_code = '$language_code' WHERE id='$customer_id'");         
         if($sql){
          return TRUE;   
         }
     }
     
     /*Update CUSTOMER LANGUAGE*/
     
     public function updateCustomerLanguageCode(){
         
         $customer_id = $this->get_customer_id();
         $language_code = $this->get_language_code();         
         $sql = $this->db->query("UPDATE tbl_customer SET language_code = '$language_code' WHERE id='$customer_id'");         
         //echo $this->db->last_query();die;
         if($sql){
          return TRUE;   
         }
         
     }

    public function getAllFilter() {

        $sql = "SELECT id, language as title FROM tbl_language WHERE status = '1' ORDER BY language";

        $sqlData = $this->db->query($sql);

        if ($sqlData->num_rows() < 1) {
            return false;
        } else {
            $data = $sqlData->result_array();
            return $data;
        }

    }


   

}

?>
