<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_karyawan = $_POST['nama_karyawan'];
    $no_telp = $_POST['no_telp'];
    $alamat = $_POST['alamat'];

    $query = "INSERT INTO karyawan (nama_karyawan, no_telp, alamat) VALUES ('$nama_karyawan', '$no_telp', '$alamat')";
    if (mysqli_query($conn, $query)) {
        header("Location: karyawan.php");
        exit;
    } else {
        die("Error: " . mysqli_error($conn));
    }
}
?>