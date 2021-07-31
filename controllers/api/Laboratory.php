<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
use Restserver\Libraries\REST_Controller;

class Laboratory extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
        $this->load->model('M_laboratory','lab');
    }

    function listlaboratory_post(){
        $patient_id=$this->post('patient_id');
        $userid=$this->post('userid');
        $secretkey=$this->post('secretkey');
        
        $postdata = $this->lab->list_laboratory($patient_id,$userid,$secretkey);
        if($postdata['ResponseCode'] == '00')
        {
            $this->response($postdata, 200);
        }else{
            $this->response($postdata);
        }
    }


    function listradiology_post(){
        $patient_id=$this->post('patient_id');
        $userid=$this->post('userid');
        $secretkey=$this->post('secretkey');
        
        $postdata = $this->lab->list_radiology($patient_id,$userid,$secretkey);
        if($postdata['ResponseCode'] == '00')
        {
            $this->response($postdata, 200);
        }else{
            $this->response($postdata);
        }
    }
    
    
}
?>