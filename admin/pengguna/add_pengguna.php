<div class="card card-primary">
	<div class="card-header">
		<h3 class="card-title">
			<i class="fa fa-edit"></i> Tambah Data</h3>
	</div>
	<form action="" method="post" enctype="multipart/form-data">
		<div class="card-body">

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Nama User</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="nama_pengguna" name="nama_pengguna" placeholder="Nama user" required>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Username</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="username" name="username" placeholder="Username" oninput="this.value = this.value.toLowerCase()">
					<small id="username-status" class="form-text"></small>					
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Password</label>
				<div class="col-sm-6">
					<input type="password" class="form-control" id="password" name="password" placeholder="Password">
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Level</label>
				<div class="col-sm-4">
					<select name="level" id="level" class="form-control">
						<option>- Pilih -</option>
						<option>Administrator</option>
						<option>Petugas</option>
					</select>
				</div>
			</div>

		</div>
		<div class="card-footer">
			<input type="submit" name="Simpan" value="Simpan" class="btn btn-info" id="submit-btn">
			<a href="?page=data-pengguna" title="Kembali" class="btn btn-secondary">Batal</a>
		</div>
	</form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const usernameInput = document.getElementById('username');
    const usernameStatus = document.getElementById('username-status');
    const submitBtn = document.getElementById('submit-btn');
    
    usernameInput.addEventListener('input', function() {
        const username = this.value.toLowerCase().trim();
        
        if (username.length === 0) {
            usernameStatus.textContent = '';
            usernameStatus.className = 'form-text';
            submitBtn.disabled = false;
            return;
        }
        
        if (username.length < 3) {
            usernameStatus.textContent = 'Username minimal 3 karakter';
            usernameStatus.className = 'form-text text-warning';
            submitBtn.disabled = true;
            return;
        }
        
        // AJAX check untuk duplikasi username
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'admin/pengguna/check_username.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                if (xhr.responseText === 'exists') {
                    usernameStatus.textContent = 'Username sudah digunakan';
                    usernameStatus.className = 'form-text text-danger';
                    submitBtn.disabled = true;
                } else {
                    usernameStatus.textContent = 'Username tersedia';
                    usernameStatus.className = 'form-text text-success';
                    submitBtn.disabled = false;
                }
            }
        };
        
        xhr.send('username=' + encodeURIComponent(username));
    });
});
</script>

<?php

    if (isset ($_POST['Simpan'])){
    //mulai proses simpan data
        
        // Validasi duplikasi username di server-side
         $username = strtolower(trim($_POST['username'])); // Konversi ke lowercase dan hapus spasi
         $check_username = "SELECT COUNT(*) as count FROM tb_pengguna WHERE username = '$username'";
         $result_check = mysqli_query($koneksi, $check_username);
         $row_check = mysqli_fetch_assoc($result_check);
        
        if ($row_check['count'] > 0) {
            echo "<script>
            Swal.fire({title: 'Username Sudah Digunakan',text: 'Silakan gunakan username lain',icon: 'error',confirmButtonText: 'OK'
            }).then((result) => {if (result.value){
                window.location = 'index.php?page=add-pengguna';
                }
            })</script>";
        } else {
             // Enkripsi password sebelum disimpan
             $password_hash = password_hash($_POST['password'], PASSWORD_BCRYPT);
             
             $sql_simpan = "INSERT INTO tb_pengguna (nama_pengguna,username,password,level) VALUES (
             '".$_POST['nama_pengguna']."',
             '".$_POST['username']."',
             '".$password_hash."',
             '".$_POST['level']."')";
            $query_simpan = mysqli_query($koneksi, $sql_simpan);
             mysqli_close($koneksi);

             if ($query_simpan) {
                 echo "<script>
                 Swal.fire({title: 'Tambah Data Berhasil',text: '',icon: 'success',confirmButtonText: 'OK'
                 }).then((result) => {if (result.value){
                     window.location = 'index.php?page=data-pengguna';
                     }
                 })</script>";
             } else {
                 echo "<script>
                 Swal.fire({title: 'Tambah Data Gagal',text: '',icon: 'error',confirmButtonText: 'OK'
                 }).then((result) => {if (result.value){
                     window.location = 'index.php?page=add-pengguna';
                     }
                 })</script>";
             }
         }
     }
     //selesai proses simpan data
