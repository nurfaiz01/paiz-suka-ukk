<?php
$page_title = "Riwayat Transaksi";
include 'header.php';

// Set default filter
$where_clause = "t.id_karyawan = {$_SESSION['karyawan_id']} AND t.status = 'selesai'";
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Tambahkan filter tanggal jika ada
if (!empty($start_date) && !empty($end_date)) {
    $where_clause .= " AND (t.tgl_transaksi BETWEEN '$start_date' AND '$end_date')";
}

// Tambahkan filter pencarian jika ada
if (!empty($search)) {
    $where_clause .= " AND (t.kd_tr LIKE '%$search%' OR u.nama_lengkap LIKE '%$search%')";
}

// Query untuk mengambil riwayat transaksi
$query = "SELECT t.*, p.nama_paket, u.nama_lengkap as nama_user,
          (SELECT tanggal FROM detail_laporan 
           WHERE kd_tr = t.kd_tr AND keterangan LIKE '%selesai%' 
           ORDER BY tanggal DESC LIMIT 1) as tgl_selesai
          FROM transaksi t 
          LEFT JOIN paket p ON t.id_paket = p.id_paket 
          LEFT JOIN users u ON t.id_user = u.id_user 
          WHERE $where_clause
          ORDER BY t.created_at DESC";
$result = mysqli_query($conn, $query);

// Hitung total pendapatan
$query_total = "SELECT SUM(total_harga) as total_pendapatan 
                FROM transaksi 
                WHERE id_karyawan = {$_SESSION['karyawan_id']} 
                AND status = 'selesai'";
$total = mysqli_fetch_assoc(mysqli_query($conn, $query_total));
?>

<div class="container-fluid py-4">
    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" name="start_date" value="<?php echo $start_date; ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" class="form-control" name="end_date" value="<?php echo $end_date; ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Cari</label>
                    <input type="text" class="form-control" name="search" placeholder="Kode/Nama Pemilik" value="<?php echo $search; ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistik -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Transaksi Selesai</h5>
                    <h3><?php echo mysqli_num_rows($result); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Pendapatan</h5>
                    <h3>Rp <?php echo number_format($total['total_pendapatan'], 0, ',', '.'); ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Riwayat -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-history me-2"></i>Riwayat Transaksi Selesai
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Tanggal Transaksi</th>
                            <th>Tanggal Selesai</th>
                            <th>Pemilik</th>
                            <th>Paket</th>
                            <th>Periode</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($result) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo $row['kd_tr']; ?></td>
                                    <td><?php echo formatTanggal($row['tgl_transaksi']); ?></td>
                                    <td><?php echo $row['tgl_selesai'] ? date('d/m/Y H:i', strtotime($row['tgl_selesai'])) : '-'; ?></td>
                                    <td><?php echo $row['nama_user']; ?></td>
                                    <td><?php echo $row['nama_paket']; ?></td>
                                    <td>
                                        <?php echo formatTanggal($row['tgl_awal']); ?> s/d 
                                        <?php echo formatTanggal($row['tgl_akhir']); ?>
                                    </td>
                                    <td>Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                                    <td>
                                        <a href="detail_transaksi.php?kd=<?php echo $row['kd_tr']; ?>" 
                                           class="btn btn-info btn-sm">
                                            <i class="fas fa-eye me-1"></i>Detail
                                        </a>
                                        <?php if($row['status'] == 'selesai'): ?>
                                        <a href="cetak_laporan.php?kd=<?php echo $row['kd_tr']; ?>" 
                                           class="btn btn-secondary btn-sm" target="_blank">
                                            <i class="fas fa-print me-1"></i>Cetak
                                        </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">Belum ada riwayat transaksi</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>