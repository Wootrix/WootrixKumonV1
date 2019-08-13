<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class group_model extends CI_Model {

    public $id = "";
    public $group = "";

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getGroup()
    {
        return $this->group;
    }

    public function setGroup($group)
    {
        $this->group = $group;
    }

    public function getById($id) {

        $sql = $this->db->query("SELECT * FROM tbl_group WHERE id = " . $id);

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->row_array();
        }

    }

    public function getAll() {

        $sql = $this->db->query("SELECT * FROM tbl_group");

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->result_array();
        }

    }

    public function addNewGroup($location = []){

        $data = array('group' => $this->getGroup());
        $sql = $this->db->insert('tbl_group', $data);

        $insert_id = $this->db->insert_id();

        if( count($location) > 0 ){

            foreach( $location as $location ){
                $this->db->query("INSERT INTO tbl_group_location (id_group, location) VALUES( $insert_id, '$location' );");
            }

        }

        if ($sql) {
            return TRUE;
        }

        return FALSE;

    }

    public function editGroup($location = []){

        $id = $this->getId();

        $data = array('group' => $this->getGroup());
        $sql = $this->db->set($data)->where('id', $this->getId())->update('tbl_group');

        $this->db->query("DELETE FROM tbl_group_location WHERE id_group = " . $id);

        if( count($location) > 0 ){

            foreach( $location as $location ){
                $this->db->query("INSERT INTO tbl_group_location (id_group, location) VALUES( $id, '$location' );");
            }

        }

        if ($sql) {
            return TRUE;
        }

        return FALSE;

    }

    public function deleteGroup() {

        $id = $this->getId();
        $sql = $this->db->query("DELETE FROM tbl_group WHERE id = " . $id);

        return TRUE;

    }

    public function getGroupLocations($id){

        $sql = $this->db->query("select location FROM tbl_group_location WHERE id_group = $id GROUP BY location");

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->result_array();
        }

    }

    public function getAllGroups(){

        $sql = $this->db->query("select * FROM tbl_group ORDER BY name");

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->result_array();
        }

    }

    public function getArticleGroups(){

        $articleId = $this->getId();

        $sql = $this->db->query("select group_id FROM tbl_article_group WHERE article_id = " . $articleId);

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->result_array();
        }

    }

    public function getUserGroups($userId){

        $sql = $this->db->query("select id_group FROM tbl_user_group WHERE id_user = " . $userId);

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->result_array();
        }

    }

    public function getAllLocations(){

        $sql = $this->db->query("select * FROM tbl_user_location ORDER BY city");

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->result_array();
        }

    }

    public function getArticleLocations(){

        $articleId = $this->getId();

        $sql = $this->db->query("select location_id FROM tbl_article_location WHERE article_id = " . $articleId);

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->result_array();
        }

    }

    public function getUserLocation($userLocationId){

        $sql = $this->db->query("select id FROM tbl_user_location WHERE id = " . $userLocationId);

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->result_array();
        }

    }

    public function getAllDisciplines(){

        $sql = $this->db->query("select * FROM tbl_discipline ORDER BY name");

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->result_array();
        }

    }

    public function getAllBranches(){

        $sql = $this->db->query("select UCASE(branch) branch FROM tbl_users WHERE branch IS NOT NULL GROUP BY branch ORDER BY branch");

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->result_array();
        }

    }

    public function getArticleDisciplines(){

        $articleId = $this->getId();

        $sql = $this->db->query("select discipline_id FROM tbl_article_discipline WHERE article_id = " . $articleId);

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->result_array();
        }

    }

    public function getArticleBranches(){

        $articleId = $this->getId();

        $sql = $this->db->query("select branch FROM tbl_article_branch WHERE article_id = " . $articleId);

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->result_array();
        }

    }

    public function getUserDiscipline($userId){

        $sql = $this->db->query("select discipline_id FROM tbl_user_discipline WHERE user_id = " . $userId);

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->result_array();
        }

    }

    public function getUserBranch($userId){

        $sql = $this->db->query("select branch FROM tbl_users WHERE id = " . $userId);

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->row_array();
        }

    }

    public function getAllUsers(){

        $sql = $this->db->query("select location FROM tbl_users GROUP BY location");

        if ($sql->num_rows() < 1) {
            return FALSE;
        } else {
            return $data = $sql->result_array();
        }

    }

    public function insertUserGroup($group){

        $data = array('id_group' => $group);
        $sql = $this->db->insert('tbl_user_group', $data);

//        $insert_id = $this->db->insert_id();

//        if( count($location) > 0 ){
//
//            foreach( $location as $location ){
//                $this->db->query("INSERT INTO tbl_group_location (id_group, location) VALUES( $insert_id, '$location' );");
//            }
//
//        }

        if ($sql) {
            return TRUE;
        }

        return FALSE;

    }

}