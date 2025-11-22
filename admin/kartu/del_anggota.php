<?php
if (isset($_GET['kode'])) {
    $id_anggota = $_GET['kode'];

    // Cek dulu apakah anggota ini adalah kepala keluarga
    $sql_cek = "SELECT a.id_kk, a.id_pend, k.id_kepala 
                FROM tb_anggota a 
                INNER JOIN tb_kk k ON a.id_kk = k.id_kk
                WHERE a.id_anggota = '$id_anggota'";
    $query_cek = mysqli_query($koneksi, $sql_cek);
    $data_cek = mysqli_fetch_assoc($query_cek);

    if ($data_cek && $data_cek['id_pend'] == $data_cek['id_kepala']) {
        // ❌ Tidak boleh hapus kepala keluarga dari daftar anggota
        echo "<script>
        Swal.fire({
            title: 'Tidak Bisa Dihapus',
            text: 'Anggota ini adalah Kepala Keluarga. Ubah Kepala Keluarga lewat menu ubah KK jika diperlukan.',
            icon: 'error',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                // balik ke halaman anggota KK yang sama
                window.location = 'index.php?page=anggota&kode=" . $data_cek['id_kk'] . "';
            }
        })
        </script>";
    } else {
        // ✅ Boleh dihapus (bukan kepala keluarga)
        $sql_hapus = "DELETE FROM tb_anggota WHERE id_anggota='$id_anggota'";
        $query_hapus = mysqli_query($koneksi, $sql_hapus);

        if ($query_hapus) {
            echo "<script>
            Swal.fire({
                title: 'Hapus Data Berhasil',
                text: '',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.value) {
                    window.location = 'index.php?page=anggota&kode=" . $data_cek['id_kk'] . "';
                }
            })
            </script>";
        } else {
            echo "<script>
            Swal.fire({
                title: 'Hapus Data Gagal',
                text: '',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.value) {
                    window.location = 'index.php?page=anggota&kode=" . $data_cek['id_kk'] . "';
                }
            })
            </script>";
        }
    }
}
?>