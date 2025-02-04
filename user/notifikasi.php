<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['user_id'];

// Ambil notifikasi dari perubahan status transaksi
$query = "SELECT t.kd_tr, t.status, t.tgl_transaksi, p.nama_paket,
          CASE 
            WHEN t.status = 'proses' THEN 'Transaksi sedang diproses'
            WHEN t.status = 'selesai' THEN 'Transaksi telah selesai'
            ELSE 'Status transaksi berubah'
          END as pesan
          FROM transaksi t
          JOIN paket p ON t.id_paket = p.id_paket
          WHERE t.id_user = ? AND t.status != 'pending'
          ORDER BY t.tgl_transaksi DESC";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .notification-item {
            border-left: 4px solid #0d6efd;
            margin-bottom: 15px;
            transition: transform 0.2s;
        }
        .notification-item:hover {
            transform: translateX(5px);
        }
        .notification-item.unread {
            background-color: #f8f9fa;
        }
        .notification-time {
            font-size: 0.85em;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="mb-4">Notifikasi</h2>

        <div class="row">
            <div class="col-md-8">
                <?php if(mysqli_num_rows($result) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <div class="card notification-item">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-bell text-primary me-2"></i>
                                        <?php echo $row['pesan']; ?>
                                    </h6>
                                    <span class="badge bg-<?php 
                                        echo $row['status'] == 'proses' ? 'primary' : 'success'; 
                                    ?>">
                                        <?php echo ucfirst($row['status']); ?>
                                    </span>
                                </div>
                                <p class="card-text mb-2">
                                    Transaksi #<?php echo $row['kd_tr']; ?> - <?php echo $row['nama_paket']; ?>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="notification-time">
                                        <i class="fas fa-clock me-1"></i>
                                        <?php echo date('d/m/Y H:i', strtotime($row['tgl_transaksi'])); ?>
                                    </small>
                                    <a href="detail_transaksi.php?kd=<?php echo $row['kd_tr']; ?>" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Belum ada notifikasi
                    </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Filter Notifikasi</h5>
                        <hr>
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary" onclick="filterNotifikasi('all')">
                                Semua Notifikasi
                            </button>
                            <button class="btn btn-outline-primary" onclick="filterNotifikasi('proses')">
                                Status Proses
                            </button>
                            <button class="btn btn-outline-primary" onclick="filterNotifikasi('selesai')">
                                Status Selesai
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function filterNotifikasi(status) {
        // Tambahkan logika filter di sini
        window.location.href = 'notifikasi.php?filter=' + status;
    }
    </script>
</body>
</html>