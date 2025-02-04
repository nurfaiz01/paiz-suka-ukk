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
    $id_karyawan = clean($_POST['id_karyawan']);
    $nama_lengkap = clean($_POST['nama_lengkap']);
    $email = clean($_POST['email']);
    $no_telp = clean($_POST['no_telp']);
    $alamat = clean($_POST['alamat']);

    // Cek apakah email sudah digunakan karyawan lain
    $query_cek = "SELECT id_karyawan FROM karyawan WHERE email = ? AND id_karyawan != ?";
    $stmt = mysqli_prepare($conn, $query_cek);
    mysqli_stmt_bind_param($stmt, "si", $email, $id_karyawan);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['error'] = "Email sudah digunakan karyawan lain!";
        header("Location: edit_karyawan.php?id=" . $id_karyawan);
        exit;
    }

    // Update data karyawan
    $query = "UPDATE karyawan 
              SET nama_lengkap = ?, 
                  email = ?, 
                  no_telp = ?, 
                  alamat = ? 
              WHERE id_karyawan = ?";
              
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssssi", $nama_lengkap, $email, $no_telp, $alamat, $id_karyawan);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Data karyawan berhasil diperbarui!";
        header("Location: karyawan.php");
    } else {
        $_SESSION['error'] = "Gagal memperbarui data karyawan: " . mysqli_error($conn);
        header("Location: edit_karyawan.php?id=" . $id_karyawan);
    }
} else {
    $_SESSION['error'] = "Metode tidak diizinkan!";
    header("Location: karyawan.php");
}
exit;
?>