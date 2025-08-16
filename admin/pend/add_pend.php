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
					<input type="text" class="form-control" id="nik" name="nik" placeholder="NIK" minlength="16" maxlength="16" required inputmode="numeric" pattern="[0-9]*" oninput="this.value=this.value.replace(/[^0-9]/g,'')">
					<small id="nik-status" class="form-text text-muted">Masukkan 16 digit NIK</small>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Nama</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Penduduk" required oninput="capitalizeWords(this)">
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">TTL</label>
				<div class="col-sm-3">
					<input type="text" class="form-control" id="tempat_lh" name="tempat_lh" placeholder="Tempat Lahir" oninput="capitalizeWords(this)" required>
				</div>
				<div class="col-sm-3">
					<input type="date" class="form-control" id="tgl_lh" name="tgl_lh" placeholder="Tanggal Lahir" required>
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
				<label class="col-sm-2 col-form-label">Desa</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="desa" name="desa" placeholder="Desa" required oninput="capitalizeWords(this)">
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">RT/RW</label>
				<div class="col-sm-3">
					<input type="text" class="form-control" id="rt" name="rt" placeholder="RT" maxlength="3" required inputmode="numeric" pattern="[0-9]*" oninput="this.value=this.value.replace(/[^0-9]/g,'')">
				</div>
				<div class="col-sm-3">
					<input type="text" class="form-control" id="rw" name="rw" placeholder="RW" maxlength="3" required inputmode="numeric" pattern="[0-9]*" oninput="this.value=this.value.replace(/[^0-9]/g,'')">
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Agama</label>
				<div class="col-sm-3">
					<select name="agama" id="agama" class="form-control">
						<option value="">- Pilih -</option>
						<option value="Islam">Islam</option>
						<option value="Kristen">Kristen</option>
						<option value="Katholik">Katholik</option>
						<option value="Hindu">Hindu</option>
						<option value="Budha">Budha</option>
						<option value="Konghucu">Konghucu</option>
					</select>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Status Perkawinan</label>
				<div class="col-sm-3">
					<select name="kawin" id="kawin" class="form-control">
						<option value="">- Pilih -</option>
						<option value="Sudah">Sudah</option>
						<option value="Belum">Belum</option>
						<option value="Cerai Mati">Cerai Mati</option>
						<option value="Cerai Hidup">Cerai Hidup</option>
					</select>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Pekerjaan</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="pekerjaan" name="pekerjaan" placeholder="Pekerjaan" required oninput="capitalizeWords(this)">
				</div>
			</div>

		</div>
		<div class="card-footer">
			<input type="submit" name="Simpan" value="Simpan" class="btn btn-info" id="btn-simpan" disabled>
			<a href="?page=data-pend" title="Kembali" class="btn btn-secondary">Batal</a>
		</div>
	</form>
</div>

<?php

if (isset($_POST['Simpan'])) {
	// Cek NIK duplikat sebelum simpan
	$nik = $_POST['nik'];
	$cek_query = "SELECT COUNT(*) as count FROM tb_pdd WHERE nik = '$nik'";
	$cek_result = mysqli_query($koneksi, $cek_query);
	$cek_data = mysqli_fetch_assoc($cek_result);

	if ($cek_data['count'] > 0) {
		echo "<script>
            Swal.fire({
                title: 'Gagal!',
                text: 'NIK sudah terdaftar dalam sistem',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.value){
                    window.location = 'index.php?page=add-pend';
                }
            })</script>";
	} else {
		//mulai proses simpan data
		$sql_simpan = "INSERT INTO tb_pdd (nik, nama, tempat_lh, tgl_lh, jekel, desa, rt, rw, agama, kawin, pekerjaan, status) VALUES (
                '" . $_POST['nik'] . "',
                '" . $_POST['nama'] . "',
    '" . $_POST['tempat_lh'] . "',
   '" . $_POST['tgl_lh'] . "',
                '" . $_POST['jekel'] . "',
                '" . $_POST['desa'] . "',
    '" . $_POST['rt'] . "',
    '" . $_POST['rw'] . "',
    '" . $_POST['agama'] . "',
    '" . $_POST['kawin'] . "',
    '" . $_POST['pekerjaan'] . "',
                'Ada')";
		$query_simpan = mysqli_query($koneksi, $sql_simpan);
		mysqli_close($koneksi);

		if ($query_simpan) {
			echo "<script>
                Swal.fire({
                    title: 'Tambah Data Berhasil',
                    text: '',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.value){
                        window.location = 'index.php?page=data-pend';
                    }
                })</script>";
		} else {
			echo "<script>
                Swal.fire({
                    title: 'Tambah Data Gagal',
                    text: '',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.value){
                        window.location = 'index.php?page=add-pend';
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
const requiredFields = [
  'nik', 'nama', 'tempat_lh', 'tgl_lh', 'jekel', 'desa', 'rt', 'rw', 'agama', 'kawin', 'pekerjaan'
];
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
    xhr.open('POST', 'admin/pend/check_nik.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
      if (xhr.readyState === 4 && xhr.status === 200) {
        try {
          const response = JSON.parse(xhr.responseText);
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