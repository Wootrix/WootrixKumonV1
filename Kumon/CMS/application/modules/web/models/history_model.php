<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
(defined('BASEPATH')) OR exit('No direct script access allowed');

class history_model extends CI_Model {
        
    private $_id = "";
    private $_title = "";
    Private $_language_id = "";
    
    
    /*GETTER AND SETTER*/
     public function get_id() {
        return $this->_id;
    }

    public function set_id($_id) {
        $this->_id = $_id;
    }

    public function get_title() {
        return $this->_title;
    }

    public function set_title($_title) {
        $this->_title = $_title;
    }

    public function get_language_id() {
        return $this->_language_id;
    }

    public function set_language_id($_language_id) {
        $this->_language_id = $_language_id;
    }
    
    /* LANGUAGE LIST */

    /**
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * listing of language 
     * */
    public function getLanguageList() {

        $language_sql = $this->db->query("SELECT id,language FROM tbl_language WHERE status ='1' ");
        if ($language_sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $language_sql->result_array();
        }
    }
    
    /* History LIST */

    /**
     * @author Techahead
     * @access Public
     * @param 
     * @return     
     * */
    
    public function getMagazineHistoryList(){
        
         /* FIRSTLY UPDATE MAGAZINE TABLE*/
        $magazine_badge_query = $this->db->query("UPDATE tbl_magazine SET badge_read_status ='1' WHERE (status='2' OR publish_date_to < CURDATE())");
        /*---------------*/
        
        $title = $this->get_title();
        if($title != ""){
            
            $sql = $this->db->query("SELECT a.id,a.title,a.cover_image,a.status,a.publish_date_from,a.publish_date_to,a.subscription_password,
            b.name,c.language FROM tbl_magazine as a INNER JOIN tbl_customer as b ON a.customer_id = b.id Inner Join tbl_language as c ON
            a.language_id = c.id WHERE a.title LIKE '%$title%' AND (a.status = '2' Or a.publish_date_to < CURDATE()) ORDER BY a.id DESC");
            
        }else{
            $sql = $this->db->query("SELECT a.id,a.title,a.cover_image,a.status,a.publish_date_from,a.publish_date_to,a.subscription_password,
            b.name,c.language FROM tbl_magazine as a INNER JOIN tbl_customer as b ON a.customer_id = b.id Inner Join tbl_language as c ON
            a.language_id = c.id WHERE a.status = '2' Or publish_date_to < CURDATE() ORDER BY a.id DESC");            
        }
        
        

        /* PAGINATION */
          $config['base_url'] = base_url().'index.php/'.'history?';
          $config['total_rows'] = $sql->num_rows();
          $config['per_page'] = 10;
          //$config['uri_segment'] = 3;
          $config['full_tag_open'] = "<p class='pagination_bottom'>";
          $config['full_tag_close'] = "</p>";
          $config['page_query_string'] = TRUE;
          $config['query_string_segment'] = 'page';
          $config['use_page_numbers'] = TRUE;
          $config['next_link'] = 'Next';
          $config['prev_link'] = 'Prev';
          $config['first_link']= 'First';
          $config['last_link'] = 'Last';

          $this->pagination->initialize($config);
          if ($_GET['page']) {
          $page = $_GET['page'];
          } else {
          $page = 1;
          }
          if ($page == 1) {
          $limitPage = 0;
          } else {
          $limitPage = ($page - 1) * 10;
          } 

        /* ------------------ */
          if($title != ""){
            
            $sql_data = $this->db->query("SELECT a.id,a.title,a.cover_image,a.status,a.publish_date_from,a.publish_date_to,a.subscription_password,
            b.name,c.language FROM tbl_magazine as a left JOIN tbl_customer as b ON a.customer_id = b.id left Join tbl_language as c ON
            a.language_id = c.id WHERE a.title LIKE '%$title%' AND (a.status = '2' Or a.publish_date_to < CURDATE()) ORDER BY a.id DESC LIMIT $limitPage,10");
            
        }else{
            $sql_data = $this->db->query("SELECT a.id,a.title,a.cover_image,a.status,a.publish_date_from,a.publish_date_to,a.subscription_password,
            b.name,c.language FROM tbl_magazine as a INNER JOIN tbl_customer as b ON a.customer_id = b.id Inner Join tbl_language as c ON
            a.language_id = c.id WHERE a.status = '2' Or publish_date_to < CURDATE() ORDER BY a.id DESC LIMIT $limitPage,10");            
        }
        
        //echo $this->db->last_query();

        if ($sql_data->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql_data->result_array();
        }
        
    }
    
     /** GET MAGAZINE
     * title
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * listing of language 
     * */
    public function getMagazinetitle(){
        
        $magazine_id = $this->get_id();        
        $sql = $this->db->query("SELECT title FROM tbl_magazine WHERE id = '$magazine_id' ");
        //echo $this->db->last_query();  
        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
             return $data = $sql->row_array();             
        }
        
        
    }
    
    /** GET DELETED MAGAZINE
     * ARTICLE
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * listing of language 
     * */
    public function getMagazineArticle(){
        
        $magazine_id = $this->get_id();
        
        $title= $this->get_title();
        $language_id = $this->get_language_id();
        
        if($title !=""){
            $sql = $this->db->query("SELECT a.id,a.title,a.author,a.image,a.media_type,a.language_id,a.magazine_id,a.customer_id,b.language ,c.name
            ,a.tags,a.category_id FROM tbl_magazine_articles as a INNER JOIN tbl_language as b ON a.language_id = b.id 
            INNER JOIN tbl_customer as c ON a.customer_id = c.id WHERE a.magazine_id = '$magazine_id' AND a.title LIKE '%$title%'
            ");
            
        }elseif($language_id !=""){
            $sql = $this->db->query("SELECT a.id,a.title,a.author,a.image,a.media_type,a.language_id,a.magazine_id,a.customer_id,b.language ,c.name
            ,a.tags,a.category_id FROM tbl_magazine_articles as a INNER JOIN tbl_language as b ON a.language_id = b.id 
            INNER JOIN tbl_customer as c ON a.customer_id = c.id WHERE a.magazine_id = '$magazine_id'  AND a.language_id='$language_id'
            ");
            
        }else{
            $sql = $this->db->query("SELECT a.id,a.title,a.author,a.image,a.media_type,a.language_id,a.magazine_id,a.customer_id,b.language ,c.name
            ,a.tags,a.category_id FROM tbl_magazine_articles as a INNER JOIN tbl_language as b ON a.language_id = b.id 
            INNER JOIN tbl_customer as c ON a.customer_id = c.id WHERE a.magazine_id = '$magazine_id'
            ");
        }
        
       
        //echo $this->db->last_query(); die;
        
        
        /* PAGINATION */
         $config['base_url'] = base_url().'index.php/'.'history?';
          $config['total_rows'] = $sql->num_rows();
          $config['per_page'] = 10;
          //$config['uri_segment'] = 3;
          $config['full_tag_open'] = "<p class='pagination_bottom'>";
          $config['full_tag_close'] = "</p>";
          $config['page_query_string'] = TRUE;
          $config['query_string_segment'] = 'page';
          $config['use_page_numbers'] = TRUE;
          $config['next_link'] = 'Next';
          $config['prev_link'] = 'Prev';
          $config['first_link']= 'First';
          $config['last_link'] = 'Last';

          $this->pagination->initialize($config);
          if ($_GET['page']) {
          $page = $_GET['page'];
          } else {
          $page = 1;
          }
          if ($page == 1) {
          $limitPage = 0;
          } else {
          $limitPage = ($page - 1) * 10;
          } 
          
         /* -------- */
          if($title !=""){
            $sql_data = $this->db->query("SELECT a.id,a.title,a.author,a.image,a.media_type,a.language_id,a.magazine_id,a.customer_id,b.language ,c.name
            ,a.tags,a.category_id FROM tbl_magazine_articles as a INNER JOIN tbl_language as b ON a.language_id = b.id 
            INNER JOIN tbl_customer as c ON a.customer_id = c.id WHERE a.magazine_id = '$magazine_id'  AND a.title LIKE '%$title%'
            LIMIT $limitPage,10 ");
            
        }elseif($language_id !=""){
            $sql_data = $this->db->query("SELECT a.id,a.title,a.author,a.image,a.media_type,a.language_id,a.magazine_id,a.customer_id,b.language ,c.name
            ,a.tags,a.category_id FROM tbl_magazine_articles as a INNER JOIN tbl_language as b ON a.language_id = b.id 
            INNER JOIN tbl_customer as c ON a.customer_id = c.id WHERE a.magazine_id = '$magazine_id' AND a.language_id='$language_id'
            LIMIT $limitPage,10");
            
        }else{
            $sql_data = $this->db->query("SELECT a.id,a.title,a.author,a.image,a.media_type,a.language_id,a.magazine_id,a.customer_id,b.language ,c.name
            ,a.tags,a.category_id FROM tbl_magazine_articles as a INNER JOIN tbl_language as b ON a.language_id = b.id 
            INNER JOIN tbl_customer as c ON a.customer_id = c.id WHERE a.magazine_id = '$magazine_id' 
            LIMIT $limitPage,10");
        }
          
          
        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
             $data = $sql_data->result_array();
                foreach ($data as $key => $value) {

                    $category_id = $value['category_id'];
                    //echo $category_id; die;
                    $implode = explode(',', $category_id);
                    $category_list = implode(",", $implode);
                    $sql1 = $this->db->query("SELECT category_name FROM tbl_category WHERE id IN ($category_list)");
                    $nameArray = $sql1->result_array();
                    $data[$key]['category_name'] = $nameArray;
                    $data[$key]['article_count'] = $sql->num_rows(); 
                }
            //echo"<pre>"; print_r($data); die;
                //$data['Article_count'] = $sql->num_rows();
            return $data;
        }
        
    }
    
}

?>
