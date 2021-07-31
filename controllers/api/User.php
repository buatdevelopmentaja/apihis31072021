<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
use Restserver\Libraries\REST_Controller;

class User extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
        $this->load->model('M_user','usr');
    }
    
    function profil_post() 
    {   
        $userid=$this->post('userid');
        $secretkey=$this->post('secretkey');

        $postdata = $this->usr->get_profil($userid,$secretkey);
        if($postdata['ResponseCode'] == '00')
        {
            $this->response($postdata, 200);
        }else{
            $this->response($postdata);
        }
    }

    function updatepicture_post() 
    {   
        $userid=$this->post('userid');
        $imagedata=$this->post('imagedata');
        $secretkey=$this->post('secretkey');


        //proses upload image
        $path="assets/document/";
        $roomPhotoList = $this->post('imagedata');
        $random_digit=md5(date('Y_m_d_h_i_s'));
        $filename=$random_digit.'.jpg';
        $decoded=base64_decode($roomPhotoList);
        file_put_contents($path.$filename,$decoded);
        //

        $postdata = $this->usr->update_picture($userid,$path.$filename,$secretkey);
        if($postdata['ResponseCode'] == '00')
        {
            $this->response($postdata, 200);
        }else{
            $this->response($postdata);
        }
    }


    function updateprofile_post() 
    {   
        $userid=$this->post('userid');
        $general_info=$this->post('general_info');
        $medical_education=$this->post('medical_education');
        $phonenumber1=$this->post('phonenumber1');
        $phonenumber2=$this->post('phonenumber2');
        $emergency_number=$this->post('emergency_number');
        $doctor_type=$this->post('doctor_type');
        $str=$this->post('str');
        $sip=$this->post('sip');
        $specialization=$this->post('specialization');
        $areas_of_interest=$this->post('areas_of_interest');
        $subspecialty=$this->post('subspecialty');
        $non_patient=$this->post('non_patient');
        $consultant_doctor=$this->post('consultant_doctor');
        $secretkey=$this->post('secretkey');

        $postdata = $this->usr->update_profile($userid,$general_info,$medical_education,$phonenumber1,$phonenumber2,$emergency_number,$doctor_type,$str,$sip,$specialization,$areas_of_interest,$subspecialty,$non_patient,$consultant_doctor,$secretkey);
        if($postdata['ResponseCode'] == '00')
        {
            $this->response($postdata, 200);
        }else{
            $this->response($postdata);
        }
    }

    
    
}
?>