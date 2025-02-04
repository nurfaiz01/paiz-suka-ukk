<?php
$page_title = "Detail Transaksi";
include 'header.php';

if(!isset($_GET['kd'])) {
   header("Location: index.php");
   exit;
}

$kd_tr = clean($_GET['kd']);

// Query detail transaksi
$query = "SELECT t.*, p.nama_paket, u.nama_lengkap as nama_user, u.no_telp, u.alamat,
         k.nama_lengkap as nama_karyawan, k.no_telp as telp_karyawan 
         FROM transaksi t 
         LEFT JOIN paket p ON t.id_paket = p.id_paket 
         LEFT JOIN users u ON t.id_user = u.id_user 
         LEFT JOIN karyawan k ON t.id_karyawan = k.id_karyawan 
         WHERE t.kd_tr = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $kd_tr);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$transaksi = mysqli_fetch_assoc($result);

// Query laporan harian
$query_laporan = "SELECT dl.*, k.nama_lengkap as nama_karyawan 
                FROM detail_laporan dl
                LEFT JOIN karyawan k ON dl.id_karyawan = k.id_karyawan 
                WHERE dl.kd_tr = ? 
                ORDER BY dl.tanggal DESC";
$stmt_laporan = mysqli_prepare($conn, $query_laporan);
mysqli_stmt_bind_param($stmt_laporan, "s", $kd_tr);
mysqli_stmt_execute($stmt_laporan);
$result_laporan = mysqli_stmt_get_result($stmt_laporan);

// Query dokumentasi
$query_foto = "SELECT d.*, k.nama_lengkap as nama_karyawan 
              FROM dokumentasi d
              LEFT JOIN karyawan k ON d.id_karyawan = k.id_karyawan 
              WHERE d.kd_tr = ? 
              ORDER BY d.created_at DESC";
$stmt_foto = mysqli_prepare($conn, $query_foto);
mysqli_stmt_bind_param($stmt_foto, "s", $kd_tr);
mysqli_stmt_execute($stmt_foto);
$result_foto = mysqli_stmt_get_result($stmt_foto);

// Cek apakah transaksi ditangani oleh karyawan ini
$is_handler = isset($_SESSION['karyawan_id']) && ($transaksi['id_karyawan'] == $_SESSION['karyawan_id']);
?>

<!-- Alert Success/Error -->
<?php if(isset($_SESSION['success'])): ?>
   <div class="alert alert-success alert-dismissible fade show" role="alert">
       <?php 
       echo $_SESSION['success'];
       unset($_SESSION['success']);
       ?>
       <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
   </div>
<?php endif; ?>

<?php if(isset($_SESSION['error'])): ?>
   <div class="alert alert-danger alert-dismissible fade show" role="alert">
       <?php 
       echo $_SESSION['error'];
       unset($_SESSION['error']);
       ?>
       <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
   </div>
<?php endif; ?>

