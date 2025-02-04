<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['admin_id']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: dashboard.php");
    exit;
}

$kd_tr = clean($_POST['kd_tr']);
$id_admin = $_SESSION['admin_id'];
$id_paket = clean($_POST['id_paket']);
$tgl_awal = clean($_POST['tgl_awal']);
$tgl_akhir = clean($_POST['tgl_akhir']);

// Ambil harga paket
$query_paket = "SELECT harga FROM paket WHERE id_paket = ?";
$stmt = mysqli_prepare($conn, $query_paket);
mysqli_stmt_bind_param($stmt, "s", $id_paket);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$paket = mysqli_fetch_assoc($result);

// Hitung jumlah hari
$datetime1 = new DateTime($tgl_awal);
$datetime2 = new DateTime($tgl_akhir);
$interval = $datetime1->diff($datetime2);
$jumlah_hari = $interval->days + 1;

// Hitung total harga
$total_harga = $paket['harga'] * $jumlah_hari;

// Insert transaksi
$query = "INSERT INTO transaksi (kd_tr, id_admin, id_paket, tgl_transaksi, tgl_awal, tgl_akhir, total_harga, status) 
          VALUES (?, ?, ?, CURDATE(), ?, ?, ?, 'pending')";
          
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "sssssd", $kd_tr, $id_admin, $id_paket, $tgl_awal, $tgl_akhir, $total_harga);

if(mysqli_stmt_execute($stmt)) {
    $_SESSION['success'] = "Transaksi berhasil ditambahkan";
} else {
    $_SESSION['error'] = "Gagal menambahkan transaksi: " . mysqli_error($conn);
}

header("Location: dashboard.php");
exit;
?>