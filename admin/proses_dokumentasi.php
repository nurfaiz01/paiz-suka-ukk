<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['admin_id']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: dashboard.php");
    exit;
}

$kd_tr = clean($_POST['kd_tr']);

// Cek apakah ada file yang diupload
if(isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $filename = $_FILES['foto']['name'];
    $filetype = $_FILES['foto']['type'];
    $filesize = $_FILES['foto']['size'];

    // Dapatkan ekstensi file
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    // Validasi ekstensi file
    if(!in_array($ext, $allowed)) {
        $_SESSION['error'] = "Format file tidak diizinkan. Harap upload file gambar (JPG, PNG, GIF)";
        header("Location: dokumentasi.php?kd=" . $kd_tr);
        exit;
    }

    // Validasi ukuran (max 2MB)
    if($filesize > 2097152) {
        $_SESSION['error'] = "Ukuran file terlalu besar. Maksimal 2MB";
        header("Location: dokumentasi.php?kd=" . $kd_tr);
        exit;
    }

    // Buat nama file unik
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
                  VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssss", $kd_tr, $filename, $filetype, $url_file);

        if(mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "Foto berhasil diupload";
        } else {
            unlink($upload_dir . $newname); // Hapus file jika gagal insert ke database
            $_SESSION['error'] = "Gagal menyimpan data foto";
        }
    } else {
        $_SESSION['error'] = "Gagal mengupload file";
    }
} else {
    $_SESSION['error'] = "Silakan pilih file foto";
}

header("Location: dokumentasi.php?kd=" . $kd_tr);
exit;
?>