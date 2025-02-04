<?php
session_start();
require_once '../includes/config.php';

// Cek login
if(!isset($_SESSION['karyawan_id'])) {
    header("Location: login.php");
    exit;
}

if(isset($_GET['kd'])) {
    $kd_tr = clean($_GET['kd']);
    $id_karyawan = $_SESSION['karyawan_id'];
    
    // Cek status dan kepemilikan transaksi
    $query_cek = "SELECT status FROM transaksi 
                 WHERE kd_tr = ? 
                 AND id_karyawan = ? 
                 AND status = 'proses'";
    $stmt_cek = mysqli_prepare($conn, $query_cek);
    mysqli_stmt_bind_param($stmt_cek, "si", $kd_tr, $id_karyawan);
    mysqli_stmt_execute($stmt_cek);
    $result_cek = mysqli_stmt_get_result($stmt_cek);
    
    if(mysqli_num_rows($result_cek) > 0) {
        // Update status
        $query = "UPDATE transaksi 
                 SET status = 'selesai'
                 WHERE kd_tr = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $kd_tr);
        
        if(mysqli_stmt_execute($stmt)) {
            // Tambah laporan selesai
            $tanggal = date('Y-m-d');
            $keterangan = "Tugas telah selesai";
            
            $query_laporan = "INSERT INTO detail_laporan (kd_tr, tanggal, keterangan, id_karyawan) 
                            VALUES (?, ?, ?, ?)";
            $stmt_laporan = mysqli_prepare($conn, $query_laporan);
            mysqli_stmt_bind_param($stmt_laporan, "sssi", $kd_tr, $tanggal, $keterangan, $id_karyawan);
            mysqli_stmt_execute($stmt_laporan);
            
            $_SESSION['success'] = "Tugas berhasil diselesaikan!";
        } else {
            $_SESSION['error'] = "Gagal menyelesaikan tugas!";
        }
    } else {
        $_SESSION['error'] = "Transaksi tidak valid atau bukan tanggung jawab Anda!";
    }
} else {
    $_SESSION['error'] = "Kode transaksi tidak valid!";
}

header("Location: index.php");
exit;