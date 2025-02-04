<?php
session_start();
require_once '../includes/config.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Filter status jika ada
$status_filter = isset($_GET['status']) ? clean($_GET['status']) : '';
$where_clause = "WHERE t.id_user = ?";
if($status_filter) {
    $where_clause .= " AND t.status = '$status_filter'";
}

// Ambil data transaksi
$query = "SELECT t.*, p.nama_paket 
          FROM transaksi t 
          JOIN paket p ON t.id_paket = p.id_paket 
          $where_clause
          ORDER BY t.created_at DESC";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi - Pawspace.id</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: white !important;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .navbar-brand {
            color: #333 !important;
            font-weight: bold;
        }
        .nav-link {
            color: #555 !important;
            font-weight: 500;
        }
        .nav-link:hover {
            color: #007bff !important;
        }
        .transaction-card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 0 15px rgba(0,0,0,.05);
        }
        .header-section {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,.05);
            padding: 20px;
            margin-bottom: 30px;
        }
        .filter-btn {
            border-radius: 20px;
            padding: 8px 20px;
            font-weight: 500;
            border: none;
            background-color: #f8f9fa;
            color: #666;
            margin: 0 5px;
            transition: all 0.3s;
        }
        .filter-btn:hover {
            background-color: #e9ecef;
        }
        .filter-btn.active {
            background-color: #007bff;
            color: white;
        }
        .table th {
            font-weight: 600;
            color: #333;
            border-top: none;
        }
        .table td {
            vertical-align: middle;
        }
        .badge {
            padding: 8px 12px;
            border-radius: 20px;
            font-weight: 500;
        }
        .btn-detail {
            border-radius: 20px;
            padding: 5px 15px;
        }
        .empty-state {
            padding: 40px 20px;
            text-align: center;
        }
        .empty-state img {
            width: 200px;
            opacity: 0.5;
            margin-bottom: 20px;
        }
        .empty-state .btn {
            border-radius: 20px;
            padding: 8px 25px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">
                <i class="fas fa-paw me-2"></i>Pawspace.id
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">
                            <i class="fas fa-home me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="daftar_paket.php">
                            <i class="fas fa-box me-1"></i> Paket
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="transaksi.php">
                            <i class="fas fa-list me-1"></i> Transaksi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profil.php">
                            <i class="fas fa-user me-1"></i> Profil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="fas fa-sign-out-alt me-1"></i> Keluar
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="container py-4">
        <!-- Header Section -->
        <div class="header-section">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">Transaksi Saya</h2>
                    <p class="text-muted mt-2 mb-0">Kelola dan pantau transaksi penitipan Anda</p>
                </div>
                <a href="daftar_paket.php" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Transaksi Baru
                </a>
            </div>
        </div>

        <!-- Filter Buttons -->
        <div class="text-center mb-4">
            <a href="transaksi.php" class="filter-btn <?php echo !$status_filter ? 'active' : ''; ?>">
                Semua
            </a>
            <a href="transaksi.php?status=pending" class="filter-btn <?php echo $status_filter == 'pending' ? 'active' : ''; ?>">
                Pending
            </a>
            <a href="transaksi.php?status=proses" class="filter-btn <?php echo $status_filter == 'proses' ? 'active' : ''; ?>">
                Proses
            </a>
            <a href="transaksi.php?status=selesai" class="filter-btn <?php echo $status_filter == 'selesai' ? 'active' : ''; ?>">
                Selesai
            </a>
        </div>

        <!-- Transactions Table -->
        <div class="card transaction-card">
            <div class="card-body p-0">
                <?php if(mysqli_num_rows($result) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Kode</th>
                                    <th>Tanggal</th>
                                    <th>Paket</th>
                                    <th>Periode</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th class="pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td class="ps-4"><?php echo $row['kd_tr']; ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($row['tgl_transaksi'])); ?></td>
                                        <td><?php echo $row['nama_paket']; ?></td>
                                        <td>
                                            <?php echo date('d/m/Y', strtotime($row['tgl_awal'])); ?> - 
                                            <?php echo date('d/m/Y', strtotime($row['tgl_akhir'])); ?>
                                        </td>
                                        <td>Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                echo $row['status'] == 'pending' ? 'warning' : 
                                                    ($row['status'] == 'proses' ? 'primary' : 'success'); 
                                            ?>">
                                                <?php echo ucfirst($row['status']); ?>
                                            </span>
                                        </td>
                                        <td class="pe-4">
                                            <a href="detail_transaksi.php?kd=<?php echo $row['kd_tr']; ?>" 
                                               class="btn btn-info btn-sm btn-detail">
                                                <i class="fas fa-eye me-1"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <img src="../assets/images/empty.svg" alt="Empty" class="mb-3">
                        <h5>Belum Ada Transaksi</h5>
                        <p class="text-muted">Anda belum memiliki transaksi. Mulai penitipan sekarang!</p>
                        <a href="daftar_paket.php" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Buat Transaksi Baru
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>