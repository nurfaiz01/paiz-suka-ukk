<?php
session_start();
require_once '../includes/config.php';

// Cek login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Ambil data transaksi
$query = "SELECT t.*, p.nama_paket 
          FROM transaksi t 
          JOIN paket p ON t.id_paket = p.id_paket 
          ORDER BY t.created_at DESC";
$result = mysqli_query($conn, $query);

// Cek apakah query berhasil
if (!$result) {
    die("Query error: " . mysqli_error($conn));
}

// Hitung total pendapatan
$query_pendapatan = "SELECT SUM(total_harga) as total FROM transaksi WHERE status = 'selesai'";
$pendapatan = mysqli_fetch_assoc(mysqli_query($conn, $query_pendapatan))['total'] ?? 0;

// Hitung jumlah transaksi per status
$query_pending = "SELECT COUNT(*) as total FROM transaksi WHERE status = 'pending'";
$query_proses = "SELECT COUNT(*) as total FROM transaksi WHERE status = 'proses'";
$query_selesai = "SELECT COUNT(*) as total FROM transaksi WHERE status = 'selesai'";

$total_pending = mysqli_fetch_assoc(mysqli_query($conn, $query_pending))['total'];
$total_proses = mysqli_fetch_assoc(mysqli_query($conn, $query_proses))['total'];
$total_selesai = mysqli_fetch_assoc(mysqli_query($conn, $query_selesai))['total'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin-style.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-camera-retro me-2"></i>Admin Panel
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="paket.php">
                            <i class="fas fa-box me-1"></i> Kelola Paket
                        </a>
                    </li>
                    <li class="nav-item">
    <a class="nav-link" href="kategori.php">
        <i class="fas fa-paw me-1"></i> Kategori Hewan
    </a>
</li>
                    <!-- Tambah menu Data Karyawan -->
                    <li class="nav-item">
                        <a class="nav-link" href="karyawan.php">
                            <i class="fas fa-user-tie me-1"></i> Data Karyawan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="laporan.php">
                            <i class="fas fa-file-alt me-1"></i> Laporan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profil.php">
                            <i class="fas fa-user me-1"></i> Profil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger ms-2" href="logout.php">
                            <i class="fas fa-sign-out-alt me-1"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="container mt-4">
        <!-- Statistik -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card stat-card bg-gradient-warning text-black">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-clock me-2"></i>Pending</h5>
                        <h3><?php echo $total_pending; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card bg-gradient-primary text-black">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-spinner me-2"></i>Proses</h5>
                        <h3><?php echo $total_proses; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card bg-gradient-success text-black">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-check-circle me-2"></i>Selesai</h5>
                        <h3><?php echo $total_selesai; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card bg-gradient-info text-black">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-money-bill-wave me-2"></i>Pendapatan</h5>
                        <h3>Rp <?php echo number_format($pendapatan, 0, ',', '.'); ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Transaksi -->
        <div class="card table-container">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-table me-2"></i>Data Transaksi</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Kd Tr</th>
                                <th>Tanggal</th>
                                <th>Paket</th>
                                <th>Tgl Awal</th>
                                <th>Tgl Akhir</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo $row['kd_tr']; ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($row['tgl_transaksi'])); ?></td>
                                    <td><?php echo $row['nama_paket']; ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($row['tgl_awal'])); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($row['tgl_akhir'])); ?></td>
                                    <td>Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                                    <td>
                                        <span class="badge bg-<?php
                                                                echo $row['status'] == 'pending' ? 'warning' : ($row['status'] == 'proses' ? 'primary' : 'success');
                                                                ?>">
                                            <?php echo ucfirst($row['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="detail_transaksi.php?kd=<?php echo $row['kd_tr']; ?>"
                                                class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                            <a href="dokumentasi.php?kd=<?php echo $row['kd_tr']; ?>"
                                                class="btn btn-success btn-sm">
                                                <i class="fas fa-camera"></i> Dokumentasi
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Transaksi -->
    <div class="modal fade" id="tambahTransaksiModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle me-2"></i>Tambah Transaksi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="proses_transaksi.php" method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-hashtag me-1"></i>Kode Transaksi
                            </label>
                            <input type="text" class="form-control" name="kd_tr" readonly
                                value="TR<?php echo date('Ymd') . rand(100, 999); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-box me-1"></i>Paket
                            </label>
                            <select class="form-select" name="id_paket" required>
                                <option value="">Pilih Paket</option>
                                <?php
                                $query_paket = mysqli_query($conn, "SELECT * FROM paket ORDER BY harga ASC");
                                while ($paket = mysqli_fetch_assoc($query_paket)):
                                ?>
                                    <option value="<?php echo $paket['id_paket']; ?>">
                                        <?php echo $paket['nama_paket']; ?> -
                                        Rp <?php echo number_format($paket['harga'], 0, ',', '.'); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-calendar me-1"></i>Tanggal Awal
                            </label>
                            <input type="date" class="form-control" name="tgl_awal" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-calendar-check me-1"></i>Tanggal Akhir
                            </label>
                            <input type="date" class="form-control" name="tgl_akhir" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Batal
                        </button>
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