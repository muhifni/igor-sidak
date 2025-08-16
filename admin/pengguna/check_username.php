<?php
include '../../inc/koneksi.php';

if (isset($_POST['username'])) {
    $username = strtolower(trim($_POST['username'])); // Konversi ke lowercase dan hapus spasi
    
    // Query untuk mengecek apakah username sudah ada
    $query = "SELECT COUNT(*) as count FROM tb_pengguna WHERE username = '$username'";
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);
    
    if ($row['count'] > 0) {
        echo 'exists';
    } else {
        echo 'available';
    }
}
?>