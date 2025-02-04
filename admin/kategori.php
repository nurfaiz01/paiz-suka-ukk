<?php
session_start();
require_once '../includes/config.php';

// Cek login admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Proses tambah kategori
if(isset($_POST['tambah'])) {
    $nama_kategori = clean($_POST['nama_kategori']);
    $deskripsi = clean($_POST['deskripsi']);
    
    $query = "INSERT INTO kategori_hewan (nama_kategori, deskripsi) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $nama_kategori, $deskripsi);
    
    if(mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Kategori berhasil ditambahkan";
    } else {
        $_SESSION['error'] = "Gagal menambahkan kategori";
    }
    header("Location: kategori.php");
    exit;
}

// Proses edit kategori
if(isset($_POST['edit'])) {
    $id_kategori = clean($_POST['id_kategori']);
    $nama_kategori = clean($_POST['nama_kategori']);
    $deskripsi = clean($_POST['deskripsi']);
    $status = clean($_POST['status']);
    
    $query = "UPDATE kategori_hewan SET nama_kategori = ?, deskripsi = ?, status = ? WHERE id_kategori = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssi", $nama_kategori, $deskripsi, $status, $id_kategori);
    
    if(mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Kategori berhasil diupdate";
    } else {
        $_SESSION['error'] = "Gagal mengupdate kategori";
    }
    header("Location: kategori.php");
    exit;
}

// Proses hapus kategori
if(isset($_GET['delete'])) {
    $id_kategori = clean($_GET['delete']);
    
    // Cek apakah kategori masih digunakan di transaksi
    $query_check = "SELECT COUNT(*) as total FROM transaksi WHERE id_kategori = ?";
    $stmt = mysqli_prepare($conn, $query_check);
    mysqli_stmt_bind_param($stmt, "i", $id_kategori);
    mysqli_stmt_execute($stmt);
    $result = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    
    if($result['total'] > 0) {
        $_SESSION['error'] = "Kategori tidak dapat dihapus karena masih digunakan dalam transaksi";
    } else {
        $query = "DELETE FROM kategori_hewan WHERE id_kategori = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id_kategori);
        
        if(mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "Kategori berhasil dihapus";
        } else {
            $_SESSION['error'] = "Gagal menghapus kategori";
        }
    }
    header("Location: kategori.php");
    exit;
}

// Ambil data kategori
$query = "SELECT * FROM kategori_hewan ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kategori Hewan - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin-style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-paw me-2"></i>Admin Panel
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">
                            <i class="fas fa-home me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="paket.php">
                            <i class="fas fa-box me-1"></i> Kelola Paket
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="kategori.php">
                            <i class="fas fa-paw me-1"></i> Kategori Hewan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="karyawan.php">
                            <i class="fas fa-user-tie me-1"></i> Data Karyawan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profil.php">
                            <i class="fas fa-user me-1"></i> Profil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger ms-2" href="logout.php">
                            <i class="fas fa-sign-out-alt me-1"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="container mt-4">
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

        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-paw me-2"></i>Kelola Kategori Hewan
                </h5>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">
                    <i class="fas fa-plus-circle me-2"></i>Tambah Kategori
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Kategori</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                                <th>Tanggal Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            while($row = mysqli_fetch_assoc($result)): 
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($row['nama_kategori']); ?></td>
                                    <td><?php echo htmlspecialchars($row['deskripsi']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $row['status'] == 'aktif' ? 'success' : 'danger'; ?>">
                                            <?php echo ucfirst($row['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal<?php echo $row['id_kategori']; ?>">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <a href="kategori.php?delete=<?php echo $row['id_kategori']; ?>" 
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Yakin ingin menghapus kategori ini?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
                                    </td>
                                </tr>

                                <!-- Modal Edit -->
                                <div class="modal fade" id="editModal<?php echo $row['id_kategori']; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Kategori</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="kategori.php" method="post">
                                                <input type="hidden" name="id_kategori" value="<?php echo $row['id_kategori']; ?>">
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nama Kategori</label>
                                                        <input type="text" class="form-control" name="nama_kategori" 
                                                               value="<?php echo htmlspecialchars($row['nama_kategori']); ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Deskripsi</label>
                                                        <textarea class="form-control" name="deskripsi" rows="3"><?php echo htmlspecialchars($row['deskripsi']); ?></textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Status</label>
                                                        <select class="form-select" name="status" required>
                                                            <option value="aktif" <?php echo $row['status'] == 'aktif' ? 'selected' : ''; ?>>Aktif</option>
                                                            <option value="nonaktif" <?php echo $row['status'] == 'nonaktif' ? 'selected' : ''; ?>>Non Aktif</option>
                                                        </select>
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
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="tambahModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="kategori.php" method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" name="nama_kategori" required>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Tutup alert otomatis setelah 3 detik
    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function() {
            $(this).remove();
        });
    }, 3000);
    </script>
</body>
</html>