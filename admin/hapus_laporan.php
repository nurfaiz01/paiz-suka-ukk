<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['admin_id']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: dashboard.php");
    exit;
}

$kd_tr = clean($_POST['kd_tr']);
$tanggal = clean($_POST['tanggal']);
$keterangan = clean($_POST['keterangan']);

// Validasi tanggal
$tgl_sekarang = date('Y-m-d');
if($tanggal > $tgl_sekarang) {
    $_SESSION['error'] = "Tanggal laporan tidak boleh melebihi tanggal hari ini";
    header("Location: detail_laporan.php?kd=" . $kd_tr);
    exit;
}

// Insert laporan
$query = "INSERT INTO detail_laporan (kd_tr, tanggal, keterangan) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "sss", $kd_tr, $tanggal, $keterangan);

if(mysqli_stmt_execute($stmt)) {
    $_SESSION['success'] = "Laporan berhasil ditambahkan";
} else {
    $_SESSION['error'] = "Gagal menambahkan laporan";
}

header("Location: detail_laporan.php?kd=" . $kd_tr);
exit;
?>