<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

if(isset($_GET['id']) && isset($_GET['kd'])) {
    $id_dokumentasi = $_GET['id'];
    $kd_tr = $_GET['kd'];

    // Ambil informasi file sebelum dihapus
    $query = "SELECT url_file FROM dokumentasi WHERE id_dokumentasi = ? AND kd_tr = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "is", $id_dokumentasi, $kd_tr);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $dokumentasi = mysqli_fetch_assoc($result);

    if($dokumentasi) {
        // Hapus file fisik
        $file_path = '../' . $dokumentasi['url_file'];
        if(file_exists($file_path)) {
            unlink($file_path);
        }

        // Hapus record dari database
        $query = "DELETE FROM dokumentasi WHERE id_dokumentasi = ? AND kd_tr = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "is", $id_dokumentasi, $kd_tr);
        
        if(mysqli_stmt_execute($stmt)) {
            $_SESSION['flash_message'] = "Foto berhasil dihapus";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_message'] = "Gagal menghapus foto";
            $_SESSION['flash_type'] = "danger";
        }
    }
} else {
    $_SESSION['flash_message'] = "Parameter tidak lengkap";
    $_SESSION['flash_type'] = "danger";
}

header("Location: dokumentasi.php?kd=" . $kd_tr);
exit;
?>