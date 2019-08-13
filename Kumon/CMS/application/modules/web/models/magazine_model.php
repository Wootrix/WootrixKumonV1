<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
(defined('BASEPATH')) OR exit('No direct script access allowed');

class magazine_model extends CI_Model {
    
    
    private $_tableName = 'tbl_magazine';
    private $_id = "";
    private $_title = "";
    private $_cover_image = "";
    private $_publish_date_from = "";
    private $_publish_date_to = "";
    private $_customer_id = "";
    private $_subscription_password = "";
    private $_created_date = "";
    private $_customer_logo = "";
    private $_bar_color = "";
    Private $_no_of_user = "";
    Private $_language_id = "";
    private $_catagory_id = "";
    /* tbl_article_decline */
    private $_article_id = "";
    private $_article_report = "";
    private $_userName="";
    private $_article_language = "";
    private $_codeType='';
    private $_source="";
    
    function getSource() {
        return $this->_source;
    }

    function setSource($source) {
        $this->_source = $source;
    }
    
    function getCodeType() {
        return $this->_codeType;
    }

    function setCodeType($codeType) {
        $this->_codeType = $codeType;
    }

    // phase 2 work changes for multiple image
    private $magazine_images="";

    /* GETTER AND SETTER */
    
    function getUserName() {
        return $this->_userName;
    }

    function setUserName($userName) {
        $this->_userName = $userName;
    }
    
    public function get_tableName() {
        return $this->_tableName;
    }

    public function set_tableName($_tableName) {
        $this->_tableName = $_tableName;
    }

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

    public function get_cover_image() {
        return $this->_cover_image;
    }

    public function set_cover_image($_cover_image) {
        $this->_cover_image = $_cover_image;
    }

    public function get_publish_date_from() {
        return $this->_publish_date_from;
    }

    public function set_publish_date_from($_publish_date_from) {
        $this->_publish_date_from = $_publish_date_from;
    }

    public function get_publish_date_to() {
        return $this->_publish_date_to;
    }

    public function set_publish_date_to($_publish_date_to) {
        $this->_publish_date_to = $_publish_date_to;
    }

    public function get_customer_id() {
        return $this->_customer_id;
    }

    public function set_customer_id($_customer_id) {
        $this->_customer_id = $_customer_id;
    }

    public function get_subscription_password() {
        return $this->_subscription_password;
    }

    public function set_subscription_password($_subscription_password) {
        $this->_subscription_password = $_subscription_password;
    }

    public function get_created_date() {
        return $this->_created_date;
    }

    public function set_created_date($_created_date) {
        $this->_created_date = $_created_date;
    }

    public function get_customer_logo() {
        return $this->_customer_logo;
    }

    public function set_customer_logo($_customer_logo) {
        $this->_customer_logo = $_customer_logo;
    }

    public function get_bar_color() {
        return $this->_bar_color;
    }

    public function set_bar_color($_bar_color) {
        $this->_bar_color = $_bar_color;
    }

    public function get_no_of_user() {
        return $this->_no_of_user;
    }

    public function set_no_of_user($_no_of_user) {
        $this->_no_of_user = $_no_of_user;
    }

    public function get_language_id() {
        return $this->_language_id;
    }

    public function set_language_id($_language_id) {
        $this->_language_id = $_language_id;
    }

    public function get_catagory_id() {
        return $this->_catagory_id;
    }

    public function set_catagory_id($_catagory_id) {
        $this->_catagory_id = $_catagory_id;
    }

    /* Tbl_article_decline */

    public function get_article_id() {
        return $this->_article_id;
    }

    public function set_article_id($_article_id) {
        $this->_article_id = $_article_id;
    }

    public function get_article_report() {
        return $this->_article_report;
    }

    public function set_article_report($_article_report) {
        $this->_article_report = $_article_report;
    }
    
     public function get_article_language() {
        return $this->_article_language;
    }

    public function set_article_language($_article_language) {
        $this->_article_language = $_article_language;
    }

    
    public function getMagazine_images() {
        return $this->magazine_images;
    }

    public function setMagazine_images($magazine_images) {
        $this->magazine_images = $magazine_images;
    }

        

    /* Magazine List */

