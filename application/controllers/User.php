<?php
require APPPATH . 'libraries/REST_Controller.php';
// jangan lupa di file rest.php di config tambahkan
//$config['check_cors'] = TRUE;
class User extends REST_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('User_model', 'um');
    }

    function all_get()
    {
        $this->response($this->um->getall(), REST_Controller::HTTP_OK);
    }

    function find_get()
    {
        $where = $this->get('kode');
        $this->response($this->um->findone($where));
    }

    function save_post()
    {
        $data = [
            'kd_user'     => $this->post('kd_user'),
            'username'    => $this->post('username'),
            'password'    => encrypt($this->post('password')),
            'nama'        => $this->post('nama')
        ];

        if ($this->um->save($data) > 0) {
            $this->response(['status' => true, 'message' => 'NEW USER CREATED'], REST_Controller::HTTP_CREATED);
        } else {
            $this->response(['status' => false, 'message' => 'FAILED TO CREATE NEW USER'], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    function delus_delete($kd_user)
    {
        $id = $kd_user;        
        if (empty($id)) {
            $this->response(['status' => false, REST_Controller::HTTP_BAD_REQUEST]);
        }

        if ($this->um->delData($id)) {
            $this->response(['status' => true, 'message' => "Data User Berhasil Dihapus"], REST_Controller::HTTP_NO_CONTENT);
        }
    }

    function edituser_put($kd_user)
    {
        $data = [
            'username'    => $this->put('username'),
            'password'    => encrypt($this->put('password')),
            'nama'        => $this->put('nama')
        ];
        $up = $this->um->upData($kd_user,$data);
        if($up){
            $this->response(['status' => true, REST_Controller::HTTP_OK]);
        }else{
            $this->response(['status' => false, REST_Controller::HTTP_BAD_REQUEST]);
        }
         
    }
}
