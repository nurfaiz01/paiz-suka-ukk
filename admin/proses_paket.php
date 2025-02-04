<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Proses Tambah Paket
if(isset($_POST['tambah'])) {
    $id_paket = $_POST['id_paket'];
    $nama_paket = $_POST['nama_paket'];
    $harga = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];

    // Cek apakah ID Paket sudah ada
    $check = mysqli_query($conn, "SELECT id_paket FROM paket WHERE id_paket = '$id_paket'");
    if(mysqli_num_rows($check) > 0) {
        $_SESSION['flash_message'] = "ID Paket sudah digunakan";
        $_SESSION['flash_type'] = "danger";
        header("Location: paket.php");
        exit;
    }

    $query = "INSERT INTO paket (id_paket, nama_paket, harga, deskripsi) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssds", $id_paket, $nama_paket, $harga, $deskripsi);

    if(mysqli_stmt_execute($stmt)) {
        $_SESSION['flash_message'] = "Paket berhasil ditambahkan";
        $_SESSION['flash_type'] = "success";
    } else {
        $_SESSION['flash_message'] = "Gagal menambahkan paket";
        $_SESSION['flash_type'] = "danger";
    }
    mysqli_stmt_close($stmt);
}

// Proses Edit Paket
if(isset($_POST['edit'])) {
    $id_paket_lama = $_POST['id_paket_lama'];
    $id_paket = $_POST['id_paket'];
    $nama_paket = $_POST['nama_paket'];
    $harga = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];

    // Cek apakah ID Paket baru sudah ada (jika ID diubah)
    if($id_paket_lama != $id_paket) {
        $check = mysqli_query($conn, "SELECT id_paket FROM paket WHERE id_paket = '$id_paket'");
        if(mysqli_num_rows($check) > 0) {
            $_SESSION['flash_message'] = "ID Paket sudah digunakan";
            $_SESSION['flash_type'] = "danger";
            header("Location: paket.php");
            exit;
        }
    }

    $query = "UPDATE paket SET id_paket = ?, nama_paket = ?, harga = ?, deskripsi = ? WHERE id_paket = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssdss", $id_paket, $nama_paket, $harga, $deskripsi, $id_paket_lama);

    if(mysqli_stmt_execute($stmt)) {
        $_SESSION['flash_message'] = "Paket berhasil diupdate";
        $_SESSION['flash_type'] = "success";
    } else {
        $_SESSION['flash_message'] = "Gagal mengupdate paket";
        $_SESSION['flash_type'] = "danger";
    }
    mysqli_stmt_close($stmt);
}

// Proses Hapus Paket
if(isset($_GET['hapus'])) {
    $id_paket = $_GET['hapus'];

    // Cek apakah paket sedang digunakan di transaksi
    $check = mysqli_query($conn, "SELECT kd_tr FROM transaksi WHERE id_paket = '$id_paket'");
    if(mysqli_num_rows($check) > 0) {
        $_SESSION['flash_message'] = "Paket tidak dapat dihapus karena sedang digunakan dalam transaksi";
        $_SESSION['flash_type'] = "danger";
        header("Location: paket.php");
        exit;
    }

    $query = "DELETE FROM paket WHERE id_paket = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $id_paket);

    if(mysqli_stmt_execute($stmt)) {
        $_SESSION['flash_message'] = "Paket berhasil dihapus";
        $_SESSION['flash_type'] = "success";
    } else {
        $_SESSION['flash_message'] = "Gagal menghapus paket";
        $_SESSION['flash_type'] = "danger";
    }
    mysqli_stmt_close($stmt);
}

header("Location: paket.php");
exit;
?>