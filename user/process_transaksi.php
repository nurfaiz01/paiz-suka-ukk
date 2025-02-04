<?php
session_start();
require_once 'config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id_user'])) {
    echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu']);
    exit;
}

try {
    // Generate kode transaksi
    $kd_tr = 'TR' . date('Ymd') . rand(100, 999);
    
    // Ambil data dari form
    $id_user = $_SESSION['id_user'];
    $id_paket = $_POST['id_paket'];
    $tgl_awal = $_POST['tgl_awal'];
    $tgl_akhir = $_POST['tgl_akhir'];
    $total_harga = $_POST['total_harga'];
    $nama_hewan = $_POST['nama_hewan'];
    $jenis_hewan = $_POST['jenis_hewan'];
    $catatan = $_POST['catatan'];
    
    // Mulai transaction
    $conn->begin_transaction();

    // Insert ke tabel transaksi
    $query = "INSERT INTO transaksi (kd_tr, id_user, id_paket, tgl_transaksi, tgl_awal, tgl_akhir, total_harga, status) 
              VALUES (?, ?, ?, CURDATE(), ?, ?, ?, 'pending')";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssd", $kd_tr, $id_user, $id_paket, $tgl_awal, $tgl_akhir, $total_harga);
    $stmt->execute();

    // Insert ke tabel detail_laporan
    $query = "INSERT INTO detail_laporan (kd_tr, tanggal, keterangan) VALUES (?, CURDATE(), ?)";
    $stmt = $conn->prepare($query);
    $keterangan = "Nama Hewan: $nama_hewan\nJenis: $jenis_hewan\nCatatan: $catatan";
    $stmt->bind_param("ss", $kd_tr, $keterangan);
    $stmt->execute();

    // Commit transaction
    $conn->commit();

    echo json_encode([
        'success' => true, 
        'message' => 'Transaksi berhasil dibuat',
        'kd_tr' => $kd_tr
    ]);
    
} catch (Exception $e) {
    // Rollback jika terjadi error
    $conn->rollback();
    
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$conn->close();
?>