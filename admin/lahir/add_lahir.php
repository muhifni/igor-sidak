<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fa fa-edit"></i> Tambah Data
        </h3>
    </div>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="card-body">

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">NIK<span class="text-danger">*</span></label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="nik" name="nik" placeholder="NIK Bayi" required
                        maxlength="16" pattern="[0-9]{16}" title="NIK harus 16 digit angka">
                    <div id="nik-message"></div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Nama<span class="text-danger">*</span></label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Bayi" required
                        oninput="capitalizeWords(this)">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Tgl Lahir<span class="text-danger">*</span></label>
                <div class="col-sm-3">
                    <input type="date" class="form-control" id="tgl_lh" name="tgl_lh" required>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Jenis Kelamin<span class="text-danger">*</span></label>
                <div class="col-sm-3">
                    <select name="jekel" id="jekel" class="form-control">
                        <option value="">- Pilih -</option>
                        <option value="Laki-Laki">Laki-Laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Keluarga<span class="text-danger">*</span></label>
                <div class="col-sm-6">
                    <select name="id_kk" id="id_kk" class="form-control select2bs4" required>
                        <option selected="selected">- Pilih KK -</option>
                        <?php
                        // ambil data dari database
                        $query = "SELECT k.*, p.nama as kepala FROM tb_kk k LEFT JOIN tb_pdd p ON k.id_kepala = p.id_pend";
                        $hasil = mysqli_query($koneksi, $query);
                        while ($row = mysqli_fetch_array($hasil)) {
                            ?>
                            <option value="<?php echo $row['id_kk'] ?>">
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

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Ibu<span class="text-danger">*</span></label>
                <div class="col-sm-6">
                    <select name="id_ibu" id="id_ibu" class="form-control select2bs4" required>
                        <option selected="selected">- Pilih Ibu -</option>
                        <?php
                        // ambil data dari database
                        $query = "
                        SELECT p.id_pend, p.nama FROM tb_pdd p
                        WHERE p.jekel = 'Perempuan'
                        ";
                        $hasil = mysqli_query($koneksi, $query);
                        while ($row = mysqli_fetch_array($hasil)) {
                            ?>
                            <option value="<?php echo $row['id_pend'] ?>">
                                <?php echo $row['nama'] ?>
                            </option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Bapak</label>
                <div class="col-sm-6">
                    <select name="id_bapak" id="id_bapak" class="form-control select2bs4">
                        <option selected="selected">- Pilih Bapak -</option>
                        <?php
                        // ambil data dari database
                        $query = "
                        SELECT p.id_pend, p.nama FROM tb_pdd p
                        WHERE p.jekel = 'Laki-Laki'
                        ";
                        $hasil = mysqli_query($koneksi, $query);
                        while ($row = mysqli_fetch_array($hasil)) {
                            ?>
                            <option value="<?php echo $row['id_pend'] ?>">
                                <?php echo $row['nama'] ?>
                            </option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>



            <div class="card-footer">
                <input type="submit" name="Simpan" value="Simpan" class="btn btn-info">
                <a href="?page=data-lahir" title="Kembali" class="btn btn-secondary">Batal</a>
            </div>
    </form>
</div>

<?php

if (isset($_POST['Simpan'])) {
    // Cek duplikasi NIK
    $nik = $_POST['nik'];
    $tables_to_check = ['tb_pdd', 'tb_datang', 'tb_lahir'];
    $nik_exists = false;
    $table_found = '';

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
        Swal.fire({title: 'NIK Sudah Terdaftar',text: 'NIK sudah terdaftar di data $table_found',icon: 'error',confirmButtonText: 'OK'
        })</script>";
    } else {
        // 1. Simpan ke tabel kelahiran
        $sql_simpan = "INSERT INTO tb_lahir (nik, nama, tgl_lh, jekel, id_kk) VALUES (
        '" . $_POST['nik'] . "',
        '" . $_POST['nama'] . "',
        '" . $_POST['tgl_lh'] . "',
        '" . $_POST['jekel'] . "',
        '" . $_POST['id_kk'] . "')";
        $query_simpan = mysqli_query($koneksi, $sql_simpan);

        if ($query_simpan) {

            $id_kk = $_POST['id_kk'];

            // 2. Ambil data KK untuk alamat bayi
            $sql_kk = "SELECT desa, rt, rw, kab FROM tb_kk WHERE id_kk = '$id_kk'";
            $q_kk = mysqli_query($koneksi, $sql_kk);
            $data_kk = mysqli_fetch_assoc($q_kk);

            $desa = $data_kk ? $data_kk['desa'] : '';
            $rt = $data_kk ? $data_kk['rt'] : '';
            $rw = $data_kk ? $data_kk['rw'] : '';
            $kab = $data_kk ? $data_kk['kab'] : '';

            // 3. Simpan ke master penduduk (tb_pdd)
            $sql_pdd = "INSERT INTO tb_pdd 
            (nik, nama, tempat_lh, tgl_lh, jekel, desa, rt, rw, agama, kawin, pekerjaan, status)
            VALUES (
                '" . $_POST['nik'] . "',
                '" . $_POST['nama'] . "',
                '" . $kab . "',               -- tempat lahir (sementara pakai kabupaten)
                '" . $_POST['tgl_lh'] . "',
                '" . $_POST['jekel'] . "',
                '" . $desa . "',
                '" . $rt . "',
                '" . $rw . "',
                '-',                      -- agama
                'Belum Kawin',
                '-',                      -- pekerjaan
                'Ada'
            )";
            $query_pdd = mysqli_query($koneksi, $sql_pdd);

            // 4. Kalau berhasil masuk tb_pdd → masukkan juga ke tb_anggota sebagai ANAK
            if ($query_pdd) {
                $id_pend_bayi = mysqli_insert_id($koneksi); // id_pend bayi yang baru dibuat

                $sql_anggota = "INSERT INTO tb_anggota (id_kk, id_pend, hubungan) VALUES (
                '$id_kk',
                '$id_pend_bayi',
                'Anak'
            )";
                mysqli_query($koneksi, $sql_anggota);
            }

            mysqli_close($koneksi);

            echo "<script>
        Swal.fire({
            title: 'Tambah Data Berhasil',
            text: 'Data kelahiran, penduduk, dan anggota KK berhasil disimpan',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value){
                window.location = 'index.php?page=data-lahir';
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
                        window.location = 'index.php?page=add-lahir';
                    }
                })
                </script>";
        }
    }


    //selesai proses simpan data
}
?>

