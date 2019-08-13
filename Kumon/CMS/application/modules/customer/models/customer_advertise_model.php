<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class customer_advertise_model extends CI_Model {
    
    
    private $_tableName = 'tbl_magazine_advertisement';
    private $_id = "";
    private $_title = "";
    private $_cover_image = "";
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
    private $_customer_id = "";
    private $_magazine_id = "";
    private $_portrait_image = "";
    private $_landscape_image = "";
    // wootrix phase 2
    private $_embed_video="";
    private $_embed_thumb="";

    /* GETTER AND SETTER */
    
    function getEmbed_thumb() {
        return $this->_embed_thumb;
    }

    function setEmbed_thumb($embed_thumb) {
        $this->_embed_thumb = $embed_thumb;
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

    public function get_media_type() {
        return $this->_media_type;
    }

    public function set_media_type($_media_type) {
        $this->_media_type = $_media_type;
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

    public function get_size() {
        return $this->_size;
    }

    public function set_size($_size) {
        $this->_size = $_size;
    }

    public function get_link() {
        return $this->_link;
    }

    public function set_link($_link) {
        $this->_link = $_link;
    }

    public function get_catagory_id() {
        return $this->_catagory_id;
    }

    public function set_catagory_id($_catagory_id) {
        $this->_catagory_id = $_catagory_id;
    }

    public function get_language_id() {
        return $this->_language_id;
    }

    public function set_language_id($_language_id) {
        $this->_language_id = $_language_id;
    }

    public function get_display_time() {
        return $this->_display_time;
    }

    public function set_display_time($_display_time) {
        $this->_display_time = $_display_time;
    }

    public function get_layout_type() {
        return $this->_layout_type;
    }

    public function set_layout_type($_layout_type) {
        $this->_layout_type = $_layout_type;
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

    public function get_customer_id() {
        return $this->_customer_id;
    }

    public function set_customer_id($_customer_id) {
        $this->_customer_id = $_customer_id;
    }

    public function get_magazine_id() {
        return $this->_magazine_id;
    }

    public function set_magazine_id($_magazine_id) {
        $this->_magazine_id = $_magazine_id;
    }
     public function get_portrait_image() {
        return $this->_portrait_image;
    }

    public function set_portrait_image($_portrait_image) {
        $this->_portrait_image = $_portrait_image;
    }

    public function get_landscape_image() {
        return $this->_landscape_image;
    }

    public function set_landscape_image($_landscape_image) {
        $this->_landscape_image = $_landscape_image;
    }
    
    // wootrix phase 2
    
    public function get_embed_video() {
        return $this->_embed_video;
    }

    public function set_embed_video($_embed_video) {
        $this->_embed_video = $_embed_video;
    }
    // end

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

    /* CATAGORY LISTING */

    /**
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * listing of catagory 
     * */
    public function getCatagoryList() {
        $sql = $this->db->query("SELECT id,category_name FROM tbl_category WHERE status = '1' ORDER BY id DESC");

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->result_array();
        }
    }

    /* Public function getMagazineList */

    public function getMagazineList() {
        $customer_id = $this->get_customer_id();
        $sql = $this->db->query("SELECT id,title FROM tbl_magazine WHERE status = '1' AND customer_id='$customer_id' AND publish_date_to >= CURDATE()");

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->result_array();
        }
    }

    /* ADD A NEW ADD */
    /* used for insert a new add
     * @author Techahead
     * @access Public
     * @param
     * all fields data 
     * @return 
     * true/false
     * */

    public function addCustomerAdvertise() {

        $media_type = $this->get_media_type();
        $link = $this->get_link();
        $cover_img = $this->get_cover_image();
        $lanscape_image= $this->get_landscape_image();
        $portrate_image = $this->get_portrait_image();
        $magazine_list = $this->get_magazine_id();
        $language_id = $this->get_language_id();
        $layout = $this->get_layout_type();
        $customer_id = $this->get_customer_id();
        $status = $this->get_status();
        $date_from = $this->get_publish_date_from();
        $date_to = $this->get_publish_date_to();
        $time = $this->get_display_time();
        $size= $this->get_size();
        $size1 = round($size/1024,3)."".'kb';

        //$status = $this->get_status();
        $embed_video=$this->get_embed_video();


        $data = array(
            'cover_image' => $cover_img,
            'media_type' => $media_type, 
            'link' => $link,
            'magazine_id' => $magazine_list,
            'language_id' => $language_id,
            'layout_type' => $layout,
            'customer_id' => $customer_id,
            'publish_date_from' => $date_from,
            'publish_date_to' => $date_to,
            'display_time' => $time,
            'size'=>$size1,
            'status' => $status,
            'landscape_image' => $lanscape_image,
            'portrait_image'=>$portrate_image,
            'embed_video'=>$embed_video,
            'embed_thumb'=>  $this->getEmbed_thumb()
                );
        $sql = $this->db->set('created_date', 'now()', FALSE)->insert('tbl_magazine_advertisement', $data);
//echo $this->db->last_query(); die;
        if ($sql) {
            return TRUE;
        }
        return FALSE;
    }

    /* GETTING LIST OF ALL ADVERTISE */

    /**
     * @author Techahead
     * @access Public
     * @param
     * all fields data 
     * @return 
     * all ads list
     * */
    public function getAdvertiseList() {
        $customer_id = $this->get_customer_id();
        $catagory_id = $this->get_catagory_id();
        $language_id = $this->get_language_id();
        
        $sql1 = "SELECT embed_video,id,cover_image,media_type,size,link,magazine_id,language_id,status,layout_type,is_approved ,publish_date_from,publish_date_to FROM tbl_magazine_advertisement WHERE customer_id= '$customer_id'";
        if ($catagory_id != '') {
            $sql1 .="  AND magazine_id LIKE '%$catagory_id%'";
        }
        if ($language_id != '') {
            $sql1 .=" AND language_id = '$language_id'
                    ";
        }

        $sql = $this->db->query($sql1); 
        //echo $this->db->last_query();

        /* PAGINATION */
        $config['base_url'] = base_url() . 'index.php/' . 'customeradvertiselisting?catagory='.$catagory_id.'&lang='.$language_id;
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
         
        /*---------------------*/
        $sql_data1 = "SELECT embed_video,id,cover_image,media_type,size,link,magazine_id,language_id,status,layout_type,is_approved 
            ,publish_date_from,publish_date_to FROM tbl_magazine_advertisement 
            WHERE customer_id= '$customer_id'";
        if ($catagory_id != '') {
            $sql_data1 .="  AND magazine_id LIKE '%$catagory_id%'";
        }
        if ($language_id != '') {
            $sql_data1 .=" AND language_id = '$language_id'
";
        }
        $sql_data1 .=" ORDER BY created_date DESC LIMIT $limitPage,10";


        $sql_data = $this->db->query($sql_data1);


        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            //return $data = $sql->result_array();
            $data = $sql_data->result_array();
            foreach ($data as $key => $value) {

                $magazine_id = $value['magazine_id'];
                $implode = explode(',', $magazine_id);
                $magazine_list = implode(",", $implode);
                $sql1 = $this->db->query("SELECT title FROM tbl_magazine WHERE id IN ($magazine_list)");
                $nameArray = $sql1->result_array();
                $data[$key]['title'] = $nameArray;
            }
            return $data;
        }
    }

    /* ALL ADVERTISE RECORD */

    /** all ads count
     * @author Techahead
     * @access Public
     * @param
     * all fields data 
     * @return 
     * count
     * */
    public function allAdvertise() {
        $customer_id = $this->get_customer_id();

        $sql = $this->db->query("SELECT id,cover_image,media_type,size,link,magazine_id,language_id,status,layout_type,is_approved FROM tbl_magazine_advertisement WHERE customer_id = '$customer_id'");
        return $sql->num_rows();
    }

    /* GET ALL PUBLISHED ADVERTISE */

    public function allPublishedAdvertise() {
        $customer_id = $this->get_customer_id();
        $sql = $this->db->query("SELECT id,cover_image,media_type,size,link,magazine_id,language_id,status,layout_type,is_approved FROM tbl_magazine_advertisement WHERE customer_id = '$customer_id' AND status='1'
                AND is_approved='1'");
        return $sql->num_rows();
    }

    /* DELETED ADVERTISE RECORD */

    /** deleted ads count
     * @author Techahead
     * @access Public
     * @param
     * all fields data 
     * @return 
     * count
     * */
    public function allDeletedAdvertise() {

        $customer_id = $this->get_customer_id();

        $sql = $this->db->query("SELECT id,cover_image,media_type,size,link,catagory_id,language_id,status,layout_type,is_approved FROM tbl_magazine_advertisement WHERE status='3'
            AND customer_id = '$customer_id'");
        return $sql->num_rows();
    }

    /* ALL DRAFTED ADVERTISE */

    public function allDraftedAdvertise() {
        $customer_id = $this->get_customer_id();
        $sql = $this->db->query("SELECT id,cover_image,media_type,size,link,catagory_id,language_id,status,layout_type,is_approved FROM tbl_magazine_advertisement WHERE status='0'
            AND customer_id = '$customer_id'");
        return $sql->num_rows();
    }

    /* All REVIEW ADVERTISE */

    public function allReviewAdvertise() {

        $customer_id = $this->get_customer_id();
        $sql = $this->db->query("SELECT id,cover_image,media_type,size,link,catagory_id,language_id,status,layout_type,is_approved FROM tbl_magazine_advertisement WHERE status='1'
            AND is_approved !='1' AND customer_id = '$customer_id'");
        return $sql->num_rows();
    }

    /**
     * @author Techahead
     * @access Public
     * @param
     * all fields data 
     * @return 
     * all ads list
     * */
    public function getCustomerDeletedAdvertiseList() {
        $customer_id = $this->get_customer_id();
        $catagory_id = $this->get_catagory_id();
        $language_id = $this->get_language_id();
        
        $sql1 = "SELECT embed_video,id,cover_image,media_type,size,link,magazine_id,language_id,status,layout_type,is_approved FROM tbl_magazine_advertisement WHERE customer_id= '$customer_id'
                    AND status='3'";
        if ($catagory_id != '') {
            $sql1 .="  AND magazine_id LIKE '%$catagory_id%'";
        }
        if ($language_id != '') {
            $sql1 .=" AND language_id = '$language_id'
                    ";
        }

        $sql = $this->db->query($sql1);        

        //echo $this->db->last_query();

        /* PAGINATION */
        $config['base_url'] = base_url() . 'index.php/' . 'customerDeletedarticle?catagory='.$catagory_id.'&lang='.$language_id;
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
        
        $sql_data1 = "SELECT embed_video,id,cover_image,media_type,size,link,magazine_id,language_id,status,layout_type,is_approved FROM tbl_magazine_advertisement
                WHERE customer_id= '$customer_id' AND status='3'";
        if ($catagory_id != '') {
            $sql_data1 .="  AND magazine_id LIKE '%$catagory_id%'";
        }
        if ($language_id != '') {
            $sql_data1 .=" AND language_id = '$language_id'
";
        }
        $sql_data1 .=" ORDER BY created_date DESC LIMIT $limitPage,10";


        $sql_data = $this->db->query($sql_data1);
       
        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            //return $data = $sql->result_array();
            $data = $sql_data->result_array();
            foreach ($data as $key => $value) {

                $magazine_id = $value['magazine_id'];
                $implode = explode(',', $magazine_id);
                $magazine_list = implode(",", $implode);
                $sql1 = $this->db->query("SELECT title FROM tbl_magazine WHERE id IN ($magazine_list)");
                $nameArray = $sql1->result_array();
                $data[$key]['title'] = $nameArray;
            }
            return $data;
        }
    }

    /**
     * @author Techahead
     * @access Public
     * @param
     * all fields data 
     * @return 
     * all ads list
     * */
    public function getCustomerPublishAdvertiseList() {
        $customer_id = $this->get_customer_id();
        $catagory_id = $this->get_catagory_id();
        $language_id = $this->get_language_id();
        
        $sql1 = "SELECT id,cover_image,media_type,size,link,magazine_id,language_id,status,layout_type,is_approved 
            ,publish_date_from,publish_date_to FROM tbl_magazine_advertisement 
            WHERE customer_id= '$customer_id' AND status='1' AND is_approved='1'";
                    
        if ($catagory_id != '') {
            $sql1 .="  AND magazine_id LIKE '%$catagory_id%'";
        }
        if ($language_id != '') {
            $sql1 .=" AND language_id = '$language_id'
                    ";
        }

        $sql = $this->db->query($sql1); 

        
        //echo $this->db->last_query();

        /* PAGINATION */
        $config['base_url'] = base_url() . 'index.php/' . 'customerPublishedarticle?catagory='.$catagory_id.'&lang='.$language_id;
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
        /*------------------------------*/
        $sql_data1 = "SELECT id,cover_image,media_type,size,link,magazine_id,language_id,status,layout_type,is_approved 
            ,publish_date_from,publish_date_to FROM tbl_magazine_advertisement
                WHERE customer_id= '$customer_id' AND is_approved='1' AND status='1' ";
        if ($catagory_id != '') {
            $sql_data1 .="  AND magazine_id LIKE '%$catagory_id%'";
        }
        if ($language_id != '') {
            $sql_data1 .=" AND language_id = '$language_id'
";
        }
        $sql_data1 .=" ORDER BY created_date DESC LIMIT $limitPage,10";


        $sql_data = $this->db->query($sql_data1);
        
       

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            //return $data = $sql->result_array();
            $data = $sql_data->result_array();
            foreach ($data as $key => $value) {

                $magazine_id = $value['magazine_id'];
                $implode = explode(',', $magazine_id);
                $magazine_list = implode(",", $implode);
                $sql1 = $this->db->query("SELECT title FROM tbl_magazine WHERE id IN ($magazine_list)");
                $nameArray = $sql1->result_array();
                $data[$key]['title'] = $nameArray;
            }
            return $data;
        }
    }

    /* ALL DRAFTED ADVERTISE */

    /**
     * @author Techahead
     * @access Public
     * @param
     * all fields data 
     * @return 
     * all ads list
     * */
    public function getCustomerDraftedAdvertiseList() {
        $customer_id = $this->get_customer_id();
        $magazine_id = $this->get_catagory_id();
        $language_id = $this->get_language_id();
        
        $sql1 = "SELECT embed_video,id,cover_image,media_type,size,link,magazine_id,language_id,status,layout_type,is_approved FROM tbl_magazine_advertisement WHERE customer_id= '$customer_id'
                    AND status='0'";
        if ($language_id != "") {
           $sql1 .= " AND language_id = '$language_id'"; 
        }
        if ($magazine_id != "") {
             $sql1 .= " AND magazine_id LIKE '%$magazine_id%'";
        }
        
        $sql = $this->db->query("$sql1");
        
        //echo $this->db->last_query();

        /* PAGINATION */
        $config['base_url'] = base_url() . 'index.php/' . 'customerDraftedarticle?catagory='.$catagory_id.'&lang='.$language_id;
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
        /*------*/
        
        $sql_data1 = "SELECT embed_video,id,cover_image,media_type,size,link,magazine_id,language_id,status,layout_type,is_approved FROM tbl_magazine_advertisement WHERE customer_id= '$customer_id'
                    AND status='0'";
        if ($language_id != "") {
           $sql_data1 .= " AND language_id = '$language_id'"; 
        }
        if ($magazine_id != "") {
             $sql_data1 .= " AND magazine_id LIKE '%$magazine_id%'";
        }
        $sql_data1 .= " ORDER BY created_date DESC LIMIT $limitPage,10";
        
        $sql_data = $this->db->query($sql_data1);

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            //return $data = $sql->result_array();
            $data = $sql_data->result_array();
            foreach ($data as $key => $value) {

                $magazine_id = $value['magazine_id'];
                $implode = explode(',', $magazine_id);
                $magazine_list = implode(",", $implode);
                $sql1 = $this->db->query("SELECT title FROM tbl_magazine WHERE id IN ($magazine_list)");
                $nameArray = $sql1->result_array();
                $data[$key]['title'] = $nameArray;
            }
            return $data;
        }
    }

    /* ALL DRAFTED ADVERTISE */

    /**
     * @author Techahead
     * @access Public
     * @param
     * all fields data 
     * @return 
     * all ads list
     * */
    public function getCustomerReviewAdvertiseList() {

        $customer_id = $this->get_customer_id();
        $catagory_id = $this->get_catagory_id();
        $language_id = $this->get_language_id();
        
        $sql1 = "SELECT id,cover_image,media_type,size,link,magazine_id,language_id,status,layout_type,is_approved FROM tbl_magazine_advertisement WHERE customer_id= '$customer_id'
                    AND status='1' AND is_approved !='1'";
        if ($catagory_id != "") {
            //$sql1 .= " AND magazine_id LIKE '%$catagory_id%'";
            $sql1 .= " AND is_approved = '$catagory_id'";
        }
        if ($language_id != "") {
            $sql1 .= " AND language_id = '$language_id'";
        }

        $sql = $this->db->query($sql1);
        //echo $this->db->last_query();

        /* PAGINATION */
        $config['base_url'] = base_url() . 'index.php/' . 'customerReviewarticle?catagory='.$catagory_id.'&lang='.$language_id;
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
        /*paging*/
         $sql_data1 = "SELECT embed_video,id,cover_image,media_type,size,link,magazine_id,language_id,status,layout_type,is_approved FROM tbl_magazine_advertisement WHERE customer_id= '$customer_id'
                    AND status='1' AND is_approved !='1'";
        if ($catagory_id != "") {
            //$sql_data1 .= " AND magazine_id LIKE '%$catagory_id%'";
            $sql1 .= " AND is_approved = '$catagory_id'";
        }
        if ($language_id != "") {
            $sql_data1 .= " AND language_id = '$language_id'";
        }
        $sql_data1 .= "ORDER BY created_date DESC LIMIT $limitPage,10";

        $sql_data = $this->db->query($sql_data1);

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            //return $data = $sql->result_array();
            $data = $sql_data->result_array();
            foreach ($data as $key => $value) {

                $magazine_id = $value['magazine_id'];
                $implode = explode(',', $magazine_id);
                $magazine_list = implode(",", $implode);
                $sql1 = $this->db->query("SELECT title FROM tbl_magazine WHERE id IN ($magazine_list)");
                $nameArray = $sql1->result_array();
                $data[$key]['title'] = $nameArray;
            }
            return $data;
        }
    }

    /** DETAILS OF A AD
     * @author Techahead
     * @access Public
     * @param    
     * @return 
     * array
     * */
    public function getAdvertiseDetails() {

        $advertise_id = $this->get_id();

        $sql = $this->db->query("SELECT embed_thumb,embed_video,id,cover_image,media_type,size,language_id,link,publish_date_from,publish_date_to,magazine_id,status,
            is_approved,layout_type,display_time FROM tbl_magazine_advertisement WHERE id = $advertise_id");

        if ($sql) {
            $data = $sql->row_array();
            $magazine_id = $data['magazine_id'];
            $implode = explode(',', $magazine_id);
            $magazine_list = implode(",", $implode);

            $sql1 = $this->db->query("SELECT title FROM tbl_magazine WHERE id IN ($magazine_list)");
            $nameArray = $sql1->result_array();
            $data['title'] = $nameArray;

            return $data;
        }
        return FALSE;
    }

    /* Update Customer ADVERTISE */

    public function UpdateCustomerAdvertise() {
        
        $advertise_id = $this->get_id();
        $media_type = $this->get_media_type();
        $link = $this->get_link();
        $cover_img = $this->get_cover_image();
        $magazine_list = $this->get_magazine_id();
        $language_id = $this->get_language_id();
        $layout = $this->get_layout_type();
        $status = $this->get_status();
        $publish_from = $this->get_publish_date_from();
        $publish_to = $this->get_publish_date_to();
        $time = $this->get_display_time();
        if($this->get_cover_image()=='embed'){
            $embedVideo=$this->get_embed_video();
            $thumb=  $this->getEmbed_thumb();
            $sql = $this->db->query("UPDATE tbl_magazine_advertisement SET embed_video='$embedVideo',embed_thumb='$thumb', cover_image ='embed',portrait_image='',landscape_image='', media_type =' $media_type' ,link = '$link',magazine_id = '$magazine_list', 
                language_id = '$language_id',publish_date_to = '$publish_to',publish_date_from='$publish_from',is_approved='0',
                    display_time='$time',layout_type = '$layout',status = '$status',created_date=now() WHERE id='$advertise_id'");
            
        }else{
//        $size = $this->get_size();
//         
//        $size1 = round($size/1024,3)."".'kb';

        $sql = $this->db->query("UPDATE tbl_magazine_advertisement SET embed_video='',media_type =' $media_type' ,link = '$link',magazine_id = '$magazine_list', 
                language_id = '$language_id',publish_date_to = '$publish_to',publish_date_from='$publish_from',is_approved='0',
                    display_time='$time',layout_type = '$layout',status = '$status',created_date=now() WHERE id='$advertise_id'");
        }
        //echo $this->db->last_query(); die;
        if ($sql) {
            return TRUE;
        }
        return FALSE;
    }

    /* UPDATE IMAGE/VIDEO PATH */

    public function UpdateImagePath() {

        $advertise_id = $this->get_id();
        $cover_img = $this->get_cover_image();
        $size = $this->get_size();
        $size1 = round($size/1024,3)."".'kb';

        $sql = $this->db->query("UPDATE tbl_magazine_advertisement SET cover_image='$cover_img',size='$size1',created_date=now() WHERE id='$advertise_id'");
        if ($sql) {
            return TRUE;
        }
        return FALSE;
    }
    
    /*Update Cover IMAGE 1*/
    public function UpdateLandscapeImage(){
        $advertise_id = $this->get_id();
        $landscape_imag = $this->get_landscape_image();
        $sql = $this->db->query("UPDATE tbl_magazine_advertisement SET landscape_image='$landscape_imag',created_date=now() WHERE id='$advertise_id'");
        if ($sql) {
            return TRUE;
        }
        return FALSE;
    }
    
  /*Update Cover IMAGE 1*/
    public function UpdatePortableImage(){
        $advertise_id = $this->get_id();
        $portrait_image = $this->get_portrait_image();
         $sql = $this->db->query("UPDATE tbl_magazine_advertisement SET portrait_image='$portrait_image',created_date=now() WHERE id='$advertise_id'");
       
        if ($sql) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * @author Techahead
     * @access Public
     * @param
     * all fields data 
     * @return 
     * all new ads list
     * */
    public function publishCustomerAdvertise() {

        $advertise_id = $this->get_id();
        $publish_dat_frm = $this->get_publish_date_from();
        $publish_dat_to = $this->get_publish_date_to();
        $disply_time = $this->get_display_time();

        $sql = $this->db->query("UPDATE tbl_magazine_advertisement SET publish_date_to='$publish_dat_to',publish_date_from='$publish_dat_frm',
                display_time='$disply_time',status='1',created_date=now() WHERE id = $advertise_id");
        
        //echo $this->db->last_query(); die;

        if ($sql) {
            return TRUE;
        }
        return FALSE;
    }

    /* ---------- */
    /*     * DELETE A AD
     * @author Techahead
     * @access Public
     * @param    
     * @return 
     * count
     * */

    public function deleteAdvertise() {
        $advertise_id = $this->get_id();

        $sql = $this->db->query("UPDATE tbl_magazine_advertisement SET status='3',created_date=now() WHERE id = $advertise_id");
        if ($sql) {
            return TRUE;
        }
        return FALSE;
    }

    /* PERMANENTLY DELETE A ADVERTISE */

    public function shiftDeleteAdvertise() {

        $advertise_id = $this->get_id();
        $sql = $this->db->query("DELETE  FROM tbl_magazine_advertisement WHERE id = $advertise_id");

        if ($sql) {
            return TRUE;
        }
        return FALSE;
    }

    /* RESTORE ADS IN SYSTEM */

    public function restoreCustomerAdvertise() {
        $advertise_id = $this->get_id();

        $sql = $this->db->query("UPDATE tbl_magazine_advertisement SET status='0',is_approved = '0',created_date=now() WHERE id = $advertise_id");

        if ($sql) {
            return TRUE;
        }
        return FALSE;
    }

    /* SEND ADS FOR REVIEW */

    public function sendAdsForReview() {

        $advertise_id = $this->get_id();        
        $sql = $this->db->query("UPDATE tbl_magazine_advertisement SET status='1',created_date=now() WHERE id = $advertise_id");

        if ($sql) {
            return TRUE;
        }
        return FALSE;
    }

    /* ADS RESULT FROM ADS_REPORT */

    public function adsReport() {

        $advertise_id = $this->get_id();

        $sql = $this->db->query("SELECT id,latitude,longitude,device_type FROM tbl_magazine_ads_report WHERE article_id = $advertise_id");

        if ($sql) {

            //return $data = $sql->result_array();
            $data = $sql->result_array();
            foreach ($data as $value) {                
                $data['total_count'] = $sql->num_rows();                
            }

            return $data ;
        }
        return FALSE;
    }

}

?>
