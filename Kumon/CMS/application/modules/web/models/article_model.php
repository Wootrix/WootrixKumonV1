<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
(defined('BASEPATH')) OR exit('No direct script access allowed');

class article_model extends CI_Model {
    
    
    private $_tableName = 'tbl_new_articles';
    private $_id = "";
    private $_title = "";
    private $_image = "";
    private $_media_type = "";
    private $_catagory_id = "";
    private $_language = "";
    private $_description = "";
    private $_status = "";
    private $_created_date = "";
    private $_magazine_id = "";
    private $_tags = "";
    private $_video_path = "";
    Private $_customer_id = "";
    private $_description_without_html = "";
    private $_allow_comment = "";
    private $_allow_share = "";
    private $_created_by = "";
    private $_publish_from = "";
    private $_publish_to = "";
    private $_article_language = "";
    // wootrix phase 2
    private $_embed_video="";
    private $_via_url="";
    private $_embed_video_thumb="";
            
    function getEmbed_video_thumb() {
        return $this->_embed_video_thumb;
    }

    function setEmbed_video_thumb($embed_video_thumb) {
        $this->_embed_video_thumb = $embed_video_thumb;
    }
    function getVia_url() {
        return $this->_via_url;
    }

    function setVia_url($via_url) {
        $this->_via_url = $via_url;
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

    public function get_image() {
        return $this->_image;
    }

    public function set_image($_image) {
        $this->_image = $_image;
    }

    public function get_media_type() {
        return $this->_media_type;
    }

    public function set_media_type($_media_type) {
        $this->_media_type = $_media_type;
    }

    public function get_catagory_id() {
        return $this->_catagory_id;
    }

    public function set_catagory_id($_catagory_id) {
        $this->_catagory_id = $_catagory_id;
    }

    public function get_language() {
        return $this->_language;
    }

    public function set_language($_language) {
        $this->_language = $_language;
    }

    public function get_description() {
        return $this->_description;
    }

    public function set_description($_description) {
        $this->_description = $_description;
    }

    public function get_status() {
        return $this->_status;
    }

    public function set_status($_status) {
        $this->_status = $_status;
    }

    public function get_created_date() {
        return $this->_created_date;
    }

    public function set_created_date($_created_date) {
        $this->_created_date = $_created_date;
    }

    public function get_magazine_id() {
        return $this->_magazine_id;
    }

    public function set_magazine_id($_magazine_id) {
        $this->_magazine_id = $_magazine_id;
    }

    public function get_tags() {
        return $this->_tags;
    }

    public function set_tags($_tags) {
        $this->_tags = $_tags;
    }

    public function get_video_path() {
        return $this->_video_path;
    }

    public function set_video_path($_video_path) {
        $this->_video_path = $_video_path;
    }

    public function get_customer_id() {
        return $this->_customer_id;
    }

    public function set_customer_id($_customer_id) {
        $this->_customer_id = $_customer_id;
    }

    public function get_description_without_html() {
        return $this->_description_without_html;
    }

    public function set_description_without_html($_description_without_html) {
        $this->_description_without_html = $_description_without_html;
    }

    public function get_allow_comment() {
        return $this->_allow_comment;
    }

    public function set_allow_comment($_allow_comment) {
        $this->_allow_comment = $_allow_comment;
    }

    public function get_allow_share() {
        return $this->_allow_share;
    }

    public function set_allow_share($_allow_share) {
        $this->_allow_share = $_allow_share;
    }

    public function get_created_by() {
        return $this->_created_by;
    }

    public function set_created_by($_created_by) {
        $this->_created_by = $_created_by;
    }

    public function get_publish_from() {
        return $this->_publish_from;
    }

    public function set_publish_from($_publish_from) {
        $this->_publish_from = $_publish_from;
    }

    public function get_publish_to() {
        return $this->_publish_to;
    }

    public function set_publish_to($_publish_to) {
        $this->_publish_to = $_publish_to;
    }

    public function get_article_language() {
        return $this->_article_language;
    }

    public function set_article_language($_article_language) {
        $this->_article_language = $_article_language;
    }
    
    // wootrix phase 2
    
    public function get_embed_video() {
        return $this->_embed_video;
    }

    public function set_embed_video($_embed_video) {
        $this->_embed_video = $_embed_video;
    }
    // end

    
    /* OPEN ARTICLE LIST */
    /*     * get all article list
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Index function load defaut 
     * */

    public function articleList() {

        $language_id = $this->get_language();
        $catagory_id = $this->get_catagory_id();
        $cat_language = $this->get_article_language(); 

        $sql1 = "SELECT a.id,a.title,a.category_id,a.language_id,a.status,a.customer_id,a.tags,b.language,a.article_language
            ,a.created_by FROM tbl_new_articles as a
            LEFT JOIN tbl_language as b ON a.language_id = b.id where a.id!='0' ";

        if ($catagory_id != '') {
            $sql1 .= "AND FIND_IN_SET('$catagory_id', a.category_id) > 0";
            //$sql1 .="  AND a.category_id LIKE '%$catagory_id%'";
        }
        if ($language_id != '') {
            $sql1 .=" AND a.language_id = '$language_id'
                    ";
        }
        if ($cat_language != '') {
           
            $sql1 .=" AND a.article_language = '$cat_language'
                    ";
        }

        $sql = $this->db->query($sql1);

        //echo $this->db->last_query(); 
        /* PAGINATION */
        $config['base_url'] = base_url() . 'index.php/' . 'openarticlelist?catagory=' . $catagory_id . '&lang=' . $language_id;
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

        $sql_data1 = "SELECT a.article_link,a.website_url,a.id,a.title,a.category_id,a.language_id,a.status,a.customer_id,a.tags,b.language,a.article_language
            ,a.created_by,a.author,a.publish_from,a.publish_to, a.magazine_id FROM tbl_new_articles as a
            LEFT JOIN tbl_language as b ON a.language_id = b.id where a.id!='0'";
        if ($catagory_id != '') {
            $sql_data1 .= "AND FIND_IN_SET('$catagory_id', a.category_id) > 0";
            //$sql_data1 .="  AND a.category_id LIKE '%$catagory_id%'";
        }
        if ($language_id != '') {
            $sql_data1 .=" AND a.language_id = '$language_id'";
        }
        if ($cat_language != '') {
            $sql_data1 .=" AND a.article_language = '$cat_language'
                    ";
        }
        $sql_data1 .=" ORDER BY a.publish_date DESC LIMIT $limitPage,10";


        $sql_data = $this->db->query($sql_data1);

        //echo $this->db->last_query();

        if ($sql_data->num_rows() < 0) {
            return FALSE;
        } else {
            //return $data = $sql->result_array();
            $data = $sql_data->result_array();
            foreach ($data as $key => $value) {
                $article_id = $value['id'];
                //echo"<pre>"; print_r($value); die;
                $sql1 = $this->db->query("SELECT id FROM tbl_article_comment WHERE article_id='$article_id'");
                $data[$key]['comment_count'] = $sql1->num_rows();

                $customer_id = $value['customer_id'];
                $sql2 = $this->db->query("SELECT name FROM tbl_admin WHERE id='$customer_id'");
                $result = $sql2->row_array();
                $data[$key]['user_name'] = $result['name'];

                $category_id = $value['category_id'];
                if ($category_id != "") {
                    $implode = explode(',', $category_id);
                    $category_list = implode(",", $implode);
                    /*Language name Logic*/
                    /*if($language_id !=""){
                        if($language_id == '1'){
                            $category_name = 'category_name';
                            
                        }elseif($language_id == '2'){
                            $category_name = 'category_name_portuguese';
                            
                        }elseif($language_id == '3'){
                            $category_name = 'category_name_spanish';
                            
                        }                        
                    }else{
                        if($value['language'] == 'Portuguese'){ 
                           $category_name = 'category_name_portuguese'; 
                        }elseif($value['language'] == 'Spanish'){
                            $category_name = 'category_name_spanish';
                        }else{
                            $category_name = 'category_name';
                        }
                       //$category_name = 'category_name'; 
                    }*/
                    if($value['language'] == 'Portuguese'){ 
                           $category_name = 'category_name_portuguese'; 
                        }elseif($value['language'] == 'Spanish'){
                            $category_name = 'category_name_spanish';
                        }else{
                            $category_name = 'category_name';
                        }
                    $sql3 = $this->db->query("SELECT $category_name as category_name FROM tbl_category WHERE id IN ($category_list) AND $category_name !=''");
                    $nameArray = $sql3->result_array();
                    $data[$key]['category_name'] = $nameArray;
                } else {
                    $data[$key]['category_name'] = "";
                }
            }
            return $data;
        }
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

    /* GET CATEGORY LIST */

    public function getCatagoryList() {

        $language = $this->get_language();
        if ($language != "") {
            if ($language == '1') {
                $sql = $this->db->query("select id,category_name,status,created_date from tbl_category where status='1' AND category_name !=''  ORDER BY category_name");
            } else if ($language == '2') {
                $sql = $this->db->query("select id,category_name_portuguese as category_name,status,created_date from tbl_category where status='1' AND category_name_portuguese !='' ORDER BY category_name");
            } else if ($language == '3') {
                $sql = $this->db->query("select id,category_name_spanish as category_name,status,created_date from tbl_category where status='1' AND category_name_spanish !='' ORDER BY category_name");
            }
            //$sql = $this->db->query("SELECT id,category_name FROM tbl_category WHERE status = '1' AND language='$language' ORDER BY id DESC");
        } else {
            $sql = $this->db->query("SELECT id,category_name FROM tbl_category WHERE status = '1' AND category_name !='' ORDER BY category_name ASC");
        }

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->result_array();
        }
    }

    /* FORM CATAGORY LIST BY DEFAULT SELECTED ENGLISH */

    public function getFormCatagoryList() {
        $language = $this->get_language();
        //$language = '1';
        if ($language != "") {
            if ($language == '1') {
                $res = $this->db->query("select id,category_name,status,created_date from tbl_category where status='1' AND category_name !=''  ORDER BY category_name");
            } else if ($language == '2') {
                $res = $this->db->query("select id,category_name_portuguese as category_name,status,created_date from tbl_category where status='1' AND category_name_portuguese !='' ORDER BY category_name");
            } else if ($language == '3') {
                $res = $this->db->query("select id,category_name_spanish as category_name,status,created_date from tbl_category where status='1' AND category_name_spanish !='' ORDER BY category_name");
            }
            //$sql = $this->db->query("SELECT id,category_name FROM tbl_category WHERE status = '1' AND language='$language' ORDER BY id DESC");
        } else {
            $sql = $this->db->query("SELECT id,category_name FROM tbl_category WHERE status = '1' AND category_name !=''ORDER BY category_name ASC");
        }

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->result_array();
        }
    }

