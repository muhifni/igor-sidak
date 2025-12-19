<?php
if (isset($_GET['kode'])) {
    // 1. Ambil NIK dari data lahir sebelum dihapus
    $sql_cek = "SELECT nik FROM tb_lahir WHERE id_lahir='" . $_GET['kode'] . "'";
    $query_cek = mysqli_query($koneksi, $sql_cek);
    $data_lahir = mysqli_fetch_array($query_cek, MYSQLI_BOTH);

    if ($data_lahir) {
        $nik = $data_lahir['nik'];

        // 2. Ambil id_pend dari tb_pdd menggunakan NIK
        $sql_pdd = "SELECT id_pend FROM tb_pdd WHERE nik='$nik'";
        $query_pdd = mysqli_query($koneksi, $sql_pdd);
        $data_pdd = mysqli_fetch_array($query_pdd, MYSQLI_BOTH);

        if ($data_pdd) {
            $id_pend = $data_pdd['id_pend'];

            // 3. Hapus dari tb_anggota
            mysqli_query($koneksi, "DELETE FROM tb_anggota WHERE id_pend='$id_pend'");

            // 4. Hapus dari tb_pdd
            mysqli_query($koneksi, "DELETE FROM tb_pdd WHERE id_pend='$id_pend'");
        }

        // 5. Akhirnya hapus dari tb_lahir
        $sql_hapus = "DELETE FROM tb_lahir WHERE id_lahir='" . $_GET['kode'] . "'";
        $query_hapus = mysqli_query($koneksi, $sql_hapus);

        if ($query_hapus) {
            echo "<script>
                Swal.fire({title: 'Hapus Data Berhasil',text: 'Data kelahiran dan data penduduk terkait berhasil dihapus',icon: 'success',confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.value) {
                        window.location = 'index.php?page=data-lahir';
                    }
                })</script>";
        } else {
            echo "<script>
                Swal.fire({title: 'Hapus Data Gagal',text: '',icon: 'error',confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.value) {
                        window.location = 'index.php?page=data-lahir';
                    }
                })</script>";
        }
    }
}

