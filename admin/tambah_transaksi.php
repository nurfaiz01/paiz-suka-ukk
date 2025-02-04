<?php
session_start();
require_once '../includes/config.php';

// Cek login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$page_title = "Kelola Transaksi";
include '../includes/header.php';

// Mengambil data transaksi
$query = "SELECT t.*, p.nama_paket 
          FROM transaksi t 
          JOIN paket p ON t.id_paket = p.id_paket 
          ORDER BY t.created_at DESC";
$result = mysqli_query($conn, $query);
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Kelola Transaksi</h2>
        <a href="tambah_transaksi.php" class="btn btn-primary">Tambah Transaksi</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="tabelTransaksi">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Paket</th>
                            <th>Tanggal Transaksi</th>
                            <th>Tanggal Awal</th>
                            <th>Tanggal Akhir</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo $row['kd_tr']; ?></td>
                                <td><?php echo $row['nama_paket']; ?></td>
                                <td><?php echo formatTanggal($row['tgl_transaksi']); ?></td>
                                <td><?php echo formatTanggal($row['tgl_awal']); ?></td>
                                <td><?php echo formatTanggal($row['tgl_akhir']); ?></td>
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
                                    <div class="btn-group">
                                        <a href="detail_transaksi.php?kd=<?php echo $row['kd_tr']; ?>" 
                                           class="btn btn-sm btn-info">Detail</a>
                                        <button type="button" 
                                                class="btn btn-sm btn-warning"
                                                onclick="updateStatus('<?php echo $row['kd_tr']; ?>')">
                                            Update Status
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Update Status -->
<div class="modal fade" id="updateStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Status Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formUpdateStatus">
                <div class="modal-body">
                    <input type="hidden" id="kd_tr" name="kd_tr">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="proses">Proses</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updateStatus(kd_tr) {
    document.getElementById('kd_tr').value = kd_tr;
    new bootstrap.Modal(document.getElementById('updateStatusModal')).show();
}

document.getElementById('formUpdateStatus').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('update_status.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            alert('Status berhasil diupdate');
            location.reload();
        } else {
            alert('Gagal mengupdate status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan');
    });
});
</script>

<?php include '../includes/footer.php'; ?>