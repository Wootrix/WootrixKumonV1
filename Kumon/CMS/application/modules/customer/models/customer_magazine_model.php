<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
(defined('BASEPATH')) OR exit('No direct script access allowed');

class customer_magazine_model extends CI_Model {
    
    
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

    /* Tbl Article */
    private $_image = "";
    private $_media_type = "";
    private $_language = "";
    private $_description = "";
    private $_status = "";
    private $_magazine_id = "";
    private $_tags = "";
    private $_video_path = "";
    private $_description_without_html = "";
    private $_allow_comment = "";
    private $_allow_share = "";
    private $_created_by = "";
    private $_publish_from = "";
    private $_publish_to = "";
    private $_article_language = "";
    
    // * phase 2
    private $_link_url="";
    private $_source="";
    private $_embedVideo="";
    private $_embedThumbVideo="";
            
    
    function getEmbedVideo() {
        return $this->_embedVideo;
    }

    function getEmbedThumbVideo() {
        return $this->_embedThumbVideo;
    }

    function setEmbedVideo($embedVideo) {
        $this->_embedVideo = $embedVideo;
    }

    function setEmbedThumbVideo($embedThumbVideo) {
        $this->_embedThumbVideo = $embedThumbVideo;
    }
    
    function getSource() {
        return $this->_source;
    }

