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

$kd_tr = clean($_GET['kd']);

// Ambil data transaksi
$query = "SELECT t.*, p.nama_paket, u.nama_lengkap as nama_user 
          FROM transaksi t 
          JOIN paket p ON t.id_paket = p.id_paket
          JOIN users u ON t.id_user = u.id_user 
          WHERE t.kd_tr = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $kd_tr);
mysqli_stmt_execute($stmt);
$transaksi = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$transaksi) {
    header("Location: dashboard.php");
    exit;
}

// Ambil detail laporan
$query_laporan = "SELECT * FROM detail_laporan WHERE kd_tr = ? ORDER BY tanggal DESC";
$stmt = mysqli_prepare($conn, $query_laporan);
mysqli_stmt_bind_param($stmt, "s", $kd_tr);
mysqli_stmt_execute($stmt);
$result_laporan = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laporan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .timeline {
            position: relative;
            padding: 20px 0;
        }
        .timeline-item {
            padding: 20px;
            border-left: 2px solid #0d6efd;
            position: relative;
            margin-bottom: 20px;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            width: 12px;
            height: 12px;
            background: #0d6efd;
            border-radius: 50%;
            left: -7px;
            top: 24px;
        }
        .timeline-date {
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Admin Panel</a>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Info Transaksi -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Detail Laporan Transaksi #<?php echo $kd_tr; ?></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table">
                            <tr>
                                <td width="150">Pelanggan</td>
                                <td>: <?php echo $transaksi['nama_user']; ?></td>
                            </tr>
                            <tr>
                                <td>Paket</td>
                                <td>: <?php echo $transaksi['nama_paket']; ?></td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>: 
                                    <span class="badge bg-<?php 
                                        echo $transaksi['status'] == 'pending' ? 'warning' : 
                                            ($transaksi['status'] == 'proses' ? 'primary' : 'success'); 
                                    ?>">
                                        <?php echo ucfirst($transaksi['status']); ?>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table">
                            <tr>
                                <td width="150">Tanggal Mulai</td>
                                <td>: <?php echo date('d/m/Y', strtotime($transaksi['tgl_awal'])); ?></td>
                            </tr>
                            <tr>
                                <td>Tanggal Selesai</td>
                                <td>: <?php echo date('d/m/Y', strtotime($transaksi['tgl_akhir'])); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Tambah Laporan -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Tambah Laporan Harian</h5>
            </div>
            <div class="card-body">
                <form action="proses_laporan.php" method="post">
                    <input type="hidden" name="kd_tr" value="<?php echo $kd_tr; ?>">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Tanggal</label>
                                <input type="date" class="form-control" name="tanggal" 
                                       value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">Keterangan</label>
                                <textarea class="form-control" name="keterangan" rows="3" required
                                          placeholder="Masukkan laporan kondisi hewan hari ini..."></textarea>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Laporan
                    </button>
                </form>
            </div>
        </div>

        <!-- Timeline Laporan -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Riwayat Laporan</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <?php if(mysqli_num_rows($result_laporan) > 0): ?>
                        <?php while($laporan = mysqli_fetch_assoc($result_laporan)): ?>
                            <div class="timeline-item">
                                <div class="timeline-date">
                                    <?php echo date('d/m/Y', strtotime($laporan['tanggal'])); ?>
                                </div>
                                <div class="timeline-content">
                                    <?php echo nl2br($laporan['keterangan']); ?>
                                    <div class="mt-2">
                                        <button onclick="hapusLaporan(<?php echo $laporan['id_detail']; ?>)" 
                                                class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-center text-muted">Belum ada laporan</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <a href="detail_transaksi.php?kd=<?php echo $kd_tr; ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function hapusLaporan(id) {
        if(confirm('Apakah Anda yakin ingin menghapus laporan ini?')) {
            window.location.href = 'hapus_laporan.php?id=' + id + '&kd=<?php echo $kd_tr; ?>';
        }
    }
    </script>
</body>
</html>