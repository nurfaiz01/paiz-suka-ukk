<?php
session_start();
require_once '../includes/config.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Validasi input
if (empty($_POST['id_paket']) || empty($_POST['id_kategori']) || 
    empty($_POST['tgl_awal']) || empty($_POST['tgl_akhir']) || 
    empty($_POST['total_harga']) || empty($_POST['nama_hewan'])) {
    $_SESSION['error'] = "Semua field harus diisi!";
    header("Location: daftar_paket.php");
    exit;
}

try {
    // Generate kode transaksi
    $kd_tr = 'TR' . date('Ymd') . rand(1000, 9999);
    
    // Ambil dan bersihkan data
    $id_user = $_SESSION['user_id'];
    $id_paket = clean($_POST['id_paket']);
    $id_kategori = clean($_POST['id_kategori']);
    $tgl_awal = clean($_POST['tgl_awal']);
    $tgl_akhir = clean($_POST['tgl_akhir']);
    $total_harga = clean($_POST['total_harga']);
    $nama_hewan = clean($_POST['nama_hewan']);
    $catatan = isset($_POST['catatan']) ? clean($_POST['catatan']) : '';
    
    // Mulai transaction
    mysqli_begin_transaction($conn);

    // Insert ke tabel transaksi
    $query = "INSERT INTO transaksi (kd_tr, id_user, id_paket, id_kategori, tgl_transaksi, tgl_awal, tgl_akhir, total_harga, status) 
              VALUES (?, ?, ?, ?, CURDATE(), ?, ?, ?, 'pending')";
    
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        throw new Exception("Error preparing statement: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "sssissd", 
        $kd_tr, 
        $id_user, 
        $id_paket,
        $id_kategori, 
        $tgl_awal, 
        $tgl_akhir, 
        $total_harga
    );

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Error executing statement: " . mysqli_stmt_error($stmt));
    }

    // Insert ke tabel detail_laporan
    $query_detail = "INSERT INTO detail_laporan (kd_tr, tanggal, keterangan) VALUES (?, CURDATE(), ?)";
    $stmt_detail = mysqli_prepare($conn, $query_detail);
    if (!$stmt_detail) {
        throw new Exception("Error preparing detail statement: " . mysqli_error($conn));
    }

    $keterangan = "Pendaftaran:\nNama Hewan: " . $nama_hewan . "\nCatatan: " . $catatan;
    mysqli_stmt_bind_param($stmt_detail, "ss", $kd_tr, $keterangan);

    if (!mysqli_stmt_execute($stmt_detail)) {
        throw new Exception("Error executing detail statement: " . mysqli_stmt_error($stmt_detail));
    }

    // Commit transaction
    mysqli_commit($conn);

    // Redirect ke halaman detail transaksi
    $_SESSION['success'] = "Transaksi berhasil dibuat!";
    header("Location: detail_transaksi.php?kd=" . $kd_tr);
    exit;

} catch (Exception $e) {
    // Rollback jika terjadi error
    mysqli_rollback($conn);
    
    $_SESSION['error'] = "Error: " . $e->getMessage();
    header("Location: daftar_paket.php");
    exit;
}
?>