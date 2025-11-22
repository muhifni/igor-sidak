<div class="card card-primary">
	<div class="card-header">
		<h3 class="card-title">
			<i class="fa fa-edit"></i> Tambah Data
		</h3>
	</div>
	<form action="" method="post" enctype="multipart/form-data">
		<div class="card-body">

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">No Kartu Keluarga</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="no_kk" name="no_kk" placeholder="No KK" maxlength="16"
						oninput="this.value=this.value.replace(/[^0-9]/g,'')" required>
					<small id="kk-status" class="form-text text-muted">Masukkan 16 digit No KK</small>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Kepala Keluarga</label>
				<div class="col-sm-6">
					<select name="kepala" id="kepala" class="form-control" required>
						<option value="">- Pilih Kepala Keluarga -</option>
						<?php
						// Mengecualikan penduduk yang sudah menjadi kepala keluarga atau anggota keluarga lain
						$sql_penduduk = "SELECT p.id_pend, p.nama, p.nik FROM tb_pdd p 
										 WHERE p.status = 'Ada' 
										 AND p.id_pend NOT IN (SELECT id_kepala FROM tb_kk WHERE id_kepala IS NOT NULL)
										 AND p.id_pend NOT IN (SELECT id_pend FROM tb_anggota)
										 ORDER BY p.nama ASC";
						$query_penduduk = mysqli_query($koneksi, $sql_penduduk);
						while ($data_penduduk = mysqli_fetch_array($query_penduduk)) {
							echo "<option value='" . $data_penduduk['id_pend'] . "'>" . $data_penduduk['nama'] . " (" . $data_penduduk['nik'] . ")</option>";
						}
						?>
					</select>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Desa/Kelurahan</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="desa" name="desa" placeholder="Desa/Kelurahan" required
						oninput="capitalizeWords(this)">
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">RT/RW</label>
				<div class="col-sm-3">
					<input type="text" class="form-control" id="rt" name="rt" placeholder="RT" maxlength="3"
						oninput="this.value=this.value.replace(/[^0-9]/g,'')" required>
				</div>
				<div class="col-sm-3">
					<input type="text" class="form-control" id="rw" name="rw" placeholder="RW" maxlength="3"
						oninput="this.value=this.value.replace(/[^0-9]/g,'')" required>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Kecamatan</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="kec" name="kec" placeholder="Kecamatan" required
						oninput="capitalizeWords(this)">
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Kabupaten</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="kab" name="kab" placeholder="Kabupaten" required
						oninput="capitalizeWords(this)">
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Provinsi</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="prov" name="prov" placeholder="Provinsi" required
						oninput="capitalizeWords(this)">
				</div>
			</div>

		</div>
		<div class="card-footer">
			<input type="submit" name="Simpan" value="Simpan" class="btn btn-info" id="btn-simpan" disabled>
			<a href="?page=data-kartu" title="Kembali" class="btn btn-secondary">Batal</a>
		</div>
	</form>
</div>

<?php

