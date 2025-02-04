<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['kd']) || empty($_GET['kd'])) {
    header("Location: dashboard.php");
    exit;
}

$kd_tr = $_GET['kd'];

// Ambil data transaksi
$query = "SELECT t.*, p.nama_paket 
          FROM transaksi t 
          JOIN paket p ON t.id_paket = p.id_paket 
          WHERE t.kd_tr = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $kd_tr);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$transaksi = mysqli_fetch_assoc($result);

if (!$transaksi) {
    header("Location: dashboard.php");
    exit;
}

// Ambil dokumentasi
$query_dok = "SELECT * FROM dokumentasi WHERE kd_tr = ? ORDER BY created_at DESC";
$stmt_dok = mysqli_prepare($conn, $query_dok);
mysqli_stmt_bind_param($stmt_dok, "s", $kd_tr);
mysqli_stmt_execute($stmt_dok);
$dokumentasi = mysqli_stmt_get_result($stmt_dok);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumentasi Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
    <style>
        .gallery-item {
            margin-bottom: 20px;
        }
        .gallery-item img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 8px;
            transition: transform 0.3s;
        }
        .gallery-item img:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
            </a>
        </div>
    </nav>

    <div class="container mt-4">
        <?php 
        if (isset($_SESSION['flash_message'])) {
            $message = $_SESSION['flash_message'];
            $type = isset($_SESSION['flash_type']) ? $_SESSION['flash_type'] : 'info';
            ?>
            <div class="alert alert-<?php echo $type; ?> alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php
            unset($_SESSION['flash_message']);
            unset($_SESSION['flash_type']);
        }
        ?>

        <!-- Info Transaksi -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Dokumentasi Transaksi #<?php echo htmlspecialchars($kd_tr); ?></h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table">
                            <tr>
                                <td width="150">Paket</td>
                                <td>: <?php echo htmlspecialchars($transaksi['nama_paket']); ?></td>
                            </tr>
                            <tr>
                                <td>Periode</td>
                                <td>: <?php echo date('d/m/Y', strtotime($transaksi['tgl_awal'])); ?> - 
                                     <?php echo date('d/m/Y', strtotime($transaksi['tgl_akhir'])); ?></td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>: <span class="badge bg-<?php 
                                    echo $transaksi['status'] == 'pending' ? 'warning' : 
                                        ($transaksi['status'] == 'proses' ? 'primary' : 'success'); 
                                    ?>"><?php echo ucfirst($transaksi['status']); ?></span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <!-- Form Upload -->
                        <form action="proses_dokumentasi.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="kd_tr" value="<?php echo htmlspecialchars($kd_tr); ?>">
                            <div class="mb-3">
                                <label class="form-label">Upload Foto Dokumentasi</label>
                                <input type="file" class="form-control" name="foto" accept="image/jpeg,image/png" required>
                                <small class="text-muted">Format: JPG, PNG (Max. 5MB)</small>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Upload Foto
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Gallery -->
                <div class="row">
                    <?php if(mysqli_num_rows($dokumentasi) > 0): ?>
                        <?php while($foto = mysqli_fetch_assoc($dokumentasi)): ?>
                            <div class="col-md-4">
                                <div class="gallery-item">
                                    <a href="../<?php echo htmlspecialchars($foto['url_file']); ?>" 
                                       data-lightbox="gallery" 
                                       data-title="Dokumentasi <?php echo date('d/m/Y H:i', strtotime($foto['created_at'])); ?>">
                                        <img src="../<?php echo htmlspecialchars($foto['url_file']); ?>" 
                                             alt="Dokumentasi" class="img-fluid">
                                    </a>
                                    <div class="text-center mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar"></i>
                                            <?php echo date('d/m/Y H:i', strtotime($foto['created_at'])); ?>
                                        </small>
                                        <a href="hapus_dokumentasi.php?id=<?php echo $foto['id_dokumentasi']; ?>&kd=<?php echo $kd_tr; ?>" 
                                           class="btn btn-danger btn-sm mt-1"
                                           onclick="return confirm('Yakin ingin menghapus foto ini?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle me-2"></i>
                                Belum ada foto dokumentasi untuk transaksi ini
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
    <script>
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true,
            'maxWidth': 1000
        });
    </script>
</body>
</html>