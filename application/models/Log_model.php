<?php
class Log_Model extends CI_Model{
    
    function getdata($cek){
        return $this->db->get_where('tb_log_d',['status' => $cek])->result();
    }

    function findone($tabel,$where){
        return $this->db->get_where($tabel,$where);
    }

    function save($tabel,$data){
        return $this->db->insert($tabel,$data);
    }

    function delData($tabel,$where,$id){
        return $this->db->where($where,$id)->delete($tabel);
    }

    function update($tabel,$where,$id,$data)
    {
        return $this->db->where($where,$id)->update($tabel,$data);
    }
    


}


?>