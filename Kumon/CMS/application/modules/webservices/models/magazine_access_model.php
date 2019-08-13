<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class magazine_access_model extends CI_Model {

    private $_tableName = 'tbl_magazine_access';

    private $id = "";
    private $idMagazine = "";
    private $idArticle = "";
    private $dateAccess = "";
    private $idUser = "";
    private $soAccess = "";
    private $typeDeviceAccess = "";
    private $country;
    private $state;
    private $city;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getIdMagazine()
    {
        return $this->idMagazine;
    }

    /**
     * @param string $idMagazine
     */
    public function setIdMagazine($idMagazine)
    {
        $this->idMagazine = $idMagazine;
    }

    /**
     * @return string
     */
    public function getIdArticle()
    {
        return $this->idArticle;
    }

    /**
     * @param string $idArticle
     */
    public function setIdArticle($idArticle)
    {
        $this->idArticle = $idArticle;
    }

    /**
     * @return string
     */
    public function getDateAccess()
    {
        return $this->dateAccess;
    }

    /**
     * @param string $dateAccess
     */
    public function setDateAccess($dateAccess)
    {
        $this->dateAccess = $dateAccess;
    }

    /**
     * @return string
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * @param string $idUser
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;
    }

    /**
     * @return string
     */
    public function getSoAccess()
    {
        return $this->soAccess;
    }

    /**
     * @param string $soAccess
     */
    public function setSoAccess($soAccess)
    {
        $this->soAccess = $soAccess;
    }

    /**
     * @return string
     */
    public function getTypeDeviceAccess()
    {
        return $this->typeDeviceAccess;
    }

    /**
     * @param string $typeDeviceAccess
     */
    public function setTypeDeviceAccess($typeDeviceAccess)
    {
        $this->typeDeviceAccess = $typeDeviceAccess;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    public function insert(){

        $data = array();

        $data['id_magazine'] = $this->idMagazine;
        $data['id_article'] = $this->idArticle;
        $data['date_access'] = $this->dateAccess;
        $data['id_user'] = $this->idUser;
        $data['so_access'] = $this->soAccess;
        $data['type_device_access'] = $this->typeDeviceAccess;
        $data['country'] = $this->country;
        $data['state'] = $this->state;
        $data['city'] = $this->city;

        $inserted = $this->db->set($data)->insert($this->_tableName);

        return $inserted;

    }

    public function getAccessBySo($customerId){
        return $this->getReportData("ma.so_access", "ma.so_access", $customerId);
    }

    public function getAccessBySoAdmin($magazines, $articles, $groups, $locations, $disciplines, $branches){

//        if( $closed ){
            return $this->getReportAdminClosedData("ma.so_access", "ma.so_access", $magazines, $articles, $groups, $locations, $disciplines, $branches);
//        }

//        return $this->getReportAdminData("ma.so_access", "ma.so_access", $magazines, $articles, $groups, $locations, $disciplines);

    }

    public function getAccessByType($customerId){
        return $this->getReportData("ma.type_device_access", "ma.type_device_access",$customerId);
    }

    public function getAccessByTypeAdmin($magazines, $articles, $groups, $locations, $disciplines, $branches){

//        if( $closed ){
            return $this->getReportAdminClosedData("ma.type_device_access", "ma.type_device_access", $magazines, $articles, $groups, $locations, $disciplines, $branches);
//        }

//        return $this->getReportAdminData("ma.type_device_access", "ma.type_device_access", $magazines, $articles, $groups, $locations, $disciplines);

    }

    public function getAccessByState($customerId){
        return $this->getReportData("ma.state", "ma.state", $customerId);
    }

    public function getAccessByStateAdmin($magazines, $articles, $groups, $locations, $disciplines, $branches){

//        if( $closed ){
            return $this->getReportAdminClosedData("ul.city", "ul.city", $magazines, $articles, $groups, $locations, $disciplines, $branches);
//        }

//        return $this->getReportAdminData("ma.state", "ma.state", $magazines, $articles, $groups, $locations, $disciplines);

    }

    public function getAccessByMagazineArticle($customerId){
        return $this->getReportData("maa.title", "ma.id_article", $customerId);
    }

    public function getAccessByMagazineArticleAdmin($magazines, $articles, $groups, $locations, $disciplines, $branches){

//        if( $closed ){
            return $this->getReportAdminClosedData("na.title", "ma.id_article", $magazines, $articles, $groups, $locations, $disciplines, $branches);
//        }

//        return $this->getReportAdminData("na.title", "ma.id_article", $magazines, $articles, $groups, $locations, $disciplines);

    }

    private function getReportData($field, $fieldGroup, $customerId){

        $magazineId = $this->getIdMagazine();
        $articleId = $this->getIdArticle();
        $userId = $this->getIdUser();

        $sql = "SELECT $field, COUNT(ma.id) total 
                FROM 
                    tbl_magazine_access ma
                    JOIN tbl_user_magazines um ON( ma.id_user = um.user_id )
                    JOIN tbl_magazine m ON( um.magazine_id = m.id AND m.customer_id = $customerId AND publish_date_to >= CURDATE() )
                    JOIN tbl_magazine_articles maa ON( ma.id_article = maa.id )
                    WHERE 1=1 ";

        if( !empty( $magazineId ) ){
            $sql .= " AND ma.id_magazine = $magazineId ";
        }

        if( !empty( $articleId ) ){
            $sql .= " AND ma.id_article = $articleId ";
        }

        if( !empty( $userId ) ){
            $sql .= " AND ma.id_user = $userId ";
        }

        $sql .= " GROUP BY $fieldGroup";

        $sqlData = $this->db->query($sql);

        if ($sqlData->num_rows() < 1) {
            return array();
        } else {
            $data = $sqlData->result_array();
            return $data;
        }

    }

    private function getReportAdminData($field, $fieldGroup, $magazines, $articles, $groups, $locations, $disciplines){

        $articleId = $this->getIdArticle();
        $userId = $this->getIdUser();

        $this->load->model("web/customer_model");
        $customerModel = new customer_model();

        $customerModel->set_id($customerId);

        $arrayIds = array();
        $arrayIdUsers = $customerModel->getUsersIdList();

        foreach($arrayIdUsers as $r){
            $arrayIds[] = $r["id"];
        }

        $idUsers = implode(",", $arrayIds);

        if( empty( $idUsers ) ){
            $idUsers = 0;
        }

        $sql = "SELECT 
                    $field, 
                    COUNT(ma.id) total
                 FROM 
                     tbl_magazine_access ma
                     JOIN tbl_new_articles na ON( ma.id_article = na.id AND na.status = '2' AND na.publish_to >= CURDATE() ) ";

        if( !empty( $categoryId ) ){
            $sql .= " JOIN tbl_category c ON( na.category_id = c.id ) ";
        }

        $sql .= " WHERE 1=1 ";

        if( !empty( $categoryId ) ){
            $sql .= " AND c.id = $categoryId ";
        }

        if( !empty( $languageId ) ){
            $sql .= " AND na.article_language = $languageId ";
        }

        if( !empty( $articleId ) ){
            $sql .=" AND ma.id_article = $articleId ";
        }

        if( !empty( $userId ) ){
            $sql .= " AND ma.id_user = $userId ";
        } else if( !empty( $customerId ) ){
            $sql .= " AND ma.id_user IN( $idUsers )";
        }

        if( $field == "na.title" ){

        }

        $sql .= " GROUP BY $fieldGroup";

//        echo $sql; exit;

        $sqlData = $this->db->query($sql);

        if ($sqlData->num_rows() < 1) {
            return array();
        } else {
            $data = $sqlData->result_array();
            return $data;
        }

    }

    private function getReportAdminClosedData($field, $fieldGroup, $magazines, $articles, $groups, $locations, $disciplines, $branches){

        $articleId = $this->getIdArticle();
        $userId = $this->getIdUser();
        $customerId = $this->session->userdata('user_id');

        $this->load->model("web/customer_model");
        $customerModel = new customer_model();

        $sql = "SELECT 
                    $field, 
                    COUNT(ma.id) total
                 FROM 
                     tbl_new_articles na
                     JOIN tbl_magazine_access ma ON( ma.id_article = na.id AND na.status = '2' ) 
                     JOIN tbl_users u ON( ma.id_user = u.id )                     
                     LEFT JOIN tbl_user_location ul ON( u.user_location_id = ul.id )";

        if( !empty($groups) ) {
            $sql .= " JOIN ( SELECT * FROM tbl_article_group WHERE group_id IN($groups) GROUP BY article_id ) ag ON na.id = ag.article_id  ";
        }

        if( !empty($disciplines) ){
            $sql .= " JOIN ( SELECT * FROM tbl_article_discipline WHERE discipline_id IN($disciplines) GROUP BY article_id ) ad ON na.id = ad.article_id ";
        }

        $sql .= " WHERE 1=1 ";

        if( !empty( $customerId ) ){
            $sql .= " AND na.magazine_id IN( SELECT id FROM tbl_magazine WHERE customer_id = $customerId ) ";
        }

        if( !empty( $magazines ) ){
            $sql .= ' AND CONCAT(",", na.magazine_id, ",") REGEXP ",(' . $magazines . ')," ';
        }

        if( !empty( $articles ) ){
            $sql .= ' AND CONCAT(",", na.id, ",") REGEXP ",(' . $articles . ')," ';
        }

        if( !empty($locations) ){
            $sql .= " AND ul.id IN ($locations) ";
        }

        if( !empty($branches) ){
            $arrayBranches = explode(",", $branches);
            $branches = implode("','", $arrayBranches);
            $branchesComplete = '\'' . $branches . '\'';
            $sql .= " AND u.branch IN ($branchesComplete) ";
        }

        $sql .= " GROUP BY $fieldGroup";

//        echo $sql; exit;

        $sqlData = $this->db->query($sql);

        if ($sqlData->num_rows() < 1) {
            return array();
        } else {
            $data = $sqlData->result_array();
            return $data;
        }

    }

    public function getAccessUsers($customerId){

        $magazineId = $this->getIdMagazine();
        $articleId = $this->getIdArticle();
        $userId = $this->getIdUser();
        $customerId = $this->session->userdata('user_id');

        $sql = "SELECT
                    u.name,
                    ma.so_access,
                    ma.type_device_access,
                    ma.country,
                    ma.state,
                    ma.city,
                    u.gender	
                FROM
                    tbl_magazine_access ma
                    JOIN tbl_users u ON( ma.id_user = u.id )
                    JOIN tbl_user_magazines um ON( ma.id_user = um.user_id )
                    JOIN tbl_magazine_articles maa ON( ma.id_article = maa.id )";

        if( !empty( $customerId ) ){
            $sql .= " JOIN tbl_magazine m ON( um.magazine_id = m.id AND m.customer_id = $customerId AND publish_date_to >= CURDATE() ) ";
        }

        $sql .= " WHERE 1=1 ";

        if( !empty( $customerId ) ){
            $sql .= " AND na.customer_id = $customerId ";
        }

        if( !empty( $magazineId ) ){
            $sql .= " AND ma.id_magazine = $magazineId ";
        }

        if( !empty( $articleId ) ){
            $sql .= " AND ma.id_article = $articleId ";
        }

        if( !empty( $userId ) ){
            $sql .= " AND ma.id_user = $userId ";
        }

        $sql .= " ORDER BY
                    u.name, ma.so_access, ma.type_device_access, ma.country, ma.state, ma.city";

        $sqlData = $this->db->query($sql);

        if ($sqlData->num_rows() < 1) {
            return array();
        } else {
            $data = $sqlData->result_array();
            return $data;
        }

    }

//    public function getPublicAccessUsers($magazines, $articles, $groups, $locations, $disciplines){
//
//        $articleId = $this->getIdArticle();
//        $userId = $this->getIdUser();
//
//        $this->load->model("web/customer_model");
//        $customerModel = new customer_model();
//
//        $customerModel->set_id($customerId);
//
//        $arrayIds = array();
//        $arrayIdUsers = $customerModel->getUsersIdList();
//
//        foreach($arrayIdUsers as $r){
//            $arrayIds[] = $r["id"];
//        }
//
//        $idUsers = implode(",", $arrayIds);
//
//        if( empty( $idUsers ) ){
//            $idUsers = 0;
//        }
//
//        $sql = "SELECT
//                    u.name,
//                    ma.so_access,
//                    ma.type_device_access,
//                    ma.country,
//                    ma.state,
//                    ma.city
//                 FROM
//                     tbl_magazine_access ma
//                     JOIN tbl_users u ON( ma.id_user = u.id )
//                     JOIN tbl_new_articles na ON( ma.id_article = na.id AND na.status = '2' AND na.publish_to >= CURDATE() ) ";
//
//        if( !empty( $categoryId ) ){
//            $sql .= " JOIN tbl_category c ON( na.category_id = c.id ) ";
//        }
//
//        $sql .= " WHERE 1=1 ";
//
//        if( !empty( $categoryId ) ){
//            $sql .= " AND c.id = $categoryId ";
//        }
//
//        if( !empty( $languageId ) ){
//            $sql .= " AND na.article_language = $languageId ";
//        }
//
//        if( !empty( $articleId ) ){
//            $sql .=" AND ma.id_article = $articleId ";
//        }
//
//        if( !empty( $userId ) ){
//            $sql .= " AND ma.id_user = $userId ";
//        } else if( !empty( $customerId ) ){
//            $sql .= " AND ma.id_user IN( $idUsers )";
//        }
//
//        $sqlData = $this->db->query($sql);
//
//        if ($sqlData->num_rows() < 1) {
//            return array();
//        } else {
//            $data = $sqlData->result_array();
//            return $data;
//        }
//
//    }

    public function getClosedAccessUsers($magazines, $articles, $groups, $locations, $disciplines, $branches){

        $articleId = $this->getIdArticle();
        $userId = $this->getIdUser();
        $customerId = $this->session->userdata('user_id');

        $this->load->model("web/customer_model");
        $customerModel = new customer_model();

        $arrayIds = array();
        $arrayIdUsers = $customerModel->getUsersIdList();

        foreach($arrayIdUsers as $r){
            $arrayIds[] = $r["id"];
        }

        $idUsers = implode(",", $arrayIds);

        if( empty( $idUsers ) ){
            $idUsers = 0;
        }

        $sql = "SELECT 
                    u.email name,
                    ma.so_access,
                    ma.type_device_access,
                    ul.country,
                    ul.state,
                    ul.city
                 FROM 
                     tbl_magazine_access ma
                     JOIN tbl_users u ON( ma.id_user = u.id )
                     JOIN tbl_new_articles na ON( ma.id_article = na.id AND na.status = '2' ) 
                     LEFT JOIN tbl_user_location ul ON( u.user_location_id = ul.id )";

        if( !empty($groups) ) {
            $sql .= " JOIN ( SELECT * FROM tbl_article_group WHERE group_id IN($groups) GROUP BY article_id ) ag ON na.id = ag.article_id  ";
        }

        if( !empty($disciplines) ){
            $sql .= " JOIN ( SELECT * FROM tbl_article_discipline WHERE discipline_id IN($disciplines) GROUP BY article_id ) ad ON na.id = ad.article_id ";
        }

        $sql .= " WHERE 1=1 ";

        if( !empty( $customerId ) ){
            $sql .= " AND na.magazine_id IN( SELECT id FROM tbl_magazine WHERE customer_id = $customerId ) ";
        }

        if( !empty( $magazines ) ){
            $sql .= ' AND CONCAT(",", na.magazine_id, ",") REGEXP ",(' . $magazines . ')," ';
        }

        if( !empty( $articles ) ){
            $sql .= ' AND CONCAT(",", na.id, ",") REGEXP ",(' . $articles . ')," ';
        }

        if( !empty($locations) ){
            $sql .= " AND ul.id IN ($locations) ";
        }

        if( !empty($branches) ){
            $arrayBranches = explode(",", $branches);
            $branches = implode("','", $arrayBranches);
            $branchesComplete = '\'' . $branches . '\'';
            $sql .= " AND u.branch IN ($branchesComplete) ";
        }

        if( !empty( $userId ) ){
            $sql .= " AND ma.id_user = $userId ";
        } else if( !empty( $customerId ) ){
            $sql .= " AND ma.id_user IN( $idUsers )";
        }

//        echo $sql; exit;

        $sqlData = $this->db->query($sql);

        if ($sqlData->num_rows() < 1) {
            return array();
        } else {
            $data = $sqlData->result_array();
            return $data;
        }

    }

    public function getClientAccess($categoryId, $languageId, $customerId) {

        $articleId = $this->getIdArticle();
        $userId = $this->getIdUser();

        $sql = "SELECT
                    c.id,
                    c.name,
                    (
                        SELECT
                            COUNT(ma.id)
                        FROM
                            tbl_magazine_access ma
                            JOIN tbl_new_articles na ON(ma.id_article = na.id AND na.status = '2' AND na.publish_to >= CURDATE())";

        if( !empty( $categoryId ) ){
            $sql .= " JOIN tbl_category ca ON( na.category_id = ca.id ) ";
        }

        $sql .= "        WHERE
                            ma.id_user IN(
                                SELECT
                                    u.id
                                FROM
                                    tbl_user_magazines um
                                    JOIN tbl_magazine m ON( um.magazine_id = m.id )
                                    JOIN tbl_customer c2 ON( m.customer_id = c2.id )
                                    JOIN tbl_users u ON( um.user_id = u.id )
                                WHERE
                                    c2.id = c.id
                                GROUP BY
                                    um.user_id
                            )";

        if( !empty( $categoryId ) ){
            $sql .= " AND ca.id = $categoryId ";
        }

        if( !empty( $languageId ) ){
            $sql .= " AND na.article_language = $languageId ";
        }

        if( !empty( $articleId ) ){
            $sql .=" AND ma.id_article = $articleId ";
        }

        if( !empty( $userId ) ){
            $sql .= " AND ma.id_user = $userId ";
        }

        $sql .= " ) total
                FROM
                    tbl_customer c
                WHERE
                    status = '1'";

        if( !empty( $customerId ) ){
            $sql .= " AND c.id = $customerId ";
        }

        $sql .= "HAVING
                    total > 0";

        $sqlData = $this->db->query($sql);

        if ($sqlData->num_rows() < 1) {
            return array();
        } else {
            $data = $sqlData->result_array();
            return $data;
        }

    }

    public function getClosedClientAccess($magazineId, $languageId, $customerId)
    {

        $articleId = $this->getIdArticle();
        $userId = $this->getIdUser();

        $sql = "SELECT
                    c.id,
                    c.name,
                    (
                        SELECT
                            COUNT(ma.id)
                        FROM
                            tbl_magazine_access ma
                            JOIN tbl_magazine_articles na ON(ma.id_article = na.id AND na.status = '2' AND na.publish_to >= CURDATE())";

        $sql .= "        WHERE
                            ma.id_user IN(
                                SELECT
                                    u.id
                                FROM
                                    tbl_user_magazines um
                                    JOIN tbl_magazine m ON( um.magazine_id = m.id )
                                    JOIN tbl_customer c2 ON( m.customer_id = c2.id )
                                    JOIN tbl_users u ON( um.user_id = u.id )
                                WHERE
                                    c2.id = c.id
                                GROUP BY
                                    um.user_id
                            )";

        if (!empty($magazineId)) {
            $sql .= " AND FIND_IN_SET( $magazineId, na.magazine_id ) > 0 ";
        }

        if (!empty($languageId)) {
            $sql .= " AND na.article_language = $languageId ";
        }

        if (!empty($articleId)) {
            $sql .= " AND ma.id_article = $articleId ";
        }

        if (!empty($userId)) {
            $sql .= " AND ma.id_user = $userId ";
        }

        $sql .= " ) total
                FROM
                    tbl_customer c
                WHERE
                    status = '1'";

        if (!empty($customerId)) {
            $sql .= " AND c.id = $customerId ";
        }

        $sql .= "HAVING
                    total > 0";

        $sqlData = $this->db->query($sql);

        if ($sqlData->num_rows() < 1) {
            return array();
        } else {
            $data = $sqlData->result_array();
            return $data;
        }

    }

}
