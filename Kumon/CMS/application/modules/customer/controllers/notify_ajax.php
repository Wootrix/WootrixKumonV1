<?php

error_reporting(E_ALL ^ E_NOTICE);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Notify_ajax extends CI_Controller {

    public function __construct() {

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
        
        //API  access key from Google API's Console
        define( 'API_ACCESS_KEY', 'AIzaSyD3w-hUZzfZn6Kjn-kN2J6A59FYMuYoCHs' );

        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    }

    /* GLOBLY SELECT LANGUAGE IN SESSION WORKING IN EVERY WHERE */

    public function select_language() {

        $languageObj = $this->load->model("web/language_model");
        $languageObj = new language_model();

       $language = $_POST['lang'];
        if ($language == "") {
            $language = 'en';
        }
        $redirectPage='';
        if($_POST['redirectPage']!=''){
        $redirectPage=$_POST['redirectPage'];
        }
        $languageObj->set_customer_id($this->session->userdata('id'));
        $languageObj->set_language_code($language);
        $languageObj->updateLanguageCode();
        //$this->lang->load('en', 'english');
       $this->session->set_userdata('language', $language);
       // echo '<pre>';print_r($this->session->all_userdata());die;

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
    public function index() {
        $this->lang->load('en', 'english');
        $Obj = $this->load->model("customer/notifications_model");
        //print_r($this->input->post()); 
        $ajax_type = $this->input->post("type");
        $data = "";

        if($ajax_type == "cmtype") {
            $msg_type = $this->input->post("msg");
            $frm_val = $this->input->post("form");

            $magazine_result = $Obj->get_close_magazine();

            $data .= '<div class="form-group">';
            $data .= '<label>Close Magazine</label>';
            $data .= '<select required  class="form-control select-down-arrow fld_cmtypein" id="fld_cm_'.$frm_val.'"  name="clsmagazine['.$frm_val.']">';
            $data .= '<option value="">Please Select...</option>';
            foreach($magazine_result as $magazine) { 
              $data .= '<option value="'.$magazine['id'].'">'.mysql_real_escape_string($magazine['title']).'</option>';
            }
            $data .= '</select>';
            $data .= '</div>';
            $data .= '<div id="div_cmtypin_'.$frm_val.'" style="display: none;"></div>';
        }
        else if($ajax_type == "cmtypein") {
            $frm_val = $this->input->post("form");
            $clsmag_id = $this->input->post("id");
            $cm_type = $this->input->post("cmtyp");
            
            if($cm_type == "close_advertisement")
            {
                $data_ad = $Obj->get_closemagazine_ad($clsmag_id);
                
                $data .= '<div class="form-group">'.
                '<label>CM Advertisement</label>'.
                '<select required style="width:70%;height:46px" id="fld_cmad_'.$frm_val.'" name="cmadvert['.$frm_val.']" class="form-control select-down-arrow" >';
                if(count($data_ad) > 0):
                    $data .= '<option value="" >Please Select...</option>';
                foreach($data_ad as $cmad):
                    $data .= '<option value="'.$cmad['id'].'" >'.$cmad['link'].'</option>';
                endforeach;
                else:
                    $data .= '<option value="" >No Closed Magazine Advertisement</option>';
                endif;
                $data .= '</select> &nbsp;';
                $data .= '</div>';
            }
            if($cm_type == "close_article")
            {
                $data_article = $Obj->get_closemagazine_article($clsmag_id);
                        
                $data .= '<div class="form-group">'.
                '<label>CM Article</label>'.
                '<select required style="width:69%;height:46px" id="fld_cmart_'.$frm_val.'" name="cmart['.$frm_val.']" class="form-control select-down-arrow" >';
                if(count($data_article) > 0):
                    $data .= '<option value="" >Please Select...</option>';
                foreach($data_article as $cmart):
                    $data .= '<option value="'.$cmart['id'].'" >'.$cmart['title'].'</option>';
                endforeach;
                else:
                    $data .= '<option value="" >No Closed Magazine Article</option>';
                endif;
                $data .= '</select> &nbsp;';
                $data .= '</div>';
            }
        }
        else if($ajax_type == "form") {
            $frm_val = $this->input->post("form");
            ++$frm_val;
            
            $data .= '<div id="form_count_'.$frm_val.'">';
            
            $data .= '<div class="row">';
            $data .= '<div class="form-group">';
            $data .= '<label>Geolocation</label>';
            $data .= '<input type="text" name="geolocation['.$frm_val.']" id="fld_loc_'.$frm_val.'" class="form-control"/>';
            $data .= '</div>';
            $data .= '<div class="form-group">';
            $data .= '<label>Message Type</label>';
            $data .= '<select required name="msgtype['.$frm_val.']" id="fld_cmtype_'.$frm_val.'" class="form-control select-down-arrow fld_cmtype">';
            $data .= '<option value="">Please Select...</option>';
            $data .= '<option value="message">Message</option>';
            $data .= '<option value="close_advertisement">Advertisement</option>';
            $data .= '<option value="close_article">Article</option>';
            $data .= '<option value="closemagazine">Closed Magazine</option>';
            $data .= '</select>';
            $data .= '</div>';
            $data .= '</div>';
            
            $data .= '<div class="row" id="div_cmtyp_'.$frm_val.'" style="display: none;"></div>';
            
            $data .= '<div class="row"  id="div_message_'.$frm_val.'">';
            $data .= '<div class="full-width-form-group form-group-text-area">';
            $data .= '<label>Message</label>';
            $data .= '<textarea row="10" width="100%" name="message['.$frm_val.']" id="fld_msg_'.$frm_val.'" class="form-control"></textarea>';
            $data .= '</div>';
            $data .= '</div>';
            
            $data .= '<button style="float:right" onclick="del_form('.$frm_val.')" class="remove_field glyphicon delete-bttn"></button>';
            $data .= '<input type="hidden" name="curform[]" id="curformval_'.$frm_val.'" value="'.$frm_val.'">';
            $data .= '</div>';
        }
        
        echo $data;
        exit;
    }
}

?>


