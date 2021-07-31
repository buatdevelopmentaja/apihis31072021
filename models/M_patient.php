<?php

class M_patient extends CI_Model{


    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
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
        
        $sql_login = "SELECT id,patient_name,image,email FROM patients pt WHERE pt.id = '$userid' ";
        $exec_login = $this->db->query($sql_login)->row();

        if (isset($exec_login)) {


                return array('Status'=>'Success',
                        'Message'=>'Success Login',
                        'Data' => array(
                            'userid' => $exec_login->id,
                            'patient_name' => $exec_login->patient_name,
                            'email' => $exec_login->email,
                            'image' => $this->base_url.$exec_login->image
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


    function list_patient($userid,$secretkey){
        
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

                $get_patient = $this->db->query("SELECT * FROM patients")->result();
                return array('Status'=>'Success',
                        'Message'=>'Success List Patient',
                        'Data' => $get_patient,
                        'ResponseCode'=>'00'
                );

            
        }else{
            return array('Status'=>'Failed',
                    'Message'=>'User not found',
                    'ResponseCode'=>'03'
            );
        }
        

    }

    function current_patient($userid,$secretkey){
        date_default_timezone_set('Asia/Jakarta');
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

                //cek appointment today
                $date_now = date("Y-m-d ");
                $time_now = date("h:i:s");
                $sql_schedule = "SELECT id,start_time,end_time FROM schedule sch WHERE sch.end_time >= '$time_now' AND sch.start_time <= '$time_now' AND userid = '$userid' AND status = 1 ";
                $get_schedule = $this->db->query($sql_schedule)->row();
                if (isset($get_schedule)) {
                    $start_time = $get_schedule->start_time;
                    $end_time = $get_schedule->end_time;

                    $datetime_start = $date_now.' '.$start_time;
                    $datetime_end = $date_now.' '.$end_time;
                    $sql_appointment = "SELECT id,patient_name,gender,email,mobileno,message,0 AS age,0 AS dob, 0 AS height FROM appointment WHERE date >= '$datetime_start' AND date <= '$datetime_end' AND doctor = '$userid' ORDER BY id ASC LIMIT 1 ";
                    $get_appointment = $this->db->query($sql_appointment)->row();

                    if (isset($get_appointment)) {
                        return array('Status'=>'Success',
                                'Message'=>'Success Data Patient',
                                'Data' => array(
                                    'id' => $get_appointment->id,
                                    'patient_name' => $get_appointment->patient_name,
                                    'gender' => $get_appointment->gender,
                                    'email' => $get_appointment->email,
                                    'mobileno' => $get_appointment->mobileno,
                                    'message' => $get_appointment->message,
                                    'age' => $get_appointment->age,
                                    'dob' => $get_appointment->dob,
                                    'height' => $get_appointment->height 
                                ),
                                'ResponseCode'=>'00'
                        );
                    }else{
                        return array(
                            'Status' => 'Failed',
                            'Message' => 'Not Found Data',
                            'ResponseCode' => '02 '.$sql_appointment 
                        );
                    }
                }else{
                    return array(
                        'Status' => 'Failed',
                        'Message' => 'Not Found Data',
                        'ResponseCode' => '02' 
                    );
                } 
        }else{
            return array('Status'=>'Failed',
                    'Message'=>'User not found',
                    'ResponseCode'=>'03'
            );
        }
        

    }

    //add prescription
    function add_prescription($item_prescription,$patient_id,$userid,$secretkey){
        

        //global declare
        $create_date = date("Y-m-d H:i:s");
        $uniqueid = 'PRS'.rand(10000,99999);
        //cek signature

        if($secretkey != $this->secretkey_server){
            return array(
                'Status' => 'Failed',
                'Message' => 'Invalid Token '.$secretkey,
                'ResponseCode' => '01' 
            );
        }
        
        $sql_login = "SELECT * FROM staff WHERE id = '$userid' AND is_active = '1' ";
        $exec_login = $this->db->query($sql_login)->row();
        if (isset($exec_login)) {
        foreach ($item_prescription as $key => $value) {
            $data = array(
                'opd_id' => 0,
                'visit_id' => 0, 
                'medicine_category_id' => 0,
                'medicine' => $value['medicine'],
                'dosage' => $value['dosage'],
                'instruction' => $value['instruction'],
                'quantity' => $value['quantity'],
                'isrepeat' => $value['isrepeat'],
                'times' => $value['times'],
                'patient_id' => $patient_id,
                'doctor_id' => $userid,
                'uniqueid' => $uniqueid,
                'create_date' => $create_date
            );
            $this->db->insert('prescription',$data);
        }
            return array('Status'=>'Success',
                        'Message'=>'Success Add Prescriptoin',
                        'ResponseCode'=>'00'
                );
        }else{
            return array(
                'Status' => 'Failed',
                'Message' => 'Not Found Data',
                'ResponseCode' => '02' 
            );
        } 
        
        
        

    }

    function update_prescription($item_prescription,$patient_id,$userid,$secretkey){
        

        //global declare
        $create_date = date("Y-m-d H:i:s");
        $uniqueid = 'PRS'.rand(10000,99999);
        //cek signature

        if($secretkey != $this->secretkey_server){
            return array(
                'Status' => 'Failed',
                'Message' => 'Invalid Token '.$secretkey,
                'ResponseCode' => '01' 
            );
        }
        
        $sql_login = "SELECT * FROM staff WHERE id = '$userid' AND is_active = '1' ";
        $exec_login = $this->db->query($sql_login)->row();
        if (isset($exec_login)) {
        foreach ($item_prescription as $key => $value) {
            $data = array(
                'opd_id' => 0,
                'visit_id' => 0, 
                'medicine_category_id' => 0,
                'medicine' => $value['medicine'],
                'dosage' => $value['dosage'],
                'instruction' => $value['instruction'],
                'quantity' => $value['quantity'],
                'isrepeat' => $value['isrepeat'],
                'times' => $value['times'],
                'patient_id' => $patient_id,
                'doctor_id' => $userid,
                'uniqueid' => $uniqueid,
                'create_date' => $create_date
            );
            $this->db->where('id',$value['id']);
            $this->db->update('prescription',$data);
        }
            return array('Status'=>'Success',
                        'Message'=>'Success Update Prescriptoin',
                        'ResponseCode'=>'00'
                );
        }else{
            return array(
                'Status' => 'Failed',
                'Message' => 'Not Found Data',
                'ResponseCode' => '02' 
            );
        } 
        
        
        

    }


    //list prescription
    function list_prescription($patient_id,$userid,$secretkey){
        
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

                $get_prescription = $this->db->query("SELECT * FROM prescription WHERE patient_id = '$patient_id' AND doctor_id = '$userid' ")->result();
                return array('Status'=>'Success',
                        'Message'=>'Success List Prescriptoin',
                        'Data' => $get_prescription,
                        'ResponseCode'=>'00'
                );

            
        }else{
            return array('Status'=>'Failed',
                    'Message'=>'User not found',
                    'ResponseCode'=>'03'
            );
        }
        

    }


