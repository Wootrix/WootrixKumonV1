<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class super_admin_model extends CI_Model {
   
        
    private $_tableName = 'tbl_admin';
    private $_id = "";
    private $_name = "";
    private $_email = "";
    private $_password = "";
    private $_role = "";
    private $_gender = "";
    private $_mobile = "";
    private $_work_phone = "";
    private $_address = "";
    private $_dob = "";
    private $_country = "";
    private $_city = "";
    private $_permission1 = "";
    private $_permission2 = "";
    private $_permission3 = "";
    private $_permission4 = "";
    private $_permission5 = "";
    private $_permission6 = "";
    private $_company_name = "";
    private $_status = "";
    private $_created_date = "";
    private $_image = "";
    private $_user_name = "";
    private $_language_code = "";
    private $_permission7 = "";
    private $_permission8 = "";

    /* TBL ADMIN GETTER AND SETTER */

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

    public function get_permission() {
        return $this->_permission;
    }

    public function set_permission($_permission) {
        $this->_permission = $_permission;
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

    /* permission */

    public function get_permission1() {
        return $this->_permission1;
    }

    public function set_permission1($_permission1) {
        $this->_permission1 = $_permission1;
    }

    public function get_permission2() {
        return $this->_permission2;
    }

    public function set_permission2($_permission2) {
        $this->_permission2 = $_permission2;
    }

    public function get_permission3() {
        return $this->_permission3;
    }

    public function set_permission3($_permission3) {
        $this->_permission3 = $_permission3;
    }

    public function get_permission4() {
        return $this->_permission4;
    }

    public function set_permission4($_permission4) {
        $this->_permission4 = $_permission4;
    }

    public function get_permission5() {
        return $this->_permission5;
    }

    public function set_permission5($_permission5) {
        $this->_permission5 = $_permission5;
    }

    public function get_permission6() {
        return $this->_permission6;
    }

    public function set_permission6($_permission6) {
        $this->_permission6 = $_permission6;
    }
    
     public function get_language_code() {
        return $this->_language_code;
    }

    public function set_language_code($_language_code) {
        $this->_language_code = $_language_code;
    }
    
     public function get_permission7() {
        return $this->_permission7;
    }

    public function set_permission7($_permission7) {
        $this->_permission7 = $_permission7;
    }

    public function get_permission8() {
        return $this->_permission8;
    }

    public function set_permission8($_permission8) {
        $this->_permission8 = $_permission8;
    }



    /* GET ADMIN LOGIN */

    /**
     * used for admin login
     * superadmin and admin
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Index function load defaut 
     * */
    public function adminLogin() {

        $user_name = $this->get_user_name();
        $password = $this->get_password();

        $sql = $this->db->query("SELECT id,name,role,image,user_name,language_code FROM tbl_admin WHERE user_name = '$user_name' AND password = md5('$password')
                AND status='1'");

        //echo $this->db->last_query(); die;

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->row_array();
        }
    }

    /* GET PERMISSION RESULT */

    /**
     * used for get permission
     * array or listing     
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Index function load defaut 
     * */
    public function getPermissionList() {

        $sql = $this->db->query("SELECT id,permission_type FROM tbl_permission");

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->result_array();
        }
    }

    /* GET ADMIN AND SUPER ADMIN LIST */

    /**
     * used for get admin
     * listing     
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * Index function load defaut 
     * */
    public function getAdminList() {

        $permission = $this->get_permission();
        $name = $this->get_name();
        if ($permission != "") { /* SORT BY PERMISSION QUERY */

            $sql = $this->db->query("SELECT id,permission1,permission2,permission3,permission4,
            permission5,permission6,permission7,permission8, image,name,role,email,gender,mobile,name,
            work_phone,address,dob,country,city,company_name,user_name FROM tbl_admin 
            WHERE permission$permission = '1' AND status='1'");
        } elseif ($name != "") { /* SEARCH BY NAME QUERY */
            $sql = $this->db->query("SELECT id,permission1,permission2,permission3,permission4,
            permission5,permission6,permission7,permission8,image,name,role,email,gender,mobile,name
            work_phone,address,dob,country,city,company_name,user_name FROM tbl_admin 
            WHERE name Like '%$name%' AND status='1'");
        } else { /* NORMAL ADMIN AND SUPER ADMIN LISTING */
            $sql = $this->db->query("SELECT id,permission1,permission2,permission3,permission4,
            permission5,permission6,permission7,permission8,image,name,role,email,gender,mobile,name
            work_phone,address,dob,country,city,company_name,user_name FROM tbl_admin WHERE status='1'");
        }
        //echo $this->db->last_query();


        /* PAGINATION */
        $config['base_url'] = base_url() . 'index.php/' . 'admin?perId='.$permission;
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
        /* --------------------------- */
        
        if ($permission != "") { /* SORT BY PERMISSION QUERY */

            $data_sql = $this->db->query("SELECT id,permission1,permission2,permission3,permission4,
            permission5,permission6,permission7,permission8,image,name,role,email,gender,mobile,name,
            work_phone,address,dob,country,city,company_name,user_name FROM tbl_admin 
            WHERE permission$permission = '1' AND status='1' ORDER BY id DESC LIMIT $limitPage,10 ");
        } elseif ($name != "") { /* SEARCH BY NAME QUERY */
            $data_sql = $this->db->query("SELECT id,permission1,permission2,permission3,permission4,
            permission5,permission6,permission7,permission8,image,name,role,email,gender,mobile,name
            work_phone,address,dob,country,city,company_name,user_name FROM tbl_admin 
            WHERE name Like '%$name%' AND status='1' ORDER BY id DESC LIMIT $limitPage,10 ");
        } else { /* NORMAL ADMIN AND SUPER ADMIN LISTING */
            $data_sql = $this->db->query("SELECT id,permission1,permission2,permission3,permission4,
            permission5,permission6,permission7,permission8,image,name,role,email,gender,mobile,name
            work_phone,address,dob,country,city,company_name,user_name FROM tbl_admin 
            WHERE status='1' ORDER BY id DESC LIMIT $limitPage,10 ");
        }

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $data_sql->result_array();
        }
    }

    /* GET PERTICULAR ADMIN DETAILS */

    /**
     * used for get admin
     * details      
     * @author Techahead
     * @access Public
     * @param  = admin id
     * @return = array of details
     * Index function load defaut 
     * */
    public function adminInformation() {

        $admin_id = $this->get_id();

        $sql = $this->db->query("SELECT id,permission1,permission2,permission3,permission4,
            permission5,permission6,permission7,permission8,image,name,role,email,gender,mobile,password,name,
            work_phone,address,dob,country,city,company_name,user_name FROM tbl_admin WHERE id ='$admin_id' ");
        //echo$this->db->last_query();
        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->row_array();
        }
    }

    /* DELETE ADMIN */

    /**
     * used for delete
     * a admin    
     * @author Techahead
     * @access Public
     * @param  = admin id
     * @return = true/false
     * Index function load defaut 
     * */
    public function deleteAdmin() {

        $admin_id = $this->get_id();
        $sql = $this->db->query("UPDATE tbl_admin SET status='2' WHERE id ='$admin_id'");
        //echo $this->db->last_query();die;
        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /* ADD SUB ADMIN */

    /**
     * used for add new
     * admin       
     * @author Techahead
     * @access Public
     * @param  = admin id
     * @return = true/false
     * Index function load defaut 
     * */
    public function InsertUserDetails() {

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
        $password = $this->get_password();
        $permission1 = $this->get_permission1();
        $country = $this->get_country();

        if ($permission1 == "") {
            $permission1 = 0;
        }
        $permission2 = $this->get_permission2();
        if ($permission2 == "") {
            $permission2 = 0;
        }
        $permission3 = $this->get_permission3();
        if ($permission3 == "") {
            $permission3 = 0;
        }
        $permission4 = $this->get_permission4();
        if ($permission4 == "") {
            $permission4 = 0;
        }
        $permission5 = $this->get_permission5();
        if ($permission5 == "") {
            $permission5 = 0;
        }
        $permission6 = $this->get_permission6();
        if ($permission6 == "") {
            $permission6 = 0;
        }
        $permission7 = $this->get_permission7();
        if ($permission7 == "") {
            $permission7 = 0;
        }
        $permission8 = $this->get_permission8();
        if ($permission8 == "") {
            $permission8 = 0;
        }
        $image = $this->get_image();


        $data = array('name' => $name, 'email' => $email, 'password' => md5($password), 'gender' => $gender, 'mobile' => $mobile, 'user_name' => $user_name,
            'work_phone' => $work_phone, 'address' => $address, 'dob' => $dob, 'country' => $country, 'city' => $city, 'company_name' => $company_name,
            'permission1' => $permission1, 'permission2' => $permission2, 'permission3' => $permission3, 'permission4' => $permission4, 'permission5' => $permission5,
            'permission6' => $permission6, 'permission7' => $permission7, 'permission8' => $permission8, 'image' => $image,'role'=>'2');
        $sql = $this->db->set('created_date', 'now()', FALSE)->insert('tbl_admin', $data);
        //echo $this->db->last_query();
        if ($sql) {
            return TRUE;
        }
        return FALSE;
    }

    public function UpdateUserDetails() {

        $admin_id = $this->get_id();
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
        $password = $this->get_password();
        $permission1 = $this->get_permission1();
        $country = $this->get_country();

        if ($permission1 == "") {
            $permission1 = 0;
        }
        $permission2 = $this->get_permission2();
        if ($permission2 == "") {
            $permission2 = 0;
        }
        $permission3 = $this->get_permission3();
        if ($permission3 == "") {
            $permission3 = 0;
        }
        $permission4 = $this->get_permission4();
        if ($permission4 == "") {
            $permission4 = 0;
        }
        $permission5 = $this->get_permission5();
        if ($permission5 == "") {
            $permission5 = 0;
        }
        $permission6 = $this->get_permission6();
        if ($permission6 == "") {
            $permission6 = 0;
        }
        $permission7 = $this->get_permission7();
        if ($permission7 == "") {
            $permission7 = 0;
        }
        $permission8 = $this->get_permission8();
        if ($permission8 == "") {
            $permission8 = 0;
        }
        $image = $this->get_image();
        if ($image != "") {
            $data = array('name' => $name, 'email' => $email,'gender' => $gender, 'mobile' => $mobile, 'user_name' => $user_name,
                'work_phone' => $work_phone, 'address' => $address, 'dob' => $dob, 'country' => $country, 'city' => $city, 'company_name' => $company_name,
                'permission1' => $permission1, 'permission2' => $permission2, 'permission3' => $permission3, 'permission4' => $permission4, 'permission5' => $permission5,
                'permission6' => $permission6,'permission7' => $permission7, 'permission8' => $permission8,'image' => $image);
        } else {
            $data = array('name' => $name, 'email' => $email,'gender' => $gender, 'mobile' => $mobile, 'user_name' => $user_name,
                'work_phone' => $work_phone, 'address' => $address, 'dob' => $dob, 'country' => $country, 'city' => $city, 'company_name' => $company_name,
                'permission1' => $permission1, 'permission2' => $permission2, 'permission3' => $permission3, 'permission4' => $permission4, 'permission5' => $permission5,
                'permission6' => $permission6,'permission7' => $permission7, 'permission8' => $permission8, );
        }
        
        if($password !=""){
           $data1 = array('password' => md5($password));
           $sql2 = $this->db->set('created_date', 'now()', FALSE)->
                        where('id', $admin_id)->update('tbl_admin', $data1);
        }


        $sql = $this->db->set('created_date', 'now()', FALSE)->
                        where('id', $admin_id)->update('tbl_admin', $data);
        //echo $this->db->last_query();
        if ($sql) {
            return TRUE;
        }
        return FALSE;
    }

    /* USER NAME EXIST CHECK */

    /**
     * check for unique
     * user name      
     * @author Techahead
     * @access Public
     * @param  = admin id
     * @return = true/false
     * Index function load defaut 
     * */
    public function userNameExistCheck() {

        $user_name = $this->get_user_name();
        //$sql = $this->db->query("SELECT id FROM tbl_admin WHERE user_name = '$user_name'");
         $sql = $this->db->query("SELECT user_name FROM tbl_admin WHERE user_name = '$user_name'");
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
         $sql = $this->db->query("SELECT email FROM tbl_admin WHERE email = '$user_email'");
        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    
     /**
     * check for unique
     * check other username exist      
     * @author Techahead
     * @access Public
     * @param  = admin id
     * @return = true/false
     * Index function load defaut 
     * */

    public function perticularUserNameExistCheck() {

        $user_name = $this->get_user_name();
        $user_id = $this->get_id();
        $sql = $this->db->query("SELECT id FROM tbl_admin WHERE user_name = '$user_name' And id !=$user_id");
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
        
         $sql = $this->db->query("SELECT email FROM tbl_admin WHERE email = '$user_email' And id !=$user_id");
        //echo $this->db->last_query(); die;
        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    /*check user exist*/
    
    public function checkUserExist(){
       //$user_name = $this->get_user_name();
       $email = $this->get_email();
        $sql = $this->db->query("SELECT id FROM tbl_admin WHERE email = '$email'");
        //echo $this->db->last_query(); die;
        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return TRUE;
        }   
        
    }
    
    /*upadta as forgot password*/
    
    public function UpdateForgotPassword(){
       $user_name = $this->get_user_name();
       $email = $this->get_email();
       $password = $this->get_password();
       
       $sql = $this->db->query("UPDATE tbl_admin SET password = md5($password) WHERE user_name = '$user_name' And email = $email");
        //echo $this->db->last_query(); die;
       if ($sql) {
            return TRUE;
        } else {
            return FALSE;
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
        $image = $this->get_image();
        $country = $this->get_country();
        
        if($image !=""){
            $sql = $this->db->query("UPDATE tbl_admin SET name='$name',email= '$email',gender='$gender',
                mobile='$mobile', work_phone='$work_phone', address='$address', dob='$dob',
                country='$country', city='$city', company_name='$company_name',image='$image' WHERE id='$user_id'");
            
        }else{
            $sql = $this->db->query("UPDATE tbl_admin SET name='$name',email= '$email', gender='$gender',
                mobile='$mobile',  work_phone='$work_phone', address='$address', dob='$dob',
                country='$country', city='$city', company_name='$company_name' WHERE id='$user_id'");            
        }        
       //echo $this->db->last_query(); die;
        if ($sql) {
            return TRUE;            
        }
        return FALSE;
        
    }
    
    /*for change Password*/
    
    public function checkUserAndPassword(){
        
        $user_name = $this->get_user_name();
        $user_password = $this->get_password();
        $user_id = $this->get_id();
        $sql = $this->db->query("SELECT id FROM tbl_admin WHERE user_name = '$user_name' And password=md5('$user_password')");
        //echo $this->db->last_query(); die;
        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return TRUE;
        } 
      
    }
    
    /*UPDATE CUSTOMER PASSWORD*/
    public function UpdateCustomerPassword(){        
        $user_id = $this->get_id();
        $password = $this->get_password();
        
        $sql = $this->db->query("UPDATE tbl_admin SET  password=md5('$password') WHERE id='$user_id'");
        
        if ($sql) {
            return TRUE;            
        }
        return FALSE;
    }
    
      /* GET PERTICULAR CUSTOMER DETAILS */

    public function customerInformation() {

        $customer_id = $this->get_id();

        $sql = $this->db->query("SELECT id,image,name,role,email,gender,mobile, work_phone,address,dob,country,
           city,company_name,user_name FROM tbl_admin WHERE id ='$customer_id' ");
        //echo$this->db->last_query();
        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->row_array();
        }
    }
    
    /*Getting All reviw article count*/
    
    public function getMagazineReviewArticle(){
        
        $sql = $this->db->query("SELECT id FROM tbl_magazine_articles WHERE status='1'");

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->num_rows();
        }
    }
    
    /*Getting all review ads*/
    
    public function getNewAdvertise(){
        
       // $sql = $this->db->query("SELECT id,cover_image,media_type,size,link,magazine_id,language_id,status,layout_type,is_approved FROM tbl_magazine_advertisement WHERE status='1' 
        //        AND is_approved = '0'");
        
        $sql = $this->db->query("SELECT id FROM tbl_new_articles WHERE status ='1'");
         if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->num_rows();
        }
    }
     
    /*Getting all deleted History*/
    
    public function getAllDeletedMagazine(){
        
         $sql = $this->db->query("SELECT a.id,a.title,a.cover_image,a.status,a.publish_date_from,a.publish_date_to,a.subscription_password,
            b.name,c.language FROM tbl_magazine as a INNER JOIN tbl_customer as b ON a.customer_id = b.id Inner Join tbl_language as c ON
            a.language_id = c.id WHERE a.badge_read_status ='0' AND (a.status = '2' Or a.publish_date_to < CURDATE())");
               
         if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->num_rows();
        }
        
    }
    
    /*GET ALL REVIEW ADS */
    
    public function allReviewAdsCount(){
        
        $sql = $this->db->query("SELECT id,cover_image,media_type,size,link,magazine_id,language_id,status,layout_type,is_approved FROM tbl_magazine_advertisement WHERE status='1' 
                AND is_approved = '0'");
        
         if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->num_rows();
        }     
    }
    
    /*GET ALL MAGAZINE ARTICLE REVIEW COUNT*/
    
    public function getAllReviewMagazineArticle(){
        
         $sql = $this->db->query("SELECT id,magazine_id FROM tbl_magazine_articles WHERE status='1' AND publish_to > CURDATE() AND magazine_id!='' UNION SELECT id,magazine_id FROM tbl_new_articles WHERE status='1' AND publish_to > CURDATE() AND magazine_id!=''"); 
         if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->result_array();
        }
    }

}

?>
