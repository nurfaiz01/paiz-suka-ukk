<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['admin_id'])) {
   header("Location: login.php");
   exit;
}

// Ambil semua data paket
$query = "SELECT * FROM paket ORDER BY id_paket ASC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Kelola Paket</title>
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
       <?php 
       if (isset($_SESSION['flash_message'])) {
           $message = $_SESSION['flash_message'];
           $type = isset($_SESSION['flash_type']) ? $_SESSION['flash_type'] : 'info';
           ?>
           <div class="alert alert-<?php echo $type; ?> alert-dismissible fade show" role="alert">
               <?php echo $message; ?>
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
           </div>
           <?php
           unset($_SESSION['flash_message']);
           unset($_SESSION['flash_type']);
       }
       ?>

       <div class="card">
           <div class="card-header d-flex justify-content-between align-items-center">
               <h5 class="mb-0">Data Paket</h5>
               <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahPaketModal">
                   + Tambah Paket
               </button>
           </div>
           <div class="card-body">
               <div class="table-responsive">
                   <table class="table table-bordered table-hover">
                       <thead>
                           <tr>
                               <th>ID Paket</th>
                               <th>Nama Paket</th>
                               <th>Harga</th>
                               <th>Deskripsi</th>
                               <th>Aksi</th>
                           </tr>
                       </thead>
                       <tbody>
                           <?php while($paket = mysqli_fetch_assoc($result)): ?>
                           <tr>
                               <td><?php echo htmlspecialchars($paket['id_paket']); ?></td>
                               <td><?php echo htmlspecialchars($paket['nama_paket']); ?></td>
                               <td>Rp <?php echo number_format($paket['harga'], 0, ',', '.'); ?></td>
                               <td><?php echo htmlspecialchars($paket['deskripsi']); ?></td>
                               <td>
                                   <button type="button" 
                                           onclick="editPaket(<?php echo htmlspecialchars(json_encode($paket), ENT_QUOTES); ?>)" 
                                           class="btn btn-warning btn-sm">
                                       <i class="fas fa-edit"></i> Edit
                                   </button>
                                   <button type="button"
                                           onclick="hapusPaket('<?php echo htmlspecialchars($paket['id_paket']); ?>')" 
                                           class="btn btn-danger btn-sm">
                                       <i class="fas fa-trash"></i> Hapus
                                   </button>
                               </td>
                           </tr>
                           <?php endwhile; ?>
                       </tbody>
                   </table>
               </div>
           </div>
       </div>
   </div>

   <!-- Modal Tambah Paket -->
   <div class="modal fade" id="tambahPaketModal" tabindex="-1">
       <div class="modal-dialog">
           <div class="modal-content">
               <div class="modal-header">
                   <h5 class="modal-title">Tambah Paket</h5>
                   <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
               </div>
               <form action="proses_paket.php" method="post">
                   <div class="modal-body">
                       <div class="mb-3">
                           <label class="form-label">ID Paket</label>
                           <input type="text" class="form-control" name="id_paket" required>
                       </div>
                       <div class="mb-3">
                           <label class="form-label">Nama Paket</label>
                           <input type="text" class="form-control" name="nama_paket" required>
                       </div>
                       <div class="mb-3">
                           <label class="form-label">Harga</label>
                           <input type="number" class="form-control" name="harga" required>
                       </div>
                       <div class="mb-3">
                           <label class="form-label">Deskripsi</label>
                           <textarea class="form-control" name="deskripsi" rows="3"></textarea>
                       </div>
                   </div>
                   <div class="modal-footer">
                       <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                       <button type="submit" name="tambah" class="btn btn-primary">Simpan</button>
                   </div>
               </form>
           </div>
       </div>
   </div>

   <!-- Modal Edit Paket -->
   <div class="modal fade" id="editPaketModal" tabindex="-1">
       <div class="modal-dialog">
           <div class="modal-content">
               <div class="modal-header">
                   <h5 class="modal-title">Edit Paket</h5>
                   <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
               </div>
               <form action="proses_paket.php" method="post">
                   <div class="modal-body">
                       <input type="hidden" name="id_paket_lama" id="edit_id_paket_lama">
                       <div class="mb-3">
                           <label class="form-label">ID Paket</label>
                           <input type="text" class="form-control" name="id_paket" id="edit_id_paket" required>
                       </div>
                       <div class="mb-3">
                           <label class="form-label">Nama Paket</label>
                           <input type="text" class="form-control" name="nama_paket" id="edit_nama_paket" required>
                       </div>
                       <div class="mb-3">
                           <label class="form-label">Harga</label>
                           <input type="number" class="form-control" name="harga" id="edit_harga" required>
                       </div>
                       <div class="mb-3">
                           <label class="form-label">Deskripsi</label>
                           <textarea class="form-control" name="deskripsi" id="edit_deskripsi" rows="3"></textarea>
                       </div>
                   </div>
                   <div class="modal-footer">
                       <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                       <button type="submit" name="edit" class="btn btn-primary">Update</button>
                   </div>
               </form>
           </div>
       </div>
   </div>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
   <script>
   function editPaket(paket) {
       document.getElementById('edit_id_paket_lama').value = paket.id_paket;
       document.getElementById('edit_id_paket').value = paket.id_paket;
       document.getElementById('edit_nama_paket').value = paket.nama_paket;
       document.getElementById('edit_harga').value = paket.harga;
       document.getElementById('edit_deskripsi').value = paket.deskripsi;
       
       const editModal = document.getElementById('editPaketModal');
       const modal = new bootstrap.Modal(editModal);
       modal.show();
   }

   function hapusPaket(id_paket) {
       if(confirm('Apakah Anda yakin ingin menghapus paket ini?')) {
           window.location.href = 'proses_paket.php?hapus=' + id_paket;
       }
   }
   </script>
</body>
</html>