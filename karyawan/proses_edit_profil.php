<?php
session_start();
require_once '../includes/config.php';

if(!isset($_SESSION['karyawan_id'])) {
    header("Location: login.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_lengkap = clean($_POST['nama_lengkap']);
    $email = clean($_POST['email']);
    $no_telp = clean($_POST['no_telp']);
    $alamat = clean($_POST['alamat']);
    $id_karyawan = $_SESSION['karyawan_id'];
    
    // Cek email unik
    $query_cek = "SELECT id_karyawan FROM karyawan WHERE email = ? AND id_karyawan != ?";
    $stmt_cek = mysqli_prepare($conn, $query_cek);
    mysqli_stmt_bind_param($stmt_cek, "si", $email, $id_karyawan);
    mysqli_stmt_execute($stmt_cek);
    $result_cek = mysqli_stmt_get_result($stmt_cek);
    
    if(mysqli_num_rows($result_cek) > 0) {
        $_SESSION['error'] = "Email sudah digunakan!";
    } else {
        // Update profil
        $query = "UPDATE karyawan SET nama_lengkap = ?, email = ?, no_telp = ?, alamat = ? WHERE id_karyawan = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssssi", $nama_lengkap, $email, $no_telp, $alamat, $id_karyawan);
        
        if(mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "Profil berhasil diperbarui!";
            $_SESSION['karyawan_nama'] = $nama_lengkap;
        } else {
            $_SESSION['error'] = "Gagal memperbarui profil!";
        }
    }
    
    header("Location: profil.php");
    exit;
} else {
    header("Location: profil.php");
    exit;
}