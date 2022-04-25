<?php
require APPPATH . 'libraries/REST_Controller.php';
// jangan lupa di file rest.php di config tambahkan
//$config['check_cors'] = TRUE;


class Logbook extends REST_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Log_model', 'lm');
    }

    function log_get()
    {
        $this->response($this->lm->getdata('b'), REST_Controller::HTTP_OK);
    }

    function logs_get()
    {
        $this->response($this->db->query("SELECT * FROM tb_log_d WHERE status='sh' OR status='sd' ")->result(), REST_Controller::HTTP_OK);
    }

    function logse_post()
    {
        if($this->post('submit')){
            $data = [
                'submit'    => $this->post('submit'),
                'tglawal'   => $this->post('tglawal'),
                'tglakhir'  => $this->post('tglakhir'),
                'filter'    => $this->post('filter')
            ];
            if($this->post('filter') != ""){
                $cekfil = explode(',',$this->post('filter'));
                if(count($cekfil) > 1){
                    $this->response($this->db->query("SELECT * FROM tb_log_d WHERE status='".$cekfil[0]."' AND tanggal_log BETWEEN '".$data['tglawal']."' AND '".$data['tglakhir']."' OR status='".$cekfil[1]."' AND tanggal_log BETWEEN '".$data['tglawal']."' AND '".$data['tglakhir']."'")->result(), REST_Controller::HTTP_OK);
                }else{
                    $this->response($this->db->query("SELECT * FROM tb_log_d WHERE status='".$this->post('filter')."' AND tanggal_log BETWEEN '".$data['tglawal']."' AND '".$data['tglakhir']."'")->result(), REST_Controller::HTTP_OK);
                }
            }else{
                $this->response($this->db->query("SELECT * FROM tb_log_d WHERE status='sh' AND tanggal_log BETWEEN '".$data['tglawal']."' AND '".$data['tglakhir']."' OR status='sd' AND tanggal_log BETWEEN '".$data['tglawal']."' AND '".$data['tglakhir']."'")->result(), REST_Controller::HTTP_OK);
            }
            //explode(',',$this->post('filter'));
        }else{
            $this->response($this->db->query("SELECT * FROM tb_log_d WHERE status='sh' OR status='sd' ")->result(), REST_Controller::HTTP_OK);
        }
    }

    function editlog_get($kd)
    {
        $datalog            = $this->lm->findone('tb_log_d', ['kd_log_d' => $kd])->row();
        //$this->db->query("SELECT * FROM tb_log_d WHERE kd_log_d='$kd' ")->row();
        $datafilegambar     = $this->lm->findone('tb_file_gambar', ['kd_log_d' => $kd])->result();
        $datafilelain       = $this->lm->findone('tb_file_lain', ['kd_log_d' => $kd])->result();
        $this->response(["datalog" => $datalog, "datafilegambar" => $datafilegambar, "datafilelain" => $datafilelain], REST_Controller::HTTP_OK);
    }


    function save_post()
    {
        $data = [
            'kd_log_d'           => encrypt(date('Hs')),
            'kd_log_h'           => encrypt(date('Ymis')),
            'deskripsi'          => $this->post('deskripsi'),
            'keterangan'         => $this->post('keterangan'),
            'tanggal_log'        => $this->post('tanggal_log'),
            'status'             => $this->post('status')
        ];

        if ($this->lm->save('tb_log_d', $data) > 0) {
            $this->response(['status' => true, 'message' => 'LOG BOOK CREATED'], REST_Controller::HTTP_CREATED);
        } else {
            $this->response(['status' => false, 'message' => 'FAILED TO CREATE LOGBOOK'], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    function update_put($id, $status)
    {
        $data = [
            'status'    => $status
        ];
        if ($this->lm->update('tb_log_d', 'kd_log_d', $id, $data)) {
            $this->response(['status' => true, 'message' => 'STATUS UPDATED ' . $status], REST_Controller::HTTP_OK);
        } else {
            $this->response(['status' => false, 'message' => 'STATUS FAILED TO UPDATE', REST_Controller::HTTP_BAD_REQUEST]);
        }
    }

    function tahun_get()
    {
        $th = $this->db->query("SELECT tanggal_log FROM tb_log_d GROUP BY year(tanggal_log)")->result_array();
        // view
        $isi = [];
        foreach ($th as $h) :
            $s['tahun'] = explode('-', $h['tanggal_log']);
            $isi[] = $s['tahun'][0];
        endforeach;
        $this->response($isi, REST_Controller::HTTP_OK);
    }

    function logtgl_get($tahun, $bulan)
    {
        $data = $this->db->query("SELECT * FROM tb_log_d WHERE YEAR(tanggal_log) = '$tahun' AND MONTH(tanggal_log)='$bulan' AND status='sh' OR YEAR(tanggal_log) = '$tahun' AND MONTH(tanggal_log)='$bulan' AND status='sd'")->result();
        $this->response($data, REST_Controller::HTTP_OK);
    }

    function uploadgam_post()
    {
        //not multiple upload file
        $send = [
            'kd_log_d' => encrypt(date('Hs'))
        ];
        $config['upload_path'] = './modul/';
        $config['allowed_types'] = '*';
        $this->load->library('upload');
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('filesimages')) {
            $error = array('error' => $this->upload->display_errors());
            $this->response(['error' => $error], REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $data =  $this->upload->data();
            $send['gambar'] = $data['file_name'];
            if ($this->lm->save('tb_file_gambar', $send)) {
                $this->response(['success' => $data], REST_Controller::HTTP_OK);
            } else {
                $this->response(['successuploadfile' => $data], REST_Controller::HTTP_OK);
            }
        }
    }

    function uploadfile_post()
    {
        $send = [
            'kd_log_d' => encrypt(date('Hs'))
        ];
        $config['upload_path'] = './modul/';
        $config['allowed_types'] = '*';
        $this->load->library('upload');
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('fileslain')) {
            $error = array('error' => $this->upload->display_errors());
            $this->response(['error' => $error], REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $data =  $this->upload->data();
            $send['nama_file'] = $data['file_name'];
            if ($this->lm->save('tb_file_lain', $send)) {
                $this->response(['success' => $data], REST_Controller::HTTP_OK);
            } else {
                $this->response(['successuploadfile' => $data], REST_Controller::HTTP_OK);
            }
        }
    }

    function updategam_post($id)
    {
        $cekdata = $this->lm->findone('tb_file_gambar', ['idgambar' => $id])->row();
        unlink('modul/' . $cekdata->gambar);
        $config['upload_path'] = './modul/';
        $config['allowed_types'] = '*';
        $this->load->library('upload');
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('filesimages')) {
            $error = array('error' => $this->upload->display_errors());
            $this->response(['error' => $error, 'oke' => $cekdata], REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $data =  $this->upload->data();
            $send['gambar'] = $data['file_name'];
            if ($this->lm->update('tb_file_gambar', 'idgambar', $id, $send)) {
                $this->response(['success' => $data, 'kd_log' => $cekdata->kd_log_d], REST_Controller::HTTP_OK);
            } else {
                $this->response(['successuploadfile' => $data], REST_Controller::HTTP_OK);
            }
        }
        $this->response(["data" => $cekdata], REST_Controller::HTTP_OK);
    }

    function deleteImg_delete($id)
    {
        $cekdata = $this->lm->findone('tb_file_gambar', ['idgambar' => $id])->row();
        unlink('modul/' . $cekdata->gambar);
        $kd_log = $cekdata->kd_log_d;
        if ($this->lm->delData('tb_file_gambar', 'idgambar', $id)) {
            $this->response(['success' => 'Gambar Berhasil Dihapus', 'kd_log' => $kd_log], REST_Controller::HTTP_OK);
        } else {
            $this->response(['error' => 'Gambar Gagal Dihapus'], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    function updatefile_post($id)
    {
        $cekdata = $this->lm->findone('tb_file_lain', ['id_file_lain' => $id])->row();
        unlink('modul/' . $cekdata->nama_file);
        $config['upload_path'] = './modul/';
        $config['allowed_types'] = '*';
        $this->load->library('upload');
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('fileslain')) {
            $error = array('error' => $this->upload->display_errors());
            $this->response(['error' => $error, 'oke' => $cekdata], REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $data =  $this->upload->data();
            $send['nama_file'] = $data['file_name'];
            if ($this->lm->update('tb_file_lain', 'id_file_lain', $id, $send)) {
                $this->response(['success' => $data, 'kd_log' => $cekdata->kd_log_d], REST_Controller::HTTP_OK);
            } else {
                $this->response(['successuploadfile' => $data], REST_Controller::HTTP_OK);
            }
        }
        $this->response(["data" => $cekdata], REST_Controller::HTTP_OK);
    }

    function deleteFile_delete($id)
    {
        $cekdata = $this->lm->findone('tb_file_lain', ['id_file_lain' => $id])->row();
        unlink('modul/' . $cekdata->nama_file);
        $kd_log = $cekdata->kd_log_d;
        if ($this->lm->delData('tb_file_lain', 'id_file_lain', $id)) {
            $this->response(['success' => 'File Lain Berhasil Dihapus', 'kd_log' => $kd_log], REST_Controller::HTTP_OK);
        } else {
            $this->response(['error' => 'File Lain Gagal Dihapus'], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    function updatelogs_put($kd_log)
    {
        $cekdata = $this->lm->findone('tb_log_d', ['kd_log_d' => $kd_log])->row();
        $data = [
            'deskripsi'          => $this->put('deskripsi'),
            'keterangan'         => $this->put('keterangan'),
            'tanggal_log'        => $this->put('tanggal_log'),
            'status'             => $this->put('status'),
        ];
        if ($cekdata->status == $this->put('status')) {
            if ($this->lm->update('tb_log_d', 'kd_log_d', $kd_log, $data)) {
                $this->response(['status' => true,  'message' => 'LOG BOOK UPDATED'], REST_Controller::HTTP_CREATED);
            } else {
                $this->response(['status' => false, 'message' => 'FAILED TO UPDATED LOGBOOK'], REST_Controller::HTTP_BAD_REQUEST);
            }
        }else{
            $cekimage           = $this->lm->findone('tb_file_gambar',['kd_log_d' => $kd_log])->result();
            $cekfile            = $this->lm->findone('tb_file_lain',['kd_log_d' => $kd_log])->result();

            $data['kd_log_d']   = encrypt(date('Hs'));
            $this->lm->save('tb_log_d', $data);
            foreach($cekimage as $c):
                $sendim = [
                    'kd_log_d'   => $data['kd_log_d'],
                     'gambar'    => $c->gambar   
                ];
                $this->lm->save('tb_file_gambar',$sendim);
            endforeach;
            foreach($cekfile as $ck):
                $sendfile = [
                    'kd_log_d'      => $data['kd_log_d'],
                    'nama_file'     => $ck->nama_file
                ];
                $this->lm->save('tb_file_lain',$sendfile);
            endforeach;
            $this->response(['status' => true, 'message' => "NEW UPDATED",REST_Controller::HTTP_CREATED]);

        }
    }

    function tambahgambar_post()
    {
        $send = [
            'kd_log_d' => $this->post('kd_log')
        ];
        $config['upload_path'] = './modul/';
        $config['allowed_types'] = '*';
        $this->load->library('upload');
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('filesimages')) {
            $error = array('error' => $this->upload->display_errors());
            $this->response(['error' => $error], REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $data =  $this->upload->data();
            $send['gambar'] = $data['file_name'];
            if ($this->lm->save('tb_file_gambar', $send)) {
                $this->response(['success' => $data, 'kd_log_d' => $send['kd_log_d']], REST_Controller::HTTP_OK);
            } else {
                $this->response(['successuploadfile' => $data], REST_Controller::HTTP_OK);
            }
        }
    }

    function tambahfile_post()
    {
        $send = [
            'kd_log_d' => $this->post('kd_log')
        ];
        $config['upload_path'] = './modul/';
        $config['allowed_types'] = '*';
        $this->load->library('upload');
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('fileslain')) {
            $error = array('error' => $this->upload->display_errors());
            $this->response(['error' => $error], REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $data =  $this->upload->data();
            $send['nama_file'] = $data['file_name'];
            if ($this->lm->save('tb_file_lain', $send)) {
                $this->response(['success' => $data, 'kd_log_d' => $send['kd_log_d']], REST_Controller::HTTP_OK);
            } else {
                $this->response(['successuploadfile' => $data], REST_Controller::HTTP_OK);
            }
        }
    }

    function cobaFilter_post()
    {
        $data = [
            'submit'    => $this->post('submit'),
            'tglawal'   => $this->post('tglawal'),
            'tglakhir'  => $this->post('tglakhir'),
            'filter'    => $this->post('filter')

        ];
        $this->response(['data' => $data],REST_Controller::HTTP_OK);
    }

    function cetak_get($status=null,$tgl=null)
    {
        $this->load->library('Pdfgenerator');
        if($status == null){
            $data['tglawal'] 	= "adsad";
            $data['tglakhir'] 	= "adsadsad";
            $data['pem']		= "CEK";
            $file_pdf 			= "Laporan Log Book";
            $paper    			= "F4";
            $orientation		= "landscape";
            $html     			= $this->load->view('cetaklogbook',$data,true);
            $this->response($this->pdfgenerator->generate($html, $file_pdf,$paper,$orientation));
        }else{
            $ceksta = explode(',',$status);
            $cektgl = explode(',',$tgl);
            $data['tglawal'] 	= $cektgl[0];
            $data['tglakhir'] 	= $cektgl[1];
            $data['laporan']	= $this->db->query("SELECT * FROM tb_log_d WHERE status='".$ceksta[0]."' AND tanggal_log BETWEEN '".$data['tglawal']."' AND '".$data['tglakhir']."' OR status='".$ceksta[1]."' AND tanggal_log BETWEEN '".$data['tglawal']."' AND '".$data['tglakhir']."'")->result();
            $data['judul']      = "Laporan ".$ceksta[0]."-".$ceksta[1];
            $file_pdf 			= "Laporan Log Book ".$ceksta[0]." - ".$ceksta[1];
            $paper    			= "F4";
            $orientation		= "landscape";
            $html     			= $this->load->view('cetaklogbook',$data,true);
            $this->response($this->pdfgenerator->generate($html, $file_pdf,$paper,$orientation));
        }
     
    }
    
}
