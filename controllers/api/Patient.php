<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
use Restserver\Libraries\REST_Controller;

class Patient extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
        $this->load->model('M_patient','pat');
    }

    function profil_post() 
    {   
        $userid=$this->post('userid');
        $secretkey=$this->post('secretkey');

        $postdata = $this->pat->get_profil($userid,$secretkey);
        if($postdata['ResponseCode'] == '00')
        {
            $this->response($postdata, 200);
        }else{
            $this->response($postdata);
        }
    }
    
    function listpatient_post() 
    {   
        $userid=$this->post('userid');
        $secretkey=$this->post('secretkey');

        $postdata = $this->pat->list_patient($userid,$secretkey);
        if($postdata['ResponseCode'] == '00')
        {
            $this->response($postdata, 200);
        }else{
            $this->response($postdata);
        }
    }

    function currentpatient_post() 
    {   
        $userid=$this->post('userid');
        $secretkey=$this->post('secretkey');

        $postdata = $this->pat->current_patient($userid,$secretkey);
        if($postdata['ResponseCode'] == '00')
        {
            $this->response($postdata, 200);
        }else{
            $this->response($postdata);
        }
    }

    function addprescription_post(){
        $item_prescription=$this->post('item_prescription');
        $patient_id=$this->post('patient_id');
        $userid=$this->post('userid');
        $secretkey=$this->post('secretkey');

        $postdata = $this->pat->add_prescription($item_prescription,$patient_id,$userid,$secretkey);
        if($postdata['ResponseCode'] == '00')
        {
            $this->response($postdata, 200);
        }else{
            $this->response($postdata);
        }
    }

    function updateprescription_post(){
        $item_prescription=$this->post('item_prescription');
        $patient_id=$this->post('patient_id');
        $userid=$this->post('userid');
        $secretkey=$this->post('secretkey');

        $postdata = $this->pat->update_prescription($item_prescription,$patient_id,$userid,$secretkey);
        if($postdata['ResponseCode'] == '00')
        {
            $this->response($postdata, 200);
        }else{
            $this->response($postdata);
        }
    }

    function listprescription_post(){
        $patient_id=$this->post('patient_id');
        $userid=$this->post('userid');
        $secretkey=$this->post('secretkey');
        
        $postdata = $this->pat->list_prescription($patient_id,$userid,$secretkey);
        if($postdata['ResponseCode'] == '00')
        {
            $this->response($postdata, 200);
        }else{
            $this->response($postdata);
        }
    }

    function addlaboratory_post(){
        $item_laboratory=$this->post('item_laboratory');
        $patient_id=$this->post('patient_id');
        $userid=$this->post('userid');
        $secretkey=$this->post('secretkey');

        $postdata = $this->pat->add_laboratory($item_laboratory,$patient_id,$userid,$secretkey);
        if($postdata['ResponseCode'] == '00')
        {
            $this->response($postdata, 200);
        }else{
            $this->response($postdata);
        }
    }

    function listlaboratory_post(){
        $patient_id=$this->post('patient_id');
        $userid=$this->post('userid');
        $secretkey=$this->post('secretkey');
        
        $postdata = $this->pat->list_laboratory($patient_id,$userid,$secretkey);
        if($postdata['ResponseCode'] == '00')
        {
            $this->response($postdata, 200);
        }else{
            $this->response($postdata);
        }
    }

    function addradiology_post(){
        $item_radiology=$this->post('item_radiology');
        $patient_id=$this->post('patient_id');
        $userid=$this->post('userid');
        $secretkey=$this->post('secretkey');

        $postdata = $this->pat->add_radiology($item_radiology,$patient_id,$userid,$secretkey);
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
        
        $postdata = $this->pat->list_radiology($patient_id,$userid,$secretkey);
        if($postdata['ResponseCode'] == '00')
        {
            $this->response($postdata, 200);
        }else{
            $this->response($postdata);
        }
    }

    function testradiology_post() 
    {   
        $userid=$this->post('userid');
        $patientid=$this->post('patientid');
        $notes=$this->post('notes');
        $secretkey=$this->post('secretkey');

        $postdata = $this->pat->test_radiology($userid,$patientid,$notes,$secretkey);
        if($postdata['ResponseCode'] == '00')
        {
            $this->response($postdata, 200);
        }else{
            $this->response($postdata);
        }
    }

    function testlaboratorium_post() 
    {   
        $userid=$this->post('userid');
        $patientid=$this->post('patientid');
        $notes=$this->post('notes');
        $secretkey=$this->post('secretkey');

        $postdata = $this->pat->test_laboratorium($userid,$patientid,$notes,$secretkey);
        if($postdata['ResponseCode'] == '00')
        {
            $this->response($postdata, 200);
        }else{
            $this->response($postdata);
        }
    }

    //new 25 juli 2021
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
        $dob=$this->post('dob');
        $address=$this->post('address');
        $marital_status=$this->post('marital_status');
        $mobileno=$this->post('mobileno');
        $secretkey=$this->post('secretkey');

        $postdata = $this->pat->update_profile($userid,$dob,$address,$marital_status,$mobileno,$secretkey);
        if($postdata['ResponseCode'] == '00')
        {
            $this->response($postdata, 200);
        }else{
            $this->response($postdata);
        }
    }


    function changepassword_post() 
    {   
        $userid=$this->post('userid');
        $old_password=$this->post('old_password');
        $password=$this->post('password');
        $conf_password=$this->post('conf_password');
        $secretkey=$this->post('secretkey');

        $postdata = $this->pat->change_password($userid,$old_password,$password,$conf_password,$secretkey);
        if($postdata['ResponseCode'] == '00')
        {
            $this->response($postdata, 200);
        }else{
            $this->response($postdata);
        }
    }


    function myprescription_post(){
        $userid=$this->post('userid');
        $secretkey=$this->post('secretkey');
        
        $postdata = $this->pat->my_prescription($userid,$secretkey);
        if($postdata['ResponseCode'] == '00')
        {
            $this->response($postdata, 200);
        }else{
            $this->response($postdata);
        }
    }


    function scheduledoctor_post(){
        $date_schedule=$this->post('date_schedule');
        $userid=$this->post('userid');
        $doctorid=$this->post('doctorid');
        $secretkey=$this->post('secretkey');

        $postdata = $this->pat->schedule_doctor($date_schedule,$userid,$doctorid,$secretkey);
        if($postdata['ResponseCode'] == '00')
        {
            $this->response($postdata, 200);
        }else{
            $this->response($postdata);
        }
    }


    //add patient
    function addpatient_post() 
    {   
        $patient_name=$this->post('patient_name');
        $dob=$this->post('dob');
        $mobileno=$this->post('mobileno');
        $email=$this->post('email');
        $gender=$this->post('gender');
        $marital_status=$this->post('marital_status');
        $blood_group=$this->post('blood_group');
        $address=$this->post('address');
        $userid=$this->post('userid');
        $secretkey=$this->post('secretkey');

        $postdata = $this->pat->add_patient($patient_name,$dob,$mobileno,$email,$gender,$marital_status,$blood_group,$address,$userid,$secretkey);
        if($postdata['ResponseCode'] == '00')
        {
            $this->response($postdata, 200);
        }else{
            $this->response($postdata);
        }
    }

    function mypatient_post(){
        $userid=$this->post('userid');
        $secretkey=$this->post('secretkey');

        $postdata = $this->pat->my_patient($userid,$secretkey);
        if($postdata['ResponseCode'] == '00')
        {
            $this->response($postdata, 200);
        }else{
            $this->response($postdata);
        }
    }


    function addappointment_post(){
        $patient_id=$this->post('patient_id');
        $date=$this->post('date');
        $doctor_id=$this->post('doctor_id');
        $mesage=$this->post('mesage');
        $userid=$this->post('userid');
        $secretkey=$this->post('secretkey');

        $postdata = $this->pat->add_appointment($patient_id,$date,$doctor_id,$mesage,$userid,$secretkey);
        if($postdata['ResponseCode'] == '00')
        {
            $this->response($postdata, 200);
        }else{
            $this->response($postdata);
        }
    }
    
    
}
?>