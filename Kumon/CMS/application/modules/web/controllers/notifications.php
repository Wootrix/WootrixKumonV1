<?php

//error_reporting(E_ALL ^ E_NOTICE);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Notifications extends CI_Controller
{

    public function __construct()
    {

        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('language');
        $this->load->library('session', true);
        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->helper('cookie');
        //$this->lang->load('en', 'english');
        $languge = $this->session->userdata('language');
        if ($languge == "") {
            $this->lang->load('en', 'english');
        } else {
            $this->lang->load($languge, 'english');
        }

        // API access key from Google API's Console
        define('API_ACCESS_KEY', 'AIzaSyB99Bgoc55Reoo0mdKRxa0zi-QBGN0O_zk');

        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    }

    /* GLOBLY SELECT LANGUAGE IN SESSION WORKING IN EVERY WHERE */

    public function select_language()
    {

        $languageObj = $this->load->model("web/language_model");
        $languageObj = new language_model();

        $language = $_POST['lang'];
        if ($language == "") {
            $language = 'en';
        }
        $redirectPage = '';
        if ($_POST['redirectPage'] != '') {
            $redirectPage = $_POST['redirectPage'];
        }
        $languageObj->set_customer_id($this->session->userdata('id'));
        $languageObj->set_language_code($language);
        $languageObj->updateLanguageCode();
        //$this->lang->load('en', 'english');
        $this->session->set_userdata('language', $language);
        //echo '<pre>';print_r($this->session->all_userdata());die;

        $this->session->unset_userdata('lang');
        redirect("$redirectPage");
    }

    /**
     * @author Techahead
     * @access Public
     * @param
     * @return
     * Index function load defaut
     * */
    public function index()
    {
        $this->lang->load('en', 'english');
        $notifyObj = $this->load->model("web/notifications_model");
        $data['history'] = $notifyObj->get_push_notification_history();

        if ($this->input->server('REQUEST_METHOD') == "POST") {

//            echo "<pre>"; print_r($this->input->post()); exit;

            $frmdatas = array();
            $totfrm = $this->input->post("msgtype");
            end($totfrm);
            $totfrm = key($totfrm);

            //Deafult Fields Fields
            $msgtype = $this->input->post("msgtype");
            $fp_language = $this->input->post("language");
            $fp_topics = $this->input->post("topics");
            $fp_message = $this->input->post("message");

            $groupsParam =  $this->input->post("group");
            $locationsParam =  $this->input->post("location");
            $disciplineParam =  $this->input->post("discipline");
            $branchParam =  $this->input->post("branch");

            //Push Types datas
            if (in_array("advertisement", $msgtype)) {
                $fp_advert = $this->input->post("advert");
            }
            if (in_array("article", $msgtype)) {
                $fp_article = $this->input->post("article");
            }
            if (in_array("closemagazine", $msgtype)) {
                $fp_cmtype = $this->input->post("cmtype");
                $fp_cm = $this->input->post("clsmagazine");
                if (in_array("cm_ad", $fp_cmtype)) {
                    $fp_cm_ad = $this->input->post("cmadvert");
                }
                if (in_array("cm_art", $fp_cmtype)) {
                    $fp_cm_art = $this->input->post("cmart");
                }
            } else {
                $fp_customer = $this->input->post("customer");
            }

            //Collect each form datas
            for ($f = 0; $f <= $totfrm; $f++) {

                if( !isset( $msgtype[$f] ) ){
                    continue;
                }

                //Message Type
                if ($msgtype[$f] != "closemagazine") {
                    $frmdatas[$f]['msgtype'] = $msgtype[$f];
                    if ($fp_customer[$f] != "") {
                        $frmdatas[$f]['customer'] = $fp_customer[$f];
                    }
                } else {
                    if ($fp_cmtype[$f] == "cm") {
                        $frmdatas[$f]['msgtype'] = "closemagazine";
                    } else {
                        $frmdatas[$f]['msgtype'] = $fp_cmtype[$f];
                    }
                }
                //Language
                if ($fp_language[$f] != "") {
                    $frmdatas[$f]['language'] = $fp_language[$f];
                }
                //Topics
                if ($fp_topics[$f] != "") {
                    $frmdatas[$f]['topics'] = $fp_topics[$f];
                }
                //Message Content
                if ($fp_message[$f] != "") {
                    $frmdatas[$f]['message'] = $fp_message[$f];
                } else {
                    $frmdatas[$f]['message'] = "";
                }
                //Type Value
                if ($msgtype[$f] == "advertisement") {
                    $frmdatas[$f]['typeval'] = $fp_advert[$f];
                }
                if ($msgtype[$f] == "article") {
                    $frmdatas[$f]['typeval'] = $fp_article[$f];
                }
                if ($msgtype[$f] == "closemagazine") {
                    if ($fp_cmtype[$f] == "cm") {
                        $frmdatas[$f]['typeval'] = $fp_cm[$f];
                    } else if ($fp_cmtype[$f] == "cm_ad") {
                        $frmdatas[$f]['typeval'] = $fp_cm[$f];
                        $frmdatas[$f]['typeval_in'] = $fp_cm_ad[$f];
                    } else if ($fp_cmtype[$f] == "cm_art") {
                        $frmdatas[$f]['typeval'] = $fp_cm[$f];
                        $frmdatas[$f]['typeval_in'] = $fp_cm_art[$f];
                    }
                }
            }

            $result_android = "";
            $result_ios = "";

            foreach ($frmdatas as $keyData => $pdata) {
                if ($pdata['customer'] != "") {
                    $notifyObj->set_customer_id($pdata['customer']);
                }

                $notifyObj->set_location($pdata['location']);
                $notifyObj->set_language_id($pdata['language']);
                $notifyObj->set_topic_id($pdata['topics']);
                $notifyObj->set_message($pdata['message']);
                $notifyObj->set_message_type($pdata['msgtype']);
                $notifyObj->set_message_value($pdata['typeval']);

                $data_result = $notifyObj->get_users($groupsParam[$keyData], $locationsParam[$keyData], $disciplineParam[$keyData], $branchParam[$keyData]);

//            echo '<pre>'; print_r($data_result);

                $regis_temp_ios = array();
                $regis_temp_android = array();
                foreach ($data_result['devices'] as $device) {
                    if (strpos($device['deviceId_ios'], ",") > 0) {
                        $rgsexplode = explode(",", $device['deviceId_ios']);
                        foreach ($rgsexplode as $rgsid) {
                            array_push($regis_temp_ios, $rgsid);
                        }
                    } else {
                        array_push($regis_temp_ios, $device['deviceId_ios']);
                    }
                    if (strpos($device['deviceId_android'], ",") > 0) {
                        $rgsexplode = explode(",", $device['deviceId_android']);
                        foreach ($rgsexplode as $rgsid) {
                            array_push($regis_temp_android, $rgsid);
                        }
                    } else {
                        array_push($regis_temp_android, $device['deviceId_android']);
                    }
                }

                foreach ($regis_temp_ios as $key_ios => $value_ios) {
                    if (empty($value_ios)) {
                        unset($regis_temp_ios[$key_ios]);
                    }
                }
                foreach ($regis_temp_android as $key_and => $value_and) {
                    if (empty($value_and)) {
                        unset($regis_temp_android[$key_and]);
                    }

                }

                //New Array for Android and IOS
                $regist_new_android = array();
                $regist_new_ios = array();
                foreach ($regis_temp_android as $new_and) {
                    if (!in_array($new_and, $regist_new_android)) {
                        array_push($regist_new_android, $new_and);
                    }
                }
                foreach ($regis_temp_ios as $new_ios) {
                    if (!in_array($new_ios, $regist_new_ios)) {
                        array_push($regist_new_ios, $new_ios);
                    }
                }

                if ($pdata['message'] != '') {
                    $bdymsg = $pdata['message'];
                } else {
                    $bdymsg = $pdata['msgtype'];
                }
                //print_r($registrationIds);
                //prep the bundle

                $data = array();
                $data['contents'] = $pdata['message'];
                $data['message'] = $pdata['message'];
                if ($pdata['msgtype'] == "cm_ad" || $pdata['msgtype'] == "cm_art") {
                    $data['messagetype'] = "closemagazine";
                } else {
                    $data['messagetype'] = $pdata['msgtype'];
                }

                if ($pdata['msgtype'] == "advertisement" || $pdata['msgtype'] == "cm_ad") {
                    if ($pdata['msgtype'] == "advertisement") {
                        $advertisement = $notifyObj->advertisement($pdata['typeval']);
                    } else if ($pdata['msgtype'] == "cm_ad") {
                        $data['messagetype_sub'] = "close_advertisement";
                        $advertisement = $notifyObj->cm_advertisement($pdata['typeval_in']);
                    }

                    $data['bannerURL'] = $this->config->base_url() . "assets/Advertise/thumbs/" . $advertisement->cover_image;
                    $data['landscapeURL'] = $this->config->base_url() . "assets/Advertise/" . $advertisement->landscape_image;
                    $data['portraitURL'] = $this->config->base_url() . "assets/Advertise/" . $advertisement->portrait_image;
                    $data['timeInSeconds'] = $advertisement->display_time;
                    $data['url'] = $advertisement->link;

                    if ($pdata['msgtype'] == "cm_ad") {
                        $data['article_id'] = $pdata['typeval'];
                        $data['article_id_sub'] = $advertisement->id;
                    } else {
                        $data['article_id'] = $advertisement->id;
                    }
                }
                if ($pdata['msgtype'] == "article" || $pdata['msgtype'] == "cm_art") {
                    if ($pdata['msgtype'] == "article") {
                        $articles = $notifyObj->articles($pdata['typeval']);
                    } else if ($pdata['msgtype'] == "cm_art") {
                        $data['messagetype_sub'] = "close_article";
                        $articles = $notifyObj->cm_articles($pdata['typeval_in']);
                    }

                    $data['url'] = $articles->article_link;

                    if ($pdata['msgtype'] == "cm_art") {
                        $data['article_id'] = $pdata['typeval'];
                        $data['article_id_sub'] = $articles->id;
                    } else {
                        $data['article_id'] = $articles->id;
                    }
                    if ($articles->allow_comment == 0) {
                        $data['allowComment'] = 'N';
                    }
                    if ($articles->allow_comment == 1) {
                        $data['allowComment'] = 'Y';
                    }
                    if ($articles->allow_share == 0) {
                        $data['allowShare'] = 'N';
                    }
                    if ($articles->allow_share == 1) {
                        $data['allowShare'] = 'Y';
                    }
                    $pos = strpos($articles->description_without_html, ' ', 10);
                    $desc = substr($articles->description_without_html, 0, $pos);
                    $data['articleDesc'] = $desc;
                    $data['articleVideoUrl'] = $this->config->base_url() . "assets/Article/" . $articles->video_path;
                    $data['coverPhotoUrl'] = $this->config->base_url() . "assets/Article/" . $articles->image;
                    $data['createdDate'] = $articles->publish_date;
                    if ($articles->embed_video_thumb != '') {
                        $data['embedded_thumbnail'] = $articles->embed_video_thumb;
                    } else {
                        $data['embedded_thumbnail'] = null;
                    }
                    if ($articles->embed_video != '') {
                        //preg_match('/src="([^"]+)"/', $articles->embed_video, $match);
                        //$data['embedded_video'] = $match[1];
                        $data['embedded_video'] = $articles->embed_video;
                    } else {
                        $data['embedded_video'] = null;
                    }
                    if ($articles->article_link != '' && $articles->article_link != '0') {
                        $link = str_replace(array('http://', 'https://', 'www.'), '', $articles->article_link);
                        $link_name = explode('.', $link);
                        $data['fullSoruce'] = $articles->article_link;
                        $data['source'] = $link_name[0];
                    } else {
                        $data['fullSoruce'] = "";
                        $data['source'] = "";
                    }
                    if ($articles->media_type == 0) {
                        $data['articleType'] = "photo";
                    }
                    if ($articles->media_type == 1) {
                        $data['articleType'] = "embedded";
                    }
                    $data['title'] = $articles->title;
                    $data['createdBy'] = $articles->created_by;
                }
                if ($pdata['msgtype'] == "closemagazine") {

                    $data['messagetype_sub'] = "closemagazine";
                    $closemagazine = $notifyObj->closemagazine($pdata['typeval']);

                    $data['url'] = $closemagazine->link_url;
                    $data['article_id'] = $closemagazine->id;
                    $data['media_type'] = $closemagazine->media_type;
                    $data['title'] = $closemagazine->title;
                }

                $msg = [
                    'title' => 'Kumon',
                    'body' => $bdymsg,
                    'sound' => 'sound.wav'
                ];

                $fields_ios = [
                    'registration_ids' => $regist_new_ios,
                    'priority' => "high",
                    'notification' => $msg,
                    'data' => $data
                ];

                $data['title'] = 'Kumon';
                $data['body'] = $bdymsg;
                $data['sound'] = 'sound.wav';

                $fields_android = [
                    'registration_ids' => $regist_new_android,
                    'priority' => "high",
                    'data' => $data
                ];

                $headers = [
                    'Authorization: key=' . API_ACCESS_KEY,
                    'Content-Type: application/json'
                ];

//            echo "<pre>";
//            echo "Data:<br>";
//            print_r($data);
//            echo "IOS:<br>";
//            echo "<pre>";
//            print_r($fields_ios);
//            echo "Android:<br>";
//            print_r($fields_android);
//            echo "</pre>";
//            exit;

//                $dataIos = $fields_ios["registration_ids"][57];
//                $fields_ios["registration_ids"] = [];
//                $fields_ios["registration_ids"][] = $dataIos;
//
//                $dataAndroid = $fields_android["registration_ids"][55];
//                $fields_android["registration_ids"] = [];
//                $fields_android["registration_ids"][] = $dataAndroid;

                //            echo "<pre>";
//            echo "Data:<br>";
//            print_r($data);
//            echo "IOS:<br>";
//            echo "<pre>";
//            print_r($fields_ios);
//            echo "Android:<br>";
//            print_r($fields_android);
//            echo "</pre>";
//            exit;

                //Android FCM Push
                if( count( $fields_android["registration_ids"] ) > 0 ){

                    $fields_android = json_encode($fields_android);

                    $ch_and = curl_init();
                    curl_setopt($ch_and, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
                    curl_setopt($ch_and, CURLOPT_POST, true);
                    curl_setopt($ch_and, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch_and, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch_and, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch_and, CURLOPT_POSTFIELDS, $fields_android);
                    $result_android = curl_exec($ch_and);
                    curl_close($ch_and);

                }

                //IOS FCM Push
                if( count( $fields_ios["registration_ids"] ) > 0 ){

                    $fields_ios = json_encode($fields_ios);

                    $ch_ios = curl_init();
                    curl_setopt($ch_ios, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
                    curl_setopt($ch_ios, CURLOPT_POST, true);
                    curl_setopt($ch_ios, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch_ios, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch_ios, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch_ios, CURLOPT_POSTFIELDS, $fields_ios);
                    $result_ios = curl_exec($ch_ios);
                    curl_close($ch_ios);

                }

//            echo "IOS Report:<br>";
//            print_r($result_ios);
//            echo "Android Report:<br>";
//            print_r($result_android);

                $data_history = $notifyObj->store_push_history();
            }
//            exit;
            //add flash data
            if ($result_android != '' || $result_ios != '') {
                $this->session->set_flashdata('push_notification_succ', 'Notification Sent Successfully');

            } else {
                $this->session->set_flashdata('push_notification_err', 'Failed to send the Notification');
            }
            redirect('notifications', 'refresh');
        }



        //echo "hello"; die;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'notifications/notifications', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    public function new_filter(){

        $i = $this->input->post("filterCount");

        $this->load->model("web/group_model");
        $model = new group_model();

        $groups = $model->getAllGroups();
        $locations = $model->getAllLocations();
        $disciplines = $model->getAllDisciplines();
        $branches = $model->getAllBranches();

        $data = array();

        $data["groups"] = $groups;
        $data["locations"] = $locations;
        $data["disciplines"] = $disciplines;
        $data["branches"] = $branches;
        $data["i"] = $i;
        $data['main_content'] = array('view' => 'notifications/push_new_filter', 'data' => array());
        $this->load->view('templates/only_content', $data);

    }

}

?>