<!-- Detail Transaksi -->
<div class="card mb-4">
   <div class="card-header d-flex justify-content-between align-items-center">
       <h5 class="card-title mb-0">
           Detail Transaksi 
           <span class="badge bg-<?php 
               echo $transaksi['status'] == 'pending' ? 'warning' : 
                   ($transaksi['status'] == 'proses' ? 'info' : 'success'); 
               ?>">
               <?php echo ucfirst($transaksi['status']); ?>
           </span>
       </h5>
       <?php if($is_handler && $transaksi['status'] == 'proses'): ?>
           <a href="selesai_tugas.php?kd=<?= $kd_tr ?>" 
              class="btn btn-success"
              onclick="return confirm('Yakin ingin menyelesaikan tugas ini?')">
               <i class="fas fa-check-circle me-2"></i>Selesaikan Tugas
           </a>
       <?php endif; ?>
   </div>
   <div class="card-body">
       <div class="row">
           <div class="col-md-4">
               <h6 class="mb-3">Informasi Transaksi</h6>
               <table class="table table-sm">
                   <tr>
                       <td width="150"><strong>Kode Transaksi</strong></td>
                       <td><?= $transaksi['kd_tr'] ?></td>
                   </tr>
                   <tr>
                       <td><strong>Paket</strong></td>
                       <td><?= $transaksi['nama_paket'] ?></td>
                   </tr>
                   <tr>
                       <td><strong>Total</strong></td>
                       <td>Rp <?= number_format($transaksi['total_harga'],0,',','.') ?></td>
                   </tr>
                   <tr>
                       <td><strong>Status</strong></td>
                       <td>
                           <span class="badge bg-<?php 
                               echo $transaksi['status'] == 'pending' ? 'warning' : 
                                   ($transaksi['status'] == 'proses' ? 'info' : 'success'); 
                               ?>">
                               <?php echo ucfirst($transaksi['status']); ?>
                           </span>
                       </td>
                   </tr>
               </table>
           </div>
           <div class="col-md-4">
               <h6 class="mb-3">Informasi Pemilik</h6>
               <table class="table table-sm">
                   <tr>
                       <td width="150"><strong>Nama</strong></td>
                       <td><?= $transaksi['nama_user'] ?></td>
                   </tr>
                   <tr>
                       <td><strong>No. Telp</strong></td>
                       <td>
                           <a href="https://wa.me/<?= str_replace(['+', ' ', '-'], '', $transaksi['no_telp']) ?>" 
                              class="btn btn-success btn-sm" target="_blank">
                               <i class="fab fa-whatsapp"></i> <?= $transaksi['no_telp'] ?>
                           </a>
                       </td>
                   </tr>
                   <tr>
                       <td><strong>Alamat</strong></td>
                       <td><?= $transaksi['alamat'] ?></td>
                   </tr>
                   <tr>
                       <td><strong>Periode</strong></td>
                       <td><?= formatTanggal($transaksi['tgl_awal']) ?> s/d <?= formatTanggal($transaksi['tgl_akhir']) ?></td>
                   </tr>
               </table>
           </div>
           <div class="col-md-4">
               <h6 class="mb-3">Informasi Petugas</h6>
               <?php if($transaksi['id_karyawan']): ?>
                   <table class="table table-sm">
                       <tr>
                           <td width="150"><strong>Nama Petugas</strong></td>
                           <td><?= $transaksi['nama_karyawan'] ?></td>
                       </tr>
                       <tr>
                           <td><strong>No. Telp</strong></td>
                           <td>
                               <a href="https://wa.me/<?= str_replace(['+', ' ', '-'], '', $transaksi['telp_karyawan']) ?>" 
                                  class="btn btn-success btn-sm" target="_blank">
                                   <i class="fab fa-whatsapp"></i> <?= $transaksi['telp_karyawan'] ?>
                               </a>
                           </td>
                       </tr>
                       <tr>
                           <td><strong>Status</strong></td>
                           <td>
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
           </div>
       </div>
   </div>
</div>

<!-- Laporan Harian -->
<div class="card mb-4">
   <div class="card-header d-flex justify-content-between align-items-center">
       <h5 class="card-title mb-0">
           <i class="fas fa-clipboard me-2"></i>Laporan Harian
       </h5>
       <?php if($is_handler && $transaksi['status'] == 'proses'): ?>
           <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalLaporan">
               <i class="fas fa-plus me-2"></i>Tambah Laporan
           </button>
       <?php endif; ?>
   </div>
   <div class="card-body">
       <div class="table-responsive">
           <table class="table table-bordered table-hover">
               <thead>
                   <tr>
                       <th>Tanggal</th>
                       <th>Keterangan</th>
                       <th>Petugas</th>
                       <th>Waktu Input</th>
                   </tr>
               </thead>
               <tbody>
                   <?php if(mysqli_num_rows($result_laporan) > 0): ?>
                       <?php while($laporan = mysqli_fetch_assoc($result_laporan)): ?>
                       <tr>
                           <td><?= formatTanggal($laporan['tanggal']) ?></td>
                           <td><?= $laporan['keterangan'] ?></td>
                           <td><?= $laporan['nama_karyawan'] ?></td>
                           <td><?= date('d/m/Y H:i', strtotime($laporan['created_at'])) ?></td>
                       </tr>
                       <?php endwhile; ?>
                   <?php else: ?>
                       <tr>
                           <td colspan="4" class="text-center">Belum ada laporan</td>
                       </tr>
                   <?php endif; ?>
               </tbody>
           </table>
       </div>
   </div>
