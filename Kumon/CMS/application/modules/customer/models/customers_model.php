<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class customers_model extends CI_Model {
    
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
    private $_dob = "";
    private $_country = "";
    private $_city = "";
    private $_company_name = "";
    private $_status = "";
    private $_created_date = "";
    private $_image = "";
    private $_user_name = "";

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
    public function customerLogin() {

        $user_name = $this->get_user_name();
        $password = $this->get_password();

        $sql = $this->db->query("SELECT id,name,role,image,user_name,language_code FROM tbl_customer WHERE user_name = '$user_name' AND password = md5('$password')
                AND status='1'");

        //echo $this->db->last_query(); die;

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->row_array();
        }
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
    public function customerBlockStatus() {

        $user_name = $this->get_user_name();
        $password = $this->get_password();

        $sql = $this->db->query("SELECT id,name,role,image,user_name,language_code FROM tbl_customer WHERE user_name = '$user_name' AND password = md5('$password')
                AND status='0'");

        //echo $this->db->last_query(); die;

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    
    /* GET PERTICULAR CUSTOMER DETAILS */

    public function customerInformation() {

        $customer_id = $this->get_id();

        $sql = $this->db->query("SELECT id,image,name,role,email,gender,mobile, work_phone,address,dob,country,
           city,company_name,user_name FROM tbl_customer WHERE id ='$customer_id' ");
        //echo$this->db->last_query();
        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->row_array();
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
    
    /*for change Password*/
    
    public function checkUserAndPassword(){
        
        $user_name = $this->get_user_name();
        $user_password = $this->get_password();
        $user_id = $this->get_id();
        $sql = $this->db->query("SELECT id FROM tbl_customer WHERE user_name = '$user_name' And password=md5('$user_password')");
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
        
        $sql = $this->db->query("UPDATE tbl_customer SET  password=md5('$password') WHERE id='$user_id'");
        
        if ($sql) {
            return TRUE;            
        }
        return FALSE;
        
        
        
        
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
        $image = $this->get_image();
        $country = $this->get_country();
        
        if($image !=""){
            $sql = $this->db->query("UPDATE tbl_customer SET name='$name',email= '$email',gender='$gender',
                mobile='$mobile', work_phone='$work_phone', address='$address', dob='$dob',
                country='$country', city='$city', company_name='$company_name',image='$image' WHERE id='$user_id'");
            
        }else{
            $sql = $this->db->query("UPDATE tbl_customer SET name='$name',email= '$email', gender='$gender',
                mobile='$mobile',work_phone='$work_phone', address='$address', dob='$dob',
                country='$country', city='$city', company_name='$company_name' WHERE id='$user_id'");            
        }        
       
        if ($sql) {
            return TRUE;            
        }
        return FALSE;
        
    }
    
    /*check user exist*/
    
    public function checkUserExist(){
       $user_name = $this->get_user_name();
       $email = $this->get_email();
        $sql = $this->db->query("SELECT id FROM tbl_customer WHERE email = '$email'");
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
       
       $sql = $this->db->query("UPDATE tbl_customer SET password = md5($password) WHERE user_name = '$user_name' And email = $email");
        //echo $this->db->last_query(); die;
       if ($sql) {
            return TRUE;
        } else {
            return FALSE;
        } 
    }
    
    
     /* All REVIEW ADVERTISE for customer */

    public function allReviewAdvertise() {

        $customer_id = $this->get_id();
        $sql = $this->db->query("SELECT id,cover_image,media_type,size,link,catagory_id,language_id,status,layout_type,is_approved FROM tbl_magazine_advertisement WHERE status='1'
            AND is_approved !='1' AND customer_id = '$customer_id'");
        return $sql->num_rows();
    }
    
    /**
     * @author Techahead
     * @access Public
     * @param 
     * @return 
     * true/false
     * */
    public function getCustomerReviewArticle() {
        $customer_id = $this->get_id();
        $sql = $this->db->query("SELECT id,magazine_id FROM tbl_magazine_articles WHERE customer_id = '$customer_id' AND publish_to > CURDATE() AND ( status='1') AND magazine_id!='' ");

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->row_array();
        }
    }

    public function hasCampaign($campaignId) {

        $id = $this->get_id();

        $sql = "SELECT
                    *
                FROM
                    tbl_customer_campaign cc
                WHERE
                    id_customer = $id
                AND
                    id = $campaignId";

        $sqlData = $this->db->query($sql);

        if ($sqlData->num_rows() < 1) {
            return false;
        } else {
            return true;
        }

    }

    public function hasCampaignGroup($groupId) {

        $id = $this->get_id();

        $sql = "SELECT
                    *
                FROM
                    tbl_customer_campaign_group
                WHERE
                    id_customer = $id
                AND
                    id = $groupId";

        $sqlData = $this->db->query($sql);

        if ($sqlData->num_rows() < 1) {
            return false;
        } else {
            return true;
        }

    }

    public function getCampaigns() {

        $id = $this->get_id();

        $sql = "SELECT
                    cc.id,
                    ccg.name,
                    ccg.id as id_group,
                    cc.banner1,
                    cc.banner2                 
                FROM
                    tbl_customer_campaign cc
                    LEFT JOIN tbl_customer_campaign_group ccg ON( cc.id_group = ccg.id )
                WHERE
                    cc.id_customer = $id
                ORDER BY
                    cc.id";

        $sqlData = $this->db->query($sql);

        if ($sqlData->num_rows() < 1) {
            return false;
        } else {
            $data = $sqlData->result_array();
            return $data;
        }

    }

    public function getCampaign($campaignId) {

        $sql = "SELECT
                    *
                FROM
                    tbl_customer_campaign cc
                WHERE
                    id = $campaignId";

        $sqlData = $this->db->query($sql);

        if ($sqlData->num_rows() < 1) {
            return false;
        } else {
            $data = $sqlData->row_array();
            return $data;
        }

    }

    public function getCampaignGroup($groupId) {

        $sql = "SELECT
                    *
                FROM
                    tbl_customer_campaign_group
                WHERE
                    id = $groupId";

        $sqlData = $this->db->query($sql);

        if ($sqlData->num_rows() < 1) {
            return false;
        } else {
            $data = $sqlData->row_array();
            return $data;
        }

    }

    public function getGroupCampaings($groupId) {

        $sql = "SELECT
                    *
                FROM
                    tbl_customer_campaign
                WHERE
                    id_group = $groupId";

        $sqlData = $this->db->query($sql);

        if ($sqlData->num_rows() < 1) {
            return false;
        } else {
            $data = $sqlData->result_array();
            return $data;
        }

    }

    public function getCampaignContent($campaignId) {

        $sql = "SELECT
                  *
                FROM
                  tbl_customer_campaign_content cc
                WHERE
                  id_campaign = $campaignId
                ORDER BY
                    id_magazine";

        $sqlData = $this->db->query($sql);

        if ($sqlData->num_rows() < 1) {
            return false;
        } else {
            $data = $sqlData->result_array();
            return $data;
        }

    }

    public function saveCampaign($layout, $idGroup = "", $idCampaign = "", $banner1 = "", $banner2 = ""){

        $idCustomer = $this->get_id();

        if( !empty( $idCampaign ) ){
            $this->db->query("UPDATE tbl_customer_campaign SET layout = $layout, id_group = $idGroup, banner1 = '$banner1', banner2 = '$banner2' WHERE id = $idCampaign");
        } else {

            $post_data = array(
                'id_customer'   =>  $idCustomer,
                'layout' => $layout,
                'id_group' => $idCampaign,
                'banner1' => $banner1,
                'banner2' => $banner2
            );

            $this->db->insert('tbl_customer_campaign', $post_data);
            $idCampaign = $this->db->insert_id();

        }

        return $idCampaign;

    }

    public function saveCampaignGroup($name, $campaigns, $copyCampaign, $campaignGroupId){

        $idCustomer = $this->get_id();

        if( !empty( $campaignGroupId ) ){
            $this->db->query("UPDATE tbl_customer_campaign_group SET name = '$name' WHERE id = $campaignGroupId");
        } else {

            $post_data = array(
                'id_customer'   =>  $idCustomer,
                'name'   =>  $name
            );

            $this->db->insert('tbl_customer_campaign_group', $post_data);
            $campaignGroupId = $this->db->insert_id();

        }

        $arrayData = array();
        $layout = 0;
        $banner1 = "";
        $banner2 = "";

        if( !empty( $copyCampaign ) ) {

            $campaignToCopy = $this->getCampaign($copyCampaign);
            $content = $this->getCampaignContent($copyCampaign);

            $layout = $campaignToCopy["layout"];
            $banner1 = $campaignToCopy["banner1"];
            $banner2 = $campaignToCopy["banner2"];

            foreach ($content as $article) {
                $arrayData[$article["id_magazine"]][] = $article["id_article"];
            }

        }

        foreach( $campaigns as $campaign ){

            $this->db->query("DELETE FROM tbl_customer_campaign_content WHERE id_campaign = $campaign;");

            $this->saveCampaign($layout, $campaignGroupId, $campaign, $banner1, $banner2);

            if( !empty( $copyCampaign ) ) {
                $this->saveCampaignContent($campaign, $arrayData);
            }

        }

        return $campaignGroupId;

    }

    public function saveCampaignContent($idCampaign, $dataArray){

//        echo "<pre>";
//
//        print_r($dataArray); exit;

        if( !empty( $idCampaign ) ){
            $this->db->query("DELETE FROM tbl_customer_campaign_content WHERE id_campaign = $idCampaign;");
        }

        foreach( $dataArray as $idMagazine => $articles ){

            foreach( $articles as $idArticle ){
                $this->db->query("INSERT INTO tbl_customer_campaign_content (id_magazine, id_article, id_campaign) VALUES( $idMagazine, $idArticle, $idCampaign );");
            }

        }

    }

}

?>