    function setSource($source) {
        $this->_source = $source;
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

    /* -------------tblArticle-------------- */

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
    
    
    public function get_link_url() {
        return $this->_link_url;
    }

    public function set_link_url($_link_url) {
        $this->_link_url = $_link_url;
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
            //$sql = $this->db->query("SELECT a.id,a.category_name FROM tbl_category as a INNER JOIN tbl_language as b
            //   ON a.language = b.id WHERE a.status = '1' AND a.language=$language_id Order By a.id DESC");
        } else {
            $sql = $this->db->query("select id,category_name,status,created_date from tbl_category where status='1' AND category_name !=''  ORDER BY category_name");
            //$sql = $this->db->query("SELECT a.id,a.category_name FROM tbl_category as a INNER JOIN tbl_language as b
            //ON a.language = b.id WHERE a.status = '1' AND a.category_name !='' Order By a.category_name ASC");
        }
//echo $this->db->last_query()
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

    /* CUSTOMER MAGAZINE LISTING */

    public function magazineList() {

        $customer_id = $this->get_customer_id();
        $title = $this->get_title();

        if ($title != "") {
            $sql = $this->db->query("SELECT id,title,cover_image,status,publish_date_from,publish_date_to,subscription_password
            FROM tbl_magazine WHERE status = '1' AND customer_id = '$customer_id' AND publish_date_to >= CURDATE()
                AND title LIKE '%$title%'");
        } else {
            $sql = $this->db->query("SELECT id,title,cover_image,status,publish_date_from,publish_date_to,subscription_password
            FROM tbl_magazine WHERE status = '1' AND customer_id = '$customer_id' AND publish_date_to >= CURDATE()");
        }

        /* PAGINATION */
        $config['base_url'] = base_url() . 'index.php/' . 'customer_magazinelist?';
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
        if ($title != "") {
            $sql_data = $this->db->query("SELECT id,title,cover_image,status,publish_date_from,publish_date_to,subscription_password
            FROM tbl_magazine WHERE status = '1' AND customer_id = '$customer_id' AND publish_date_to >= CURDATE() AND title LIKE '%$title%' 
                ORDER BY publish_date DESC LIMIT $limitPage,10 ");
        } else {
            $sql_data = $this->db->query("SELECT id,title,cover_image,status,publish_date_from,publish_date_to,subscription_password
            FROM tbl_magazine WHERE status = '1' AND customer_id = '$customer_id' AND publish_date_to >= CURDATE() ORDER BY id DESC LIMIT $limitPage,10 ");
        }


        //echo $this->db->last_query();

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            //return $data = $sql->result_array();
            $data = $sql_data->result_array();
            /* get article count in this magazine */
            foreach ($data as $key => $value) {
                $magazine_id = $value['id'];
                $sql1 = $this->db->query("SELECT id FROM tbl_magazine_articles WHERE  FIND_IN_SET('$magazine_id', magazine_id) > 0 AND (status='1' OR status='3') ");
                $data[$key]['new_article_count'] = $sql1->num_rows();
                $sql2 = $this->db->query("SELECT id FROM tbl_magazine_articles WHERE  FIND_IN_SET('$magazine_id', magazine_id) > 0 UNION SELECT id FROM tbl_new_articles WHERE  FIND_IN_SET('$magazine_id', magazine_id) > 0");
                $data[$key]['article_count'] = $sql2->num_rows();
            }
            return $data;
        }
    }

    /* --------------------- */

    /* MAGAZINE ARTICLE LIST */

    /**
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * array
     * */
    public function getMagazineArticleList() {
        $sql1 .="";
        $magazine_id = $this->get_id();
        $catagory_id = $this->get_catagory_id();
        $language_id = $this->get_language_id();
        $cat_language = $this->get_article_language();

        $sql1 .= "SELECT a.id,a.title,a.author,a.status,a.publish_date,a.category_id,a.tags,a.publish_from,a.publish_to,a.article_language,
            b.name,c.language, 0 as nr_order FROM tbl_magazine_articles as a LEFT JOIN tbl_customer as b ON a.customer_id = b.id LEFT JOIN tbl_language as c ON
            a.language_id = c.id WHERE a.id != '0'";
        
        if ($magazine_id != "") {
           $sql1 .= "  AND FIND_IN_SET('$magazine_id', a.magazine_id) > 0";
            //$sql1 .= " AND a.category_id LIKE'%$catagory_id%'";
        }
//        if ($language_id != "") {
//            $sql1 .= " AND a.language_id = $language_id ";
//        }
        if ($cat_language != '') {
            $sql1 .=" AND a.article_language = '$cat_language'";
        }
        
        
        $sql1 .= " UNION SELECT a.id,a.title,a.author,a.status,a.publish_date,a.category_id,a.tags,a.publish_from,a.publish_to,a.article_language,
            b.name,c.language, nr_order FROM tbl_new_articles as a 
            LEFT JOIN tbl_customer as b ON a.customer_id = b.id 
            LEFT JOIN tbl_language as c ON a.language_id = c.id 
            JOIN tbl_article_order ao ON( a.id = ao.article_id AND ao.magazine_id = $magazine_id )
            WHERE a.id != '0'";
        
        if ($magazine_id != "") {
           $sql1 .= "  AND FIND_IN_SET('$magazine_id', a.magazine_id) > 0";
            //$sql1 .= " AND a.category_id LIKE'%$catagory_id%'";
        }
//        if ($language_id != "") {
//            $sql1 .= " AND a.language_id = $language_id ";
//        }
        if ($cat_language != '') {
            $sql1 .=" AND a.article_language = '$cat_language'";
        }

        

        $sql = $this->db->query($sql1);

        //echo $this->db->last_query();die;

        /* PAGINATION */
        $config['base_url'] = base_url() . 'index.php/'.'customer_articlelist?catagory='. $catagory_id .'&lang='. $language_id.'&rowId='.$magazine_id;
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
        $sql_data1="";
        $sql_data1 .= " SELECT s.* FROM (SELECT a.link_url as url,a.source,a.created_by,a.website_url,if(a.article_link='',if(a.website_url='',a.link_url,a.website_url),if(a.article_link='0',if(a.website_url='',a.link_url,a.website_url),a.article_link)) as article_link,a.magazine_id,a.id,a.title,a.author,a.status,a.publish_date,a.category_id,a.tags,a.publish_from,a.publish_to,a.article_language,
            b.name,c.language, 0 as nr_order, 0 as max_order FROM tbl_magazine_articles as a LEFT JOIN tbl_customer as b ON a.customer_id = b.id LEFT JOIN tbl_language as c ON
            a.language_id = c.id WHERE a.id != 0";
        if ($magazine_id != "") {
            $sql_data1 .= "  AND FIND_IN_SET('$magazine_id', a.magazine_id) > 0";
        }
        if ($cat_language != '') {
            $sql_data1 .=" AND a.article_language = '$cat_language'";
        }
        
        $sql_data1 .= " UNION SELECT a.via_url as url,a.source,a.created_by,a.website_url,if(a.article_link='',a.website_url,if(a.article_link='0',a.website_url,a.article_link)) as article_link,a.magazine_id,a.id,a.title,a.author,a.status,a.publish_date,a.category_id,a.tags,a.publish_from,a.publish_to,a.article_language,
            b.name,c.language, nr_order, (select max(nr_order) from tbl_article_order where magazine_id = $magazine_id ) as max_order FROM tbl_new_articles as a LEFT JOIN tbl_customer as b ON a.customer_id = b.id 
            LEFT JOIN tbl_language as c ON a.language_id = c.id 
            JOIN tbl_article_order ao ON( a.id = ao.article_id AND ao.magazine_id = $magazine_id )
            WHERE a.id != 0";
        if ($magazine_id != "") {
            $sql_data1 .= "  AND FIND_IN_SET('$magazine_id', a.magazine_id) > 0";
        }
        if ($cat_language != '') {
            $sql_data1 .=" AND a.article_language = '$cat_language'";
        }
        
        $sql_data1 .= ") s";

        $sql_data1 .=" ORDER BY s.nr_order asc  ";
        $sql_data = $this->db->query($sql_data1);

//        echo $this->db->last_query();die;
        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            //return $data = $sql->result_array();            
            $data = $sql_data->result_array();
            //echo "<pre>";print_r($data);die;    
            foreach ($data as $key => $value) {
                $article_id = $value['id'];
                //$category_id = $value['category_id'];
                $magazine_ids=$value['magazine_id'];
                $implode = explode(',', $magazine_ids);
                $magzine_id_array = implode(",", $implode);
                //echo $category_list; die;
                /*if ($language_id != "") {

                    if ($language_id == "1") {
                        $category_name = 'category_name';
                    } elseif ($language_id == "2") {
                        $category_name = 'category_name_portuguese';
                    } elseif ($language_id == "3") {
                        $category_name = 'category_name_spanish';
                    }
                } else {
                    $category_name = 'category_name';
                }*/
                if($value['language'] == 'Portuguese'){ 
                           $category_name = 'category_name_portuguese'; 
                        }elseif($value['language'] == 'Spanish'){
                            $category_name = 'category_name_spanish';
                        }else{
                            $category_name = 'category_name';
                        }
                if(!empty($magzine_id_array)){
                $sql1 = $this->db->query("SELECT title as category_name FROM tbl_magazine WHERE id IN ($magzine_id_array)");
                }else{
                $sql1 = $this->db->query("SELECT title as category_name FROM tbl_magazine");    
                }
                //$sql1 = $this->db->query("SELECT category_name FROM tbl_category WHERE id IN ($category_list)");
                $nameArray = $sql1->result_array();
                $data[$key]['categoy_name'] = $nameArray;

                $sql2 = $this->db->query("SELECT id FROM tbl_magazine_article_comment WHERE article_id = $article_id AND STATUS = '1'");
                $data[$key]['comment_count'] = $sql2->num_rows();
            }
            //echo "<pre>";print_r($data);die;
            return $data;
        }
    }

    /* -------------- */

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

        $sql = $this->db->query("SELECT id FROM tbl_magazine_articles WHERE  FIND_IN_SET('$magazine_id', magazine_id) > 0 UNION ALL SELECT id FROM tbl_new_articles WHERE  FIND_IN_SET('$magazine_id', magazine_id) > 0");
        //AND FIND_IN_SET('$magazine_id', a.magazine_id) > 0
        //echo $this->db->last_query(); die;
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

        $sql = $this->db->query("SELECT id FROM tbl_magazine_articles WHERE FIND_IN_SET('$magazine_id', magazine_id) > 0 AND status='0' UNION SELECT id FROM tbl_new_articles WHERE FIND_IN_SET('$magazine_id', magazine_id) > 0 AND status='0' ");
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
        $sql = $this->db->query("SELECT id FROM tbl_magazine_articles WHERE FIND_IN_SET('$magazine_id', magazine_id) > 0 AND ( status='1' OR  status='3') UNION ALL SELECT id FROM tbl_new_articles WHERE FIND_IN_SET('$magazine_id', magazine_id) > 0 AND ( status='1' OR  status='3') ");
        return $data = $sql->num_rows();
//        if ($sql->num_rows() < 1) {
//            return FALSE;
//        } else {
//            return $data = $sql->num_rows();
//        }
    }

    /* ---------------- */

    public function getMagazineDraftArticle() {

        $magazine_id = $this->get_id();
        $sql = $this->db->query("SELECT id FROM tbl_magazine_articles WHERE FIND_IN_SET('$magazine_id', magazine_id) > 0 AND status='4' ");
        return $data = $sql->num_rows();
//        if ($sql->num_rows() < 1) {
//            return FALSE;
//        } else {
//            return $data = $sql->num_rows();
//        }
    }

    /* Published Article list */

    public function getMagazinePublishedArticleList() {$sql1 .="";
        $magazine_id = $this->get_id();
        $catagory_id = $this->get_catagory_id();
        $language_id = $this->get_language_id();
        $cat_language = $this->get_article_language();

        $sql1 .= "SELECT a.id,a.title,a.author,a.status,a.publish_date,a.category_id,a.tags,a.publish_from,a.publish_to,a.article_language,
            b.name,c.language FROM tbl_magazine_articles as a LEFT JOIN tbl_customer as b ON a.customer_id = b.id LEFT JOIN tbl_language as c ON
            a.language_id = c.id WHERE a.status='2'";
        
        if ($magazine_id != "") {
           $sql1 .= "  AND FIND_IN_SET('$magazine_id', a.magazine_id) > 0";
            //$sql1 .= " AND a.category_id LIKE'%$catagory_id%'";
        }
//        if ($language_id != "") {
//            $sql1 .= " AND a.language_id = $language_id ";
//        }
        if ($cat_language != '') {
            $sql1 .=" AND a.article_language = '$cat_language'";
        }
        
        
        $sql1 .= " UNION SELECT a.id,a.title,a.author,a.status,a.publish_date,a.category_id,a.tags,a.publish_from,a.publish_to,a.article_language,
            b.name,c.language FROM tbl_new_articles as a LEFT JOIN tbl_customer as b ON a.customer_id = b.id LEFT JOIN tbl_language as c ON
            a.language_id = c.id WHERE a.status='2'";
        
        if ($magazine_id != "") {
           $sql1 .= "  AND FIND_IN_SET('$magazine_id', a.magazine_id) > 0";
            //$sql1 .= " AND a.category_id LIKE'%$catagory_id%'";
        }
//        if ($language_id != "") {
//            $sql1 .= " AND a.language_id = $language_id ";
//        }
        if ($cat_language != '') {
            $sql1 .=" AND a.article_language = '$cat_language'";
        }

        

        $sql = $this->db->query($sql1);

        //echo $this->db->last_query();die;

        /* PAGINATION */
        $config['base_url'] = base_url() . 'index.php/'.'customer_articlelist?catagory='. $catagory_id .'&lang='. $language_id.'&rowId='.$magazine_id;
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
        $sql_data1="";
        $sql_data1 .= " SELECT s.* FROM (SELECT a.source,a.created_by,a.website_url,a.article_link,a.magazine_id,a.id,a.title,a.author,a.status,a.publish_date,a.category_id,a.tags,a.publish_from,a.publish_to,a.article_language,
            b.name,c.language FROM tbl_magazine_articles as a LEFT JOIN tbl_customer as b ON a.customer_id = b.id LEFT JOIN tbl_language as c ON
            a.language_id = c.id WHERE a.status='2'";
        if ($magazine_id != "") {
            $sql_data1 .= "  AND FIND_IN_SET('$magazine_id', a.magazine_id) > 0";
        }
        if ($cat_language != '') {
            $sql_data1 .=" AND a.article_language = '$cat_language'";
        }
        
        $sql_data1 .= " UNION SELECT a.source,a.created_by,a.website_url,a.article_link,a.magazine_id,a.id,a.title,a.author,a.status,a.publish_date,a.category_id,a.tags,a.publish_from,a.publish_to,a.article_language,
            b.name,c.language FROM tbl_new_articles as a LEFT JOIN tbl_customer as b ON a.customer_id = b.id LEFT JOIN tbl_language as c ON
            a.language_id = c.id WHERE a.status='2'";
        if ($magazine_id != "") {
            $sql_data1 .= "  AND FIND_IN_SET('$magazine_id', a.magazine_id) > 0";
        }
        if ($cat_language != '') {
            $sql_data1 .=" AND a.article_language = '$cat_language'";
        }
        
        $sql_data1 .= ") s";

        $sql_data1 .=" ORDER BY s.publish_date DESC LIMIT $limitPage,10";
        $sql_data = $this->db->query($sql_data1);

        //echo $this->db->last_query();die;
        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            //return $data = $sql->result_array();            
            $data = $sql_data->result_array();
            //echo "<pre>";print_r($data);die;    
            foreach ($data as $key => $value) {
                $article_id = $value['id'];
                //$category_id = $value['category_id'];
                $magazine_ids=$value['magazine_id'];
                $implode = explode(',', $magazine_ids);
                $magzine_id_array = implode(",", $implode);
                //echo $category_list; die;
                /*if ($language_id != "") {

                    if ($language_id == "1") {
                        $category_name = 'category_name';
                    } elseif ($language_id == "2") {
                        $category_name = 'category_name_portuguese';
                    } elseif ($language_id == "3") {
                        $category_name = 'category_name_spanish';
                    }
                } else {
                    $category_name = 'category_name';
                }*/
                if($value['language'] == 'Portuguese'){ 
                           $category_name = 'category_name_portuguese'; 
                        }elseif($value['language'] == 'Spanish'){
                            $category_name = 'category_name_spanish';
                        }else{
                            $category_name = 'category_name';
                        }
                if(!empty($magzine_id_array)){
                $sql1 = $this->db->query("SELECT title as category_name FROM tbl_magazine WHERE id IN ($magzine_id_array)");
                }else{
                $sql1 = $this->db->query("SELECT title as category_name FROM tbl_magazine");    
                }
                //$sql1 = $this->db->query("SELECT category_name FROM tbl_category WHERE id IN ($category_list)");
                $nameArray = $sql1->result_array();
                $data[$key]['categoy_name'] = $nameArray;

                $sql2 = $this->db->query("SELECT id FROM tbl_magazine_article_comment WHERE article_id = $article_id AND STATUS = '1'");
                $data[$key]['comment_count'] = $sql2->num_rows();
            }
            return $data;
        }
        
                }

    /* GET CUSTOMER DRAFTED ARTICLE LIST */

    public function getMagazineDrafArticle() {
        $sql1 .="";
        $magazine_id = $this->get_id();
        $catagory_id = $this->get_catagory_id();
        $language_id = $this->get_language_id();
        $cat_language = $this->get_article_language();

        $sql1 .= "SELECT a.id,a.title,a.author,a.status,a.publish_date,a.category_id,a.tags,a.publish_from,a.publish_to,a.article_language,
            b.name,c.language FROM tbl_magazine_articles as a LEFT JOIN tbl_customer as b ON a.customer_id = b.id LEFT JOIN tbl_language as c ON
            a.language_id = c.id WHERE a.status='4'";
        
        if ($magazine_id != "") {
           $sql1 .= "  AND FIND_IN_SET('$magazine_id', a.magazine_id) > 0";
            //$sql1 .= " AND a.category_id LIKE'%$catagory_id%'";
        }
//        if ($language_id != "") {
//            $sql1 .= " AND a.language_id = $language_id ";
//        }
        if ($cat_language != '') {
            $sql1 .=" AND a.article_language = '$cat_language'";
        }
        
        
        $sql1 .= " UNION SELECT a.id,a.title,a.author,a.status,a.publish_date,a.category_id,a.tags,a.publish_from,a.publish_to,a.article_language,
            b.name,c.language FROM tbl_new_articles as a LEFT JOIN tbl_customer as b ON a.customer_id = b.id LEFT JOIN tbl_language as c ON
            a.language_id = c.id WHERE a.status='4'";
        
        if ($magazine_id != "") {
           $sql1 .= "  AND FIND_IN_SET('$magazine_id', a.magazine_id) > 0";
            //$sql1 .= " AND a.category_id LIKE'%$catagory_id%'";
        }
//        if ($language_id != "") {
//            $sql1 .= " AND a.language_id = $language_id ";
//        }
        if ($cat_language != '') {
            $sql1 .=" AND a.article_language = '$cat_language'";
        }

        

        $sql = $this->db->query($sql1);

        //echo $this->db->last_query();die;

        /* PAGINATION */
        $config['base_url'] = base_url() . 'index.php/'.'customer_articlelist?catagory='. $catagory_id .'&lang='. $language_id.'&rowId='.$magazine_id;
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
        $sql_data1="";
        $sql_data1 .= " SELECT s.* FROM (SELECT a.source,a.created_by,a.website_url,a.article_link,a.magazine_id,a.id,a.title,a.author,a.status,a.publish_date,a.category_id,a.tags,a.publish_from,a.publish_to,a.article_language,
            b.name,c.language FROM tbl_magazine_articles as a LEFT JOIN tbl_customer as b ON a.customer_id = b.id LEFT JOIN tbl_language as c ON
            a.language_id = c.id WHERE a.status='4'";
        if ($magazine_id != "") {
            $sql_data1 .= "  AND FIND_IN_SET('$magazine_id', a.magazine_id) > 0";
        }
        if ($cat_language != '') {
            $sql_data1 .=" AND a.article_language = '$cat_language'";
        }
        
        $sql_data1 .= " UNION SELECT a.source,a.created_by,a.website_url,a.article_link,a.magazine_id,a.id,a.title,a.author,a.status,a.publish_date,a.category_id,a.tags,a.publish_from,a.publish_to,a.article_language,
            b.name,c.language FROM tbl_new_articles as a LEFT JOIN tbl_customer as b ON a.customer_id = b.id LEFT JOIN tbl_language as c ON
            a.language_id = c.id WHERE a.status='4'";
        if ($magazine_id != "") {
            $sql_data1 .= "  AND FIND_IN_SET('$magazine_id', a.magazine_id) > 0";
        }
        if ($cat_language != '') {
            $sql_data1 .=" AND a.article_language = '$cat_language'";
        }
        
        $sql_data1 .= ") s";

        $sql_data1 .=" ORDER BY s.publish_date DESC LIMIT $limitPage,10";
        $sql_data = $this->db->query($sql_data1);

        //echo $this->db->last_query();die;
        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            //return $data = $sql->result_array();            
            $data = $sql_data->result_array();
            //echo "<pre>";print_r($data);die;    
            foreach ($data as $key => $value) {
                $article_id = $value['id'];
                //$category_id = $value['category_id'];
                $magazine_ids=$value['magazine_id'];
                $implode = explode(',', $magazine_ids);
                $magzine_id_array = implode(",", $implode);
                //echo $category_list; die;
                /*if ($language_id != "") {

                    if ($language_id == "1") {
                        $category_name = 'category_name';
                    } elseif ($language_id == "2") {
                        $category_name = 'category_name_portuguese';
                    } elseif ($language_id == "3") {
                        $category_name = 'category_name_spanish';
                    }
                } else {
                    $category_name = 'category_name';
                }*/
                if($value['language'] == 'Portuguese'){ 
                           $category_name = 'category_name_portuguese'; 
                        }elseif($value['language'] == 'Spanish'){
                            $category_name = 'category_name_spanish';
                        }else{
                            $category_name = 'category_name';
                        }
                if(!empty($magzine_id_array)){
                $sql1 = $this->db->query("SELECT title as category_name FROM tbl_magazine WHERE id IN ($magzine_id_array)");
                }else{
                $sql1 = $this->db->query("SELECT title as category_name FROM tbl_magazine");    
                }
                //$sql1 = $this->db->query("SELECT category_name FROM tbl_category WHERE id IN ($category_list)");
                $nameArray = $sql1->result_array();
                $data[$key]['categoy_name'] = $nameArray;

                $sql2 = $this->db->query("SELECT id FROM tbl_magazine_article_comment WHERE article_id = $article_id AND STATUS = '1'");
                $data[$key]['comment_count'] = $sql2->num_rows();
            }
            return $data;
        }}

