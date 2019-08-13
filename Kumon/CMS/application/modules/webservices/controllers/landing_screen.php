<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * @author Techahead
 * 
 */

class Landing_screen extends CI_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session', true);
    }

    public function landingScreenData() {
        $authValidValue = $this->config->item("validValue");

        if ($authValidValue != '') {
//            $json = '{
//            "token":"1",
//            "articleLanguage":"pt",
//            "appLanguage":"en"
//            }';

            $json = trim(file_get_contents('php://input'));

            if (!empty($json)) {

                $json = json_decode($json, true);
                if ($json['appLanguage'] == "") {
                    $json['appLanguage'] = $json['applanguage'];
                }
                if ($json['appLanguage'] == "en") {
                    $this->lang->load('en');
                } elseif ($json['appLanguage'] == "pt") {
                    $this->lang->load('po');
                } elseif ($json['appLanguage'] == "es") {
                    $this->lang->load('sp');
                }
                $articleObj = $this->load->model("webservices/new_articles_model");
                $articleObj = new new_articles_model();

                $articleObj->set_token($json['token']);
                $articleObj->set_language_name($json['articleLanguage']);
                //echo $json['appLanguage'];die;
                $articleObj->set_app_lang_name($json['appLanguage']);

                $getLangId = $articleObj->getLanguageId();
                //echo "<pre>";print_r($getLangId);die;
                if ($articleObj->get_lang_param() == "2") {
                    foreach ($getLangId as $l) {
                        $lang[] = $l['id'];
                    }
                } else {
                    $lang = $getLangId['id'];
                }
                //echo "<pre>";print_r($lang);die;
                $articleObj->set_language_id($lang);
//                $verifyToken = $articleObj->verifyTokenValue();
//
//                if ($verifyToken == TRUE) {

                    //Store the DeviceID
                    $userObj = $this->load->model("webservices/users_model");
                    $userObj = new users_model();
                    $userObj->set_UserId($json['token']);
                    $userObj->set_DeviceId($json['deviceId']);
                    if ($json['device'] == "iPhone") {
                        $userObj->set_device_type("1");
                    } elseif ($json['device'] == "Android") {
                        $userObj->set_device_type("2");
                    } elseif ($json['device'] == "Website") {
                        $userObj->set_device_type("3");
                    }

                    $storeDeviceID = $userObj->storeUserDeviceID();

                    $getArticle = $articleObj->getNewArticle();

                    $getUserMagzines = $articleObj->getUserArticles();
                    $i = 0;
                    if ($getArticle != '') {
                        $result = array();

                        $j = 0;
                        if ($getArticle['media_type'] == '0') {
                            $mediaType = "photo";
                        }
                        if ($getArticle['media_type'] == '1') {
                            $mediaType = "video";
                        }

                        if ($getArticle['allow_share'] == '0') {
                            $allowShare = "N";
                        }
                        if ($getArticle['allow_share'] == '1') {
                            $allowShare = "Y";
                        }

                        if ($getArticle['allow_comment'] == '0') {
                            $allowComment = "N";
                        }
                        if ($getArticle['allow_comment'] == '1') {
                            $allowComment = "Y";
                        }
                        if ($getArticle['embed_video_thumb'] != '') {
                            $result[$i]['openArticle'][$i]['embedded_thumbnail'] = base_url() . $getArticle['embed_video_thumb'];
                        } else {
                            $result[$i]['openArticle'][$i]['embedded_thumbnail'] = '';
                        }
                        preg_match('/src="([^"]+)"/', $getArticle['embed_video'], $match);
                        $result[$i]['openArticle'][$i]['embedded_video_link'] = $match[1];
                        $result[$i]['openArticle'][$i]['embedded_video'] = $getArticle['embed_video'];
                        $result[$i]['openArticle'][$j]['articleId'] = $getArticle['id'];
                        $result[$i]['openArticle'][$j]['articleType'] = $mediaType;
                        $result[$i]['openArticle'][$j]['title'] = $getArticle['title'];
                        $result[$i]['openArticle'][$j]['createdDate'] = $getArticle['publish_date'];
                        $result[$i]['openArticle'][$j]['fullSoruce'] = $getArticle['article_link'];
                        $link = str_replace(array('http://', 'https://', 'www.'), '', $getArticle['article_link']);
                        $link_name = explode('.', $link);
                        $result[$i]['openArticle'][$j]['source'] = $link_name[0];
                        if ($getArticle['created_by'] == '0') {
                            $result[$i]['openArticle'][$j]['articleDesc'] = "";
                        } elseif ($getArticle['created_by'] == '1') {
                            $result[$i]['openArticle'][$j]['articleDesc'] = base64_encode($getArticle['description']);
                        }
                        $result[$i]['openArticle'][$j]['articleDescPlain'] = $getArticle['description_without_html'];


                        $epImage = explode(":", $getArticle['image']);
                        if (($epImage[0] == "http" || $epImage[0] == "https")) {
                            $image = $getArticle['image'];
                        } else {
                            $image = $this->config->base_url() . "assets/Article/" . $getArticle['image'];
                        }



                        $epVideo = explode(":", $getArticle['video_path']);
                        if (($epVideo[0] == "http" || $epVideo[0] == "https")) {
                            $video = $getArticle['video_path'];
                        } else {
                            $video = $this->config->base_url() . "assets/Article/" . $getArticle['video_path'];
                            $videoThumbs = $this->config->base_url() . "assets/Article/thumbs/" . $getArticle['video_path'] . 'demo.jpeg';
                        }

                        if ($getArticle['media_type'] == '0') {
                            $result[$i]['openArticle'][$j]['coverPhotoUrl'] = $image;
                        } elseif ($getArticle['media_type'] == '1') {
                            $result[$i]['openArticle'][$j]['coverPhotoUrl'] = $videoThumbs;
                        }

                        //$result[$i]['openArticle'][$j]['coverPhotoUrl'] = $getArticle['image'];
                        $result[$i]['openArticle'][$j]['articleVideoUrl'] = $video;
                        $result[$i]['openArticle'][$j]['allowShare'] = $allowShare;
                        $result[$i]['openArticle'][$j]['allowComment'] = $allowComment;
                        $result[$i]['openArticle'][$j]['commentsCount'] = $getArticle['commentCount'];
                        $result[$i]['openArticle'][$j]['createdBy'] = $getArticle['created_by'];
                    } else {
                        $result[$i]['openArticle'] = array();
                    }

                    if ($getUserMagzines != '') {
                        $l = 0;
                        foreach ($getUserMagzines as $m) {
                            $result[$i]['magazines'][$l]['magazineId'] = $m['id'];
                            $result[$i]['magazines'][$l]['coverPhotoUrl'] = $this->config->base_url() . "assets/Magazine_cover/" . $m['cover_image'];
                            $result[$i]['magazines'][$l]['mobileAppBarColorRGB'] = $m['bar_color'];
                            $result[$i]['magazines'][$l]['customerLogoUrl'] = $this->config->base_url() . "assets/Magazine_cover/customer_logo/" . $m['customer_logo'];
                            $result[$i]['magazines'][$l]['magazineName'] = $m['title'];

                            $l++;
                        }
                    } else {
                        $result[$i]['magazines'] = array();
                    }
                    $i++;
                    $j++;


                    echo json_encode(array('data' => $result, 'message' => $this->lang->line("landing_screen_webservice"), 'success' => true));
                    exit;

//                } else {
//                    echo json_encode(array('data' => array(), 'message' => $this->lang->line("token_expire_webservice"), 'success' => false));
//                    exit;
//                }

                //echo "<pre>";print_r($result);die;
            } else {
                echo json_encode(array('data' => array(), 'message' => $this->lang->line("input_data_webservice"), 'success' => false));
                exit;
            }
        } else {
            echo json_encode(array('data' => array(), 'message' => $this->lang->line("input_data_webservice"), 'success' => false));
            exit;
        }
    }

    public function getWootrixTopics() {
        $authValidValue = $this->config->item("validValue");

        if ($authValidValue != '') {
//            $json = '{
//            "token":"219",
//            "articleLanguage":"en",
//            "appLanguage":"pt"
//            }';

            $json = trim(file_get_contents('php://input'));

            if (!empty($json)) {
                $json = json_decode($json, true);
                if ($json['appLanguage'] == "en") {
                    $this->lang->load('en');
                } elseif ($json['appLanguage'] == "pt") {
                    $this->lang->load('po');
                } elseif ($json['appLanguage'] == "es") {
                    $this->lang->load('sp');
                }
                $categoryObj = $this->load->model("webservices/category_model");
                $categoryObj = new category_model();

                $categoryObj->set_token($json['token']);
                $verifyToken = $categoryObj->verifyTokenValue();
//                if ($verifyToken == TRUE) {

                $categoryObj->set_language_name($json['appLanguage']);
                $getTopics = $categoryObj->getTopics();
                foreach ($getTopics as $a) {
                    $topics[] = $a;
                }
                $i = 0;

                $result = array();
                if ($topics != '') {
                    foreach ($topics as $g) {
                        $result['topics'][$i]['topicId'] = $g['id'];
                        $result['topics'][$i]['topicTitle'] = preg_replace("/&#?[a-z0-9]+;/i", "", htmlspecialchars_decode($g['category_name']));
                        $i++;
                    }
                    $result1[] = $result;
                } else {
                    $result1 = array();
                }
                //echo "<pre>";print_r($result1);die;
                echo json_encode(array('data' => $result1, 'message' => $this->lang->line("topics_webservice"), 'success' => true));
                exit;
//                } else {
//                    echo json_encode(array('data' => array(), 'message' => $this->lang->line("token_expire_webservice"), 'success' => false));
//                    exit;
//                }
                //echo "<pre>";print_r($result);die;
            } else {
                echo json_encode(array('data' => array(), 'message' => $this->lang->line("input_data_webservice"), 'success' => false));
                exit;
            }
        } else {
            echo json_encode(array('data' => array(), 'message' => $this->lang->line("input_data_webservice"), 'success' => false));
            exit;
        }
    }

    public function getOpenArticles() {
        $authValidValue = $this->config->item("validValue");
        if ($authValidValue != '') {
//            $json = '{
//            "token":"1",
//            "deviceType":"tablet",
//            "pageNumber":"1",
//            "topics":"1,6,7",
//            "articleLanguage":"en",
//            "appLanguage":"en"
//            }';

            $json = trim(file_get_contents('php://input'));

            if (!empty($json)) {
                $json = json_decode($json, true);
                if ($json['appLanguage'] == "en") {
                    $this->lang->load('en');
                } elseif ($json['appLanguage'] == "pt") {
                    $this->lang->load('po');
                } elseif ($json['appLanguage'] == "es") {
                    $this->lang->load('sp');
                }
                $articleObj = $this->load->model("webservices/new_articles_model");
                $articleObj = new new_articles_model();

                $articleObj->set_token($json['token']);
                $articleObj->set_language_name($json['articleLanguage']);
                $articleObj->set_app_lang_name($json['appLanguage']);
                $getLangId = $articleObj->getLanguageId();
                if ($articleObj->get_lang_param() == "2") {
                    foreach ($getLangId as $l) {
                        $lang[] = $l['id'];
                    }
                } else {
                    $lang = $getLangId['id'];
                }
                $articleObj->set_language_id($lang);
                $verifyToken = $articleObj->verifyTokenValue();

                if ($verifyToken == TRUE) {
                    if ($json['topics'] != '') {
                        $topicId = explode(",", $json['topics']);
                        $articleObj->set_category_id($json['topics']);
                    } else {
                        $articleObj->set_category_id("0");
                    }
                    $articleObj->set_device_type($json['deviceType']);
                    $articleObj->set_page_number($json['pageNumber']);
                    $getOpenArticles = $articleObj->getOpenArticles();
                    //print_r($getOpenArticles);die;
                    if ($json['deviceType'] == "tablet" || $json['deviceType'] == "web") {
                        $onePage = "30";
                    } elseif ($json['deviceType'] == "mobile") {
                        $onePage = "36";
                    }
                    $getOpenCount= $articleObj->getOpenArticlesCount();
                    //print_r($getOpenCount);die;
                    $row = round($getOpenCount['pageCount'] / $onePage);
                    $rowMod = ($getOpenCount['pageCount'] % $onePage);

                    if ($rowMod > 0) {
                        $row = $row + 1;
                    }
                    if ($row == "0") {
                        $row = "1";
                    }

                    $getTotalPages = $row; 
                    $result = array();
                    $i = 0;
                    $result['totalPages'] = $getTotalPages;
                    foreach ($getOpenArticles as $g) {
                        
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
                        } else {
                            $detailScreen = "N";
                        }


                        if ($g['embed_video'] != '') {

                            $result['openArticles'][$i]['embedded_thumbnail'] = base_url() . $g['embed_video_thumb'];
                            preg_match('/src="([^"]+)"/', $g['embed_video'], $match);
                            $result['openArticles'][$i]['embedded_video_link'] = $match[1];
                            $result['openArticles'][$i]['embedded_video'] = $g['embed_video'];
                        } else {
                            $result['openArticles'][$i]['embedded_thumbnail'] = '';
                            $result['openArticles'][$i]['embedded_video_link'] = '';
                            $result['openArticles'][$i]['embedded_video'] = '';
                        }
                        $result['openArticles'][$i]['articleId'] = $g['id'];
                        if ($g['embed_video'] != '') {
                            $result['openArticles'][$i]['articleType'] = 'embedded';
                        } else {
                            $result['openArticles'][$i]['articleType'] = $mediaType;
                        }
                        $result['openArticles'][$i]['title'] = $g['title'];
                        $result['openArticles'][$i]['createdDate'] = $g['publish_date'];
                        if ($g['article_link'] != '' && $g['article_link'] != '0') {
                            $result['openArticles'][$i]['fullSoruce'] = $g['article_link'];
                            $link = str_replace(array('http://', 'https://', 'www.'), '', $g['article_link']);
                            $link_name = explode('.', $link);
                            $result['openArticles'][$i]['source'] = $link_name[0];
                        }else{
                            $result['openArticles'][$i]['fullSoruce'] = '';
                            
                            $result['openArticles'][$i]['source'] = '';
                        } 
//                        else {
//                            $result['openArticles'][$i]['fullSoruce'] = $g['website_url'];
//                            $link = str_replace(array('http://', 'https://', 'www.'), '', $g['website_url']);
//                            $link_name = explode('.', $link);
//                            $result['openArticles'][$i]['source'] = $link_name[0];
//                        }
                        $result['openArticles'][$i]['articleDescPlain'] = $g['description_without_html'];
                        if ($g['created_by'] == '0') {
                            $result['openArticles'][$i]['articleDescHTML'] = "";
                        } elseif ($g['created_by'] == '1') {
                            $result['openArticles'][$i]['articleDescHTML'] = base64_encode($g['description']);
                        }

                        $epImage = explode(":", $g['image']);
                        if (($epImage[0] == "http" || $epImage[0] == "https")) {
                            $image = $g['image'];
                        } else {
                            $image = $this->config->base_url() . "assets/Article/" . $g['image'];
                        }

                        $result['openArticles'][$i]['coverPhotoUrl'] = $image;

                        $epVideo = explode(":", $g['video_path']);
                        if (($epVideo[0] == "http" || $epVideo[0] == "https")) {
                            $video = $g['video_path'];
                        } else {
                            $video = $this->config->base_url() . "assets/Article/" . $g['video_path'];
                        }


                        $result['openArticles'][$i]['articleVideoUrl'] = $video;
                        if ($g['via_url'] != '' && $g['via_url'] != '0' && $g['created_by']!='0') {
                            $result['openArticles'][$i]['allowShare'] = "N";
                            $result['openArticles'][$i]['allowComment'] = "N";
                        } else {
                            $result['openArticles'][$i]['allowShare'] = $allowShare;
                            $result['openArticles'][$i]['allowComment'] = $allowComment;
                        }
                        $result['openArticles'][$i]['commentsCount'] = $getCommentCount['commentCount'];
                        $result['openArticles'][$i]['detailScreen'] = $detailScreen;
                        $result['openArticles'][$i]['createdBy'] = $g['created_by'];
                        $i++;
                    }
                    if ($result['openArticles'] != '') {
                        $result1[] = $result;
                    } else {
                        $result1 = array();
                    }

                    echo json_encode(array('data' => $result1, 'message' => $this->lang->line("open_articles_webservice"), 'success' => true));
                    exit;
                } else {
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

    public function getInternalArticleDetail() {

        $this->load->helper('general_helper');
        $this->lang->load('po');
        $this->load->model("web/article_model");
        $this->load->model("webservices/users_model");

        $articleModel = new article_model();

        $magazineId = $_POST["magazineId"];
        $articleId = $_POST["articleId"];

        $articleModel->set_id($articleId);
        $articleModel->set_magazine_id($magazineId);

        if( !empty( $magazineId ) ){
            $result = $articleModel->getMagazineArticle();
            $resultUpdated = $this->updateResultMagazineArticle($result[0]);
            $resultUpdated["openArticles"]["magazineId"] = $magazineId;
        } else {
            $result = $articleModel->getArticle();
            $resultUpdated = $this->updateResult($result[0]);
        }

//                echo '<pre>';
//                print_r($result);
//                echo "</pre>";
//                exit;

        echo json_encode(array('data' => $resultUpdated, 'success' => true));

        exit;

    }

    public function getArticleDetail() {

        $this->load->helper('general_helper');
        $this->lang->load('po');
        $this->load->model("web/article_model");
        $this->load->model("webservices/users_model");

        $articleModel = new article_model();

        $authValidValue = $this->config->item("validValue");

        if ($authValidValue != '') {

            $json = trim(file_get_contents('php://input'));

            if (!empty($json)) {

                $json = json_decode($json, true);

                $magazineId = $json["magazineId"];
                $articleId = $json["articleId"];

                $articleModel->set_id($articleId);
                $articleModel->set_magazine_id($magazineId);

                $result = $articleModel->getArticle();

                if( count( $result ) > 0 ){
                    $result = $articleModel->getArticle();
                    $resultUpdated = $this->updateResult($result[0]);
                } else {
                    $result = $articleModel->getMagazineArticle();
                    $resultUpdated = $this->updateResultMagazineArticle($result[0]);
                    $resultUpdated["openArticles"]["magazineId"] = $magazineId;
                }

//                echo '<pre>';
//                print_r($result);
//                echo "</pre>";
//                exit;

            }

        } else {
            echo json_encode(array('data' => array(), 'success' => false));
            exit;
        }

        echo json_encode(array('data' => $resultUpdated, 'success' => true));

        exit;

    }

    private function updateResult( $result ){

        $resultUpdated = array();

        $this->load->model("webservices/new_articles_model");

        $articleObj = new new_articles_model();

        $articleObj->set_id($result['id']);

        if ($result['media_type'] == '0') {
            $mediaType = "photo";
        }
        if ($result['media_type'] == '1') {
            $mediaType = "video";
        }
        if ($result['allow_share'] == '0') {
            $allowShare = "N";
        }
        if ($result['allow_share'] == '1') {
            $allowShare = "Y";
        }

        if ($result['allow_comment'] == '0') {
            $allowComment = "N";
        }
        if ($result['allow_comment'] == '1') {
            $allowComment = "Y";
        }
        if ($result['article_link'] != '') {
            $detailScreen = "Y";
        } else {
            $detailScreen = "N";
        }

        if ($result['embed_video'] != '') {
            $resultUpdated['openArticles']['embedded_thumbnail'] = base_url() . $result['embed_video_thumb'];
            preg_match('/src="([^"]+)"/', $result['embed_video'], $match);
            $resultUpdated['openArticles']['embedded_video_link'] = $match[1];
            $resultUpdated['openArticles']['embedded_video'] = $result['embed_video'];
        } else {
            $resultUpdated['openArticles']['embedded_thumbnail'] = '';
            $resultUpdated['openArticles']['embedded_video_link'] = '';
            $resultUpdated['openArticles']['embedded_video'] = '';
        }

        $resultUpdated['openArticles']['articleId'] = $result['id'];

        if ($result['embed_video'] != '') {
            $resultUpdated['openArticles']['articleType'] = 'embedded';
        } else {
            $resultUpdated['openArticles']['articleType'] = $mediaType;
        }

        $resultUpdated['openArticles']['title'] = $result['title'];
        $resultUpdated['openArticles']['createdDate'] = $result['publish_date'];

        if ($result['article_link'] != '' && $result['article_link'] != '0') {
            $resultUpdated['openArticles']['fullSoruce'] = $result['article_link'];
            $link = str_replace(array('http://', 'https://', 'www.'), '', $result['article_link']);
            $link_name = explode('.', $link);
            $resultUpdated['openArticles']['source'] = $link_name[0];
        }else{
            $resultUpdated['openArticles']['fullSoruce'] = '';
            $resultUpdated['openArticles']['source'] = '';
        }

        $resultUpdated['openArticles']['articleDescPlain'] = $result['description_without_html'];

        if ($result['created_by'] == '0') {
            $resultUpdated['openArticles']['articleDescHTML'] = "";
        } elseif ($result['created_by'] == '1') {
            $resultUpdated['openArticles']['articleDescHTML'] = base64_encode($result['description']);
        }

        $epImage = explode(":", $result['image']);

        if (($epImage[0] == "http" || $epImage[0] == "https")) {
            $image = $result['image'];
        } else {
            $image = $this->config->base_url() . "assets/Article/" . $result['image'];
        }

        $resultUpdated['openArticles']['coverPhotoUrl'] = $image;

        $epVideo = explode(":", $result['video_path']);

        if (($epVideo[0] == "http" || $epVideo[0] == "https")) {
            $video = $result['video_path'];
        } else {
            $video = $this->config->base_url() . "assets/Article/" . $result['video_path'];
        }

        $result['openArticles']['articleVideoUrl'] = $video;

        if ($result['via_url'] != '' && $result['via_url'] != '0' && $result['created_by']!='0') {
            $resultUpdated['openArticles']['allowShare'] = "N";
            $resultUpdated['openArticles']['allowComment'] = "N";
        } else {
            $resultUpdated['openArticles']['allowShare'] = $allowShare;
            $resultUpdated['openArticles']['allowComment'] = $allowComment;
        }

        $resultUpdated['openArticles']['detailScreen'] = $detailScreen;
        $resultUpdated['openArticles']['createdBy'] = $result['created_by'];
        $resultUpdated['openArticles']['magazineId'] = $result['magazine_id'];

        return $resultUpdated;

    }

    private function updateResultMagazineArticle($g){

        $this->load->model("webservices/new_articles_model");
        $articleObj = new new_articles_model();

        $articleObj->set_id($g['id']);

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
        if ($g['article_link'] == '') {
            $detailScreen = "N";
        }

        if ($g['embed_video'] != '') {

            $result['openArticles']['embedded_thumbnail'] = base_url() . $g['embed_video_thumb'];
            preg_match('/src="([^"]+)"/', $g['embed_video'], $match);
            $result['openArticles']['embedded_video_link'] = $match[1];
            $result['openArticles']['embedded_video'] = $g['embed_video'];

        } else {
            $result['openArticles']['embedded_thumbnail'] = '';
            $result['openArticles']['embedded_video_link'] = '';
            $result['openArticles']['embedded_video'] = '';
        }

        $result['openArticles']['articleId'] = $g['id'];
        if ($g['embed_video'] != '') {
            $result['openArticles']['articleType'] = 'embedded';
        } else {
            $result['openArticles']['articleType'] = $mediaType;
        }
        $result['openArticles']['title'] = $g['title'];
        $result['openArticles']['createdDate'] = $g['publish_date'];
        if ($g['link_url'] != '' && (strpos($URL, "http://") !== false) || (strpos($URL, "https://") !== false)) {
            $result['openArticles']['fullSoruce'] = $g['link_url'];
            $link = str_replace(array('http://', 'https://', 'www.'), '', $g['link_url']);
            $link_name = explode('.', $link);
            $result['openArticles']['source'] = $link_name[0];
        } else {
            if ($g['article_link'] == '' || $g['article_link'] == '0') {
                $result['openArticles']['fullSoruce'] = '';
                $result['openArticles']['source'] = '';
            } else {
                $result['openArticles']['fullSoruce'] = $g['article_link'];
                $link = str_replace(array('http://', 'https://', 'www.'), '', $g['article_link']);
                $link_name = explode('.', $link);
                $result['openArticles']['source'] = $link_name[0];
            }
        }

        $result['openArticles']['articleDescPlain'] = $g['description_without_html'];
        $result['openArticles']['articleDescHTML'] = $g['description'] ? base64_encode($g['description']) : "";

        $epVideo = explode(":", $g['image']);
        if (($epVideo[0] == "http" || $epVideo[0] == "https")) {
            $result['openArticles']['coverPhotoUrl'] = $g['image'];
        } else {
            $result['openArticles']['coverPhotoUrl'] = $this->config->base_url() . "assets/Article/" . $g['image'];
        }
        $result['openArticles']['landingScreenLogo'] = $this->config->base_url() . "assets/Magazine_cover/" . $g['magazine_images'];
        $epVideo = explode(":", $g['video_path']);
        if (($epVideo[0] == "http" || $epVideo[0] == "https")) {
            $video = $g['video_path'];
        } else {
            $video = $this->config->base_url() . "assets/Article/" . $g['video_path'];
        }

        $result['openArticles']['articleVideoUrl'] = $video;
        if (substr($video, -4) == '.pdf' || substr($result['openArticles']['fullSoruce'], -4) == '.pdf') {
            if (substr($video, -4) == '.pdf') {
                $link_repl = str_replace(array('http://', 'https://', 'www.'), '', $video);
                $link_name = explode('.', $link_repl);
                if ($link_name[0] == "docs") {
                    $result['openArticles']['source'] = $link_name[0];
                    $result['openArticles']['fullSoruce'] = $video;
                } else {
                    $video = "https://docs.google.com/viewer?url=" . trim($video);
                    $link1 = str_replace(array('http://', 'https://', 'www.'), '', $video);
                    $link_name = explode('.', $link1);
                    $result['openArticles']['source'] = $link_name[0];
                    $result['openArticles']['fullSoruce'] = $video;
                }
                $result['openArticles']['articleVideoUrl'] = $this->config->base_url() . "assets/Article/";
                $result['openArticles']['articleType'] = "photo";
            }
            if (substr($result['openArticles']['fullSoruce'], -4) == '.pdf') {
                $flsrcval = $result['openArticles']['fullSoruce'];
                $link_repl = str_replace(array('http://', 'https://', 'www.'), '', $flsrcval);
                $link_name = explode('.', $link_repl);
                if ($link_name[0] == "docs") {
                    $result['openArticles']['source'] = $link_name[0];
                    $result['openArticles']['fullSoruce'] = $flsrcval;
                } else {
                    $flsrcval2 = "https://docs.google.com/viewer?url=" . trim($flsrcval);
                    $link2 = str_replace(array('http://', 'https://', 'www.'), '', $flsrcval2);
                    $link_name = explode('.', $link2);
                    $result['openArticles']['source'] = $link_name[0];
                    $result['openArticles']['fullSoruce'] = $flsrcval2;
                }
            }
        }

        $desstr = $g['description'];
        $dom = new DomDocument();
        $dom->loadHTML($desstr);
        $outputhref = array();
        foreach ($dom->getElementsByTagName('a') as $item) {
            $outputhref[] = array(
                'href' => $item->getAttribute('href'),
            );
        }
        if (substr($outputhref[0]['href'], -4) == ".pdf") {
            $flsrcval = $outputhref[0]['href'];
            $link_repl = str_replace(array('http://', 'https://', 'www.'), '', $flsrcval);
            $link_name = explode('.', $link_repl);
            if ($link_name[0] == "docs") {
                $result['openArticles']['source'] = $link_name[0];
                $result['openArticles']['fullSoruce'] = $flsrcval;
            } else {
                $flsrcval2 = "https://docs.google.com/viewer?url=" . trim($flsrcval);
                $link2 = str_replace(array('http://', 'https://', 'www.'), '', $flsrcval2);
                $link_name = explode('.', $link2);
                $result['openArticles']['source'] = $link_name[0];
                $result['openArticles']['fullSoruce'] = $flsrcval2;
            }
        }

        if ($g['article_link'] != '' && $g['created_by'] != '0') {
            $result['openArticles']['allowShare'] = "N";
            $result['openArticles']['allowComment'] = "N";
        } else {
            $result['openArticles']['allowShare'] = $allowShare;
        }
        $result['openArticles']['detailScreen'] = $detailScreen;
        $result['openArticles']['createdBy'] = $g['created_by'];
        $result['openArticles']['createdSource'] = $g['source'];

        return $result;

    }

    public function getAdvDetail() {

        $this->load->helper('general_helper');
        $this->lang->load('po');
        $this->load->model("web/advertise_model");

        $model = new advertise_model();

        $authValidValue = $this->config->item("validValue");

        if ($authValidValue != '') {

            $json = trim(file_get_contents('php://input'));

            if (!empty($json)) {

                $json = json_decode($json, true);

                $adId = $json["adId"];

                $model->set_id($adId);
                $ad = $model->getAdvDetail();

                if( $ad->media_type == 2 ){
                    $videoUrl = $ad->embed_video;
                    preg_match('/src="([^"]+)"/', $videoUrl, $match);
                    $explode= explode("/", $match[1]);
                    $videoThumb=end($explode);
                    $ad->link = $match[1];
                }

            }

        } else {
            echo json_encode(array('data' => array(), 'success' => false));
            exit;
        }

        $success = true;

        if( empty($ad) ){
            $success = false;
        }

        echo json_encode(array('data' => $ad, 'success' => $success));

        exit;

    }

    public function getInternalAdvDetail() {

        $this->load->helper('general_helper');
        $this->lang->load('po');
        $this->load->model("web/advertise_model");

        $model = new advertise_model();

        $adId = $_POST["adId"];

        $model->set_id($adId);

        $result = $model->getAdvDetail();

        if( $result->media_type == 2 ){
            $videoUrl = $result->embed_video;
            preg_match('/src="([^"]+)"/', $videoUrl, $match);
            $explode= explode("/", $match[1]);
            $videoThumb=end($explode);
            $result->link = $match[1];
        }

//                echo '<pre>';
//                print_r($result);
//                echo "</pre>";
//                exit;

        echo json_encode(array('data' => $result, 'success' => true));

        exit;

    }

    public function getBranchDetails() {

        $this->load->helper('general_helper');
        $this->lang->load('po');
        $this->load->model("webservices/new_articles_model");
        $this->load->model("web/article_model");
        $this->load->model("website/magazine_articles_model");

        $authValidValue = $this->config->item("validValue");

        if ($authValidValue != '') {

            $json = trim(file_get_contents('php://input'));

            if (!empty($json)) {

                $json = json_decode($json, true);

                $articleId = $json["articleId"];
                $magazineId = $json["magazineId"];
                $article = "";
                $description = "Confira o conteúdo em nossa revista digital no App FBH.";
                $image = $this->config->base_url() . 'images/logo.png';

                $obj = new new_articles_model();
                $obj->set_id($articleId);

                if( !empty( $magazineId ) ){
                    $obj->set_magazine_id($magazineId);
                }

                $article = $obj->getNewArticleDetail();

                if( !empty( $article["description_without_html"] ) ){
                    $description = str_replace( array("\n","\r"), ' ', trim( substr( $article["description_without_html"], 0, 200 ) ) );
                }

                if( !empty( $article['image'] ) ){
                    $image = $this->config->base_url() . 'assets/Article/' . $article['image'];
                } else if( !empty( $article['embed_video_thumb'] ) ){
                    $image = $this->config->base_url() . $article['embed_video_thumb'];
                }

                $entities = array("&Agrave;","&Aacute;","&Acirc;","&Atilde;","&Auml;","&Aring;","&AElig;","&Ccedil;","&Egrave;","&Eacute;","&Ecirc;","&Euml;","&Igrave;","&Iacute;","&Icirc;","&Iuml;","&ETH;","&Ntilde;","&Ograve;","&Oacute;","&Ocirc;","&Otilde;","&Ouml;","&Oslash;","&Ugrave;","&Uacute;","&Ucirc;","&Uuml;","&Yacute;","&THORN;","&szlig;","&agrave;","&aacute;","&acirc;","&atilde;","&auml;","&aring;","&aelig;","&ccedil;","&egrave;","&eacute;","&ecirc;","&euml;","&igrave;","&iacute;","&icirc;","&iuml;","&eth;","&ntilde;","&ograve;","&oacute;","&ocirc;","&otilde;","&ouml;","&oslash;","&ugrave;","&uacute;","&ucirc;","&uuml;","&yacute;","&thorn;","&yuml;");
                $decode = array("À","Á","Â","Ã","Ä","Å","Æ","Ç","È","É","Ê","Ë","Ì","Í","Î","Ï","Ð","Ñ","Ò","Ó","Ô","Õ","Ö","Ø","Ù","Ú","Û","Ü","Ý","Þ","ß","à","á","â","ã","ä","å","æ","ç","è","é","ê","ë","ì","í","î","ï","ð","ñ","ò","ó","ô","õ","ö","ø","ù","ú","û","ü","ý","þ","ÿ");
                $finalDescription = str_replace($entities, $decode, $description);
//                $finalDescription = mb_convert_encoding($description, "ASCII", "UTF-8");
//                $finalDescription = mb_detect_encoding($description);

                if( !empty( $magazineId ) ){
                    $data = '{"$article_id": "' . $articleId . '", "$magazine_id": "' . $magazineId . '", "$marketing_title": "' . $article["title"] . '", "$og_title": "' . $article["title"] . '", "$og_image_url": "' . $image . '", "$og_description": "' . $finalDescription . '"}';
                } else {
                    $data = '{"$article_id": "' . $articleId . '", "$marketing_title": "' . $article["title"] . '", "$og_title": "' . $article["title"] . '", "$og_image_url": "' . $image . '", "$og_description": "' . $finalDescription . '"}';
                }

                if( empty( $article ) && !empty( $magazineId ) ){

                    $obj = new magazine_articles_model();
                    $obj->set_id($articleId);

                    $article = $obj->getMagazineArticleDetail();

                    if( !empty( $article["description_without_html"] ) ){
                        $description = str_replace( array("\n","\r"), ' ', trim( substr( $article["description_without_html"], 0, 200 ) ) );
                    }

                    if( !empty( $article['image'] ) ){
                        $image = $this->config->base_url() . 'assets/Article/' . $article['image'];
                    } else if( !empty( $article['embed_video_thumb'] ) ){
                        $image = $this->config->base_url() . $article['embed_video_thumb'];
                    }

                    $finalDescription = str_replace($entities, $decode, $description);
//                    $finalDescription = mb_detect_encoding($description);

                    $data = '{"$article_id": "' . $articleId . '", "$magazine_id": "' . $magazineId . '", "$marketing_title": "' . $article["title"] . '", "$og_title": "' . $article["title"] . '", "$og_image_url": "' . $image . '", "$og_description": "' . $finalDescription . '"}';

                }

//                echo $data; exit;

                if( empty( $article ) ){
                    echo json_encode(array('data' => array("message" => "Artigo nao encontrado"), 'success' => false));
                    exit;
                }

                if( empty( $article["branch_link"] ) ){

                    $cURL = curl_init();

                    curl_setopt($cURL, CURLOPT_URL,"https://api.branch.io/v1/url");
                    curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($cURL, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

                    $postData = array(
                        "branch_key" => "key_live_deEOxg2Pl9gSzwQRFckC2kclrta5xX9c",
                        "data" => $data,
                        "type" => 2
                    );

//                    print_r($postData); exit;

                    curl_setopt($cURL, CURLOPT_POST, true);
                    curl_setopt($cURL, CURLOPT_POSTFIELDS, json_encode($postData));

                    $result = curl_exec($cURL);
                    curl_close($cURL);

                    $returnArray = json_decode($result);

                    if( !empty( $returnArray ) && isset( $returnArray->url ) ){

                        $dataReturn = array(
                            "branchLink" => $returnArray->url
                        );

                        $obj->updateBranchLink( $returnArray->url );

                    } else {
                        echo json_encode(array('data' => array(), 'success' => false));
                        exit;
                    }

                } else {

                    $dataReturn = array(
                        "branchLink" => $article["branch_link"]
                    );

                }

            }

        } else {
            echo json_encode(array('data' => array(), 'success' => false));
            exit;
        }

        echo json_encode(array('data' => $dataReturn, 'success' => true));

        exit;

    }

    public function getUserHasMagazine() {

        $this->load->helper('general_helper');
        $this->lang->load('po');
        $this->load->model("webservices/magzine_model");

        $authValidValue = $this->config->item("validValue");

        $arrayReturn = array();

        if ($authValidValue != '') {

            $json = trim(file_get_contents('php://input'));

            if (!empty($json)) {

                $json = json_decode($json, true);

                $userId = $json["userId"];
                $magazineId = $json["magazineId"];

                $obj = new Magzine_model();
                $obj->set_user_id($userId);
                $obj->set_id($magazineId);

                $userHasMagazine = $obj->userHasMagazine();
                $arrayReturn["userHasMagazine"] = $userHasMagazine;

            }

        } else {
            echo json_encode(array('data' => array(), 'success' => false));
            exit;
        }

        echo json_encode(array('data' => $arrayReturn, 'success' => true));
        exit;

    }

}
