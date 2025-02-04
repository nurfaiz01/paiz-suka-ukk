<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: dashboard.php");
    exit;
}

$id_user = $_SESSION['user_id'];
$password_lama = $_POST['password_lama'];
$password_baru = $_POST['password_baru'];
$konfirmasi_password = $_POST['konfirmasi_password'];

// Validasi password sama
if($password_baru !== $konfirmasi_password) {
    $_SESSION['error'] = "Konfirmasi password tidak sesuai";
    header("Location: profil.php");
    exit;
}

// Cek password lama
$query = "SELECT password FROM users WHERE id_user = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if(!password_verify($password_lama, $user['password'])) {
    $_SESSION['error'] = "Password lama salah";
    header("Location: profil.php");
    exit;
}

// Update password
$password_hash = password_hash($password_baru, PASSWORD_DEFAULT);
$query = "UPDATE users SET password = ? WHERE id_user = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "si", $password_hash, $id_user);

if(mysqli_stmt_execute($stmt)) {
    $_SESSION['success'] = "Password berhasil diubah";
} else {
    $_SESSION['error'] = "Gagal mengubah password";
}

header("Location: profil.php");
exit;
?>