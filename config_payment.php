<?php
// Konfigurasi Payment Lock System
// File ini mengatur akses login berdasarkan status pembayaran client

// Fungsi untuk membaca file .env
function loadEnv($file_path) {
    if (!file_exists($file_path)) {
        return [];
    }
    
    $lines = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $env = [];
    
    foreach ($lines as $line) {
        // Skip komentar
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        // Parse key=value
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            $env[$key] = $value;
        }
    }
    
    return $env;
}

// Load konfigurasi dari .env
$env_config = loadEnv(__DIR__ . '/.env');

// Ambil konfigurasi dari .env atau gunakan default
$payment_lock_enabled = isset($env_config['PAYMENT_LOCK_ENABLED']) ? 
    (strtolower($env_config['PAYMENT_LOCK_ENABLED']) === 'true') : false;

$lock_message = isset($env_config['LOCK_MESSAGE']) ? 
    $env_config['LOCK_MESSAGE'] : 'Akses aplikasi diblokir karena pembayaran belum lunas. Silakan hubungi administrator.';

// Pesan jika aplikasi diblokir
$blocked_message = $lock_message;

// Fungsi untuk mengecek apakah login diblokir
function isLoginBlocked() {
    global $payment_lock_enabled;
    return $payment_lock_enabled === true;
}

// Fungsi untuk mendapatkan pesan lock
function getLockMessage() {
    global $blocked_message;
    return $blocked_message;
}
?>