<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Kumon extends CI_Controller {

    public function __construct() {

        parent::__construct();

        $this->load->helper('url');
        $this->load->library('session', true);
        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->helper('language');
        $this->lang->load('en', 'english');

    }

    public function group_list() {

        if ($this->session->userdata('id') == ""){
            redirect("login_check");
        }

        $this->load->model("web/group_model");
        $model = new group_model();

        $roles = $model->getAll();

        $data['data_result'] = $roles;

        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'kumon/roles_list', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());

        $this->load->view('templates/common', $data);

    }

    public function new_group() {

        if ($this->session->userdata('id') == ""){
            redirect("login_check");
        }

        $this->load->model("web/group_model");
        $model = new group_model();

        if ($this->input->post("save") == "save") {

            $model->setGroup($this->input->post("group_name"));

            $location = $this->input->post("location");
            $result = $model->addNewGroup($location);

            if ($result == TRUE) {
                $this->session->set_flashdata("suc_msg", "Grupo adicionado com sucesso.");
            }else{
                $this->session->set_flashdata("msg", "Grupo já existente.");
            }

            redirect("groups");

        }

        $locations = $model->getAllLocations();

        $data["locations"] = $locations;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'kumon/new_role', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());

        $this->load->view('templates/common', $data);

    }

    public function edit_group() {

        if ($this->session->userdata('id') == "") {
            redirect("login_check");
        }

        $this->load->model("web/group_model");
        $model = new group_model();

        $id = $_GET['rowId'];
        $data_result = $model->getById($id);

        if ($this->input->post("save") == "save") {

            $model->setGroup(trim(addslashes(htmlentities(strip_tags($this->input->post("group_name"))))));
            $model->setId($this->input->post("groupId"));

            $model->editGroup($this->input->post("location"));
            $this->session->set_flashdata("msg", "Grupo editado com sucesso");
            redirect("groups");

        }

        $locations = $model->getAllLocations();
        $data["locations"] = $locations;

        $groupLocations = $model->getGroupLocations($id);
        $data["groupLocations"] = $groupLocations;

        $data['data_result'] = $data_result;
        $data['header'] = array('view' => 'templates/adminheader', 'data' => array());
        $data['main_content'] = array('view' => 'kumon/edit_role', 'data' => array());
        $data['footer'] = array('view' => 'templates/adminfooter', 'data' => array());
        $this->load->view('templates/common', $data);
    }

    public function delete_group(){

        $this->load->model("web/group_model");
        $model = new group_model();
        $model->setId($_POST['val']);
        $model->deleteGroup();
        exit();
    }

}