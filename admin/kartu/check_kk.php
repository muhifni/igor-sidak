<?php
include "../../inc/koneksi.php";

header('Content-Type: application/json');

if (isset($_POST['no_kk'])) {
    $no_kk = $_POST['no_kk'];
    $id_kk = isset($_POST['id_kk']) ? $_POST['id_kk'] : '';

    // Query untuk memeriksa apakah No KK sudah ada
    $query = "SELECT id_kk FROM tb_kk WHERE no_kk = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "s", $no_kk);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $found_id);
    
    $exists = false;
    $found_id_val = '';
    if (mysqli_stmt_fetch($stmt)) {
        $exists = true;
        $found_id_val = $found_id;
    }
    mysqli_stmt_close($stmt);

    if ($exists) {
        // Jika sedang dalam mode edit, periksa apakah No KK yang ditemukan milik data yang sama
        if ($id_kk !== '' && strval($found_id_val) === strval($id_kk)) {
            echo json_encode(['exists' => false, 'message' => 'No KK milik kartu keluarga ini']);
        } else {
            echo json_encode(['exists' => true, 'message' => 'No KK sudah terdaftar']);
        }
    } else {
        echo json_encode(['exists' => false, 'message' => 'No KK tersedia']);
    }
    mysqli_close($koneksi);
}
?>