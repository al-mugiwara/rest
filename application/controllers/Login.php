<?php
require APPPATH . 'libraries/REST_Controller.php';
// jangan lupa di file rest.php di config tambahkan
//$config['check_cors'] = TRUE;
class Login extends REST_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Log_model', 'lm');
    }
 

 function login_post()
    {
        $username   = $this->post('username');
        $password   = encrypt($this->post('password'));
        
        if(!empty($username) && !empty($password))
        {
           $data = $this->db->get_where('tb_user',['username' => $username, 'password' => $password])->row();
           if($data){
               $this->response([
                   'status'         => TRUE,
                   'message'        => "User Login Successfull",
                   'data'           => $data
               ], REST_Controller::HTTP_OK);
           }else{
               $this->response("Wrong username or password",REST_Controller::HTTP_BAD_REQUEST);
           }
        }else{
            $this->response("Harap Masukkan username atau password dengan benar",REST_Controller::HTTP_BAD_REQUEST);
        }
        // if ($this->lm->save($data) > 0) {
        //     $this->response(['status' => true, 'message' => 'LOG BOOK CREATED'], REST_Controller::HTTP_CREATED);
        // } else {
        //     $this->response(['status' => false, 'message' => 'FAILED TO CREATE LOGBOOK'], REST_Controller::HTTP_BAD_REQUEST);
        // }
    }

    
    
}
