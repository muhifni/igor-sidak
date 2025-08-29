<?php
// Fungsi untuk membaca file .env
function env($key, $default = null)
{
    $value = getenv($key);
    if ($value !== false) {
        return $value;
    }
    static $env = null;
    if ($env === null) {
        $env = [];
        if (file_exists(__DIR__ . '/../.env')) {
            $lines = file(__DIR__ . '/../.env');
            foreach ($lines as $line) {
                $line = trim($line);
                if ($line && strpos($line, '=') !== false && $line[0] !== '#') {
                    list($k, $v) = explode('=', $line, 2);
                    $env[trim($k)] = trim($v);
                }
            }
        }
    }
    return isset($env[$key]) ? $env[$key] : $default;
}

// Fungsi log akses sederhana
function log_access()
{
    global $koneksi;

    // Hapus kode debug yang ditambahkan sebelumnya
    // print_r($_SERVER);
    // die('DEBUGGING: End of Server Variables.');

    $ip_address = 'UNKNOWN';
    $ip_headers = [
        'HTTP_CF_CONNECTING_IP', // Prioritas utama untuk Cloudflare
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_REAL_IP',
        'REMOTE_ADDR'
    ];

    foreach ($ip_headers as $header) {
        if (!empty($_SERVER[$header])) {
            $ip_list = explode(',', $_SERVER[$header]);
            $first_ip = trim(reset($ip_list));

            // Validasi IP dan pastikan bukan IP privat (kecuali untuk pengembangan lokal)
            if (filter_var($first_ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                $ip_address = $first_ip;
                break; 
            }
            
            // Fallback jika semua IP adalah privat (misalnya dalam jaringan internal)
            if ($ip_address === 'UNKNOWN') {
                 $ip_address = $first_ip;
            }
        }
    }

    // Fallback terakhir jika semua header kosong
    if ($ip_address === 'UNKNOWN' && !empty($_SERVER['REMOTE_ADDR'])) {
        $ip_address = $_SERVER['REMOTE_ADDR'];
    }

    $page_accessed = $_SERVER['REQUEST_URI'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN';

    $stmt = $koneksi->prepare("INSERT INTO tb_access_log (ip_address, page_accessed, user_agent) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $ip_address, $page_accessed, $user_agent);
    $stmt->execute();
    $stmt->close();
}

$host = env('DB_HOST');
$dbname = env('DB_DATABASE');
$port = env('DB_PORT');
$user = env('DB_USERNAME');
$pass = env('DB_PASSWORD');

$koneksi = new mysqli(
    $host,
    $user,
    $pass,
    $dbname,
    $port
);

// Set timezone untuk Asia/Jakarta
date_default_timezone_set('Asia/Jakarta');
mysqli_query($koneksi, "SET time_zone = '+07:00'");
