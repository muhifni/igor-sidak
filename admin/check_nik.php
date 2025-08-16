<?php
// Matikan error reporting untuk mencegah output HTML
error_reporting(1);
ini_set('display_errors', 1);

include "../inc/koneksi.php";

header('Content-Type: application/json');

// Cek koneksi database
if ($koneksi->connect_error) {
    echo json_encode(['exists' => false, 'message' => 'Error koneksi database']);
    exit;
}

if (isset($_POST['nik'])) {
    $nik = $_POST['nik'];
    
    // Parameter untuk mode edit - bisa dari berbagai modul
    $id_pend = isset($_POST['id_pend']) ? $_POST['id_pend'] : '';
    $id_datang = isset($_POST['id_datang']) ? $_POST['id_datang'] : '';
    $id_lahir = isset($_POST['id_lahir']) ? $_POST['id_lahir'] : '';
    
    // Cek NIK di semua tabel yang menyimpan NIK
    $tables_to_check = [
        'tb_pdd' => 'id_pend',
        'tb_datang' => 'id_datang',
        'tb_lahir' => 'id_lahir'
    ];
    
    $nik_exists = false;
    $table_found = '';
    $id_found = '';
    
    foreach ($tables_to_check as $table => $id_column) {
        $query = "SELECT $id_column FROM $table WHERE nik = ?";
        $stmt = mysqli_prepare($koneksi, $query);
        
        if (!$stmt) {
            echo json_encode(['exists' => false, 'message' => 'Error preparing statement']);
            exit;
        }
        
        mysqli_stmt_bind_param($stmt, "s", $nik);
        
        if (!mysqli_stmt_execute($stmt)) {
            echo json_encode(['exists' => false, 'message' => 'Error executing query']);
            exit;
        }
        
        mysqli_stmt_bind_result($stmt, $found_id);
        
        if (mysqli_stmt_fetch($stmt)) {
            // Jika sedang edit dan NIK ditemukan di tabel yang sama dengan ID yang sama, maka boleh
            $skip_check = false;
            
            if ($table === 'tb_pdd' && $id_pend !== '' && strval($found_id) === strval($id_pend)) {
                $skip_check = true;
            } elseif ($table === 'tb_datang' && $id_datang !== '' && strval($found_id) === strval($id_datang)) {
                $skip_check = true;
            } elseif ($table === 'tb_lahir' && $id_lahir !== '' && strval($found_id) === strval($id_lahir)) {
                $skip_check = true;
            }
            
            if ($skip_check) {
                mysqli_stmt_close($stmt);
                continue;
            }
            
            $nik_exists = true;
            $table_found = $table;
            $id_found = $found_id;
            mysqli_stmt_close($stmt);
            break;
        }
        mysqli_stmt_close($stmt);
    }
    
    if ($nik_exists) {
        $table_names = [
            'tb_pdd' => 'Penduduk',
            'tb_datang' => 'Pendatang',
            'tb_lahir' => 'Kelahiran'
        ];
        $message = 'NIK sudah terdaftar di data ' . $table_names[$table_found];
        echo json_encode(['exists' => true, 'table' => $table_found, 'id' => $id_found, 'message' => $message]);
    } else {
        echo json_encode(['exists' => false, 'message' => 'NIK tersedia']);
    }
    
    mysqli_close($koneksi);
} else {
    echo json_encode(['exists' => false, 'message' => 'Parameter NIK tidak ditemukan']);
}
?>