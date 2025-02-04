<?php
session_start();
require_once '../includes/config.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Ambil data transaksi aktif user
$user_id = $_SESSION['user_id'];
$query = "SELECT t.*, p.nama_paket 
          FROM transaksi t 
          JOIN paket p ON t.id_paket = p.id_paket 
          WHERE t.id_user = ? AND t.status != 'selesai'
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
    <title>Dashboard - Pawspace.id</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .card {
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,.05);
            border-radius: 10px;
            border: none;
        }
        .btn-menu {
            text-align: left;
            padding: 12px;
            margin-bottom: 10px;
        }
        .profile-icon {
            font-size: 48px;
            color: #0d6efd;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">
                <i class="fas fa-paw"></i> Pawspace.id
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="daftar_paket.php">
                            <i class="fas fa-box"></i> Paket
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="transaksi.php">
                            <i class="fas fa-list"></i> Transaksi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profil.php">
                            <i class="fas fa-user"></i> Profil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="fas fa-sign-out-alt"></i> Keluar
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="container py-4">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-4">
                <!-- Profile Card -->
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-user-circle profile-icon mb-3"></i>
                        <h5 class="card-title">Selamat Datang</h5>
                        <p class="text-muted"><?php echo $_SESSION['user_nama']; ?></p>
                        <a href="profil.php" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-user-edit"></i> Edit Profil
                        </a>
                    </div>
                </div>

                <!-- Menu Card -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Menu Utama</h5>
                        <div class="d-grid">
                            <a href="daftar_paket.php" class="btn btn-primary btn-menu">
                                <i class="fas fa-box me-2"></i> Lihat Paket
                            </a>
                            <a href="transaksi.php" class="btn btn-primary btn-menu">
                                <i class="fas fa-list me-2"></i> Transaksi Saya
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0"><i class="fas fa-clock me-2"></i>Transaksi Aktif</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Paket</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(mysqli_num_rows($result) > 0): ?>
                                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                            <td><?php echo $row['kd_tr']; ?></td>
                                            <td><?php echo $row['nama_paket']; ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($row['tgl_transaksi'])); ?></td>
                                            <td>
                                                <span class="badge bg-<?php 
                                                    echo $row['status'] == 'pending' ? 'warning' : 
                                                        ($row['status'] == 'proses' ? 'primary' : 'success'); 
                                                ?>">
                                                    <?php echo ucfirst($row['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="detail_transaksi.php?kd=<?php echo $row['kd_tr']; ?>" 
                                                   class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-3">
                                                <p class="text-muted mb-0">Belum ada transaksi aktif</p>
                                                <a href="daftar_paket.php" class="btn btn-primary btn-sm mt-2">
                                                    <i class="fas fa-plus"></i> Buat Transaksi Baru
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>