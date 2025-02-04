<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Filter tanggal default
$tgl_awal = isset($_GET['tgl_awal']) ? $_GET['tgl_awal'] : date('Y-m-01');
$tgl_akhir = isset($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : date('Y-m-t');

// Query untuk laporan dengan filter tanggal
$query = "SELECT t.*, p.nama_paket, 
          COALESCE(a.nama_lengkap, u.nama_lengkap) as pembuat,
          CASE 
              WHEN t.id_admin IS NOT NULL THEN 'Admin'
              ELSE 'User'
          END as role
          FROM transaksi t 
          JOIN paket p ON t.id_paket = p.id_paket
          LEFT JOIN admin a ON t.id_admin = a.id_admin
          LEFT JOIN users u ON t.id_user = u.id_user
          WHERE DATE(t.tgl_transaksi) >= ?
          AND DATE(t.tgl_transaksi) <= ?
          ORDER BY t.tgl_transaksi DESC";

// Persiapkan statement
$stmt = mysqli_prepare($conn, $query);

// Konversi format tanggal untuk query
$tgl_awal_query = date('Y-m-d', strtotime($tgl_awal));
$tgl_akhir_query = date('Y-m-d', strtotime($tgl_akhir));

// Binding parameter
mysqli_stmt_bind_param($stmt, "ss", $tgl_awal_query, $tgl_akhir_query);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Dashboard</a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Laporan Transaksi</h5>
            </div>
            <div class="card-body">
                <!-- Form Filter dengan value yang benar -->
                <form method="get" action="" class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Awal</label>
                        <input type="date" class="form-control" name="tgl_awal" 
                               value="<?php echo date('Y-m-d', strtotime($tgl_awal)); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Akhir</label>
                        <input type="date" class="form-control" name="tgl_akhir" 
                               value="<?php echo date('Y-m-d', strtotime($tgl_akhir)); ?>" required>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                        <a href="laporan.php" class="btn btn-secondary me-2">Reset</a>
                        <a href="cetak_laporan.php?tgl_awal=<?php echo $tgl_awal; ?>&tgl_akhir=<?php echo $tgl_akhir; ?>" 
                           class="btn btn-success" target="_blank">
                            <i class="fas fa-print"></i> Cetak
                        </a>
                    </div>
                </form>

                <!-- Debug info untuk memastikan filter berfungsi -->
                <?php if(isset($_GET['tgl_awal']) && isset($_GET['tgl_akhir'])): ?>
                <div class="alert alert-info">
                    Menampilkan data dari <?php echo date('d/m/Y', strtotime($tgl_awal)); ?> 
                    sampai <?php echo date('d/m/Y', strtotime($tgl_akhir)); ?>
                </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Tanggal</th>
                                <th>Pembuat</th>
                                <th>Role</th>
                                <th>Paket</th>
                                <th>Tgl Awal</th>
                                <th>Tgl Akhir</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if(mysqli_num_rows($result) > 0):
                                $no = 1;
                                while($row = mysqli_fetch_assoc($result)): 
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $row['kd_tr']; ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($row['tgl_transaksi'])); ?></td>
                                    <td><?php echo $row['pembuat']; ?></td>
                                    <td><?php echo $row['role']; ?></td>
                                    <td><?php echo $row['nama_paket']; ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($row['tgl_awal'])); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($row['tgl_akhir'])); ?></td>
                                    <td>Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
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
                                           class="btn btn-info btn-sm">Detail</a>
                                    </td>
                                </tr>
                            <?php 
                                endwhile;
                            else:
                            ?>
                                <tr>
                                    <td colspan="11" class="text-center">Tidak ada data untuk periode ini</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>