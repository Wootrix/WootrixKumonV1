<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * @author Techahead
 * 
 */

class Article_comments extends CI_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session', true);
        
    }
    
    public function getArticleComments(){
       $authValidValue = $this->config->item("validValue");
        if ($authValidValue != '') {
//            $json = '{
//            "token":"1",
//            "articleId":"805",
//            "appLanguage":"en",
//            "type":"open"
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
                $articleCommObj = $this->load->model("webservices/article_comment_model");
                $articleCommObj = new article_comment_model();
                
                $articleCommObj->set_token($json['token']);
                
                $verifyToken = $articleCommObj->verifyTokenValue();
                if ($verifyToken == TRUE) {                    
                  $articleCommObj->set_article_id($json['articleId']);
                  $articleCommObj->setType($json['type']);
                  $getArticleComm = $articleCommObj->getArticleComments();
                  
                  $result = array();
                  $i=0;
                  if($getArticleComm != ''){
                  foreach($getArticleComm as $g){
                      if($g['name']!=''){
                     $result['comments'][$i]['name'] = $g['name'];
                      }else{
                     $result['comments'][$i]['name'] = '';
                      }
                       $exp = explode(":",$g['photoUrl']);
                if($exp[0] == "http" || $exp[0] == "https"){
                    $url = $g['photoUrl'];
                }else{
                    if($g['photoUrl']){
                    $url = $this->config->base_url()."assets/user_image/".$g['photoUrl'];
                    }else{
                        $url = "";
                    }
                }               
                     //$result['comments'][$i]['photoUrl'] = $this->config->base_url()."assets/user_image/".$g['photoUrl'];
                     $result['comments'][$i]['photoUrl'] = $url;
                     $result['comments'][$i]['comment'] = $g['comment'];
                     $result['comments'][$i]['commentDate'] = $g['created_date'];
                  $i++;}
                  $result1[] = $result;
                  }else{
                     $result1 = array(); 
                  }
                  //echo "<pre>";print_r($result1);die;
                  echo json_encode(array('data' => $result1, 'message' => $this->lang->line("article_comment_data_webservice"), 'success' => true));
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
    
    public function postComment(){
        $authValidValue = $this->config->item("validValue");
        if ($authValidValue != '') {
//            $json = '{
//            "token":"1",
//            "comment":"ioyjkhfjkgbfk",
//            "articleId":"805",
//            "appLanguage":"en",
//            "type":"open"
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
                $articleCommObj = $this->load->model("webservices/article_comment_model");
                $articleCommObj = new article_comment_model();
                
                $articleCommObj->set_token($json['token']);
                
                $verifyToken = $articleCommObj->verifyTokenValue();
                if ($verifyToken == TRUE) {                    
                  $articleCommObj->set_article_id($json['articleId']);
                  $articleCommObj->set_comment($json['comment']);
                  $articleCommObj->set_user_id($json['token']);
                  $articleCommObj->setType($json['type']);
                  $postComment = $articleCommObj->postArticleComments();
                  //echo "<pre>";print_r($result1);die;
                  echo json_encode(array('data' => array(), 'message' => $this->lang->line("comment_post_webservice"), 'success' => true));
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

