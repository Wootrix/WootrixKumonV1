<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * @author Techahead
 * 
 */

class Search_advertise_open extends CI_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session', true);
        
    }
    
    public function searchAdvertiseOpen(){
       $authValidValue = $this->config->item("validValue");
        if ($authValidValue != '') {
//            $json = '{
//            "token":"1",
//            "platform":"tablet",
//            "topics":"1,6,7",
//            "articleLanguage":"en",
//            "appLanguage":"en"
//            }';

            $json = trim(file_get_contents('php://input'));
            //echo $json;die;
            if (!empty($json)) {
                $json = json_decode($json, true);
                if($json['appLanguage'] == "en"){
                    $this->lang->load('en');
                }elseif($json['appLanguage'] == "pt"){
                    $this->lang->load('po');
                }elseif($json['appLanguage'] == "es"){
                    $this->lang->load('sp');
                }    
                $advObj = $this->load->model("webservices/advertisement_model");
                $advObj = new advertisement_model();
                
                $advObj->set_lang($json['articleLanguage']);
                $getLangId = $advObj->getLanguageId();
                if($advObj->get_lang_param() == "2"){
                    foreach($getLangId as $l){
                       $lang[] = $l['id'];
                    }
                }else{
                    $lang = $getLangId['id'];
                }
                //echo "<pre>";print_r($lang);die;
                
                if($json['appLanguage']=='en' || $json['applanguage']=='en'){
                $languageId='1';
                }else if($json['appLanguage']=='es' || $json['applanguage']=='es'){
                $languageId='3';
                }else if($json['appLanguage']=='pt' || $json['applanguage']=='pt'){
                $languageId='2';
                }
                //echo "====".$languageId;
                $advObj->set_language_id($languageId);
                $advObj->set_catagory_id($json['topics']);
                $getAdvOpen = $advObj->getAllAdvertiseOpen();
                //print_r($getAdvOpen);die;
                
                $i=0;
                //$result = array();
                foreach($getAdvOpen as $key=>$g){
                    if($g['media_type'] == "1"){
                        $type = "standard";
                        $bannUrl = $this->config->base_url()."assets/Advertise/".$g['cover_image'];
                        $portraitUrl = $this->config->base_url()."assets/Advertise/".$g['portrait_image'];
                        $landscapeUrl = $this->config->base_url()."assets/Advertise/".$g['landscape_image'];
                        $videoUrl = "";
                    }
                    if($g['media_type'] == "2"){
                        if($g['cover_image']=='embed'){
                        $type = "embeded";
                        $bannUrl = '';
                        $videoUrl = $g['embed_video'];
                        preg_match('/src="([^"]+)"/', $videoUrl, $match);
                        $explode= explode("/", $match[1]);
                        $videoThumb=end($explode);
                        $portraitUrl = "";
                        $landscapeUrl = "";
                        }else{
                        $type = "video";
                        $bannUrl = $this->config->base_url()."assets/Advertise/thumbs/".$g['cover_image']."demo.jpeg";
                        $videoUrl = $this->config->base_url()."assets/Advertise/".$g['cover_image'];
                        $portraitUrl = "";
                        $landscapeUrl = "";
                        }
                    }
                    
                    
                    
                    if($json['platform'] == "mobile" || $json['platform'] == "web" || $json['platform'] == "tablet"){
                    if($g['layout_type'] == "1"){
                        
                        $result['advertisement_layout1'][$i]['advertisementId'] = $g['id'];
                        $result['advertisement_layout1'][$i]['type'] = $type;
                        
                        $result['advertisement_layout1'][$i]['bannerURL'] = $bannUrl;
                        $result['advertisement_layout1'][$i]['portraitURL'] = $portraitUrl;
                        $result['advertisement_layout1'][$i]['landscapeURL'] = $landscapeUrl;
                        $result['advertisement_layout1'][$i]['linkToOpen'] = $g['link'];
                        $result['advertisement_layout1'][$i]['videoURL'] = $videoUrl;
                        if($type=='embeded'){
                        $result['advertisement_layout1'][$i]['videoThumbURL'] = base_url().$g['embed_thumb'];
                        $result['advertisement_layout1'][$i]['embededUrl'] = $match[1]; 
                        }
                        $result['advertisement_layout1'][$i]['timeInSeconds'] = $g['display_time']; 
                        
                    }
                    }
                    if($json['platform'] != "mobile"){
                    if($g['layout_type'] == "2"){
                        $result['advertisement_layout2'][$i]['advertisementId'] = $g['id'];
                        $result['advertisement_layout2'][$i]['type'] = $type;
                        $result['advertisement_layout2'][$i]['bannerURL'] = $bannUrl;
                        $result['advertisement_layout2'][$i]['portraitURL'] = $portraitUrl;
                        $result['advertisement_layout2'][$i]['landscapeURL'] = $landscapeUrl;
                        $result['advertisement_layout2'][$i]['linkToOpen'] = $g['link'];
                        $result['advertisement_layout2'][$i]['videoURL'] = $videoUrl;
                        if($type=='embeded'){
                        $result['advertisement_layout2'][$i]['videoThumbURL'] = base_url().$g['embed_thumb'];
                        $result['advertisement_layout2'][$i]['embededUrl'] = $match[1]; 
                        }
                        $result['advertisement_layout2'][$i]['timeInSeconds'] = $g['display_time'];                        
                        
                    }
                    if($g['layout_type'] == "3"){
                        $result['advertisement_layout3'][$i]['advertisementId'] = $g['id'];
                        $result['advertisement_layout3'][$i]['type'] = $type;
                        $result['advertisement_layout3'][$i]['bannerURL'] = $bannUrl;
                        $result['advertisement_layout3'][$i]['portraitURL'] = $portraitUrl;
                        $result['advertisement_layout3'][$i]['landscapeURL'] = $landscapeUrl;
                        $result['advertisement_layout3'][$i]['linkToOpen'] = $g['link'];
                        $result['advertisement_layout3'][$i]['videoURL'] = $videoUrl;
                        if($type=='embeded'){
                        $result['advertisement_layout3'][$i]['videoThumbURL'] = base_url().$g['embed_thumb'];
                        $result['advertisement_layout3'][$i]['embededUrl'] = $match[1]; 
                        }
                        $result['advertisement_layout3'][$i]['timeInSeconds'] = $g['display_time'];                        
                        
                    }
                    if($g['layout_type'] == "4"){
                        $result['advertisement_layout4'][$i]['advertisementId'] = $g['id'];
                        $result['advertisement_layout4'][$i]['type'] = $type;
                        $result['advertisement_layout4'][$i]['bannerURL'] = $bannUrl;
                        $result['advertisement_layout4'][$i]['portraitURL'] = $portraitUrl;
                        $result['advertisement_layout4'][$i]['landscapeURL'] = $landscapeUrl;
                        $result['advertisement_layout4'][$i]['linkToOpen'] = $g['link'];
                        $result['advertisement_layout4'][$i]['videoURL'] = $videoUrl;
                        if($type=='embeded'){
                        $result['advertisement_layout4'][$i]['videoThumbURL'] = base_url().$g['embed_thumb'];
                        $result['advertisement_layout4'][$i]['embededUrl'] = $match[1]; 
                        }
                        $result['advertisement_layout4'][$i]['timeInSeconds'] = $g['display_time'];                        
                        
                    }
                    }
                $i++;}
                //$result1[] = $result;
                if($json['platform'] == "mobile" || $json['platform'] == "web" || $json['platform'] == "tablet"){
                foreach ($result['advertisement_layout1'] as $res){
                $data['advertisement_layout1'][]=$res;
                }
                }
                if($json['platform'] != "mobile"){
                foreach ($result['advertisement_layout2'] as $res){
                $data['advertisement_layout2'][]=$res;
                }
                foreach ($result['advertisement_layout3'] as $res){
                $data['advertisement_layout3'][]=$res;
                }
                foreach ($result['advertisement_layout4'] as $res){
                $data['advertisement_layout4'][]=$res;
                }
                }
                if($data == '' || $data == NULL){
                    $data = array();
                    echo json_encode(array('data' => $data, 'message' => $this->lang->line("advertisement_open_webservice"), 'success' => false));
                exit;
                }
                echo json_encode(array('data' => $data, 'message' => $this->lang->line("advertisement_open_webservice"), 'success' => true));
                exit;
                
            } else {
                echo json_encode(array('data' => array(), 'message' => $this->lang->line("input_data_webservice"), 'success' => false));
                exit;
            }
        } else {
            echo json_encode(array('data' => array(), 'message' => $this->lang->line("input_data_webservice"), 'success' => false));
            exit;
        } 
    }
    
    public function searchAdvertisementMagazine(){
       $authValidValue = $this->config->item("validValue");
        if ($authValidValue != '') {
//            $json = '{
//            "token":"1",
//            "platform":"tablet",
//            "magazineId":"1",
//            "appLanguage":"en"
//            }';

            $json = trim(file_get_contents('php://input'));
            //echo $json;die;
            if (!empty($json)) { 
                $json = json_decode($json, true);
                if($json['appLanguage'] == "en"){
                    $this->lang->load('en');
                }elseif($json['appLanguage'] == "pt"){
                    $this->lang->load('po');
                }elseif($json['appLanguage'] == "es"){
                    $this->lang->load('sp');
                }
                $advObj = $this->load->model("webservices/advertisement_model");
                $advObj = new advertisement_model();
                
                $advObj->set_magazine_id($json['magazineId']);
                $getAdvMagazine = $advObj->getAllAdvertiseMagazine();
                
                //print_r($getAdvMagazine);die;
                $i=0;
                //$result = array();
                foreach($getAdvMagazine as $key=>$g){
                    if($g['media_type'] == "1"){
                        $type = "standard";
                        $bannUrl = $this->config->base_url()."assets/Advertise/".$g['cover_image'].'demo.jpeg';
                        $portraitUrl = $this->config->base_url()."assets/Advertise/".$g['portrait_image'];
                        $landscapeUrl = $this->config->base_url()."assets/Advertise/".$g['landscape_image'];
                        $videoUrl = "";
                    }
                    if($g['media_type'] == "2"){
                        if($g['cover_image']=='embed'){
                        $type = "embeded";
                        $bannUrl = '';
                        $videoUrl = $g['embed_video'];
                        preg_match('/src="([^"]+)"/', $videoUrl, $match);
                        $explode= explode("/", $match[1]);
                        $videoThumb=end($explode);
                        $portraitUrl = "";
                        $landscapeUrl = "";
                        }else{
                        $type = "video";
                        $bannUrl = $this->config->base_url()."assets/Advertise/thumbs/".$g['cover_image'].'demo.jpeg';
                        $videoUrl = $this->config->base_url()."assets/Advertise/".$g['cover_image'];
                        $portraitUrl = "";
                        $landscapeUrl = "";
                        }
                    }
                    
                    
                    //print_r($g);die;
                    $magazineId=$g['id'];
                    if($g['layout_type'] == "1"){
                        
                        $result['advertisement_layout1'][$i]['advertisementId'] = $magazineId;
                        $result['advertisement_layout1'][$i]['type'] = $type;
                        $result['advertisement_layout1'][$i]['bannerURL'] = $bannUrl;
                        $result['advertisement_layout1'][$i]['portraitURL'] = $portraitUrl;
                        $result['advertisement_layout1'][$i]['landscapeURL'] = $landscapeUrl;
                        $result['advertisement_layout1'][$i]['linkToOpen'] = $g['link'];
                        $result['advertisement_layout1'][$i]['videoURL'] = $videoUrl;
                        if($type=='embeded'){
                        $result['advertisement_layout1'][$i]['videoThumbURL'] = base_url().$g['embed_thumb'];
                        $result['advertisement_layout1'][$i]['embededUrl'] = $match[1]; 
                        }
                        $result['advertisement_layout1'][$i]['timeInSeconds'] = $g['display_time'];                        
                        
                    }
                    if($g['layout_type'] == "2"){
                        
                        $result['advertisement_layout2'][$i]['advertisementId'] = $magazineId;
                        $result['advertisement_layout2'][$i]['type'] = $type;
                        $result['advertisement_layout2'][$i]['bannerURL'] = $bannUrl;
                        $result['advertisement_layout2'][$i]['portraitURL'] = $portraitUrl;
                        $result['advertisement_layout2'][$i]['landscapeURL'] = $landscapeUrl;
                        $result['advertisement_layout2'][$i]['linkToOpen'] = $g['link'];
                        $result['advertisement_layout2'][$i]['videoURL'] = $videoUrl;
                        if($type=='embeded'){
                        $result['advertisement_layout2'][$i]['videoThumbURL'] = base_url().$g['embed_thumb'];
                        $result['advertisement_layout2'][$i]['embededUrl'] = $match[1]; 
                        }
                        $result['advertisement_layout2'][$i]['timeInSeconds'] = $g['display_time'];                        
                        
                    }
                    if($g['layout_type'] == "3"){
                        
                        $result['advertisement_layout3'][$i]['advertisementId'] = $magazineId;
                        $result['advertisement_layout3'][$i]['type'] = $type;
                        $result['advertisement_layout3'][$i]['bannerURL'] = $bannUrl;
                        $result['advertisement_layout3'][$i]['portraitURL'] = $portraitUrl;
                        $result['advertisement_layout3'][$i]['landscapeURL'] = $landscapeUrl;
                        $result['advertisement_layout3'][$i]['linkToOpen'] = $g['link'];
                        $result['advertisement_layout3'][$i]['videoURL'] = $videoUrl;
                        if($type=='embeded'){
                        $result['advertisement_layout3'][$i]['videoThumbURL'] = base_url().$g['embed_thumb'];
                        $result['advertisement_layout3'][$i]['embededUrl'] = $match[1]; 
                        }
                        $result['advertisement_layout3'][$i]['timeInSeconds'] = $g['display_time'];                        
                        
                    }
                    if($g['layout_type'] == "4"){
                        
                        $result['advertisement_layout4'][$i]['advertisementId'] = $magazineId;
                        $result['advertisement_layout4'][$i]['type'] = $type;
                        $result['advertisement_layout4'][$i]['bannerURL'] = $bannUrl;
                        $result['advertisement_layout4'][$i]['portraitURL'] = $portraitUrl;
                        $result['advertisement_layout4'][$i]['landscapeURL'] = $landscapeUrl;
                        $result['advertisement_layout4'][$i]['linkToOpen'] = $g['link'];
                        $result['advertisement_layout4'][$i]['videoURL'] = $videoUrl;
                        if($type=='embeded'){
                        $result['advertisement_layout4'][$i]['videoThumbURL'] = base_url().$g['embed_thumb'];
                        $result['advertisement_layout4'][$i]['embededUrl'] = $match[1]; 
                        }
                        $result['advertisement_layout4'][$i]['timeInSeconds'] = $g['display_time'];                        
                        
                    }
                $i++;}
                //print_r($result);die;
                //$result1[] = $result;
                foreach ($result['advertisement_layout1'] as $res){
                $data['advertisement_layout1'][]=$res;
                }
                foreach ($result['advertisement_layout2'] as $res){
                $data['advertisement_layout2'][]=$res;
                }
                foreach ($result['advertisement_layout3'] as $res){
                $data['advertisement_layout3'][]=$res;
                }
                foreach ($result['advertisement_layout4'] as $res){
                $data['advertisement_layout4'][]=$res;
                }
                //print_r($data);die;
                if($data == '' || $data == NULL){
                    $data = array();
                    echo json_encode(array('data' => $data, 'message' => $this->lang->line("advertisement_magazine_webservice"), 'success' => false));
                exit;
                }
                echo json_encode(array('data' => $data, 'message' => $this->lang->line("advertisement_magazine_webservice"), 'success' => true));
                exit;
                
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
