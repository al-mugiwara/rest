<?php

class User_Model extends CI_Model{
    
    function getall(){
        return $this->db->get('tb_user')->result();
    }

    function findone($where){
        return $this->db->get_where('tb_user',['kd_user' => $where])->result();
    }

    function save($data){
        return $this->db->insert('tb_user',$data);
    }
}


?>