    /* GET ALL ARTICLE */

    public function getAllArticle() {

        $sql = $this->db->query("SELECT id FROM tbl_new_articles");
        return $sql->num_rows();
    }

    /* GET ALL PUBLISH ARTICLE */

    public function getAllPublishArticle() {

        $sql = $this->db->query("SELECT id FROM tbl_new_articles WHERE status='2'");
        return $sql->num_rows();
    }

    /* GET ALL NEW ARTICLE */

    public function getAllNewArticle() {
        $sql = $this->db->query("SELECT id FROM tbl_new_articles WHERE status='1' AND created_by='0'");
        return $sql->num_rows();
    }

    /* GET ALL DELETED ARTICLE */

    public function getAlldeletedArticle() {
        $sql = $this->db->query("SELECT id FROM tbl_new_articles WHERE status='0'");
        return $sql->num_rows();
    }

    /* GET ALL DRAFTED ARTICLE */

    public function getAllDraftedArticle() {

        $sql = $this->db->query("SELECT id FROM tbl_new_articles WHERE status ='1' AND created_by ='1'");

        return $sql->num_rows();
    }

    /* NEW ARTICLE LIST */

    /*     * get all article list
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Index function load defaut 
     * */

    public function getNewArticleList() {

        $language_id = $this->get_language();
        $catagory_id = $this->get_catagory_id();


        $sql1 = "SELECT a.id,a.title,a.category_id,a.language_id,a.status,a.customer_id,a.tags,b.language,
             a.article_language FROM tbl_new_articles as a
            LEFT JOIN tbl_language as b ON a.language_id = b.id WHERE a.status = '1'
            AND a.created_by ='0'";

        if ($catagory_id != '') {
            $sql1 .= "AND FIND_IN_SET('$catagory_id', a.category_id) > 0";
            //$sql1 .="  AND a.category_id LIKE '%$catagory_id%'";
        }
        if ($language_id != '') {
            $sql1 .=" AND a.article_language = '$language_id'
                    ";
        }

        $sql = $this->db->query($sql1);

        //echo $this->db->last_query(); 
        /* PAGINATION */
        $config['base_url'] = base_url() . 'index.php/' . 'getnewarticlelist?catagory=' . $catagory_id . '&lang=' . $language_id;
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

        $sql_data1 = "SELECT a.website_url,a.id,a.title,a.category_id,a.language_id,a.status,a.customer_id,a.tags,b.language 
            ,a.created_by,a.author,a.publish_from,a.publish_to,a.article_language FROM tbl_new_articles as a
            LEFT JOIN tbl_language as b ON a.language_id = b.id WHERE a.status = '1' 
            AND a.created_by ='0'";
        if ($catagory_id != '') {
            $sql_data1 .= "AND FIND_IN_SET('$catagory_id', a.category_id) > 0";
            //$sql_data1 .="  AND a.category_id LIKE '%$catagory_id%'";
        }
        if ($language_id != '') {
            $sql_data1 .=" AND a.article_language = '$language_id'
";
        }
        $sql_data1 .=" ORDER BY a.publish_date DESC LIMIT $limitPage,10";
        $sql_data = $this->db->query($sql_data1);


        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            //return $data = $sql->result_array();
            $data = $sql_data->result_array();
            foreach ($data as $key => $value) {
                $article_id = $value['id'];
                $sql1 = $this->db->query("SELECT id FROM tbl_article_comment WHERE article_id='$article_id'");
                $data[$key]['comment_count'] = $sql1->num_rows();
                /* USER INFO */
                $customer_id = $value['customer_id'];
                $sql2 = $this->db->query("SELECT name FROM tbl_admin WHERE id='$customer_id'");
                $result = $sql2->row_array();
                $data[$key]['user_name'] = $result['name'];
                /* catagory list */
                $category_id = $value['category_id'];
                $implode = explode(',', $category_id);
                $category_list = implode(",", $implode);
                /*Language changes*/
                /*if($language_id !=""){
                        if($language_id == '1'){
                            $category_name = 'category_name';
                            
                        }elseif($language_id == '2'){
                            $category_name = 'category_name_portuguese';
                            
                        }elseif($language_id == '3'){
                            $category_name = 'category_name_spanish';                            
                        }                        
                    }else{
                       //$category_name = 'category_name';
                        if($value['language'] == 'Portuguese'){ 
                           $category_name = 'category_name_portuguese'; 
                        }elseif($value['language'] == 'Spanish'){
                            $category_name = 'category_name_spanish';
                        }else{
                            $category_name = 'category_name';
                        }
                    }*/
                if($value['language'] == 'Portuguese'){ 
                           $category_name = 'category_name_portuguese'; 
                        }elseif($value['language'] == 'Spanish'){
                            $category_name = 'category_name_spanish';
                        }else{
                            $category_name = 'category_name';
                        }
                $sql3 = $this->db->query("SELECT $category_name as category_name FROM tbl_category WHERE id IN ($category_list) AND $category_name !=''");
                $nameArray = $sql3->result_array();
                $data[$key]['category_name'] = $nameArray;
            }
            return $data;
        }
    }

    /* DELETED ARTICLE LIST */

    /*     * get all article list
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Index function load defaut 
     * */

    public function getDeletedArticleList() {

        $language_id = $this->get_language();
        $catagory_id = $this->get_catagory_id();

        $sql1 = "SELECT a.id,a.title,a.category_id,a.language_id,a.status,a.customer_id,a.tags,b.language,a.article_language 
            ,a.created_by,a.author,a.publish_from,a.publish_to FROM tbl_new_articles as a
            LEFT JOIN tbl_language as b ON a.language_id = b.id WHERE a.status='0'";

        if ($catagory_id != '') {
            $sql1 .= "AND FIND_IN_SET('$catagory_id', a.category_id) > 0";
            //$sql1 .="  AND a.category_id LIKE '%$catagory_id%'";
        }
        if ($language_id != '') {
            $sql1 .=" AND a.article_language = '$language_id'
                    ";
        }

        $sql = $this->db->query($sql1);


        //echo $this->db->last_query(); 
        /* PAGINATION */
        $config['base_url'] = base_url() . 'index.php/' . 'getdeletedarticlelist?catagory=' . $catagory_id . '&lang=' . $language_id;
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
        $sql_data1 = "SELECT a.website_url,a.id,a.title,a.category_id,a.language_id,a.status,a.customer_id,a.tags,b.language 
            ,a.created_by,a.article_language,a.author,a.publish_from,a.publish_to FROM tbl_new_articles as a
            LEFT JOIN tbl_language as b ON a.language_id = b.id WHERE a.status='0'";

        if ($catagory_id != '') {
            $sql_data1 .= "AND FIND_IN_SET('$catagory_id', a.category_id) > 0";
            //$sql_data1 .="  AND a.category_id LIKE '%$catagory_id%'";
        }
        if ($language_id != '') {
            $sql_data1 .=" AND a.article_language = '$language_id'
                    ";
        }

        $sql_data1 .=" ORDER BY a.publish_date DESC LIMIT $limitPage,10";
        $sql_data = $this->db->query($sql_data1);


        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            //return $data = $sql->result_array();
            $data = $sql_data->result_array();
            foreach ($data as $key => $value) {
                $article_id = $value['id'];
                $sql1 = $this->db->query("SELECT id FROM tbl_article_comment WHERE article_id='$article_id'");
                $data[$key]['comment_count'] = $sql1->num_rows();
                /* USER INFO */
                $customer_id = $value['customer_id'];
                $sql2 = $this->db->query("SELECT name FROM tbl_admin WHERE id='$customer_id'");
                $result = $sql2->row_array();
                $data[$key]['user_name'] = $result['name'];
                /* catagory list */
                $category_id = $value['category_id'];
                $implode = explode(',', $category_id);
                $category_list = implode(",", $implode);
                /*Language changes*/
                /*if($language_id !=""){
                        if($language_id == '1'){
                            $category_name = 'category_name';
                            
                        }elseif($language_id == '2'){
                            $category_name = 'category_name_portuguese';
                            
                        }elseif($language_id == '3'){
                            $category_name = 'category_name_spanish';                            
                        }                        
                    }else{
                       //$category_name = 'category_name';
                        if($value['language'] == 'Portuguese'){ 
                           $category_name = 'category_name_portuguese'; 
                        }elseif($value['language'] == 'Spanish'){
                            $category_name = 'category_name_spanish';
                        }else{
                            $category_name = 'category_name';
                        }
                    }*/
                if($value['language'] == 'Portuguese'){ 
                           $category_name = 'category_name_portuguese'; 
                        }elseif($value['language'] == 'Spanish'){
                            $category_name = 'category_name_spanish';
                        }else{
                            $category_name = 'category_name';
                        }
                $sql3 = $this->db->query("SELECT $category_name as category_name FROM tbl_category WHERE id IN ($category_list) AND $category_name !=''");
                //$sql3 = $this->db->query("SELECT category_name FROM tbl_category WHERE id IN ($category_list)");
                $nameArray = $sql3->result_array();
                $data[$key]['category_name'] = $nameArray;
            }
            return $data;
        }
    }

    /* PUBLISHED ARTICLE LIST */

    /** get all article list
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Index function load defaut 
     * */
    public function getPublishedArticleList() {

        $language_id = $this->get_language();
        $catagory_id = $this->get_catagory_id();
        $cat_language = $this->get_article_language(); 


        $sql1 = "SELECT a.id,a.title,a.category_id,a.language_id,a.status,a.customer_id,a.tags,b.language 
            ,a.article_language,a.created_by,a.author,a.publish_from,a.publish_to FROM tbl_new_articles as a
            LEFT JOIN tbl_language as b ON a.language_id = b.id WHERE a.status='2'";
        if ($catagory_id != '') {
            $sql1 .= "AND FIND_IN_SET('$catagory_id', a.category_id) > 0";
            //$sql1 .="  AND a.category_id LIKE '%$catagory_id%'";
        }
        if ($cat_language != '') {
            $sql1 .=" AND a.article_language = '$cat_language'
                    ";
        }
        if ($language_id != '') {
            $sql1 .=" AND a.language_id = '$language_id'
                    ";
        }

        $sql = $this->db->query($sql1);


        // echo $this->db->last_query(); 
        /* PAGINATION */
        $config['base_url'] = base_url() . 'index.php/' . 'getpublishedarticlelist?catagory=' . $catagory_id . '&lang=' . $language_id;
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
        $sql_data1 = "SELECT a.website_url,a.id,a.title,a.category_id,a.language_id,a.status,a.customer_id,a.tags,b.language 
            ,a.article_language,a.created_by,a.author,a.publish_from,a.publish_to FROM tbl_new_articles as a
            LEFT JOIN tbl_language as b ON a.language_id = b.id WHERE a.status='2'";
        if ($catagory_id != '') {
             $sql_data1 .= "AND FIND_IN_SET('$catagory_id', a.category_id) > 0";
            //$sql_data1 .="  AND a.category_id LIKE '%$catagory_id%'";
        }
        if ($cat_language != '') {
            $sql_data1 .=" AND a.article_language = '$cat_language'
";
        }
        if ($language_id != '') {
            $sql_data1 .=" AND a.language_id = '$language_id'
                    ";
        }
        $sql_data1 .=" ORDER BY a.publish_date DESC LIMIT $limitPage,10";
        $sql_data = $this->db->query($sql_data1);

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            //return $data = $sql->result_array();
            $data = $sql_data->result_array();
            foreach ($data as $key => $value) {
                $article_id = $value['id'];
                $sql1 = $this->db->query("SELECT id FROM tbl_article_comment WHERE article_id='$article_id'");
                $data[$key]['comment_count'] = $sql1->num_rows();
                /* USER INFO */
                $customer_id = $value['customer_id'];
                $sql2 = $this->db->query("SELECT name FROM tbl_admin WHERE id='$customer_id'");
                $result = $sql2->row_array();
                $data[$key]['user_name'] = $result['name'];
                /* catagory list */
                $category_id = $value['category_id'];
                $implode = explode(',', $category_id);
                $category_list = implode(",", $implode);
                /*Language changes*/
               /* if($language_id !=""){
                        if($language_id == '1'){
                            $category_name = 'category_name';
                            
                        }elseif($language_id == '2'){
                            $category_name = 'category_name_portuguese';
                            
                        }elseif($language_id == '3'){
                            $category_name = 'category_name_spanish';                            
                        }                        
                    }else{
                       //$category_name = 'category_name';
                        if($value['language'] == 'Portuguese'){ 
                           $category_name = 'category_name_portuguese'; 
                        }elseif($value['language'] == 'Spanish'){
                            $category_name = 'category_name_spanish';
                        }else{
                            $category_name = 'category_name';
                        }
                    } */
                if($value['language'] == 'Portuguese'){ 
                           $category_name = 'category_name_portuguese'; 
                        }elseif($value['language'] == 'Spanish'){
                            $category_name = 'category_name_spanish';
                        }else{
                            $category_name = 'category_name';
                        }
                $sql3 = $this->db->query("SELECT $category_name as category_name FROM tbl_category WHERE id IN ($category_list) AND $category_name !=''");
                //$sql3 = $this->db->query("SELECT category_name FROM tbl_category WHERE id IN ($category_list)");
                $nameArray = $sql3->result_array();
                $data[$key]['category_name'] = $nameArray;
            }
            return $data;
        }
    }

    /* DRAFTED ARTICLE LIST */

    /*     * get all article list
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Index function load defaut 
     * */

    public function getDraftedArticleList() {

        $language_id = $this->get_language(); 
        $catagory_id = $this->get_catagory_id();

        $sql1 = "SELECT a.id,a.title,a.category_id,a.language_id,a.status,a.customer_id,a.tags,b.language,a.article_language 
            FROM tbl_new_articles as a
            LEFT JOIN tbl_language as b ON a.language_id = b.id WHERE a.status='1' AND a.created_by ='1'";

        if ($catagory_id != '') {
            $sql1 .= "AND FIND_IN_SET('$catagory_id', a.category_id) > 0";
            //$sql1 .="  AND a.category_id LIKE '%$catagory_id%'";
        }
        if ($language_id != '') {
            $sql1 .=" AND a.article_language = '$language_id'
                    ";
        }

        $sql = $this->db->query($sql1);




        //echo $this->db->last_query(); 
        /* PAGINATION */
        $config['base_url'] = base_url() . 'index.php/' . 'getdraftarticlelist?catagory=' . $catagory_id . '&lang=' . $language_id;
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
        $sql_data1 = "SELECT a.website_url,a.id,a.title,a.category_id,a.language_id,a.status,a.customer_id,a.tags,b.language, 
            a.publish_from, a.publish_to, a.article_language FROM tbl_new_articles as a
            LEFT JOIN tbl_language as b ON a.language_id = b.id WHERE a.status='1' AND a.created_by ='1' ";
        if ($catagory_id != '') {
            $sql_data1 .= "AND FIND_IN_SET('$catagory_id', a.category_id) > 0";
            //$sql_data1 .="  AND a.category_id LIKE '%$catagory_id%'";
        }
        if ($language_id != '') {
            $sql_data1 .=" AND a.article_language = '$language_id'
";
        }
        $sql_data1 .=" ORDER BY a.publish_date DESC LIMIT $limitPage,10";
        $sql_data = $this->db->query($sql_data1);


        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            //return $data = $sql->result_array();
            $data = $sql_data->result_array();
            foreach ($data as $key => $value) {
                $article_id = $value['id'];
                $sql1 = $this->db->query("SELECT id FROM tbl_article_comment WHERE article_id='$article_id'");
                $data[$key]['comment_count'] = $sql1->num_rows();
                /* USER INFO */
                $customer_id = $value['customer_id'];
                $sql2 = $this->db->query("SELECT name FROM tbl_admin WHERE id='$customer_id'");
                $result = $sql2->row_array();
                $data[$key]['user_name'] = $result['name'];
                /* catagory list */
                $category_id = $value['category_id'];
                $implode = explode(',', $category_id);
                $category_list = implode(",", $implode);
                /*Language changes*/
                /*if($language_id !=""){
                        if($language_id == '1'){
                            $category_name = 'category_name';
                            
                        }elseif($language_id == '2'){
                            $category_name = 'category_name_portuguese';
                            
                        }elseif($language_id == '3'){
                            $category_name = 'category_name_spanish';                            
                        }                        
                    }else{
                       //$category_name = 'category_name';
                        if($value['language'] == 'Portuguese'){ 
                           $category_name = 'category_name_portuguese'; 
                        }elseif($value['language'] == 'Spanish'){
                            $category_name = 'category_name_spanish';
                        }else{
                            $category_name = 'category_name';
                        }
                    }*/
                if($value['language'] == 'Portuguese'){ 
                           $category_name = 'category_name_portuguese'; 
                        }elseif($value['language'] == 'Spanish'){
                            $category_name = 'category_name_spanish';
                        }else{
                            $category_name = 'category_name';
                        }
                $sql3 = $this->db->query("SELECT $category_name as category_name FROM tbl_category WHERE id IN ($category_list) AND $category_name !=''");
                //$sql3 = $this->db->query("SELECT category_name FROM tbl_category WHERE id IN ($category_list)");
                $nameArray = $sql3->result_array();
                $data[$key]['category_name'] = $nameArray;
            }
            return $data;
        }
    }

    /* INSERT NEW ARTICLE */

    public function InsertArticle($groups, $locations, $disciplines, $branches) {
        $creater_id = $this->get_customer_id();
        $title = $this->get_title();
        $media_type = $this->get_media_type();
        $catagory = $this->get_catagory_id();
        $language = $this->get_language();
        $tags = $this->get_tags();
        $article_lang = $this->get_article_language();
        //wootrix phase 2
        $embed_video=$this->get_embed_video();
        
        
        if ($tags == "") {
            $tags = "";
        } else {
            $tags = $this->get_tags();
        }
        $discription = $this->get_description();
        $discription = str_replace("&nbsp;", "", $discription);
        $allow_comments = $this->get_allow_comment();
        $allow_share = $this->get_allow_share();
        $image = $this->get_image();
        $video_path = $this->get_video_path();
        $status = $this->get_status();
        $from_date = $this->get_publish_from();
        $to_date = $this->get_publish_to();
        $discription_plane = strip_tags($discription);

        
        $data1 = array(
            'title' => $title, 
            'media_type' => $media_type, 
            'language_id' => $language,
            'category_id' => $catagory,
            'tags' => $tags,
            'video_path' => $video_path,
            'allow_comment' => $allow_comments, 
            'allow_share' => $allow_share, 
            'image' => $image, 
            'description' => $discription,
            'status' => $status, 
            'article_language' => $article_lang,
            'customer_id' => $creater_id, 
            
            'publish_from' => $from_date,
            'publish_to' => $to_date, 
            'description_without_html' => $discription_plane,
            'embed_video' => $embed_video,
            'magazine_id' =>  $this->get_magazine_id(),
            'via_url' =>  $this->getVia_url(),
            'article_link'=>$this->getVia_url(),
            'embed_video_thumb'=>  $this->getEmbed_video_thumb()
             );
            //echo "<pre>"; 
            //print_r($data);die;
            
           $data=array_filter($data1);
           //print_r($data);die;
        if($this->getVia_url()!=''){
        $data['created_by']='0';
        }else{
        $data['created_by']='1';
        }
        $sql = $this->db->set('publish_date', 'now()', FALSE)->insert('tbl_new_articles', $data);

        if ($sql) {
            $insert_id = $this->db->insert_id();
            $this->set_id($insert_id);
            $this->insertArticleGroup($groups);
            $this->insertArticleLocation($locations);
            $this->insertArticleDiscipline($disciplines);
            $this->insertArticleBranch($branches);
            $this->insertArticleOrder($insert_id, $this->get_magazine_id());
            return TRUE;
        }
        return FALSE;
    }

    /* GET ARTICLE DETAILS */

    public function getArticleDetails() {
        $article_id = $this->get_id();

        $sql = $this->db->query("SELECT * FROM tbl_new_articles as a WHERE a.id= '$article_id'");

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->row_array();
        }
    }

    /* UPDATE ARTICLE DETAILS */

    public function UpdateOPENArticle() {
        $article_id = $this->get_id();

        $title = $this->get_title();
        //$media_type = $this->get_media_type();
        $catagory = $this->get_catagory_id();
        $language = $this->get_language();
        $tags = $this->get_tags();
        if ($tags == "") {
            $tags = "";
        } else {
            $tags = $this->get_tags();
        }
        //$discription = $this->get_description();        
        $allow_comments = $this->get_allow_comment();
        $allow_share = $this->get_allow_share();
        $status = $this->get_status();
        //$image = $this->get_image();
        $from_date = $this->get_publish_from();
        $to_date = $this->get_publish_to();
        $article_language = $this->get_article_language();

        /* $data = array('title' => $title, 'media_type' => $media_type, 'language_id' => $language, 'category_id' => $catagory, 'tags' => $tags,
          'allow_comment' => $allow_comments, 'allow_share' => $allow_share, 'description' => $discription, 'status' => $status,
          'publish_from' => $from_date, 'publish_to' => $to_date,'description_without_html'=>$discription_plane);
         */

        if ($image != "") {
            $data = array('title' => $title, 'language_id' => $language, 'category_id' => $catagory, 'tags' => $tags,
                'allow_comment' => $allow_comments, 'allow_share' => $allow_share, 'image' => $image, 'status' => $status,
                'publish_from' => $from_date, 'publish_to' => $to_date, 'article_language' => $article_language);
        } else {
            $data = array('title' => $title, 'language_id' => $language, 'category_id' => $catagory, 'tags' => $tags,
                'allow_comment' => $allow_comments, 'allow_share' => $allow_share, 'status' => $status,
                'publish_from' => $from_date, 'publish_to' => $to_date, 'article_language' => $article_language);
        }
        $sql = $this->db->set('publish_date', 'now()', FALSE);
        $this->db->where('id', $article_id);
        $this->db->update('tbl_new_articles', $data);
        //echo $this->db->last_query(); die;
        if ($sql) {
            return TRUE;
        }
        return FALSE;
    }

    /* UPDATE ARTICLE DETAILS */

    public function UpdateArticle($groups, $locations, $disciplines, $branches) {
        
        $article_id = $this->get_id();

        $title = $this->get_title();
        $media_type = $this->get_media_type();
        $catagory = $this->get_catagory_id();
        $language = $this->get_language();
        $tags = $this->get_tags();
        if ($tags == "") {
            $tags = "";
        } else {
            $tags = $this->get_tags();
        }
        $discription = $this->get_description();
        $discription = str_replace("&nbsp;", "", $discription);
        $allow_comments = $this->get_allow_comment();
        $allow_share = $this->get_allow_share();
        $status = $this->get_status();
        $image = $this->get_image();
        $from_date = $this->get_publish_from();
        $to_date = $this->get_publish_to();
        $discription_plane = strip_tags($discription);
        $article_language = $this->get_article_language();
        $created_by=  $this->get_created_by();
        /* $data = array('title' => $title, 'media_type' => $media_type, 'language_id' => $language, 'category_id' => $catagory, 'tags' => $tags,
          'allow_comment' => $allow_comments, 'allow_share' => $allow_share, 'description' => $discription, 'status' => $status,
          'publish_from' => $from_date, 'publish_to' => $to_date,'description_without_html'=>$discription_plane,'created_by'=>'0');
         */

        if ($image != "") {
            $data1 = array('article_link'=>$this->getVia_url(),'magazine_id'=>  $this->get_magazine_id(),'via_url' =>  $this->getVia_url(),'title' => $title, 'language_id' => $language, 'category_id' => $catagory, 'tags' => $tags, 'media_type' => $media_type,
                'allow_comment' => $allow_comments, 'allow_share' => $allow_share, 'image' => $image, 'description' => $discription, 'status' => $status,
                'publish_from' => $from_date, 'publish_to' => $to_date, 'description_without_html' => $discription_plane, 'article_language' => $article_language,'created_by'=>$created_by,'embed_video'=>  $this->get_embed_video(),'embed_video_thumb'=>  $this->getEmbed_video_thumb());
        } else {
            $data1 = array('article_link'=>$this->getVia_url(),'magazine_id'=>  $this->get_magazine_id(),'via_url' =>  $this->getVia_url(),'title' => $title, 'language_id' => $language, 'category_id' => $catagory, 'tags' => $tags, 'media_type' => $media_type,
                'allow_comment' => $allow_comments, 'allow_share' => $allow_share, 'description' => $discription, 'status' => $status,
                'publish_from' => $from_date, 'publish_to' => $to_date, 'description_without_html' => $discription_plane, 'article_language' => $article_language,'created_by'=>$created_by,'embed_video'=>  $this->get_embed_video(),'embed_video_thumb'=>  $this->getEmbed_video_thumb());
        }
        $data=array_filter($data1);
        if($allow_share=='0'){
            $data['allow_share']='0';
        }
        if($allow_comments=='0'){
            $data['allow_comment']='0';
        }
        $sql = $this->db->set('publish_date', 'now()', FALSE);
        $this->db->where('id', $article_id);
        $this->db->update('tbl_new_articles', $data);

        if ($sql) {
            $this->insertArticleGroup($groups);
            $this->insertArticleLocation($locations);
            $this->insertArticleDiscipline($disciplines);
            $this->insertArticleBranch($branches);
            $this->updateArticleOrder($article_id, $this->get_magazine_id());
            return TRUE;
        }

        return FALSE;

    }

    /* Update vide Path */

    public function UpdateVideoPath() {
        $article_id = $this->get_id();
        $video_path = $this->get_video_path();

        $data = array('video_path' => $video_path);
        $sql = $this->db->set('publish_date', 'now()', FALSE);
        $this->db->where('id', $article_id);
        $this->db->update('tbl_new_articles', $data);
        //echo $this->db->last_query(); die;
        if ($sql) {
            return TRUE;
        }
        return FALSE;
    }

    /* DELETE A OPEN ARTICLE */

    public function deleteOpenArticle() {

        $article_id = $this->get_id();
        $sql = $this->db->query("UPDATE tbl_new_articles SET status='0' WHERE id='$article_id'");
        //echo $this->db->last_query(); die;
        if ($sql) {
            return TRUE;
        }
        return FALSE;
    }

    /* RESTORE A ARTICLE */

    public function restoreOpenArticle() {

        $article_id = $this->get_id();
        $sql = $this->db->query("UPDATE tbl_new_articles SET status='1' , created_by ='1' WHERE id='$article_id'");
        //echo $this->db->last_query(); die;
        if ($sql) {
            return TRUE;
        }
        return FALSE;
    }

    /* permanently DELETE A ARTICLE */

    public function shiftDeleteOpenArticle() {

        $article_id = $this->get_id();
        $sql = $this->db->query("DELETE FROM tbl_new_articles WHERE id='$article_id'");
        //echo $this->db->last_query(); die;
        if ($sql) {
            return TRUE;
        }
        return FALSE;
    }

    /* publish a article */

    public function publishOpenArticle() {

        $article_id = $this->get_id();
        $sql = $this->db->query("UPDATE tbl_new_articles SET status='2' WHERE id='$article_id'");
        //echo $this->db->last_query(); die;
        if ($sql) {
            return TRUE;
        }
        return FALSE;
    }

    public function getMagazineArticle(){

        $magazineId = $this->get_magazine_id();
        $articleId = $this->get_id();

        $sql = "SELECT * FROM tbl_magazine_articles WHERE id = $articleId";
        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            $row = $query->result_array();
            return $row;
        } else {
            return array();
        }

    }

    public function getArticle(){

        $articleId = $this->get_id();
        $magazineId = $this->get_magazine_id();

        $sql = "SELECT * FROM tbl_new_articles WHERE id = $articleId";

        if( !empty( $magazineId ) ){
            $sql .= " AND FIND_IN_SET('$magazineId', magazine_id) > 0";
        }

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            $row = $query->result_array();
            return $row;
        } else {
            return array();
        }

    }

    public function insertArticleGroup($groups){

        $articleId = $this->get_id();

        $this->db->query("DELETE FROM tbl_article_group WHERE article_id = " . $articleId);

        if( count($groups) > 0 ) {

            foreach ($groups as $item) {
                $this->db->query("INSERT INTO tbl_article_group (article_id, group_id) VALUES( $articleId, $item );");
            }

        }

    }

    public function insertArticleLocation($locations){

        $articleId = $this->get_id();

        $this->db->query("DELETE FROM tbl_article_location WHERE article_id = " . $articleId);

        if( count($locations) > 0 ) {

            foreach ($locations as $item) {
                $this->db->query("INSERT INTO tbl_article_location (article_id, location_id) VALUES( $articleId, $item );");
            }

        }

    }

    public function insertArticleDiscipline($disciplines){

        $articleId = $this->get_id();

        $this->db->query("DELETE FROM tbl_article_discipline WHERE article_id = " . $articleId);

        if( count($disciplines) > 0 ) {

            foreach ($disciplines as $item) {
                $this->db->query("INSERT INTO tbl_article_discipline (article_id, discipline_id) VALUES( $articleId, $item );");
            }

        }

    }

    public function insertArticleBranch($branches){

        $articleId = $this->get_id();

        $this->db->query("DELETE FROM tbl_article_branch WHERE article_id = " . $articleId);

        if( count($branches) > 0 ) {

            foreach ($branches as $item) {
                $this->db->query("INSERT INTO tbl_article_branch (article_id, branch) VALUES( $articleId, '$item' );");
            }

        }

    }

    public function insertArticleOrder($articleId, $magazineIds){

        $arrayMagazineId = explode(",", $magazineIds);

        foreach( $arrayMagazineId as $magazineId ){

            $sql = $this->db->query("SELECT max(nr_order) nr_order from tbl_article_order where magazine_id = " . $magazineId);
            $result = $sql->row_array();
            $order = $result["nr_order"] + 1;

            $this->db->query("INSERT INTO tbl_article_order (article_id, magazine_id, nr_order) VALUES( $articleId, $magazineId, $order );");

        }

    }

    public function updateArticleOrder($articleId, $magazineIds){

        $arrayMagazineId = explode(",", $magazineIds);

        foreach( $arrayMagazineId as $magazineId ){

            $sql = $this->db->query("SELECT nr_order from tbl_article_order where article_id = " . $articleId . " AND magazine_id = " . $magazineId);
            $result = $sql->row_array();

            if( !$result["nr_order"] ){
                $this->insertArticleOrder($articleId, $magazineIds);
            }

        }

    }

}

?>
