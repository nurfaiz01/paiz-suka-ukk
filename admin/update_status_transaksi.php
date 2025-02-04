<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">Update Status Transaksi</h5>
    </div>
    <div class="card-body">
        <form action="update_status_transaksi.php" method="post" class="row align-items-end">
            <input type="hidden" name="kd_tr" value="<?php echo $kd_tr; ?>">
            <div class="col-md-8">
                <label class="form-label">Status</label>
                <select class="form-select" name="status" required>
                    <option value="pending" <?php echo $transaksi['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="proses" <?php echo $transaksi['status'] == 'proses' ? 'selected' : ''; ?>>Proses</option>
                    <option value="selesai" <?php echo $transaksi['status'] == 'selesai' ? 'selected' : ''; ?>>Selesai</option>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-save"></i> Update Status
                </button>
            </div>
        </form>
    </div>
</div>