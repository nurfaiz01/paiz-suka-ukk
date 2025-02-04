<?php
session_start();
require_once '../includes/config.php';

// Cek login admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Cek parameter kd_tr
if (!isset($_GET['kd']) || empty($_GET['kd'])) {
    header("Location: dashboard.php");
    exit;
}

$kd_tr = clean($_GET['kd']);

// Ambil data transaksi dengan info karyawan dan kategori hewan
$query = "SELECT t.*, p.nama_paket, p.harga, u.nama_lengkap, u.email, u.no_telp, u.alamat,
          k.nama_lengkap as nama_karyawan, k.no_telp as telp_karyawan, k.email as email_karyawan,
          kh.nama_kategori, kh.deskripsi as deskripsi_kategori
          FROM transaksi t 
          JOIN paket p ON t.id_paket = p.id_paket
          JOIN users u ON t.id_user = u.id_user
          LEFT JOIN karyawan k ON t.id_karyawan = k.id_karyawan
          LEFT JOIN kategori_hewan kh ON t.id_kategori = kh.id_kategori
          WHERE t.kd_tr = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $kd_tr);
mysqli_stmt_execute($stmt);
$transaksi = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$transaksi) {
    header("Location: dashboard.php");
    exit;
}

// Ambil detail laporan pertama untuk mendapatkan informasi hewan
$query_first_report = "SELECT * FROM detail_laporan WHERE kd_tr = ? ORDER BY created_at ASC LIMIT 1";
$stmt = mysqli_prepare($conn, $query_first_report);
mysqli_stmt_bind_param($stmt, "s", $kd_tr);
mysqli_stmt_execute($stmt);
$first_report = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

// Parse informasi hewan dari keterangan
$info_hewan = [
    'nama' => '',
    'catatan' => ''
];
if ($first_report) {
    $lines = explode("\n", $first_report['keterangan']);
    foreach ($lines as $line) {
        if (strpos($line, 'Nama Hewan:') !== false) {
            $info_hewan['nama'] = trim(str_replace('Nama Hewan:', '', $line));
        }
        if (strpos($line, 'Catatan:') !== false) {
            $info_hewan['catatan'] = trim(str_replace('Catatan:', '', $line));
        }
    }
}

// Ambil detail laporan dengan info karyawan
$query_laporan = "SELECT dl.*, k.nama_lengkap as nama_karyawan 
                 FROM detail_laporan dl
                 LEFT JOIN karyawan k ON dl.id_karyawan = k.id_karyawan 
                 WHERE dl.kd_tr = ? 
                 ORDER BY dl.tanggal DESC";
$stmt = mysqli_prepare($conn, $query_laporan);
mysqli_stmt_bind_param($stmt, "s", $kd_tr);
mysqli_stmt_execute($stmt);
$result_laporan = mysqli_stmt_get_result($stmt);

// Ambil dokumentasi dengan info karyawan
$query_dokumentasi = "SELECT d.*, k.nama_lengkap as nama_karyawan 
                     FROM dokumentasi d
                     LEFT JOIN karyawan k ON d.id_karyawan = k.id_karyawan 
                     WHERE d.kd_tr = ? 
                     ORDER BY d.created_at DESC";
