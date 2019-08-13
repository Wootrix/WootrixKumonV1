<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
(defined('BASEPATH')) OR exit('No direct script access allowed');

class notifications_model extends CI_Model {
    
    private $_location = "";
    private $_language_id = "";
    private $_topic_id = "";
    private $_customer_id = "";
    private $_message = "";
    private $_message_type = "";
    private $_message_value = "";
    
    /*GETTER AND SETTER*/
    public function set_message_value($_message_value) {
        $this->_message_value = $_message_value;
    }
    
    public function get_message_value() {
        return $this->_message_value;
    }
    
    public function set_message_type($_message_type) {
        $this->_message_type = $_message_type;
    }
    
    public function get_message_type() {
        return $this->_message_type;
    }
    
    public function get_location() {
        return $this->_location;
    }

    public function set_location($_location) {
        $this->_location = $_location;
    }
    
    public function get_language_id() {
        return $this->_language_id;
    }

    public function set_language_id($_language_id) {
        $this->_language_id = $_language_id;
    }
    
    public function get_topic_id() {
        return $this->_topic_id;
    }

    public function set_topic_id($_topic_id) {
        $this->_topic_id = $_topic_id;
    }
    
    public function get_customer_id() {
        return $this->_customer_id;
    }

    public function set_customer_id($_customer_id) {
        $this->_customer_id = $_customer_id;
    }
    
    public function get_message() {
        return $this->_message;
    }

    public function set_message($_message) {
        $this->_message = $_message;
    }
    
    public function get_users($arrayGroups = [], $arrayLocations = [], $arrayDisciplines = [], $arrayBranches = [])
    {
        $groups = implode(",", $arrayGroups);
        $locations = implode(",", $arrayLocations);
        $disciplines = implode(",", $arrayDisciplines);
        $branches = implode("','", $arrayBranches);

        $qrys = "";
        $message = $this->get_message();
        $message_type = $this->get_message_type();
        $message_value = $this->get_message_value();
        $location = $this->get_location();

        if($location != "" || ($message_type == 'closemagazine' || $message_type == 'close_advertisement' || $message_type == 'close_article' || $message_type == 'message'))
        {
            $qrys .= "(";
        }
        if($location != '')
        {
            if($langid != "" || ($message_type == 'closemagazine' || $message_type == 'close_advertisement' || $message_type == 'close_article' || $message_type == 'message'))
            {
                $qrys .= " `location` LIKE '%$location%' AND ";
            }
            else
            {
                $qrys .= " `location` LIKE '%$location%' ";
            }
        }
        if(($message_type == 'closemagazine' || $message_type == 'close_advertisement' || $message_type == 'close_article') || $message_type == 'message')
        {
            $magazineid = $this->db->query("SELECT user_id FROM tbl_user_magazines WHERE magazine_id IN ('$message_value')");
            $magazineid = $magazineid->result_array();
            //print_r($magazineid); 
            foreach($magazineid as $mz)
            {
                $ids[]=  $mz['user_id'];
            }
            $userid = implode(",", $ids);
            
                $qrys .= " `id` IN ($userid) ";
        }

        $query = "SELECT u.id,deviceId_android,deviceId_ios,location,category,article_language 
                                FROM tbl_users u
                                LEFT JOIN tbl_user_group ug ON u.id = ug.id_user
                                LEFT JOIN tbl_user_location ul ON u.user_location_id = ul.id
                                LEFT JOIN tbl_user_discipline ud ON u.id = ud.user_id
                                WHERE 
                                  1 = 1";

        if( $groups != '' ){
            $query .= " AND ug.id_group IN ($groups) ";
        }

        if( $locations != '' ){
            $query .= " AND ul.id IN ($locations) ";
        }

        if( $disciplines != '' ){
            $query .= " AND ud.discipline_id IN ($disciplines) ";
        }

        if( $branches != '' ){
            $branchesComplete = '\'' . $branches . '\'';
            $query .= " AND u.branch IN ($branchesComplete) ";
        }

        if( $qrys != '' ){
            $query .= " AND $qrys ";
        }

        $query .= " AND (deviceId_android != '' OR deviceId_ios != '')";

//        echo $query; exit;

        $sql = $this->db->query($query);
        $data["devices"] = $sql->result_array();
//        echo $this->db->last_query()."<br>";  exit;
        return $data;
    }
    
    public function store_push_history()
    {
        $options = "";
        $data =  array();
        $location = $this->get_location();
        $message = $this->get_message();
        $user_id = $this->session->userdata['user_id'];
        
        if($location != '')
        {
            $options .= "$location";
        }
        $current_date = date("Y-m-d H:i:s");
        
        $data['message'] = $message;
        $data['options'] = $options;
        $data['sent_at'] = $current_date;
        $data['user_type'] = 'customer';
        $data['customer_id'] = $user_id;
        $this->db->insert('tbl_notifications_history', $data);
        $insert_id = $this->db->insert_id();

        return  $insert_id;
    }
    
    public function get_push_notification_history()
    {
        $user_id = $this->session->userdata['user_id'];
        $data = array();
        $history_sql = $this->db->query("SELECT * FROM `tbl_notifications_history` where customer_id='$user_id' and user_type='customer' ORDER BY `id` DESC LIMIT 0,25");
        if ($history_sql->num_rows() < 1) {
            return $data;
        } else {
            return $data = $history_sql->result_array();
        }
    }
    
    public function cm_advertisement($id)
    {
        $this->db->where('id', $id);
        $this->db->order_by("link", "asc");
        return $get=$this->db->get('tbl_magazine_advertisement')->row();
    }
    
    public function cm_articles($id)
    {
        $this->db->where('id', $id);
        $this->db->order_by("title", "asc");
        return $get=$this->db->get('tbl_magazine_articles')->row();
    }
    
    public function closemagazine($id)
    {
        $this->db->where('id', $id);
        $this->db->order_by("title", "asc");
        return $get=$this->db->get('tbl_magazine')->row();
    }
    
    public function get_close_magazine()
    {
        $user_id = $this->session->userdata['user_id'];
        $data = array();
        $sql = $this->db->query("SELECT id,title FROM tbl_magazine where status='1' and `customer_id` = '".$user_id."' order by title asc");
        if ($sql->num_rows() < 1) {
            return $data;
        } else {
            return $data = $sql->result_array();
        } 
    }
    
    public function get_closemagazine_ad($id)
    {
        $data = array();
        $sql = $this->db->query("SELECT id,link FROM `tbl_magazine_advertisement` WHERE `magazine_id` LIKE '%$id%' ORDER BY `link` ASC ");
        if ($sql->num_rows() < 1) {
            return $data;
        } else {
            return $data = $sql->result_array();
        } 
    }
    
    public function get_closemagazine_article($id)
    {
        $data = array();
        $sql = $this->db->query("SELECT id,title FROM `tbl_magazine_articles` WHERE `magazine_id` LIKE '%$id%' ORDER BY `title` ASC ");
        if ($sql->num_rows() < 1) {
            return $data;
        } else {
            return $data = $sql->result_array();
        } 
    }
    
}

?>
