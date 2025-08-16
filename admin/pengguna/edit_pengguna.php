<?php

if (isset($_GET['kode'])) {
    $sql_cek = "SELECT * FROM tb_pengguna WHERE id_pengguna='" . $_GET['kode'] . "'";
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

            <input type='hidden' class="form-control" name="id_pengguna" value="<?php echo $data_cek['id_pengguna']; ?>"
                readonly />

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Nama User</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="nama_pengguna" name="nama_pengguna" value="<?php echo $data_cek['nama_pengguna']; ?>" />
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Username</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo $data_cek['username']; ?>" oninput="this.value = this.value.toLowerCase()" />
                    <small id="username-status" class="form-text"></small>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Password</label>
                <div class="col-sm-6">
                    <input type="password" class="form-control" id="pass" name="password" placeholder="Masukkan password baru" />
                    <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password</small>
                    <input id="mybutton" onclick="change()" type="checkbox" class="form-checkbox"> Lihat Password
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Level</label>
                <div class="col-sm-4">
                    <select name="level" id="level" class="form-control">
                        <option value="">-- Pilih Level --</option>
                        <?php
                        //menhecek data yg dipilih sebelumnya
                        if ($data_cek['level'] == "Administrator") echo "<option value='Administrator' selected>Administrator</option>";
                        else echo "<option value='Administrator'>Administrator</option>";

                        if ($data_cek['level'] == "Petugas") echo "<option value='Petugas' selected>Petugas</option>";
                        else echo "<option value='Petugas'>Petugas</option>";
                        ?>
                    </select>
                </div>
            </div>

        </div>
        <div class="card-footer">
            <input type="submit" name="Ubah" value="Simpan" class="btn btn-success" id="submit-btn">
            <a href="?page=data-pengguna" title="Kembali" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const usernameInput = document.getElementById('username');
        const usernameStatus = document.getElementById('username-status');
        const submitBtn = document.getElementById('submit-btn');
        const originalUsername = usernameInput.value.toLowerCase();

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

            // Skip validasi jika username tidak berubah
            if (username === originalUsername) {
                usernameStatus.textContent = '';
                usernameStatus.className = 'form-text';
                submitBtn.disabled = false;
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

if (isset($_POST['Ubah'])) {
    // Validasi duplikasi username (kecuali untuk user yang sedang diedit)
    $username = strtolower(trim($_POST['username'])); // Konversi ke lowercase dan hapus spasi
    $id_pengguna = $_POST['id_pengguna'];
    $check_username = "SELECT COUNT(*) as count FROM tb_pengguna WHERE username = '$username' AND id_pengguna != '$id_pengguna'";
    $result_check = mysqli_query($koneksi, $check_username);
    $row_check = mysqli_fetch_assoc($result_check);

    if ($row_check['count'] > 0) {
        echo "<script>
            Swal.fire({title: 'Username Sudah Digunakan',text: 'Silakan gunakan username lain',icon: 'error',confirmButtonText: 'OK'
            }).then((result) => {if (result.value){
                window.location = 'index.php?page=edit-pengguna&kode=$id_pengguna';
                }
            })</script>";
    } else {
        // Cek apakah password diubah
        if (!empty($_POST['password'])) {
            // Enkripsi password baru
            $password_hash = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $sql_ubah = "UPDATE tb_pengguna SET
                     nama_pengguna='" . $_POST['nama_pengguna'] . "',
                     username='" . $_POST['username'] . "',
                     password='" . $password_hash . "',
                     level='" . $_POST['level'] . "'
                     WHERE id_pengguna='" . $_POST['id_pengguna'] . "'";
        } else {
            // Update tanpa mengubah password
            $sql_ubah = "UPDATE tb_pengguna SET
                     nama_pengguna='" . $_POST['nama_pengguna'] . "',
                     username='" . $_POST['username'] . "',
                     level='" . $_POST['level'] . "'
                     WHERE id_pengguna='" . $_POST['id_pengguna'] . "'";
        }
        $query_ubah = mysqli_query($koneksi, $sql_ubah);
        mysqli_close($koneksi);

        if ($query_ubah) {
            echo "<script>
                 Swal.fire({title: 'Ubah Data Berhasil',text: '',icon: 'success',confirmButtonText: 'OK'
                 }).then((result) => {if (result.value)
                     {window.location = 'index.php?page=data-pengguna';
                     }
                 })</script>";
        } else {
            echo "<script>
                 Swal.fire({title: 'Ubah Data Gagal',text: '',icon: 'error',confirmButtonText: 'OK'
                 }).then((result) => {if (result.value)
                     {window.location = 'index.php?page=data-pengguna';
                     }
                 })</script>";
        }
    }
}
?>

<script type="text/javascript">
    function change() {
        var x = document.getElementById('pass').type;

        if (x == 'password') {
            document.getElementById('pass').type = 'text';
            document.getElementById('mybutton').innerHTML;
        } else {
            document.getElementById('pass').type = 'password';
            document.getElementById('mybutton').innerHTML;
        }
    }
</script>