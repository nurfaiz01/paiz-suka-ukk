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
    
    // Set timezone ke WIB
    date_default_timezone_set('Asia/Jakarta');
    
    // Cek status transaksi
    $query_cek = "SELECT status FROM transaksi 
                 WHERE kd_tr = ? AND status = 'pending'";
    $stmt_cek = mysqli_prepare($conn, $query_cek);
    mysqli_stmt_bind_param($stmt_cek, "s", $kd_tr);
    mysqli_stmt_execute($stmt_cek);
    $result_cek = mysqli_stmt_get_result($stmt_cek);
    
    if(mysqli_num_rows($result_cek) > 0) {
        // Mulai transaction untuk memastikan kedua query berhasil
        mysqli_begin_transaction($conn);
        
        try {
            // Update status dan tambah id_karyawan
            $query = "UPDATE transaksi 
                     SET status = 'proses', 
                         id_karyawan = ? 
                     WHERE kd_tr = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "is", $id_karyawan, $kd_tr);
            mysqli_stmt_execute($stmt);
            
            // Tambah laporan awal dengan timestamp lengkap
            $current_timestamp = date('Y-m-d H:i:s');
            $keterangan = "Tugas diambil oleh karyawan";
            
            $query_laporan = "INSERT INTO detail_laporan (kd_tr, tanggal, keterangan, id_karyawan, created_at) 
                            VALUES (?, ?, ?, ?, ?)";
            $stmt_laporan = mysqli_prepare($conn, $query_laporan);
            mysqli_stmt_bind_param($stmt_laporan, "sssis", $kd_tr, $current_timestamp, $keterangan, $id_karyawan, $current_timestamp);
            mysqli_stmt_execute($stmt_laporan);
            
            // Commit transaksi jika semua berhasil
            mysqli_commit($conn);
            $_SESSION['success'] = "Tugas berhasil diambil!";
            
        } catch (Exception $e) {
            // Rollback jika terjadi error
            mysqli_rollback($conn);
            $_SESSION['error'] = "Gagal mengambil tugas: " . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = "Transaksi tidak ditemukan atau sudah diambil karyawan lain!";
    }
} else {
    $_SESSION['error'] = "Kode transaksi tidak valid!";
}

header("Location: index.php");
exit;