</div>

<!-- Dokumentasi -->
<div class="card">
   <div class="card-header d-flex justify-content-between align-items-center">
       <h5 class="card-title mb-0">
           <i class="fas fa-camera me-2"></i>Dokumentasi
       </h5>
       <?php if($is_handler && $transaksi['status'] == 'proses'): ?>
           <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalFoto">
               <i class="fas fa-plus me-2"></i>Tambah Foto
           </button>
       <?php endif; ?>
   </div>
   <div class="card-body">
       <?php if(mysqli_num_rows($result_foto) > 0): ?>
           <div class="row">
               <?php while($foto = mysqli_fetch_assoc($result_foto)): ?>
               <div class="col-md-3 mb-3">
                   <div class="card">
                       <img src="../<?= $foto['url_file'] ?>" class="card-img-top" alt="Dokumentasi"
                            style="height: 200px; object-fit: cover;">
                       <div class="card-body">
                           <p class="card-text">
                               <small class="text-muted">
                                   Petugas: <?= $foto['nama_karyawan'] ?><br>
                                   Upload: <?= date('d/m/Y H:i', strtotime($foto['created_at'])) ?>
                               </small>
                           </p>
                       </div>
                   </div>
               </div>
               <?php endwhile; ?>
           </div>
       <?php else: ?>
           <p class="text-center text-muted">Belum ada dokumentasi</p>
       <?php endif; ?>
   </div>
</div>

<!-- Modal Tambah Laporan -->
<div class="modal fade" id="modalLaporan">
   <div class="modal-dialog">
       <div class="modal-content">
           <form action="tambah_laporan.php" method="POST">
               <input type="hidden" name="kd_tr" value="<?= $kd_tr ?>">
               <div class="modal-header">
                   <h5 class="modal-title">Tambah Laporan</h5>
                   <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
               </div>
               <div class="modal-body">
                   <div class="mb-3">
                       <label class="form-label">Tanggal</label>
                       <input type="date" class="form-control" name="tanggal" required
                              value="<?= date('Y-m-d') ?>"
                              min="<?= $transaksi['tgl_awal'] ?>" 
                              max="<?= $transaksi['tgl_akhir'] ?>">
                   </div>
                   <div class="mb-3">
                       <label class="form-label">Keterangan</label>
                       <textarea class="form-control" name="keterangan" rows="3" required></textarea>
                   </div>
               </div>
               <div class="modal-footer">
                   <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                   <button type="submit" class="btn btn-primary">
                       <i class="fas fa-save me-2"></i>Simpan
                   </button>
               </div>
           </form>
       </div>
   </div>
</div>

<!-- Modal Tambah Foto -->
<div class="modal fade" id="modalFoto">
   <div class="modal-dialog">
       <div class="modal-content">
           <form action="tambah_dokumentasi.php" method="POST" enctype="multipart/form-data">
               <input type="hidden" name="kd_tr" value="<?= $kd_tr ?>">
               <div class="modal-header">
                   <h5 class="modal-title">Tambah Dokumentasi</h5>
                   <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
               </div>
               <div class="modal-body">
                   <div class="mb-3">
                       <label class="form-label">Pilih Foto</label>
                       <input type="file" class="form-control" name="foto" accept="image/*" required>
                       <small class="text-muted">Format: JPG, PNG, JPEG. Max: 2MB</small>
                       </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-2"></i>Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CSS Tambahan -->
<style>
    .gallery-item img {
        height: 200px;
        object-fit: cover;
        border-radius: 6px;
        transition: transform 0.3s;
    }
    
    .gallery-item img:hover {
        transform: scale(1.02);
    }

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

    .btn-whatsapp {
        background: #25D366;
        color: white;
    }

    .btn-whatsapp:hover {
        background: #128C7E;
        color: white;
    }
</style>

<!-- Script Tambahan -->
<script>
// Tutup alert otomatis setelah 3 detik
window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function() {
        $(this).remove();
    });
}, 3000);

// Inisialisasi tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
});
</script>

<?php include 'footer.php'; ?>