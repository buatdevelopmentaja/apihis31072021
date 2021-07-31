<?php

class M_laboratory extends CI_Model{


    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->secretkey_server = $this->config->item('secretkey_server');
        $this->base_url = $this->config->item('base_url');
        $this->load->model('M_base','base');
    }


    //list prescription
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

                $this->db->select('parent.id parent_id, parent.category_name lab_name,
                c1.id lab_id,
                c1.category_name c1_category_name,
                count(category_parameter.id) as total_parameter,
                sum(parameter.price) as total_parameter_price
                ');
                $this->db->from('pathology_category as parent');
                $this->db->join('pathology_category as c1', 'c1.parent_id = parent.id', "left");
                $this->db->join('pathology_category_parameters as category_parameter', 'category_parameter.pathology_category_id = c1.id', "left outer");
                $this->db->join('pathology_parameter as parameter', 'category_parameter.pathology_parameter_id = parameter.id', "left outer");
                $this->db->where('parent.parent_id', '0');
                $this->db->group_by('c1_category_name');

                $this->db->order_by('parent_category_name','asc');
                $this->db->order_by('c1_category_name','asc');

                $data_exec =  $this->db->get()->result_array();

                return array('Status'=>'Success',
                        'Message'=>'Success List Laboratory',
                        'Data' => $data_exec,
                        'ResponseCode'=>'00'
                );

            
        }else{
            return array('Status'=>'Failed',
                    'Message'=>'User not found',
                    'ResponseCode'=>'03'
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

                $this->db->select('
                lab.id radiology_id,
                lab.lab_name radiology_name,
                test.id as test_id,
                test.parameter_name as test_name
                ');
                $this->db->from('radiology_parameter as test');
                $this->db->join('lab', 'test.lab_id = lab.id', "left");
                $this->db->order_by('category_name','asc');
                $this->db->order_by('test_name','asc');

                $data_exec =  $this->db->get()->result_array();

                return array('Status'=>'Success',
                        'Message'=>'Success List Radiology',
                        'Data' => $data_exec,
                        'ResponseCode'=>'00'
                );

            
        }else{
            return array('Status'=>'Failed',
                    'Message'=>'User not found',
                    'ResponseCode'=>'03'
            );
        }
        

    }


    
   



}

?>