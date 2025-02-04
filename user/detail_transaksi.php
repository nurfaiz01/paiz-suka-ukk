<?php
session_start();
require_once '../includes/config.php';

// Cek login user
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Cek parameter kd_tr
if (!isset($_GET['kd']) || empty($_GET['kd'])) {
    header("Location: transaksi.php");
    exit;
}

$kd_tr = clean($_GET['kd']);
$user_id = $_SESSION['user_id'];

// Ambil data transaksi dengan info karyawan dan kategori hewan
$query = "SELECT t.*, p.nama_paket, p.harga,
          k.nama_lengkap as nama_karyawan, k.no_telp as telp_karyawan, k.email as email_karyawan,
          kh.nama_kategori, kh.deskripsi as deskripsi_kategori 
          FROM transaksi t 
          JOIN paket p ON t.id_paket = p.id_paket 
          LEFT JOIN karyawan k ON t.id_karyawan = k.id_karyawan
          LEFT JOIN kategori_hewan kh ON t.id_kategori = kh.id_kategori
          WHERE t.kd_tr = ? AND t.id_user = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "si", $kd_tr, $user_id);
mysqli_stmt_execute($stmt);
$transaksi = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$transaksi) {
    header("Location: transaksi.php");
    exit;
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

// Ambil data hewan dari detail_laporan pertama
$first_report = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT * FROM detail_laporan WHERE kd_tr = '$kd_tr' ORDER BY created_at ASC LIMIT 1"));
$info_hewan = [];
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
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi - Pawspace</title>
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
        .info-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="transaksi.php">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Transaksi
            </a>
        </div>
    </nav>

    <div class="container py-4">
        <!-- Info Transaksi -->
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Detail Transaksi #<?php echo $kd_tr; ?></h5>
                        <a href="cetak_bukti.php?kd=<?php echo $kd_tr; ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-print me-2"></i>Cetak Bukti
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-card">
                                    <h6 class="text-primary mb-3">Informasi Paket</h6>
                                    <p class="mb-2">
                                        <strong><i class="fas fa-box me-2"></i>Paket:</strong><br>
                                        <?php echo $transaksi['nama_paket']; ?>
                                    </p>
                                    <p class="mb-2">
                                        <strong><i class="fas fa-calendar me-2"></i>Periode:</strong><br>
                                        <?php echo date('d/m/Y', strtotime($transaksi['tgl_awal'])); ?> - 
                                        <?php echo date('d/m/Y', strtotime($transaksi['tgl_akhir'])); ?>
                                    </p>
                                    <p class="mb-0">
                                        <strong><i class="fas fa-money-bill-wave me-2"></i>Total:</strong><br>
                                        Rp <?php echo number_format($transaksi['total_harga'], 0, ',', '.'); ?>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <h6 class="text-primary mb-3">Informasi Hewan</h6>
                                    <p class="mb-2">
                                        <strong><i class="fas fa-paw me-2"></i>Kategori:</strong><br>
                                        <?php echo $transaksi['nama_kategori']; ?>
                                    </p>
                                    <p class="mb-2">
                                        <strong><i class="fas fa-tag me-2"></i>Nama Hewan:</strong><br>
                                        <?php echo $info_hewan['nama'] ?? '-'; ?>
                                    </p>
                                    <?php if(isset($info_hewan['catatan']) && !empty($info_hewan['catatan'])): ?>
                                    <p class="mb-0">
                                        <strong><i class="fas fa-clipboard me-2"></i>Catatan:</strong><br>
                                        <?php echo $info_hewan['catatan']; ?>
                                    </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <div class="text-center mt-3">
                            <span class="badge bg-<?php 
                                echo $transaksi['status'] == 'pending' ? 'warning' : 
                                    ($transaksi['status'] == 'proses' ? 'primary' : 'success'); 
                            ?> p-2">
                                <i class="fas fa-<?php 
                                    echo $transaksi['status'] == 'pending' ? 'clock' : 
                                        ($transaksi['status'] == 'proses' ? 'spinner' : 'check'); 
                                ?> me-2"></i>
                                <?php echo ucfirst($transaksi['status']); ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Info Karyawan -->
                <?php if($transaksi['id_karyawan']): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-user-tie me-2"></i>Informasi Petugas</h5>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-1"><strong>Nama Petugas:</strong></p>
                                <p class="mb-0"><?php echo $transaksi['nama_karyawan']; ?></p>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-1"><strong>Email:</strong></p>
                                <p class="mb-0"><?php echo $transaksi['email_karyawan']; ?></p>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-1"><strong>Hubungi Petugas:</strong></p>
                                <a href="https://wa.me/<?php echo str_replace(['+', ' ', '-'], '', $transaksi['telp_karyawan']); ?>" 
                                   class="btn btn-success btn-sm" target="_blank">
                                    <i class="fab fa-whatsapp me-2"></i>WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Informasi Pembayaran -->
                <?php if($transaksi['status'] == 'pending'): ?>
                <div class="alert alert-info">
                    <h5><i class="fas fa-info-circle me-2"></i>Informasi Pembayaran</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="bg-white p-3 rounded">
                                <h6>Transfer ke:</h6>
                                <p class="mb-1">Bank BCA</p>
                                <p class="mb-1">No. Rekening: <strong>2891281775</strong></p>
                                <p class="mb-0">A/N: <strong>Abil Prima Nurfaiz</strong></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>Total Pembayaran:</h6>
                            <h3 class="text-primary">Rp <?php echo number_format($transaksi['total_harga'], 0, ',', '.'); ?></h3>
                            <a href="https://wa.me/6282257004434?text=Halo, saya ingin konfirmasi pembayaran untuk transaksi <?php echo $transaksi['kd_tr']; ?>" 
                               class="btn btn-success mt-2" target="_blank">
                                <i class="fab fa-whatsapp me-2"></i>Konfirmasi Pembayaran
                            </a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Timeline Laporan -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Laporan Harian</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <?php if(mysqli_num_rows($result_laporan) > 0): ?>
                                <?php while($laporan = mysqli_fetch_assoc($result_laporan)): ?>
                                    <div class="timeline-item">
                                        <div class="timeline-date">
                                            <?php echo date('d/m/Y', strtotime($laporan['tanggal'])); ?>
                                            <small class="text-muted">(oleh: <?php echo $laporan['nama_karyawan']; ?>)</small>
                                        </div>
                                        <div class="timeline-content">
                                            <?php echo nl2br($laporan['keterangan']); ?>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <p class="text-center text-muted py-3">Belum ada laporan harian</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dokumentasi -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-camera me-2"></i>Dokumentasi</h5>
                    </div>
                    <div class="card-body">
                        <?php if(mysqli_num_rows($result_dokumentasi) > 0): ?>
                            <div class="row g-3">
                                <?php while($foto = mysqli_fetch_assoc($result_dokumentasi)): ?>
                                    <div class="col-12">
                                        <div class="card">
                                            <img src="../<?php echo $foto['url_file']; ?>" class="card-img-top" alt="Foto">
                                            <img src="../<?php echo $foto['url_file']; ?>" class="card-img-top" alt="Dokumentasi">
                                            <div class="card-body">
                                                <small class="text-muted">
                                                    <i class="fas fa-user me-1"></i>Petugas: <?php echo $foto['nama_karyawan']; ?><br>
                                                    <i class="fas fa-clock me-1"></i>Upload: <?php echo date('d/m/Y H:i', strtotime($foto['created_at'])); ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-center text-muted py-3">Belum ada dokumentasi</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Fungsi untuk memperbesar gambar saat diklik
    document.querySelectorAll('.gallery-item img').forEach(image => {
        image.onclick = function() {
            // Buat elemen modal untuk menampilkan gambar
            let modal = document.createElement('div');
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
            
            // Buat elemen gambar dalam modal
            let modalImg = document.createElement('img');
            modalImg.src = this.src;
            modalImg.style.maxHeight = '90%';
            modalImg.style.maxWidth = '90%';
            modalImg.style.objectFit = 'contain';
            
            // Tambahkan gambar ke modal
            modal.appendChild(modalImg);
            
            // Tambahkan modal ke body
            document.body.appendChild(modal);
            
            // Tutup modal saat diklik
            modal.onclick = function() {
                document.body.removeChild(modal);
            }
        }
    });
    </script>
</body>
</html>