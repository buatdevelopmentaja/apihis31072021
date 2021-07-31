<?php

class M_user extends CI_Model{


    public function __construct() {
        parent::__construct();
        $this->secretkey_server = $this->config->item('secretkey_server');
        $this->base_url = $this->config->item('base_url');
        $this->load->model('M_base','base');
    }


    function get_profil($userid,$secretkey){
        
        //cek signature

        if($secretkey != $this->secretkey_server){
            return array(
                'Status' => 'Failed',
                'Message' => 'Invalid Token',
                'ResponseCode' => '01' 
            );
        }
        
        $sql_login = "SELECT * FROM staff WHERE id = '$userid' AND is_active = '1' ";
        $exec_login = $this->db->query($sql_login)->row();

        if (isset($exec_login)) {


                return array('Status'=>'Success',
                        'Message'=>'Success Login',
                        'Data' => array(
                            'userid' => $exec_login->id,
                            'employee_id' => $exec_login->employee_id,
                            'department' => $exec_login->department,
                            'designation' => $exec_login->designation,
                            'specialist' => $exec_login->specialist,
                            'qualification' => $exec_login->qualification,
                            'work_exp' => $exec_login->work_exp,
                            'specialization' => $exec_login->specialization,
                            'name' => $exec_login->name,
                            'email' => $exec_login->email,
                            'image' => $exec_login->image, 
                            'surname' => $exec_login->surname,
                            'contact_no' => $exec_login->contact_no,
                            'emergency_contact_no' => $exec_login->emergency_contact_no,
                            'dob' => $exec_login->dob,
                            'local_address' => $exec_login->local_address,
                            'gender' => $exec_login->gender
                        ),
                        'ResponseCode'=>'00'
                );

            
        }else{
            return array('Status'=>'Failed',
                    'Message'=>'User not found',
                    'ResponseCode'=>'03'
            );
        }
        

    }


    function update_picture($userid,$imagedata,$secretkey){
        
        //cek signature

        if($secretkey != $this->secretkey_server){
            return array(
                'Status' => 'Failed',
                'Message' => 'Invalid Token',
                'ResponseCode' => '01' 
            );
        }
        
        $sql_data = "SELECT * FROM staff WHERE id = '$userid' AND is_active = '1' ";
        $exec_data = $this->db->query($sql_data)->row();

        if (isset($exec_data)) {

            $url_image = $this->base_url.$imagedata;
            $update_pic = $this->db->query("UPDATE staff SET image = '$url_image' WHERE id = '$userid' ");

            if ($update_pic == TRUE) {
                return array('Status'=>'Success',
                        'Message'=>'Success, Change Picture',
                        'ResponseCode'=>'00'
                );
            }else{
                return array('Status'=>'Failed',
                        'Message'=>'Failed, Please Try Again',
                        'ResponseCode'=>'03'
                );
            }
            
        }else{
            return array('Status'=>'Failed',
                    'Message'=>'User not found',
                    'ResponseCode'=>'03'
            );
        }
        

    }

    function update_profile($userid,$general_info,$medical_education,$phonenumber1,$phonenumber2,$emergency_number,$doctor_type,$str,$sip,$specialization,$areas_of_interest,$subspecialty,$non_patient,$consultant_doctor,$secretkey){
        
        //cek signature

        if($secretkey != $this->secretkey_server){
            return array(
                'Status' => 'Failed',
                'Message' => 'Invalid Token',
                'ResponseCode' => '01' 
            );
        }
        
        $sql_data = "SELECT * FROM staff WHERE id = '$userid' AND is_active = '1' ";
        $exec_data = $this->db->query($sql_data)->row();

        if (isset($exec_data)) {

            $data_update = array(
                'general_info' => $general_info,
                'medical_education' => $medical_education, 
                'phonenumber1' => $phonenumber1,
                'phonenumber2' => $phonenumber2,
                'emergency_number' => $emergency_number,
                'doctor_type' => $doctor_type,
                'str' => $str,
                'sip' => $sip,
                'specialization' => $specialization,
                'areas_of_interest' => $areas_of_interest,
                'subspecialty' => $subspecialty,
                'non_patient' => $non_patient,
                'consultant_doctor' => $consultant_doctor
            );
            $this->db->where('id',$userid);
            $this->db->update('staff',$data);

            if ($update_pic == TRUE) {
                return array('Status'=>'Success',
                        'Message'=>'Success, Change Profile',
                        'ResponseCode'=>'00'
                );
            }else{
                return array('Status'=>'Failed',
                        'Message'=>'Failed, Please Try Again',
                        'ResponseCode'=>'03'
                );
            }
            
        }else{
            return array('Status'=>'Failed',
                    'Message'=>'User not found',
                    'ResponseCode'=>'03'
            );
        }
        

    }
    
   



}

?>