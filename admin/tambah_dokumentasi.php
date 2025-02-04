<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['admin_id']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: transaksi.php");
    exit;
}

$kd_tr = clean($_POST['kd_tr']);

// Cek apakah ada file yang diupload
if(isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $allowed = ['jpg', 'jpeg', 'png'];
    $filename = $_FILES['foto']['name'];
    $filetype = $_FILES['foto']['type'];
    $filesize = $_FILES['foto']['size'];
    
    // Validasi ekstensi file
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if(!in_array(strtolower($ext), $allowed)) {
        $_SESSION['error'] = "Format file tidak diizinkan";
        header("Location: detail_transaksi.php?kd=" . $kd_tr);
        exit;
    }
    
    // Validasi ukuran (max 5MB)
    if($filesize > 5242880) {
        $_SESSION['error'] = "Ukuran file terlalu besar (max 5MB)";
        header("Location: detail_transaksi.php?kd=" . $kd_tr);
        exit;
    }
    
    // Generate nama file unik
    $newname = uniqid() . '.' . $ext;
    $upload_dir = '../uploads/dokumentasi/';
    
    // Buat direktori jika belum ada
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    // Upload file
    if(move_uploaded_file($_FILES['foto']['tmp_name'], $upload_dir . $newname)) {
        // Simpan ke database
        $url_file = 'uploads/dokumentasi/' . $newname;
        $query = "INSERT INTO dokumentasi (kd_tr, nama_file, jenis_file, url_file) 
                  VALUES ('$kd_tr', '$filename', '$filetype', '$url_file')";
                  
        if(mysqli_query($conn, $query)) {
            $_SESSION['success'] = "Dokumentasi berhasil diupload";
        } else {
            $_SESSION['error'] = "Gagal menyimpan data dokumentasi";
            // Hapus file jika gagal menyimpan ke database
            unlink($upload_dir . $newname);
        }
    } else {
        $_SESSION['error'] = "Gagal mengupload file";
    }
} else {
    $_SESSION['error'] = "Silakan pilih file untuk diupload";
}

header("Location: detail_transaksi.php?kd=" . $kd_tr);
exit;
?>