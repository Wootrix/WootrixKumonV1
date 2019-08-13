<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * @author Techahead
 * 
 */

class Search_open_articles extends CI_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session', true);
        
    }
    
    public function searchOpenArticles(){
        $authValidValue = $this->config->item("validValue");
        if ($authValidValue != '') {
//            $json = '{
//            "token":"1",
//            "searchKeyword":"airbus",
//            "topics":"1,6,7",
//            "articleLanguage":"en",
//            "appLanguage":"en"
//            }';

            $json = trim(file_get_contents('php://input'));

            if (!empty($json)) {
                $json = json_decode($json, true);
                if($json['appLanguage'] == "en"){
                    $this->lang->load('en');
                }elseif($json['appLanguage'] == "pt"){
                    $this->lang->load('po');
                }elseif($json['appLanguage'] == "es"){
                    $this->lang->load('sp');
                }
                $articleObj = $this->load->model("webservices/new_articles_model");
                $articleObj = new new_articles_model();
                
                $articleObj->set_token($json['token']);
                $articleObj->set_language_name($json['articleLanguage']);
                $getLangId = $articleObj->getLanguageId();
                if($articleObj->get_lang_param() == "2"){
                    foreach($getLangId as $l){
                       $lang[] = $l['id'];
                    }
                }else{
                    $lang = $getLangId['id'];
                }
                //echo "<pre>";print_r($lang);die;
                $articleObj->set_language_id($lang);
                $verifyToken = $articleObj->verifyTokenValue();
                if ($verifyToken == TRUE) {                    
                  $articleObj->set_category_id($json['topics']);
                  $articleObj->set_title($json['searchKeyword']);
                  
                  $getSearchArticle = $articleObj->getSearchOpenArticles();
                  
                  $result = array();
                  $i=0;
                  foreach($getSearchArticle as $g){
                    $articleObj->set_id($g['id']);
                    $getCommentCount = $articleObj->articleCommentCountOpen();
                    
                    if ($g['media_type'] == '0') {
                        $mediaType = "photo";
                    }
                    if ($g['media_type'] == '1') {
                        $mediaType = "video";
                    }
                    if ($g['allow_share'] == '0') {
                        $allowShare = "N";
                    }
                    if ($g['allow_share'] == '1') {
                        $allowShare = "Y";
                    }

                    if ($g['allow_comment'] == '0') {
                        $allowComment = "N";
                    }
                    if ($g['allow_comment'] == '1') {
                        $allowComment = "Y";
                    }
                    if ($g['article_link'] != '') {
                        $detailScreen = "Y";
                    }else{
                        $detailScreen = "N";
                    }
                    
                  
                  if($g['embed_video']!=''){
                      
                  $result['openArticles'][$i]['embedded_thumbnail'] = base_url().$g['embed_video_thumb'];
                  preg_match('/src="([^"]+)"/', $g['embed_video'], $match);
                  $result['openArticles'][$i]['embedded_video_link'] = $match[1];
                  $result['openArticles'][$i]['embedded_video'] = $g['embed_video']; 
                  $mediaType='embedded';
                  }  else {
                  $result['openArticles'][$i]['embedded_thumbnail'] = '';
                  $result['openArticles'][$i]['embedded_video_link'] = '';
                  $result['openArticles'][$i]['embedded_video'] = '';  
                  }
                  $result['openArticles'][$i]['articleId'] = $g['id'];
                  if($g['embed_video']!=''){
                  $result['openArticles'][$i]['articleType'] = 'embedded';
                  }else{
                  $result['openArticles'][$i]['articleType'] = $mediaType;
                  }
                  $result['openArticles'][$i]['title'] = $g['title'];
                  $result['openArticles'][$i]['createdDate'] = $g['publish_date'];
                  if($g['article_link']!='' && $g['article_link']!='0'){
                  $result['openArticles'][$i]['fullSoruce'] = $g['article_link'];
                  $link = str_replace(array('http://', 'https://', 'www.'), '', $g['article_link']);
                  $link_name = explode('.', $link);
                  $result['openArticles'][$i]['source'] = $link_name[0];
                  }else{
                  $result['openArticles'][$i]['fullSoruce'] = $g['website_url'];
                  $link = str_replace(array('http://', 'https://', 'www.'), '', $g['website_url']);
                  $link_name = explode('.', $link);
                  $result['openArticles'][$i]['source'] = $link_name[0];
                      
                  }
                  $result['openArticles'][$i]['articleDescPlain'] = preg_replace("/&#?[a-z0-9]+;/i","",htmlspecialchars_decode($g['description_without_html']));
                  if($g['created_by'] == '0'){
                        $result['openArticles'][$i]['articleDescHTML'] = "";
                    }elseif($g['created_by'] == '1'){
                        $result['openArticles'][$i]['articleDescHTML'] = base64_encode($g['description']);
                    }
                 
                $epImage = explode(":",$g['image']);
                if (($epImage[0] == "http" || $epImage[0] == "https")) {
                  $image=$g['image']; 
                }else{
                  $image=$this->config->base_url() . "assets/Article/" .$g['image'];   
                }
                    
                  $result['openArticles'][$i]['coverPhotoUrl'] = $image;       
                  
                  $epVideo = explode(":",$g['video_path']);
                if (($epVideo[0] == "http" || $epVideo[0] == "https")) {
                  $video=$g['video_path']; 
                }else{
                  $video=$this->config->base_url() . "assets/Article/" .$g['video_path'];   
                }
                  
                  
                  $result['openArticles'][$i]['articleVideoUrl'] = $video;
                  $result['openArticles'][$i]['allowShare'] = $allowShare;
                  $result['openArticles'][$i]['allowComment'] = $allowComment;
                  $result['openArticles'][$i]['commentsCount'] = $getCommentCount['commentCount'];
                  $result['openArticles'][$i]['detailScreen'] = $detailScreen;
                  $result['openArticles'][$i]['createdBy'] = $g['created_by'];
                  $i++;}
                  if($result['openArticles'] != ''){
                  $result1[] = $result;
                  }else{
                     $result1= array(); 
                  }
                  //echo "<pre>";print_r($getSearchArticle);die;
                  echo json_encode(array('data' => $result1, 'message' => $this->lang->line("search_open_webservice"), 'success' => true));
                  exit;
                  
                }else{
                   echo json_encode(array('data' => array(), 'message' => $this->lang->line("token_expire_webservice"), 'success' => false));
                   exit; 
                }
                
            } else {
                echo json_encode(array('data' => array(), 'message' => $this->lang->line("input_data_webservice"), 'success' => false));
                exit;
            }
        } else {
            echo json_encode(array('data' => array(), 'message' => $this->lang->line("input_data_webservice"), 'success' => false));
            exit;
        }
    }
    
    public function searchMagazineData(){
        $authValidValue = $this->config->item("validValue");
        if ($authValidValue != '') {
//            $json = '{
//            "token":"1",
//            "searchKeyword":"airbus",
//            "magazineId":"1",
//            "appLanguage":"en"
//            }';

            $json = trim(file_get_contents('php://input'));

            if (!empty($json)) {
                $json = json_decode($json, true);
                if($json['appLanguage'] == "en"){
                    $this->lang->load('en');
                }elseif($json['appLanguage'] == "pt"){
                    $this->lang->load('po');
                }elseif($json['appLanguage'] == "es"){
                    $this->lang->load('sp');
                }
                $articleObj = $this->load->model("webservices/new_articles_model");
                $articleObj = new new_articles_model();
                
                $articleObj->set_token($json['token']);
                
                $verifyToken = $articleObj->verifyTokenValue();
                if ($verifyToken == TRUE) {                    
                  $articleObj->set_magazine_id($json['magazineId']);
                  $articleObj->set_title($json['searchKeyword']);
                  $getMagazineData = $articleObj->getSearchedMagazineData();
                  $result = array();
                  $i=0;
                  foreach($getMagazineData as $g){
                    $articleObj->set_id($g['id']);
                    $getCommentCount = $articleObj->articleCommentCountMagazine();
                    
                    if ($g['media_type'] == '0') {
                        $mediaType = "photo";
                    }
                    if ($g['media_type'] == '1') {
                        $mediaType = "video";
                    }
                    if ($g['allow_share'] == '0') {
                        $allowShare = "N";
                    }
                    if ($g['allow_share'] == '1') {
                        $allowShare = "Y";
                    }

                    if ($g['allow_comment'] == '0') {
                        $allowComment = "N";
                    }
                    if ($g['allow_comment'] == '1') {
                        $allowComment = "Y";
                    }
                    if ($g['article_link'] != '') {
                        $detailScreen = "Y";
                    }else{
                        $detailScreen = "N";
                    }
                    
                  if($g['embed_video']!=''){
                      
                  $result['openArticles'][$i]['embedded_thumbnail'] = base_url().$g['embed_video_thumb'];
                  preg_match('/src="([^"]+)"/', $g['embed_video'], $match);
                  $result['openArticles'][$i]['embedded_video_link'] = $match[1];
                  $result['openArticles'][$i]['embedded_video'] = $g['embed_video']; 
                  $mediaType='embedded';
                  }  else {
                  $result['openArticles'][$i]['embedded_thumbnail'] = '';
                  $result['openArticles'][$i]['embedded_video_link'] = '';
                  $result['openArticles'][$i]['embedded_video'] = '';  
                  }
                  $result['openArticles'][$i]['articleId'] = $g['id'];
                  $result['openArticles'][$i]['articleType'] = $mediaType;
                  $result['openArticles'][$i]['title'] = $g['title'];
                  $result['openArticles'][$i]['createdDate'] = $g['publish_date'];
                  $result['openArticles'][$i]['source'] = $g['article_link'];
                  $result['openArticles'][$i]['fullSoruce'] = $g['article_link'];
                  $result['openArticles'][$i]['articleDescPlain'] = preg_replace("/&#?[a-z0-9]+;/i","",htmlspecialchars_decode($g['description_without_html']));
                  $result['openArticles'][$i]['articleDescHTML'] = base64_encode($g['description']);
                 
                  
                  $result['openArticles'][$i]['coverPhotoUrl'] = $g['image'];                  
                  $result['openArticles'][$i]['articleVideoUrl'] = $g['video_path'];
                  $result['openArticles'][$i]['allowShare'] = $allowShare;
                  $result['openArticles'][$i]['allowComment'] = $allowComment;
                  $result['openArticles'][$i]['commentsCount'] = $getCommentCount['commentCount'];
                  $result['openArticles'][$i]['detailScreen'] = $detailScreen;
                  $result['openArticles'][$i]['createdBy'] = $g['created_by'];
                  $result['openArticles'][$i]['createdSource'] = $g['source'];
                    
                  $i++;}
                  if($result['openArticles'] != ''){
                  $result1[] = $result;
                  }else{
                     $result1= array(); 
                  }
                  
                  //echo "<pre>";print_r($getSearchArticle);die;
                  echo json_encode(array('data' => $result1, 'message' => $this->lang->line("search_magazine_webservice"), 'success' => true));
                  exit;
                  
                }else{
                   echo json_encode(array('data' => array(), 'message' => $this->lang->line("token_expire_webservice"), 'success' => false));
                   exit; 
                }
                
            } else {
                echo json_encode(array('data' => array(), 'message' => $this->lang->line("input_data_webservice"), 'success' => false));
                exit;
            }
        } else {
            echo json_encode(array('data' => array(), 'message' => $this->lang->line("input_data_webservice"), 'success' => false));
            exit;
        }
    }
    
}