if (isset($_POST['Simpan'])) {
	// Cek No KK duplikat sebelum simpan
	$no_kk = $_POST['no_kk'];
	$cek_query = "SELECT COUNT(*) as count FROM tb_kk WHERE no_kk = '$no_kk'";
	$cek_result = mysqli_query($koneksi, $cek_query);
	$cek_data = mysqli_fetch_assoc($cek_result);

	if ($cek_data['count'] > 0) {
		echo "<script>
            Swal.fire({
                title: 'Gagal!',
                text: 'No KK sudah terdaftar dalam sistem',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.value){
                    window.location = 'index.php?page=add-kartu';
                }
            })</script>";
	} else {

		// Ambil id kepala dari form
		$id_kepala = isset($_POST['kepala']) ? $_POST['kepala'] : '';

		// Validasi: kepala keluarga wajib dipilih
		if (empty($id_kepala)) {
			echo "<script>
            Swal.fire({
                title: 'Gagal!',
                text: 'Silakan pilih Kepala Keluarga terlebih dahulu',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.value){
                    window.location = 'index.php?page=add-kartu';
                }
            })
        </script>";
			exit;
		}

		// Ambil nama kepala dari tb_pdd
		$sql_nama = "SELECT nama FROM tb_pdd WHERE id_pend = '$id_kepala'";
		$query_nama = mysqli_query($koneksi, $sql_nama);
		$data_nama = mysqli_fetch_assoc($query_nama);

		$nama_kepala = $data_nama ? $data_nama['nama'] : '';

		// SIMPAN KE tb_kk
		$sql_simpan = "INSERT INTO tb_kk (no_kk, kepala, id_kepala, desa, rt, rw, kec, kab, prov) VALUES (
              '" . $_POST['no_kk'] . "',
              '" . $nama_kepala . "',
              '" . $id_kepala . "',
              '" . $_POST['desa'] . "',
              '" . $_POST['rt'] . "',
              '" . $_POST['rw'] . "',
              '" . $_POST['kec'] . "',
              '" . $_POST['kab'] . "',
              '" . $_POST['prov'] . "')";
		$query_simpan = mysqli_query($koneksi, $sql_simpan);

		if ($query_simpan) {
			// ambil id_kk baru
			$id_kk_baru = mysqli_insert_id($koneksi);

			// otomatis masukkan kepala keluarga ke tb_anggota
			$sql_anggota_kepala = "INSERT INTO tb_anggota (id_kk, id_pend, hubungan)
                               VALUES ('$id_kk_baru', '$id_kepala', 'Kepala Keluarga')";
			mysqli_query($koneksi, $sql_anggota_kepala);

			mysqli_close($koneksi);

			echo "<script>
            Swal.fire({
                title: 'Tambah Data Berhasil',
                text: '',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.value){
                    window.location = 'index.php?page=data-kartu';
                }
            })
        </script>";
		} else {
			mysqli_close($koneksi);

			echo "<script>
            Swal.fire({
                title: 'Tambah Data Gagal',
                text: '',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.value){
                    window.location = 'index.php?page=add-kartu';
                }
            })
        </script>";
		}
	}

}
?>

<script>
	function capitalizeWords(input) {
		input.value = input.value.replace(/\b\w+/g, function (word) {
			return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
		});
	}

	document.addEventListener('DOMContentLoaded', function () {
		const form = document.querySelector('form');
		const submitBtn = document.getElementById('btn-simpan');
		const noKkInput = document.getElementById('no_kk');
		const kkStatus = document.getElementById('kk-status');
		const requiredFields = [
			'no_kk', 'kepala', 'desa', 'rt', 'rw', 'kec', 'kab', 'prov'
		];
		let kkValid = false;
		let kkDuplicate = false;

		function checkFormFilled() {
			let filled = true;
			requiredFields.forEach(function (fieldId) {
				const el = document.getElementById(fieldId);
				if (el) {
					if (el.tagName === 'SELECT') {
						if (el.value === '') filled = false;
					} else {
						if (el.value.trim() === '') filled = false;
					}
				}
			});
			// No KK wajib 16 digit dan tidak duplikat
			if (!kkValid || kkDuplicate) filled = false;
			submitBtn.disabled = !filled;
		}

		noKkInput.addEventListener('input', function () {
			const noKk = this.value;
			kkValid = noKk.length === 16;
			if (kkValid) {
				const xhr = new XMLHttpRequest();
				xhr.open('POST', 'admin/kartu/check_kk.php', true);
				xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
				xhr.onreadystatechange = function () {
					if (xhr.readyState === 4 && xhr.status === 200) {
						try {
							const response = JSON.parse(xhr.responseText);
							kkStatus.className = 'form-text';
							kkDuplicate = response.exists;
							if (kkDuplicate) {
								kkStatus.classList.add('text-danger');
								kkStatus.classList.remove('text-muted', 'text-success');
								kkStatus.textContent = response.message;
							} else {
								kkStatus.classList.add('text-success');
								kkStatus.classList.remove('text-muted', 'text-danger');
								kkStatus.textContent = response.message;
							}
							checkFormFilled();
						} catch (e) {
							kkStatus.className = 'form-text text-danger';
							kkStatus.textContent = 'Error saat memvalidasi No KK';
							kkDuplicate = true;
							checkFormFilled();
						}
					}
				};
				xhr.send('no_kk=' + encodeURIComponent(noKk));
			} else if (noKk.length > 0) {
				kkStatus.className = 'form-text text-muted';
				kkStatus.textContent = 'No KK harus 16 digit';
				kkDuplicate = true;
				checkFormFilled();
			} else {
				kkStatus.className = 'form-text text-muted';
				kkStatus.textContent = 'Masukkan 16 digit No KK';
				kkDuplicate = true;
				checkFormFilled();
			}
		});

		form.addEventListener('input', checkFormFilled);
		checkFormFilled();
	});
</script>