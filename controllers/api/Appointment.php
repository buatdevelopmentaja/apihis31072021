<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
use Restserver\Libraries\REST_Controller;

class Appointment extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
        $this->load->model('M_appointment','apt');
    }
    //start for doctor dummy session
    function listappointment_post() 
    {   
        $userid=$this->post('userid');
        $secretkey=$this->post('secretkey');

        $postdata = $this->apt->list_appointment($userid,$secretkey);
        if($postdata['ResponseCode'] == '00')
        {
            $this->response($postdata, 200);
        }else{
            $this->response($postdata);
        }
    }

    function listsession_post() 
    {   
        $session_id=$this->post('session_id');
        $userid=$this->post('userid');
        $secretkey=$this->post('secretkey');

        $postdata = $this->apt->list_session_appointment($session_id,$userid,$secretkey);
        if($postdata['ResponseCode'] == '00')
        {
            $this->response($postdata, 200);
        }else{
            $this->response($postdata);
        }
    }

    function add_post() 
    {   
        $patient_id=$this->post('patient_id');
        $appointment_no=$this->post('appointment_no');
        $appointment_datetime=$this->post('appointment_datetime');
        $priority=$this->post('priority');
        $patient_name=$this->post('patient_name');
        $gender=$this->post('gender');
        $email=$this->post('email');
        $mobile_no=$this->post('mobile_no');
        $specialist=$this->post('specialist');
        $doctor=$this->post('doctor');
        $amount=$this->post('amount');
        $message=$this->post('message');
        $appointment_status=$this->post('appointment_status');
        $source=$this->post('source');
        $is_opd=$this->post('is_opd');
        $is_ipd=$this->post('is_ipd');
        $live_constultant=$this->post('live_constultant');
        $secretkey=$this->post('secretkey');

        $postdata = $this->apt->add_appointment($patient_id,$appointment_no,$appointment_datetime,$priority,$patient_name,$gender,$email,$mobile_no,$specialist,$doctor,$amount,$message,$appointment_status,$source,$is_opd,$is_ipd,$live_constultant,$secretkey);
        if($postdata['ResponseCode'] == '00')
        {
            $this->response($postdata, 200);
        }else{
            $this->response($postdata);
        }
    }

    function detail_post() 
    {   
        // $userid=$this->post('userid');
        $appointment_id=$this->post('appointment_id');
        $secretkey=$this->post('secretkey');

        $postdata = $this->apt->detail_appointment($appointment_id,$secretkey);
        if($postdata['ResponseCode'] == '00')
        {
            $this->response($postdata, 200);
        }else{
            $this->response($postdata);
        }
    }


    function update_post() 
    {   
        // $userid=$this->post('userid');
        $appointment_id=$this->post('appointment_id');
        $patient_id=$this->post('patient_id');
        $appointment_no=$this->post('appointment_no');
        $appointment_datetime=$this->post('appointment_datetime');
        $priority=$this->post('priority');
        $patient_name=$this->post('patient_name');
        $gender=$this->post('gender');
        $email=$this->post('email');
        $mobile_no=$this->post('mobile_no');
        $specialist=$this->post('specialist');
        $doctor=$this->post('doctor');
        $amount=$this->post('amount');
        $message=$this->post('message');
        $appointment_status=$this->post('appointment_status');
        $source=$this->post('source');
        $is_opd=$this->post('is_opd');
        $is_ipd=$this->post('is_ipd');
        $live_constultant=$this->post('live_constultant');
        $secretkey=$this->post('secretkey');

        $postdata = $this->apt->update_appointment($appointment_id,$patient_id,$appointment_no,$appointment_datetime,$priority,$patient_name,$gender,$email,$mobile_no,$specialist,$doctor,$amount,$message,$appointment_status,$source,$is_opd,$is_ipd,$live_constultant,$secretkey);
        if($postdata['ResponseCode'] == '00')
        {
            $this->response($postdata, 200);
        }else{
            $this->response($postdata);
        }
    }

    function delete_post() 
    {   
        // $userid=$this->post('userid');
        $appointment_id=$this->post('appointment_id');
        $secretkey=$this->post('secretkey');

        $postdata = $this->apt->delete_appointment($appointment_id,$secretkey);
        if($postdata['ResponseCode'] == '00')
        {
            $this->response($postdata, 200);
        }else{
            $this->response($postdata);
        }
    }
    //end start for doctor dummy session

    //for user
    function listdata_post() 
    {   
        $userid=$this->post('userid');
        $secretkey=$this->post('secretkey');

        $postdata = $this->apt->list_appointment_patient($userid,$secretkey);
        if($postdata['ResponseCode'] == '00')
        {
            $this->response($postdata, 200);
        }else{
            $this->response($postdata);
        }
    }

    function adddata_post() 
    {   
        $patient_id=$this->post('userid');
        $appointment_datetime=$this->post('appointment_datetime');
        $priority=$this->post('priority');
        $patient_name=$this->post('patient_name');
        $gender=$this->post('gender');
        $email=$this->post('email');
        $mobile_no=$this->post('mobile_no');
        $specialist=$this->post('specialist');
        $doctor=$this->post('doctor_id');
        $message=$this->post('message');
        $is_opd=$this->post('is_opd');
        $is_ipd=$this->post('is_ipd');
        $live_consult=$this->post('live_consult');
        $secretkey=$this->post('secretkey');

        $postdata = $this->apt->add_appointment_patient($patient_id,$appointment_datetime,$priority,$patient_name,$gender,$email,$mobile_no,$specialist,$doctor,$message,$is_opd,$is_ipd,$live_consult,$secretkey);
        if($postdata['ResponseCode'] == '00')
        {
            $this->response($postdata, 200);
        }else{
            $this->response($postdata);
        }
    }

    
    
}
?>