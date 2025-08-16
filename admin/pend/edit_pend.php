<?php

if (isset($_GET['kode'])) {
	$sql_cek = "SELECT * FROM tb_pdd WHERE id_pend='" . $_GET['kode'] . "'";
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
				<label class="col-sm-2 col-form-label">Id Penduduk</label>
				<div class="col-sm-2">
					<input type="text" class="form-control" id="id_pend" name="id_pend" value="<?php echo $data_cek['id_pend']; ?>"
						readonly />
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">NIK</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="nik" name="nik" value="<?php echo $data_cek['nik']; ?>" minlength="16" maxlength="16" required inputmode="numeric" pattern="[0-9]*" oninput="this.value=this.value.replace(/[^0-9]/g,'')">
					<small id="nik-status" class="form-text text-muted">Masukkan 16 digit NIK</small>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Nama</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="nama" name="nama" value="<?php echo $data_cek['nama']; ?>" required oninput="capitalizeWords(this)">
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">TTL</label>
				<div class="col-sm-3">
					<input type="text" class="form-control" id="tempat_lh" name="tempat_lh" value="<?php echo $data_cek['tempat_lh']; ?>" required oninput="capitalizeWords(this)">
				</div>
				<div class="col-sm-3">
					<input type="date" class="form-control" id="tgl_lh" name="tgl_lh" value="<?php echo $data_cek['tgl_lh']; ?>" />
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Jenis Kelamin</label>
				<div class="col-sm-3">
					<select name="jekel" id="jekel" class="form-control">
						<option value="">-- Pilih jekel --</option>
						<?php
						//menhecek data yg dipilih sebelumnya
						if ($data_cek['jekel'] == "Laki-Laki" || $data_cek['jekel'] == "Laki-Laki") echo "<option value='Laki-Laki' selected>Laki-Laki</option>";
						else echo "<option value='Laki-Laki'>Laki-Laki</option>";

						if ($data_cek['jekel'] == "Perempuan" || $data_cek['jekel'] == "Perempuan") echo "<option value='Perempuan' selected>Perempuan</option>";
						else echo "<option value='Perempuan'>Perempuan</option>";
						?>
					</select>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Desa</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="desa" name="desa" value="<?php echo $data_cek['desa']; ?>" required oninput="capitalizeWords(this)">
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">RT/RW</label>
				<div class="col-sm-3">
					<input type="text" class="form-control" id="rt" name="rt" value="<?php echo $data_cek['rt']; ?>" maxlength="3" required inputmode="numeric" pattern="[0-9]*" oninput="this.value=this.value.replace(/[^0-9]/g,'')">
				</div>
				<div class="col-sm-3">
					<input type="text" class="form-control" id="rw" name="rw" value="<?php echo $data_cek['rw']; ?>" maxlength="3" required inputmode="numeric" pattern="[0-9]*" oninput="this.value=this.value.replace(/[^0-9]/g,'')">
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Agama</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="agama" name="agama" value="<?php echo $data_cek['agama']; ?>" />
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Status Perkawinan</label>
				<div class="col-sm-3">
					<select name="kawin" id="kawin" class="form-control">
						<option value="">-- Pilih Status --</option>
						<?php
						//menhecek data yg dipilih sebelumnya
						if ($data_cek['kawin'] == "Sudah") echo "<option value='Sudah' selected>Sudah</option>";
						else echo "<option value='Sudah'>Sudah</option>";

						if ($data_cek['kawin'] == "Belum") echo "<option value='Belum' selected>Belum</option>";
						else echo "<option value='Belum'>Belum</option>";

						if ($data_cek['kawin'] == "Cerai Mati") echo "<option value='Cerai Mati' selected>Cerai Mati</option>";
						else echo "<option value='Cerai Mati'>Cerai Mati</option>";

						if ($data_cek['kawin'] == "Cerai Hidup") echo "<option value='Cerai Hidup' selected>Cerai Hidup</option>";
						else echo "<option value='Cerai Hidup'>Cerai Hidup</option>";
						?>
					</select>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Pekerjaan</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="pekerjaan" name="pekerjaan" value="<?php echo $data_cek['pekerjaan']; ?>" required oninput="capitalizeWords(this)">
				</div>
			</div>

		</div>
		<div class="card-footer">
			<input type="submit" name="Ubah" value="Simpan" class="btn btn-success" id="btn-simpan" disabled>
			<a href="?page=data-pend" title="Kembali" class="btn btn-secondary">Batal</a>
		</div>
	</form>
</div>

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
	const requiredFields = [
		'nik', 'nama', 'tempat_lh', 'tgl_lh', 'jekel', 'desa', 'rt', 'rw', 'agama', 'kawin', 'pekerjaan'
	];
	// Inisialisasi status NIK berdasarkan data yang sudah ada
	let nikValid = nikInput.value.length === 16;
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
	const idPend = document.getElementById('id_pend') ? document.getElementById('id_pend').value : '';
	if (nikValid) {
		const xhr = new XMLHttpRequest();
		xhr.open('POST', 'admin/pend/check_nik.php', true);
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.onreadystatechange = function() {
			if (xhr.readyState === 4 && xhr.status === 200) {
				try {
					const response = JSON.parse(xhr.responseText);
					nikStatus.className = 'form-text';
					// Jika NIK ditemukan dan id_pend berbeda, maka duplikat
					console.log(response);
					if (response.exists && response.id_pend !== idPend) {
						nikDuplicate = true;
						nikStatus.classList.add('text-danger');
						nikStatus.classList.remove('text-muted', 'text-success');
						nikStatus.textContent = response.message;
					} else {
						nikDuplicate = false;
						nikStatus.classList.add('text-success');
						nikStatus.classList.remove('text-muted', 'text-danger');
						nikStatus.textContent = response.message;
					}
					checkFormFilled();
				} catch (e) {
					nikStatus.className = 'form-text text-danger';
					nikStatus.textContent = 'Error saat memvalidasi NIK';
					nikDuplicate = true;
					checkFormFilled();
				}
			}
		};
		xhr.send('nik=' + encodeURIComponent(nik) + '&id_pend=' + encodeURIComponent(idPend));
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
	
	// Inisialisasi saat halaman dimuat
	document.addEventListener('DOMContentLoaded', function() {
		// Validasi NIK awal jika sudah ada data
		if (nikInput.value.length === 16) {
			nikValid = true;
			nikDuplicate = false; // Asumsi NIK yang sudah ada valid
			nikStatus.className = 'form-text text-success';
			nikStatus.textContent = 'NIK milik user ini';
		}
		checkFormFilled();
	});
</script>

<?php

if (isset($_POST['Ubah'])) {
	$sql_ubah = "UPDATE tb_pdd SET 
		nik='" . $_POST['nik'] . "',
		nama='" . $_POST['nama'] . "',
		tempat_lh='" . $_POST['tempat_lh'] . "',
		tgl_lh='" . $_POST['tgl_lh'] . "',
		jekel='" . $_POST['jekel'] . "',
		desa='" . $_POST['desa'] . "',
		rt='" . $_POST['rt'] . "',
		rw='" . $_POST['rw'] . "',
		agama='" . $_POST['agama'] . "',
		kawin='" . $_POST['kawin'] . "',
		pekerjaan='" . $_POST['pekerjaan'] . "'
		WHERE id_pend='" . $_POST['id_pend'] . "'";
	$query_ubah = mysqli_query($koneksi, $sql_ubah);
	mysqli_close($koneksi);

	if ($query_ubah) {
		echo "<script>
      Swal.fire({title: 'Ubah Data Berhasil',text: '',icon: 'success',confirmButtonText: 'OK'
      }).then((result) => {if (result.value)
        {window.location = 'index.php?page=data-pend';
        }
      })</script>";
	} else {
		echo "<script>
      Swal.fire({title: 'Ubah Data Gagal',text: '',icon: 'error',confirmButtonText: 'OK'
      }).then((result) => {if (result.value)
        {window.location = 'index.php?page=data-pend';
        }
      })</script>";
	}
}
