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

    $user_id = $_SESSION['ses_id'] ?? 0;
    $username = $_SESSION['ses_nama'] ?? 'Guest';
    $page = $_GET['page'] ?? 'dashboard';
    // Mendapatkan IP address user yang sebenarnya (untuk deployment dengan proxy/CDN)
    // Berdasarkan dokumentasi Cloudflare 2024
    $ip = null;
    
    // 1. Prioritas utama: CF-Connecting-IP (Cloudflare)
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP']) && filter_var($_SERVER['HTTP_CF_CONNECTING_IP'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
    }
    // 2. X-Forwarded-For (Load balancer/proxy)
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $forwarded_ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        foreach ($forwarded_ips as $forwarded_ip) {
            $forwarded_ip = trim($forwarded_ip);
            if (filter_var($forwarded_ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                $ip = $forwarded_ip;
                break;
            }
        }
    }
    // 3. X-Real-IP (Nginx proxy)
    elseif (!empty($_SERVER['HTTP_X_REAL_IP']) && filter_var($_SERVER['HTTP_X_REAL_IP'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_X_REAL_IP'];
    }
    // 4. Fallback ke REMOTE_ADDR
    if (empty($ip)) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }
    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    $sql = "INSERT INTO tb_access_log (user_id, username, page_accessed, ip_address, user_agent) 
            VALUES ('$user_id', '$username', '$page', '$ip', '$user_agent')";
    mysqli_query($koneksi, $sql);
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
