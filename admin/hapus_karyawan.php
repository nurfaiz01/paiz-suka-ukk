<?php
session_start();
require_once '../includes/config.php';

// Cek login admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Cek parameter id
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "ID karyawan tidak valid!";
    header("Location: karyawan.php");
    exit;
}

$id_karyawan = clean($_GET['id']);

// Cek apakah karyawan sedang menangani transaksi
$query_cek = "SELECT kd_tr FROM transaksi WHERE id_karyawan = ? AND status = 'proses'";
$stmt = mysqli_prepare($conn, $query_cek);
mysqli_stmt_bind_param($stmt, "i", $id_karyawan);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    $_SESSION['error'] = "Tidak dapat menghapus karyawan yang sedang menangani transaksi!";
    header("Location: karyawan.php");
    exit;
}

// Hapus semua laporan dari karyawan ini
$query_laporan = "DELETE FROM detail_laporan WHERE id_karyawan = ?";
$stmt = mysqli_prepare($conn, $query_laporan);
mysqli_stmt_bind_param($stmt, "i", $id_karyawan);
mysqli_stmt_execute($stmt);

// Hapus semua dokumentasi dari karyawan ini
$query_dokumentasi = "DELETE FROM dokumentasi WHERE id_karyawan = ?";
$stmt = mysqli_prepare($conn, $query_dokumentasi);
mysqli_stmt_bind_param($stmt, "i", $id_karyawan);
mysqli_stmt_execute($stmt);

// Hapus data karyawan
$query = "DELETE FROM karyawan WHERE id_karyawan = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id_karyawan);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['success'] = "Karyawan berhasil dihapus!";
} else {
    $_SESSION['error'] = "Gagal menghapus karyawan: " . mysqli_error($conn);
}

header("Location: karyawan.php");
exit;
?>