<?php
require APPPATH. 'libraries/REST_Controller.php';

class User extends REST_Controller{
  
    function __construct()
    {
        parent::__construct();
        $this->load->model('User_model','um');
    }

    function all_get(){
        $this->response($this->um->getall());
    }

    function find_get(){
        $where = $this->get('kode');
        $this->response($this->um->findone($where));
    }

    function save_post(){
        $data = $this->post();
        $this->response($this->um->save($data));
    }
    

    
}

?>