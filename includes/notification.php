<?php

function getAdminNotifications($conn) {
    // Hitung transaksi pending
    $query = "SELECT COUNT(*) as total FROM transaksi WHERE status = 'pending'";
    $result = mysqli_query($conn, $query);
    $pending = mysqli_fetch_assoc($result)['total'];
    
    // Hitung transaksi proses
    $query = "SELECT COUNT(*) as total FROM transaksi WHERE status = 'proses'";
    $result = mysqli_query($conn, $query);
    $proses = mysqli_fetch_assoc($result)['total'];
    
    return [
        'pending' => $pending,
        'proses' => $proses
    ];
}
?>

<!-- Tambahkan kode ini di navbar admin -->
<?php
$notifications = getAdminNotifications($conn);
?>
<li class="nav-item">
    <a class="nav-link" href="transaksi.php?status=pending">
        <i class="fas fa-bell"></i>
        <?php if($notifications['pending'] > 0): ?>
            <span class="badge bg-warning"><?php echo $notifications['pending']; ?></span>
        <?php endif; ?>
    </a>
</li>