<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['admin_id']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: dashboard.php");
    exit;
}

$kd_tr = clean($_POST['kd_tr']);
$status = clean($_POST['status']);

$query = "UPDATE transaksi SET status = ? WHERE kd_tr = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ss", $status, $kd_tr);

if(mysqli_stmt_execute($stmt)) {
    $_SESSION['success'] = "Status berhasil diupdate";
} else {
    $_SESSION['error'] = "Gagal mengupdate status";
}

// Redirect kembali ke halaman sebelumnya
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
?>