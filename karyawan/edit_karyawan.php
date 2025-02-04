<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id_karyawan = $_GET['id'];
    $query = "SELECT * FROM karyawan WHERE id_karyawan = $id_karyawan";
    $result = mysqli_query($conn, $query);
    $karyawan = mysqli_fetch_assoc($result);

    if (!$karyawan) {
        die("Karyawan tidak ditemukan.");
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_karyawan = $_POST['id_karyawan'];
    $nama_karyawan = $_POST['nama_karyawan'];
    $no_telp = $_POST['no_telp'];
    $alamat = $_POST['alamat'];

    $query = "UPDATE karyawan SET nama_karyawan = '$nama_karyawan', no_telp = '$no_telp', alamat = '$alamat' WHERE id_karyawan = $id_karyawan";
    if (mysqli_query($conn, $query)) {
        header("Location: karyawan.php");
        exit;
    } else {
        die("Error: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Karyawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin-style.css">
</head>
<body>
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Content -->
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Karyawan</h5>
            </div>
            <div class="card-body">
                <form action="edit_karyawan.php" method="post">
                    <input type="hidden" name="id_karyawan" value="<?php echo $karyawan['id_karyawan']; ?>">
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-user me-1"></i>Nama Karyawan
                        </label>
                        <input type="text" class="form-control" name="nama_karyawan" value="<?php echo $karyawan['nama_karyawan']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-phone me-1"></i>No. Telp
                        </label>
                        <input type="text" class="form-control" name="no_telp" value="<?php echo $karyawan['no_telp']; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-map-marker-alt me-1"></i>Alamat
                        </label>
                        <textarea class="form-control" name="alamat"><?php echo $karyawan['alamat']; ?></textarea>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>