    function add_laboratory($item_lab,$patient_id,$userid,$secretkey){
        

        //global declare
        $create_date = date("Y-m-d H:i:s");
        $uniqueid = 'PRS'.rand(10000,99999);
        //cek signature

        if($secretkey != $this->secretkey_server){
            return array(
                'Status' => 'Failed',
                'Message' => 'Invalid Token '.$secretkey,
                'ResponseCode' => '01' 
            );
        }
        
        $sql_login = "SELECT * FROM staff WHERE id = '$userid' AND is_active = '1' ";
        $exec_login = $this->db->query($sql_login)->row();
        if (isset($exec_login)) {
        foreach ($item_lab as $key => $value) {
            $data = array(
                'test_name' => $value['lab_name'],
                'short_name' => $value['lab_name'], 
                'test_type' => $value['note'],
                'pathology_category_id' => $value['lab_id'],
                'unit' => '',
                'sub_category' => '',
                'report_days' => '',
                'method' => '',
                'charge_id' => '',
                'patient_id' => $patient_id,
                'requested_at' => '',
                'confirmed_at' => '',
                'finished_at' => '',
                'status' => 'requested',
                'canceled_at' => '',
                'pathoable_id' => '',
                'pathoable_type' => '',
                'created_at' => $create_date
            );
            $this->db->insert('pathology',$data);
        }
            return array('Status'=>'Success',
                        'Message'=>'Success Add Laboratory',
                        'ResponseCode'=>'00'
                );
        }else{
            return array(
                'Status' => 'Failed',
                'Message' => 'Not Found Data',
                'ResponseCode' => '02' 
            );
        } 
        
        
        

    }

