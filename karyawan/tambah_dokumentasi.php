<?php
session_start();
require_once '../includes/config.php';

// Cek login
if(!isset($_SESSION['karyawan_id'])) {
    header("Location: login.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kd_tr = clean($_POST['kd_tr']);
    $id_karyawan = $_SESSION['karyawan_id'];
    
    // Cek transaksi
    $query_cek = "SELECT status FROM transaksi 
                 WHERE kd_tr = ? AND id_karyawan = ? 
                 AND status = 'proses'";
    $stmt_cek = mysqli_prepare($conn, $query_cek);
    mysqli_stmt_bind_param($stmt_cek, "si", $kd_tr, $id_karyawan);
    mysqli_stmt_execute($stmt_cek);
    $result_cek = mysqli_stmt_get_result($stmt_cek);
    
    if(mysqli_num_rows($result_cek) > 0) {
        // Upload file
        if(isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
            $file = $_FILES['foto'];
            $nama_file = $file['name'];
            $ukuran = $file['size'];
            $tmp = $file['tmp_name'];
            
            // Cek ukuran (max 2MB)
            if($ukuran > 2097152) {
                $_SESSION['error'] = "Ukuran file terlalu besar! Max. 2MB";
                header("Location: detail_transaksi.php?kd=" . $kd_tr);
                exit;
            }
            
            // Cek tipe file
            $allowed = ['image/jpeg', 'image/jpg', 'image/png'];
            $tipe = mime_content_type($tmp);
            if(!in_array($tipe, $allowed)) {
                $_SESSION['error'] = "Tipe file tidak diizinkan!";
                header("Location: detail_transaksi.php?kd=" . $kd_tr);
                exit;
            }
            
            // Buat folder jika belum ada
            if(!file_exists('../uploads/dokumentasi')) {
                mkdir('../uploads/dokumentasi', 0777, true);
            }
            
            // Generate nama file unik
            $ext = pathinfo($nama_file, PATHINFO_EXTENSION);
            $nama_baru = uniqid() . '_' . time() . '.' . $ext;
            $path = '../uploads/dokumentasi/' . $nama_baru;
            
            // Pindahkan file
            if(move_uploaded_file($tmp, $path)) {
                // Simpan ke database
                $url_file = 'uploads/dokumentasi/' . $nama_baru;
                $query = "INSERT INTO dokumentasi (kd_tr, nama_file, jenis_file, url_file, id_karyawan) 
                         VALUES (?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "ssssi", $kd_tr, $nama_file, $tipe, $url_file, $id_karyawan);
                
                if(mysqli_stmt_execute($stmt)) {
                    $_SESSION['success'] = "Dokumentasi berhasil diupload!";
                } else {
                    $_SESSION['error'] = "Gagal menyimpan dokumentasi!";
                    // Hapus file jika gagal simpan ke database
                    unlink($path);
                }
            } else {
                $_SESSION['error'] = "Gagal mengupload file!";
            }
        } else {
            $_SESSION['error'] = "File tidak valid!";
        }
    } else {
        $_SESSION['error'] = "Transaksi tidak valid atau bukan tanggung jawab Anda!";
    }
    
    header("Location: detail_transaksi.php?kd=" . $kd_tr);
    exit;
}

header("Location: index.php");
exit;