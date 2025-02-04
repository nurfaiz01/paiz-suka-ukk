<?php
$page_title = "Dashboard Karyawan";
include 'header.php';

// Query untuk mengambil transaksi yang belum diambil (pending)
$query_pending = "SELECT t.*, p.nama_paket, u.nama_lengkap as nama_user, k.nama_lengkap as nama_karyawan 
                 FROM transaksi t 
                 LEFT JOIN paket p ON t.id_paket = p.id_paket 
                 LEFT JOIN users u ON t.id_user = u.id_user 
                 LEFT JOIN karyawan k ON t.id_karyawan = k.id_karyawan 
                 WHERE t.status = 'pending' 
                 ORDER BY t.created_at DESC";
$result_pending = mysqli_query($conn, $query_pending);

// Query untuk mengambil transaksi yang sedang ditangani karyawan ini
$query_proses = "SELECT t.*, p.nama_paket, u.nama_lengkap as nama_user 
                FROM transaksi t 
                LEFT JOIN paket p ON t.id_paket = p.id_paket 
                LEFT JOIN users u ON t.id_user = u.id_user 
                WHERE t.id_karyawan = {$_SESSION['karyawan_id']} 
                AND t.status = 'proses' 
                ORDER BY t.created_at DESC";
$result_proses = mysqli_query($conn, $query_proses);

// Query untuk statistik karyawan
$query_stats = "SELECT 
    COUNT(CASE WHEN status = 'proses' THEN 1 END) as total_proses,
    COUNT(CASE WHEN status = 'selesai' THEN 1 END) as total_selesai
    FROM transaksi 
    WHERE id_karyawan = {$_SESSION['karyawan_id']}";
$stats = mysqli_fetch_assoc(mysqli_query($conn, $query_stats));
?>

<!-- Statistik Karyawan -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Tugas Aktif</h5>
                <h3><?php echo $stats['total_proses']; ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Tugas Selesai</h5>
                <h3><?php echo $stats['total_selesai']; ?></h3>
            </div>
        </div>
    </div>
</div>

<!-- Daftar Transaksi Pending -->
<div class="card mb-4">
    <div class="card-header bg-danger text-white">
        <h5 class="card-title mb-0"><i class="fas fa-clock me-2"></i>Transaksi Pending</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Tanggal</th>
                        <th>Pemilik</th>
                        <th>Paket</th>
                        <th>Periode</th>
                        <th>Total</th>
                        <th>Petugas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($result_pending) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($result_pending)): ?>
                            <tr>
                                <td><?php echo $row['kd_tr']; ?></td>
                                <td><?php echo formatTanggal($row['tgl_transaksi']); ?></td>
                                <td><?php echo $row['nama_user']; ?></td>
                                <td><?php echo $row['nama_paket']; ?></td>
                                <td>
                                    <?php echo formatTanggal($row['tgl_awal']); ?> s/d 
                                    <?php echo formatTanggal($row['tgl_akhir']); ?>
                                </td>
                                <td>Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                                <td>
                                    <?php 
                                    echo !empty($row['nama_karyawan']) ? 
                                        $row['nama_karyawan'] : 
                                        '<span class="badge bg-secondary">Belum ada</span>'; 
                                    ?>
                                </td>
                                <td>
                                    <a href="detail_transaksi.php?kd=<?php echo $row['kd_tr']; ?>" 
                                       class="btn btn-info btn-sm">
                                        <i class="fas fa-eye me-1"></i>Detail
                                    </a>
                                    <?php if(empty($row['id_karyawan'])): ?>
                                    <a href="ambil_tugas.php?kd=<?php echo $row['kd_tr']; ?>" 
                                       class="btn btn-success btn-sm"
                                       onclick="return confirm('Ambil tugas ini?')">
                                        <i class="fas fa-check me-1"></i>Ambil
                                    </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada transaksi pending</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Daftar Transaksi Yang Ditangani -->
<div class="card">
    <div class="card-header bg-success text-white">
        <h5 class="card-title mb-0"><i class="fas fa-tasks me-2"></i>Transaksi Yang Ditangani</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Tanggal</th>
                        <th>Pemilik</th>
                        <th>Paket</th>
                        <th>Periode</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($result_proses) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($result_proses)): ?>
                            <tr>
                                <td><?php echo $row['kd_tr']; ?></td>
                                <td><?php echo formatTanggal($row['tgl_transaksi']); ?></td>
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
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">Belum ada transaksi yang ditangani</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>