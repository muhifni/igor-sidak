<?php

if (isset($_GET['kode'])) {
	$sql_cek = "SELECT d.id_datang, d.nik, d.nama_datang, d.jekel, d.tgl_datang, p.id_pend, p.nama from 
		tb_datang d inner join tb_pdd p on d.pelapor=p.id_pend WHERE id_datang='" . $_GET['kode'] . "'";
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
					<input type="text" class="form-control" id="id_datang" name="id_datang" value="<?php echo $data_cek['id_datang']; ?>"
						readonly />
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">NIK</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="nik" name="nik" minlength="16" maxlength="16" oninput="this.value=this.value.replace(/[^0-9]/g,'')" value="<?php echo $data_cek['nik']; ?>"
						required>
					<small id="nik-status" class="form-text text-muted">Masukkan 16 digit NIK</small>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Nama</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="nama_datang" name="nama_datang" value="<?php echo $data_cek['nama_datang']; ?>"
						required oninput="capitalizeWords(this)">
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
				<label class="col-sm-2 col-form-label">Tgl Datang</label>
				<div class="col-sm-3">
					<input type="date" class="form-control" id="tgl_datang" name="tgl_datang" value="<?php echo $data_cek['tgl_datang']; ?>"
						required>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Pelapor</label>
				<div class="col-sm-6">
					<select name="pelapor" id="prlapor" class="form-control select2bs4" required>
						<option selected="">- Pilih -</option>
						<?php
						// ambil data dari database
						$query = "select * from tb_pdd";
						$hasil = mysqli_query($koneksi, $query);
						while ($row = mysqli_fetch_array($hasil)) {
						?>
							<option value="<?php echo $row['id_pend'] ?>" <?= $data_cek['id_pend'] == $row['id_pend'] ? "selected" : null ?>>
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
			<input type="submit" name="Ubah" value="Simpan" class="btn btn-success" id="btn-simpan">
			<a href="?page=data-datang" title="Kembali" class="btn btn-secondary">Batal</a>
		</div>
	</form>
</div>

<?php

if (isset($_POST['Ubah'])) {
    // Cek NIK duplikat di semua tabel sebelum update
    $nik = $_POST['nik'];
    $id_datang = $_POST['id_datang'];
    $tables_to_check = ['tb_pdd', 'tb_datang', 'tb_lahir'];
    $nik_exists = false;
    $table_found = '';
    
    // Cek NIK di tabel yang menggunakan kolom nik
    foreach ($tables_to_check as $table) {
        if ($table === 'tb_datang') {
            // Untuk tabel yang sama, cek apakah NIK digunakan oleh record lain
            $cek_query = "SELECT COUNT(*) as count FROM $table WHERE nik = '$nik' AND id_datang != '$id_datang'";
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
            Swal.fire({
                title: 'Gagal!',
                text: 'NIK sudah terdaftar di data $table_found',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.value){
                    window.location = 'index.php?page=data-datang';
                }
            })</script>";
    } else {
        $sql_ubah = "UPDATE tb_datang SET 
            nik='" . $_POST['nik'] . "',
            nama_datang='" . $_POST['nama_datang'] . "',
            jekel='" . $_POST['jekel'] . "',
            tgl_datang='" . $_POST['tgl_datang'] . "',
            pelapor='" . $_POST['pelapor'] . "'
            WHERE id_datang='" . $_POST['id_datang'] . "'";
        $query_ubah = mysqli_query($koneksi, $sql_ubah);
        mysqli_close($koneksi);

	if ($query_ubah) {
		echo "<script>
      Swal.fire({title: 'Ubah Data Berhasil',text: '',icon: 'success',confirmButtonText: 'OK'
      }).then((result) => {if (result.value)
        {window.location = 'index.php?page=data-datang';
        }
      })</script>";
	} else {
		echo "<script>
      Swal.fire({title: 'Ubah Data Gagal',text: '',icon: 'error',confirmButtonText: 'OK'
      }).then((result) => {if (result.value)
        {window.location = 'index.php?page=data-datang';
        }
      })</script>";
	}
    }
}
?>

<script>
	function capitalizeWords(input) {
		let value = input.value;
		let words = value.split(' ');
		let capitalizedWords = words.map(word => {
			if (word.length > 0) {
				return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
			}
			return word;
		});
		input.value = capitalizedWords.join(' ');
	}

	const form = document.querySelector('form');
	const submitBtn = document.getElementById('btn-simpan');
	const nikInput = document.getElementById('nik');
	const nikStatus = document.getElementById('nik-status');
	const requiredFields = ['nik', 'nama_datang', 'jekel', 'tgl_datang', 'pelapor'];
	let nikValid = false;
	let nikDuplicate = false;
	const currentIdDatang = document.getElementById('id_datang').value;

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
			xhr.open('POST', '/Sistem-Data-Kependudukan/admin/check_nik.php', true);
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
			xhr.send('nik=' + encodeURIComponent(nik) + '&id_datang=' + encodeURIComponent(currentIdDatang));
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

	// Validasi awal saat halaman dimuat
	document.addEventListener('DOMContentLoaded', function() {
		const nik = nikInput.value;
		nikValid = nik.length === 16;
		if (nikValid) {
			nikStatus.className = 'form-text text-success';
			nikStatus.textContent = 'NIK milik user ini';
			nikDuplicate = false;
		}
		checkFormFilled();
	});

	form.addEventListener('input', checkFormFilled);
</script>