<?php
if (isset($_GET['kode'])) {
    $id_pend = $_GET['kode'];

    // Cek apakah penduduk ini adalah kepala keluarga di tb_kk
    $sql_cek_kepala = "SELECT id_kk FROM tb_kk WHERE id_kepala = '$id_pend'";
    $q1 = mysqli_query($koneksi, $sql_cek_kepala);

    if (mysqli_num_rows($q1) > 0) {
        echo "<script>
        Swal.fire({
            title: 'Tidak Bisa Dihapus',
            text: 'Penduduk ini adalah Kepala Keluarga. Pindahkan atau ubah KK terlebih dahulu.',
            icon: 'error',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value){
                window.location = 'index.php?page=data-pend';
            }
        })
        </script>";
        exit;
    }

    // Cek apakah penduduk adalah anggota KK
    $sql_cek_anggota = "SELECT id_anggota FROM tb_anggota WHERE id_pend = '$id_pend'";
    $q2 = mysqli_query($koneksi, $sql_cek_anggota);

    if (mysqli_num_rows($q2) > 0) {
        echo "<script>
        Swal.fire({
            title: 'Tidak Bisa Dihapus',
            text: 'Penduduk ini masih terdaftar sebagai anggota Kartu Keluarga.',
            icon: 'error',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value){
                window.location = 'index.php?page=data-pend';
            }
        })
        </script>";
        exit;
    }

    // Jika aman → baru hapus penduduk
    $sql_hapus = "DELETE FROM tb_pdd WHERE id_pend='$id_pend'";
    $query_hapus = mysqli_query($koneksi, $sql_hapus);

    if ($query_hapus) {
        echo "<script>
        Swal.fire({
            title: 'Hapus Data Berhasil',
            text: '',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value){
                window.location = 'index.php?page=data-pend';
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
            if (result.value){
                window.location = 'index.php?page=data-pend';
            }
        })
        </script>";
    }
}
?>