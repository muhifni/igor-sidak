<?php
include "../../inc/koneksi.php";

header('Content-Type: application/json');

if (isset($_POST['nik'])) {
    $nik = $_POST['nik'];
    $id_pend = isset($_POST['id_pend']) ? $_POST['id_pend'] : '';
    $query = "SELECT id_pend FROM tb_pdd WHERE nik = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "s", $nik);
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
        // Pastikan perbandingan id_pend menggunakan tipe string
        if ($id_pend !== '' && strval($found_id_val) === strval($id_pend)) {
            echo json_encode(['exists' => false, 'id_pend' => $found_id_val, 'message' => 'NIK milik user ini']);
        } else {
            echo json_encode(['exists' => true, 'id_pend' => $found_id_val, 'message' => 'NIK sudah terdaftar']);
        }
    } else {
        echo json_encode(['exists' => false, 'id_pend' => '', 'message' => 'NIK tersedia']);
    }
    mysqli_close($koneksi);
}
?>