    /**
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Magazine Array 
     * */
    public function magazineList() {
        $title = $this->get_title();        
        if($title !=""){
          $sql = $this->db->query("SELECT id,title,cover_image,status,publish_date_from,publish_date_to,subscription_password
            FROM tbl_magazine WHERE status = '1' AND publish_date_to >= CURDATE() AND title LIKE '%$title%'");
        }else{
         $sql = $this->db->query("SELECT id,title,cover_image,status,publish_date_from,publish_date_to,subscription_password
            FROM tbl_magazine WHERE status = '1' AND publish_date_to >= CURDATE()");   
        }

        /* PAGINATION */
        $config['base_url'] = base_url() . 'index.php/' . 'magazinelist?';
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
        $config['first_link'] = 'First';
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
                  
         if($title !=""){
          $sql_data = $this->db->query("SELECT id,title,cover_image,status,publish_date_from,publish_date_to,subscription_password
            FROM tbl_magazine WHERE status = '1' AND publish_date_to >= CURDATE() AND title LIKE '%$title%' ORDER BY id DESC LIMIT $limitPage,10 ");
          }else{
           $sql_data = $this->db->query("SELECT id,title,cover_image,status,publish_date_from,publish_date_to,subscription_password
            FROM tbl_magazine WHERE status = '1' AND publish_date_to >= CURDATE() ORDER BY id DESC LIMIT $limitPage,10 ");   
          }
        //echo $this->db->last_query();die;
        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            //return $data = $sql->result_array();
            $data = $sql_data->result_array();
            /* get article count in this magazine */
            foreach ($data as $key => $value) {
                $magazine_id = $value['id'];
                $sql1 = $this->db->query("SELECT id FROM tbl_magazine_articles WHERE FIND_IN_SET('$magazine_id', magazine_id) > 0 AND status='1' ");
                $data[$key]['new_article_count'] = $sql1->num_rows();
                $sql2 = $this->db->query("SELECT id FROM tbl_magazine_articles WHERE FIND_IN_SET('$magazine_id', magazine_id) > 0 UNION ALL SELECT id FROM tbl_new_articles WHERE FIND_IN_SET('$magazine_id', magazine_id) > 0");
                //echo $this->db->last_query();die;
                $data[$key]['all_article_count'] = $sql2->num_rows();
            }
            //echo "<pre>";print_r($data);die;
            return $data;
        }
    }

    /* CREATE MAGAZINE FOR A CUSTOMER */

    /**
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * True/false 
     * */
    public function addCustomerMagazine() {
        $id=  $this->get_id();
        $codeType=  $this->getCodeType();
        $title = $this->get_title();
        $publish_date_from = $this->get_publish_date_from();
        $publish_date_to = $this->get_publish_date_to();
        $subscription_pass = $this->get_subscription_password();
        $customer_id = $this->get_customer_id();
        $cover_image = $this->get_cover_image();
        $customer_logo = $this->get_customer_logo();
        if($bar_color !==""){
          $bar_color = $this->get_bar_color();  
        }else{
           $bar_color = '#ffffff';
        }
        
        $no_of_user = $this->get_no_of_user();   //
        $magazine_images=$this->getMagazine_images();

        $data1 = array(
            'title' => $title, 
            'cover_image' => $cover_image,
            'publish_date_from' => $publish_date_from,
            'publish_date_to' => $publish_date_to,
            'customer_id' => $customer_id,
            'subscription_password' => $subscription_pass,
            'customer_logo' => $customer_logo,
            'bar_color' => $bar_color,
            'no_of_user' => $no_of_user,
            'magazine_images'=>$magazine_images
             );
             
        $data=  array_filter($data1);
        
        if($id!=''){
        $sql = $this->db->set($data)->where('id',$id)->update('tbl_magazine');
            $magazine_id = $id;
        }else{

            $sql = $this->db->set('created_date', 'now()', FALSE)->insert('tbl_magazine', $data);
            $magazine_id = $this->db->insert_id();

            $usersSql = $this->db->query("select id FROM tbl_users");
            $users = $usersSql->result_array();

            foreach( $users as $user ){
                $this->db->query("INSERT INTO tbl_user_magazines (user_id, magazine_id) VALUES( " . $user['id'] . ", $magazine_id );");
            }

        }

        $this->set_id($magazine_id);

        if ($sql) {

            /* ---GENERATE RANDOM PASSWORD FOR CUSTOMER MAGAZINE---- */
            if($codeType=='1'){
            for ($loop = 0; $loop < intVal($no_of_user); $loop++) {
                $magPassword = "";
                $nameChar = "ABCDEFGHIJKLMNOPQRSTYUVWXYZabcdefghijklmnopqrstuvwxyz";
                //echo $no_of_user;
                for ($i = 0; $i < 3; $i++) {

                    $magPassword .= $magazine_id . $nameChar[rand(0, strlen($nameChar))];
                }

                $mag_data = array('magazine_id' => $magazine_id, 'password' => $magPassword, 'code_type' => '1');
                $sql = $this->db->set('date', 'now()', FALSE)->insert('tbl_magazine_password', $mag_data);

                /* ---END---- */
            }
            }else if($codeType=='2'){

                $magPassword = "";
                $nameChar = "ABCDEFGHIJKLMNOPQRSTYUVWXYZabcdefghijklmnopqrstuvwxyz";
                //echo $no_of_user;
                for ($i = 0; $i < 3; $i++) {

                    $magPassword .= $magazine_id . $nameChar[rand(0, strlen($nameChar))];
                }

                $mag_data = array('magazine_id' => $magazine_id, 'password' => $magPassword, 'code_type' => '2');
                $sql = $this->db->set('date', 'now()', FALSE)->insert('tbl_magazine_password', $mag_data);
                return TRUE;
            }

            return TRUE;
    }
        return FALSE;
    }

    /* CUSTOMER SUJJESTION */

    /**
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * customer array
     * */
    public function customerSuggestionList() {
        $name=  $this->getUserName();
        if($name!=''){
        $sql = $this->db->select()
                ->from('tbl_customer')
                ->where('status','1')
                ->like('user_name',$name,'both')
                ->get();
        
        }else{
        $sql = $this->db->query("SELECT * FROM tbl_customer WHERE status = '1'");
        }
        if ($sql) {
            return $data = $sql->result_array();
        }
        return FALSE;
    }

    /* MAGAZINE ARTICLE LIST */

    /**
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * array
     * */
    public function getMagazineArticleList() {
        $sql_data1='';
        $sql1='';
        $magazine_id = $this->get_id();
        $Title = $this->get_catagory_id();
        $language_id = $this->get_language_id();
        $cat_language = $this->get_article_language(); 
        
        $sql1 .= "SELECT a.id,a.title,a.author,a.status,a.publish_date,a.magazine_id,a.tags,a.publish_to,a.publish_from,a.article_language,
            b.name,c.language , 0 as nr_order
            FROM tbl_magazine_articles as a 
            LEFT JOIN tbl_customer as b ON a.customer_id = b.id 
            LEFT Join tbl_language as c ON            
            a.language_id = c.id WHERE a.status != '4' AND a.status != '3'";
         
        if ($magazine_id != '') {
            $sql1 .= "AND FIND_IN_SET('$magazine_id', a.magazine_id) > 0";
            //$sql1 .="  AND a.category_id LIKE '$catagory_id,%'";
        }
        if ($Title != '') {
            $sql1 .=" AND a.title LIKE '%$Title%'";
        }
        
        
        $sql1 .= " UNION SELECT a.id,a.title,a.author,a.status,a.publish_date,a.magazine_id,a.tags,a.publish_to,a.publish_from,a.article_language,
            b.name,c.language, nr_order
            FROM tbl_new_articles as a 
            LEFT JOIN tbl_customer as b ON a.customer_id = b.id 
            LEFT Join tbl_language as c ON a.language_id = c.id
            JOIN tbl_article_order ao ON( a.id = ao.article_id AND ao.magazine_id = $magazine_id ) 
            WHERE a.status != '4' AND a.status != '3'";
         
        if ($magazine_id != '') {
            $sql1 .= "AND FIND_IN_SET('$magazine_id', a.magazine_id) > 0";
            //$sql1 .="  AND a.category_id LIKE '$catagory_id,%'";
        }
        if ($Title != '') {
            $sql1 .=" AND a.title LIKE '%$Title%'";
        }
        
        

        $sql = $this->db->query($sql1);

        //echo $this->db->last_query(); die;

        /* PAGINATION */
        $config['base_url'] = base_url() . 'index.php/' . 'magazinearticlelist?catagory='.$Title.'&lang='.$language_id.'&rowId='.$magazine_id;
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
        $config['first_link'] = 'First';
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
        $sql_data1 .= "SELECT s.* FROM (SELECT a.source,a.created_by,a.website_url,if(a.article_link='',if(a.website_url='',a.link_url,a.website_url),if(a.article_link='0',if(a.website_url='',a.link_url,a.website_url),a.article_link)) as article_link,a.id,a.title,a.author,a.status,a.publish_date,a.magazine_id,a.tags,a.publish_to,a.publish_from,a.article_language,
            b.name,c.language, 0 as nr_order, 0 as max_order FROM tbl_magazine_articles as a LEFT JOIN tbl_customer as b ON a.customer_id = b.id LEFT Join tbl_language as c ON
            a.article_language = c.id WHERE a.status != '4' AND a.status != '3'";
         
        if ($magazine_id != '') {
            $sql_data1 .= "AND FIND_IN_SET('$magazine_id', a.magazine_id) > 0";
            //$sql_data1 .="  AND a.category_id LIKE '$catagory_id,%'";
        }
        
        if ($Title != '') { 
            $sql_data1 .=" AND a.title LIKE '%$Title%'";
        }
        
        
        $sql_data1 .= " UNION SELECT a.source,a.created_by,a.website_url,if(a.article_link='',if(a.website_url='','',a.website_url),if(a.article_link='0',if(a.website_url='','',a.website_url),a.article_link)) as article_link,a.id,a.title,a.author,a.status,a.publish_date,a.magazine_id,a.tags,a.publish_to,a.publish_from,a.article_language,
            b.name,c.language, ao.nr_order, (select max(nr_order) from tbl_article_order where magazine_id = $magazine_id ) as max_order FROM tbl_new_articles as a LEFT JOIN tbl_customer as b ON a.customer_id = b.id LEFT Join tbl_language as c ON
            a.article_language = c.id 
            LEFT JOIN tbl_article_order ao ON( a.id = ao.article_id AND ao.magazine_id = $magazine_id ) WHERE a.status != '4' AND a.status != '3'";
         
        if ($magazine_id != '') {
            $sql_data1 .= "AND FIND_IN_SET('$magazine_id', a.magazine_id) > 0";
            //$sql_data1 .="  AND a.category_id LIKE '$catagory_id,%'";
        }
        
        if ($Title != '') { 
            $sql_data1 .=" AND a.title LIKE '%$Title%'";
        }
        $sql_data1 .=" ) s";
        

        $sql_data1 .=" ORDER BY s.nr_order asc  ";
        
        $sql_data = $this->db->query($sql_data1);
//        echo $this->db->last_query(); die;
        if ($sql_data->num_rows() < 1) {
            return FALSE;
        } else {
            //return $data = $sql->result_array();            
            $data = $sql_data->result_array();
            foreach ($data as $key => $value) {
               // echo"<pre>"; print_r($value); 

                $magazine_id = $value['magazine_id'];
                $implode = explode(',', $magazine_id);
                $magazine_list = implode(",", $implode);
                //echo $category_list; die;                
              
                $sql1 = $this->db->query("SELECT title as category_name FROM tbl_magazine WHERE id IN ($magazine_list)");
                
                //$sql1 = $this->db->query("SELECT category_name FROM tbl_category WHERE id IN ($category_list)");
                $nameArray = $sql1->result_array();
                $data[$key]['categoy_name'] = $nameArray;
                $article_id = $value['id'];
                $sql2 = $this->db->query("SELECT id FROM tbl_magazine_article_comment WHERE article_id = '$article_id' AND status='1'");
                $data[$key]['comments_count'] = $sql2->num_rows();
            }
//            echo "<pre>";
//                        print_r($data);die;
            return $data;
        }
    }

    /* Published Article list */

    public function getMagazinePublishedArticleList() {
        $sql_data1='';
        $sql1='';
        $magazine_id = $this->get_id();
        $Title = $this->get_catagory_id();
        $language_id = $this->get_language_id();
        $cat_language = $this->get_article_language(); 
        
        $sql1 .= "SELECT a.source,a.id,a.title,a.author,a.status,a.publish_date,a.magazine_id,a.tags,a.publish_to,a.publish_from,a.article_language,
            b.name,c.language FROM tbl_magazine_articles as a LEFT JOIN tbl_customer as b ON a.customer_id = b.id LEFT Join tbl_language as c ON
            a.language_id = c.id WHERE a.status='2'";
         
        if ($magazine_id != '') {
            $sql1 .= "AND FIND_IN_SET('$magazine_id', a.magazine_id) > 0";
            //$sql1 .="  AND a.category_id LIKE '$catagory_id,%'";
        }
        if ($Title != '') {
            $sql1 .=" AND a.title LIKE '%$Title%'";
        }
        
        
        $sql1 .= " UNION SELECT a.source,a.id,a.title,a.author,a.status,a.publish_date,a.magazine_id,a.tags,a.publish_to,a.publish_from,a.article_language,
            b.name,c.language FROM tbl_new_articles as a LEFT JOIN tbl_customer as b ON a.customer_id = b.id LEFT Join tbl_language as c ON
            a.language_id = c.id WHERE a.status='2'";
         
        if ($magazine_id != '') {
            $sql1 .= "AND FIND_IN_SET('$magazine_id', a.magazine_id) > 0";
            //$sql1 .="  AND a.category_id LIKE '$catagory_id,%'";
        }
        if ($Title != '') {
            $sql1 .=" AND a.title LIKE '%$Title%'";
        }
        
        

        $sql = $this->db->query($sql1);

        //echo $this->db->last_query(); die;

        /* PAGINATION */
        $config['base_url'] = base_url() . 'index.php/' . 'magazinearticlelist?catagory='.$Title.'&lang='.$language_id.'&rowId='.$magazine_id;
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
        $config['first_link'] = 'First';
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
        $sql_data1 .= "SELECT s.* FROM (SELECT a.source,a.created_by,a.website_url,a.article_link,a.id,a.title,a.author,a.status,a.publish_date,a.magazine_id,a.tags,a.publish_to,a.publish_from,a.article_language,
            b.name,c.language FROM tbl_magazine_articles as a LEFT JOIN tbl_customer as b ON a.customer_id = b.id LEFT Join tbl_language as c ON
            a.article_language = c.id WHERE a.status='2'";
         
        if ($magazine_id != '') {
            $sql_data1 .= "AND FIND_IN_SET('$magazine_id', a.magazine_id) > 0";
            //$sql_data1 .="  AND a.category_id LIKE '$catagory_id,%'";
        }
        
        if ($Title != '') { 
            $sql_data1 .=" AND a.title LIKE '%$Title%'";
        }
        
        
        $sql_data1 .= " UNION SELECT a.source,a.created_by,a.website_url,a.article_link,a.id,a.title,a.author,a.status,a.publish_date,a.magazine_id,a.tags,a.publish_to,a.publish_from,a.article_language,
            b.name,c.language FROM tbl_new_articles as a LEFT JOIN tbl_customer as b ON a.customer_id = b.id LEFT Join tbl_language as c ON
            a.article_language = c.id WHERE a.status='2'";
         
        if ($magazine_id != '') {
            $sql_data1 .= "AND FIND_IN_SET('$magazine_id', a.magazine_id) > 0";
            //$sql_data1 .="  AND a.category_id LIKE '$catagory_id,%'";
        }
        
        if ($Title != '') { 
            $sql_data1 .=" AND a.title LIKE '%$Title%'";
        }
        $sql_data1 .=" ) s";
        

        $sql_data1 .=" ORDER BY s.publish_date DESC LIMIT $limitPage,10 ";
        
        $sql_data = $this->db->query($sql_data1);
        //echo $this->db->last_query(); die;
        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            //return $data = $sql->result_array();            
            $data = $sql_data->result_array();
            foreach ($data as $key => $value) {
               // echo"<pre>"; print_r($value); 

                $category_id = $value['magazine_id'];
                $implode = explode(',', $category_id);
                $category_list = implode(",", $implode);
                //echo $category_list; die;                
                
                $sql1 = $this->db->query("SELECT title as category_name FROM tbl_magazine WHERE id IN ($category_list)");
                
                //$sql1 = $this->db->query("SELECT category_name FROM tbl_category WHERE id IN ($category_list)");
                $nameArray = $sql1->result_array();
                $data[$key]['categoy_name'] = $nameArray;
                $article_id = $value['id'];
                $sql2 = $this->db->query("SELECT id FROM tbl_magazine_article_comment WHERE article_id = '$article_id' AND status='1'");
                $data[$key]['comments_count'] = $sql2->num_rows();
            }
            return $data;
        }
    }

    /* Magazine DELETED Article list */

    public function getMagazineDeletedArticleList() {
        $sql_data1='';
        $sql1='';
        $magazine_id = $this->get_id();
        $Title = $this->get_catagory_id();
        $language_id = $this->get_language_id();
        $cat_language = $this->get_article_language(); 
        
        $sql1 .= "SELECT a.id,a.title,a.author,a.status,a.publish_date,a.magazine_id,a.tags,a.publish_to,a.publish_from,a.article_language,
            b.name,c.language FROM tbl_magazine_articles as a LEFT JOIN tbl_customer as b ON a.customer_id = b.id LEFT Join tbl_language as c ON
            a.language_id = c.id WHERE a.status='0'";
         
        if ($magazine_id != '') {
            $sql1 .= "AND FIND_IN_SET('$magazine_id', a.magazine_id) > 0";
            //$sql1 .="  AND a.category_id LIKE '$catagory_id,%'";
        }
        if ($Title != '') {
            $sql1 .=" AND a.title LIKE '%$Title%'";
        }
        
        
        $sql1 .= " UNION SELECT a.id,a.title,a.author,a.status,a.publish_date,a.magazine_id,a.tags,a.publish_to,a.publish_from,a.article_language,
            b.name,c.language FROM tbl_new_articles as a LEFT JOIN tbl_customer as b ON a.customer_id = b.id LEFT Join tbl_language as c ON
            a.language_id = c.id WHERE a.status='0'";
         
        if ($magazine_id != '') {
            $sql1 .= "AND FIND_IN_SET('$magazine_id', a.magazine_id) > 0";
            //$sql1 .="  AND a.category_id LIKE '$catagory_id,%'";
        }
        if ($Title != '') {
            $sql1 .=" AND a.title LIKE '%$Title%'";
        }
        
        

        $sql = $this->db->query($sql1);

        //echo $this->db->last_query(); die;

        /* PAGINATION */
        $config['base_url'] = base_url() . 'index.php/' . 'magazinearticlelist?catagory='.$Title.'&lang='.$language_id.'&rowId='.$magazine_id;
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
        $config['first_link'] = 'First';
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
        $sql_data1 .= "SELECT s.* FROM (SELECT a.source,a.created_by,a.website_url,a.article_link,a.id,a.title,a.author,a.status,a.publish_date,a.magazine_id,a.tags,a.publish_to,a.publish_from,a.article_language,
            b.name,c.language FROM tbl_magazine_articles as a LEFT JOIN tbl_customer as b ON a.customer_id = b.id LEFT Join tbl_language as c ON
            a.article_language = c.id WHERE a.status='0'";
         
        if ($magazine_id != '') {
            $sql_data1 .= "AND FIND_IN_SET('$magazine_id', a.magazine_id) > 0";
            //$sql_data1 .="  AND a.category_id LIKE '$catagory_id,%'";
        }
        
        if ($Title != '') { 
            $sql_data1 .=" AND a.title LIKE '%$Title%'";
        }
        
        
        $sql_data1 .= " UNION SELECT a.source,a.created_by,a.website_url,a.article_link,a.id,a.title,a.author,a.status,a.publish_date,a.magazine_id,a.tags,a.publish_to,a.publish_from,a.article_language,
            b.name,c.language FROM tbl_new_articles as a LEFT JOIN tbl_customer as b ON a.customer_id = b.id LEFT Join tbl_language as c ON
            a.article_language = c.id WHERE a.status='0'";
         
        if ($magazine_id != '') {
            $sql_data1 .= "AND FIND_IN_SET('$magazine_id', a.magazine_id) > 0";
            //$sql_data1 .="  AND a.category_id LIKE '$catagory_id,%'";
        }
        
        if ($Title != '') { 
            $sql_data1 .=" AND a.title LIKE '%$Title%'";
        }
        $sql_data1 .=" ) s";
        

        $sql_data1 .=" ORDER BY s.publish_date DESC LIMIT $limitPage,10 ";
        
        $sql_data = $this->db->query($sql_data1);
        //echo $this->db->last_query(); die;
        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            //return $data = $sql->result_array();            
            $data = $sql_data->result_array();
            foreach ($data as $key => $value) {
               // echo"<pre>"; print_r($value); 

                $category_id = $value['magazine_id'];
                $implode = explode(',', $category_id);
                $category_list = implode(",", $implode);
              
                $sql1 = $this->db->query("SELECT title as category_name FROM tbl_magazine WHERE id IN ($category_list)");
                
                //$sql1 = $this->db->query("SELECT category_name FROM tbl_category WHERE id IN ($category_list)");
                $nameArray = $sql1->result_array();
                $data[$key]['categoy_name'] = $nameArray;
                $article_id = $value['id'];
                $sql2 = $this->db->query("SELECT id FROM tbl_magazine_article_comment WHERE article_id = '$article_id' AND status='1'");
                $data[$key]['comments_count'] = $sql2->num_rows();
            }
            return $data;
        }
    }

    /* Magazine Review Article list */

    public function getMagazineReviewArticleList() {
        $sql_data1='';
        $sql1='';
        $magazine_id = $this->get_id();
        $Title = $this->get_catagory_id();
        $language_id = $this->get_language_id();
        $cat_language = $this->get_article_language(); 
        
        $sql1 .= "SELECT a.id,a.title,a.author,a.status,a.publish_date,a.magazine_id,a.tags,a.publish_to,a.publish_from,a.article_language,
            b.name,c.language FROM tbl_magazine_articles as a LEFT JOIN tbl_customer as b ON a.customer_id = b.id LEFT Join tbl_language as c ON
            a.language_id = c.id WHERE a.status='1'";
         
        if ($magazine_id != '') {
            $sql1 .= "AND FIND_IN_SET('$magazine_id', a.magazine_id) > 0";
            //$sql1 .="  AND a.category_id LIKE '$catagory_id,%'";
        }
        if ($Title != '') {
            $sql1 .=" AND a.title LIKE '%$Title%'";
        }
        
        
        $sql1 .= " UNION SELECT a.id,a.title,a.author,a.status,a.publish_date,a.magazine_id,a.tags,a.publish_to,a.publish_from,a.article_language,
            b.name,c.language FROM tbl_new_articles as a LEFT JOIN tbl_customer as b ON a.customer_id = b.id LEFT Join tbl_language as c ON
            a.language_id = c.id WHERE a.status='1'";
         
        if ($magazine_id != '') {
            $sql1 .= "AND FIND_IN_SET('$magazine_id', a.magazine_id) > 0";
            //$sql1 .="  AND a.category_id LIKE '$catagory_id,%'";
        }
        if ($Title != '') {
            $sql1 .=" AND a.title LIKE '%$Title%'";
        }
        
        

        $sql = $this->db->query($sql1);

        //echo $this->db->last_query(); die;

        /* PAGINATION */
        $config['base_url'] = base_url() . 'index.php/' . 'magazinearticlelist?catagory='.$Title.'&lang='.$language_id.'&rowId='.$magazine_id;
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
        $config['first_link'] = 'First';
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
        $sql_data1 .= "SELECT s.* FROM (SELECT a.source,a.created_by,a.website_url,a.article_link,a.id,a.title,a.author,a.status,a.publish_date,a.magazine_id,a.tags,a.publish_to,a.publish_from,a.article_language,
            b.name,c.language FROM tbl_magazine_articles as a LEFT JOIN tbl_customer as b ON a.customer_id = b.id LEFT Join tbl_language as c ON
            a.article_language = c.id WHERE a.status='1'";
         
        if ($magazine_id != '') {
            $sql_data1 .= "AND FIND_IN_SET('$magazine_id', a.magazine_id) > 0";
            //$sql_data1 .="  AND a.category_id LIKE '$catagory_id,%'";
        }
        
        if ($Title != '') { 
            $sql_data1 .=" AND a.title LIKE '%$Title%'";
        }
        
        
        $sql_data1 .= " UNION SELECT a.source,a.created_by,a.website_url,a.article_link,a.id,a.title,a.author,a.status,a.publish_date,a.magazine_id,a.tags,a.publish_to,a.publish_from,a.article_language,
            b.name,c.language FROM tbl_new_articles as a LEFT JOIN tbl_customer as b ON a.customer_id = b.id LEFT Join tbl_language as c ON
            a.article_language = c.id WHERE a.status='1'";
         
        if ($magazine_id != '') {
            $sql_data1 .= "AND FIND_IN_SET('$magazine_id', a.magazine_id) > 0";
            //$sql_data1 .="  AND a.category_id LIKE '$catagory_id,%'";
        }
        
        if ($Title != '') { 
            $sql_data1 .=" AND a.title LIKE '%$Title%'";
        }
        $sql_data1 .=" ) s";
        

        $sql_data1 .=" ORDER BY s.publish_date DESC LIMIT $limitPage,10 ";
        
        $sql_data = $this->db->query($sql_data1);
        //echo $this->db->last_query(); die;
        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            //return $data = $sql->result_array();            
            $data = $sql_data->result_array();
            foreach ($data as $key => $value) {
               // echo"<pre>"; print_r($value); 

                $category_id = $value['magazine_id'];
                $implode = explode(',', $category_id);
                $category_list = implode(",", $implode);
               
                $sql1 = $this->db->query("SELECT title as category_name FROM tbl_magazine WHERE id IN ($category_list)");
                
                //$sql1 = $this->db->query("SELECT category_name FROM tbl_category WHERE id IN ($category_list)");
                $nameArray = $sql1->result_array();
                $data[$key]['categoy_name'] = $nameArray;
                $article_id = $value['id'];
                $sql2 = $this->db->query("SELECT id FROM tbl_magazine_article_comment WHERE article_id = '$article_id' AND status='1'");
                $data[$key]['comments_count'] = $sql2->num_rows();
            }
            return $data;
        }
    }

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

