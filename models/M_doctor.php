<?php

class M_doctor extends CI_Model{


    public function __construct() {
        parent::__construct();
        $this->secretkey_server = $this->config->item('secretkey_server');
        $this->base_url = $this->config->item('base_url');
        $this->load->model('M_base','base');
    }

    function list_doctor($userid,$secretkey){
        
        //cek signature
        if($secretkey != $this->secretkey_server){
            return array(
                'Status' => 'Failed',
                'Message' => 'Invalid Token',
                'ResponseCode' => '01' 
            );
        }

        $sql_user = "SELECT * FROM users WHERE id = '$userid' AND is_active = 'yes' ";
        $exec_user = $this->db->query($sql_user)->row();

        if (isset($exec_user)) {

            $sql_doctor = "SELECT stf.id,stf.name,stf.email,stf.gender,IFNULL(rts.doctor_consultant,0) AS doctor_consultant,IFNULL(rts.price_consultant,0) AS price_consultant FROM staff stf LEFT JOIN rates rts ON rts.userid = stf.id WHERE is_active = '1' ";

            $data_exec = $this->db->query($sql_doctor)->result();

            if (isset($data_exec)) {
                $data = array(
                    'ResponseCode' => '00',
                    'Status' => 'Success',
                    'Message' => 'List Data Doctor',
                    'Data' => $data_exec
                );
            }else{
                $data = array(
                    'ResponseCode' => '03',
                    'Status' => 'Failed',
                    'Message' => 'Not Found Data Doctor', 
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