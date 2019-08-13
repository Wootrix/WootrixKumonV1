<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Magazine_advertise_model extends CI_Model
{


    private $_tableName = 'tbl_magazine_advertisement';
    private $_id = "";
    private $_title = "";
    private $_cover_image = "";
    private $_media_type = "";
    private $_magazine_id = "";
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
    private $_limit = "";
    private $_page = "";
    private $_web_category = "";
    private $_article_id = "";
    private $_search_key = "";
    private $_langMag = "";

    public function get_langMag()
    {
        return $this->_langMag;
    }

    public function set_langMag($_langMag)
    {
        $this->_langMag = $_langMag;
    }

    public function get_search_key()
    {
        return $this->_search_key;
    }

    public function set_search_key($_search_key)
    {
        $this->_search_key = $_search_key;
    }

    public function get_article_id()
    {
        return $this->_article_id;
    }

    public function set_article_id($_article_id)
    {
        $this->_article_id = $_article_id;
    }

    function getWeb_category()
    {
        return $this->_web_category;
    }

    function setWeb_category($web_category)
    {
        $this->_web_category = $web_category;
    }

    function get_page()
    {
        return $this->_page;
    }

    function set_page($_page)
    {
        $this->_page = $_page;
    }

    function get_limit()
    {
        return $this->_limit;
    }

    function set_limit($_limit)
    {
        $this->_limit = $_limit;
    }

    public function get_tableName()
    {
        return $this->_tableName;
    }

    public function get_id()
    {
        return $this->_id;
    }

    public function get_title()
    {
        return $this->_title;
    }

    public function get_cover_image()
    {
        return $this->_cover_image;
    }

    public function get_media_type()
    {
        return $this->_media_type;
    }

    public function get_magazine_id()
    {
        return $this->_magazine_id;
    }

    public function get_publish_date_from()
    {
        return $this->_publish_date_from;
    }

    public function get_publish_date_to()
    {
        return $this->_publish_date_to;
    }

    public function get_size()
    {
        return $this->_size;
    }

    public function get_link()
    {
        return $this->_link;
    }

    public function get_catagory_id()
    {
        return $this->_catagory_id;
    }

    public function get_language_id()
    {
        return $this->_language_id;
    }

    public function get_display_time()
    {
        return $this->_display_time;
    }

    public function get_layout_type()
    {
        return $this->_layout_type;
    }

    public function get_status()
    {
        return $this->_status;
    }

    public function get_created_date()
    {
        return $this->_created_date;
    }

    public function get_is_approved()
    {
        return $this->_is_approved;
    }

    public function get_customer_id()
    {
        return $this->_customer_id;
    }

    public function set_tableName($_tableName)
    {
        $this->_tableName = $_tableName;
    }

    public function set_id($_id)
    {
        $this->_id = $_id;
    }

    public function set_title($_title)
    {
        $this->_title = $_title;
    }

    public function set_cover_image($_cover_image)
    {
        $this->_cover_image = $_cover_image;
    }

    public function set_media_type($_media_type)
    {
        $this->_media_type = $_media_type;
    }

    public function set_magazine_id($_magazine_id)
    {
        $this->_magazine_id = $_magazine_id;
    }

    public function set_publish_date_from($_publish_date_from)
    {
        $this->_publish_date_from = $_publish_date_from;
    }

    public function set_publish_date_to($_publish_date_to)
    {
        $this->_publish_date_to = $_publish_date_to;
    }

    public function set_size($_size)
    {
        $this->_size = $_size;
    }

    public function set_link($_link)
    {
        $this->_link = $_link;
    }

    public function set_catagory_id($_catagory_id)
    {
        $this->_catagory_id = $_catagory_id;
    }

    public function set_language_id($_language_id)
    {
        $this->_language_id = $_language_id;
    }

    public function set_display_time($_display_time)
    {
        $this->_display_time = $_display_time;
    }

    public function set_layout_type($_layout_type)
    {
        $this->_layout_type = $_layout_type;
    }

    public function set_status($_status)
    {
        $this->_status = $_status;
    }

    public function set_created_date($_created_date)
    {
        $this->_created_date = $_created_date;
    }

    public function set_is_approved($_is_approved)
    {
        $this->_is_approved = $_is_approved;
    }

    public function set_customer_id($_customer_id)
    {
        $this->_customer_id = $_customer_id;
    }

    public function getAdvertiseDetail($groups, $locations, $disciplines, $branches)
    {

        $websiteLanguage = $this->session->userdata('languagesWeb');

        $row = array();
        $date = date("Y-m-d");
        $limit = $this->get_limit();
        $page = $this->get_page();
        $category = ltrim($this->getWeb_category(), '|');
        $languageId = ltrim($this->get_language_id(), ',');
        $finalArtLang = explode(",", $languageId);
        $artLangId = implode("|", $finalArtLang);
        if ($artLangId == "") {
            $artLangId = $languageId;
        }
        $langId = $this->get_langMag();
        $articleID = $this->get_article_id();
        $searchKey = $this->get_search_key();
        if (isset($_GET) && $_GET['page'] == '') {
            $layoutType = '1';
        } else {
            $layoutType = ($_GET['page'] % 4);
            if (trim($layoutType) == '' && $layoutType != '0') {
                $layoutType = '1';
            } elseif ($layoutType == '0') {
                $layoutType = '4';
            }
        }
        //echo "===============".$category;
        $magazine = $this->get_magazine_id();
        if ($this->session->userdata("langSelect") == 'english') {
            $languageIdWeb = '1';
        } elseif ($this->session->userdata("langSelect") == 'portuguese') {
            $languageIdWeb = '2';
        } elseif ($this->session->userdata("langSelect") == 'spanish') {
            $languageIdWeb = '3';
        }
        $query = $this->db->query("SELECT * FROM (SELECT id as adsid,title,cover_image,media_type,display_time,link,embed_video,embed_thumb FROM (`tbl_magazine_advertisement`) WHERE FIND_IN_SET('$magazine', magazine_id) > 0 AND `status` = '1' AND `is_approved` = '1' AND `layout_type` = '$layoutType' AND publish_date_from <= '$date' AND publish_date_to >= '$date' UNION SELECT id as adsid,title,cover_image,media_type,display_time,link,embed_video,embed_thumb FROM (`tbl_advertisement`) WHERE FIND_IN_SET('$magazine', magazine_id) > 0 AND `status` = '1' AND `is_approved` = '1' AND `layout_type` = '$layoutType' AND publish_date_from <= '$date' AND publish_date_to >= '$date') s ORDER BY RAND() LIMIT 1");
        //echo $this->db->last_query();die;
        $row['advertisement'] = $query->row_array();
        $articleSql = "";
        $articleSql .= " SELECT s.* FROM (SELECT 
        id,
    title,
    description,
    description_without_html,
    if(article_link='',if(website_url='',link_url,website_url),if(article_link='0',if(website_url='',link_url,website_url),article_link)) as article_link,
    publish_date,
    category_id,
    tags,
    crawling_date,
    media_id,
    website_url,
    status,
    image,
    language_id,
    article_language,
    video_path,
    customer_id,
    magazine_id,
    created_by,
    media_type,
    allow_comment,
    allow_share,
    publish_from,
    publish_to,embed_video,embed_video_thumb,source, 0 as nr_order
        FROM
        (`tbl_magazine_articles`)
        WHERE
        FIND_IN_SET('$magazine', magazine_id) > 0 AND status = '2' AND publish_from <= '$date' AND publish_to >= '$date'";

        if ($searchKey != '') {
            $articleSql .= "AND title like '%$searchKey%'";
        }

        $articleSql .= " UNION SELECT 
        na.id,
    title,
    description,
    description_without_html,
    if(article_link='',website_url,if(article_link='0',website_url,article_link)) as article_link,
    publish_date,
    category_id,
    tags,
    crawling_date,
    media_id,
    website_url,
    status,
    image,
    language_id,
    article_language,
    video_path,
    customer_id,
    na.magazine_id,
    created_by,
    media_type,
    allow_comment,
    allow_share,
    publish_from,
    publish_to,embed_video,embed_video_thumb,source, ao.nr_order
        FROM
        tbl_new_articles na
        LEFT JOIN tbl_article_order ao ON( na.id = ao.article_id AND ao.magazine_id = $magazine )";

        if( !empty($groups) ) {
            $articleSql .= " JOIN tbl_article_group ag ON na.id = ag.article_id ";
        }

        if( !empty($locations) ){
            $articleSql .= " JOIN tbl_article_location al ON na.id = al.article_id ";
        }

        if( !empty($disciplines) ){
            $articleSql .= " JOIN tbl_article_discipline ad ON na.id = ad.article_id ";
        }

        if( !empty($branches) ){
            $articleSql .= " JOIN tbl_article_branch ab ON na.id = ab.article_id ";
        }

        $articleSql .= " WHERE
        FIND_IN_SET('$magazine', na.magazine_id) > 0 AND status = '2' AND publish_from <= '$date' AND publish_to >= '$date'";

        if( !empty($groups) ){
            $articleSql .= " AND ag.group_id IN ($groups) ";
        }

        if( !empty($locations) ){
            $articleSql .= " AND al.location_id IN ($locations) ";
        }

        if( !empty($disciplines) ){
            $articleSql .= " AND ad.discipline_id IN ($disciplines) ";
        }

        if( !empty($branches) ){
            $articleSql .= " AND ab.branch = '$branches' ";
        }

        if ($searchKey != '') {
            $articleSql .= "AND title like '%$searchKey%'";
        }
        $articleSql .= ") s";

        $articleSql .= " ORDER BY s.nr_order asc ";
        if ($limit != '') {
            $articleSql .= " LIMIT $limit,$page";
        } else {
            $articleSql .= " LIMIT 3";
        }
        $articleQuery = $this->db->query($articleSql);

//        echo "<pre>"; echo $this->db->last_query();die;

        if ($articleQuery->num_rows() > 0) {
            $row['articles'] = $articleQuery->result_array();
        } else {
            return FALSE;
        }

        return $row;
    }

    public function getAdvertiseOnly()
    {
        $date = date('Y-m-d');
        $layoutType = $this->get_layout_type();
        $magazine = $this->get_magazine_id();

        if ($this->session->userdata("langSelect") == 'english') {
            $languageIdWeb = '1';
        } elseif ($this->session->userdata("langSelect") == 'portuguese') {
            $languageIdWeb = '2';
        } elseif ($this->session->userdata("langSelect") == 'spanish') {
            $languageIdWeb = '3';
        }
        $query = $this->db->query("SELECT * FROM (SELECT id as adsid,title,cover_image,media_type,display_time,link,embed_video,embed_thumb FROM (`tbl_magazine_advertisement`) WHERE FIND_IN_SET('$magazine', magazine_id) > 0 AND `status` = '1' AND `is_approved` = '1' AND `layout_type` = '$layoutType' AND publish_date_from <= '$date' AND publish_date_to >= '$date' UNION SELECT id as adsid,title,cover_image,media_type,display_time,link,embed_video,embed_thumb FROM (`tbl_advertisement`) WHERE FIND_IN_SET('$magazine', magazine_id) > 0 AND `status` = '1' AND `is_approved` = '1' AND `layout_type` = '$layoutType' AND publish_date_from <= '$date' AND publish_date_to >= '$date') s ORDER BY RAND() LIMIT 1");

        //echo $this->db->last_query();
        return $query->row_array();

    }

    public function getArticlesAdvertiseOnly()
    {
        $date = date('Y-m-d');
        $layoutType = $this->get_layout_type();
        $languageIdWeb1 = $this->session->userdata('languages');
        $languageIdWeb = implode(",", array_filter(explode(",", $languageIdWeb1)));
        $topics1 = $this->session->userdata('topics');
        $topics = implode("|", array_filter(explode("|", $topics1)));
        if ($topics != '') {
            $query = $this->db->query("SELECT id as adsid,title,cover_image,media_type,display_time,link,embed_video,embed_thumb FROM (`tbl_advertisement`) WHERE  `status` = '1' AND `is_approved` = '1' AND `layout_type` = '$layoutType' AND language_id IN($languageIdWeb) AND catagory_id REGEXP '(^|,)($topics)(,|$)' AND publish_date_from <= '$date' AND publish_date_to >= '$date' ORDER BY RAND() LIMIT 1");
        } else {
            $query = $this->db->query("SELECT id as adsid,title,cover_image,media_type,display_time,link,embed_video,embed_thumb FROM (`tbl_advertisement`) WHERE  `status` = '1' AND `is_approved` = '1' AND `layout_type` = '$layoutType' AND language_id IN($languageIdWeb) AND publish_date_from <= '$date' AND publish_date_to >= '$date' ORDER BY RAND() LIMIT 1");
        }

        //echo $this->db->last_query();die;
        return $query->row_array();

    }

    public function getAdvertiseDetailPaging()
    {
        $row = array();
        $date = date("Y-m-d");
        $category = ltrim($this->getWeb_category(), '|');
        $languageId = ltrim($this->get_language_id(), ',');
        $finalArtLang = explode(",", $languageId);
        $artLangId = implode("|", $finalArtLang);
        if ($artLangId == "") {
            $artLangId = $languageId;
        }
        $langId = $this->get_langMag();

        $articleID = $this->get_article_id();
        $searchKey = $this->get_search_key();
        $layoutType = $_GET['page'];
        if ($layoutType == '') {
            $layoutType = '1';
        }
        //echo "===============".$category;
        $magazine = $this->get_magazine_id();

        $articleSql = "";
        $articleSql .= "SELECT 
        count(*) pageCount
        FROM
        (`tbl_magazine_articles`)
        WHERE
        FIND_IN_SET('$magazine', magazine_id) > 0 AND status = '2' AND publish_from <= '$date' AND publish_to >= '$date'";
//        if($articleID != ''){
//          $articleSql .= " AND `id` = '$articleID'";
//        }
//        if($category!=''){
//        $articleSql .="AND `category_id` REGEXP '(^|,)($category)(,|$)'";
//        }
//        if($artLangId!=''){
//        $articleSql .="AND `article_language` REGEXP '(^|,)($artLangId)(,|$)' ";
//        }

        if ($searchKey != '') {
            $articleSql .= "AND title like '%$searchKey%'";
        }

        $articleSql .= " UNION SELECT 
        count(*) pageCount
        FROM
        (`tbl_new_articles`)
        WHERE
        FIND_IN_SET('$magazine', magazine_id) > 0 AND status = '2' AND publish_from <= '$date' AND publish_to >= '$date'";
//        if($articleID != ''){
//          $articleSql .= " AND `id` = '$articleID'";
//        }
//        if($category!=''){
//        $articleSql .="AND `category_id` REGEXP '(^|,)($category)(,|$)'";
//        }
//        if($artLangId!=''){
//        $articleSql .="AND `article_language` REGEXP '(^|,)($artLangId)(,|$)' ";
//        }

        if ($searchKey != '') {
            $articleSql .= "AND title like '%$searchKey%'";
        }

        $articleQuery = $this->db->query($articleSql);
        //echo $this->db->last_query()."<br>";die;

        $row = $articleQuery->result_array();
        $totalRows = "";
        foreach ($row as $r) {
            $totalRows = $totalRows + $r['pageCount'];
        }
        $row1['pageCount'] = $totalRows;
        return $row1;
    }


    public function getArticleLayoutCount()
    {
        $row = array();
        $date = date("Y-m-d");
        $category = ltrim($this->getWeb_category(), '|');
        $languageId = ltrim($this->get_language_id(), ',');
        //echo $languageId;die;
        if ($languageId == '') {
            $languageId = '1';
        }
        $articleID = $this->get_article_id();
        $searchKey = $this->get_search_key();
        $layoutType = $_GET['page'];
        if ($layoutType == '') {
            $layoutType = '1';
        }

        if ($this->session->userdata("langSelect") == 'english') {
            $languageIdWeb = '1';
        } elseif ($this->session->userdata("langSelect") == 'portuguese') {
            $languageIdWeb = '2';
        } elseif ($this->session->userdata("langSelect") == 'spanish') {
            $languageIdWeb = '3';
        }
        //echo "===============".$category;
        $magazine = $this->get_magazine_id();

        $articleSql = "";
        $articleSql .= "SELECT 
        count(*) pageCount
        FROM
        (`tbl_new_articles`)
        WHERE
        status = '2' AND publish_from <= '$date' AND publish_to >= '$date'";
        if ($articleID != '') {
            $articleSql .= " AND `id` = '$articleID'";
        }
        if ($category != '') {
            $articleSql .= "AND `category_id` REGEXP '(^|,)($category)(,|$)'";
        }
        if ($languageId != '') {
            $articleSql .= "AND `article_language` IN($languageId) ";
        }

        if ($searchKey != '') {
            $articleSql .= "AND title like '%$searchKey%'";
        }
        $articleQuery = $this->db->query($articleSql);
        //echo $this->db->last_query()."<br>";die;
        $row = $articleQuery->row_array();
        return $row;
    }

    public function getUserMagazinesRedirect()
    {
        $query = $this->db->select()
            ->from("tbl_user_magazines")
            ->where("user_id", $this->session->userdata("user_id"))
            ->where("magazine_id", $_GET['magazineId'])
            ->get();

        if ($query->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }

    }

    public function getUserMagazinesDetailsForHeader()
    {
        $query = $this->db->select()
            ->from("tbl_magazine")
            ->where("id", $_GET['magazineId'])
            ->get();

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return FALSE;
        }

    }


    public function getArticleLayoutDetails()
    {
        //echo $this->session->userdata('topics');die;
        //echo$this->get_language_id();die;
        $languageIdWeb = $this->session->userdata('languages');
        $row = array();
        $date = date("Y-m-d");
        $limit = $this->get_limit();
        $page = $this->get_page();
        $category = ltrim($this->getWeb_category(), '|');
        $languageId = ltrim($this->get_language_id(), ',');
        if ($languageId == '') {
            $languageId = '1';
        }
        $articleID = $this->get_article_id();
        $searchKey = $this->get_search_key();
        if (isset($_GET) && $_GET['page'] == '') {
            $layoutType = '1';
        } else {
            $layoutType = ($_GET['page']) % 4;

            if (trim($layoutType) == '' && $layoutType != '0') {
                $layoutType = '1';
            } else if (trim($layoutType) == '0') {
                $layoutType = '4';
            }
        }
        //echo "===============".$category;
        $languageIdWeb1 = $this->session->userdata('languages');
        $languageIdWeb = implode(",", array_filter(explode(",", $languageIdWeb1))) ? implode(",", array_filter(explode(",", $languageIdWeb1))) : 0;
        $magazine = $this->get_magazine_id();
        $topics1 = $this->session->userdata('topics');
        $topics = implode("|", array_filter(explode("|", $topics1)));
        $websiteLanguage = $this->session->userdata('languagesWeb');

        if (empty($websiteLanguage)) {
            $websiteLanguage = 1;
        }

        if ($topics != '') {
            $query = $this->db->query("SELECT id as adsid, title,cover_image,media_type,display_time,link,embed_video,embed_thumb FROM (`tbl_advertisement`) WHERE `status` = '1' AND `is_approved` = '1' AND `layout_type` = '$layoutType' AND publish_date_from <= '$date' AND publish_date_to >= '$date' AND language_id IN($websiteLanguage) AND catagory_id REGEXP '(^|,)($topics)(,|$)' AND publish_date_to >= '$date' ORDER BY RAND() LIMIT 1");
        } else {
            $query = $this->db->query("SELECT id as adsid, title,cover_image,media_type,display_time,link,embed_video,embed_thumb FROM (`tbl_advertisement`) WHERE `status` = '1' AND `is_approved` = '1' AND `layout_type` = '$layoutType' AND publish_date_from <= '$date' AND publish_date_to >= '$date' AND language_id IN($websiteLanguage) AND publish_date_to >= '$date' ORDER BY RAND() LIMIT 1");
        }

        //echo $this->db->last_query();die;
        $row['advertisement'] = $query->row_array();
        $articleSql = "";
        $articleSql .= "SELECT 
        id,
title,
author,
image,
media_type,
language_id,
video_path,
embed_video,
customer_id,
description,
description_without_html,
if(article_link='',website_url,if(article_link='0',website_url,article_link)) as article_link,
publish_date,
category_id,
tags,
crawling_date,
media_id,
status,
magazine_id,
created_by,
allow_comment,
allow_share,
publish_from,
publish_to,
article_language,
via_url,
source,
embed_video_thumb  FROM
        (`tbl_new_articles`)
        WHERE
        status = '2' AND publish_from <= '$date' AND publish_to >= '$date'";
        if ($articleID != '') {
            $articleSql .= " AND `id` = '$articleID'";
        }
        if ($category != '') {
            $articleSql .= "AND `category_id` REGEXP '(^|,)($category)(,|$)'";
        }

        if ($languageId != '') {
            $articleSql .= "AND `article_language` IN($languageId) ";
        }
        if ($searchKey != '') {
            $articleSql .= "AND title like '%$searchKey%'";
        }
        $articleSql .= " ORDER BY `publish_date` desc";
        if ($limit != '') {
            $articleSql .= " LIMIT $limit,$page";
        } else {
            $articleSql .= " LIMIT 3";
        }
        $articleQuery = $this->db->query($articleSql);

        //echo $this->db->last_query()."<br>";die;
        if ($articleQuery->num_rows() > 0) {
            $row['articles'] = $articleQuery->result_array();
        } else {
            return FALSE;
        }
        //echo "<pre>";print_r($row);die;
        return $row;
    }


}

