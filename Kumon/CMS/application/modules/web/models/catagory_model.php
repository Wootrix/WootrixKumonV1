<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class catagory_model extends CI_Model {
    
    
    private $_tableName = 'tbl_category';
    private $_id = "";
    private $_category_code = "";
    private $_category_name = "";
    private $_category_name_portuguese = "";
    private $_category_name_spanish = "";
    private $_status = "";
    private $_language = "";

    /* GETTER AND SETTER */

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

    public function get_category_code() {
        return $this->_category_code;
    }

    public function set_category_code($_category_code) {
        $this->_category_code = $_category_code;
    }

    public function get_category_name() {
        return $this->_category_name;
    }

    public function set_category_name($_category_name) {
        $this->_category_name = $_category_name;
    }

    public function get_status() {
        return $this->_status;
    }

    public function set_status($_status) {
        $this->_status = $_status;
    }

    public function get_language() {
        return $this->_language;
    }

    public function set_language($_language) {
        $this->_language = $_language;
    }
    
    function getCategory_name_portuguese() {
        return $this->_category_name_portuguese;
    }

    function getCategory_name_spanish() {
        return $this->_category_name_spanish;
    }

    function setCategory_name_portuguese($category_name_portuguese) {
        $this->_category_name_portuguese = $category_name_portuguese;
    }

    function setCategory_name_spanish($category_name_spanish) {
        $this->_category_name_spanish = $category_name_spanish;
    }

    /* CATAGORY LISTING */

    /**
     * THIS ACTION IS USED 
     * catagory listing
     * languagename, num of article
     * in a catagory 
     * @author Techahead
     * @access Public
     * @param 
     * @return array

     * */
    public function getCatagoryList() {
        $language_id = $this->get_language();
        if ($language_id != "") {
            
            if($language_id =='1'){
                $category_name = 'category_name';                
            }elseif($language_id =='2'){
                $category_name = 'category_name_portuguese';                
            }elseif($language_id == '3'){
                $category_name = 'category_name_spanish';                
            }
            $sql = $this->db->query("SELECT a.id,a.category_name,a.category_name_portuguese,a.category_name_spanish FROM tbl_category as a  WHERE a.status = '1' 
                AND a.$category_name !='' ORDER BY a.id DESC");
        } else {
            $sql = $this->db->query("SELECT a.id,a.category_name,a.category_name_portuguese,a.category_name_spanish FROM tbl_category as a  WHERE a.status = '1' 
                AND category_name !='' ORDER BY a.id DESC");
        }
        //echo $this->db->last_query(); 
        /* PAGINATION */
        $config['base_url'] = base_url() . 'index.php/' . 'catagory?lang='.$language_id;
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
        
        if ($language_id != "") {
            
            if($language_id =='1'){
                $category_name = 'category_name';                
            }elseif($language_id =='2'){
                $category_name = 'category_name_portuguese';                
            }elseif($language_id == '3'){
                $category_name = 'category_name_spanish';                
            }
            $sql = $this->db->query("SELECT a.id,a.category_name,a.category_name_portuguese,a.category_name_spanish  FROM tbl_category as a WHERE a.status = '1' 
                AND a.$category_name !='' ORDER BY a.id DESC LIMIT $limitPage,10");
        } else {
            $sql = $this->db->query("SELECT a.id,a.category_name,a.category_name_portuguese,a.category_name_spanish FROM tbl_category as a WHERE a.status = '1' 
                ORDER BY a.id DESC LIMIT $limitPage,10");
        }

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            //return $data = $sql->result_array();
            $data = $sql->result_array();
            foreach ($data as $key => $value) {
                $category_id = $value['id'];
                $query1 = $this->db->query("SELECT id FROM tbl_new_articles WHERE category_id LIKE '%$category_id%'");
                $article_count1 = $query1->num_rows();
                $query2 = $this->db->query("SELECT id FROM tbl_magazine_articles WHERE category_id LIKE '%$category_id%'");
                $article_count2 = $query2->num_rows();
                $article_count = $article_count1 + $article_count2;
                $data[$key]['article_count'] = $article_count;
            }
            return $data;
        }
    }

    /* LANGUAGE LIST */

    /**
     * THIS ACTION IS USED     
     * language list 
     * @author Techahead
     * @access Public
     * @param 
     * @return array

     * */
    public function getLanguageList() {

        $language_sql = $this->db->query("SELECT id,language FROM tbl_language WHERE status ='1' ");
        if ($language_sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $language_sql->result_array();
        }
    }

    /* ADD NEW CATAGORY */

    /**
     * THIS ACTION IS USED     
     * For ADD NEW CATEGORY 
     * @author Techahead
     * @access Public
     * @param 
     * @return array

     * */
    public function addNewCatagory() {
        
        $language_id = $this->get_language();
        $catagoryEnglish = $this->get_category_name();
        $catagorySpanish = $this->getCategory_name_spanish();
        $catagoryPortuguese = $this->getCategory_name_portuguese();
        
        
        //$statusAvailability=$this->CatagoryExistCheck($catagoryEnglish);
        //if($statusAvailability==TRUE){
        $data = array('category_name' => $catagoryEnglish,'category_name_portuguese' => $catagoryPortuguese,'category_name_spanish' => $catagorySpanish);
        $sql = $this->db->set('created_date', 'now()', FALSE)->insert('tbl_category', $data);
        
        if ($sql) {
            return TRUE;
        }
        //}else{ 
            return FALSE;
        //}
    }
    
     /**
     * THIS ACTION IS USED     
     * For ADD NEW CATEGORY 
     * @author Techahead
     * @access Public
     * @param 
     * @return array

     * */
    public function CatagoryExistCheck($language_id,$catagory) {
        if($this->get_language()){
        $language_id = $this->get_language();
        }if($this->get_category_name()){
        $catagory = $this->get_category_name();
        }
        $sql = $this->db->query("SELECT id FROM tbl_category WHERE category_name='$catagory'");
        //echo $this->db->last_query();die;
        if ($sql->num_rows() < 1) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * THIS ACTION IS USED     
     * FOR DELETE A CATEGORY 
     * @author Techahead
     * @access Public
     * @param 
     * @return array

     * */
    public function deleteCatagory() {

        $catagory_id = $this->get_id();
        $sql = $this->db->query("DELETE FROM  tbl_category  WHERE id ='$catagory_id'");

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    
    /*catagory unique ness check*/
    public function checkEnglishLanguage(){
        
        $catagory_name = $this->get_category_name();
        $sql = $this->db->query("SELECT id FROM tbl_category WHERE category_name='$catagory_name'");
        //echo $this->db->last_query();die;
        if ($sql->num_rows() < 1) {
            return TRUE;
        }
        return FALSE;
        
    }
    
    public function checkSpanishLanguage(){
        
         $catagorySpanish = $this->getCategory_name_spanish();
        $sql = $this->db->query("SELECT id FROM tbl_category WHERE category_name_spanish='$catagorySpanish'");
        //echo $this->db->last_query();die;
        if ($sql->num_rows() < 1) {
            return TRUE;
        }
        return FALSE;
        
    }
    
    public function checkPortugeLanguage(){
        $catagoryPortuguese = $this->getCategory_name_portuguese();
        $sql = $this->db->query("SELECT id FROM tbl_category WHERE category_name_portuguese='$catagoryPortuguese'");
        //echo $this->db->last_query();die;
        if ($sql->num_rows() < 1) {
            return TRUE;
        }
        return FALSE;
        
    }

    public function getOpenMagazinesFilterList() {

        $sql = "SELECT id, category_name as title FROM tbl_category WHERE status = '1' ORDER BY category_name";

        $sqlData = $this->db->query($sql);

        if ($sqlData->num_rows() < 1) {
            return false;
        } else {
            $data = $sqlData->result_array();
            return $data;
        }

    }

    public function getCategoryArticlesFilterList($languageId) {

        $categoryId = $this->get_id();

        $sql = "SELECT id, title FROM tbl_new_articles WHERE status = '2' ";

        if( !empty( $categoryId ) ){
            $sql .= " AND FIND_IN_SET($categoryId, category_id) > 0 ";
        }

        if( !empty( $languageId ) ){
            $sql .= " AND article_language = $languageId ";
        }

        $sql .= " AND publish_to >= CURDATE() ORDER BY title";

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
