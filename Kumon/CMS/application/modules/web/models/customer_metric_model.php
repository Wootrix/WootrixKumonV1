<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class customer_metric_model extends CI_Model {

    private $_tableName = 'tbl_customer_metric';

    private $id = "";
    private $idCustomer = "";
    private $metric = "";

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
    public function getIdCustomer()
    {
        return $this->idCustomer;
    }

    /**
     * @param string $idCustomer
     */
    public function setIdCustomer($idCustomer)
    {
        $this->idCustomer = $idCustomer;
    }

    /**
     * @return string
     */
    public function getMetric()
    {
        return $this->metric;
    }

    /**
     * @param string $metric
     */
    public function setMetric($metric)
    {
        $this->metric = $metric;
    }

    public function insert($metrics){

        $data = array();

        $this->idCustomer = $metrics["id_customer"];

        $this->db->query("DELETE FROM tbl_customer_metric WHERE id_customer = " . $this->idCustomer);

        if( isset($metrics["metric_1"]) && $metrics["metric_1"] == 1 ){
            $data[] = array("id_customer" => $this->idCustomer, "metric" => 1);
        }

        if( isset($metrics["metric_2"]) && $metrics["metric_2"] == 1 ){
            $data[] = array("id_customer" => $this->idCustomer, "metric" => 2);
        }

        if( isset($metrics["metric_3"]) && $metrics["metric_3"] == 1 ){
            $data[] = array("id_customer" => $this->idCustomer, "metric" => 3);
        }

        if( isset($metrics["metric_4"]) && $metrics["metric_4"] == 1 ){
            $data[] = array("id_customer" => $this->idCustomer, "metric" => 4);
        }

        if( isset($metrics["metric_5"]) && $metrics["metric_5"] == 1 ){
            $data[] = array("id_customer" => $this->idCustomer, "metric" => 5);
        }

        if( isset($metrics["metric_6"]) && $metrics["metric_6"] == 1 ){
            $data[] = array("id_customer" => $this->idCustomer, "metric" => 6);
        }

        $inserted = $this->db->insert_batch($this->_tableName, $data);

        return $inserted;

    }

    public function getMetrics(){

        $idCustomer = $this->getIdCustomer();

        $sql = $this->db->query("SELECT * FROM tbl_customer_metric WHERE id_customer = $idCustomer");

        if ($sql->num_rows() < 1) {
            return array();
        } else {
            return $data = $sql->result_array();
        }

    }

}
