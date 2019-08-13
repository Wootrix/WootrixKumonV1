<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class advertise_model extends CI_Model
{


    private $_tableName = 'tbl_advertisement';
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
    private $_portrait_image = "";
    private $_landscape_image = "";
    // wootrix phase 2
    private $_embed_video = "";
    private $_magazine_id = "";
    private $_path = "";
    private $_embed_thumb = "";

    function getPath()
    {
        return $this->_path;
    }

    function setPath($path)
    {
        $this->_path = $path;
    }

    /* GETTER AND SETTER */

    public function get_tableName()
    {
        return $this->_tableName;
    }

    public function set_tableName($_tableName)
    {
        $this->_tableName = $_tableName;
    }

    public function get_id()
    {
        return $this->_id;
    }

    public function set_id($_id)
    {
        $this->_id = $_id;
    }

    public function get_title()
    {
        return $this->_title;
    }

    public function set_title($_title)
    {
        $this->_title = $_title;
    }

    public function get_cover_image()
    {
        return $this->_cover_image;
    }

    public function set_cover_image($_cover_image)
    {
        $this->_cover_image = $_cover_image;
    }

    public function get_media_type()
    {
        return $this->_media_type;
    }

    public function set_media_type($_media_type)
    {
        $this->_media_type = $_media_type;
    }

    public function get_publish_date_from()
    {
        return $this->_publish_date_from;
    }

    public function set_publish_date_from($_publish_date_from)
    {
        $this->_publish_date_from = $_publish_date_from;
    }

    public function get_publish_date_to()
    {
        return $this->_publish_date_to;
    }

    public function set_publish_date_to($_publish_date_to)
    {
        $this->_publish_date_to = $_publish_date_to;
    }

    public function get_size()
    {
        return $this->_size;
    }

    public function set_size($_size)
    {
        $this->_size = $_size;
    }

    public function get_link()
    {
        return $this->_link;
    }

    public function set_link($_link)
    {
        $this->_link = $_link;
    }

    public function get_catagory_id()
    {
        return $this->_catagory_id;
    }

    public function set_catagory_id($_catagory_id)
    {
        $this->_catagory_id = $_catagory_id;
    }

    public function get_language_id()
    {
        return $this->_language_id;
    }

    public function set_language_id($_language_id)
    {
        $this->_language_id = $_language_id;
    }

    public function get_display_time()
    {
        return $this->_display_time;
    }

    public function set_display_time($_display_time)
    {
        $this->_display_time = $_display_time;
    }

    public function get_layout_type()
    {
        return $this->_layout_type;
    }

    public function set_layout_type($_layout_type)
    {
        $this->_layout_type = $_layout_type;
    }

    public function get_status()
    {
        return $this->_status;
    }

    public function set_status($_status)
    {
        $this->_status = $_status;
    }

    public function get_created_date()
    {
        return $this->_created_date;
    }

    public function set_created_date($_created_date)
    {
        $this->_created_date = $_created_date;
    }

    public function get_portrait_image()
    {
        return $this->_portrait_image;
    }

    public function set_portrait_image($_portrait_image)
    {
        $this->_portrait_image = $_portrait_image;
    }

    public function get_landscape_image()
    {
        return $this->_landscape_image;
    }

    public function set_landscape_image($_landscape_image)
    {
        $this->_landscape_image = $_landscape_image;
    }

    public function get_customer_id()
    {
        return $this->_customer_id;
    }

    public function set_customer_id($_customer_id)
    {
        $this->_customer_id = $_customer_id;
    }

    // wootrix phase 2

    public function get_embed_video()
    {
        return $this->_embed_video;
    }

    public function set_embed_video($_embed_video)
    {
        $this->_embed_video = $_embed_video;
    }

    public function get_magazine_id()
    {
        return $this->_magazine_id;
    }

    public function set_magazine_id($_magazine_id)
    {
        $this->_magazine_id = $_magazine_id;
    }

    function getEmbed_thumb()
    {
        return $this->_embed_thumb;
    }

    function setEmbed_thumb($embed_thumb)
    {
        $this->_embed_thumb = $embed_thumb;
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
    public function getLanguageList()
    {

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
    public function getCatagoryList()
    {
        $language_id = $this->get_language_id();

        if ($language_id != "") {
            if ($language_id == '1') {
                $sql = $this->db->query("select id,category_name,status,created_date from tbl_category where status='1' AND category_name !=''  ORDER BY category_name");
            } else if ($language_id == '2') {
                $sql = $this->db->query("select id,category_name_portuguese as category_name,status,created_date from tbl_category where status='1' AND category_name_portuguese !='' ORDER BY category_name");
            } else if ($language_id == '3') {
                $sql = $this->db->query("select id,category_name_spanish as category_name,status,created_date from tbl_category where status='1' AND category_name_spanish !='' ORDER BY category_name");
            }

            //$sql = $this->db->query("SELECT id,category_name FROM tbl_category WHERE status = '1' AND language='$language_id' ORDER BY id DESC");
        } else {
            $sql = $this->db->query("SELECT id,category_name FROM tbl_category WHERE status = '1' AND category_name != '' ORDER BY category_name ASC");
        }


        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->result_array();
        }
    }

    /* FORM CATAGORY LIST BY DEFAULT SELECTED ENGLISH */

    public function getFormCatagoryList()
    {
        $language = $this->get_language_id();
        if ($language != "") {
            //$sql = $this->db->query("SELECT id,category_name FROM tbl_category WHERE status = '1' AND language='$language' 
            //AND category_name != '' ORDER BY id ASC");
            if ($language == '1') {
                $res = $this->db->query("select id,category_name,status,created_date from tbl_category where status='1' AND category_name !=''  ORDER BY category_name");
            } else if ($language == '2') {
                $res = $this->db->query("select id,category_name_portuguese as category_name,status,created_date from tbl_category where status='1' AND category_name_portuguese !='' ORDER BY category_name");
            } else if ($language == '3') {
                $res = $this->db->query("select id,category_name_spanish as category_name,status,created_date from tbl_category where status='1' AND category_name_spanish !='' ORDER BY category_name");
            }
        } else {
            $sql = $this->db->query("SELECT id,category_name FROM tbl_category WHERE status = '1' AND category_name != '' ORDER BY category_name ASC");
        }

        //echo $this->db->last_query(); die;
        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->result_array();
        }
    }

    /* public function getFormCatagoryList() {        
      //$language = $this->get_language();
      $language = '1';
      if($language !=""){
      $sql = $this->db->query("SELECT id,category_name FROM tbl_category WHERE status = '1' AND language='$language' ORDER BY id DESC");
      }else{
      $sql = $this->db->query("SELECT id,category_name FROM tbl_category WHERE status = '1' ORDER BY id DESC");
      }

      if ($sql->num_rows() < 1) {
      return FALSE;
      } else {
      return $data = $sql->result_array();
      }
      } */

    /* ADD A NEW ADD */
    /* used for insert a new add
     * @author Techahead
     * @access Public
     * @param
     * all fields data 
     * @return 
     * true/false
     * */

    public function addAdvertise()
    {
        $media_type = $this->get_media_type();
        $link = $this->get_link();
        $cover_img = $this->get_cover_image();
        $catagory_list = $this->get_catagory_id();
        $language_id = $this->get_language_id();
        $layout = $this->get_layout_type();
        $status = $this->get_status();
        $date_from = $this->get_publish_date_from();
        $date_to = $this->get_publish_date_to();
        $time = $this->get_display_time();
        $customer_id = $this->get_customer_id();
        $lanscape_image = $this->get_landscape_image();
        $portrate_image = $this->get_portrait_image();
        $size = $this->get_size();

        // wootrix phase 2
        $embed_video = $this->get_embed_video();
        $magazine_list = $this->get_magazine_id();
        /* convert in kb
          if      ($size>=1000000000) {$size1=(bytes/1000000000).toFixed(2)+' GB';}
          else if ($size>=1000000)    {$size1=(bytes/1000000).toFixed(2)+' MB';}
          else if ($size>=1000)       {$size1=(bytes/1000).toFixed(2)+' KB';}
          else if ($size>1)           {$size1=bytes+' bytes';}
          else if ($size==1)          {$size1=bytes+' byte';}
          else                        {$size1='0 byte';}
         */


        $size1 = round($size / 1024, 3) . "" . 'kb';


        $data = array(
            'cover_image' => $cover_img,
            'media_type' => $media_type,
            'link' => $link,
            'catagory_id' => $catagory_list,
            'magazine_id' => $magazine_list,
            'language_id' => $language_id,
            'layout_type' => $layout,
            'publish_date_from' => $date_from,
            'publish_date_to' => $date_to,
            'display_time' => $time,
            'size' => $size1,
            'status' => $status,
            'customer_id' => $customer_id,
            'landscape_image' => $lanscape_image,
            'portrait_image' => $portrate_image,
            'embed_video' => $embed_video,
            'embed_thumb' => $this->getEmbed_thumb()
        );

        $sql = $this->db->set('created_date', 'now()', FALSE)->insert('tbl_advertisement', $data);
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
    public function getAdvertiseList()
    {
        $status = $this->get_status();
        $catagory_id = $this->get_catagory_id();

        $language_id = $this->get_language_id();

        $sql1 = "SELECT * FROM (SELECT 
    id,
    cover_image,
    media_type,
    size,
    link,
    catagory_id,
    language_id,
    status,
    layout_type,
    is_approved,
    publish_date_from,
            publish_date_to,embed_video
FROM
    tbl_advertisement UNION SELECT 
    id,
    cover_image,
    media_type,
    size,
    link,
    catagory_id,
    language_id,
    status,
    layout_type,
    is_approved,
    publish_date_from,
            publish_date_to,embed_video
FROM
    tbl_magazine_advertisement)as s";

        if ($status != '') {
            $sql1 .= " where s.status='$status'";
        }


        $sql = $this->db->query($sql1);


        /* PAGINATION */
        $config['base_url'] = base_url() . 'index.php/' . 'advertiselisting?catagory=' . $catagory_id . '&lang=' . $language_id;
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

        $sql_data1 = "SELECT * FROM (SELECT 
    source,
    id,
    cover_image,
    media_type,
    size,
    link,
    catagory_id,
    language_id,
    status,
    layout_type,
    is_approved,
    publish_date_from,
    magazine_id,
            publish_date_to,embed_video
FROM
    tbl_advertisement UNION SELECT 
    source,
    id,
    cover_image,
    media_type,
    size,
    link,
    catagory_id,
    language_id,
    status,
    layout_type,
    is_approved,
    publish_date_from,
    magazine_id,
            publish_date_to,embed_video
FROM
    tbl_magazine_advertisement) as s ";
        if ($status != '') {
            $sql_data1 .= " where s.status='$status'";
        }
        $sql_data1 .= " ORDER BY s.id DESC LIMIT $limitPage,10";


        $sql_data = $this->db->query($sql_data1);
        //echo $this->db->last_query();die;
        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            //return $data = $sql->result_array();
            $data = $sql_data->result_array();
            foreach ($data as $key => $value) {
                if ($language_id != '') {
                    if ($language_id == '1') {
                        $catagory_name = 'category_name';
                    } else if ($language_id == '2') {
                        $catagory_name = 'category_name_portuguese';
                    } elseif ($language_id == '3') {
                        $catagory_name = 'category_name_spanish';
                    }
                } else {
                    $catagory_name = 'category_name';
                }

                $category_id = $value['catagory_id'];

                $implode = explode(',', $category_id);
                $category_list = implode(",", $implode);
                if ($category_list == '') {
                    $category_list = '0';
                }
                //echo 'dd<pre>';print_r($category_list);die;
                $sql1 = $this->db->query("SELECT $catagory_name as category_name FROM tbl_category WHERE id IN ($category_list)");
                $nameArray = $sql1->result_array();
                $data[$key]['categoy_name'] = $nameArray;
            }
//            echo '<pre>';
//            print_r($data);die;
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
    public function allAdvertise()
    {

        $sql = $this->db->query("SELECT id,cover_image,media_type,size,link,catagory_id,language_id,status,layout_type,is_approved FROM tbl_advertisement UNION SELECT id,cover_image,media_type,size,link,catagory_id,language_id,status,layout_type,is_approved FROM tbl_magazine_advertisement");

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
    public function allDeletedAdvertise()
    {

        $sql = $this->db->query("SELECT id,cover_image,media_type,size,link,catagory_id,language_id,status,layout_type,is_approved FROM tbl_advertisement WHERE status='3' UNION SELECT id,cover_image,media_type,size,link,catagory_id,language_id,status,layout_type,is_approved FROM tbl_magazine_advertisement WHERE status='3'");
        return $sql->num_rows();
    }

    /**
     * @author Techahead
     * @access Public
     * @param
     * all fields data
     * @return
     * all new ads list
     * */
    public function getDeletedAdvertiseList()
    {

        $catagory_id = $this->get_catagory_id();
        $language_id = $this->get_language_id();

        $sql1 = "SELECT id,cover_image,media_type,size,link,catagory_id,language_id,status,layout_type,is_approved FROM tbl_advertisement
                WHERE status='3' ";
        if ($catagory_id != '') {
            $sql1 .= "  AND catagory_id LIKE '%$catagory_id%'";
        }
        if ($language_id != '') {
            //$sql1 .=" AND language_id = '$language_id'
            //      ";
        }

        $sql = $this->db->query($sql1);

        /* PAGINATION */
        $config['base_url'] = base_url() . 'index.php/' . 'deletedadvertiselisting?catagory=' . $catagory_id . '&lang=' . $language_id;
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


        $sql_data1 = "SELECT id,cover_image,media_type,size,link,catagory_id,language_id,status,layout_type,is_approved FROM tbl_advertisement
                WHERE status='3'";
        if ($catagory_id != '') {
            $sql_data1 .= "  AND catagory_id LIKE '%$catagory_id%'";
        }
        if ($language_id != '') {
            //$sql_data1 .=" AND language_id = '$language_id'
//";
        }
        $sql_data1 .= " ORDER BY created_date DESC LIMIT $limitPage,10";


        $sql_data = $this->db->query($sql_data1);

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            //return $data = $sql->result_array();
            $data = $sql_data->result_array();
            foreach ($data as $key => $value) {

                if ($language_id != "") {
                    if ($language_id == '1') {
                        $category_name = 'category_name';
                    } else if ($language_id == '2') {
                        $category_name = 'category_name_portuguese';
                    } else if ($language_id == '3') {
                        $category_name = 'category_name_spanish';
                    }
                } else {
                    $category_name = 'category_name';
                }

                $category_id = $value['catagory_id'];
                $implode = explode(',', $category_id);
                $category_list = implode(",", $implode);
                if (empty($category_list)) {
                    $category_list = '0';
                }
                $sql1 = $this->db->query("SELECT $category_name as category_name FROM tbl_category WHERE id IN ($category_list) AND $category_name !=''");
                $nameArray = $sql1->result_array();
                $data[$key]['categoy_name'] = $nameArray;
            }
            return $data;
        }
    }

    /* ALL PUBLISHED ADVERTISE MENS BELONG BETWEEN TODAY DATE */

    /** publish ads
     * @author Techahead
     * @access Public
     * @param
     * all fields data
     * @return
     * count
     * */
    public function allPublishedAdvertise()
    {

        //$sql =  $this->db->query("SELECT id FROM tbl_advertisement WHERE publish_date_from >= date AND publish_date_to <= date");

        $sql = $this->db->query("SELECT id FROM tbl_advertisement WHERE status='1' AND is_approved='1' UNION SELECT id FROM tbl_magazine_advertisement WHERE status='1' AND is_approved='1'");

        return $sql->num_rows();
    }

    /* ALL PUBLISHED ADVERTISE MENS BELONG BETWEEN TODAY DATE */

    /**
     * @author Techahead
     * @access Public
     * @param
     * all fields data
     * @return
     * all published ads list
     * */
    public function getPublishAdvertiseList()
    {

        $catagory_id = $this->get_catagory_id();
        $language_id = $this->get_language_id();

        $sql1 = "SELECT id,cover_image,media_type,size,link,catagory_id,language_id,status,layout_type,is_approved,publish_date_from,
            publish_date_to FROM tbl_advertisement WHERE
                 status='1'";
        if ($catagory_id != '') {
            $sql1 .= "  AND catagory_id LIKE '%$catagory_id%'";
        }
        if ($language_id != '') {
            //$sql1 .=" AND language_id = '$language_id'
            //         ";
        }

        $sql = $this->db->query($sql1);

        /* PAGINATION */
        $config['base_url'] = base_url() . 'index.php/' . 'publishadvertiselisting?catagory=' . $catagory_id . '&lang=' . $language_id;
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


        $sql_data1 = "SELECT id,cover_image,media_type,size,link,catagory_id,language_id,status,layout_type,is_approved,publish_date_from,
            publish_date_to FROM tbl_advertisement WHERE
                 status='1'";
        if ($catagory_id != '') {
            $sql_data1 .= " AND catagory_id LIKE '%$catagory_id%'";
        }
        if ($language_id != '') {
            //$sql_data1 .=" AND language_id = '$language_id'
//";
        }
        $sql_data1 .= " ORDER BY created_date DESC LIMIT $limitPage,10";


        $sql_data = $this->db->query($sql_data1);

//echo$this->db->last_query();
        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            //return $data = $sql->result_array();
            $data = $sql_data->result_array();
            foreach ($data as $key => $value) {

                if ($language_id != "") {
                    if ($language_id == '1') {
                        $category_name = 'category_name';
                    } else if ($language_id == '2') {
                        $category_name = 'category_name_portuguese';
                    } else if ($language_id == '3') {
                        $category_name = 'category_name_spanish';
                    }
                } else {
                    $category_name = 'category_name';
                }

                $category_id = $value['catagory_id'];
                $implode = explode(',', $category_id);
                $category_list = implode(",", $implode);
                if (!empty($category_list) && $category_name != '') {
                    $sql1 = $this->db->query("SELECT $category_name as category_name FROM tbl_category WHERE id IN ($category_list) AND $category_name !=''");
                } else {
                    $sql1 = $this->db->query("SELECT $category_name as category_name FROM tbl_category");
                }
                $nameArray = $sql1->result_array();
                $data[$key]['categoy_name'] = $nameArray;
            }
            return $data;
        }
    }

    /* ALL DRAFTED ADVERTISE (which are approved and not in date range,) */

    /** approved ad and not publish yet
     * @author Techahead
     * @access Public
     * @param
     * @return
     * count
     * */
    /*  public function allDraftedAdvertise() {

      $sql = $this->db->query("SELECT id FROM tbl_advertisement WHERE is_approved='1' AND status='1' AND CURDATE() NOT between publish_date_from AND publish_date_to
      AND publish_date_to <= CURDATE()");
      return $sql->num_rows();
      }
     * */

    public function allDraftedAdvertise()
    {

        $sql = $this->db->query("SELECT id FROM tbl_advertisement WHERE status='0' UNION SELECT id FROM tbl_magazine_advertisement WHERE status='0'");
        return $sql->num_rows();
    }

    /**
     * @author Techahead
     * @access Public
     * @param
     * all fields data
     * @return
     * all published ads list
     * */
    public function getDraftAdvertiseList()
    {

        $catagory_id = $this->get_catagory_id();
        $language_id = $this->get_language_id();


        $sql1 = "SELECT id,cover_image,media_type,size,link,catagory_id,language_id,status,layout_type,is_approved FROM tbl_advertisement
                WHERE status='0' ";
        if ($catagory_id != '') {
            $sql1 .= "  AND catagory_id LIKE '%$catagory_id%'";
        }
        if ($language_id != '') {
            //$sql1 .=" AND language_id = '$language_id'
            //";
        }

        $sql = $this->db->query($sql1);

        //echo $this->db->last_query();

        /* PAGINATION */
        $config['base_url'] = base_url() . 'index.php/' . 'draftadvertiselisting?catagory=' . $catagory_id . '&lang=' . $language_id;
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


        $sql_data1 = "SELECT embed_video,id,cover_image,media_type,size,link,catagory_id,language_id,status,layout_type,is_approved FROM tbl_advertisement
                WHERE status='0'";
        if ($catagory_id != '') {
            $sql_data1 .= "  AND catagory_id LIKE '%$catagory_id%'";
        }
        if ($language_id != '') {
            //$sql_data1 .=" AND language_id = '$language_id'
//";
        }
        $sql_data1 .= " ORDER BY created_date DESC LIMIT $limitPage,10";


        $sql_data = $this->db->query($sql_data1);


        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            //return $data = $sql->result_array();
            $data = $sql_data->result_array();
            foreach ($data as $key => $value) {

                if ($language_id != "") {
                    if ($language_id == '1') {
                        $category_name = 'category_name';
                    } else if ($language_id == '2') {
                        $category_name = 'category_name_portuguese';
                    } else if ($language_id == '3') {
                        $category_name = 'category_name_spanish';
                    }
                } else {
                    $category_name = 'category_name';
                }

                $category_id = $value['catagory_id'];
                $implode = explode(',', $category_id);
                $category_list = implode(",", $implode);
                if (empty($category_list)) {
                    $category_list = '0';
                }
                $sql1 = $this->db->query("SELECT $category_name as category_name FROM tbl_category WHERE id IN ($category_list) AND $category_name !=''");
                $nameArray = $sql1->result_array();
                $data[$key]['categoy_name'] = $nameArray;
            }
            return $data;
        }
    }

    /* ALL NEW ADVERTISE */
    /*     * all new saved ads
     * @author Techahead
     * @access Public
     * @param    
     * @return 
     * count
     * */

    public function allNewAdvertise()
    {

        $sql = $this->db->query("SELECT id FROM tbl_advertisement WHERE status='0' UNION SELECT id FROM tbl_magazine_advertisement WHERE status='0'");
        return $sql->num_rows();
    }

    /**
     * @author Techahead
     * @access Public
     * @param
     * all fields data
     * @return
     * all new ads list
     * */
    public function getNewAdvertiseList()
    {

        $catagory_id = $this->get_catagory_id();
        $language_id = $this->get_language_id();

        if ($status != "") {
            $sql1 = $this->db->query("SELECT id,cover_image,media_type,size,link,catagory_id,language_id,status,layout_type,is_approved FROM tbl_advertisement WHERE status='0' AND catagory_id LIKE'%$catagory_id%'");
        } elseif ($language_id != "") {

            $sql1 = $this->db->query("SELECT id,cover_image,media_type,size,link,catagory_id,language_id,status,layout_type,is_approved FROM tbl_advertisement WHERE language_id = '$language_id'
                    AND status='0'");
        } else {

            $sql1 = $this->db->query("SELECT id,cover_image,media_type,size,link,catagory_id,language_id,status,layout_type,is_approved FROM tbl_advertisement
                WHERE status='0'");
        }
        //echo $this->db->last_query();

        /* PAGINATION */
        $config['base_url'] = base_url() . 'index.php/' . 'newadvertiselisting?catagory=' . $catagory_id . '&lang=' . $language_id;
        $config['total_rows'] = $sql1->num_rows();
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

        if ($status != "") {
            $sql = $this->db->query("SELECT id,cover_image,media_type,size,link,catagory_id,language_id,status,layout_type,is_approved FROM tbl_advertisement WHERE status='0' AND catagory_id LIKE'%$catagory_id%'
                    ORDER BY created_date DESC  LIMIT $limitPage,10");
        } elseif ($language_id != "") {

            $sql = $this->db->query("SELECT id,cover_image,media_type,size,link,catagory_id,language_id,status,layout_type,is_approved FROM tbl_advertisement WHERE language_id = '$language_id'
                    AND status='0' ORDER BY created_date DESC  LIMIT $limitPage,10");
        } else {

            $sql = $this->db->query("SELECT id,cover_image,media_type,size,link,catagory_id,language_id,status,layout_type,is_approved FROM tbl_advertisement
                WHERE status='0' ORDER BY created_date DESC  LIMIT $limitPage,10");
        }


        if ($sql1->num_rows() < 1) {
            return FALSE;
        } else {
            //return $data = $sql->result_array();
            $data = $sql->result_array();
            foreach ($data as $key => $value) {

                $category_id = $value['catagory_id'];
                $implode = explode(',', $category_id);
                $category_list = implode(",", $implode);
                $sql1 = $this->db->query("SELECT category_name FROM tbl_category WHERE id IN ($category_list)");
                $nameArray = $sql1->result_array();
                $data[$key]['categoy_name'] = $nameArray;
            }
            return $data;
        }
    }

    /* ALL REVIEW ADVERTISE */

    /** all ads which are for review
     * These ads will come from customer
     * ads which are in review state
     * @author Techahead
     * @access Public
     * @param
     * @return
     * count
     * */
    public function allReviewAdvertise()
    {

        $sql = $this->db->query("SELECT id FROM tbl_magazine_advertisement WHERE status='1' AND is_approved='0' ");
        //echo $this->db->last_query();die;
        return $sql->num_rows();
    }

    /* GET ALL MAGAZINE LIST */
    /* Public function getMagazineList */

    public function getMagazineList()
    {
        $customer_id = $this->get_customer_id();
        $sql = $this->db->query("SELECT id,title FROM tbl_magazine WHERE status = '1' AND publish_date_to >= CURDATE()");

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->result_array();
        }
    }

    /**
     * @author Techahead
     * @access Public
     * @param
     * all fields data
     * @return
     * all new ads list
     * */
    public function getReviewAdvertiseList()
    {

        $catagory_id = $this->get_catagory_id();
        $language_id = $this->get_language_id();

        $sql1 = "SELECT id,cover_image,media_type,size,link,magazine_id,language_id,status,layout_type,is_approved FROM tbl_magazine_advertisement WHERE status='1' 
                AND is_approved = '0' ";
        if ($catagory_id != '') {
            $sql1 .= "  AND catagory_id LIKE '%$catagory_id%'";
        }
        if ($language_id != '') {
            $sql1 .= " AND language_id = '$language_id'
                    ";
        }

        $sql = $this->db->query($sql1);

        //echo $this->db->last_query();

        /* PAGINATION */
        $config['base_url'] = base_url() . 'index.php/' . 'reviewadvertiselisting?catagory=' . $catagory_id . '&lang=' . $language_id;
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

        $sql_data1 = "SELECT embed_video,id,cover_image,media_type,size,link,magazine_id,language_id,status,layout_type,is_approved FROM tbl_magazine_advertisement WHERE status='1' 
                AND is_approved = '0'";
        if ($catagory_id != '') {
            $sql_data1 .= "  AND catagory_id LIKE '%$catagory_id%'";
        }
        if ($language_id != '') {
            $sql_data1 .= " AND language_id = '$language_id'
";
        }
        $sql_data1 .= " ORDER BY created_date DESC LIMIT $limitPage,10";


        $sql_data = $this->db->query($sql_data1);

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            //return $data = $sql->result_array();
            $data = $sql_data->result_array();
            foreach ($data as $key => $value) {

                $category_id = $value['magazine_id'];
                $implode = explode(',', $category_id);
                $category_list = implode(",", $implode);
                $sql1 = $this->db->query("SELECT title FROM tbl_magazine WHERE id IN ($category_list)");
                $nameArray = $sql1->result_array();
                $data[$key]['categoy_name'] = $nameArray;
            }
            return $data;
        }
    }

    /* Publish A Advertise */

    /** update a ads for publish
     * @author Techahead
     * @access Public
     * @param
     * @return
     * count
     * */
    public function publishAAdvertise()
    {

        $advertise_id = $this->get_id();
        $publish_dat_frm = $this->get_publish_date_from();
        $publish_dat_to = $this->get_publish_date_to();
        $disply_time = $this->get_display_time();

        $sql = $this->db->query("UPDATE tbl_advertisement SET publish_date_to='$publish_dat_to',publish_date_from='$publish_dat_frm',
                display_time=$disply_time,status='1',created_date = now() WHERE id = $advertise_id");
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

    public function deleteAdvertise()
    {
        $advertise_id = $this->get_id();
        $path = $this->getPath();
        if ($path == 'admin') {
            $sql = $this->db->query("UPDATE tbl_advertisement SET status='3' ,created_date = now() WHERE id = $advertise_id");
        } else {
            $sql = $this->db->query("UPDATE tbl_magazine_advertisement SET status='3' ,created_date = now() WHERE id = $advertise_id");
        }

        //echo $this->db->last_query();
        if ($sql) {
            return TRUE;
        }
        return FALSE;
    }

    /** DETAILS OF A AD
     * @author Techahead
     * @access Public
     * @param
     * @return
     * array
     * */
    public function getAdvertiseDetails()
    {

        $advertise_id = $this->get_id();
        $sourcePath = $this->getPath();
        if ($sourcePath == 'admin') {
            $sql = $this->db->query("SELECT * FROM tbl_advertisement WHERE id = $advertise_id");
        } else {
            $sql = $this->db->query("SELECT * FROM tbl_magazine_advertisement WHERE id = $advertise_id");
        }

        if ($sql) {
            $data = $sql->row_array();
            $category_id = $data['catagory_id'];
            $implode = explode(',', $category_id);
            $category_list = implode(",", $implode);
            if (!empty($category_list)) {
                $sql1 = $this->db->query("SELECT category_name FROM tbl_category WHERE id IN ($category_list)");
            } else {
                $sql1 = $this->db->query("SELECT category_name FROM tbl_category");
            }
            $nameArray = $sql1->result_array();
            $data['categoy_name'] = $nameArray;

            return $data;
        }
        //print_r($data);die;
        return FALSE;
    }

    public function UpdateAdvertise()
    {
        $advertise_id = $this->get_id();

        $media_type = $this->get_media_type();
        $link = $this->get_link();
        $catagory_list = $this->get_catagory_id();
        $language_id = $this->get_language_id();
        $layout = $this->get_layout_type();
        $status = $this->get_status();
        $publish_date_from = $this->get_publish_date_from();
        $publish_date_to = $this->get_publish_date_to();
        $dis_time = $this->get_display_time();
        $sourcePath = $this->getPath();
        if ($this->get_cover_image() == 'embed') {
            $data1 = array('embed_thumb' => $this->getEmbed_thumb(), 'cover_image' => 'embed', 'embed_video' => $this->get_embed_video(), 'magazine_id' => $this->get_magazine_id(), 'media_type' => $media_type, 'link' => $link, 'catagory_id' => $catagory_list, 'language_id' => $language_id,
                'layout_type' => $layout, 'publish_date_from' => $publish_date_from, 'publish_date_to' => $publish_date_to, 'display_time' => $dis_time);
        } else {
            $data1 = array('embed_thumb' => $this->getEmbed_thumb(), 'embed_video' => '', 'magazine_id' => $this->get_magazine_id(), 'media_type' => $media_type, 'link' => $link, 'catagory_id' => $catagory_list, 'language_id' => $language_id,
                'layout_type' => $layout, 'publish_date_from' => $publish_date_from, 'publish_date_to' => $publish_date_to, 'display_time' => $dis_time);
        }
        //$size = $this->get_size();
        //$size1 = round($size/1024,3)."".'kb';
        $data = array_filter($data1);
        if ($sourcePath == 'admin') {
            $sql = $this->db->set('created_date', 'now()', FALSE)
                ->where('id', $advertise_id)
                ->update('tbl_advertisement', $data);
        } else {
            $sql = $this->db->set('created_date', 'now()', FALSE)
                ->where('id', $advertise_id)
                ->update('tbl_magazine_advertisement', $data);
        }
        //echo $this->db->last_query();die;
        if ($sql) {
            return TRUE;
        }
        return FALSE;
    }

    /* Update Cover IMAGE 1 */

    public function UpdateCoverImage()
    {
        $advertise_id = $this->get_id();
        $cover_img = $this->get_cover_image();
        $size = $this->get_size();

        $size1 = round($size / 1024, 3) . "" . 'kb';
        $data = array('cover_image' => $cover_img, 'size' => $size1);

        $sql = $this->db->set('created_date', 'now()', FALSE)
            ->where('id', $advertise_id);
        if ($this->getPath() == 'admin') {
            $sql = $this->db->update('tbl_advertisement', $data);
        } elseif ($this->getPath() == 'customer') {
            $sql = $this->db->update('tbl_magazine_advertisement', $data);
        }

        //echo $this->db->last_query(); 

        if ($sql) {
            return TRUE;
        }
        return FALSE;
    }

    /* Update Cover IMAGE 1 */

    public function UpdateLandscapeImage()
    {
        $advertise_id = $this->get_id();
        $landscape_imag = $this->get_landscape_image();
        $data = array('landscape_image' => $landscape_imag);
        $sql = $this->db->set('created_date', 'now()', FALSE)
            ->where('id', $advertise_id);
        if ($this->getPath() == 'admin') {
            $sql = $this->db->update('tbl_advertisement', $data);
        } elseif ($this->getPath() == 'customer') {
            $sql = $this->db->update('tbl_magazine_advertisement', $data);
        }
//echo $this->db->last_query(); die;
        if ($sql) {
            return TRUE;
        }
        return FALSE;
    }

    /* Update Cover IMAGE 1 */

    public function UpdatePortableImage()
    {
        $advertise_id = $this->get_id();
        $portrait_image = $this->get_portrait_image();
        $data = array('portrait_image' => $portrait_image);
        $sql = $this->db->set('created_date', 'now()', FALSE)
            ->where('id', $advertise_id);
        if ($this->getPath() == 'admin') {
            $sql = $this->db->update('tbl_advertisement', $data);
        } elseif ($this->getPath() == 'customer') {
            $sql = $this->db->update('tbl_magazine_advertisement', $data);
        }


        if ($sql) {
            return TRUE;
        }
        return FALSE;
    }

    /* ADS RESULT FROM ADS_REPORT */

    public function adsReport()
    {
        $advertise_id = $this->get_id();
        $sql = $this->db->query("SELECT id,latitude,longitude,device_type FROM tbl_ads_report WHERE article_id = $advertise_id");

        if ($sql) {
            $data = $sql->result_array();
            foreach ($data as $value) {
                $data['total_count'] = $sql->num_rows();
            }

            return $data;
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

    public function restoreAdvertise()
    {
        $advertise_id = $this->get_id();
        $sourcePath = $this->getPath();
        if ($sourcePath == 'admin') {
            $sql = $this->db->query("UPDATE tbl_advertisement SET status='0',created_date = now() WHERE id = $advertise_id");
        } else {
            $sql = $this->db->query("UPDATE tbl_magazine_advertisement SET status='0',created_date = now() WHERE id = $advertise_id");
        }

        if ($sql) {
            return TRUE;
        }
        return FALSE;
    }

    /* Permanent DELETE */

    public function shiftDeleteAdvertise()
    {
        $advertise_id = $this->get_id();
        $sourcePath = $this->getPath();
        if ($sourcePath == 'admin') {
            $sql = $this->db->query("DELETE FROM tbl_advertisement WHERE id = $advertise_id");
        } else {
            $sql = $this->db->query("DELETE FROM tbl_magazine_advertisement WHERE id = $advertise_id");
        }

        if ($sql) {
            return TRUE;
        }
        return FALSE;
    }

    /* GET CUSTOMER ADVERTISE DETAILS */

    public function getCustomerAdvertiseDetails()
    {

        $advertise_id = $this->get_id();

        $sql = $this->db->query("SELECT embed_video,id,cover_image,media_type,size,language_id,link,publish_date_from,publish_date_to,magazine_id,status,
            layout_type,display_time FROM tbl_magazine_advertisement WHERE id = $advertise_id");

        if ($sql) {
            $data = $sql->row_array();
            $category_id = $data['magazine_id'];
            $implode = explode(',', $category_id);
            $category_list = implode(",", $implode);

            $sql1 = $this->db->query("SELECT title FROM tbl_magazine WHERE id IN ($category_list)");
            $nameArray = $sql1->result_array();
            $data['categoy_name'] = $nameArray;

            return $data;
        }
        return FALSE;
    }

    /* Approve ads */

    public function ApproveCustomerMagazineAds()
    {
        $id = $this->get_id();
        $sql = $this->db->query("UPDATE tbl_magazine_advertisement SET is_approved='1' WHERE id='$id'");
        //echo $this->db->last_query(); die;
        if ($sql) {
            return True;
        }
        return false;
    }

    /* Approve ads */

    public function DeclineCustomerMagazineAds()
    {
        $id = $this->get_id();

        $sql = $this->db->query("UPDATE tbl_magazine_advertisement SET is_approved='2' WHERE id='$id'");
        if ($sql) {
            return True;
        }
        return false;
    }

    public function getAdvDetail()
    {

        $adId = $this->get_id();

        $query = "SELECT id, magazine_id, media_type, embed_video, link FROM tbl_advertisement WHERE id = {$adId}";

        $sql = $this->db->query($query);

        return $sql->row();

    }

}

?>
