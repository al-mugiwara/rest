<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('Modelumum','m');
	}
	
	public function index()
	{
		
		$this->template->load('data/template','kader/laporan/laporanKegiatan');
	}

	public function cetak($tipe)
	{
		$this->load->library('Pdfgenerator');
		if($tipe == "kegiatan"){
			$data['perempuan'] = $this->db->query("SELECT COUNT(id_pemeriksaan) as total FROM pem_balita,tabel_user WHERE pem_balita.id_balita=tabel_user.id_user AND tabel_user.jenis_kelamin='p' AND pem_balita.tgl_pemeriksaan='".date('Y-m-d')."'")->row();

			$data['laki'] = $this->db->query("SELECT COUNT(id_pemeriksaan) as total FROM pem_balita,tabel_user WHERE pem_balita.id_balita=tabel_user.id_user AND tabel_user.jenis_kelamin='l' AND pem_balita.tgl_pemeriksaan='".date('Y-m-d')."'")->row();

			$data['jmlp'] = $this->db->query("SELECT COUNT('id_user') as total FROM tabel_user,tb_balita WHERE tabel_user.id_user = tb_balita.id_user AND tabel_user.jenis_kelamin='p'")->row();

			$data['jmll'] = $this->db->query("SELECT COUNT('id_user') as total FROM tabel_user,tb_balita WHERE tabel_user.id_user = tb_balita.id_user AND tabel_user.jenis_kelamin='l'")->row();

			$data['jmlpk'] = $this->db->query("SELECT COUNT('id_user') as total FROM tabel_user,tb_balita WHERE tabel_user.id_user = tb_balita.id_user AND tabel_user.jenis_kelamin='p' AND tb_balita.status_kia='ADA'")->row();

			$data['jmllk'] = $this->db->query("SELECT COUNT('id_user') as total FROM tabel_user,tb_balita WHERE tabel_user.id_user = tb_balita.id_user AND tabel_user.jenis_kelamin='l' AND tb_balita.status_kia='ADA'")->row();
			$data['tgl'] = date('Y-m-d');
			$file_pdf = "Rekapitulasi Kegiatan Posyandu";
			$paper    		= "F4";
			$orientation	= "landscape";
			$html     		= $this->load->view('kader/laporan/cetakKegiatan',$data,true);
			$this->pdfgenerator->generate($html, $file_pdf,$paper,$orientation);
		}
		else if($tipe == "kegiatanfilter"){
			$data['perempuan'] = $this->db->query("SELECT COUNT(id_pemeriksaan) as total FROM pem_balita,tabel_user WHERE pem_balita.id_balita=tabel_user.id_user AND tabel_user.jenis_kelamin='p' AND pem_balita.tgl_pemeriksaan='".$this->input->post('tgl')."'")->row();

			$data['laki'] = $this->db->query("SELECT COUNT(id_pemeriksaan) as total FROM pem_balita,tabel_user WHERE pem_balita.id_balita=tabel_user.id_user AND tabel_user.jenis_kelamin='l' AND pem_balita.tgl_pemeriksaan='".$this->input->post('tgl')."'")->row();

			$data['jmlp'] = $this->db->query("SELECT COUNT('id_user') as total FROM tabel_user,tb_balita WHERE tabel_user.id_user = tb_balita.id_user AND tabel_user.jenis_kelamin='p'")->row();

			$data['jmll'] = $this->db->query("SELECT COUNT('id_user') as total FROM tabel_user,tb_balita WHERE tabel_user.id_user = tb_balita.id_user AND tabel_user.jenis_kelamin='l'")->row();

			$data['jmlpk'] = $this->db->query("SELECT COUNT('id_user') as total FROM tabel_user,tb_balita WHERE tabel_user.id_user = tb_balita.id_user AND tabel_user.jenis_kelamin='p' AND tb_balita.status_kia='ADA'")->row();

			$data['jmllk'] = $this->db->query("SELECT COUNT('id_user') as total FROM tabel_user,tb_balita WHERE tabel_user.id_user = tb_balita.id_user AND tabel_user.jenis_kelamin='l' AND tb_balita.status_kia='ADA'")->row();
			$data['tgl'] = $this->input->post('tgl');

			$file_pdf 		= "Rekapitulasi Kegiatan Posyandu";
			$paper    		= "F4";
			$orientation	= "landscape";
			$html     		= $this->load->view('kader/laporan/cetakKegiatan',$data,true);
			$this->pdfgenerator->generate($html, $file_pdf,$paper,$orientation);
		}
		
	}
	
	public function balita()
	{
		
		$this->template->load('data/template','kader/laporan/laporanBalita');
	}

	public function cetakbalita($jenis)
	{
		$this->load->library('Pdfgenerator');
		if($jenis == 'periksa'){

			$data['tglawal'] 	= $this->input->post('tglawal');
			$data['tglakhir'] 	= $this->input->post('tglakhir');
			$data['pem']		= $this->db->query("SELECT * FROM pem_balita,tabel_user WHERE pem_balita.id_balita=tabel_user.id_user AND pem_balita.tgl_pemeriksaan BETWEEN '".$data['tglawal']."' AND '".$data['tglakhir']."'")->result();
			$file_pdf 			= "Laporan Pemeriksaan Balita";
			$paper    			= "F4";
			$orientation		= "landscape";
			$html     			= $this->load->view('kader/laporan/cetakperiksab',$data,true);
			$this->pdfgenerator->generate($html, $file_pdf,$paper,$orientation);
		}else if($jenis == 'vitamin'){
			$data['tglawal'] 	= $this->input->post('tglawal');
			$data['tglakhir'] 	= $this->input->post('tglakhir');
			$data['pem']		= $this->db->query("SELECT * FROM tb_vitamin,tabel_user WHERE tb_vitamin.id_balita=tabel_user.id_user AND tb_vitamin.tgl_diberikan BETWEEN '".$data['tglawal']."' AND '".$data['tglakhir']."'")->result();
			$file_pdf 			= "Laporan Pemberian Vitamin Balita";
			$paper    			= "F4";
			$orientation		= "landscape";
			$html     			= $this->load->view('kader/laporan/cetakperiksav',$data,true);
			$this->pdfgenerator->generate($html, $file_pdf,$paper,$orientation);
		}else if($jenis == 'imun'){
			$data['tglawal'] 	= $this->input->post('tglawal');
			$data['tglakhir'] 	= $this->input->post('tglakhir');
			$data['pem']		= $this->db->query("SELECT * FROM tb_imunisasi,tabel_user WHERE tb_imunisasi.id_balita=tabel_user.id_user AND tb_imunisasi.tgl_diberikan BETWEEN '".$data['tglawal']."' AND '".$data['tglakhir']."'")->result();
			$file_pdf 			= "Laporan Pemberian Vitamin Balita";
			$paper    			= "F4";
			$orientation		= "landscape";
			$html     			= $this->load->view('kader/laporan/cetakperiksai',$data,true);
			$this->pdfgenerator->generate($html, $file_pdf,$paper,$orientation);
		}
	}

	public function bumil() 
	{
		$this->template->load('data/template','kader/laporan/laporanBumil');
	}

	public function cetakbumil()
	{
		$this->load->library('Pdfgenerator');
		$data['tglawal'] 	= $this->input->post('tglawal');
		$data['tglakhir'] 	= $this->input->post('tglakhir');
		$data['pem']		= $this->db->query("SELECT * FROM pem_bumil,tabel_user WHERE pem_bumil.id_bumil=tabel_user.id_user AND pem_bumil.tgl_diperiksa BETWEEN '".$data['tglawal']."' AND '".$data['tglakhir']."'")->result();
		$file_pdf 			= "Laporan Pemberian Vitamin Balita";
		$paper    			= array(0,0,1200,1200);
		$orientation		= "landscape";
		$html     			= $this->load->view('kader/laporan/cetakperiksabumil',$data,true);
		$this->pdfgenerator->generate($html, $file_pdf,$paper,$orientation);
	}


}
