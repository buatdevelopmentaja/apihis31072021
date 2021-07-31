<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
use Restserver\Libraries\REST_Controller;

class Rate extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
        $this->load->model('M_rate','rt');
    }
    
    function listrate_post() 
    {   
        $userid=$this->post('userid');
        $secretkey=$this->post('secretkey');

        $postdata = $this->rt->list_rate($userid,$secretkey);
        if($postdata['ResponseCode'] == '00')
        {
            $this->response($postdata, 200);
        }else{
            $this->response($postdata);
        }
    }


    function update_post() 
    {   
        $userid=$this->post('userid');
        $price_consultant=$this->post('price_consultant');
        $doctor_consultant=$this->post('doctor_consultant');
        $bank_name=$this->post('bank_name');
        $account_number=$this->post('account_number');
        $account_name=$this->post('account_name');
        $update_date=$this->post('update_date');
        $secretkey=$this->post('secretkey');

        $postdata = $this->rt->update_rate($price_consultant,$doctor_consultant,$bank_name,$account_number,$account_name,$userid,$secretkey);
        if($postdata['ResponseCode'] == '00')
        {
            $this->response($postdata, 200);
        }else{
            $this->response($postdata);
        }
    }

    
    
}
?>