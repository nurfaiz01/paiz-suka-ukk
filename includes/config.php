<?php

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'ukk');
define('BASE_URL', 'http://paizukk.test/');

$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Fungsi untuk mencegah SQL Injection
function clean($string) {
    global $conn;
    return mysqli_real_escape_string($conn, $string);
}

// Fungsi untuk generate kode transaksi
function generateKdTr() {
    return 'TR' . date('Ymd') . rand(1000, 9999);
}

// Fungsi untuk format tanggal Indonesia
function formatTanggal($date) {
    $bulan = array (
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
    $split = explode('-', $date);
    return $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
}
?>