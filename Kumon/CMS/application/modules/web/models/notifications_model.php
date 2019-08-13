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
	$message_type = preg_replace('/[0-9]+/', '', $message_type);
        $message_value = $this->get_message_value();
//        $location = $this->get_location();
        $langid = $this->get_language_id();
	 
        $customerid = $this->get_customer_id();
        $topicid = $this->get_topic_id();
        if($langid != "" || $topicid != "" || (($message_type == 'closemagazine' || $message_type == 'cm_ad' || $message_type == 'cm_art') && $customerid == '') || ($customerid != '' && ($message_type != 'closemagazine' || $message_type != 'cm_ad' || $message_type != 'cm_art')) )
        {
            $qrys .= "(";
        }
//        if($locations != '')
//        {
//            if($langid != "" || ($topicid != "" || (($message_type == 'closemagazine' || $message_type == 'cm_ad' || $message_type == 'cm_art') && $customerid == '') || ($customerid != '' && ($message_type != 'closemagazine' || $message_type != 'cm_ad' || $message_type != 'cm_art'))))
//            {
//                $qrys .= " `location` LIKE '%$location%' AND ";
//            }
//            else
//            {
//                $qrys .= " `location` LIKE '%$location%' ";
//            }
//        }
        if($langid != '')
        {
            if(($topicid != "" || (($message_type == 'closemagazine' || $message_type == 'cm_ad' || $message_type == 'cm_art') && $customerid == '') || ($customerid != '' && ($message_type != 'closemagazine' || $message_type != 'cm_ad' || $message_type != 'cm_art'))))
            {
                $qrys .= " `website_language_id` IN ($langid) AND ";
            }
            else
            {
                $qrys .= " `website_language_id` IN ($langid) ";
            }
        }
        if($topicid != '')
        {
            $imptopic = implode("|", $topicid);
            if((($message_type == 'closemagazine' || $message_type == 'cm_ad' || $message_type == 'cm_art') && $customerid == '') || ($customerid != '' && ($message_type != 'closemagazine' || $message_type != 'cm_ad' || $message_type != 'cm_art')))
            {
                $qrys .= " `category` REGEXP '^$imptopic' AND ";
            }
            else
            {
                $qrys .= " `category` REGEXP '^$imptopic'  ";
            }
        }
        if(($message_type == 'closemagazine' || $message_type == 'cm_ad' || $message_type == 'cm_art') && $customerid == '')
        {
            $magazineid = $this->db->query("SELECT user_id FROM tbl_user_magazines WHERE magazine_id IN ('$message_value')");
            $magazineid = $magazineid->result_array();
            //print_r($magazineid);
            foreach($magazineid as $mz)
            {
                $ids[]=  $mz['user_id'];
            }
            $userid = implode(",", $ids);
            
                $qrys .= " u.id IN ($userid) ";
           
        }
        else if($customerid != '' && ($message_type != 'closemagazine' || $message_type != 'cm_ad' || $message_type != 'cm_art'))
        {
            $cm_userd = array();
            $magid_sql = $this->db->query("SELECT magazine_id FROM `tbl_magazine_articles` WHERE `customer_id` = '$customerid'");
            $magazinesid = $magid_sql->result_array();
            $unimagaidsart = array();
            //print_r($magazinesid);
            foreach($magazinesid as $tmpmagid)
            {
                if(strpos($tmpmagid['magazine_id'], ","))
                {
                    $expcmag = explode(",", $tmpmagid['magazine_id']);
                    foreach($expcmag as $tminmagid)
                    {
                        if(!in_array($tminmagid, $unimagaidsart))
                        {
                            array_push($unimagaidsart, $tminmagid);
                        }    
                    }
                }
                else {
                    if(!in_array($tmpmagid['magazine_id'], $unimagaidsart))
                    {
                        array_push($unimagaidsart, $tmpmagid['magazine_id']);
                    }    
                }
            }
            $uni_magid = array_unique($unimagaidsart);
            foreach($uni_magid as $magid)
            {
                $userid_sql = $this->db->query("SELECT user_id FROM `tbl_user_magazines` WHERE `magazine_id` = '{$magid}'");
                $musersid = $userid_sql->result_array();
                foreach ($musersid as $muser)
                {
                    array_push($cm_userd,$muser['user_id']);
                }
            }
            $impiusersids = implode(",", $cm_userd);
            
                $qrys .= " u.id IN ($impiusersids) ";
           
        }
        
        if($qrys != "")
        {
            $qrys .= ")";
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
//        echo $this->db->last_query()."<br>";  die;
        return $data;
    }
    
    public function store_push_history()
    {
       $options = "";
        $data =  array();
        $location = $this->get_location();
        $langid = $this->get_language_id();
        $customerid = $this->get_customer_id();
        $topicid = $this->get_topic_id();
        $message = $this->get_message();
        
        if($location != '')
        {
            $options .= "$location";
        }
        if($langid != '')
        {
            $lang_sql = $this->db->query("SELECT language FROM `tbl_language` WHERE `id` = '$langid'");
            $lang_data = $lang_sql->result_array();
            $options .= "{$lang_data[0]['language']}";
        }
        if($topicid != '')
        {
            foreach($topicid as $topcd) {
            $topic_sql = $this->db->query("SELECT category_name FROM `tbl_category` WHERE `id` = '$topcd'");
            $topic_data = $topic_sql->result_array();
            if($location != '' || $langid != '')
            {
                $options .= ", {$topic_data[0]['category_name']}";
            }
            else
            {
                $options .= "{$topic_data[0]['category_name']}";
            }
            }
        }
        if($customerid != '')
        {
            $customer_sql = $this->db->query("SELECT name,user_name FROM `tbl_customer` WHERE `id` = '$customerid'");
            $customer_data = $customer_sql->result_array();
            if($location != '' || $langid != '' || $topicid != '')
            {
                $options .= ", {$customer_data[0]['company_name']} ({$customer_data[0]['name']})";
            }
            else 
            {
                $options .= "{$customer_data[0]['company_name']} ({$customer_data[0]['name']})";
            }
        }
        $current_date = date("Y-m-d H:i:s");
        
        $data['message'] = $message;
        $data['options'] = $options;
        $data['sent_at'] = $current_date;
        $data['user_type'] = 'admin';
        $this->db->insert('tbl_notifications_history', $data);
        $insert_id = $this->db->insert_id();

        return  $insert_id;
   
	}
    
    public function get_push_notification_history()
    {
        $data = array();
        $history_sql = $this->db->query("SELECT * FROM `tbl_notifications_history` WHERE `user_type` = 'admin' AND `customer_id` = '0' ORDER BY `id` DESC LIMIT 0,25");
        if ($history_sql->num_rows() < 1) {
            return $data;
        } else {
            return $data = $history_sql->result_array();
        }
    }
	
    public function advertisement($id)
    {
        $this->db->where('id', $id);
        $this->db->order_by("link", "asc");
        return $get=$this->db->get('tbl_advertisement')->row();
    }
    
    public function articles($id)
    {
        $this->db->where('id', $id);
        $this->db->order_by("title", "asc");
        return $get=$this->db->get('tbl_new_articles')->row();
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
    
    // Form AJAX Functions
    public function get_language()
    {
        $data = array();
        $sql = $this->db->query("SELECT id,language,language_code FROM tbl_language WHERE status='1'");
        if ($sql->num_rows() < 1) {
            return $data;
        } else {
            return $data = $sql->result_array();
        } 
    }
    
    public function get_topics()
    {
        $data = array();
        $sql= $this->db->query("SELECT id,category_name FROM tbl_category WHERE status='1'");
        if ($sql->num_rows() < 1) {
            return $data;
        } else {
            return $data = $sql->result_array();
        } 
    }
    
    public function get_customers()
    {
        $data = array();
        $sql= $this->db->query("SELECT id,name,company_name FROM tbl_customer WHERE status='1' order by name asc");
        if ($sql->num_rows() < 1) {
            return $data;
        } else {
            return $data = $sql->result_array();
        } 
    }
    
    public function get_close_magazine()
    {
        $data = array();
        $sql = $this->db->query("SELECT id,title FROM tbl_magazine where status='1' order by title asc");
        if ($sql->num_rows() < 1) {
            return $data;
        } else {
            return $data = $sql->result_array();
        } 
    }
    
    public function get_open_article()
    {
        $data = array();
        $sql = $this->db->query("SELECT id,title,article_link FROM tbl_new_articles where status= '2' order by title asc");
        if ($sql->num_rows() < 1) {
            return $data;
        } else {
            return $data = $sql->result_array();
        } 
    }
    
    public function get_open_advertisement()
    {
        $data = array();
        $sql = $this->db->query("SELECT id,link FROM tbl_advertisement WHERE status='1' order by link asc");
        if ($sql->num_rows() < 1) {
            return $data;
        } else {
            return $data = $sql->result_array();
        } 
    }
    
    public function get_closemagazine_ad($id)
    {
        $data = array();
        $sql = $this->db->query("SELECT id,link FROM `tbl_advertisement` WHERE `magazine_id` LIKE '%$id%' ORDER BY `link` ASC ");
        if ($sql->num_rows() < 1) {
            return $data;
        } else {
            return $data = $sql->result_array();
        } 
    }
    
    public function get_closemagazine_article($id)
    {
        $data = array();
        $sql = $this->db->query("SELECT id,title FROM `tbl_new_articles` WHERE `magazine_id` LIKE '%$id%' ORDER BY `title` ASC ");
        if ($sql->num_rows() < 1) {
            return $data;
        } else {
            return $data = $sql->result_array();
        } 
    }
    
}

?>
