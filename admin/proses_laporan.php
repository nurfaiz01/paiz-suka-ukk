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

$query = "INSERT INTO detail_laporan (kd_tr, tanggal, keterangan) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "sss", $kd_tr, $tanggal, $keterangan);

if(mysqli_stmt_execute($stmt)) {
    $_SESSION['success'] = "Laporan berhasil ditambahkan";
} else {
    $_SESSION['error'] = "Gagal menambahkan laporan";
}

header("Location: detail_transaksi.php?kd=" . $kd_tr);
exit;
?>