    //list laboratory
    function list_laboratory($patient_id,$userid,$secretkey){
        
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

                $get_lab = $this->db->query("SELECT test_name AS lab_name,pathology_category_id AS lab_id,test_type AS note FROM pathology WHERE patient_id = '$patient_id'")->result();
                return array('Status'=>'Success',
                        'Message'=>'Success List Laboratory',
                        'Data' => $get_lab,
                        'ResponseCode'=>'00'
                );

            
        }else{
            return array('Status'=>'Failed',
                    'Message'=>'User not found',
                    'ResponseCode'=>'03'
            );
        }
        

    }


    function add_radiology($item_radiology,$patient_id,$userid,$secretkey){
        

        //global declare
        $create_date = date("Y-m-d H:i:s");
        $uniqueid = 'PRS'.rand(10000,99999);
        //cek signature

        if($secretkey != $this->secretkey_server){
            return array(
                'Status' => 'Failed',
                'Message' => 'Invalid Token '.$secretkey,
                'ResponseCode' => '01' 
            );
        }
        
        $sql_login = "SELECT * FROM staff WHERE id = '$userid' AND is_active = '1' ";
        $exec_login = $this->db->query($sql_login)->row();
        if (isset($exec_login)) {
        foreach ($item_radiology as $key => $value) {
            $data = array(
                'test_name' => $value['radiology_name'],
                'short_name' => $value['radiology_name'], 
                'test_type' => $value['note'],
                'radiology_category_id' => $value['radiology_id'],
                'radiology_parameter_id' => '',
                'sub_category' => '',
                'report_days' => '',
                'charge_id' => '',
                'patient_id' => $patient_id,
                'created_at' => $create_date,
                'radioable_id' => '',
                'radioable_type' => '',
                'status' => 'requested',
                'requested_at' => '',
                'confirmed_at' => '',
                'finished_at' => '',
                'discharged' => ''
                
            );
            $this->db->insert('radio',$data);
        }
            return array('Status'=>'Success',
                        'Message'=>'Success Add Radiology',
                        'ResponseCode'=>'00'
                );
        }else{
            return array(
                'Status' => 'Failed',
                'Message' => 'Not Found Data',
                'ResponseCode' => '02' 
            );
        } 
        
    }

    function list_radiology($patient_id,$userid,$secretkey){
        
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

                $get_radiology = $this->db->query("SELECT test_name AS radiology_name,radiology_category_id AS radiology_id,test_type AS note FROM radio WHERE patient_id = '$patient_id'")->result();
                return array('Status'=>'Success',
                        'Message'=>'Success List Radiology',
                        'Data' => $get_radiology,
                        'ResponseCode'=>'00'
                );

            
        }else{
            return array('Status'=>'Failed',
                    'Message'=>'User not found',
                    'ResponseCode'=>'03'
            );
        }
        

    }

    //new 25/07/2021
    function update_picture($userid,$imagedata,$secretkey){
        
        //cek signature

        if($secretkey != $this->secretkey_server){
            return array(
                'Status' => 'Failed',
                'Message' => 'Invalid Token',
                'ResponseCode' => '01' 
            );
        }
        
        $sql_data = "SELECT * FROM users WHERE user_id = '$userid' AND is_active = 'yes' ";
        $exec_data = $this->db->query($sql_data)->row();

        if (isset($exec_data)) {

            $url_image = $this->base_url.$imagedata;
            $update_pic = $this->db->query("UPDATE patients SET image = '$url_image' WHERE id = '$userid' ");

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

    function update_profile($userid,$dob,$address,$marital_status,$mobileno,$secretkey){
        
        //cek signature

        if($secretkey != $this->secretkey_server){
            return array(
                'Status' => 'Failed',
                'Message' => 'Invalid Token',
                'ResponseCode' => '01' 
            );
        }
        
        $sql_data = "SELECT * FROM users WHERE user_id = '$userid' AND is_active = 'yes' ";
        $exec_data = $this->db->query($sql_data)->row();

        if (isset($exec_data)) {

            $data_update = array(
                'dob' => $dob,
                'address' => $address, 
                'marital_status' => $marital_status,
                'mobileno' => $mobileno
            );
            $this->db->where('id',$userid);
            $update_data = $this->db->update('patients',$data_update);

            if ($update_data == TRUE) {
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


    function change_password($userid,$old_password,$password,$conf_password,$secretkey){
        
        //cek signature

        if($secretkey != $this->secretkey_server){
            return array(
                'Status' => 'Failed',
                'Message' => 'Invalid Token',
                'ResponseCode' => '01' 
            );
        }

        //parameter


        if($password != $conf_password){
            return array(
                'Status' => 'Failed',
                'Message' => 'Wrong Password',
                'ResponseCode' => '01' 
            );
        }

        
        $check_user = "SELECT id,password FROM users WHERE id = '$userid' AND is_active = 'yes' ";
        $exec_user = $this->db->query($check_user)->row();
        
        if (isset($exec_user)) {


            //checking old password - password
            $get_password = $exec_user->password;

            if ($old_password == $get_password) {

                $update_pass = $this->db->query("UPDATE users SET password = '$password' WHERE id = '$userid' ");

                return array('Status'=>'Success',
                        'Message'=>'Success Change Password',
                        'ResponseCode'=>'00'
                );


            } else {

                return array('Status'=>'Failed',
                        'Message'=>'Wrong Password',
                        'ResponseCode'=>'03'
                );

            }
            //

            
        }else{
            return array('Status'=>'Failed',
                    'Message'=>'User not found',
                    'ResponseCode'=>'03'
            );
        }
        

    }



    function my_prescription($userid,$secretkey){
        
        //cek signature

        if($secretkey != $this->secretkey_server){
            return array(
                'Status' => 'Failed',
                'Message' => 'Invalid Token',
                'ResponseCode' => '01' 
            );
        }
        
        $sql_login = "SELECT * FROM users WHERE id = '$userid' AND is_active = 'yes' ";
        $exec_login = $this->db->query($sql_login)->row();

        if (isset($exec_login)) {

                $get_prescription = $this->db->query("SELECT 
                pp.id,pp.medicine,pp.dosage,pp.instruction,pp.quantity,pp.isrepeat,sf.id AS doctor_id,sf.name AS doctor_name 
                FROM prescription pp 
                INNER JOIN staff sf ON pp.doctor_id = sf.id
                WHERE pp.patient_id = '$userid'
                ")->result();
                return array('Status'=>'Success',
                        'Message'=>'Success List Prescriptoin',
                        'Data' => $get_prescription,
                        'ResponseCode'=>'00'
                );

            
        }else{
            return array('Status'=>'Failed',
                    'Message'=>'User not found',
                    'ResponseCode'=>'03'
            );
        }
        

    }

    function schedule_doctor($date_schedule,$userid,$doctorid,$secretkey){
        
        //cek signature
        if($secretkey != $this->secretkey_server){
            return array(
                'Status' => 'Failed',
                'Message' => 'Invalid Token',
                'ResponseCode' => '01' 
            );
        }

        $date = $date_schedule;
        $date_res = date('D', strtotime($date));
        if ($date_res == 'Sun') {
            $date_num = 1;
        }elseif ($date_res == 'Mon') {
            $date_num = 2;
        }elseif ($date_res == 'Tue') {
            $date_num = 3;
        }elseif ($date_res == 'Wed') {
            $date_num = 4;
        }elseif ($date_res == 'Thu') {
            $date_num = 5;
        }elseif ($date_res == 'Fri') {
            $date_num = 6;
        }elseif ($date_res == 'Sat') {
            $date_num = 7;
        }else{
            $date_num = 0;
        }

        $sql_user = "SELECT * FROM users WHERE id = '$userid' AND is_active = 'yes' ";
        $exec_user = $this->db->query($sql_user)->row();

        if (isset($exec_user)) {

            $sql_days = "SELECT id,(
                        CASE
                            WHEN days = '1' Then 'Sunday'
                            WHEN days = '2' Then 'Monday'
                            WHEN days = '3' Then 'Tuesday'
                            WHEN days = '4' Then 'Wednesday'
                            WHEN days = '5' Then 'Thursday'
                            WHEN days = '6' Then 'Friday'
                            WHEN days = '7' Then 'Saturday'
                        END
                        ) AS days, days AS days_id, start_time, end_time,
                        (
                            SELECT 
                            CASE
                                WHEN count(id) =  '0' THEN 'Selected'
                                ELSE 'Booked'
                            END 
                            AS status FROM appointment WHERE DATE_FORMAT(date,'%Y-%m-%d') = '$date_schedule'
                            AND DATE_FORMAT(date,'%H:%i:%s') >= start_time
                            AND DATE_FORMAT(date,'%H:%i:%s') <= end_time
                        ) AS status 
                        FROM schedule WHERE userid = '$doctorid' AND status = 1 AND days =";

            $list_schedule = $this->db->query($sql_days.$date_num)->result();

            $data = array(
                'ResponseCode' => '00',
                'Status' => 'Success',
                'Message' => 'List Data Schedule',
                'Data' => array(
                    'list' => $list_schedule
                ) 
            );

            return $data;

        }else{
            return array('Status'=>'Failed',
                    'Message'=>'User not found',
                    'ResponseCode'=>'03'
            );
        }
        
    }


    function add_patient($patient_name,$dob,$mobileno,$email,$gender,$marital_status,$blood_group,$address,$userid,$secretkey){
        
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

            $data_insert = array(
                'username' => $email,
                'password' => rand(1000,9999),
                'childs' => '',
                'role' =>  'patient',
                'verification_code' => '',
                'is_active' => 'yes'
            );

            $exec_insert = $this->db->insert('users',$data_insert);
            $insert_id = $this->db->insert_id();
            if ($exec_insert == TRUE) {
                $this->db->query("UPDATE users SET user_id = '$insert_id' WHERE id = '$insert_id' ");

                $get_last_patient = $this->db->query("SELECT patient_unique_id FROM `patients` ORDER BY id DESC LIMIT 1")->row();
                $new_unique_id = $get_last_patient->patient_unique_id + 1;

                $data_patient = array(
                    'id' => $insert_id,
                    'patient_unique_id' => $new_unique_id,
                    'email' => $email, 
                    'lang_id' => $userid,
                    'patient_name' => $patient_name,
                    'age' => '',
                    'month' => date('m', strtotime($dob)),
                    'marital_status' => $marital_status,
                    'blood_group' => $blood_group,
                    'address' => $address,
                    'mobileno' => $mobileno,
                    'dob' => $dob,
                    'gender' => $gender,
                    'guardian_email' => '',
                    'is_active' => 'yes',
                    'discharged' => '',
                    'patient_type' => '',
                    'organisation' => '',
                    'known_allergies' => '',
                    'old_patient' => 'No',
                    'disable_at' => '',
                    'note' => '',
                    'is_ipd' => '',
                    'app_key' => ''
                );
                $exec_insert = $this->db->insert('patients',$data_patient);

                return array('Status'=>'Success',
                        'Message'=>'Success Register patient',
                        'ResponseCode'=>'00'
                );
            }else{
                return array('Status'=>'Failed',
                        'Message'=>'User not found',
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


    function my_patient($userid,$secretkey){
        
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

                $get_patient = $this->db->query("SELECT id,patient_name,dob,gender,blood_group,marital_status,'Main Patient' AS type_patient FROM patients WHERE id = '$userid'
                    UNION ALL
                    SELECT id,patient_name,dob,gender,blood_group,marital_status,'Sub Patient' AS type_patient 
                    FROM patients WHERE lang_id = '$userid' ")->result();
                return array('Status'=>'Success',
                        'Message'=>'Success List Patient',
                        'Data' => $get_patient,
                        'ResponseCode'=>'00'
                );

            
        }else{
            return array('Status'=>'Failed',
                    'Message'=>'User not found',
                    'ResponseCode'=>'03'
            );
        }
        

    }

    function add_appointment($patient_id,$date,$doctor_id,$message,$userid,$secretkey){
        
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

            $sql_patient = "SELECT * FROM patients WHERE id = '$patient_id' ";
            $get_data_patient = $this->db->query($sql_patient)->row();

                $data_insert = array(
                    'patient_id' => $patient_id,
                    'appointment_no' => 'APPNO'.rand(100,999), 
                    'date' => $date,
                    'priority' => '1',
                    'patient_name' => $get_data_patient->patient_name,
                    'gender' => $get_data_patient->gender,
                    'email' => $get_data_patient->email,
                    'mobileno' => $get_data_patient->mobileno,
                    'specialist' => '',
                    'doctor' => $doctor_id,
                    'amount' => '0',
                    'message' => $message,
                    'appointment_status' => 'approved',
                    'source' => 'online',
                    'is_opd' => 'yes',
                    'is_ipd' => 'yes',
                    'live_consult' => 'yes',
                    'created_at' => date('Y-m-d h:i:s')
                );
                $data_execute = $this->db->insert('appointment',$data_insert);
                if ($data_execute = TRUE) {
                    $data = array(
                        'ResponseCode' => '00',
                        'Status' => 'Success',
                        'Message' => 'Success Add appointment' 
                    );
                }else{
                    $data = array(
                        'ResponseCode' => '02',
                        'Status' => 'Failed',
                        'Message' => 'Failed Add appointment' 
                    );
                }
                

                return $data;

            
        }else{
            return array('Status'=>'Failed',
                    'Message'=>'User not found',
                    'ResponseCode'=>'03'
            );
        }
        

    }



    
   



}

?>