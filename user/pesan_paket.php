<?php
session_start();

require_once '../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: daftar_paket.php");
    exit;
}

$id_paket = clean($_GET['id']);

// Ambil detail paket
$query = "SELECT * FROM paket WHERE id_paket = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $id_paket);
mysqli_stmt_execute($stmt);
$paket = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$paket) {
    header("Location: daftar_paket.php");
    exit;
}

// Ambil daftar kategori
$query_kategori = "SELECT * FROM kategori_hewan WHERE status = 'aktif'";
$result_kategori = mysqli_query($conn, $query_kategori);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Paket</title>
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
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Pesan Paket Penitipan</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h5><?php echo $paket['nama_paket']; ?></h5>
                            <p class="mb-0">Harga: Rp <?php echo number_format($paket['harga'], 0, ',', '.'); ?> /hari</p>
                            <p class="mb-0"><?php echo $paket['deskripsi']; ?></p>
                        </div>

                        <form action="proses_pesanan.php" method="POST">
                            <input type="hidden" name="id_paket" value="<?php echo $paket['id_paket']; ?>">
                            
                            <!-- Kategori Hewan -->
                            <div class="mb-3">
                                <label class="form-label">Kategori Hewan</label>
                                <select class="form-select" name="id_kategori" required>
                                    <option value="">Pilih Kategori Hewan</option>
                                    <?php while($kategori = mysqli_fetch_assoc($result_kategori)): ?>
                                        <option value="<?php echo $kategori['id_kategori']; ?>">
                                            <?php echo $kategori['nama_kategori']; ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <!-- Nama Hewan -->
                            <div class="mb-3">
                                <label class="form-label">Nama Hewan</label>
                                <input type="text" class="form-control" name="nama_hewan" required>
                            </div>

                            <!-- Catatan -->
                            <div class="mb-3">
                                <label class="form-label">Catatan</label>
                                <textarea class="form-control" name="catatan" rows="3"></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" name="tgl_awal" required
                                       min="<?php echo date('Y-m-d'); ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" class="form-control" name="tgl_akhir" required
                                       min="<?php echo date('Y-m-d'); ?>">
                            </div>

                            <div id="kalkulasi" class="alert alert-primary d-none">
                                <p class="mb-1">Jumlah Hari: <span id="jumlah_hari">0</span></p>
                                <p class="mb-0">Total Biaya: Rp <span id="total_biaya">0</span></p>
                            </div>

                            <input type="hidden" name="total_harga" id="input_total_harga" value="0">

                            <div class="d-flex justify-content-between">
                                <a href="daftar_paket.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-shopping-cart"></i> Pesan Sekarang
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const tglAwal = document.querySelector('input[name="tgl_awal"]');
        const tglAkhir = document.querySelector('input[name="tgl_akhir"]');
        const kalkulasi = document.getElementById('kalkulasi');
        const jumlahHari = document.getElementById('jumlah_hari');
        const totalBiaya = document.getElementById('total_biaya');
        const inputTotalHarga = document.getElementById('input_total_harga');
        const hargaPerHari = <?php echo $paket['harga']; ?>;

        function hitungTotal() {
            if(tglAwal.value && tglAkhir.value) {
                const start = new Date(tglAwal.value);
                const end = new Date(tglAkhir.value);
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                const total = diffDays * hargaPerHari;
                
                jumlahHari.textContent = diffDays;
                totalBiaya.textContent = new Intl.NumberFormat('id-ID').format(total);
                inputTotalHarga.value = total;
                kalkulasi.classList.remove('d-none');
            }
        }

        tglAwal.addEventListener('change', function() {
            tglAkhir.min = this.value;
            hitungTotal();
        });

        tglAkhir.addEventListener('change', function() {
            if(this.value < tglAwal.value) {
                alert('Tanggal selesai tidak boleh lebih awal dari tanggal mulai');
                this.value = tglAwal.value;
            }
            hitungTotal();
        });
    });
    </script>
</body>
</html>