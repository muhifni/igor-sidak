<?php
// Nonaktifkan error reporting untuk mencegah output HTML
error_reporting(0);
ini_set('display_errors', 0);

include "../../inc/koneksi.php";

header('Content-Type: application/json');

// Cek koneksi database
if (mysqli_connect_errno()) {
    echo json_encode(['exists' => true, 'message' => 'Error koneksi database']);
    exit;
}

if (isset($_POST['no_kk'])) {
    $no_kk = $_POST['no_kk'];
    $id_kk = isset($_POST['id_kk']) ? $_POST['id_kk'] : '';

    // Query untuk memeriksa apakah No KK sudah ada
    $query = "SELECT id_kk FROM tb_kk WHERE no_kk = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    
    if (!$stmt) {
        echo json_encode(['exists' => true, 'message' => 'Error dalam query']);
        mysqli_close($koneksi);
        exit;
    }
    
    mysqli_stmt_bind_param($stmt, "s", $no_kk);
    
    if (!mysqli_stmt_execute($stmt)) {
        echo json_encode(['exists' => true, 'message' => 'Error dalam eksekusi query']);
        mysqli_stmt_close($stmt);
        mysqli_close($koneksi);
        exit;
    }
    
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
} else {
    echo json_encode(['exists' => true, 'message' => 'Parameter no_kk tidak ditemukan']);
}
?>