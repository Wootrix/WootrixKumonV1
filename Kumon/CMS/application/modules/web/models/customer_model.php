<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

(defined('BASEPATH')) OR exit('No direct script access allowed');

class customer_model extends CI_Model {  
    
        
    private $_tableName = 'tbl_customer';
    private $_id = "";
    private $_name = "";
    private $_email = "";
    private $_password = "";
    private $_role = "";
    private $_gender = "";
    private $_mobile = "";
    private $_work_phone = "";
    private $_address = "";
    private $_dob ="";
    private $_country = "";
    private $_city = "";
    private $_company_name = "";
    private $_status = "";
    private $_created_date = "";
    private $_image = "";
    private $_user_name = "";
    private $_auto_accept_article = "";
    
    public function get_tableName() {
        return $this->_tableName;
    }

    public function set_tableName($_tableName) {
        $this->_tableName = $_tableName;
    }

    public function get_id() {
        return $this->_id;
    }

    public function set_id($_id) {
        $this->_id = $_id;
    }

    public function get_name() {
        return $this->_name;
    }

    public function set_name($_name) {
        $this->_name = $_name;
    }

    public function get_email() {
        return $this->_email;
    }

    public function set_email($_email) {
        $this->_email = $_email;
    }

    public function get_password() {
        return $this->_password;
    }

    public function set_password($_password) {
        $this->_password = $_password;
    }

    public function get_role() {
        return $this->_role;
    }

    public function set_role($_role) {
        $this->_role = $_role;
    }

    public function get_gender() {
        return $this->_gender;
    }

    public function set_gender($_gender) {
        $this->_gender = $_gender;
    }

    public function get_mobile() {
        return $this->_mobile;
    }

    public function set_mobile($_mobile) {
        $this->_mobile = $_mobile;
    }

    public function get_work_phone() {
        return $this->_work_phone;
    }

    public function set_work_phone($_work_phone) {
        $this->_work_phone = $_work_phone;
    }

    public function get_address() {
        return $this->_address;
    }

    public function set_address($_address) {
        $this->_address = $_address;
    }

    public function get_dob() {
        return $this->_dob;
    }

    public function set_dob($_dob) {
        $this->_dob = $_dob;
    }

    public function get_country() {
        return $this->_country;
    }

    public function set_country($_country) {
        $this->_country = $_country;
    }

    public function get_city() {
        return $this->_city;
    }

    public function set_city($_city) {
        $this->_city = $_city;
    }

    public function get_company_name() {
        return $this->_company_name;
    }

    public function set_company_name($_company_name) {
        $this->_company_name = $_company_name;
    }

    public function get_status() {
        return $this->_status;
    }

    public function set_status($_status) {
        $this->_status = $_status;
    }

    public function get_created_date() {
        return $this->_created_date;
    }

    public function set_created_date($_created_date) {
        $this->_created_date = $_created_date;
    }

    public function get_image() {
        return $this->_image;
    }

    public function set_image($_image) {
        $this->_image = $_image;
    }

    public function get_user_name() {
        return $this->_user_name;
    }

    public function set_user_name($_user_name) {
        $this->_user_name = $_user_name;
    }

    public function getAutoAcceptArticle()
    {
        return $this->_auto_accept_article;
    }

    public function setAutoAcceptArticle($auto_accept_article)
    {
        $this->_auto_accept_article = $auto_accept_article;
    }

    /*CUSTOMER LIST*/
    