    /** GET MAGAZINE
     * title
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * listing of language 
     * */
    public function getMagazinetitle() {

        $magazine_id = $this->get_id();

        $sql = $this->db->query("SELECT title FROM tbl_magazine WHERE id = '$magazine_id' ");

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->row_array();
        }
    }

    /* DELETE MAGAZINE ARTICLE */

    /**
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * language
     * */
    public function deleteMagazineArticle() {

        $article_id = $this->get_id();
        $source=  $this->getSource();
        if($source=='admin'){
            $sql = $this->db->query("UPDATE tbl_new_articles SET status='0' WHERE id ='$article_id'");
        }elseif ($source=='customer') {
            $sql = $this->db->query("UPDATE tbl_magazine_articles SET status='0' WHERE id ='$article_id'");
        }else{
        $sql = $this->db->query("UPDATE tbl_magazine_articles SET status='0' WHERE id ='$article_id'");
        }
        
        return TRUE;
    }

    /* DELETE MAGAZINE */

    /**
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * language
     * */
    public function deleteMagazine() {

        $magazine_id = $this->get_id();

        $sql = $this->db->query("UPDATE tbl_magazine SET status='2',badge_read_status='0' WHERE id ='$magazine_id'");

            /* REMOVE ALL ARTICLE IN THIS MAGAZINE */
            $sql1 = $this->db->query("UPDATE tbl_magazine_articles SET status='0',publish_date=now() WHERE magazine_id ='$magazine_id'");
            return TRUE;
       
    }

    /* GET CATAGORY LIST */

    /**
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * array
     * */
     public function getCatagoryList() {

        $language_id = $this->get_language_id();
        if ($language_id != "") {

            if ($language_id == '1') {
                $sql = $this->db->query("select id,category_name,status,created_date from tbl_category where status='1' AND category_name !=''  ORDER BY category_name");
            } else if ($language_id == '2') {
                $sql = $this->db->query("select id,category_name_portuguese as category_name,status,created_date from tbl_category where status='1' AND category_name_portuguese !='' ORDER BY category_name");
            } else if ($language_id == '3') {
                $sql = $this->db->query("select id,category_name_spanish as category_name,status,created_date from tbl_category where status='1' AND category_name_spanish !='' ORDER BY category_name");
            }            
        } else {
            $sql = $this->db->query("select id,category_name,status,created_date from tbl_category where status='1' AND category_name !=''  ORDER BY category_name");            
        }

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->result_array();
        }
    }
   /* public function getCatagoryList() {

        $language_id = $this->get_language_id();
        if ($language_id != "") {
            $sql = $this->db->query("SELECT a.id,a.category_name FROM tbl_category as a INNER JOIN tbl_language as b
                ON a.language = b.id WHERE a.status = '1' AND a.language=$language_id ORDER BY a.id DESC");
        } else {
            $sql = $this->db->query("SELECT a.id,a.category_name FROM tbl_category as a INNER JOIN tbl_language as b
                ON a.language = b.id WHERE a.status = '1' ORDER BY a.id DESC");
        }

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->result_array();
        }
    } */

    /* GET MAGAZINE ALL ARTICLE */

    /**
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * count
     * */
    public function getMagazineAllArticleCount() {

        $magazine_id = $this->get_id();

        $sql = $this->db->query("SELECT id FROM tbl_magazine_articles WHERE FIND_IN_SET('$magazine_id', magazine_id) > 0 AND status != '4' AND status != '3' UNION ALL SELECT id FROM tbl_new_articles WHERE FIND_IN_SET('$magazine_id', magazine_id) > 0 AND status != '4' AND status != '3'");
        //echo $this->db->last_query();die;
        return $data = $sql->num_rows();
//        if ($sql->num_rows() < 1) {
//            return FALSE;
//        } else {
//            return $data = $sql->num_rows();
//        }
    }

    /* GET PUBLISH ARTICLE */

    /**
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * count
     * */
    public function getMagazinePublishArticle() {

        $magazine_id = $this->get_id();

        $sql = $this->db->query("SELECT id FROM tbl_magazine_articles WHERE FIND_IN_SET('$magazine_id', magazine_id) > 0 AND status='2' UNION ALL SELECT id FROM tbl_new_articles WHERE FIND_IN_SET('$magazine_id', magazine_id) > 0 AND status='2' ");
        return $data = $sql->num_rows();
//        if ($sql->num_rows() < 1) {
//            return FALSE;
//        } else {
//            return $data = $sql->num_rows();
//        }
    }

    /* GET DELETED ARTICLE */

    /**
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * count
     * */
    public function getMagazineDeletedArticle() {

        $magazine_id = $this->get_id();

        $sql = $this->db->query("SELECT id FROM tbl_magazine_articles WHERE FIND_IN_SET('$magazine_id', magazine_id) > 0 AND status='0' UNION ALL SELECT id FROM tbl_new_articles WHERE FIND_IN_SET('$magazine_id', magazine_id) > 0 AND status='0' ");
        return $data = $sql->num_rows();
//        if ($sql->num_rows() < 1) {
//            return FALSE;
//        } else {
//            return $data = $sql->num_rows();
//        }
    }

    /* REVIEW ARTICLE */

    /**
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * true/false
     * */
    public function getMagazineReviewArticle() {

        $magazine_id = $this->get_id();

        $sql = $this->db->query("SELECT id FROM tbl_magazine_articles WHERE FIND_IN_SET('$magazine_id', magazine_id) > 0 AND status='1' UNION ALL SELECT id FROM tbl_new_articles WHERE FIND_IN_SET('$magazine_id', magazine_id) > 0 AND status='1' ");
        return $data = $sql->num_rows();
//        if ($sql->num_rows() < 1) {
//            return FALSE;
//        } else {
//            return $data = $sql->num_rows();
//        }
    }

    /* GET ARTICLE DETAILS */

    /**
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * array
     * */
    public function GetMagazineArticleDetails() {

        $article_id = $this->get_id();
        if($this->getSource()=='admin'){
        $sql = $this->db->query("SELECT a.id,a.title,a.author,a.status,a.publish_date,a.category_id,a.tags,a.image,a.publish_from,publish_to,a.article_language,
            b.name,c.language,d.title as mag_title,a.description FROM tbl_new_articles as a LEFT JOIN tbl_customer as b ON a.customer_id = b.id LEFT Join tbl_language as c ON
            a.language_id = c.id LEFT JOIN tbl_magazine as d ON a.magazine_id=d.id 
            WHERE a.id = '$article_id'");
        }else if($this->getSource()=='customer'){
        $sql = $this->db->query("SELECT a.id,a.title,a.author,a.status,a.publish_date,a.category_id,a.tags,a.image,a.publish_from,publish_to,a.article_language,
            b.name,c.language,d.title as mag_title,a.description FROM tbl_magazine_articles as a LEFT JOIN tbl_customer as b ON a.customer_id = b.id LEFT Join tbl_language as c ON
            a.language_id = c.id LEFT JOIN tbl_magazine as d ON a.magazine_id=d.id 
            WHERE a.id = '$article_id'");
        }else{
        $sql = $this->db->query("SELECT a.id,a.title,a.author,a.status,a.publish_date,a.category_id,a.tags,a.image,a.publish_from,publish_to,a.article_language,
            b.name,c.language,d.title as mag_title,a.description FROM tbl_magazine_articles as a LEFT JOIN tbl_customer as b ON a.customer_id = b.id LEFT Join tbl_language as c ON
            a.language_id = c.id LEFT JOIN tbl_magazine as d ON a.magazine_id=d.id 
            WHERE a.id = '$article_id'");
        }
//echo $this->db->last_query(); die;
        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {

            $data = $sql->row_array();

            $category_id = $data['category_id'];
            $implode = explode(',', $category_id);
            $category_list = implode(",", $implode);
            //echo $category_list; die;
            $sql1 = $this->db->query("SELECT category_name FROM tbl_category WHERE id IN ($category_list)");
            $nameArray = $sql1->result_array();
            $data['categoy_name'] = $nameArray;

            return $data;
        }
    }

    /* Approve magazine article */

    /**
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * true/false
     * */
    public function ApproveMagazineArticle() {

        $article_id = $this->get_id();
        if($this->getSource()=='admin'){
        $sql = $this->db->query("UPDATE tbl_new_articles SET status='2',publish_date=now() WHERE id ='$article_id'");    
        }else if($this->getSource()=='customer'){
        $sql = $this->db->query("UPDATE tbl_magazine_articles SET status='2',publish_date=now() WHERE id ='$article_id'");    
        }else{
        $sql = $this->db->query("UPDATE tbl_magazine_articles SET status='2',publish_date=now() WHERE id ='$article_id'");
        }

        if ($this->db->affected_rows() < 1) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /* DECLINE MAGAZINE ARTICLE */
    /*     * this method is used as update 
     * article status and inset
     * rejection report in rejection tbl     
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * true/false
     * */

    public function DeclineMagazineArticle() {

        $article_id = $this->get_article_id();
        $rejection_report = $this->get_article_report();
        $sql = $this->db->query("UPDATE tbl_magazine_articles SET status='3', publish_date=now() WHERE id ='$article_id'");
        if ($sql) {
            //return TRUE;
            $data = array('article_id' => $article_id, 'article_report' => $rejection_report);
            $sql_rejection = $this->db->set('created_date', 'now()', FALSE)->insert('tbl_article_decline', $data);
            return true;
        }
    }
    
    
    /**
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * True/false 
     * */
    public function UpdateCustomerMagazine() {        
        $cover_image = $this->get_cover_image();
        $customer_logo = $this->get_customer_logo();
        $magazine_id = $this->get_id();
        $bar_color = $this->get_bar_color(); 
        
        if($cover_image !=""){
          $sql = $this->db->query("UPDATE tbl_magazine SET cover_image='$cover_image' WHERE id ='$magazine_id'");          
        }
        
        if($customer_logo !=""){
            $sql = $this->db->query("UPDATE tbl_magazine SET customer_logo='$customer_logo' WHERE id ='$magazine_id'");
            
        }
        
        if($bar_color !==""){
           $sql = $this->db->query("UPDATE tbl_magazine SET bar_color='$bar_color' WHERE id ='$magazine_id'");
        }
        
        $no_of_user = $this->get_no_of_user();          
        //echo $this->db->last_query(); die;       

            /* ---GENERATE RANDOM PASSWORD FOR CUSTOMER MAGAZINE---- */
            for ($loop = 0; $loop < intVal($no_of_user); $loop++) {
                $magPassword = "";
                $nameChar = "ABCDEFGHIJKLMNOPQRSTYUVWXYZabcdefghijklmnopqrstuvwxyz";
                //echo $no_of_user;
                for ($i = 0; $i < 3; $i++) {

                    $magPassword .= $magazine_id.'_'.$nameChar[rand(0, strlen($nameChar))];
                }

                $mag_data = array('magazine_id' => $magazine_id, 'password' => $magPassword);
                $sql = $this->db->set('date', 'now()', FALSE)->insert('tbl_magazine_password', $mag_data);

                /* ---END---- */
            }

            return TRUE;
       
    }
    
    public function getMagazineDetail(){
        
      $magazine_id = $this->get_id();

        $sql = $this->db->query("SELECT * FROM tbl_magazine WHERE id = '$magazine_id' ");

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->row_array();
        }
        
    }
    
    public function getMagazineDetailAjax(){
        
      $magazine_id = $this->get_id();

        $sql = $this->db->query("SELECT cover_image,customer_logo,magazine_images FROM tbl_magazine WHERE id = '$magazine_id' ");

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->row_array();
        }
        
    }
    
    public function getMagazineArticleFilter(){

        $customer_id = $this->get_id();

        $sql = "SELECT a.id,a.title FROM tbl_magazine_articles as a LEFT JOIN tbl_customer as b ON a.customer_id = b.id 
                WHERE a.status != '4' AND a.status != '3' ";

        if( !empty( $customer_id ) ){
            $sql .= " AND b.id = $customer_id ";
        }

        $sql .= " ORDER BY a.title";

        $sqlData = $this->db->query($sql);

        if ($sqlData->num_rows() < 1) {
            return false;
        } else {
            $data = $sqlData->result_array();
            return $data;
        }

    }

    public function getMagazineUsersFilter(){

        $magazineId = $this->get_id();
        $customerId = $this->get_customer_id();

        $sql = "SELECT u.id, CONCAT(u.name, ' (', IF(u.email IS NULL, '', u.email) , ')') as title 
                FROM tbl_user_magazines um 
                JOIN tbl_users u ON um.user_id = u.id 
                JOIN tbl_magazine m ON( um.magazine_id = m.id AND m.customer_id = $customerId )
                WHERE ";

        if( !empty( $magazineId ) ){
            $sql .= " um.magazine_id = $magazineId AND ";
        }

        $sql .= " u.name != '' 
                GROUP BY u.id ORDER BY u.name";

        $sqlData = $this->db->query($sql);

        if ($sqlData->num_rows() < 1) {
            return false;
        } else {
            $data = $sqlData->result_array();
            return $data;
        }

    }

    public function getCustomerMagazinesFilter($customerId){

        $sql = "SELECT
                    id, title
                FROM
                    tbl_magazine
                WHERE 
                  1 = 1 ";

        if( !empty($customerId) ){
            $sql .= " AND customer_id = $customerId ";
        }

        $sql .= " AND status = '1' ORDER BY title";

        $sqlData = $this->db->query($sql);

        if ($sqlData->num_rows() < 1) {
            return false;
        } else {
            $data = $sqlData->result_array();
            return $data;
        }

    }

    public function getSignageArticle($idCampaign, $item){

        $sql = "SELECT
                    ma.id, ma.title, image, description_without_html, branch_link, video_path, ma.publish_date
                FROM
                    tbl_customer_campaign_content ccc
                    JOIN tbl_magazine_articles ma ON( ccc.id_article = ma.id )
                    JOIN tbl_magazine m ON( m.id IN( ma.magazine_id ) AND m.status = '1' AND m.publish_date_to >= CURDATE() )
                WHERE 
                  ccc.id_campaign = $idCampaign
                LIMIT
                  $item, 1";

        $sqlData = $this->db->query($sql);

        if ($sqlData->num_rows() < 1) {
            return false;
        } else {
            $data = $sqlData->result_array();
            return $data;
        }

    }

    public function getArticlesByMagazineFilter($magazineId = "", $customerId = ""){

        $sql = "SELECT
                    ma.id, ma.title, image, description_without_html, branch_link
                FROM
                    tbl_new_articles ma
                    JOIN tbl_magazine m ON( m.id IN( ma.magazine_id ) AND m.status = '1' )
                WHERE 1=1 ";

        if( !empty( $customerId ) ){
            $sql .= " AND m.customer_id = $customerId ";
        }

        if( !empty( $magazineId ) ){
            $sql .= " AND FIND_IN_SET('$magazineId', ma.magazine_id) > 0 ";
        }

        $sql .= " AND ma.status = '2'
                ORDER BY
                    ma.title";

        $sqlData = $this->db->query($sql);

        if ($sqlData->num_rows() < 1) {
            return false;
        } else {
            $data = $sqlData->result_array();
            return $data;
        }

    }

    public function getMagazineCode($id){
        $sql = "SELECT * FROM tbl_magazine_password WHERE id = $id";
        $sqlData = $this->db->query($sql);
        return $sqlData->row();
    }

    public function updateMagazineCode($magazineCodeId, $newCodeValue){
        $this->db->query("UPDATE tbl_magazine_password SET password = '$newCodeValue' WHERE id = $magazineCodeId");
    }

    public function checkUniqueCode($id, $code){

        $sql = "SELECT * FROM tbl_magazine_password WHERE binary password = '$code' AND id != $id";

        $sqlData = $this->db->query($sql);

        return $sqlData->num_rows() < 1;

    }

    public function getCampaign($campaignId){

        $sql = "SELECT 
                    * 
                FROM 
                    tbl_customer_campaign_counter ccc
                    JOIN tbl_customer_campaign cc ON( ccc.campaign_id = cc.id )
                WHERE 
                    ccc.campaign_id = $campaignId";

        $sqlData = $this->db->query($sql);
        $itemCounter = $sqlData->row();

        if( empty($itemCounter) ){
            $this->db->query("INSERT INTO tbl_customer_campaign_counter (campaign_id, counter) VALUES( $campaignId, 0 );");
            return $this->getCampaign($campaignId);
        }

        return $itemCounter;

    }

    public function getAccessGroups(){

        $sql = "SELECT 
                    * 
                FROM 
                    tbl_group";

        $sqlData = $this->db->query($sql);

        $data = $sqlData->result_array();
        return $data;

    }

    public function getMagazineAccessGroups($magazineId){

        $sql = "SELECT 
                    group_id
                FROM 
                    tbl_magazine_group
                WHERE
                    magazine_id = " . $magazineId;

        $sqlData = $this->db->query($sql);

        $data = $sqlData->result_array();
        return $data;

    }

    public function saveAccessGroup($magazineId, $groups){

        $this->db->query("DELETE FROM tbl_magazine_group WHERE magazine_id = " . $magazineId);

        foreach( $groups as $group ){
            $this->db->query("INSERT INTO tbl_magazine_group (magazine_id, group_id) VALUES( $magazineId, $group );");
        }

    }

//    public function getItemCounter($campaignId, $layout, $item){
//        $sql = "SELECT * FROM tbl_magazine_article_campaign WHERE campaign = $campaignId AND layout = $layout AND item = $item";
//
//        $sqlData = $this->db->query($sql);
//        $itemCounter = $sqlData->row();
//
//        if( empty($itemCounter) ){
//            $this->db->query("INSERT INTO tbl_magazine_article_campaign (campaign, item, layout, counter) VALUES( $campaignId, $item, $layout, $item );");
//            return $this->getItemCounter($campaignId, $layout, $item);
//        }
//
//        return $itemCounter;
//    }

    public function updateItemCounter($campaignId, $counter){
        $this->db->query("UPDATE tbl_customer_campaign_counter SET counter = $counter WHERE campaign_id = $campaignId");
    }

    public function saveMagazineLocations($locations){

        $magazineId = $this->get_id();

        $this->db->query("DELETE FROM tbl_magazine_location WHERE id_magazine = " . $magazineId);

        foreach( $locations as $location ){
            $this->db->query("INSERT INTO tbl_magazine_location (id_magazine, country) VALUES( $magazineId, '$location' );");
        }

    }

    public function getMagazineCountries(){

        $sql = $this->db->query("select country FROM tbl_user_location GROUP BY country ORDER BY country");

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->result_array();
        }

    }

    public function changeArticleOrder( $articleId, $magazineId, $oldOrder, $newOrder ){

        if( $oldOrder > $newOrder ){

            for( $i = $oldOrder - 1; $i >= $newOrder; $i-- ){
                $modifiedOrder = $i + 1;
                $this->db->query("UPDATE tbl_article_order SET nr_order = $modifiedOrder WHERE magazine_id = $magazineId AND nr_order = $i");
            }

        } else {

            for( $i = $oldOrder + 1; $i <= $newOrder; $i++ ){
                $modifiedOrder = $i - 1;
                $this->db->query("UPDATE tbl_article_order SET nr_order = $modifiedOrder WHERE magazine_id = $magazineId AND nr_order = $i");
            }

        }

        $this->db->query("UPDATE tbl_article_order SET nr_order = $newOrder WHERE article_id = $articleId AND magazine_id = $magazineId AND nr_order = $oldOrder");

    }

}

?>
