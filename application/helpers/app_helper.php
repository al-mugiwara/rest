<?php 
defined('BASEPATH') OR exit ('No direct script access allowed');
function tgl_indo($tanggal){
	$bulan = array (
		1 =>   'Januari',
		'Februari',
		'Maret',
		'April',
		'Mei',
		'Juni',
		'Juli',
		'Agustus',
		'September',
		'Oktober',
		'November',
		'Desember'
	);
	$pecahkan = explode('-', $tanggal); 
	return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}
function tgl_indo_to_database($string)
{
  $satu = explode('-', $string);
  return $satu[2] . '-' . $satu[1] . '-' . $satu[0];
}
function rupiah($nominal){
	return "Rp. ".number_format ($nominal,0,",",".");
}

 function random_color_part(){
      return str_pad(dechex(mt_rand(0,255)), 2, '0',STR_PAD_LEFT);
    }

 function random_color(){
     return random_color_part().random_color_part().random_color_part();
   }

   function bulane($bulan){
   	$data_bulan = array(
            '1' => 'Jan',
            '2' => 'Feb',
            '3' => 'Mar',
            '4' => 'Apr',
            '5' => 'Mei',
            '6' => 'Jun',
            '7' => 'Jul',
            '8' => 'Agu',
            '9' => 'Sep',
            '10' => 'Okt',
            '11' => 'Nov',
            '12' => 'Des',
    );
    return $data_bulan[$bulan];
   }


 ?>