$stmt = mysqli_prepare($conn, $query_dokumentasi);
mysqli_stmt_bind_param($stmt, "s", $kd_tr);
mysqli_stmt_execute($stmt);
$result_dokumentasi = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi</title>
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
        .gallery-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
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
        <!-- Info Transaksi -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Detail Transaksi #<?php echo $kd_tr; ?></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <h6 class="mb-3">Informasi Transaksi</h6>
                        <table class="table">
                            <tr>
                                <td width="150">Kode Transaksi</td>
                                <td>: <?php echo $transaksi['kd_tr']; ?></td>
                            </tr>
                            <tr>
                                <td>Tanggal</td>
                                <td>: <?php echo date('d/m/Y', strtotime($transaksi['tgl_transaksi'])); ?></td>
                            </tr>
                            <tr>
                                <td>Paket</td>
                                <td>: <?php echo $transaksi['nama_paket']; ?></td>
                            </tr>
                            <tr>
                                <td>Periode</td>
                                <td>: <?php echo date('d/m/Y', strtotime($transaksi['tgl_awal'])); ?> - 
                                     <?php echo date('d/m/Y', strtotime($transaksi['tgl_akhir'])); ?></td>
                            </tr>
                            <tr>
                                <td>Total</td>
                                <td>: Rp <?php echo number_format($transaksi['total_harga'], 0, ',', '.'); ?></td>
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

                    <div class="col-md-3">
                        <h6 class="mb-3">Informasi Hewan</h6>
                        <table class="table">
                            <tr>
                                <td width="150">Jenis Hewan</td>
                                <td>: <?php echo $transaksi['nama_kategori']; ?></td>
                            </tr>
                            <tr>
                                <td>Nama Hewan</td>
                                <td>: <?php echo $info_hewan['nama']; ?></td>
                            </tr>
                            <tr>
                                <td>Catatan</td>
                                <td>: <?php echo $info_hewan['catatan']; ?></td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-3">
                        <h6 class="mb-3">Informasi Pelanggan</h6>
                        <table class="table">
                            <tr>
                                <td width="150">Nama</td>
                                <td>: <?php echo $transaksi['nama_lengkap']; ?></td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>: <?php echo $transaksi['email']; ?></td>
                            </tr>
                            <tr>
                                <td>No. Telepon</td>
                                <td>: 
                                    <a href="https://wa.me/<?php echo str_replace(['+', ' ', '-'], '', $transaksi['no_telp']); ?>" 
                                       class="btn btn-success btn-sm" target="_blank">
                                        <i class="fab fa-whatsapp"></i> <?php echo $transaksi['no_telp']; ?>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>: <?php echo $transaksi['alamat']; ?></td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-3">
                        <h6 class="mb-3">Informasi Petugas</h6>
                        <?php if($transaksi['id_karyawan']): ?>
                            <table class="table">
                                <tr>
                                    <td width="150">Nama Petugas</td>
                                    <td>: <?php echo $transaksi['nama_karyawan']; ?></td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>: <?php echo $transaksi['email_karyawan']; ?></td>
                                </tr>
                                <tr>
                                    <td>No. Telepon</td>
                                    <td>: 
                                        <a href="https://wa.me/<?php echo str_replace(['+', ' ', '-'], '', $transaksi['telp_karyawan']); ?>" 
                                           class="btn btn-success btn-sm" target="_blank">
                                            <i class="fab fa-whatsapp"></i> <?php echo $transaksi['telp_karyawan']; ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td>: 
                                        <?php if($transaksi['status'] == 'proses'): ?>
                                            <span class="badge bg-primary">Sedang Menangani</span>
                                        <?php elseif($transaksi['status'] == 'selesai'): ?>
                                            <span class="badge bg-success">Selesai</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        <?php else: ?>
                            <div class="alert alert-warning">
                                <i class="fas fa-info-circle"></i> Belum ada petugas yang menangani
                            </div>
                        <?php endif; ?>

                        <!-- Update Status -->
                        <div class="mt-3">
                            <form action="update_status.php" method="post" class="d-flex gap-2">
                                <input type="hidden" name="kd_tr" value="<?php echo $kd_tr; ?>">
                                <select name="status" class="form-select">
                                    <option value="pending" <?php echo $transaksi['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="proses" <?php echo $transaksi['status'] == 'proses' ? 'selected' : ''; ?>>Proses</option>
                                    <option value="selesai" <?php echo $transaksi['status'] == 'selesai' ? 'selected' : ''; ?>>Selesai</option>
                                </select>
                                <button type="submit" class="btn btn-primary">Update Status</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Laporan dan Dokumentasi -->
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Riwayat Laporan</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <?php if(mysqli_num_rows($result_laporan) > 0): ?>
                                <?php while($laporan = mysqli_fetch_assoc($result_laporan)): ?>
                                    <div class="timeline-item">
                                        <div class="timeline-date">
                                            <?php echo date('d/m/Y', strtotime($laporan['tanggal'])); ?>
                                            <small class="text-muted">(<?php echo $laporan['nama_karyawan'] ?: 'System'; ?>)</small>
                                        </div>
                                        <div class="timeline-content">
                                            <?php echo nl2br($laporan['keterangan']); ?>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <p class="text-center text-muted">Belum ada laporan</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Dokumentasi</h5>
                    </div>
                    <div class="card-body">
                        <?php if(mysqli_num_rows($result_dokumentasi) > 0): ?>
                            <div class="row g-3">
                                <?php while($foto = mysqli_fetch_assoc($result_dokumentasi)): ?>
                                    <div class="col-12">
                                        <div class="card">
                                            <img src="../<?php echo $foto['url_file']; ?>" class="card-img-top" alt="Dokumentasi">
                                            <div class="card-body">
                                                <small class="text-muted">
                                                    Petugas: <?php echo $foto['nama_karyawan'] ?: 'System'; ?><br>
                                                    Upload: <?php echo date('d/m/Y H:i', strtotime($foto['created_at'])); ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-center text-muted">Belum ada foto dokumentasi</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Fungsi untuk memperbesar gambar saat diklik
    document.querySelectorAll('.card-img-top').forEach(image => {
        image.onclick = function() {
            const modal = document.createElement('div');
            modal.style.position = 'fixed';
            modal.style.top = '0';
            modal.style.left = '0';
            modal.style.width = '100%';
            modal.style.height = '100%';
            modal.style.backgroundColor = 'rgba(0,0,0,0.9)';
            modal.style.display = 'flex';
            modal.style.alignItems = 'center';
            modal.style.justifyContent = 'center';
            modal.style.zIndex = '1000';
            
            const modalImg = document.createElement('img');
            modalImg.src = this.src;
            modalImg.style.maxHeight = '90%';
            modalImg.style.maxWidth = '90%';
            modalImg.style.objectFit = 'contain';
            
            modal.appendChild(modalImg);
            document.body.appendChild(modal);
            
            modal.onclick = function() {
                document.body.removeChild(modal);
            }
        }
    });
    </script>
</body>
</html>