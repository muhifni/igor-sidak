<?php
include "../inc/koneksi.php";

if (isset($_POST['Cetak'])) {
	$id = $_POST['lahir'];
}

$tanggal = date("m/y");
$tgl = date("d/m/y");
?>


<!DOCTYPE html>
<html lang="en">

<head>
	<title>CETAK SURAT</title>
</head>

<body>
	<center>

		<h2>PEMERINTAH KABUPATEN SUMBA BARAT DAYA</h2>
		<h3>KECAMATAN LOURA
			<br>DESA KARUNI
		</h3>
		<p>________________________________________________________________________</p>

		<?php
		$sql_tampil = "SELECT 
                l.*,
                p_anak.nik as nik_anak,
                p_anak.tempat_lh as tempat_lh_anak,
                p_anak.desa as desa_anak,
                p_anak.rt as rt_anak,
                p_anak.rw as rw_anak,
                p_ibu.nik as nik_ibu,
                p_ibu.nama as nama_ibu,
                p_ibu.tempat_lh as tempat_lh_ibu,
                p_ibu.tgl_lh as tgl_lh_ibu,
                p_ibu.desa as desa_ibu,
                p_ibu.rt as rt_ibu,
                p_ibu.rw as rw_ibu,
                p_ibu.pekerjaan as pekerjaan_ibu,
                p_ibu.agama as agama_ibu,
                p_ayah.nik as nik_ayah,
                p_ayah.nama as nama_ayah,
                p_ayah.tempat_lh as tempat_lh_ayah,
                p_ayah.tgl_lh as tgl_lh_ayah,
                p_ayah.desa as desa_ayah,
                p_ayah.rt as rt_ayah,
                p_ayah.rw as rw_ayah,
                p_ayah.pekerjaan as pekerjaan_ayah,
                p_ayah.agama as agama_ayah
            FROM tb_lahir l
            LEFT JOIN tb_pdd p_anak ON l.nik = p_anak.nik
            LEFT JOIN tb_pdd p_ibu ON l.id_ibu = p_ibu.id_pend
            LEFT JOIN tb_pdd p_ayah ON l.id_bapak = p_ayah.id_pend
            WHERE l.id_lahir = '$id'";
		$query_tampil = mysqli_query($koneksi, $sql_tampil);
		while ($data = mysqli_fetch_array($query_tampil, MYSQLI_BOTH)) {
			?>
		</center>

		<center>
			<h4>
				<u>SURAT KETERANGAN KELAHIRAN</u>
			</h4>
			<h4>No Surat :
				<?php echo $data['id_lahir']; ?>/Ket.Kelahiran/
				<?php echo $tanggal; ?>
			</h4>
		</center>
		<p>Yang bertandatangan dibawah ini Kepala Desa Karuni, Kecamatan Loura, Kabupaten Sumba Barat Daya, dengan ini
			menerangkan
			bahwa :</P>

		<table>
			<tbody>
				<tr>
					<td width="180"><b>I. ANAK</b></td>
				</tr>
				<tr>
					<td>NIK</td>
					<td>:</td>
					<td><?php echo $data['nik_anak']; ?></td>
				</tr>
				<tr>
					<td>Nama</td>
					<td>:</td>
					<td><?php echo $data['nama']; ?></td>
				</tr>
				<tr>
					<td>Tempat Lahir</td>
					<td>:</td>
					<td><?php echo $data['tempat_lh_anak']; ?></td>
				</tr>
				<tr>
					<td>Tanggal Lahir</td>
					<td>:</td>
					<td><?php echo date('d-m-Y', strtotime($data['tgl_lh'])); ?></td>
				</tr>
				<tr>
					<td>Jenis Kelamin</td>
					<td>:</td>
					<td><?php echo $data['jekel']; ?></td>
				</tr>
				<tr>
					<td>Alamat</td>
					<td>:</td>
					<td>RT <?php echo $data['rt_anak']; ?> / RW <?php echo $data['rw_anak']; ?> Desa
						<?php echo $data['desa_anak']; ?>
					</td>
				</tr>
				<tr>
					<td>Anak Ke</td>
					<td>:</td>
					<td><?php echo $data['anak_ke']; ?></td>
				</tr>

				<tr>
					<td><br></td>
				</tr>
				<tr>
					<td><b>II. IBU</b></td>
				</tr>
				<tr>
					<td>NIK Ibu</td>
					<td>:</td>
					<td><?php echo $data['nik_ibu']; ?></td>
				</tr>
				<tr>
					<td>Nama Ibu</td>
					<td>:</td>
					<td><?php echo $data['nama_ibu']; ?></td>
				</tr>
				<tr>
					<td>Tempat / Tgl Lahir</td>
					<td>:</td>
					<td><?php echo $data['tempat_lh_ibu']; ?>, <?php echo date('d-m-Y', strtotime($data['tgl_lh_ibu'])); ?>
					</td>
				</tr>
				<tr>
					<td>Alamat</td>
					<td>:</td>
					<td>RT <?php echo $data['rt_ibu']; ?> / RW <?php echo $data['rw_ibu']; ?> Desa
						<?php echo $data['desa_ibu']; ?>
					</td>
				</tr>
				<tr>
					<td>Pekerjaan</td>
					<td>:</td>
					<td><?php echo $data['pekerjaan_ibu']; ?></td>
				</tr>
				<tr>
					<td>Agama</td>
					<td>:</td>
					<td><?php echo $data['agama_ibu']; ?></td>
				</tr>

				<tr>
					<td><br></td>
				</tr>
				<tr>
					<td><b>III. AYAH</b></td>
				</tr>
				<tr>
					<td>NIK Ayah</td>
					<td>:</td>
					<td><?php echo $data['nik_ayah']; ?></td>
				</tr>
				<tr>
					<td>Nama Ayah</td>
					<td>:</td>
					<td><?php echo $data['nama_ayah']; ?></td>
				</tr>
				<tr>
					<td>Tempat / Tgl Lahir</td>
					<td>:</td>
					<td><?php echo $data['tempat_lh_ayah']; ?>,
						<?php echo date('d-m-Y', strtotime($data['tgl_lh_ayah'])); ?>
					</td>
				</tr>
				<tr>
					<td>Alamat</td>
					<td>:</td>
					<td>RT <?php echo $data['rt_ayah']; ?> / RW <?php echo $data['rw_ayah']; ?> Desa
						<?php echo $data['desa_ayah']; ?>
					</td>
				</tr>
				<tr>
					<td>Pekerjaan</td>
					<td>:</td>
					<td><?php echo $data['pekerjaan_ayah']; ?></td>
				</tr>
				<tr>
					<td>Agama</td>
					<td>:</td>
					<td><?php echo $data['agama_ayah']; ?></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	<p>Demikian Surat ini dibuat, agar dapat digunakan sebagaimana mestinya.</P>
	<br>
	<p align="right">
		Loura,
		<?php echo $tgl; ?>
		<br> KEPALA DESA KARUNI
		<br>
		<br>
		<br>
		<br>
		<br>( IGOR )
	</p>


	<script>
		window.print();
	</script>

</body>

</html>