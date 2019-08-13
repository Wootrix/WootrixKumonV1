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
                $verifyToken = $articleObj->verifyTokenValue();

                if ($verifyToken == TRUE) {

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
                        $result[$i]['openArticle'][$j]['articleDescPlain'] = preg_replace("/&#?[a-z0-9]+;/i", "", htmlspecialchars_decode($getArticle['description_without_html']));


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
                    //
                } else {
                    echo json_encode(array('data' => array(), 'message' => $this->lang->line("token_expire_webservice"), 'success' => false));
                    exit;
                }

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

                    $row = round(count($getOpenArticles) / $onePage);
                    $rowMod = (count($getOpenArticles) % $onePage);

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
                        } else {
                            $result['openArticles'][$i]['fullSoruce'] = $g['website_url'];
                            $link = str_replace(array('http://', 'https://', 'www.'), '', $g['website_url']);
                            $link_name = explode('.', $link);
                            $result['openArticles'][$i]['source'] = $link_name[0];
                        }
                        $result['openArticles'][$i]['articleDescPlain'] = preg_replace("/&#?[a-z0-9]+;/i", "", htmlspecialchars_decode($g['description_without_html']));
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
                        if ($g['via_url'] != '') {
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
                    //echo "<pre>";print_r($result1);die;
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

}
