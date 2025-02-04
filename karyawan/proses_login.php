<?php
session_start();
require_once '../includes/config.php';

// Cek method POST
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: login.php");
    exit;
}

// Ambil data login
$username = clean($_POST['username']);
$password = $_POST['password'];

// Validasi input
if (empty($username) || empty($password)) {
    $_SESSION['error'] = "Username dan password harus diisi!";
    header("Location: login.php");
    exit;
}

// Cek username
$query = "SELECT * FROM karyawan WHERE username = ? AND status = 'aktif'";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 1) {
    $karyawan = mysqli_fetch_assoc($result);
    
    // Verifikasi password
    if (password_verify($password, $karyawan['password'])) {
        // Set session
        $_SESSION['karyawan_id'] = $karyawan['id_karyawan'];
        $_SESSION['karyawan_username'] = $karyawan['username'];
        $_SESSION['karyawan_nama'] = $karyawan['nama_lengkap'];
        
        // Redirect ke dashboard
        header("Location: index.php");
        exit;
    }
}

// Jika login gagal
$_SESSION['error'] = "Username atau password salah!";
header("Location: login.php");
exit;
?>