<script>
    function capitalizeWords(input) {
        input.value = input.value.replace(/\b\w+/g, function (word) {
            return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
        });
    }

    // Validasi NIK hanya angka
    const nikInput = document.getElementById('nik');
    const nikMessage = document.getElementById('nik-message');

    nikInput.addEventListener('input', function () {
        // Hapus semua karakter non-digit
        this.value = this.value.replace(/\D/g, '');

        // Batasi maksimal 16 digit
        if (this.value.length > 16) {
            this.value = this.value.slice(0, 16);
        }

        const nik = this.value;

        // Validasi NIK dengan AJAX jika sudah 16 digit
        if (nik.length === 16) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '../admin/check_nik.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        nikMessage.className = '';

                        if (response.exists) {
                            nikMessage.innerHTML = '<small class="text-danger">' + response.message + '</small>';
                        } else {
                            nikMessage.innerHTML = '<small class="text-success">' + response.message + '</small>';
                        }
                    } catch (e) {
                        nikMessage.innerHTML = '<small class="text-warning">Error saat memvalidasi NIK</small>';
                    }
                }
            };
            xhr.send('nik=' + encodeURIComponent(nik));
        } else if (nik.length > 0) {
            nikMessage.innerHTML = '<small class="text-muted">NIK harus 16 digit</small>';
        } else {
            nikMessage.innerHTML = '';
        }
    });
</script>