    public function getCustomerList(){
        
        $status = $this->get_status();
        $name = $this->get_name();
        $status_val = $status-1;
        if($status != ""){
             $sql = $this->db->query("SELECT id,image,name,role,email,gender,mobile,status,            
            work_phone,address,dob,country,city,company_name,user_name FROM tbl_customer WHERE status='$status_val'
                      ORDER BY id DESC");
            
        }elseif ($name != "") {
            $sql = $this->db->query("SELECT id,image,name,role,email,gender,mobile,status,            
            work_phone,address,dob,country,city,company_name,user_name FROM tbl_customer 
            WHERE name Like '%$name%' ORDER BY id DESC");
            
        }else{
            $sql = $this->db->query("SELECT id,image,name,role,email,gender,mobile,status,            
            work_phone,address,dob,country,city,company_name,user_name FROM tbl_customer ORDER BY id DESC");            
        }
        //echo $this->db->last_query();
        
        
        /*PAGINATION*/
        $config['base_url'] = base_url().'index.php/'.'customers?perId='.$status;
        $config['total_rows'] = $sql->num_rows();
        $config['per_page'] = 10;
        //$config['uri_segment'] = 3;
        $config['full_tag_open'] = "<p class='pagination_bottom'>";
        $config['full_tag_close'] = "</p>";
         $config['page_query_string'] = TRUE;
         $config['query_string_segment'] = 'page';
         $config['use_page_numbers'] = TRUE;
         $config['next_link'] = 'Next';
         $config['prev_link'] = 'Prev';
         $config['first_link']= 'First';
         $config['last_link'] = 'Last';

        $this->pagination->initialize($config);
        if ($_GET['page']) {
            $page = $_GET['page'];
        } else {
            $page = 1;
        }
        if ($page == 1) {
            $limitPage = 0;
        } else {
            $limitPage = ($page - 1) * 10;
        }
        
        /*------------------*/
        
        if($status != ""){
             $data_sql = $this->db->query("SELECT id,image,name,role,email,gender,mobile,status,            
            work_phone,address,dob,country,city,company_name,user_name FROM tbl_customer WHERE status='$status_val'
                     ORDER BY id DESC LIMIT $limitPage,10 ");
            
        }elseif ($name != "") {
            $data_sql = $this->db->query("SELECT id,image,name,role,email,gender,mobile,status,            
            work_phone,address,dob,country,city,company_name,user_name FROM tbl_customer 
            WHERE name Like '%$name%' ORDER BY id DESC LIMIT $limitPage,10");
            
        }else{
            $data_sql = $this->db->query("SELECT id,image,name,role,email,gender,mobile,status,            
            work_phone,address,dob,country,city,company_name,user_name FROM tbl_customer
            ORDER BY id DESC LIMIT $limitPage,10");            
        }
        
        
        
        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->result_array();
        }
        
    }
    
    /*CUSTOMER user name userNameExistCheck*/
    
    public function userNameExistCheck(){
        
        $user_name = $this->get_user_name();
        $sql = $this->db->query("SELECT id FROM tbl_customer WHERE user_name = '$user_name'");
        //$sql = $this->db->query("SELECT user_name FROM ( SELECT user_name FROM tbl_customer
           // WHERE username = '$user_name'");
        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return TRUE;
        }
        
    }
    
     /*USER EMAIL EXIST CHECK*/
    
    public function userEmailCheck() {

        $user_email = $this->get_email();
        //$sql = $this->db->query("SELECT id FROM tbl_admin WHERE user_name = '$user_name'");
         $sql = $this->db->query("SELECT email  FROM tbl_customer
              WHERE email = '$user_email'");
        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
    /*for perticular name check*/
    
    public function perticuilarUserNameExistCheck(){
        $user_name = $this->get_user_name();
        $user_id = $this->get_id();
        $sql = $this->db->query("SELECT id FROM tbl_customer WHERE user_name = '$user_name' And id !=$user_id");
        //echo $this->db->last_query(); die;
        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return TRUE;
        } 
    }
    /*CHECK PERTICULAR USER EMAIL*/
    public function perticularUserEmailExistCheck() {

        $user_email = $this->get_email();
        $user_id = $this->get_id();
        
         $sql = $this->db->query("SELECT email FROM tbl_customer
             WHERE email = '$user_email' And id !=$user_id");
        //echo $this->db->last_query(); die;
        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
   


    /*INSERT CUSTOMER DETAILS*/
    
    public function InsertCustomerDetails(){
        
        $name = $this->get_name();
        $gender = $this->get_gender();
        $dob = $this->get_dob();
        $email = $this->get_email();
        $company_name = $this->get_company_name();
        $work_phone = $this->get_work_phone();
        $mobile = $this->get_mobile();
        $city = $this->get_city();       
        $address = $this->get_address();
        $user_name = $this->get_user_name();
        $password= $this->get_password();        
        $image = $this->get_image();
        
        
        $data = array('name' => $name, 'email' => $email, 'password' => md5($password),'gender' => $gender,'mobile' => $mobile,'user_name' => $user_name,
            'work_phone' => $work_phone,'address' => $address,'dob' => $dob,'country' => $country,'city' => $city,'company_name' => $company_name,
            'image'=>$image);
        $sql = $this->db->set('created_date', 'now()', FALSE)->insert('tbl_customer', $data);
        //echo $this->db->last_query();
        if ($sql) {
            return TRUE;            
        }
        return FALSE;
        
        
    }
    
    /* GET PERTICULAR CUSTOMER DETAILS */

    public function customerInformation() {

        $customer_id = $this->get_id();

        $sql = $this->db->query("SELECT id,image,name,role,email,gender,mobile, work_phone,address,dob,country,
           city,company_name,user_name, auto_accept_article FROM tbl_customer WHERE id ='$customer_id' ");
        //echo$this->db->last_query();
        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->row_array();
        }
    }
    
    /*DELETE A CUSTOMER*/
    
    public function deleteCustomer(){
        
        $customer_id = $this->get_id();
        
        $query = $this->db->query("SELECT status FROM tbl_customer WHERE id ='$customer_id'");
        $status_value = $query->row_array();
        $status_val = $status_value['status'];
        if($status_val == 1){
            $status = '0';
        }else{
             $status = '1';
        }
        
        $sql = $this->db->query("UPDATE tbl_customer SET status='$status' WHERE id ='$customer_id'");

        if ($sql) {
            return FALSE;
        } else {
            return TRUE;
        }
        
    }
    
    /* UPDATE CUSTOMER DETAILS*/
    
    public function UpdateCustomerDetails(){
        $user_id = $this->get_id();
        $name = $this->get_name();
        $gender = $this->get_gender();
        $dob = $this->get_dob();
        $email = $this->get_email();
        $company_name = $this->get_company_name();
        $work_phone = $this->get_work_phone();
        $mobile = $this->get_mobile();
        $city = $this->get_city();       
        $address = $this->get_address();
        $user_name = $this->get_user_name();
        $password= $this->get_password();        
        $image = $this->get_image();
        $country = $this->get_country();
        $autoAcceptArticle = $this->getAutoAcceptArticle() == 1 ? $this->getAutoAcceptArticle() : 0;

       if ($image != ""){
           $sql = $this->db->query("UPDATE tbl_customer SET name='$name',email= '$email',gender='$gender',
                mobile='$mobile', user_name='$user_name', work_phone='$work_phone', address='$address', dob='$dob',
                country='$country', city='$city',image='$image', company_name='$company_name', auto_accept_article = '$autoAcceptArticle' WHERE id='$user_id'");
           
       }else{
           $sql = $this->db->query("UPDATE tbl_customer SET name='$name',email= '$email',gender='$gender',
                mobile='$mobile', user_name='$user_name', work_phone='$work_phone', address='$address', dob='$dob',
                country='$country', city='$city', company_name='$company_name', auto_accept_article = '$autoAcceptArticle' WHERE id='$user_id'");
       }
        if($password !=""){
           $sql1 = $this->db->query("UPDATE tbl_customer SET password=md5('$password') WHERE id='$user_id'"); 
        }
       //echo $this->db->last_query(); die;
        if ($sql) {
            return TRUE;            
        }
        return FALSE;
        
    }
    
    /*Update Profile Image*/
    
    public function UpdateCustomerProfileImg(){
        
         $user_id = $this->get_id();
         $image = $this->get_image();
         $sql1 = $this->db->query("UPDATE tbl_customer SET image='$image' WHERE id='$user_id'"); 
        if ($sql) {
            return TRUE;            
        }
        return FALSE;
        
    }


    /*CUSTOMER MAGAZINE LIST*/
    
     /**
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Magazine Array 
     * */
    public function getCustomerMagazineList() {
        
        $customer_id = $this->get_id();

        //$sql = $this->db->query("SELECT id,title,cover_image,status,publish_date_from,publish_date_to,subscription_password
         //   FROM tbl_magazine WHERE status = '1' AND customer_id='$customer_id'");
        
        $sql = $this->db->query("SELECT id,title,cover_image,status,publish_date_from,publish_date_to,subscription_password
            FROM tbl_magazine WHERE status = '1' AND publish_date_to >= CURDATE() AND customer_id='$customer_id'");

        /* PAGINATION */
        $config['base_url'] = base_url() . 'index.php/' . 'customermagazine?rowId='.$customer_id;
        $config['total_rows'] = $sql->num_rows();
        $config['per_page'] = 10;
        //$config['uri_segment'] = 3;
        $config['full_tag_open'] = "<p class='pagination_bottom'>";
        $config['full_tag_close'] = "</p>";
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers'] = TRUE;
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Prev';
        $config['first_link'] = 'First';
        $config['last_link'] = 'Last';

        $this->pagination->initialize($config);
        if ($_GET['page']) {
            $page = $_GET['page'];
        } else {
            $page = 1;
        }
        if ($page == 1) {
            $limitPage = 0;
        } else {
            $limitPage = ($page - 1) * 10;
        }

        /* ------------------ */
        $sql_data = $this->db->query("SELECT id,title,cover_image,status,publish_date_from,publish_date_to,subscription_password
            FROM tbl_magazine WHERE status = '1' AND publish_date_to >= CURDATE() AND customer_id='$customer_id' ORDER BY id DESC LIMIT $limitPage,10");
        
        // $sql_data = $this->db->query("SELECT id,title,cover_image,status,publish_date_from,publish_date_to,subscription_password
        //    FROM tbl_magazine WHERE status = '1' AND customer_id='$customer_id' ORDER BY id DESC LIMIT $limitPage,10 ");
        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            //return $data = $sql->result_array();
            $data = $sql_data->result_array();
            /* get article count in this magazine */
            foreach ($data as $key => $value) {
                $magazine_id = $value['id'];
                $sql1 = $this->db->query("SELECT id FROM tbl_magazine_articles WHERE magazine_id ='$magazine_id' AND status='1' ");
                $data[$key]['new_article_count'] = $sql1->num_rows();
            }
            return $data;
        }
    }

    public function getCustomerMagazineFilterList() {

        $customer_id = $this->get_id();

        $sql = "SELECT id, title FROM tbl_magazine WHERE customer_id = 6 AND status = '1' AND publish_date_to >= CURDATE() ORDER BY title";

        $sqlData = $this->db->query($sql);

        if ($sqlData->num_rows() < 1) {
            return false;
        } else {
            $data = $sqlData->result_array();
            return $data;
        }

    }

    public function getCustomerFilterList() {

        $sql = "SELECT id, name as title FROM tbl_customer WHERE status = '1' ORDER BY name";

        $sqlData = $this->db->query($sql);

        if ($sqlData->num_rows() < 1) {
            return false;
        } else {
            $data = $sqlData->result_array();
            return $data;
        }

    }

    public function getUsersFilterList(){

        $customerId = $this->_id;

        $sql = "SELECT              
                    u.id, CONCAT(u.name, ' (', IF(u.email IS NULL, '', u.email) , ')') as title	
                FROM                
                    tbl_users u";

        if( !empty($customerId) ){
            $sql .= " JOIN tbl_user_magazines um ON( u.id = um.user_id ) ";
        }

        $sql .= " WHERE ";

        if (!empty($customerId)) {

            $sql .= " um.magazine_id IN(  
                                SELECT           
                                id               
                            FROM                
                                tbl_magazine     
                            WHERE               
                                customer_id = $customerId)
                        AND ";

        }

        $sql .= " u.name != ''
                GROUP BY
                    u.id
                ORDER BY
                    u.name";

        $sqlData = $this->db->query($sql);

        if ($sqlData->num_rows() < 1) {
            return false;
        } else {
            $data = $sqlData->result_array();
            return $data;
        }

    }

    public function getUsersIdList(){

        $customerId = $this->_id;

        $sql = "SELECT              
                    u.id,
	                c.company_name
                FROM                
                    tbl_user_magazines um
                    JOIN tbl_users u ON( um.user_id = u.id )
                    JOIN tbl_magazine m ON( um.magazine_id = m.id )
	                JOIN tbl_customer c ON( m.customer_id = c.id )
                WHERE ";

        if (!empty($customerId)) {

            $sql .= " um.magazine_id IN(  
                                SELECT           
                                id               
                            FROM                
                                tbl_magazine     
                            WHERE               
                                customer_id = $customerId)
                        AND ";

        }

        $sql .= " u.name != ''
                GROUP BY
                    u.id
                ORDER BY
                    u.name";

        $sqlData = $this->db->query($sql);

        if ($sqlData->num_rows() < 1) {
            return false;
        } else {
            $data = $sqlData->result_array();
            return $data;
        }

    }

    public function getAutoPublishPermission() {

        $customerId = $this->get_id();

        $sql = "SELECT auto_accept_article FROM tbl_customer WHERE id = {$customerId}";

        $sqlData = $this->db->query($sql);

        if ($sqlData->num_rows() > 0) {

            $data = $sqlData->result_array();

            if( $data[0]["auto_accept_article"] == 1 ){
                return true;
            }

        }

        return false;

    }

    public function getLayoutCustomer() {

        $sql = "SELECT
                    c.id,
                    c.name
                FROM
                    tbl_customer_campaign cc
                    JOIN tbl_customer c ON(cc.id_customer = c.id)
                GROUP BY
                    cc.id_customer
                ORDER BY
                    c.name";

        $sqlData = $this->db->query($sql);

        if ($sqlData->num_rows() < 1) {
            return false;
        } else {
            $data = $sqlData->result_array();
            return $data;
        }

    }

    public function getCustomerCampaigns() {

        $customerId = $this->get_id();

        $sql = "SELECT
                    cc.layout,
                    cc.url,
                    cc.id
                FROM
                    tbl_customer_campaign cc
                WHERE
                    cc.id_customer = $customerId";

        $sqlData = $this->db->query($sql);

        if ($sqlData->num_rows() < 1) {
            return false;
        } else {
            $data = $sqlData->result_array();
            return $data;
        }

    }

    public function isCampaignCreated($layout){

        $customerId = $this->get_id();

        $sql = "SELECT
                    *
                FROM
                    tbl_customer_campaign
                WHERE
                    id_customer = $customerId
                AND  
                    layout = $layout";

        $sqlData = $this->db->query($sql);

        if ($sqlData->num_rows() < 1) {
            return false;
        } else {
            return true;
        }

    }

    public function saveCampaign($campaigns){

        $id = $this->get_id();

        $this->db->query("DELETE FROM tbl_customer_campaign WHERE id_customer = $id;");

        foreach( $campaigns as $k => $url ){

            if( empty( $url ) ){
                continue;
            }

            $tvId = $k + 1;
            $name = "Televisão " . $tvId;
            $this->db->query("INSERT INTO tbl_customer_campaign (id_customer, layout, url) VALUES( $id, 0, '$url' );");
        }

    }

}

?>
