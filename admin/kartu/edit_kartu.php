<?php

    if(isset($_GET['kode'])){
        $sql_cek = "SELECT * FROM tb_kk WHERE id_kk='".$_GET['kode']."'";
        $query_cek = mysqli_query($koneksi, $sql_cek);
        $data_cek = mysqli_fetch_array($query_cek,MYSQLI_BOTH);
    }
?>

<div class="card card-success">
	<div class="card-header">
		<h3 class="card-title">
			<i class="fa fa-edit"></i> Ubah Data</h3>
	</div>
	<form action="" method="post" enctype="multipart/form-data">
		<div class="card-body">

			<div class="form-group row d-none">
				<label class="col-sm-2 col-form-label">No Sistem</label>
				<div class="col-sm-3">
					<input type='text' class="form-control" id="id_kk" name="id_kk" value="<?php echo $data_cek['id_kk']; ?>"
					 readonly/>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">No KK</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="no_kk" name="no_kk" value="<?php echo $data_cek['no_kk']; ?>"
					 required>
					<small id="kk-status" class="form-text"></small>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Kepala Keluarga</label>
				<div class="col-sm-6">
					<select name="kepala" id="kepala" class="form-control" required>
						<option value="">- Pilih Kepala Keluarga -</option>
						<?php
						// Mengecualikan penduduk yang sudah menjadi kepala keluarga atau anggota keluarga lain
						// kecuali kepala keluarga yang sedang diedit
						$current_id_kk = $data_cek['id_kk'];
						$sql_penduduk = "SELECT p.id_pend, p.nama, p.nik FROM tb_pdd p 
										 WHERE p.status = 'Ada' 
										 AND (p.id_pend NOT IN (SELECT id_kepala FROM tb_kk WHERE id_kepala IS NOT NULL AND id_kk != '$current_id_kk')
										 OR p.id_pend = '{$data_cek['id_kepala']}')
										 AND p.id_pend NOT IN (SELECT id_pend FROM tb_anggota WHERE id_kk != '$current_id_kk')
										 ORDER BY p.nama ASC";
						$query_penduduk = mysqli_query($koneksi, $sql_penduduk);
						while ($data_penduduk = mysqli_fetch_array($query_penduduk)) {
							if ($data_penduduk['id_pend'] == $data_cek['id_kepala']) {
								echo "<option value='" . $data_penduduk['id_pend'] . "' selected>" . $data_penduduk['nama'] . " (" . $data_penduduk['nik'] . ")</option>";
							} else {
								echo "<option value='" . $data_penduduk['id_pend'] . "'>" . $data_penduduk['nama'] . " (" . $data_penduduk['nik'] . ")</option>";
							}
						}
						?>
					</select>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Desa</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="desa" name="desa" value="<?php echo $data_cek['desa']; ?>"
					 required oninput="capitalizeWords(this)">
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">RT/RW</label>
				<div class="col-sm-3">
					<input type="text" class="form-control" id="rt" name="rt" value="<?php echo $data_cek['rt']; ?>"
					 required>
				</div>
				<div class="col-sm-3">
					<input type="text" class="form-control" id="rw" name="rw" value="<?php echo $data_cek['rw']; ?>"
					 required>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Kecamatan</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="kec" name="kec" value="<?php echo $data_cek['kec']; ?>"
					 required oninput="capitalizeWords(this)">
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Kabupaten</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="kab" name="kab" value="<?php echo $data_cek['kab']; ?>"
					 required oninput="capitalizeWords(this)">
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Provinsi</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="prov" name="prov" value="<?php echo $data_cek['prov']; ?>"
					 required oninput="capitalizeWords(this)">
				</div>
			</div>


		</div>
		<div class="card-footer">
			<input type="submit" name="Ubah" value="Simpan" class="btn btn-success" id="submit-btn">
			<a href="?page=data-kartu" title="Kembali" class="btn btn-secondary">Batal</a>
		</div>
	</form>
</div>



