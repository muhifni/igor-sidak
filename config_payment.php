<?php
// Konfigurasi Pembatasan Akses Aplikasi
// File ini mengatur status pembayaran dan tanggal expired

// Status pembayaran: 'active' = aktif, 'blocked' = diblokir
$payment_status = 'active';

// Tanggal expired aplikasi (format: YYYY-MM-DD)
$expired_date = '2025-10-01';

// Pesan untuk notifikasi
$payment_message = 'Ini adalah trial dan aplikasi akan expired pada tanggal ' . date('d F Y', strtotime($expired_date));

// Pesan jika aplikasi diblokir
$blocked_message = 'Akses aplikasi diblokir. Silakan hubungi administrator untuk mengaktifkan kembali.';

// Pesan jika aplikasi expired
$expired_message = 'Aplikasi telah expired. Silakan lunasi pembayaran untuk melanjutkan penggunaan.';

// Fungsi untuk mengecek status akses
function checkAccessStatus() {
    global $payment_status, $expired_date;
    
    // Cek jika status diblokir
    if ($payment_status != 'active') {
        return 'blocked';
    }
    
    // Cek jika sudah expired
    if (date('Y-m-d') > $expired_date) {
        return 'expired';
    }
    
    return 'active';
}

// Fungsi untuk menghitung sisa hari
function getDaysRemaining() {
    global $expired_date;
    $today = new DateTime();
    $expiry = new DateTime($expired_date);
    $diff = $today->diff($expiry);
    
    if ($today > $expiry) {
        return 0;
    }
    
    return $diff->days;
}

// Fungsi untuk mendapatkan pesan notifikasi
function getNotificationMessage() {
    global $payment_message, $blocked_message, $expired_message;
    
    $status = checkAccessStatus();
    $days_remaining = getDaysRemaining();
    
    switch ($status) {
        case 'blocked':
            return $blocked_message;
        case 'expired':
            return $expired_message;
        case 'active':
            if ($days_remaining <= 7) {
                return 'Perhatian! Aplikasi akan expired dalam ' . $days_remaining . ' hari lagi (' . date('d F Y', strtotime($GLOBALS['expired_date'])) . ')';
            } else {
                return $payment_message;
            }
        default:
            return $payment_message;
    }
}
?>