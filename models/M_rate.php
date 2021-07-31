<?php

class M_rate extends CI_Model{


    public function __construct() {
        parent::__construct();
        $this->secretkey_server = $this->config->item('secretkey_server');
        $this->base_url = $this->config->item('base_url');
        $this->load->model('M_base','base');
    }

    function list_rate($userid,$secretkey){
        
        //cek signature
        if($secretkey != $this->secretkey_server){
            return array(
                'Status' => 'Failed',
                'Message' => 'Invalid Token',
                'ResponseCode' => '01' 
            );
        }

        $sql_user = "SELECT * FROM staff WHERE id = '$userid' AND is_active = '1' ";
        $exec_user = $this->db->query($sql_user)->row();

        if (isset($exec_user)) {

            $sql_days = "SELECT price_consultant,doctor_consultant,bank_name,account_number,account_name FROM rates WHERE userid = '$userid' AND status = 1";

            $data_exec = $this->db->query($sql_days)->row();

            if (isset($data_exec)) {
                $data = array(
                    'ResponseCode' => '00',
                    'Status' => 'Success',
                    'Message' => 'List Data Rate',
                    'Data' => array(
                        'price_consultant' => $data_exec->price_consultant,
                        'doctor_consultant' => $data_exec->doctor_consultant, 
                        'bank_name' => $data_exec->bank_name,
                        'account_number' => $data_exec->account_number,
                        'account_name' => $data_exec->account_name
                    ) 
                );
            }else{
                $data = array(
                    'ResponseCode' => '03',
                    'Status' => 'Failed',
                    'Message' => 'Not Found Data Rate', 
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



    function update_rate($price_consultant,$doctor_consultant,$bank_name,$account_number,$account_name,$userid,$secretkey){
        //cek signature
        if($secretkey != $this->secretkey_server){
            return array(
                'Status' => 'Failed',
                'Message' => 'Invalid Token',
                'ResponseCode' => '01' 
            );
        }

        $sql_user = "SELECT * FROM staff WHERE id = '$userid' AND is_active = '1' ";
        $exec_user = $this->db->query($sql_user)->row();

        if (isset($exec_user)) {


            //check data rate
            $sql_rate = "SELECT id FROM rates WHERE userid = '$userid' ";
            $exec_rate = $this->db->query($sql_rate)->row();

            if (isset($exec_rate)) {
                $data_insert = array(
                    'price_consultant' => $price_consultant,
                    'doctor_consultant' => $doctor_consultant, 
                    'bank_name' => $bank_name,
                    'account_number' => $account_number,
                    'account_name' => $account_name,
                    'update_date' => date('Y-m-d h:i:s')
                );
                $this->db->where('userid',$userid);
                $exec_update = $this->db->update('rates',$data_insert);

                if ($exec_update = TRUE) {
                    $data = array(
                        'ResponseCode' => '00',
                        'Status' => 'Success',
                        'Message' => 'Success Update Rate' 
                    );
                }else{
                    $data = array(
                        'ResponseCode' => '02',
                        'Status' => 'Failed',
                        'Message' => 'Failed Update Rate' 
                    );
                }

            }else{
                $data = array(
                    'ResponseCode' => '03',
                    'Status' => 'Failed',
                    'Message' => 'Not Found Data Rate', 
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