<?php

    if (isset ($_POST['Ubah'])){
    // Validasi duplikasi No KK
    $no_kk = $_POST['no_kk'];
    $id_kk = $_POST['id_kk'];
    
    $sql_check = "SELECT id_kk FROM tb_kk WHERE no_kk = '$no_kk' AND id_kk != '$id_kk'";
    $query_check = mysqli_query($koneksi, $sql_check);
    
    if (mysqli_num_rows($query_check) > 0) {
        echo "<script>
        Swal.fire({title: 'No KK Sudah Terdaftar',text: 'Silakan gunakan nomor KK yang lain.',icon: 'error',confirmButtonText: 'OK'
        })</script>";
    } else {
        // Ambil nama kepala keluarga berdasarkan id_pend
        $id_kepala = $_POST['kepala'];
        $sql_nama = "SELECT nama FROM tb_pdd WHERE id_pend = '$id_kepala'";
        $query_nama = mysqli_query($koneksi, $sql_nama);
        $data_nama = mysqli_fetch_assoc($query_nama);
        $nama_kepala = $data_nama['nama'];
        
        $sql_ubah = "UPDATE tb_kk SET 
        no_kk='".$_POST['no_kk']."',
        kepala='".$nama_kepala."',
        id_kepala='".$id_kepala."',
        desa='".$_POST['desa']."',
        rt='".$_POST['rt']."',
        rw='".$_POST['rw']."',
        kec='".$_POST['kec']."',
        kab='".$_POST['kab']."',
        prov='".$_POST['prov']."'
        WHERE id_kk='".$_POST['id_kk']."'";
        $query_ubah = mysqli_query($koneksi, $sql_ubah);
        mysqli_close($koneksi);

        if ($query_ubah) {
            echo "<script>
          Swal.fire({title: 'Ubah Data Berhasil',text: '',icon: 'success',confirmButtonText: 'OK'
          }).then((result) => {if (result.value)
            {window.location = 'index.php?page=data-kartu';
            }
          })</script>";
        } else {
            echo "<script>
          Swal.fire({title: 'Ubah Data Gagal',text: '',icon: 'error',confirmButtonText: 'OK'
          }).then((result) => {if (result.value)
            {window.location = 'index.php?page=data-kartu';
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

document.addEventListener('DOMContentLoaded', function() {
	const noKkInput = document.getElementById('no_kk');
	const kkStatus = document.getElementById('kk-status');
	const submitBtn = document.getElementById('submit-btn');
	const originalKk = '<?php echo $data_cek['no_kk']; ?>';
	let kkValid = true;

	function checkKkAvailability(kk) {
		if (kk.length !== 16) {
			kkStatus.textContent = 'No KK harus 16 digit';
			kkStatus.className = 'form-text text-danger';
			kkValid = false;
			checkFormValid();
			return;
		}

		if (kk === originalKk) {
			kkStatus.textContent = '';
			kkValid = true;
			checkFormValid();
			return;
		}

		fetch('check_kk.php', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded',
			},
			body: 'no_kk=' + encodeURIComponent(kk) + '&id_kk=<?php echo $data_cek['id_kk']; ?>'
		})
		.then(response => response.json())
		.then(data => {
			if (data.exists) {
				kkStatus.textContent = 'No KK sudah terdaftar';
				kkStatus.className = 'form-text text-danger';
				kkValid = false;
			} else {
				kkStatus.textContent = 'No KK tersedia';
				kkStatus.className = 'form-text text-success';
				kkValid = true;
			}
			checkFormValid();
		})
		.catch(error => {
			console.error('Error:', error);
			kkStatus.textContent = 'Error checking No KK';
			kkStatus.className = 'form-text text-danger';
			kkValid = false;
			checkFormValid();
		});
	}

	function checkFormValid() {
		submitBtn.disabled = !kkValid;
	}

	noKkInput.addEventListener('input', function() {
		const kk = this.value.trim();
		if (kk.length > 0) {
			checkKkAvailability(kk);
		} else {
			kkStatus.textContent = '';
			kkValid = false;
			checkFormValid();
		}
	});

	// Initial check
	checkFormValid();
});
</script>
