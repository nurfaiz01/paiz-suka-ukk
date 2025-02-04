<?php
session_start();
require_once '../includes/config.php';

// Cek login admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validasi input
    if (!isset($_POST['id_karyawan']) || !isset($_POST['password']) || !isset($_POST['konfirmasi_password'])) {
        $_SESSION['error'] = "Data tidak lengkap!";
        header("Location: karyawan.php");
        exit;
    }

    $id_karyawan = clean($_POST['id_karyawan']);
    $password = $_POST['password'];
    $konfirmasi_password = $_POST['konfirmasi_password'];

    // Validasi password match
    if ($password !== $konfirmasi_password) {
        $_SESSION['error'] = "Password dan konfirmasi password tidak sama!";
        header("Location: karyawan.php");
        exit;
    }

    // Validasi password length
    if (strlen($password) < 6) {
        $_SESSION['error'] = "Password minimal 6 karakter!";
        header("Location: karyawan.php");
        exit;
    }

    try {
        // Validasi karyawan exists
        $query_cek = "SELECT id_karyawan FROM karyawan WHERE id_karyawan = ?";
        $stmt = mysqli_prepare($conn, $query_cek);
        mysqli_stmt_bind_param($stmt, "i", $id_karyawan);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 0) {
            $_SESSION['error'] = "Karyawan tidak ditemukan!";
            header("Location: karyawan.php");
            exit;
        }

        // Hash password baru
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Update password
        $query = "UPDATE karyawan SET password = ? WHERE id_karyawan = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "si", $password_hash, $id_karyawan);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "Password karyawan berhasil direset!";
        } else {
            throw new Exception("Gagal mengupdate password");
        }

    } catch (Exception $e) {
        $_SESSION['error'] = "Gagal mereset password: " . $e->getMessage();
    }

} else {
    $_SESSION['error'] = "Metode tidak diizinkan!";
}

header("Location: karyawan.php");
exit;
?>