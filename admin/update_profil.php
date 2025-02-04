<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['admin_id']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: dashboard.php");
    exit;
}

$id_admin = $_SESSION['admin_id'];
$nama_lengkap = clean($_POST['nama_lengkap']);
$email = clean($_POST['email']);

// Cek apakah email sudah digunakan admin lain
$query = "SELECT id_admin FROM admin WHERE email = ? AND id_admin != ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "si", $email, $id_admin);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) > 0) {
    $_SESSION['error'] = "Email sudah digunakan";
    header("Location: profil.php");
    exit;
}

// Update profil
$query = "UPDATE admin SET nama_lengkap = ?, email = ? WHERE id_admin = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ssi", $nama_lengkap, $email, $id_admin);

if(mysqli_stmt_execute($stmt)) {
    $_SESSION['success'] = "Profil berhasil diupdate";
    $_SESSION['admin_nama'] = $nama_lengkap; // Update nama di session
} else {
    $_SESSION['error'] = "Gagal mengupdate profil";
}

header("Location: profil.php");
exit;
?>