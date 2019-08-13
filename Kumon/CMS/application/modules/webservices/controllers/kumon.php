<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class kumon extends CI_Controller
{
    public function __construct() {

        parent::__construct();

        $this->load->helper('url');
//        $this->load->libcrary('session', true);

        error_reporting(E_ALL);
        ini_set('display_errors', 1);

    }

    public function generateArticleLocation(){

        $this->load->model("webservices/users_model");
        $userObj = new users_model();

        $branches = array("BARRANQUILLA", "BOGOTA", "BUENOS AIRES", "CALI", "LA PAZ", "LIMA", "SANTIAGO");

        foreach( $branches as $branch ){
            $userObj->updateUserBranch($branch);
        }
//
//        echo "done";


//        $userObj->getArticles();
//        $userObj->setUserMagazines();
//        $userObj->setArticleOrder();


    }

    public function testKumon(){
//        $this->load->model("webservices/users_model");
//        $userObj = new users_model();
//
////        $userObj->getArticles();
//        $userObj->setUserMagazines();

        $articleObj = $this->load->model("webservices/new_articles_model");
        $articleObj = new new_articles_model();

        $articleObj->set_token($this->session->userdata("user_id"));
        echo $articleObj->get_token();
        echo "<br />";
        $articleObj->set_language_name("en");
        $getLangId = $articleObj->getLanguageId();
        if($this->session->userdata('languages')!=''){
            $articleObj->set_language_id(ltrim($this->session->userdata('languages'),","));
        }else{
            $articleObj->set_language_id($getLangId['id']);
        }
        $getUserMagzines = $articleObj->getUserArticlesTest();

        echo "<pre>";
        print_r($getUserMagzines);
        echo "</pre>"; exit;

    }

    public function login() {

        $this->load->model("webservices/users_model");
        $userObj = new users_model();

//        $authValidValue = $this->config->item("validValue");
//
//        if ($authValidValue != '') {
//
//            $json = trim(file_get_contents('php://input'));
//
//            if (!empty($json)) {
//
//                $json = json_decode($json, true);
//
//                $token = $json["token"];
//
//                try{

                    $email = "user.test01@kumon.com.br";

                    $client = new SoapClient('http://201.91.129.22/wsKumon01/PortalOrientador.asmx?WSDL', array("trace" => 1, "exception" => 1));

                    $function = 'getDataUserExternal';

                    $arguments = array( "getEntityTokenExternal" => array(
                        'strApp' => "41883FA5-D29A-439B-9186-6E8E9000572D",
                        'strToken' => '1234567890',
                        "strEmail" => $email,
                        'intEntityID' => '28'
                    ) );

                    $options = array('location' => 'http://201.91.129.22/wsKumon01/PortalOrientador.asmx');

                    $client->__soapCall($function, $arguments, $options);
                    $response_xml = $client->__getLastResponse();

//                    $token = "D07ACA05-5E95-4EA5-8F9F-7CF0E18A4257";
//
//                    $client = new SoapClient('http://201.91.129.22/wsKumon01/PortalOrientador.asmx?WSDL', array("trace" => 1, "exception" => 1));
//
//                    $function = 'getEntityTokenExternal';
//
//                    $arguments = array( "getEntityTokenExternal" => array(
//                        'strApp' => "41883FA5-D29A-439B-9186-6E8E9000572D",
//                        'strToken' => 'exte$n@l',
//                        "strEntityToken" => $token,
//                        'intEntityID' => '28'
//                    ) );

                    $this->load->model("webservices/users_model");
                    $userObj = new users_model();

                    $options = array('location' => 'http://201.91.129.22/wsKumon01/PortalOrientador.asmx');

                    $client->__soapCall($function, $arguments, $options);
                    $response_xml = $client->__getLastResponse();

//                    header ("Content-Type:text/xml");
//                    print_r($response_xml); exit;

                    $xml = new SimpleXMLElement($response_xml);
                    $xml->registerXPathNamespace("diffgr", "urn:schemas-microsoft-com:xml-diffgram-v1");
                    $body = $xml->xpath("//diffgr:diffgram");

                    $name = "Colaborador";

                    if( $body[0]->NewDataSet->Table->CustomerType == "O" ){
                        $name = "Orientador";
                    }

                    $group = $body[0]->NewDataSet->Table->CustomerType;
                    $country = $body[0]->NewDataSet->Table->Country;
                    $state =  $body[0]->NewDataSet->Table->State;
                    $city =  $body[0]->NewDataSet->Table->City;

                    $disciplines = [];

                    if( $body[0]->NewDataSet->Table->Mat == "1" ){
                        $disciplines[] = "mat";
                    }

                    if( $body[0]->NewDataSet->Table->Por == "1" ){
                        $disciplines[] = "por";
                    }

                    if( $body[0]->NewDataSet->Table->Nih == "1" ){
                        $disciplines[] = "nih";
                    }

                    if( $body[0]->NewDataSet->Table->Kok == "1" ){
                        $disciplines[] = "kok";
                    }

                    if( $body[0]->NewDataSet->Table->Ing == "1" ){
                        $disciplines[] = "ing";
                    }

//                    echo "<pre>"; print_r($disciplines); exit;

                    $userObj->set_name($name);
                    $userObj->set_email($email);
                    $userObj->set_password($this->generateRandomString());
                    $userObj->set_check_website("Website");
                    $signup = $userObj->signupInWootrix($group, $country, $state, $city, $disciplines);

                    if( isset( $body[0]->NewDataSet->Table ) ){

                    } else {

                    }


                    $xml = new SimpleXMLElement($response_xml);
                    $xml->registerXPathNamespace("diffgr", "urn:schemas-microsoft-com:xml-diffgram-v1");
                    $body = $xml->xpath("//diffgr:diffgram");

                    $name = "Colaborador";

                    print_r( $body[0]->NewDataSet->Table );
                    exit;

//                    if( $body[0]->NewDataSet->Table->CustomerType == "O" ){
//                        $name = "Orientador";
//                    }

//                    $userObj->set_name($name);
//                    $userObj->set_email("wootrix_" . $body[0]->NewDataSet->Table->UserID . "@wootrix.com.br");
//                    $userObj->set_password($this->generateRandomString());
//                    $userObj->set_check_website("Website");
//                    $signup = $userObj->signupInWootrix();

//                } catch( Exception $e){
//                    echo "<pre>";
//                    print_r($e->getMessage());
//                }
//
//
//            }
//
//        }

//        try {
//
//            $client = new SoapClient('http://201.91.129.22/wsKumon01/PortalOrientador.asmx?WSDL', array("trace" => 1, "exception" => 0));
//
//            $function = 'getEntityTokenExternal';
//
//            $arguments = array( "getEntityTokenExternal" => array(
//                'strApp' => "41883FA5-D29A-439B-9186-6E8E9000572D",
//                'strToken' => 'exte$n@l',
////                'strEntityToken' => "D11FDCCA-D7DC-4524-B438-3006AC920114",
//                "strEntityToken" => "D07ACA05-5E95-4EA5-8F9F-7CF0E18A4257", //orientador
//    //            "strEntityToken" => "47C81924-B8CC-424E-B720-A3D0A15AD954", //colaborador
//                'intEntityID' => '28'
//            ) );
//
//            $options = array('location' => 'http://201.91.129.22/wsKumon01/PortalOrientador.asmx');
//
//            $response = $client->__soapCall($function, $arguments, $options);
//            $response_xml = $client->__getLastResponse();
//
//            header ("Content-Type:text/xml");
//            print_r($response_xml);
//
//        } catch( Exception $e){
//            echo "<pre>";
//            print_r($e->getMessage());
//        }

//        libxml_disable_entity_loader(false);

//        $feed = file_get_contents(APPPATH . "assets/sucesso.xml");
//
//        $xml = new SimpleXMLElement($feed);
//        $xml->registerXPathNamespace("diffgr", "urn:schemas-microsoft-com:xml-diffgram-v1");
//        $body = $xml->xpath("//diffgr:diffgram");


    }

    private function getKumonResponse($token){

        //DEV
//        $url = 'http://201.91.129.22/wsKumon01/PortalOrientador.asmx';

        //PROD
        $url = 'http://ws01.unidadekumon.com.br/weborder/PortalOrientador.asmx';

        $client = new SoapClient($url . "?WSDL", array("trace" => 1, "exception" => 1));

        $function = 'getEntityTokenExternal';

        $intEntityID = 28;

        if( $_SERVER['SERVER_NAME'] == "endirecto.unidadkumon.com"){
            $intEntityID = 29;
        }

        $arguments = array( "getEntityTokenExternal" => array(
            'strApp' => "41883FA5-D29A-439B-9186-6E8E9000572D",
            'strToken' => '1234567890',
            "strEntityToken" => $token,
            'intEntityID' => $intEntityID
        ) );

        $options = array('location' => $url);

        $client->__soapCall($function, $arguments, $options);
        $response_xml = $client->__getLastResponse();

        return $response_xml;

    }

    public function register(){

        $this->load->model("webservices/users_model");
        $userObj = new users_model();

        $authValidValue = $this->config->item("validValue");

        if ($authValidValue != '') {

            $json = trim(file_get_contents('php://input'));

            if (!empty($json)) {

                $json = json_decode($json, true);

                $token = $json["token"];

            }

        } else {
            echo json_encode(array('data' => array(), 'success' => false));
            exit;
        }

        $feed = file_get_contents(APPPATH . "assets/sucesso.xml");

        $xml = new SimpleXMLElement($feed);
        $xml->registerXPathNamespace("diffgr", "urn:schemas-microsoft-com:xml-diffgram-v1");
        $body = $xml->xpath("//diffgr:diffgram");

        $name = "Colaborador";

        if( $body[0]->NewDataSet->Table->CustomerType == "O" ){
            $name = "Orientador";
        }

        $userObj->set_name($name);
        $userObj->set_email("wootrix_" . $body[0]->NewDataSet->Table->UserID . "@wootrix.com.br");
        $userObj->set_password($this->generateRandomString());
        $userObj->set_check_website("Website");
        $signup = $userObj->signupInWootrix();

        if ($signup == FALSE) {
            $this->session->set_flashdata("userName", $this->input->post("userName"));
            $this->session->set_flashdata("userEmail", $this->input->post("userEmail"));

            $this->session->set_flashdata("loginError", $this->lang->line("email_id_exists_web"));
            $this->session->set_flashdata("error", "1");
            redirect($this->config->base_url());
        } else {
            $this->session->set_flashdata("regMsg", $this->lang->line("user_reg_success_web"));
            $this->session->set_flashdata("error", "2");
            redirect($this->config->base_url());
        }

        $data['header'] = array('view' => 'templates/header', 'data' => array());
        $data['main_content'] = array('view' => 'website_login/login', 'data' => array());
        $data['footer'] = array('view' => 'templates/footer', 'data' => array());
        $this->load->view('templates/common_template', $data);

    }

    private function generateRandomString($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}