    /* Magazine DELETED Article list */

    public function getMagazineDeletedArticleList() {$sql1 .="";
        $magazine_id = $this->get_id();
        $catagory_id = $this->get_catagory_id();
        $language_id = $this->get_language_id();
        $cat_language = $this->get_article_language();

        $sql1 .= "SELECT a.id,a.title,a.author,a.status,a.publish_date,a.category_id,a.tags,a.publish_from,a.publish_to,a.article_language,
            b.name,c.language FROM tbl_magazine_articles as a LEFT JOIN tbl_customer as b ON a.customer_id = b.id LEFT JOIN tbl_language as c ON
            a.language_id = c.id WHERE a.status='0'";
        
        if ($magazine_id != "") {
           $sql1 .= "  AND FIND_IN_SET('$magazine_id', a.magazine_id) > 0";
            //$sql1 .= " AND a.category_id LIKE'%$catagory_id%'";
        }
//        if ($language_id != "") {
//            $sql1 .= " AND a.language_id = $language_id ";
//        }
        if ($cat_language != '') {
            $sql1 .=" AND a.article_language = '$cat_language'";
        }
        
        
        $sql1 .= " UNION SELECT a.id,a.title,a.author,a.status,a.publish_date,a.category_id,a.tags,a.publish_from,a.publish_to,a.article_language,
            b.name,c.language FROM tbl_new_articles as a LEFT JOIN tbl_customer as b ON a.customer_id = b.id LEFT JOIN tbl_language as c ON
            a.language_id = c.id WHERE a.status='0'";
        
        if ($magazine_id != "") {
           $sql1 .= "  AND FIND_IN_SET('$magazine_id', a.magazine_id) > 0";
            //$sql1 .= " AND a.category_id LIKE'%$catagory_id%'";
        }
//        if ($language_id != "") {
//            $sql1 .= " AND a.language_id = $language_id ";
//        }
        if ($cat_language != '') {
            $sql1 .=" AND a.article_language = '$cat_language'";
        }

        

        $sql = $this->db->query($sql1);

        //echo $this->db->last_query();die;

        /* PAGINATION */
        $config['base_url'] = base_url() . 'index.php/'.'customer_articlelist?catagory='. $catagory_id .'&lang='. $language_id.'&rowId='.$magazine_id;
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
        $sql_data1="";
        $sql_data1 .= " SELECT s.* FROM (SELECT a.source,a.created_by,a.website_url,a.article_link,a.magazine_id,a.id,a.title,a.author,a.status,a.publish_date,a.category_id,a.tags,a.publish_from,a.publish_to,a.article_language,
            b.name,c.language FROM tbl_magazine_articles as a LEFT JOIN tbl_customer as b ON a.customer_id = b.id LEFT JOIN tbl_language as c ON
            a.language_id = c.id WHERE a.status='0'";
        if ($magazine_id != "") {
            $sql_data1 .= "  AND FIND_IN_SET('$magazine_id', a.magazine_id) > 0";
        }
        if ($cat_language != '') {
            $sql_data1 .=" AND a.article_language = '$cat_language'";
        }
        
        $sql_data1 .= " UNION SELECT a.source,a.created_by,a.website_url,a.article_link,a.magazine_id,a.id,a.title,a.author,a.status,a.publish_date,a.category_id,a.tags,a.publish_from,a.publish_to,a.article_language,
            b.name,c.language FROM tbl_new_articles as a LEFT JOIN tbl_customer as b ON a.customer_id = b.id LEFT JOIN tbl_language as c ON
            a.language_id = c.id WHERE a.status='0'";
        if ($magazine_id != "") {
            $sql_data1 .= "  AND FIND_IN_SET('$magazine_id', a.magazine_id) > 0";
        }
        if ($cat_language != '') {
            $sql_data1 .=" AND a.article_language = '$cat_language'";
        }
        
        $sql_data1 .= ") s";

        $sql_data1 .=" ORDER BY s.publish_date DESC LIMIT $limitPage,10";
        $sql_data = $this->db->query($sql_data1);

        //echo $this->db->last_query();die;
        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            //return $data = $sql->result_array();            
            $data = $sql_data->result_array();
            //echo "<pre>";print_r($data);die;    
            foreach ($data as $key => $value) {
                $article_id = $value['id'];
                //$category_id = $value['category_id'];
                $magazine_ids=$value['magazine_id'];
                $implode = explode(',', $magazine_ids);
                $magzine_id_array = implode(",", $implode);
                //echo $category_list; die;
                /*if ($language_id != "") {

                    if ($language_id == "1") {
                        $category_name = 'category_name';
                    } elseif ($language_id == "2") {
                        $category_name = 'category_name_portuguese';
                    } elseif ($language_id == "3") {
                        $category_name = 'category_name_spanish';
                    }
                } else {
                    $category_name = 'category_name';
                }*/
                if($value['language'] == 'Portuguese'){ 
                           $category_name = 'category_name_portuguese'; 
                        }elseif($value['language'] == 'Spanish'){
                            $category_name = 'category_name_spanish';
                        }else{
                            $category_name = 'category_name';
                        }
                if(!empty($magzine_id_array)){
                $sql1 = $this->db->query("SELECT title as category_name FROM tbl_magazine WHERE id IN ($magzine_id_array)");
                }else{
                $sql1 = $this->db->query("SELECT title as category_name FROM tbl_magazine");    
                }
                //$sql1 = $this->db->query("SELECT category_name FROM tbl_category WHERE id IN ($category_list)");
                $nameArray = $sql1->result_array();
                $data[$key]['categoy_name'] = $nameArray;

                $sql2 = $this->db->query("SELECT id FROM tbl_magazine_article_comment WHERE article_id = $article_id AND STATUS = '1'");
                $data[$key]['comment_count'] = $sql2->num_rows();
            }
            return $data;
        }
        
                }

    /* Magazine Review Article list */

    public function getMagazineReviewArticleList() {
        $sql1 .="";
        $magazine_id = $this->get_id();
        $catagory_id = $this->get_catagory_id();
        $language_id = $this->get_language_id();
        $cat_language = $this->get_article_language();

        $sql1 .= "SELECT a.id,a.title,a.author,a.status,a.publish_date,a.category_id,a.tags,a.publish_from,a.publish_to,a.article_language,
            b.name,c.language FROM tbl_magazine_articles as a LEFT JOIN tbl_customer as b ON a.customer_id = b.id LEFT JOIN tbl_language as c ON
            a.language_id = c.id WHERE a.id != '0' AND (a.status='1' OR a.status='3')";
        
        if ($magazine_id != "") {
           $sql1 .= "  AND FIND_IN_SET('$magazine_id', a.magazine_id) > 0";
            //$sql1 .= " AND a.category_id LIKE'%$catagory_id%'";
        }
//        if ($language_id != "") {
//            $sql1 .= " AND a.language_id = $language_id ";
//        }
        if ($cat_language != '') {
            $sql1 .=" AND a.article_language = '$cat_language'";
        }
        
        
        $sql1 .= " UNION SELECT a.id,a.title,a.author,a.status,a.publish_date,a.category_id,a.tags,a.publish_from,a.publish_to,a.article_language,
            b.name,c.language FROM tbl_new_articles as a LEFT JOIN tbl_customer as b ON a.customer_id = b.id LEFT JOIN tbl_language as c ON
            a.language_id = c.id WHERE a.id != '0' AND (a.status='1' OR a.status='3')";
        
        if ($magazine_id != "") {
           $sql1 .= "  AND FIND_IN_SET('$magazine_id', a.magazine_id) > 0";
            //$sql1 .= " AND a.category_id LIKE'%$catagory_id%'";
        }
//        if ($language_id != "") {
//            $sql1 .= " AND a.language_id = $language_id ";
//        }
        if ($cat_language != '') {
            $sql1 .=" AND a.article_language = '$cat_language'";
        }

        

        $sql = $this->db->query($sql1);

        //echo $this->db->last_query();die;

        /* PAGINATION */
        $config['base_url'] = base_url() . 'index.php/'.'customer_articlelist?catagory='. $catagory_id .'&lang='. $language_id.'&rowId='.$magazine_id;
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
        $sql_data1="";
        $sql_data1 .= " SELECT s.* FROM (SELECT a.source,a.created_by,a.website_url,a.article_link,a.magazine_id,a.id,a.title,a.author,a.status,a.publish_date,a.category_id,a.tags,a.publish_from,a.publish_to,a.article_language,
            b.name,c.language FROM tbl_magazine_articles as a LEFT JOIN tbl_customer as b ON a.customer_id = b.id LEFT JOIN tbl_language as c ON
            a.language_id = c.id WHERE a.id != '0' AND (a.status='1' OR a.status='3')";
        if ($magazine_id != "") {
            $sql_data1 .= "  AND FIND_IN_SET('$magazine_id', a.magazine_id) > 0";
        }
        if ($cat_language != '') {
            $sql_data1 .=" AND a.article_language = '$cat_language'";
        }
        
        $sql_data1 .= " UNION SELECT a.source,a.created_by,a.website_url,a.article_link,a.magazine_id,a.id,a.title,a.author,a.status,a.publish_date,a.category_id,a.tags,a.publish_from,a.publish_to,a.article_language,
            b.name,c.language FROM tbl_new_articles as a LEFT JOIN tbl_customer as b ON a.customer_id = b.id LEFT JOIN tbl_language as c ON
            a.language_id = c.id WHERE a.id != '0' AND (a.status='1' OR a.status='3')";
        if ($magazine_id != "") {
            $sql_data1 .= "  AND FIND_IN_SET('$magazine_id', a.magazine_id) > 0";
        }
        if ($cat_language != '') {
            $sql_data1 .=" AND a.article_language = '$cat_language'";
        }
        
        $sql_data1 .= ") s";

        $sql_data1 .=" ORDER BY s.publish_date DESC LIMIT $limitPage,10";
        $sql_data = $this->db->query($sql_data1);

        //echo $this->db->last_query();die;
        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            //return $data = $sql->result_array();            
            $data = $sql_data->result_array();
            //echo "<pre>";print_r($data);die;    
            foreach ($data as $key => $value) {
                $article_id = $value['id'];
                //$category_id = $value['category_id'];
                $magazine_ids=$value['magazine_id'];
                $implode = explode(',', $magazine_ids);
                $magzine_id_array = implode(",", $implode);
                //echo $category_list; die;
                /*if ($language_id != "") {

                    if ($language_id == "1") {
                        $category_name = 'category_name';
                    } elseif ($language_id == "2") {
                        $category_name = 'category_name_portuguese';
                    } elseif ($language_id == "3") {
                        $category_name = 'category_name_spanish';
                    }
                } else {
                    $category_name = 'category_name';
                }*/
                if($value['language'] == 'Portuguese'){ 
                           $category_name = 'category_name_portuguese'; 
                        }elseif($value['language'] == 'Spanish'){
                            $category_name = 'category_name_spanish';
                        }else{
                            $category_name = 'category_name';
                        }
                if(!empty($magzine_id_array)){
                $sql1 = $this->db->query("SELECT title as category_name FROM tbl_magazine WHERE id IN ($magzine_id_array)");
                }else{
                $sql1 = $this->db->query("SELECT title as category_name FROM tbl_magazine");    
                }
                //$sql1 = $this->db->query("SELECT category_name FROM tbl_category WHERE id IN ($category_list)");
                $nameArray = $sql1->result_array();
                $data[$key]['categoy_name'] = $nameArray;

                $sql2 = $this->db->query("SELECT id FROM tbl_magazine_article_comment WHERE article_id = $article_id AND STATUS = '1'");
                $data[$key]['comment_count'] = $sql2->num_rows();
            }
            return $data;
        }
    }

    /* SEND FOR REVIEW STATE */

    public function sendForReview() {
        $article_id = $this->get_id();
        $sql = $this->db->query("UPDATE tbl_magazine_articles SET Status ='1', publish_date=now() WHERE id='$article_id'");
        if ($sql) {
            return TRUE;
        }
        return FALSE;
    }

    public function sendForPublish() {
        $article_id = $this->get_id();
        $sql = $this->db->query("UPDATE tbl_magazine_articles SET Status ='2', publish_date=now() WHERE id='$article_id'");
        if ($sql) {
            return TRUE;
        }
        return FALSE;
    }

    /* RESTORE ARTICLE */

    public function restoreArticle() {
        $article_id = $this->get_id();
        $source=  $this->getSource();
        if($source=='admin'){
        $sql = $this->db->query("UPDATE tbl_new_articles SET Status ='1', publish_date=now() WHERE id='$article_id'");
        }elseif ($source=='customer') {
        $sql = $this->db->query("UPDATE tbl_magazine_articles SET Status ='1', publish_date=now() WHERE id='$article_id'");
        }else{
        $sql = $this->db->query("UPDATE tbl_magazine_articles SET Status ='1', publish_date=now() WHERE id='$article_id'");
        }
        if ($sql) {
            return TRUE;
        }
        return FALSE;
    }

    /* PERMANENTLY DELETE */

    public function shiftDeleteArticle($magazineId) {

        $article_id = $this->get_id();
        $source=  $this->getSource();
        if($source=='admin'){
        $sql = $this->db->query("DELETE FROM tbl_new_articles WHERE id='$article_id'");
        }else if($source=='customer'){
        $sql = $this->db->query("DELETE FROM tbl_magazine_articles WHERE id='$article_id'");
        }else{
        $sql = $this->db->query("DELETE FROM tbl_magazine_articles WHERE id='$article_id'");
        }

        $sql = $this->db->query("select * FROM tbl_article_order WHERE magazine_id = $magazineId AND article_id = $article_id");
        $deletedData = $sql->row_array();

        $nextOrder = $deletedData["nr_order"] + 1;

        $sql = $this->db->query("select * FROM tbl_article_order WHERE magazine_id = $magazineId AND nr_order = $nextOrder");
        $data = $sql->row_array();

        while( isset( $data["nr_order"] ) ){

            $orderId = $data["id"];
            $newOrder = $data["nr_order"] - 1;

            $this->db->query("UPDATE tbl_article_order SET nr_order = $newOrder WHERE id = $orderId");

            $nextOrder++;

            $sql = $this->db->query("select * FROM tbl_article_order WHERE magazine_id = $magazineId AND nr_order = $nextOrder");
            $data = $sql->row_array();

        }

        $sql = $this->db->query("DELETE FROM tbl_article_order WHERE magazine_id = $magazineId AND article_id = $article_id");

        if ($sql) {
            return TRUE;
        }
        return FALSE;
    }

    /* REJECTION REPORT */

    public function getRejectionReport() {

        $article_id = $this->get_id();

        $sql = $this->db->query("SELECT a.article_report,b.title FROM tbl_article_decline as a INNER JOIN tbl_magazine_articles as b ON a.article_id = b.id
            WHERE a.article_id='$article_id' ORDER BY a.id DESC LIMIT 0,1");
        //echo $this->db->last_query();
        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            //return $data = $sql->result_array();            
            return $sql->row_array();
        }
    }

    /* INSERT NEW ARTICLE */

    public function InsertCustomerArticle($groups, $locations, $disciplines, $branches) {

        $creater_id = $this->get_customer_id();
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
        $image = $this->get_image();
        $video_path = $this->get_video_path();
        $status = $this->get_status();
        $from_date = $this->get_publish_from();
        $to_date = $this->get_publish_to();
        $magazine_id = $this->get_magazine_id();
        $plane_description = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($discription, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));
        //strip_tags($discription);
        $article_language = $this->get_article_language();
       
        if(count($magazine_id)>0 && is_array($magazine_id)){
            $magazine_ids=implode(",",$magazine_id);
        }else{
            $magazine_ids="";
        }
        $link_url=$this->get_link_url();
        


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
            'customer_id' => $creater_id,
            'publish_from' => $from_date,
            'publish_to' => $to_date,
            'magazine_id' => $magazine_ids,
            'description_without_html' => $plane_description, 
            'article_language' => $article_language,
            'link_url'=>$link_url,
            'embed_video'=>$this->getEmbedVideo(),
            'embed_video_thumb'=>  $this->getEmbedThumbVideo(),
            'article_link'=>$link_url
                );
        if($link_url!=''){
            $data1['created_by']='0';
        }else{
            $data1['created_by']='2';
        }
        $data=array_filter($data1);
        //echo '<pre>';print_r($data);die;
        $sql = $this->db->set('publish_date', 'now()', FALSE)->insert('tbl_new_articles', $data);
        //echo $this->db->last_query(); die;
        if ($sql) {
            $insert_id = $this->db->insert_id();
            $this->set_id($insert_id);
            $this->insertArticleGroup($groups);
            $this->insertArticleLocation($locations);
            $this->insertArticleDiscipline($disciplines);
            $this->insertArticleBranch($branches);
            $this->insertArticleOrder($insert_id, $magazine_ids);
            return TRUE;
        }
        return FALSE;
    }

    /* GET ARTICLE DETAILS */

    public function getArticleDetails() {
        $article_id = $this->get_id();
        if($this->getSource()=='admin'){
        $sql = $this->db->query("SELECT a.embed_video_thumb,a.embed_video,if(a.via_url ='',if(article_link='0','',article_link),via_url) as viaUrl,a.magazine_id,a.id,a.title,a.category_id,a.language_id,a.status,a.customer_id,a.tags,a.description,a.media_type,a.allow_comment,a.allow_share 
            ,a.article_language,a.article_language,publish_from,publish_to,image,video_path,a.magazine_id FROM tbl_new_articles as a WHERE a.id= '$article_id'");
        }elseif ($this->getSource()=='customer') {
        $sql = $this->db->query("SELECT a.embed_video_thumb,a.embed_video,if(a.link_url ='',if(article_link='0','',article_link),link_url) as viaUrl,a.magazine_id,a.id,a.title,a.category_id,a.language_id,a.status,a.customer_id,a.tags,a.description,a.media_type,a.allow_comment,a.allow_share 
            ,a.article_language,a.article_language,publish_from,publish_to,image,video_path,a.magazine_id FROM tbl_magazine_articles as a WHERE a.id= '$article_id'");
        }else{
        $sql = $this->db->query("SELECT a.embed_video_thumb,a.embed_video,if(a.link_url ='',if(article_link='0','',article_link),link_url) as viaUrl,a.magazine_id,a.id,a.title,a.category_id,a.language_id,a.status,a.customer_id,a.tags,a.description,a.media_type,a.allow_comment,a.allow_share 
            ,a.article_language,a.article_language,publish_from,publish_to,image,video_path,a.magazine_id FROM tbl_magazine_articles as a WHERE a.id= '$article_id'");
        }
        //echo $this->db->last_query();die;
        if ($sql->num_rows() < 1) { 
            return FALSE;
        } else {
            return $data = $sql->row_array();
        }
    }

    /* Update vide Path */

    public function UpdateVideoPath() {
        $article_id = $this->get_id();
        $video_path = $this->get_video_path();

        $data = array('video_path' => $video_path);
        $sql = $this->db->set('publish_date', 'now()', FALSE);
        $this->db->where('id', $article_id);
        $this->db->update('tbl_magazine_articles', $data);
        //echo $this->db->last_query(); die;
        if ($sql) {
            return TRUE;
        }
        return FALSE;
    }

    /* UPDATE ARTICLE DETAILS */

    public function UpdateCustomerArticle($groups, $locations, $disciplines, $branches) {
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
        $plane_description = strip_tags($discription);
        $article_language = $this->get_article_language();
        $magazine_id=$this->get_magazine_id();

        
        if(count($magazine_id)>0 && is_array($magazine_id)){
            $magazine_ids=implode(",",$magazine_id);
        }else{
            $magazine_ids="";
        }
        //echo 'msg='.$magazine_ids;die;
        $link_url=$this->get_link_url();
        
        
        if ($image != "") {
            $data1 = array(
                'title' => $title,
                'media_type' => $media_type, 
                'language_id' => $language,
                'category_id' => $catagory,
                'tags' => $tags,
                'allow_comment' => $allow_comments,
                'allow_share' => $allow_share,
                'image' => $image, 
                'description' => $discription,
                'status' => $status,
                'publish_from' => $from_date,
                'publish_to' => $to_date,
                'description_without_html' => $plane_description,
                'article_language' => $article_language,
                'magazine_id'=>$magazine_ids,
                'embed_video'=>  $this->getEmbedVideo(),
                'embed_video_thumb'=>  $this->getEmbedThumbVideo(),
                'article_link' => $link_url,
                'link_url'=>$link_url
                    );
        } else {
            $data1 = array(
                'title' => $title, 
                'media_type' => $media_type, 
                'language_id' => $language,
                'category_id' => $catagory,
                'tags' => $tags,
                'allow_comment' => $allow_comments,
                'allow_share' => $allow_share,
                'description' => $discription, 
                'status' => $status,
                'publish_from' => $from_date, 
                'publish_to' => $to_date,
                'description_without_html' => $plane_description, 
                'article_language' => $article_language,
                'magazine_id'=>$magazine_ids,
                'embed_video'=>  $this->getEmbedVideo(),
                'embed_video_thumb'=>  $this->getEmbedThumbVideo(),
                'article_link' => $link_url,
                'link_url'=>$link_url
                    );
        }
        $data=  array_filter($data1);
        $sql = $this->db->set('publish_date', 'now()', FALSE);
        $this->db->where('id', $article_id);
        $this->db->set('via_url',  $this->get_link_url())->update('tbl_new_articles', $data);
        //echo $this->db->last_query(); die;
        if ($sql) {
            $this->insertArticleGroup($groups);
            $this->insertArticleLocation($locations);
            $this->insertArticleDiscipline($disciplines);
            $this->insertArticleBranch($branches);
            $this->updateArticleOrder($article_id, $magazine_ids);
            return TRUE;
        }
        return FALSE;
    }

    /* GET ALL CODE FOR A MAGAZINE */

    public function getMagazineCodeList() {

        $magazine_id = $this->get_magazine_id();

        $sql = $this->db->query("SELECT password,read_status FROM tbl_magazine_password WHERE magazine_id='$magazine_id'");
        //echo $this->db->last_query();
        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->result_array();
        }
    }

    /* get Magazine Info */

    public function getMagazineInfo() {
        $magazine_id = $this->get_magazine_id();

        $sql = $this->db->query("SELECT id,publish_date_from,publish_date_to FROM tbl_magazine WHERE id='$magazine_id'");
        //echo $this->db->last_query();
        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->row_array();
        }
    }
    
    /*** phase 2 changes 
     * 
     * 
     * 
     * 
     * ***/
    
    public function getCustomerMagazine(){
        $customer_id=$this->session->userdata('user_id');

        if($customer_id!=''){
        $sql = $this->db->query("SELECT * FROM tbl_magazine WHERE customer_id='$customer_id' AND publish_date_to >= CURDATE() AND status='1' ORDER BY id");
        }else{
        $sql = $this->db->query("SELECT * FROM tbl_magazine WHERE publish_date_to >= CURDATE() AND status='1' ORDER BY id");
        }
        //echo $this->db->last_query();die;
        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->result_array();
        }
    }
    
    /****
     * For phase 2 new work
     * 
     * 
     */
    
    public function getCustomerMagazineArticleListNew(){
        
        
        $magazine_id = $this->get_id();
        //$catagory_id = $this->get_catagory_id();
        //$language_id = $this->get_language_id();
        $cat_language = $this->get_article_language();

        $sql1 = "SELECT a.id,a.title,a.author,a.status,a.publish_date,a.category_id,a.tags,a.publish_from,a.publish_to,a.article_language,
            b.name,c.language,a.magazine_id FROM tbl_magazine_articles as a LEFT JOIN tbl_customer as b ON a.customer_id = b.id LEFT JOIN tbl_language as c ON
            a.language_id = c.id WHERE a.magazine_id REGEXP '(^|,)($magazine_id)(,|$)'";
        
       
        //
        if ($catagory_id != "") {
            $sql1 .= " AND FIND_IN_SET('$catagory_id', a.category_id) > 0";
            //$sql1 .= " AND a.category_id LIKE'%$catagory_id%'";
        }
        if ($language_id != "") {
            $sql1 .= " AND a.language_id = $language_id ";
        }
        if ($cat_language != '') {
            $sql1 .=" AND a.article_language = '$cat_language'
                    ";
        }

        

        $sql = $this->db->query($sql1);
        // echo $this->db->last_query();die;
//echo $this->db->last_query();die;
        //echo $this->db->last_query();

        /* PAGINATION */
        $config['base_url'] = base_url() . 'index.php/'.'CustomerArticlelist?catagory='. $catagory_id .'&lang='. $language_id.'&rowId='.$magazine_id;
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

        $sql_data1 = "SELECT a.id,a.title,a.author,a.status,a.publish_date,a.category_id,a.tags,a.publish_from,a.publish_to,a.article_language,
            b.name,c.language,a.magazine_id FROM tbl_magazine_articles as a LEFT JOIN tbl_customer as b ON a.customer_id = b.id LEFT JOIN tbl_language as c ON
            a.language_id = c.id WHERE a.magazine_id REGEXP '(^|,)($magazine_id)(,|$)'";
        if ($language_id != "") {
            $sql_data1 .= " AND a.language_id = $language_id";
        }
        if ($catagory_id != "") {
            $sql_data1 .= "  AND FIND_IN_SET('$catagory_id', a.category_id) > 0";
            //$sql_data1 .= " AND a.category_id LIKE'%$catagory_id%'";
        }
        if ($cat_language != '') {
            $sql_data1 .=" AND a.article_language = '$cat_language'
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
                $category_id = $value['category_id'];
                $implode = explode(',', $category_id);
                $category_list = implode(",", $implode);
                $magazine_ids=$value['magazine_id'];
                $data[$key]['magazine_name']=$this->getMagazinesNameByMaganizeId($magazine_ids);
                //echo $category_list; die;
                /*if ($language_id != "") {

                    if ($language_id == "1") {
                        $category_name = 'category_name';
                    } elseif ($language_id == "2") {
                        $category_name = 'category_name_portuguese';
                    } elseif ($language_id == "3") {
                        $category_name = 'category_name_spanish';
                    }
                } else {
                    $category_name = 'category_name';
                }*/
                if($value['language'] == 'Portuguese'){ 
                           $category_name = 'category_name_portuguese'; 
                        }elseif($value['language'] == 'Spanish'){
                            $category_name = 'category_name_spanish';
                        }else{
                            $category_name = 'category_name';
                        }

                $sql1 = $this->db->query("SELECT $category_name as category_name FROM tbl_category WHERE id IN ($category_list) AND $category_name!=''");
                //$sql1 = $this->db->query("SELECT category_name FROM tbl_category WHERE id IN ($category_list)");
                $nameArray = $sql1->result_array();
                $data[$key]['categoy_name'] = $nameArray;

                $sql2 = $this->db->query("SELECT id FROM tbl_magazine_article_comment WHERE article_id = $article_id AND STATUS = '1'");
                $data[$key]['comment_count'] = $sql2->num_rows();
            }
           
            return $data;
        }

        
        
        
    }
    
    public function getMagazinesNameByMaganizeId($ids){
        //echo $id."<br>";
        if($ids!=''){
            $ids_array=explode(",",$ids);
        }else{
            $ids_array=array();
        }
        $query=$this->db
             ->select('m.title')
             ->where_in('m.id',$ids_array)
             ->from('tbl_magazine as m')
             ->get();
    if($query->num_rows()>0){
        $magazineArray=$query->result_array();
        foreach($magazineArray as $temp){
            $magazine[]=$temp['title'];
        }
        
        if(is_array($magazine) && count($magazine>0)){
            $magazine_str=implode(",",$magazine);
        }else{
            $magazine_str="";
        }     
    }else{
    $magazine_str="";    
    }
        return $magazine_str;
    }

    public function getMagazineCodeListUpdated($used, $actualPage) {

        $magazine_id = $this->get_magazine_id();
        $customerId = $this->get_customer_id();

        $sql = "SELECT mp.id, m.title, mp.password, mp.read_status 
                FROM tbl_magazine_password mp
                JOIN tbl_magazine m ON( mp.magazine_id = m.id )
                WHERE 1 = 1";

        if( !empty( $magazine_id ) ){
            $sql .= " AND mp.magazine_id='$magazine_id' ";
        } else {
            $sql .= " AND magazine_id IN( SELECT m2.id FROM tbl_magazine m2 WHERE customer_id = $customerId AND m2.status = '1' 
                        AND m2.publish_date_to >= CURDATE() ) ";
        }

        if( $used !== "" ){
            $sql .= " AND mp.read_status = '$used' ";
        }

        $result = $this->db->query($sql);

        $data = array();
        $data["totalValues"] = $result->num_rows();

        $config['base_url'] = base_url().'index.php/magazineCodes';
        $config['total_rows'] = $result->num_rows();
        $config['per_page'] = 10;
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

        if ($actualPage) {
            $page = $actualPage;
        } else {
            $page = 1;
        }

        if ($page == 1) {
            $limitPage = 0;
        } else {
            $limitPage = ($page - 1) * 10;
        }

        $sql = "SELECT mp.id, m.title, mp.password, mp.read_status 
                FROM tbl_magazine_password mp
                JOIN tbl_magazine m ON( mp.magazine_id = m.id )
                WHERE 1 = 1";

        if( !empty( $magazine_id ) ){
            $sql .= " AND mp.magazine_id = '$magazine_id' ";
        } else {
            $sql .= " AND magazine_id IN( SELECT m2.id FROM tbl_magazine m2 WHERE customer_id = $customerId AND m2.status = '1' 
                        AND m2.publish_date_to >= CURDATE() ) ";
        }

        if( $used !== "" ){
            $sql .= " AND mp.read_status = '$used' ";
        }

        $sql .= " LIMIT $limitPage, 10";

//        echo $sql; exit;

        $result = $this->db->query($sql);

        $data["result"] = $result->result_array();

        return $data;

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
