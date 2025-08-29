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
    $ip = $_SERVER['HTTP_CF_CONNECTING_IP'] ?? // Cloudflare
          $_SERVER['HTTP_X_FORWARDED_FOR'] ?? // Load balancer/proxy
          $_SERVER['HTTP_X_REAL_IP'] ?? // Nginx proxy
          $_SERVER['REMOTE_ADDR']; // Fallback
    
    // Jika ada multiple IP (comma separated), ambil yang pertama
    if (strpos($ip, ',') !== false) {
        $ip = trim(explode(',', $ip)[0]);
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
