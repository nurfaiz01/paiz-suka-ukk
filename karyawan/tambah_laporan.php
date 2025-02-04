<?php
session_start();
require_once '../includes/config.php';

// Cek login
if(!isset($_SESSION['karyawan_id'])) {
    header("Location: login.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kd_tr = clean($_POST['kd_tr']);
    $tanggal = clean($_POST['tanggal']);
    $keterangan = clean($_POST['keterangan']);
    $id_karyawan = $_SESSION['karyawan_id'];
    
    // Cek apakah transaksi valid dan milik karyawan ini
    $query_cek = "SELECT status FROM transaksi 
                 WHERE kd_tr = ? AND id_karyawan = ? 
                 AND status = 'proses'";
    $stmt_cek = mysqli_prepare($conn, $query_cek);
    mysqli_stmt_bind_param($stmt_cek, "si", $kd_tr, $id_karyawan);
    mysqli_stmt_execute($stmt_cek);
    $result_cek = mysqli_stmt_get_result($stmt_cek);
    
    if(mysqli_num_rows($result_cek) > 0) {
        // Simpan laporan
        $query = "INSERT INTO detail_laporan (kd_tr, tanggal, keterangan, id_karyawan) 
                 VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sssi", $kd_tr, $tanggal, $keterangan, $id_karyawan);
        
        if(mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "Laporan berhasil ditambahkan!";
        } else {
            $_SESSION['error'] = "Gagal menambahkan laporan!";
        }
    } else {
        $_SESSION['error'] = "Transaksi tidak valid atau bukan tanggung jawab Anda!";
    }
    
    header("Location: detail_transaksi.php?kd=" . $kd_tr);
    exit;
}

// Jika bukan POST, kembali ke dashboard
header("Location: index.php");
exit;