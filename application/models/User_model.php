<?php
class User_Model extends CI_Model{
    
    function getall(){
        return $this->db->from('tb_user')->order_by("kd_user","desc")->get()->result();
    }

    function findone($where){
        return $this->db->get_where('tb_user',['kd_user' => $where])->result();
    }

    function save($data){
        return $this->db->insert('tb_user',$data);
    }

    function delData($id){
        return $this->db->where('kd_user',$id)->delete('tb_user');
    }

    function upData($id,$data){
        return $this->db->where('kd_user',$id)->update('tb_user',$data);
    }

    

   
      
    


}


?>