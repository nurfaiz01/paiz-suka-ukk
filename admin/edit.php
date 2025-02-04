<?php
session_start();
require_once '../includes/config.php';

// Cek login admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Ambil data karyawan berdasarkan ID
$id_karyawan = $_GET['id'];
$query = "SELECT * FROM karyawan WHERE id_karyawan = '$id_karyawan'";
$result = mysqli_query($conn, $query);
$karyawan = mysqli_fetch_assoc($result);

// Jika data tidak ditemukan
if (!$karyawan) {
    $_SESSION['error'] = "Data karyawan tidak ditemukan";
    header("Location: karyawan.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Karyawan - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin-style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="karyawan.php">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i>Edit Karyawan
                </h5>
            </div>
            <div class="card-body">
                <form action="edit_karyawan.php" method="POST">
                    <input type="hidden" name="id_karyawan" value="<?= $karyawan['id_karyawan']; ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" value="<?= $karyawan['username']; ?>" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" name="nama_lengkap" value="<?= $karyawan['nama_lengkap']; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="<?= $karyawan['email']; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" class="form-control" name="no_telp" value="<?= $karyawan['no_telp']; ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea class="form-control" name="alamat" rows="3"><?= $karyawan['alamat']; ?></textarea>
                    </div>
                    
                    <div class="text-end">
                        <a href="karyawan.php" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>