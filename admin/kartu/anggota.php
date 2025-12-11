<?php
$karkel = null;
$data_cek = null;

if (isset($_GET['kode'])) {
	$kode = (int) $_GET['kode'];

	$sql_cek = "SELECT k.*, COALESCE(p.nama, k.kepala) AS kepala_nama
                    FROM tb_kk k
                    LEFT JOIN tb_pdd p ON k.id_kepala = p.id_pend
                    WHERE k.id_kk = '$kode'";
	$query_cek = mysqli_query($koneksi, $sql_cek);
	$data_cek = mysqli_fetch_array($query_cek, MYSQLI_BOTH);

	if ($data_cek) {
		$karkel = $data_cek['id_kk'];
	}
}
?>



<div class="card card-primary">
	<div class="card-header">
		<h3 class="card-title">
			<i class="fa fa-users"></i> Anggota KK
		</h3>
	</div>
	<form action="" method="post" enctype="multipart/form-data">
		<div class="card-body">


			<input type='hidden' class="form-control" id="id_kk" name="id_kk" value="<?php echo $data_cek['id_kk']; ?>"
				readonly />

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">No KK | Kepala Keluarga</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="no_kk" name="no_kk"
						value="<?php echo $data_cek['no_kk'] ?? ''; ?>" readonly />
				</div>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="kepala" name="kepala"
						value="<?php echo $data_cek['kepala'] ?? ''; ?>" readonly />
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Alamat</label>
				<div class="col-sm-8">
					<input type="text" class="form-control"
						value="<?php echo ($data_cek['desa'] ?? '') . ' RT ' . ($data_cek['rt'] ?? '') . ' / RW ' . ($data_cek['rw'] ?? '') . ' - ' . ($data_cek['kab'] ?? '') . ' - ' . ($data_cek['prov'] ?? ''); ?>"
						readonly />
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Anggota</label>
				<div class="col-sm-4">
					<select name="id_pend" id="id_pend" class="form-control select2bs4" required>
						<option value="">- Penduduk -</option>
						<?php
						if (!$karkel) {
							// KK belum jelas / tidak ditemukan → jangan tampilkan apapun
							// Bisa juga dibuat disabled di HTML kalau mau
						} else {
							// Ambil penduduk yang statusnya 'Ada' dan BELUM menjadi anggota KK ini
							$sql = $koneksi->query("
                		    SELECT p.id_pend, p.nik, p.nama
	              	  		FROM tb_pdd p
 	               		    WHERE p.status = 'Ada'
                		      AND p.id_pend NOT IN (
                		          SELECT id_pend FROM tb_anggota
                		      )
                		    ORDER BY p.nama ASC;
                		");

							while ($data = $sql->fetch_assoc()) {
								?>
								<option value="<?= $data['id_pend']; ?>">
									<?= $data['nik']; ?> - <?= $data['nama']; ?>
								</option>
								<?php
							}
						}
						?>
					</select>
				</div>

				<div class="col-sm-3">
					<select name="hubungan" id="hubungan" class="form-control">
						<option>- Hub Keluarga -</option>
						<option>Istri</option>
						<option>Anak</option>
						<option>Orang Tua</option>
						<option>Mertua</option>
						<option>Menantu</option>
						<option>Cucu</option>
						<option>Saudara</option>
					</select>
				</div>
				<input type="submit" name="Simpan" value="Tambah" class="btn btn-success">
			</div>


			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>NIK</th>
								<th>Nama</th>
								<th>Jekel</th>
								<th>Hub Keluarga</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>

							<?php
							$no = 1;
							$sql = $koneksi->query("SELECT p.nik, p.nama, p.jekel, a.hubungan, a.id_anggota 
			  from tb_pdd p inner join tb_anggota a on p.id_pend=a.id_pend where status='Ada' and id_kk=$karkel");
							while ($data = $sql->fetch_assoc()) {
								?>

								<tr>
									<td>
										<?php echo $data['nik']; ?>
									</td>
									<td>
										<?php echo $data['nama']; ?>
									</td>
									<td>
										<?php echo $data['jekel']; ?>
									</td>
									<td>
										<?php echo $data['hubungan']; ?>
									</td>
									<td>
										<a href="?page=del-anggota&kode=<?php echo $data['id_anggota']; ?>"
											onclick="return confirm('Apakah anda yakin hapus data ini ?')" title="Hapus"
											class="btn btn-danger btn-sm">
											<i class="fa fa-trash"></i>
										</a>
									</td>
								</tr>

								<?php
							}
							?>
						</tbody>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
		<div class="card-footer">
			<a href="?page=data-kartu" title="Kembali" class="btn btn-warning">Kembali</a>
		</div>
	</form>
</div>

<?php

if (isset($_POST['Simpan'])) {
	//mulai proses simpan data
	$id_pend = $_POST['id_pend'];
	$id_kk = $_POST['id_kk'];

	// Cek apakah penduduk sudah terdaftar di kartu keluarga lain
	$cek_anggota = "SELECT COUNT(*) as count FROM tb_anggota WHERE id_pend = '$id_pend'";
	$result_cek = mysqli_query($koneksi, $cek_anggota);
	$row_cek = mysqli_fetch_assoc($result_cek);

	// Cek apakah penduduk sudah menjadi kepala keluarga
	$cek_kepala = "SELECT COUNT(*) as count FROM tb_kk k WHERE k.id_kepala = '$id_pend'";
	$result_kepala = mysqli_query($koneksi, $cek_kepala);
	$row_kepala = mysqli_fetch_assoc($result_kepala);

	if ($row_cek['count'] > 0 || $row_kepala['count'] > 0) {
		echo "<script>
            Swal.fire({title: 'Gagal Menambah Data',text: 'Penduduk sudah terdaftar di Kartu Keluarga lain!',icon: 'error',confirmButtonText: 'OK'
            }).then((result) => {if (result.value){
                window.location = 'index.php?page=anggota&kode=$id_kk';
                }
            })</script>";
	} else {
		$sql_simpan = "INSERT INTO tb_anggota (id_kk, id_pend, hubungan) VALUES (
                '$id_kk',
                '$id_pend',
                '" . $_POST['hubungan'] . "')";
		$query_simpan = mysqli_query($koneksi, $sql_simpan);
		mysqli_close($koneksi);

		if ($query_simpan) {
			echo "<script>
                 Swal.fire({title: 'Tambah Data Berhasil',text: '',icon: 'success',confirmButtonText: 'OK'
                 }).then((result) => {if (result.value){
                     window.location = 'index.php?page=anggota&kode=$id_kk';
                     }
                 })</script>";
		} else {
			echo "<script>
                 Swal.fire({title: 'Tambah Data Gagal',text: '',icon: 'error',confirmButtonText: 'OK'
                 }).then((result) => {if (result.value){
                     window.location = 'index.php?page=anggota&kode=$id_kk';
                     }
                 })</script>";
		}
	}
}
//selesai proses simpan data
