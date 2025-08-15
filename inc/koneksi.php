<?php
// Fungsi untuk membaca file .env
function env($key, $default = null) {
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

$koneksi = new mysqli (
    env('DB_HOST', 'localhost'),
    env('DB_USERNAME', 'root'),
    env('DB_PASSWORD', ''),
    env('DB_DATABASE', ''),
    (int)env('DB_PORT', 3306)
);
