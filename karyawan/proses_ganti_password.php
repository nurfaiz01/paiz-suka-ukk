<?php
session_start();
require_once '../includes/config.php';

if(!isset($_SESSION['karyawan_id'])) {
    header("Location: login.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password_lama = $_POST['password_lama'];
    $password_baru = $_POST['password_baru'];
    $konfirmasi_password = $_POST['konfirmasi_password'];
    $id_karyawan = $_SESSION['karyawan_id'];
    
    // Cek password lama
    $query = "SELECT password FROM karyawan WHERE id_karyawan = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_karyawan);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $karyawan = mysqli_fetch_assoc($result);
    
    if(password_verify($password_lama, $karyawan['password'])) {
        if($password_baru == $konfirmasi_password) {
            // Update password
            $password_hash = password_hash($password_baru, PASSWORD_DEFAULT);
            $query = "UPDATE karyawan SET password = ? WHERE id_karyawan = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "si", $password_hash, $id_karyawan);
            
            if(mysqli_stmt_execute($stmt)) {
                $_SESSION['success'] = "Password berhasil diperbarui!";
            } else {
                $_SESSION['error'] = "Gagal memperbarui password!";
            }
        } else {
            $_SESSION['error'] = "Konfirmasi password tidak sesuai!";
        }
    } else {
        $_SESSION['error'] = "Password lama salah!";
    }
    
    header("Location: profil.php");
    exit;
} else {
    header("Location: profil.php");
    exit;
}