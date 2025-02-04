<?php
// proses_upload_dokumentasi.php - Handler upload foto

session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['admin_id'])) {
    $_SESSION['error'] = "Anda harus login terlebih dahulu";
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: dashboard.php");
    exit;
}

if (!isset($_POST['kd_tr']) || empty($_POST['kd_tr'])) {
    $_SESSION['error'] = "Kode transaksi tidak valid";
    header("Location: dashboard.php");
    exit;
}

$kd_tr = clean($_POST['kd_tr']);

// Validasi file upload
if (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['error'] = "Silakan pilih file untuk diupload";
    header("Location: dokumentasi.php?kd=" . $kd_tr);
    exit;
}

// Validasi tipe dan ukuran file
$allowed = ['jpg', 'jpeg', 'png'];
$filename = $_FILES['foto']['name'];
$filetype = $_FILES['foto']['type'];
$filesize = $_FILES['foto']['size'];
$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

if (!in_array($ext, $allowed)) {
    $_SESSION['error'] = "Format file tidak diizinkan. Harap upload file JPG atau PNG";
    header("Location: dokumentasi.php?kd=" . $kd_tr);
    exit;
}

if ($filesize > 5242880) { // 5MB
    $_SESSION['error'] = "Ukuran file terlalu besar (max 5MB)";
    header("Location: dokumentasi.php?kd=" . $kd_tr);
    exit;
}

// Buat direktori upload
$upload_dir = '../uploads/dokumentasi/';
if (!file_exists($upload_dir)) {
    if (!mkdir($upload_dir, 0777, true)) {
        $_SESSION['error'] = "Gagal membuat direktori upload";
        header("Location: dokumentasi.php?kd=" . $kd_tr);
        exit;
    }
}

// Generate nama file unik
$newname = uniqid() . '_' . time() . '.' . $ext;
$upload_path = $upload_dir . $newname;

// Upload file
if (!move_uploaded_file($_FILES['foto']['tmp_name'], $upload_path)) {
    $_SESSION['error'] = "Gagal mengupload file";
    header("Location: dokumentasi.php?kd=" . $kd_tr);
    exit;
}

// Simpan ke database
$url_file = 'uploads/dokumentasi/' . $newname;
$query = "INSERT INTO dokumentasi (kd_tr, nama_file, jenis_file, url_file) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $query);

if (!$stmt) {
    unlink($upload_path); // Hapus file jika query error
    $_SESSION['error'] = "Database error: " . mysqli_error($conn);
    header("Location: dokumentasi.php?kd=" . $kd_tr);
    exit;
}

mysqli_stmt_bind_param($stmt, "ssss", $kd_tr, $filename, $filetype, $url_file);

if (!mysqli_stmt_execute($stmt)) {
    unlink($upload_path); // Hapus file jika execute error
    $_SESSION['error'] = "Gagal menyimpan ke database";
    header("Location: dokumentasi.php?kd=" . $kd_tr);
    exit;
}

$_SESSION['success'] = "Foto berhasil diupload";
header("Location: dokumentasi.php?kd=" . $kd_tr);
exit;
?>