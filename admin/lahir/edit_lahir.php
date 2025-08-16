<?php

if (isset($_GET['kode'])) {
	$sql_cek = "SELECT * FROM tb_lahir WHERE id_lahir='" . $_GET['kode'] . "'";
	$query_cek = mysqli_query($koneksi, $sql_cek);
	$data_cek = mysqli_fetch_array($query_cek, MYSQLI_BOTH);
}
?>

<div class="card card-success">
	<div class="card-header">
		<h3 class="card-title">
			<i class="fa fa-edit"></i> Ubah Data
		</h3>
	</div>
	<form action="" method="post" enctype="multipart/form-data">
		<div class="card-body">

			<div class="form-group row d-none">
				<label class="col-sm-2 col-form-label">No Sistem</label>
				<div class="col-sm-2">
					<input type="text" class="form-control" id="id_lahir" name="id_lahir" value="<?php echo $data_cek['id_lahir']; ?>"
						readonly />
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">NIK</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="nik" name="nik" value="<?php echo $data_cek['nik']; ?>"
						required maxlength="16" pattern="[0-9]{16}" title="NIK harus 16 digit angka">
					<small class="form-text text-muted">NIK harus 16 digit angka</small>
					<div id="nik-message"></div>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Nama</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="nama" name="nama" value="<?php echo $data_cek['nama']; ?>"
						required oninput="capitalizeWords(this)">
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Tgl Lahir</label>
				<div class="col-sm-3">
					<input type="date" class="form-control" id="tgl_lh" name="tgl_lh" value="<?php echo $data_cek['tgl_lh']; ?>"
						required>
				</div>
			</div>


			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Jenis Kelamin</label>
				<div class="col-sm-3">
					<select name="jekel" id="jekel" class="form-control">
						<option value="">-- Pilih jekel --</option>
						<?php
						//menhecek data yg dipilih sebelumnya
						if ($data_cek['jekel'] == "Laki-Laki") echo "<option value='Laki-Laki' selected>Laki-Laki</option>";
						else echo "<option value='Laki-Laki'>Laki-Laki</option>";

						if ($data_cek['jekel'] == "Perempuan") echo "<option value='Perempuan' selected>Perempuan</option>";
						else echo "<option value='Perempuan'>Perempuan</option>";
						?>
					</select>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Keluarga</label>
				<div class="col-sm-6">
					<select name="id_kk" id="id_kk" class="form-control select2bs4" required>
						<option selected="">- Pilih -</option>
						<?php
						// ambil data dari database
						$query = "SELECT k.*, p.nama as kepala FROM tb_kk k LEFT JOIN tb_pdd p ON k.id_kepala = p.id_pend";
						$hasil = mysqli_query($koneksi, $query);
						while ($row = mysqli_fetch_array($hasil)) {
						?>
							<option value="<?php echo $row['id_kk'] ?>" <?= $data_cek['id_kk'] == $row['id_kk'] ? "selected" : null ?>>
								<?php echo $row['no_kk'] ?>
								-
								<?php echo $row['kepala'] ?>
							</option>
						<?php
						}
						?>
					</select>
				</div>
			</div>

		</div>
		<div class="card-footer">
			<input type="submit" name="Ubah" value="Simpan" class="btn btn-success">
			<a href="?page=data-lahir" title="Kembali" class="btn btn-secondary">Batal</a>
		</div>
	</form>
</div>

<?php

if (isset($_POST['Ubah'])) {
	// Cek duplikasi NIK (kecuali untuk record yang sedang diedit)
	$nik = $_POST['nik'];
	$id_lahir = $_POST['id_lahir'];
	$tables_to_check = ['tb_pdd', 'tb_datang', 'tb_lahir'];
	$nik_exists = false;
	$table_found = '';
	
	foreach ($tables_to_check as $table) {
		if ($table == 'tb_lahir') {
			// Untuk tabel tb_lahir, kecualikan record yang sedang diedit
			$cek_query = "SELECT COUNT(*) as count FROM $table WHERE nik = '$nik' AND id_lahir != '$id_lahir'";
		} else {
			$cek_query = "SELECT COUNT(*) as count FROM $table WHERE nik = '$nik'";
		}
		$cek_result = mysqli_query($koneksi, $cek_query);
		$cek_data = mysqli_fetch_assoc($cek_result);
		
		if ($cek_data['count'] > 0) {
			$nik_exists = true;
			$table_names = [
				'tb_pdd' => 'Penduduk',
				'tb_datang' => 'Pendatang',
				'tb_lahir' => 'Kelahiran'
			];
			$table_found = $table_names[$table];
			break;
		}
	}
	
	if ($nik_exists) {
		echo "<script>
		Swal.fire({title: 'NIK Sudah Terdaftar',text: 'NIK sudah terdaftar di data $table_found',icon: 'error',confirmButtonText: 'OK'
		})</script>";
	} else {
		$sql_ubah = "UPDATE tb_lahir SET 
			nik='" . $_POST['nik'] . "',
			nama='" . $_POST['nama'] . "',
			tgl_lh='" . $_POST['tgl_lh'] . "',
			jekel='" . $_POST['jekel'] . "',
			id_kk='" . $_POST['id_kk'] . "'
			WHERE id_lahir='" . $_POST['id_lahir'] . "'";
		$query_ubah = mysqli_query($koneksi, $sql_ubah);
		mysqli_close($koneksi);

	if ($query_ubah) {
		echo "<script>
      Swal.fire({title: 'Ubah Data Berhasil',text: '',icon: 'success',confirmButtonText: 'OK'
      }).then((result) => {if (result.value)
        {window.location = 'index.php?page=data-lahir';
        }
      })</script>";
	} else {
		echo "<script>
      Swal.fire({title: 'Ubah Data Gagal',text: '',icon: 'error',confirmButtonText: 'OK'
      }).then((result) => {if (result.value)
        {window.location = 'index.php?page=data-lahir';
        }
      })</script>";
	}
	}
}
?>

<script>
	function capitalizeWords(input) {
		input.value = input.value.replace(/\b\w+/g, function(word) {
			return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
		});
	}

	// Validasi NIK hanya angka
	document.getElementById('nik').addEventListener('input', function(e) {
		// Hapus semua karakter non-digit
		this.value = this.value.replace(/\D/g, '');
		
		// Batasi maksimal 16 digit
		if (this.value.length > 16) {
			this.value = this.value.slice(0, 16);
		}
		
		// Validasi NIK dengan AJAX jika sudah 16 digit
		if (this.value.length === 16) {
			checkNIK(this.value);
		} else {
			// Reset pesan jika NIK belum 16 digit
			document.getElementById('nik-message').innerHTML = '';
		}
	});
	
	// Fungsi untuk cek NIK via AJAX
	function checkNIK(nik) {
		const idLahir = document.querySelector('input[name="id_lahir"]').value;
		$.ajax({
			url: '/Sistem-Data-Kependudukan/admin/check_nik.php',
			type: 'POST',
			data: { nik: nik, id_lahir: idLahir },
			dataType: 'json',
			success: function(response) {
				const messageDiv = document.getElementById('nik-message');
				if (response.exists) {
					messageDiv.innerHTML = '<small class="text-danger">' + response.message + '</small>';
				} else {
					messageDiv.innerHTML = '<small class="text-success">' + response.message + '</small>';
				}
			},
			error: function() {
				document.getElementById('nik-message').innerHTML = '<small class="text-warning">Error validasi NIK</small>';
			}
		});
	}
</script>