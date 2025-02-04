<?php
// File: user/update_profil.php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: dashboard.php");
    exit;
}

$id_user = $_SESSION['user_id'];
$nama_lengkap = clean($_POST['nama_lengkap']);
$email = clean($_POST['email']);
$no_telp = clean($_POST['no_telp']);
$alamat = clean($_POST['alamat']);

// Cek apakah email sudah digunakan user lain
$query = "SELECT id_user FROM users WHERE email = ? AND id_user != ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "si", $email, $id_user);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) > 0) {
    $_SESSION['error'] = "Email sudah digunakan";
    header("Location: profil.php");
    exit;
}

// Update profil
$query = "UPDATE users SET nama_lengkap = ?, email = ?, no_telp = ?, alamat = ? WHERE id_user = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ssssi", $nama_lengkap, $email, $no_telp, $alamat, $id_user);

if(mysqli_stmt_execute($stmt)) {
    $_SESSION['success'] = "Profil berhasil diupdate";
    $_SESSION['user_nama'] = $nama_lengkap; // Update nama di session
} else {
    $_SESSION['error'] = "Gagal mengupdate profil";
}

header("Location: profil.php");
exit;
?>