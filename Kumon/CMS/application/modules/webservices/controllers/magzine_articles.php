<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * @author Techahead
 * 
 */

class Magzine_articles extends CI_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session', true);
        
    }
   
    public function magzineArticles(){
       $authValidValue = $this->config->item("validValue");
        if ($authValidValue != '') {
//            $json = '{
//            "token":"1",
//            "deviceType":"tablet",
//            "pageNumber":"1",
//            "magazineId":"1",
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
                $articleObj->set_app_lang_name($json['appLanguage']);
                $articleObj->set_language_id($lang);
                $verifyToken = $articleObj->verifyTokenValue();
                //print_r($verifyToken);
                if ($verifyToken == TRUE) {
                    
                  $articleObj->set_magazine_id($json['magazineId']);
                  $articleObj->set_device_type($json['deviceType']);
                  $articleObj->set_page_number($json['pageNumber']);

                    $groups = [];
                    $locations = [];
                    $disciplines = [];

                    $this->load->model("webservices/users_model");
                    $userModel = new users_model();

                    $userModel->set_token($json['token']);
                    $user = $userModel->getUserAccountDetails();

                    $this->load->model("web/group_model");
                    $model = new group_model();

                    $userGroups = $model->getUserGroups($json['token']);

                    if( $userGroups !== false ) {

                        foreach ($userGroups as $group) {
                            $groups[] = $group["id_group"];
                        }

                    }

                    $articleLocation = $model->getUserLocation($user["user_location_id"]);

                    if( $articleLocation !== false ){

                        foreach( $articleLocation as $location ){
                            $locations[] = $location["id"];
                        }

                    }

                    $articleDiscipline = $model->getUserDiscipline($json['token']);

                    if( $articleDiscipline !== false ){

                        foreach( $articleDiscipline as $discipline ){
                            $disciplines[] = $discipline["discipline_id"];
                        }

                    }

                    $groupsString = implode(",", $groups);
                    $locationsString = implode(",", $locations);
                    $disciplinesString = implode(",", $disciplines);

                  $getMagzineArticle = $articleObj->getMagzineArticle($groupsString, $locationsString, $disciplinesString, $user["branch"]);
                  
                  $getTotalPages = $articleObj->getMagzineArticleCount();
                  //print_r($getTotalPages);
                  if ($json['deviceType'] == "tablet" || $json['deviceType'] == "web") {
                        $onePage = "30";
                    } elseif ($json['deviceType'] == "mobile") {
                        $onePage = "36";
                    }
                  $row = round($getTotalPages['pageCount'] / $onePage);
                    $rowMod = ($getTotalPages['pageCount'] % $onePage);

                    if ($rowMod > 0) {
                        $row = $row + 1;
                    }
                    if ($row == "0") {
                        $row = "1";
                    }
                  $result = array();
                  $i=0;
                  $magazineCover=$articleObj->getMagazineCover();
                  $result['cover_image']=$this->config->base_url()."assets/Magazine_cover/".$magazineCover['magazine_images'];                  ;
                  $result['totalPages'] = $row;
                  foreach($getMagzineArticle as $g){
                      
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
                    }
                    if($g['article_link'] == ''){
                        $detailScreen = "N";
                    }
                    
                  if($g['embed_video']!=''){
                      
                  $result['magazineArticles'][$i]['embedded_thumbnail'] = base_url().$g['embed_video_thumb'];
                  preg_match('/src="([^"]+)"/', $g['embed_video'], $match);
                  $result['magazineArticles'][$i]['embedded_video_link'] = $match[1];
                  $result['magazineArticles'][$i]['embedded_video'] = $g['embed_video']; 
                      
                  }  else {
                  $result['magazineArticles'][$i]['embedded_thumbnail'] = '';
                  $result['magazineArticles'][$i]['embedded_video_link'] = '';
                  $result['magazineArticles'][$i]['embedded_video'] = '';  
                  }
                  
                  $result['magazineArticles'][$i]['articleId'] = $g['id'];
                  if($g['embed_video']!=''){
                  $result['magazineArticles'][$i]['articleType'] = 'embedded';
                  }else{
                  $result['magazineArticles'][$i]['articleType'] = $mediaType;
                  }
                  $result['magazineArticles'][$i]['title'] = $g['title'];
                  $result['magazineArticles'][$i]['createdDate'] = $g['publish_date'];
                  if($g['link_url']!='' && (strpos($URL, "http://") !== false) || (strpos($URL, "https://") !== false)){
                  $result['magazineArticles'][$i]['fullSoruce'] = $g['link_url'];
                  $link = str_replace(array('http://', 'https://', 'www.'), '', $g['link_url']);
                  $link_name = explode('.', $link);
                  $result['magazineArticles'][$i]['source'] = $link_name[0];
                  }else{
                  if($g['article_link']=='' || $g['article_link']=='0'){
                  $result['magazineArticles'][$i]['fullSoruce'] = '';
                  $result['magazineArticles'][$i]['source'] = '';
                  }else{
                  $result['magazineArticles'][$i]['fullSoruce'] = $g['article_link'];
                  $link = str_replace(array('http://', 'https://', 'www.'), '', $g['article_link']);
                  $link_name = explode('.', $link);
                  $result['magazineArticles'][$i]['source'] = $link_name[0];
                  }
                  }
                  
                  $result['magazineArticles'][$i]['articleDescPlain'] = $g['description_without_html'];
                  $result['magazineArticles'][$i]['articleDescHTML'] = $g['description']?base64_encode($g['description']):"";
                  
                  $epVideo = explode(":",$g['image']);
                if (($epVideo[0] == "http" || $epVideo[0] == "https")) {
                    $result['magazineArticles'][$i]['coverPhotoUrl'] = $g['image'];                  
                }else{
                  $result['magazineArticles'][$i]['coverPhotoUrl'] = $this->config->base_url()."assets/Article/".$g['image'];                  
                  }
                  $result['magazineArticles'][$i]['landingScreenLogo'] = $this->config->base_url()."assets/Magazine_cover/".$g['magazine_images'];                  
                  $epVideo = explode(":",$g['video_path']);
                if (($epVideo[0] == "http" || $epVideo[0] == "https")) {
                  $video=$g['video_path']; 
                }else{
                  $video=$this->config->base_url() . "assets/Article/" .$g['video_path'];   
                }
                
                $result['magazineArticles'][$i]['articleVideoUrl'] = $video;
                if(substr($video, -4) == '.pdf' || substr($result['magazineArticles'][$i]['fullSoruce'], -4) == '.pdf')
                {
                    if(substr($video, -4) == '.pdf')
                    {
                        $link_repl = str_replace(array('http://', 'https://', 'www.'), '', $video);
                        $link_name = explode('.', $link_repl);
                        if($link_name[0] == "docs")
                        {
                            $result['magazineArticles'][$i]['source'] = $link_name[0];
                            $result['magazineArticles'][$i]['fullSoruce'] = $video;  
                        }
                        else
                        {
                            $video = "https://docs.google.com/viewer?url=".trim($video);
                            $link1 = str_replace(array('http://', 'https://', 'www.'), '', $video);
                            $link_name = explode('.', $link1);
                            $result['magazineArticles'][$i]['source'] = $link_name[0];
                            $result['magazineArticles'][$i]['fullSoruce'] = $video;
                        }
                        $result['magazineArticles'][$i]['articleVideoUrl'] = $this->config->base_url() . "assets/Article/";
                        $result['magazineArticles'][$i]['articleType'] = "photo";
                    }
                    if(substr($result['magazineArticles'][$i]['fullSoruce'], -4) == '.pdf')
                    {
                        $flsrcval = $result['magazineArticles'][$i]['fullSoruce'];
                        $link_repl = str_replace(array('http://', 'https://', 'www.'), '', $flsrcval);
                        $link_name = explode('.', $link_repl);
                        if($link_name[0] == "docs")
                        {
                            $result['magazineArticles'][$i]['source'] = $link_name[0];
                            $result['magazineArticles'][$i]['fullSoruce'] = $flsrcval;  
                        }
                        else
                        {
                            $flsrcval2 = "https://docs.google.com/viewer?url=".trim($flsrcval);
                            $link2 = str_replace(array('http://', 'https://', 'www.'), '', $flsrcval2);
                            $link_name = explode('.', $link2);
                            $result['magazineArticles'][$i]['source'] = $link_name[0];
                            $result['magazineArticles'][$i]['fullSoruce'] = $flsrcval2;
                        }
                    }
                }
                
                $desstr = $g['description'];
                $dom = new DomDocument();
                $dom->loadHTML($desstr);
                $outputhref = array();
                foreach ($dom->getElementsByTagName('a') as $item) {
                   $outputhref[] = array (
                      'href' => $item->getAttribute('href'),
                   );
                }
                if(substr($outputhref[0]['href'],-4) == ".pdf")
                {
                    $flsrcval = $outputhref[0]['href'];
                        $link_repl = str_replace(array('http://', 'https://', 'www.'), '', $flsrcval);
                        $link_name = explode('.', $link_repl);
                        if($link_name[0] == "docs")
                        {
                            $result['magazineArticles'][$i]['source'] = $link_name[0];
                            $result['magazineArticles'][$i]['fullSoruce'] = $flsrcval;  
                        }
                        else
                        {
                            $flsrcval2 = "https://docs.google.com/viewer?url=".trim($flsrcval);
                            $link2 = str_replace(array('http://', 'https://', 'www.'), '', $flsrcval2);
                            $link_name = explode('.', $link2);
                            $result['magazineArticles'][$i]['source'] = $link_name[0];
                            $result['magazineArticles'][$i]['fullSoruce'] = $flsrcval2;
                        }
                }
                  
                  if($g['article_link']!='' && $g['created_by']!='0'){
                  $result['magazineArticles'][$i]['allowShare'] = "N";
                  $result['magazineArticles'][$i]['allowComment'] = "N";
                  }else{
                  $result['magazineArticles'][$i]['allowShare'] = $allowShare;
                  $result['magazineArticles'][$i]['allowComment'] = $allowComment;
                  }
                  $result['magazineArticles'][$i]['commentsCount'] = $getCommentCount['commentCount'];
                  $result['magazineArticles'][$i]['detailScreen'] = $detailScreen;
                  $result['magazineArticles'][$i]['createdBy'] = $g['created_by'];
                  $result['magazineArticles'][$i]['createdSource'] = $g['source'];
                  $i++;}
                  if($result['magazineArticles'] != ''){
                  $result1[] = $result;
                  echo json_encode(array('data' => $result1, 'message' => $this->lang->line("magazine_article_webservice"), 'success' => true));
                  }else{
                      $result1 = array();
                  echo json_encode(array('data' => $result1, 'message' => $this->lang->line("magazine_article_webservice_blank"), 'success' => true));
                  }
                  
                  //echo "<pre>";print_r($result1);die;
//                  echo json_encode(array('data' => $result1, 'message' => $this->lang->line("magazine_article_webservice"), 'success' => true));
//                  exit;
                  
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
    
    public function getMagzines(){
       $authValidValue = $this->config->item("validValue");
        if ($authValidValue != '') {
//            $json = '{
//            "token":"1",            
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
                $magzineObj = $this->load->model("webservices/magzine_model");
                $magzineObj = new magzine_model();
                
                $magzineObj->set_token($json['token']);
                
//                $verifyToken = $magzineObj->verifyTokenValue();
//                if ($verifyToken == TRUE) {
                  $getUserMagzines = $magzineObj->getUserMagzines();
                  if($getUserMagzines != ''){
                  $i=0;
                  $result = array();
                  foreach($getUserMagzines as $g){
                      $result['magazines'][$i]['magazineId'] = $g['id'];
                      $result['magazines'][$i]['coverPhotoUrl'] = $this->config->base_url()."assets/Magazine_cover/".$g['cover_image'];
                      $result['magazines'][$i]['mobileAppBarColorRGB'] = $g['bar_color'];
                      $result['magazines'][$i]['customerLogoUrl'] = $this->config->base_url()."assets/Magazine_cover/customer_logo/".$g['customer_logo'];
                  $i++;}
                  $result1[] = $result;
//                  }else{
//                    $result1 = array();
//                  }
                  
                  //echo "<pre>";print_r($result1);die;
                  echo json_encode(array('data' => $result1, 'message' => $this->lang->line("user_magazine_webservice"), 'success' => true));
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
    
    public function getSubscribeMagzine(){
        $authValidValue = $this->config->item("validValue");
        if ($authValidValue != '') {
//            $json = '{
//            "token":"1",
//            "magazineId":"1",
//            "subscriptionPassword":"123456",
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
                $magzineObj = $this->load->model("webservices/magzine_model");
                $magzineObj = new magzine_model();
                
                $magzineObj->set_token($json['token']);
                
                $verifyToken = $magzineObj->verifyTokenValue();
                if ($verifyToken == TRUE) {                    
                  //$magzineObj->set_magazine_id($json['magazineId']);
                  $magzineObj->set_password($json['subscriptionPassword']);
                  $getSubsMag = $magzineObj->getSubscribeMagzineData();
                  if($getSubsMag != FALSE){
                  $i=0;
                  $result = array();
                  $result['magazines'][$i]['magazineId'] = $getSubsMag['id'];
                  $result['magazines'][$i]['coverPhotoUrl'] = $this->config->base_url()."assets/Magazine_cover/".$getSubsMag['cover_image'];
                  $result['magazines'][$i]['mobileAppBarColorRGB'] = $getSubsMag['bar_color'];
                  $result['magazines'][$i]['customerLogoUrl'] = $this->config->base_url()."assets/Magazine_cover/customer_logo/".$getSubsMag['customer_logo'];
                  $i++;
                  $result1[] = $result;
                  //echo "<pre>";print_r($getSubsMag);die;
                  echo json_encode(array('data' => $result1, 'message' => $this->lang->line("subs_magazine_webservice"), 'success' => true));
                  exit;
                  }else{
                    $result1 = array();

                    $magazineId = $magzineObj->getMagazineByCode();

                    $magzineObj->set_id($magazineId);
                    $magzineObj->set_user_id($json['token']);
                    $userHasMagazine = $magzineObj->userHasMagazine();

                  //echo "<pre>";print_r($getSubsMag);die;
                  echo json_encode(array('data' => $result1, 'message' => $this->lang->line("check_subs_webservice"), 'success' => false,
                      'hasMagazine' => $userHasMagazine, 'magazineId' => $magazineId));
                  exit;  
                  }
                  
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

    public function getMagazineData(){

        $this->load->helper('general_helper');
        $this->lang->load('po');
        $this->load->model("webservices/magzine_model");

        $model = new magzine_model();

        $authValidValue = $this->config->item("validValue");

        if ($authValidValue != '') {

            $json = trim(file_get_contents('php://input'));

            if (!empty($json)) {

                $json = json_decode($json, true);

                $magazineId = $json["magazineId"];

                $model->set_id($magazineId);
                $magazineData = $model->getMagazineData();

                $i=0;
                $result = array();

                $result['magazines'][$i]['magazineId'] = $magazineData['id'];
                $result['magazines'][$i]['coverPhotoUrl'] = $this->config->base_url()."assets/Magazine_cover/".$magazineData['cover_image'];
                $result['magazines'][$i]['mobileAppBarColorRGB'] = $magazineData['bar_color'];
                $result['magazines'][$i]['customerLogoUrl'] = $this->config->base_url()."assets/Magazine_cover/customer_logo/".$magazineData['customer_logo'];

                $resultMagazine[] = $result;

            }

        } else {
            echo json_encode(array('data' => array(), 'success' => false));
            exit;
        }

        echo json_encode(array('data' => $resultMagazine, 'success' => true));

        exit;

    }
    
    public function magzinedelete(){
        $authValidValue = $this->config->item("validValue");
        if ($authValidValue != '') { 
            $json = trim(file_get_contents('php://input'));
            $json = json_decode($json, true);
            $magznObj = $this->load->model("webservices/magzine_model");
            $magznObj = new magzine_model();
            $result = $magznObj->magzinedelete($json['token'],$json['magazine_id']);
            if($result)
            {
                echo json_encode(array('data' => $result, 'message' => 'Deleted successfully', 'success' => true));
                exit;
            }
            else  
            {
                echo json_encode(array('data' => $result, 'message' => $this->lang->line("subs_magazine_webservice"), 'success' => false));
                exit;
            }
        }
    }

    public function magazineAccess(){

        $this->load->helper('general_helper');
        $this->lang->load('po');
        $this->load->model("webservices/magazine_access_model");
        $this->load->model("webservices/users_model");

        $magazineAccessModel = new magazine_access_model();
        $userObj = new users_model();

        $authValidValue = $this->config->item("validValue");

        if ($authValidValue != '') {

            $json = trim(file_get_contents('php://input'));

            if (!empty($json)) {

                $json = json_decode($json, true);

                $userToken = $json["token"];
                $userObj->set_token($userToken);
                $validValue = $userObj->verifyTokenValue();

//                if ( !$validValue ) {
//                    echo json_encode(array('data' => array(), 'message' => $this->lang->line("token_invalid_webservice"), 'success' => false));
//                    exit;
//                }

                $country = "";
                $state = "";
                $city = "";

                if( !empty( $json["latitude"] ) && !empty( $json["longitude"] ) ){

                    $latitude = $json["latitude"];
                    $longitude = $json["longitude"];

                    $dataLocation = getLocationByLatLng($latitude, $longitude);

                    $country = $dataLocation["country"];
                    $state = $dataLocation["state"];
                    $city = $dataLocation["city"];

                }

                $magazineAccessModel->setIdMagazine($json["id_magazine"]);
                $magazineAccessModel->setIdArticle($json["id_article"]);
                $magazineAccessModel->setDateAccess($json["date_access"]);
                $magazineAccessModel->setIdUser($json["token"]);
                $magazineAccessModel->setSoAccess($json["so_access"]);
                $magazineAccessModel->setTypeDeviceAccess($json["type_device_access"]);
                $magazineAccessModel->setCountry($country);
                $magazineAccessModel->setState($state);
                $magazineAccessModel->setCity($city);

                $inserted = $magazineAccessModel->insert();

            }

        } else {
            echo json_encode(array('data' => array(), 'message' => $this->lang->line("input_data_webservice"), 'success' => false));
            exit;
        }

        echo json_encode(array('data' => array(), 'message' => $this->lang->line("magazine_access_success"), 'success' => $inserted));

        exit;

    }

}

