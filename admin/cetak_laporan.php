<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Filter tanggal
$tgl_awal = isset($_GET['tgl_awal']) ? $_GET['tgl_awal'] : date('Y-m-01');
$tgl_akhir = isset($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : date('Y-m-t');

// Query untuk laporan
$query = "SELECT t.*, p.nama_paket, a.nama_lengkap as admin_nama
          FROM transaksi t 
          JOIN paket p ON t.id_paket = p.id_paket
          JOIN admin a ON t.id_admin = a.id_admin
          WHERE t.tgl_transaksi BETWEEN ? AND ?
          ORDER BY t.tgl_transaksi DESC";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ss", $tgl_awal, $tgl_akhir);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Hitung total pendapatan
$query_total = "SELECT SUM(total_harga) as total FROM transaksi 
                WHERE tgl_transaksi BETWEEN ? AND ? AND status = 'selesai'";
$stmt_total = mysqli_prepare($conn, $query_total);
mysqli_stmt_bind_param($stmt_total, "ss", $tgl_awal, $tgl_akhir);
mysqli_stmt_execute($stmt_total);
$total = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_total))['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .total {
            text-align: right;
            margin-top: 20px;
            font-weight: bold;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Transaksi Penitipan Hewan</h2>
        <p>Periode: <?php echo date('d/m/Y', strtotime($tgl_awal)); ?> - 
                    <?php echo date('d/m/Y', strtotime($tgl_akhir)); ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Tanggal</th>
                <th>Admin</th>
                <th>Paket</th>
                <th>Tgl Awal</th>
                <th>Tgl Akhir</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            while($row = mysqli_fetch_assoc($result)): 
            ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $row['kd_tr']; ?></td>
                    <td><?php echo date('d/m/Y', strtotime($row['tgl_transaksi'])); ?></td>
                    <td><?php echo $row['admin_nama']; ?></td>
                    <td><?php echo $row['nama_paket']; ?></td>
                    <td><?php echo date('d/m/Y', strtotime($row['tgl_awal'])); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($row['tgl_akhir'])); ?></td>
                    <td>Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                    <td><?php echo ucfirst($row['status']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="total">
        Total Pendapatan: Rp <?php echo number_format($total, 0, ',', '.'); ?>
    </div>

    <div class="no-print" style="margin-top: 20px;">
        <button onclick="window.print()">Cetak Laporan</button>
        <button onclick="window.history.back()">Kembali</button>
    </div>
</body>
</html>