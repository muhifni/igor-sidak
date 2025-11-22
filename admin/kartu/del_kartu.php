<?php
if (isset($_GET['kode'])) {
    $id_kk = (int) $_GET['kode'];

    // Hapus dulu semua anggota yang terkait KK ini
    $sql_hapus_anggota = "DELETE FROM tb_anggota WHERE id_kk = '$id_kk'";
    mysqli_query($koneksi, $sql_hapus_anggota);

    // Baru hapus data KK-nya
    $sql_hapus_kk = "DELETE FROM tb_kk WHERE id_kk = '$id_kk'";
    $query_hapus_kk = mysqli_query($koneksi, $sql_hapus_kk);

    if ($query_hapus_kk) {
        echo "<script>
        Swal.fire({
            title: 'Hapus Data Berhasil',
            text: 'Data KK dan seluruh anggotanya telah dihapus',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=data-kartu';
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
                window.location = 'index.php?page=data-kartu';
            }
        })
        </script>";
    }
}
?>