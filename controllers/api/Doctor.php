<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
use Restserver\Libraries\REST_Controller;

class Doctor extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
        $this->load->model('M_doctor','doc');
    }
    
    function listdata_post() 
    {   
        $userid=$this->post('userid');
        $secretkey=$this->post('secretkey');

        $postdata = $this->doc->list_doctor($userid,$secretkey);
        if($postdata['ResponseCode'] == '00')
        {
            $this->response($postdata, 200);
        }else{
            $this->response($postdata);
        }
    }

    function listexpert_post() 
    {   
        $userid=$this->post('userid');
        $secretkey=$this->post('secretkey');

        $postdata = $this->doc->list_expert($userid,$secretkey);
        if($postdata['ResponseCode'] == '00')
        {
            $this->response($postdata, 200);
        }else{
            $this->response($postdata);
        }
    }


    

    
    
}
?>