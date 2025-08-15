<?php
// Fungsi untuk membaca file .env
function env($key, $default = null) {
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

$host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_DATABASE'];
$port = $_ENV['DB_PORT'];
$user = $_ENV['DB_USERNAME'];
$pass = $_ENV['DB_PASSWORD'];

$koneksi = new mysqli (
    $host,
    $user,
    $pass,
    $dbname,
    $port
);
