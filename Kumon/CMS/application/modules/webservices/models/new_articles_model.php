<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class New_articles_model extends CI_Model {
    
    
    private $_tableName = 'tbl_new_articles';
    private $_id = "";
    private $_title = "";
    private $_author = "";
    private $_description = "";
    private $_description_without_html = "";
    private $_article_link = "";
    private $_publish_date = "";
    private $_category_id = "";
    private $_tags = "";
    private $_crawling_date = "";
    private $_media_id = "";
    private $_website_url = "";
    private $_status = "";
    private $_image = "";
    private $_language_id = "";
    private $_video_path = "";
    private $_customer_id = "";
    private $_magazine_id = "";
    private $_created_by = "";
    private $_media_type = "";
    private $_allow_comment = "";
    private $_allow_share = "";
    private $_token = "";
    private $_language_name = "";
    private $_device_type = "";
    private $_page_number = "";
    private $_comment = "";
    private $_user_id = "";
    private $_we_category = "";
    private $_server = "";
    private $_lang_param = "";
    private $_app_lang_name = "";
    private $_source="";

    function getSource() {
        return $this->_source;
    }

    function setSource($source) {
        $this->_source = $source;
    }

    public function get_app_lang_name() {
        return $this->_app_lang_name;
    }

    public function set_app_lang_name($_app_lang_name) {
        $this->_app_lang_name = $_app_lang_name;
    }

    public function get_lang_param() {
        return $this->_lang_param;
    }

    public function set_lang_param($_lang_param) {
        $this->_lang_param = $_lang_param;
    }

    function getServer() {
        return $this->_server;
    }

    function setServer($server) {
        $this->_server = $server;
    }

    function getWe_category() {
        return $this->_we_category;
    }

    function setWe_category($we_category) {
        $this->_we_category = $we_category;
    }

    public function get_comment() {
        return $this->_comment;
    }

    public function get_user_id() {
        return $this->_user_id;
    }

    public function set_comment($_comment) {
        $this->_comment = $_comment;
    }

    public function set_user_id($_user_id) {
        $this->_user_id = $_user_id;
    }

    public function get_page_number() {
        return $this->_page_number;
    }

    public function set_page_number($_page_number) {
        $this->_page_number = $_page_number;
    }

    public function get_device_type() {
        return $this->_device_type;
    }

    public function set_device_type($_device_type) {
        $this->_device_type = $_device_type;
    }

    public function get_language_name() {
        return $this->_language_name;
    }

    public function set_language_name($_language_name) {
        $this->_language_name = $_language_name;
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

    public function get_author() {
        return $this->_author;
    }

    public function get_description() {
        return $this->_description;
    }

    public function get_description_without_html() {
        return $this->_description_without_html;
    }

    public function get_article_link() {
        return $this->_article_link;
    }

    public function get_publish_date() {
        return $this->_publish_date;
    }

    public function get_category_id() {
        return $this->_category_id;
    }

    public function get_tags() {
        return $this->_tags;
    }

    public function get_crawling_date() {
        return $this->_crawling_date;
    }

    public function get_media_id() {
        return $this->_media_id;
    }

    public function get_website_url() {
        return $this->_website_url;
    }

    public function get_status() {
        return $this->_status;
    }

    public function get_image() {
        return $this->_image;
    }

    public function get_language_id() {
        return $this->_language_id;
    }

    public function get_video_path() {
        return $this->_video_path;
    }

    public function get_customer_id() {
        return $this->_customer_id;
    }

    public function get_magazine_id() {
        return $this->_magazine_id;
    }

    public function get_created_by() {
        return $this->_created_by;
    }

    public function get_media_type() {
        return $this->_media_type;
    }

    public function get_allow_comment() {
        return $this->_allow_comment;
    }

    public function get_allow_share() {
        return $this->_allow_share;
    }

    public function get_token() {
        return $this->_token;
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

    public function set_author($_author) {
        $this->_author = $_author;
    }

    public function set_description($_description) {
        $this->_description = $_description;
    }

    public function set_description_without_html($_description_without_html) {
        $this->_description_without_html = $_description_without_html;
    }

    public function set_article_link($_article_link) {
        $this->_article_link = $_article_link;
    }

    public function set_publish_date($_publish_date) {
        $this->_publish_date = $_publish_date;
    }

    public function set_category_id($_category_id) {
        $this->_category_id = $_category_id;
    }

    public function set_tags($_tags) {
        $this->_tags = $_tags;
    }

    public function set_crawling_date($_crawling_date) {
        $this->_crawling_date = $_crawling_date;
    }

    public function set_media_id($_media_id) {
        $this->_media_id = $_media_id;
    }

    public function set_website_url($_website_url) {
        $this->_website_url = $_website_url;
    }

    public function set_status($_status) {
        $this->_status = $_status;
    }

    public function set_image($_image) {
        $this->_image = $_image;
    }

    public function set_language_id($_language_id) {
        $this->_language_id = $_language_id;
    }

    public function set_video_path($_video_path) {
        $this->_video_path = $_video_path;
    }

    public function set_customer_id($_customer_id) {
        $this->_customer_id = $_customer_id;
    }

    public function set_magazine_id($_magazine_id) {
        $this->_magazine_id = $_magazine_id;
    }

    public function set_created_by($_created_by) {
        $this->_created_by = $_created_by;
    }

    public function set_media_type($_media_type) {
        $this->_media_type = $_media_type;
    }

    public function set_allow_comment($_allow_comment) {
        $this->_allow_comment = $_allow_comment;
    }

    public function set_allow_share($_allow_share) {
        $this->_allow_share = $_allow_share;
    }

    public function set_token($_token) {
        $this->_token = $_token;
    }

    public function verifyTokenValue() {
        $query = $this->db->select("id")
                ->from("tbl_users")
                ->where("token", $this->get_token())
                ->where("token_expiry_date >=", "NOW()", false)
                ->get();
       
       
            return TRUE;
       
    }

    public function getNewArticle() {
        $date = date("Y-m-d");
        $appLangName = $this->get_app_lang_name();
        //echo $appLangName;die;
        if($appLangName == "en"){
            $appLangName = "1";
        }elseif($appLangName == "pt"){
            $appLangName = "2";
        }elseif($appLangName == "es"){
            $appLangName = "3";
        }
        if ($this->getServer() == 'web') {
            $webLang = $this->get_app_lang_name();
            if($webLang == ""){
                $webLang = "1";
            }
            $langId = ltrim($this->get_language_id(), ',');
            $webCat = ltrim($this->getWe_category(), '|');
            $sql = "SELECT id,
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
embed_video_thumb FROM
    (`tbl_new_articles`)
WHERE
    `status` = '2'
        AND `publish_from` <= '$date'
        AND `publish_to` >= '$date' ";
            $sql .="AND `article_language` IN($langId)";
            if ($webCat != '') {
                $sql .="AND `category_id` REGEXP '(^|,)($webCat)(,|$)'";
            }
            $sql .="ORDER BY `publish_date` desc
LIMIT 1";
            $query = $this->db->query($sql);
            //echo $this->db->last_query();
        } else {
            $queryGetCategory = $this->db->select("category")
                                         ->from("tbl_users")
                                         ->where("id",  $this->get_token())
                                         ->get();
            $rowCategory = $queryGetCategory->row_array();
            
            $artLangId = implode("|",$this->get_language_id());
            if($artLangId == ""){
                $artLangId = $this->get_language_id();
            }
            $artQuery = "";
            $artQuery .= "SELECT id,
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
embed_video_thumb
                        FROM (`tbl_new_articles`)
                        WHERE `status` =  '2'
                        AND `publish_from` <= '$date'
                        AND `publish_to` >= '$date' ";
            if($rowCategory['category'] != ''){
                $catId = $rowCategory['category'];
            $artQuery .= "AND `category_id` REGEXP '(^|,)($catId)(,|$)'";
            }
                        $artQuery .= "
                        AND `article_language` REGEXP '(^|,)($artLangId)(,|$)'  
                        ORDER BY `publish_date` desc
                        LIMIT 1";
                        $query = $this->db->query($artQuery);
            //echo $this->get_language_id();die;
//            $query = $this->db->select("*")
//                    ->from("tbl_new_articles")
//                    //->where("magazine_id","0")
//                    ->where("status", "2")
//                    ->where("publish_from <=", $date)
//                    ->where("publish_to >=", $date);
//            if($catId != ""){
//            $query = $this->db->where("category_id",$catId);
//            }
//                    $query = $this->db->where("language_id",$appLangName)
//                    ->where_in("article_language", $this->get_language_id())
//                    ->order_by("publish_date", "desc")
//                    ->limit("1")
//                    ->get();
            
        }
        //echo $this->db->last_query();die;
        if ($query->num_rows() > 0) {
            $row = $query->row_array();

            $queryCommentCount = $this->db->select("count(id) as commentCount")
                    ->from("tbl_article_comment")
                    ->where("article_id", $row['id'])
                    ->get();
            $rowCount = $queryCommentCount->row_array();
            $row['commentCount'] = $rowCount['commentCount'];
            //echo "<pre>";print_r($row);die;
            return $row;
        } else {
            return FALSE;
        }
    }

    public function getUserArticlesTest()
    {

        $date = date("Y-m-d");

        $langId = ltrim($this->get_language_id(), ",");

        $queryGetUserMagId = $this->db->select("magazine_id")
            ->from("tbl_user_magazines")
            ->join("tbl_users u", "user_id = u.id")
            ->join("tbl_magazine_location", "location = country AND id_magazine = magazine_id")
            ->where("user_id", $this->get_token())
            ->get();

        $rowMagId = $queryGetUserMagId->result_array();

        echo $this->db->last_query();die;

    }

    public function getUserArticles() {

        $date = date("Y-m-d");

        $langId = ltrim($this->get_language_id(), ",");

        $queryGetUserMagId = $this->db->select("magazine_id")
                ->from("tbl_user_magazines")
                ->join("tbl_users u", "user_id = u.id")
                ->join("tbl_magazine_location", "location = country AND id_magazine = magazine_id")
                ->where("user_id", $this->get_token())
                ->get();

        $rowMagId = $queryGetUserMagId->result_array();

//        echo $this->db->last_query();die;

        if ($queryGetUserMagId->num_rows() > 0) {
            foreach ($rowMagId as $r) {

                $magId[] = $r['magazine_id'];
            }
        } else {
            $magId = array(0);
        }
        //echo "<pre>";print_r($magId);die;
        if ($langId == '') {
            $langId = $this->get_language_id();
        }

        $query = $this->db->select("*")
                ->from("tbl_magazine")
                ->where("publish_date_from <=", $date)
                ->where("publish_date_to >=", $date)
                //->where_in("language_id",  $langId)
                ->where("status", "1")
                ->where_in("id", $magId)
                ->order_by("publish_date_from desc")
                ->get();

        if ($query->num_rows() > 0) {
            $row = $query->result_array();
            $i = 0;
            foreach ($row as $r) {
                $date = date("Y-m-d");
                $queryArt = $this->db->select("count(id) as totalArticle")
                        ->from("tbl_magazine_articles")
                        ->where("magazine_id", $r['id'])
                        ->where("status", "2")
                        ->where("publish_from <=", $date)
                        ->where("publish_to >=", $date)
                        ->get();
                $rowArt = $queryArt->row_array();
                $row[$i]['totalArticle'] = $rowArt['totalArticle'];
                $i++;
            }
            return $row;
        } else {
            return FALSE;
        }
    }

    public function getLanguageId() {
        if ($this->get_language_name() != '') {
            if ($this->get_language_name() == "en" || $this->get_language_name() == "es" || $this->get_language_name() == "pt") {
                
                $exp = $this->get_language_name();
                if ($exp == 'pt') {
                    $exp = 'po';
                }
            } else {
                $exp = explode(",", $this->get_language_name());
                $expCount = count($exp);

                if ($exp[0] == "pt") {
                    $exp[0] = "po";
                } elseif ($exp[1] == "pt") {
                    $exp[1] = "po";
                } elseif ($exp[2] == "pt") {
                    $exp[2] = "po";
                }
            }
        } else {
            $exp = "en";
        }

        //echo "<pre>";print_r($exp);die;
        $query = $this->db->select("id")
                ->from("tbl_language")
                ->where_in("language_code", $exp)
                ->get();
        //echo $this->db->last_query();die;
        if ($query->num_rows() > 1) {
            $row = $query->result_array();
            $this->set_lang_param("2");

            return $row;
        } else {
            $row = $query->row_array();
            $this->set_lang_param("1");
            return $row;
        }
    }

    public function getOpenArticles() {
        $deviceType = $this->get_device_type();
        $pageNum = $this->get_page_number();
        $appLangName = $this->get_app_lang_name();
        //echo $appLangName;die;
        if($appLangName == "en"){
            $appLangName = "1";
        }elseif($appLangName == "pt"){
            $appLangName = "2";
        }elseif($appLangName == "es"){
            $appLangName = "3";
        }
        if ($deviceType == "tablet" || $deviceType == "web") {
            $onePage = "30";
        } elseif ($deviceType == "mobile") {
            $onePage = "36";
        }
        for ($i = 1; $i <= $pageNum; $i++) {
            $offset += $onePage;
        }
        if (($onePage == $offset) || ($onePage < $offset)) {
            $offset = $offset - $onePage;
        }
        $date = date("Y-m-d");
        $categoryIds=str_replace(",","|",ltrim ($this->get_category_id(), ','));
        //echo $categoryIds;die;
        if ($this->get_category_id() != '') {
            $topicId = explode(",", $this->get_category_id());
        }
        //echo $this->get_language_id();die;
        $langExp = implode("|", $this->get_language_id());
        //echo "<pre>";print_r($langExp);die;
        if($langExp == ""){
            $langExp = $this->get_language_id();
        }
        
        
        $totalValue = count($topicId);
        //print_r($topicId);
        $queryNew = "";
        $queryNew .= "SELECT  
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
if(article_link='',via_url,if(via_url='0',via_url,article_link)) as article_link,
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
embed_video_thumb
                FROM (`tbl_new_articles`)
                WHERE `status` =  '2'
                AND article_language REGEXP '(^|,)($langExp)(,|$)'
                AND `publish_from` <= '$date'
                AND `publish_to` >= '$date' ";
        if ($this->get_category_id() != '' && $this->get_category_id() != '0') {
            $queryNew .= "AND (`category_id` REGEXP '(^|,)($categoryIds)(,|$)')";
        }
        
         $queryNew .= " ORDER BY publish_date desc 
                       LIMIT $offset,$onePage"; 
                
        $query = $this->db->query($queryNew);
        //echo $this->db->last_query();die;
        if ($query->num_rows() > 0) {
            $row = $query->result_array();
            return $row;
        } else {

            return FALSE;
        }
    }
    
    
    public function getOpenArticlesCount() {
        $deviceType = $this->get_device_type();
        $pageNum = $this->get_page_number();
        $appLangName = $this->get_app_lang_name();
        //echo $appLangName;die;
        if($appLangName == "en"){
            $appLangName = "1";
        }elseif($appLangName == "pt"){
            $appLangName = "2";
        }elseif($appLangName == "es"){
            $appLangName = "3";
        }
        if ($deviceType == "tablet" || $deviceType == "web") {
            $onePage = "30";
        } elseif ($deviceType == "mobile") {
            $onePage = "36";
        }
        for ($i = 1; $i <= $pageNum; $i++) {
            $offset += $onePage;
        }
        if (($onePage == $offset) || ($onePage < $offset)) {
            $offset = $offset - $onePage;
        }
        $date = date("Y-m-d");
        $categoryIds=str_replace(",","|",ltrim ($this->get_category_id(), ','));
        //echo $categoryIds;die;
        if ($this->get_category_id() != '') {
            $topicId = explode(",", $this->get_category_id());
        }
        //echo $this->get_language_id();die;
        $langExp = implode("|", $this->get_language_id());
        //echo "<pre>";print_r($langExp);die;
        if($langExp == ""){
            $langExp = $this->get_language_id();
        }
        
        
        $totalValue = count($topicId);
        //print_r($topicId);
        $queryNew = "";
        $queryNew .= "SELECT count(*) as pageCount FROM (`tbl_new_articles`)
                WHERE `status` =  '2'
                AND article_language REGEXP '(^|,)($langExp)(,|$)'
                AND `publish_from` <= '$date'
                AND `publish_to` >= '$date' ";
        if ($this->get_category_id() != '' && $this->get_category_id() != '0') {
            $queryNew .= "AND (`category_id` REGEXP '(^|,)($categoryIds)(,|$)')";
        }
        
         $queryNew .= " ORDER BY publish_date desc"; 
                
        $query = $this->db->query($queryNew);
        //echo $this->db->last_query();die;
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row;
        } else {

            return FALSE;
        }
    }

    public function articleCommentCountOpen() {
        $queryCommentCount = $this->db->select("count(id) as commentCount")
                ->from("tbl_article_comment")
                ->where("article_id", $this->get_id())
                ->get();
        $rowCount = $queryCommentCount->row_array();
        return $rowCount;
    }

    public function articleCommentCountMagazine() {
        $queryCommentCount = $this->db->select("count(id) as commentCount")
                ->from("tbl_magazine_article_comment")
                ->where("article_id", $this->get_id())
                ->get();
        $rowCount = $queryCommentCount->row_array();
        return $rowCount;
    }

    public function getMagzineArticle($groups, $locations, $disciplines, $branch) {
        $deviceType = $this->get_device_type();
        $pageNum = $this->get_page_number();

        if ($deviceType == "tablet" || $deviceType == "web") {
            $onePage = "30";
        } elseif ($deviceType == "mobile") {
            $onePage = "36";
        }
        for ($i = 1; $i <= $pageNum; $i++) {
            $offset += $onePage;
        }
        if (($onePage == $offset) || ($onePage < $offset)) {
            $offset = $offset - $onePage;
        }
        
        
        $artLang = implode("|",  $this->get_language_id());
        
        if($artLang == ""){
            $artLang = $this->get_language_id();
        }
        if($this->get_app_lang_name() == "en"){
           $langMagID = "1"; 
        }elseif($this->get_app_lang_name() == "pt"){
           $langMagID = "2"; 
        }elseif($this->get_app_lang_name() == "es"){
            $langMagID = "3";
        }
        $date = date("Y-m-d");
        $magID = $this->get_magazine_id();
        $queryGetCategory = $this->db->select("category")
                                         ->from("tbl_users")
                                         ->where("id",  $this->get_token())
                                         ->get();
            $rowCategory = $queryGetCategory->row_array();
        $catID = $rowCategory['category'];
        $myQuery = "";
        $myQuery .= " SELECT * FROM (SELECT 
        id,
    title,
    description,
    description_without_html,
    if(article_link='',link_url,if(article_link='0',link_url,article_link)) as article_link,
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
    publish_to,link_url,embed_video_thumb,embed_video,source, 0 as nr_order
                    FROM (`tbl_magazine_articles`)
                    WHERE FIND_IN_SET('$magID', magazine_id) > 0
                    AND `status` =  '2'
                    AND `publish_from` <= '$date'
                    AND `publish_to` >= '$date' UNION SELECT 
        na.id,
    title,
    description,
    description_without_html,
    if(article_link='',via_url,if(article_link='0',via_url,article_link)) as article_link,
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
    publish_to,na.id,embed_video_thumb,embed_video,source, ao.nr_order
                    FROM
        tbl_new_articles na
        LEFT JOIN tbl_article_order ao ON( na.id = ao.article_id AND ao.magazine_id = $magID ) ";

        if( !empty($groups) ) {
            $myQuery .= " JOIN tbl_article_group ag ON na.id = ag.article_id ";
        }

        if( !empty($locations) ){
            $myQuery .= " JOIN tbl_article_location al ON na.id = al.article_id ";
        }

        if( !empty($disciplines) ){
            $myQuery .= " JOIN tbl_article_discipline ad ON na.id = ad.article_id ";
        }

        if( !empty($branch) ){
            $myQuery .= " JOIN tbl_article_branch ab ON na.id = ab.article_id ";
        }

        $myQuery .= " WHERE FIND_IN_SET('$magID', na.magazine_id) > 0
                    AND `status` =  '2'
                    AND `publish_from` <= '$date'
                    AND `publish_to` >= '$date'";

        if( !empty($groups) ){
            $myQuery .= " AND ag . group_id IN($groups) ";
        }

        if( !empty($locations) ){
            $myQuery .= " AND al . location_id IN($locations) ";
        }

        if( !empty($disciplines) ){
            $myQuery .= " AND ad . discipline_id IN($disciplines) ";
        }

        if( !empty($branch) ){
            $myQuery .= " AND ab.branch = '$branch' ";
        }

        $myQuery .= ") s";
        
         $myQuery .=" ORDER BY s.nr_order asc
                    LIMIT $offset,$onePage";
        $query = $this->db->query($myQuery);
//        echo $this->db->last_query();die;
        if ($query->num_rows() > 0) {

            $row = $query->result_array();

//            echo "<pre>";print_r($row);die;
            return $row;
        } else {

            return FALSE;
        }
    }

    public function getTotalPagesMagzine() {
        $deviceType = $this->get_device_type();


        if ($deviceType == "tablet" || $deviceType == "web") {
            $onePage = "15";
        } elseif ($deviceType == "mobile") {
            $onePage = "9";
        }

        $date = date("Y-m-d");
        $queryTotalRows = $this->db->select("count(id) as totalPages")
                ->from("tbl_magazine_articles")
                ->where("magazine_id", $this->get_magazine_id())
                ->where("status", "2")
                ->where("publish_from <=", $date)
                ->where("publish_to >=", $date)
                ->where_in("article_language", $this->get_language_id())
                ->get();
        //echo $this->db->last_query();die;
        $rowTotalPages = $queryTotalRows->row_array();
        $row = round($rowTotalPages['totalPages'] / $onePage);
        if ($row == "0") {
            $row = "1";
        }
        return $row;
    }

    public function getTotalPagesArticles() {
        $deviceType = $this->get_device_type();


        if ($deviceType == "tablet" || $deviceType == "web") {
            $onePage = "15";
        } elseif ($deviceType == "mobile") {
            $onePage = "9";
        }

        $date = date("Y-m-d");
        if ($this->get_category_id() != '') {
            $topicId = explode(",", $this->get_category_id());
        }

        $queryTotalRows = $this->db->select("count(id) as totalPages")
                ->from("tbl_new_articles")
                ->where("magazine_id", "0")
                ->where("status", "2")
                ->where("publish_from <=", $date)
                ->where("publish_to >=", $date)
                ->where_in("article_language", $this->get_language_id());
        if ($this->get_category_id() != '') {
            foreach ($topicId as $t) {
                if ($t != '0') {
                    $queryTotalRows = $this->db->or_like("category_id", $t, "both");
                }
            }
        }
        $queryTotalRows = $this->db->get();
        //echo $this->db->last_query();die;
        $rowTotalPages = $queryTotalRows->row_array();
        //print_r($rowTotalPages);die;
        $row = round($rowTotalPages['totalPages'] / $onePage);
        if ($row == "0") {
            $row = "1";
        }
        return $row;
    }

    public function getArticleDetail() {
        $source=  $this->getSource();
        if($source=='customer'){
        $query = $this->db->select("id,
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
if(article_link!='',link_url,if(article_link!='0',link_url,article_link)) as article_link,
publish_date,
category_id,
tags,
crawling_date,
media_id,
website_url,
status,
magazine_id,
created_by,
allow_comment,
allow_share,
publish_from,
publish_to,
article_language,
link_url,
source,
embed_video_thumb
",FALSE)
                ->from("tbl_magazine_articles")
                ->where("id", $this->get_id())
                ->get();
        }else{
        $query = $this->db->select("id,
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
if(article_link!='',via_url,if(article_link!='0',via_url,article_link)) as article_link,
publish_date,
category_id,
tags,
crawling_date,
media_id,
website_url,
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
embed_video_thumb
",FALSE)
                ->from("tbl_new_articles")
                ->where("id", $this->get_id())
                ->get();
        }
        //echo $this->db->last_query();die;
        $row = $query->row_array();
        return $row;
    }

    public function getAllComments() {
        $server = $this->getServer();
        if ($server == 'open') {
            $query = $this->db->select("ac.*,tu.name,tu.photoUrl")
                    ->from("tbl_article_comment as ac")
                    ->join("tbl_users as tu", 'ac.user_id = tu.id', 'left')
                    ->where("ac.article_id", $this->get_id())
                    ->get();
            $row = $query->result_array();
        }if ($server == 'magazine') {
            $query = $this->db->select("ac.*,tu.name,tu.photoUrl")
                    ->from("tbl_magazine_article_comment as ac")
                    ->join("tbl_users as tu", 'ac.user_id = tu.id', 'left')
                    ->where("ac.article_id", $this->get_id())
                    ->get();
            $row = $query->result_array();
        }
        return $row;
    }

    public function getRelatedArticles() {
        $server = $this->getServer();
        if ($server == 'web') {
            $id = $this->get_id();
            $date = date("Y-m-d");
            $topicId = ltrim($this->get_category_id(), '|');
            $langId = ltrim($this->get_language_id(), ',');
            $magazineId = $this->get_magazine_id();
            if ($magazineId != '') {
                $sql = "SELECT 
    *
FROM ";
    if($this->getSource()=='customer'){
    $sql .= "(`tbl_magazine_articles`)";
    }else{
    $sql .= "(`tbl_new_articles`)";    
    }
$sql .= "WHERE
    `status` = '2'
        AND `publish_from` <= '$date'
        AND `publish_to` >= '$date'
        AND `id` != '$id'";
if($this->getSource()=='customer'){
$sql .= " AND magazine_id REGEXP '(^|,)($magazineId)(,|$)'";
}
//                if ($topicId != '') {
//                    $sql .= " AND category_id REGEXP '(^|,)($topicId)(,|$)'";
//                }
                if ($langId != '' && $this->getSource()!='customer') {
                    $sql .= " AND article_language IN($langId)";
                }
                $sql .= " ORDER BY `publish_date` desc
LIMIT 4";
            } else {
                $sql = "SELECT 
    *
FROM ";
    if($this->getSource()=='customer'){
    $sql .= "(`tbl_magazine_articles`)";
    }else{
    $sql .= "(`tbl_new_articles`)";    
    }
$sql .= "WHERE
    `status` = '2'
        AND `publish_from` <= '$date'
        AND `publish_to` >= '$date'
        AND `id` != '$id'";
//                if ($topicId != '') {
//                    $sql .= " AND category_id REGEXP '(^|,)($topicId)(,|$)'";
//                }
                if ($langId != '' && $this->getSource()!='customer') {
                    $sql .= " AND article_language IN($langId)";
                }
                $sql .= " ORDER BY `publish_date` desc
LIMIT 4";
            }
            $query = $this->db->query($sql);
            //echo $this->db->last_query();
            if ($query->num_rows() > 0) {

                $row = $query->result_array();
                $i = 0;
                foreach ($row as $r) {
                    $catId = explode(",", $r['category_id']);
                    $queryTopic = $this->db->select("*")
                            ->from("tbl_category")
                            ->where_in("id", $catId)
                            ->get();
                    //echo $this->db->last_query();die;
                    $rowTopic = $queryTopic->row_array();
                    //echo "<pre>";print_r($rowTopic);die;
                    $row[$i]['topicName'] = $rowTopic['category_name'];
                    $i++;
                }

                //echo "<pre>";print_r($row);die;
                return $row;
            } else {

                return FALSE;
            }
        } else {
            $date = date("Y-m-d");
            $topicId = explode(",", $this->get_category_id());

            $query = $this->db->select("*")
                    ->from("tbl_new_articles")
                    ->where("status", "2")
                    ->where("publish_from <=", $date)
                    ->where("publish_to >=", $date)
                    ->where("id !=", $this->get_id());
            foreach ($topicId as $t) {
                $query = $this->db->or_like("category_id", $t, "both");
            }
            $query = $this->db->order_by("publish_date", "desc")
                    ->limit("4")
                    ->get();
            //echo $this->db->last_query();die;
            if ($query->num_rows() > 0) {

                $row = $query->result_array();
                $i = 0;
                foreach ($row as $r) {
                    $catId = explode(",", $r['category_id']);
                    $queryTopic = $this->db->select("*")
                            ->from("tbl_category")
                            ->where_in("id", $catId)
                            ->get();
                    //echo $this->db->last_query();die;
                    $rowTopic = $queryTopic->row_array();
                    //echo "<pre>";print_r($rowTopic);die;
                    $row[$i]['topicName'] = $rowTopic['category_name'];
                    $i++;
                }

                //echo "<pre>";print_r($row);die;
                return $row;
            } else {

                return FALSE;
            }
        }
    }

    public function getThreeMagazineOnly() {
        $date = date("Y-m-d");
        $queryGetUserMagId = $this->db->select("magazine_id")
                ->from("tbl_user_magazines")
                ->join("tbl_users u", "user_id = u.id")
                ->join("tbl_magazine_location", "location = country AND id_magazine = magazine_id")
                ->where("user_id", $this->get_token())
                ->get();
        $rowMagId = $queryGetUserMagId->result_array();

        if ($queryGetUserMagId->num_rows() > 0) {
            foreach ($rowMagId as $r) {

                $magId[] = $r['magazine_id'];
            }
        } else {
            $magId = array(0);
        }
        $query = $this->db->select("*")
                ->from("tbl_magazine")
                ->where("publish_date_from <=", $date)
                ->where("publish_date_to >=", $date)
                ->where("language_id", $this->get_language_id())
                ->where("status", "1")
                ->where_in("id", $magId)
                ->limit("3")
                ->get();
        if ($query->num_rows() > 0) {
            $row = $query->result_array();
            return $row;
        } else {
            return FALSE;
        }
    }

    public function getSearchOpenArticles() {
        $date = date("Y-m-d");
        $lang = $this->get_language_id();
        $imp = implode(",", $lang);
        $lang = $imp;
        if ($lang == '') {
            $lang = '1';
        }
        if ($this->get_category_id() != '') {
            $topicId = explode(",", $this->get_category_id());
            $topicImplode = implode("|", $topicId);

            //echo "<pre>";print_r($topicImplode);die;
            //print_r(count($topicId));die;
//        $query = $this->db->select("*")
//                          ->from("tbl_new_articles")                          
//                          ->where("status","2")
//                          ->where("publish_from <=",$date)
//                          ->where("publish_to >=",$date)
//                          ->where("language_id",  $this->get_language_id())
//                          ->like("title",  $this->get_title(),"both");                          
//       
//       $query = $this->db->where("category_id","REGEXP '(^|,)($topicImplode)(,|$)'");
//       
//       $query = $this->db->get();
            //echo $this->db->last_query();die;
//        $title = $this->get_title();
//       $query = "";
//       $query .= "SELECT 
//    *
//FROM
//    (tbl_new_articles)
//WHERE
//    status = '2' AND publish_from <= '$date' AND publish_to >= '$date' AND language_id = ".$this->get_language_id()." AND (";
//       $getCount = count($topicId);
//       $i = 1;
//       foreach($topicId as $t){
//           if($i != $getCount){
//       $query .= " FIND_IN_SET('$t', category_id) > 0 OR";
//           }else{
//               $query .= " FIND_IN_SET('$t', category_id) > 0";
//           }
//       $i++;}
//       $query .= " ) AND title LIKE '%$title%'";
//     
//       $queryRes = $this->db->query($query);
//       //echo $this->db->last_query();die;
//       $row = $queryRes->result_array();
//       return $row;


            $title = $this->get_title();
            $query = "";
            $query .= "SELECT 
      
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
embed_video_thumb,source
FROM
    (tbl_new_articles)
WHERE
    status = '2' AND publish_from <= '$date' AND publish_to >= '$date' AND article_language IN ($lang) AND ";

            $query .= "category_id REGEXP '(^|,)($topicImplode)(,|$)' AND title LIKE '%$title%'";
            $query .= " OR tags LIKE '%$title%'";
            $query .= "order by publish_date desc";
            $query .= " limit 20";
        } else {
            $title = $this->get_title();
            $query = "";
            $query .= "SELECT 
    *
FROM
    (tbl_new_articles)
WHERE
    status = '2' AND publish_from <= '$date' AND publish_to >= '$date' AND article_language IN ($lang) AND ";
            if ($title != '') {
                $query .= " title LIKE '%$title%'";
                $query .= " OR tags LIKE '%$title%'";
            } else {
                $query .= " title = ''";
                $query .= " OR tags =''";
            }
            $query .= "order by publish_date desc";
            $query .= " limit 20";
        }

        $queryRes = $this->db->query($query);
        ///echo $this->db->last_query();die;

        $row = $queryRes->result_array();
        return $row;
    }

    public function getSearchedMagazineData() {

        $date = date("Y-m-d");
        if ($this->get_title()) {
//            $query = $this->db->select("*")
//                    ->from("tbl_magazine_articles")
//                    ->where("status", "2")
//                    ->where("magazine_id", $this->get_magazine_id())
//                    ->where("publish_from <=", $date)
//                    ->where("publish_to >=", $date)
//                    ->like("title", $this->get_title(), "both")
//                    ->or_like("tags", $this->get_title(), "both")
//                    ->order_by("publish_date", "desc")
//                    ->limit("20")
//                    ->get();
//            //echo $this->db->last_query();die;
//            $row = $query->result_array();
            
            $magID=  $this->get_magazine_id();
            $title=$this->get_title();
            $myQuery = "";
        $myQuery .= " SELECT * FROM (SELECT 
        id,
    title,
    description,
    description_without_html,
    if(article_link='',link_url,if(article_link='0',link_url,article_link)) as article_link,
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
    publish_to,link_url,embed_video_thumb,embed_video,source
                    FROM (`tbl_magazine_articles`)
                    WHERE FIND_IN_SET('$magID', magazine_id) > 0
                    AND `status` =  '2'
                    AND `publish_from` <= '$date'
                    AND `publish_to` >= '$date' AND (title LIKE '%$title%' OR tags LIKE '%$title%')  UNION SELECT 
        id,
    title,
    description,
    description_without_html,
    if(article_link='',via_url,if(article_link='0',via_url,article_link)) as article_link,
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
    publish_to,id,embed_video_thumb,embed_video,source
                    FROM (`tbl_new_articles`)
                    WHERE FIND_IN_SET('$magID', magazine_id) > 0
                    AND `status` =  '2'
                    AND `publish_from` <= '$date'
                    AND `publish_to` >= '$date' AND (title LIKE '%$title%' OR tags LIKE '%$title%')) s";
        
         $myQuery .=" ORDER BY s.`publish_date` desc
                    LIMIT 20";
        $query = $this->db->query($myQuery);
        //echo $this->db->last_query();    die;
            $row=$query->result_array();
            
            
            
            
        } else {
            $row = array();
        }
        return $row;
    }

    public function getArticleIdUsingTitle() {
        $date = date("Y-m-d");
        if ($this->get_category_id() != '') {
            $topicImplode = $this->get_category_id();

            $title = $this->get_title();
            $query = "";
            $query .= "SELECT 
    *
FROM
    (tbl_new_articles)
WHERE
    status = '2' AND publish_from <= '$date' AND publish_to >= '$date' AND article_language = " . $this->get_language_id() . " AND ";

            $query .= "category_id REGEXP '(^|,)($topicImplode)(,|$)' AND title LIKE '%$title%'";
            $query .= "order by publish_date desc";
            $query .= " limit 1";
        } else {
            $title = $this->get_title();
            $query = "";
            $query .= "SELECT 
    *
FROM
    (tbl_new_articles)
WHERE
    status = '2' AND publish_from <= '$date' AND publish_to >= '$date' AND article_language = " . $this->get_language_id() . " AND ";

            $query .= " title LIKE '%$title%'";
            $query .= "order by publish_date desc";
            $query .= " limit 1";
        }

        $queryRes = $this->db->query($query);
        //echo $this->db->last_query();die;
        $row = $queryRes->row_array();
        return $row;
    }

    public function getSearchMagazines() {
        $date = date("Y-m-d");

        $queryGetUserMagId = $this->db->select("magazine_id")
                ->from("tbl_user_magazines")
                ->where("user_id", $this->session->userdata("user_id"))
                ->get();
        $rowMagId = $queryGetUserMagId->result_array();

        if ($queryGetUserMagId->num_rows() > 0) {
            foreach ($rowMagId as $r) {

                $magId[] = $r['magazine_id'];
            }
        } else {
            $magId = array(0);
        }
        //echo "<pre>";print_r($magId);die;

        $query = $this->db->select("*")
                ->from("tbl_magazine")
                ->where("publish_date_from <=", $date)
                ->where("publish_date_to >=", $date)
                ->where("language_id", $this->get_language_id())
                ->where("status", "1")
                ->where_in("id", $magId)
                ->like("title", $this->get_title(), "both")
                ->get();
        //echo $this->db->last_query();die;

        if ($query->num_rows() > 0) {
            $row = $query->result_array();
            return $row;
        } else {
            return FALSE;
        }
    }

    public function getRelatedArticleList() {
        $id = $this->get_id();
        $date = date("Y-m-d");
        $topicId = ltrim($this->get_category_id(), '|');
        $langId = ltrim($this->get_language_id(), ',');
        $magazineId = $this->get_magazine_id();
        if ($magazineId != '') {
            $sql = "SELECT 
    *
FROM
    (`tbl_magazine_articles`)
WHERE
    `status` = '2'
        AND `publish_from` <= '$date'
        AND `publish_to` >= '$date'
        AND `id` != '$id'
        AND `magazine_id` = '$magazineId'";
            if ($topicId != '') {
                $sql .= " AND category_id REGEXP '(^|,)($topicId)(,|$)'";
            }if ($langId != '') {
                $sql .= " AND article_language IN($langId)";
            }
            $sql .= " ORDER BY `publish_date` desc
LIMIT 4";
        } else {
            $sql = "SELECT 
    *
FROM
    (`tbl_new_articles`)
WHERE
    `status` = '2'
        AND `publish_from` <= '$date'
        AND `publish_to` >= '$date'
        AND `id` != '$id'
        AND `magazine_id` = '$magazineId'";
            if ($topicId != '') {
                $sql .= " AND category_id REGEXP '(^|,)($topicId)(,|$)'";
            }if ($langId != '') {
                $sql .= " AND article_language IN($langId)";
            }
            $sql .= " ORDER BY `publish_date` desc
LIMIT 4";
        }
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) {

            $row = $query->result_array();
            $i = 0;
            foreach ($row as $r) {
                $catId = explode(",", $r['category_id']);
                $queryTopic = $this->db->select("*")
                        ->from("tbl_category")
                        ->where_in("id", $catId)
                        ->get();
                //echo $this->db->last_query();die;
                $rowTopic = $queryTopic->row_array();
                //echo "<pre>";print_r($rowTopic);die;
                $row[$i]['topicName'] = $rowTopic['category_name'];
                $i++;
            }

            //echo "<pre>";print_r($row);die;
            return $row;
        } else {

            return FALSE;
        }
    }
    
    public function getMagazineCover(){
        
        $query=  $this->db->select('')
                ->from('tbl_magazine')
                ->where('id',  $this->get_magazine_id())
                ->get();
        return $query->row_array();
        
    }
	public function getMagzineArticleCount() {
        $deviceType = $this->get_device_type();
        $pageNum = $this->get_page_number();

        if ($deviceType == "tablet" || $deviceType == "web") {
            $onePage = "30";
        } elseif ($deviceType == "mobile") {
            $onePage = "36";
        }
        for ($i = 1; $i <= $pageNum; $i++) {
            $offset += $onePage;
        }
        if (($onePage == $offset) || ($onePage < $offset)) {
            $offset = $offset - $onePage;
        }
        
        
        $artLang = implode("|",  $this->get_language_id());
        
        if($artLang == ""){
            $artLang = $this->get_language_id();
        }
        if($this->get_app_lang_name() == "en"){
           $langMagID = "1"; 
        }elseif($this->get_app_lang_name() == "pt"){
           $langMagID = "2"; 
        }elseif($this->get_app_lang_name() == "es"){
            $langMagID = "3";
        }
        $date = date("Y-m-d");
        $magID = $this->get_magazine_id();
        $queryGetCategory = $this->db->select("category")
                                         ->from("tbl_users")
                                         ->where("id",  $this->get_token())
                                         ->get();
            $rowCategory = $queryGetCategory->row_array();
        $catID = $rowCategory['category'];
        $myQuery = "";
        $myQuery .= " SELECT count(*) as pageCount  FROM (SELECT 
        id,
    title,
    description,
    description_without_html,
    if(article_link='',link_url,if(article_link='0',link_url,article_link)) as article_link,
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
    publish_to,link_url,embed_video_thumb,embed_video,source
                    FROM (`tbl_magazine_articles`)
                    WHERE FIND_IN_SET('$magID', magazine_id) > 0
                    AND `status` =  '2'
                    AND `publish_from` <= '$date'
                    AND `publish_to` >= '$date' UNION SELECT 
        id,
    title,
    description,
    description_without_html,
    if(article_link='',via_url,if(article_link='0',via_url,article_link)) as article_link,
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
    publish_to,id,embed_video_thumb,embed_video,source
                    FROM (`tbl_new_articles`)
                    WHERE FIND_IN_SET('$magID', magazine_id) > 0
                    AND `status` =  '2'
                    AND `publish_from` <= '$date'
                    AND `publish_to` >= '$date') s";
        
         $myQuery .=" ORDER BY s.`publish_date` desc
                    LIMIT $offset,$onePage";
        $query = $this->db->query($myQuery);
        //echo $this->db->last_query();die;
        if ($query->num_rows() > 0) {

            $row = $query->row_array();

            //echo "<pre>";print_r($row);die;
            return $row;
        } else {

            return FALSE;
        }
    }

    public function updateBranchLink($branchLink){

        $articleId = $this->get_id();

        $sql = $this->db->query("UPDATE tbl_new_articles SET branch_link = '{$branchLink}' WHERE id = {$articleId}");

        if ($sql) {
            return TRUE;
        }

        return FALSE;

    }

    public function getNewArticleDetail() {

        $articleId = $this->get_id();
        $magazineId = $this->get_magazine_id();

        $sql = "SELECT * FROM tbl_new_articles WHERE id = $articleId ";

        if( !empty( $magazineId ) ){
            $sql .= " AND FIND_IN_SET('$magazineId', magazine_id) > 0";
        }

        $query = $this->db->query($sql);

        $row = array();

        if ($query->num_rows() > 0) {
            $row = $query->row_array();
        }

        return $row;

    }


}
