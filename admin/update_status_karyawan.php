<?php
session_start();
require_once '../includes/config.php';

// Cek login admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Verifikasi request POST
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $_SESSION['error'] = "Metode tidak diizinkan!";
    header("Location: karyawan.php");
    exit;
}

// Validasi parameter yang diperlukan
if (!isset($_POST['id_karyawan']) || !isset($_POST['status'])) {
    $_SESSION['error'] = "Data yang diperlukan tidak lengkap!";
    header("Location: karyawan.php");
    exit;
}

$id_karyawan = clean($_POST['id_karyawan']);
$status = clean($_POST['status']);

// Validasi nilai status
$allowed_status = ['aktif', 'nonaktif'];
if (!in_array($status, $allowed_status)) {
    $_SESSION['error'] = "Status yang dipilih tidak valid!";
    header("Location: karyawan.php");
    exit;
}

// Cek apakah karyawan ada
$query_check = "SELECT id_karyawan, status FROM karyawan WHERE id_karyawan = ?";
$stmt = mysqli_prepare($conn, $query_check);
mysqli_stmt_bind_param($stmt, "i", $id_karyawan);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    $_SESSION['error'] = "Data karyawan tidak ditemukan!";
    header("Location: karyawan.php");
    exit;
}

$current_data = mysqli_fetch_assoc($result);

// Cek apakah karyawan memiliki transaksi aktif saat akan dinonaktifkan
if ($status == 'nonaktif') {
    $query_active = "SELECT COUNT(*) as active_count FROM transaksi 
                     WHERE id_karyawan = ? AND status = 'proses'";
    $stmt = mysqli_prepare($conn, $query_active);
    mysqli_stmt_bind_param($stmt, "i", $id_karyawan);
    mysqli_stmt_execute($stmt);
    $active_result = mysqli_stmt_get_result($stmt);
    $active_count = mysqli_fetch_assoc($active_result)['active_count'];

    if ($active_count > 0) {
        $_SESSION['error'] = "Tidak dapat menonaktifkan karyawan yang masih memiliki transaksi dalam proses!";
        header("Location: karyawan.php");
        exit;
    }
}

// Hanya update jika status berbeda
if ($current_data['status'] != $status) {
    // Update status karyawan
    $query = "UPDATE karyawan SET status = ? WHERE id_karyawan = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "si", $status, $id_karyawan);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Status karyawan berhasil diperbarui!";
    } else {
        $_SESSION['error'] = "Gagal memperbarui status karyawan: " . mysqli_error($conn);
    }
}

header("Location: karyawan.php");
exit;
?>