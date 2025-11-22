<div class="card card-primary">
	<div class="card-header">
		<h3 class="card-title">
			<i class="fa fa-edit"></i> Tambah Data
		</h3>
	</div>
	<form action="" method="post" enctype="multipart/form-data">
		<div class="card-body">

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">NIK</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="nik" name="nik" placeholder="NIK" minlength="16" maxlength="16" oninput="this.value=this.value.replace(/[^0-9]/g,'')" required>
					<small id="nik-status" class="form-text text-muted">Masukkan 16 digit NIK</small>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Nama</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="nama_datang" name="nama_datang" placeholder="Nama Pendatang" required oninput="capitalizeWords(this)">
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Jenis Kelamin</label>
				<div class="col-sm-3">
					<select name="jekel" id="jekel" class="form-control">
						<option value="">- Pilih -</option>
						<option value="Laki-Laki">Laki-Laki</option>
						<option value="Perempuan">Perempuan</option>
					</select>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Tgl Datang</label>
				<div class="col-sm-3">
					<input type="date" class="form-control" id="tgl_datang" name="tgl_datang" required>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Pelapor</label>
				<div class="col-sm-6">
					<select name="pelapor" id="pelapor" class="form-control select2bs4" required>
						<option selected="selected">- Pilih Penduduk -</option>
						<?php
						// ambil data dari database
						$query = "select * from tb_pdd where status='Ada'";
						$hasil = mysqli_query($koneksi, $query);
						while ($row = mysqli_fetch_array($hasil)) {
						?>
							<option value="<?php echo $row['id_pend'] ?>">
								<?php echo $row['nik'] ?>
								-
								<?php echo $row['nama'] ?>
							</option>
						<?php
						}
						?>
					</select>
				</div>
			</div>

		</div>
		<div class="card-footer">
			<input type="submit" name="Simpan" value="Simpan" class="btn btn-info" id="btn-simpan" disabled>
			<a href="?page=data-datang" title="Kembali" class="btn btn-secondary">Batal</a>
		</div>
	</form>
</div>

<?php

if (isset($_POST['Simpan'])) {
	// Cek NIK duplikat di semua tabel sebelum simpan
	$nik = $_POST['nik'];
	$tables_to_check = ['tb_pdd', 'tb_datang', 'tb_lahir'];
	$nik_exists = false;
	$table_found = '';

	// Cek NIK di tabel yang menggunakan kolom nik
	foreach ($tables_to_check as $table) {
		$cek_query = "SELECT COUNT(*) as count FROM $table WHERE nik = '$nik'";
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
            Swal.fire({
                title: 'Gagal!',
                text: 'NIK sudah terdaftar di data $table_found',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.value){
                    window.location = 'index.php?page=add-datang';
                }
            })</script>";
	} else {
		//mulai proses simpan data
		$sql_simpan = "INSERT INTO tb_datang (nik, nama_datang, jekel, tgl_datang, pelapor) VALUES (
            '" . $_POST['nik'] . "',
            '" . $_POST['nama_datang'] . "',
            '" . $_POST['jekel'] . "',
            '" . $_POST['tgl_datang'] . "',
            '" . $_POST['pelapor'] . "')";
		$query_simpan = mysqli_query($koneksi, $sql_simpan);
		mysqli_close($koneksi);

		if ($query_simpan) {
			echo "<script>
      Swal.fire({title: 'Tambah Data Berhasil',text: '',icon: 'success',confirmButtonText: 'OK'
      }).then((result) => {if (result.value){
          window.location = 'index.php?page=data-datang';
          }
      })</script>";
		} else {
			echo "<script>
      Swal.fire({title: 'Tambah Data Gagal',text: '',icon: 'error',confirmButtonText: 'OK'
      }).then((result) => {if (result.value){
          window.location = 'index.php?page=add-datang';
          }
      })</script>";
		}
	}
}
//selesai proses simpan data
?>

<script>
	function capitalizeWords(input) {
		input.value = input.value.replace(/\b\w+/g, function(word) {
			return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
		});
	}

	const form = document.querySelector('form');
	const submitBtn = document.getElementById('btn-simpan');
	const nikInput = document.getElementById('nik');
	const nikStatus = document.getElementById('nik-status');
	const requiredFields = ['nik', 'nama_datang', 'jekel', 'tgl_datang', 'pelapor'];
	let nikValid = false;
	let nikDuplicate = false;

	function checkFormFilled() {
		let filled = true;
		requiredFields.forEach(function(fieldId) {
			const el = document.getElementById(fieldId);
			if (el) {
				if (el.tagName === 'SELECT') {
					if (el.value === '') filled = false;
				} else {
					if (el.value.trim() === '') filled = false;
				}
			}
		});
		// NIK wajib 16 digit dan tidak duplikat
		if (!nikValid || nikDuplicate) filled = false;
		submitBtn.disabled = !filled;
	}

	nikInput.addEventListener('input', function() {
		const nik = this.value;
		nikValid = nik.length === 16;
		if (nikValid) {
			const xhr = new XMLHttpRequest();
			xhr.open('POST', '../check_nik.php', true);
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xhr.onreadystatechange = function() {
				if (xhr.readyState === 4 && xhr.status === 200) {
					try {
						const response = JSON.parse(xhr.responseText);
						console.log(response);
						nikStatus.className = 'form-text';
						nikDuplicate = response.exists;
						if (nikDuplicate) {
							nikStatus.classList.add('text-danger');
							nikStatus.classList.remove('text-muted', 'text-success');
							nikStatus.textContent = response.message;
						} else {
							nikStatus.classList.add('text-success');
							nikStatus.classList.remove('text-muted', 'text-danger');
							nikStatus.textContent = response.message;
						}
						checkFormFilled();
					} catch (e) {
						console.log(e);
						nikStatus.className = 'form-text text-danger';
						nikStatus.textContent = 'Error saat memvalidasi NIK';
						nikDuplicate = true;
						checkFormFilled();
					}
				}
			};
			xhr.send('nik=' + encodeURIComponent(nik));
		} else if (nik.length > 0) {
			nikStatus.className = 'form-text text-muted';
			nikStatus.textContent = 'NIK harus 16 digit';
			nikDuplicate = true;
			checkFormFilled();
		} else {
			nikStatus.className = 'form-text text-muted';
			nikStatus.textContent = 'Masukkan 16 digit NIK';
			nikDuplicate = true;
			checkFormFilled();
		}
	});

	form.addEventListener('input', checkFormFilled);
	document.addEventListener('DOMContentLoaded', checkFormFilled);
</script>