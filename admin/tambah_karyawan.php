<?php
session_start();
require_once '../includes/config.php';

// Cek login admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Cek method POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = clean($_POST['username']);
    $password = $_POST['password'];
    $konfirmasi_password = $_POST['konfirmasi_password'];
    $nama_lengkap = clean($_POST['nama_lengkap']);
    $email = clean($_POST['email']);
    $no_telp = clean($_POST['no_telp']);
    $alamat = clean($_POST['alamat']);

    // Validasi password match
    if ($password !== $konfirmasi_password) {
        $_SESSION['error'] = "Password dan konfirmasi password tidak sama!";
        header("Location: karyawan.php");
        exit;
    }

    // Cek username unik
    $query_cek = "SELECT id_karyawan FROM karyawan WHERE username = ?";
    $stmt = mysqli_prepare($conn, $query_cek);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['error'] = "Username sudah digunakan!";
        header("Location: karyawan.php");
        exit;
    }

    // Cek email unik
    $query_cek = "SELECT id_karyawan FROM karyawan WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query_cek);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['error'] = "Email sudah digunakan!";
        header("Location: karyawan.php");
        exit;
    }

    // Hash password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insert data karyawan
    $query = "INSERT INTO karyawan (username, password, nama_lengkap, email, no_telp, alamat) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssssss", $username, $password_hash, $nama_lengkap, $email, $no_telp, $alamat);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Karyawan berhasil ditambahkan!";
    } else {
        $_SESSION['error'] = "Gagal menambahkan karyawan: " . mysqli_error($conn);
    }
} else {
    $_SESSION['error'] = "Metode tidak diizinkan!";
}

header("Location: karyawan.php");
exit;
?>