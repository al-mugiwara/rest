<center><h3>LAPORAN PEMBERIAN VITAMIN BALITA</h3></center>
<table>
	<tr>
		<td>TANGGAL PEMERIKSAAN</td>
		<td>&nbsp;:</td>
		<td><?=tgl_indo($tglawal)."-".tgl_indo($tglakhir)?></td>
	</tr>
	<tr>
		<td>NAMA POSYANDU</td>
		<td>&nbsp;:</td>
		<td>BINTANG KEJORA</td>
	</tr>
</table>
<br>
<style>
table {
	font-family: arial, sans-serif;
	border-collapse: collapse;
	width: 100%;
}

td, th {
	border: 1px solid black;
	text-align: left;
	padding: 8px;
}

tr:nth-child(even) {
	background-color: #dddddd;

}
</style>
<table >
	<tr>
		<th>No</th>
		<th>Nama Balita</th>
		<th>Umur</th>
		<th>Jenis Vitamin</th>
		<th>Tanggal Pemberian</th>
		<th>Pemberi Vitamin</th>
		<th>Keterangan</th>
	</tr> 
	<?php 
	$no=1; 
	foreach($pem as $p):
		$bidan =$this->db->get_where('tabel_user',['id_user' => $p->id_kader])->row();
		?>
		<tr>
			<td><?=$no++;?></td>
			<td><?=$p->nama;?></td>
			<td><?=$p->umur;?></td>
			<td><?=$p->jenis_vitamin;?></td>
			<td><?=tgl_indo($p->tgl_diberikan);?></td>
			<td><?=$bidan->nama;?></td>
			<td><?=$p->keterangan;?></td>
		</tr>
	<?php endforeach;?>
	
</table>
