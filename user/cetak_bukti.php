<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['kd']) || empty($_GET['kd'])) {
    header("Location: dashboard.php");
    exit;
}

$kd_tr = clean($_GET['kd']);
$id_user = $_SESSION['user_id'];

// Ambil data transaksi
$query = "SELECT t.*, p.nama_paket, p.harga, u.nama_lengkap, u.no_telp, u.alamat 
          FROM transaksi t 
          JOIN paket p ON t.id_paket = p.id_paket 
          JOIN users u ON t.id_user = u.id_user
          WHERE t.kd_tr = ? AND t.id_user = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "si", $kd_tr, $id_user);
mysqli_stmt_execute($stmt);
$transaksi = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$transaksi) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Transaksi #<?php echo $kd_tr; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-row {
            margin-bottom: 10px;
        }
        .label {
            font-weight: bold;
            width: 150px;
            display: inline-block;
        }
        .total {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #000;
            text-align: right;
            font-weight: bold;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: bold;
        }
        .status-pending { background: #ffc107; color: #000; }
        .status-proses { background: #0d6efd; color: #fff; }
        .status-selesai { background: #198754; color: #fff; }
    </style>
</head>
<body>
    <div class="header">
        <h2>PENITIPAN HEWAN</h2>
        <p>Jl. Contoh No. 123, Kota</p>
        <p>Telp: 0812-3456-7890 | Email: info@penitipanhewan.com</p>
    </div>

    <h3>BUKTI TRANSAKSI</h3>

    <div class="info-section">
        <div class="info-row">
            <span class="label">Kode Transaksi</span>: <?php echo $transaksi['kd_tr']; ?>
        </div>
        <div class="info-row">
            <span class="label">Tanggal</span>: <?php echo date('d/m/Y', strtotime($transaksi['tgl_transaksi'])); ?>
        </div>
        <div class="info-row">
            <span class="label">Status</span>: 
            <span class="status-badge status-<?php echo $transaksi['status']; ?>">
                <?php echo ucfirst($transaksi['status']); ?>
            </span>
        </div>
    </div>

    <div class="info-section">
        <h4>Data Pemesan:</h4>
        <div class="info-row">
            <span class="label">Nama</span>: <?php echo $transaksi['nama_lengkap']; ?>
        </div>
        <div class="info-row">
            <span class="label">No. Telepon</span>: <?php echo $transaksi['no_telp']; ?>
        </div>
        <div class="info-row">
            <span class="label">Alamat</span>: <?php echo $transaksi['alamat']; ?>
        </div>
    </div>

    <div class="info-section">
        <h4>Detail Penitipan:</h4>
        <div class="info-row">
            <span class="label">Paket</span>: <?php echo $transaksi['nama_paket']; ?>
        </div>
        <div class="info-row">
            <span class="label">Tanggal Mulai</span>: <?php echo date('d/m/Y', strtotime($transaksi['tgl_awal'])); ?>
        </div>
        <div class="info-row">
            <span class="label">Tanggal Selesai</span>: <?php echo date('d/m/Y', strtotime($transaksi['tgl_akhir'])); ?>
        </div>
        <div class="info-row">
            <span class="label">Harga per Hari</span>: Rp <?php echo number_format($transaksi['harga'], 0, ',', '.'); ?>
        </div>
    </div>

    <div class="total">
        Total Pembayaran: Rp <?php echo number_format($transaksi['total_harga'], 0, ',', '.'); ?>
    </div>

    <div class="info-section" style="margin-top: 50px; text-align: right;">
        <p>
            <?php echo date('d F Y'); ?><br>
            Penitipan Hewan<br><br><br><br>
            (_________________)
        </p>
    </div>

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">Cetak Bukti</button>
        <button onclick="window.history.back()" style="padding: 10px 20px; cursor: pointer;">Kembali</button>
    </div>
</body>
</html>