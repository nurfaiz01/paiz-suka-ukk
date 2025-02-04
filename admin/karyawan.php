<?php
session_start();
require_once '../includes/config.php';

// Cek login admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Ambil data karyawan
$query = "SELECT * FROM karyawan ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

// Hitung jumlah karyawan aktif & nonaktif
$query_aktif = "SELECT COUNT(*) as total FROM karyawan WHERE status = 'aktif'";
$query_nonaktif = "SELECT COUNT(*) as total FROM karyawan WHERE status = 'nonaktif'";

$total_aktif = mysqli_fetch_assoc(mysqli_query($conn, $query_aktif))['total'];
$total_nonaktif = mysqli_fetch_assoc(mysqli_query($conn, $query_nonaktif))['total'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Karyawan - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #2a9d8f;
            --danger-color: #e63946;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }

        body {
            background-color: #f5f6fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background-color: var(--primary-color);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 600;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .stats-card {
            padding: 1.5rem;
            border-radius: 15px;
        }

        .stats-card h3 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0;
        }

        .table {
            border-radius: 10px;
            overflow: hidden;
        }

        .table thead th {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 1rem;
        }

        .btn {
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 500;
        }

        .btn-group .btn {
            border-radius: 6px;
            margin: 0 2px;
        }

        .modal-content {
            border-radius: 15px;
            border: none;
        }

        .modal-header {
            background-color: var(--primary-color);
            color: white;
            border-radius: 15px 15px 0 0;
        }

        .modal-header .btn-close {
            color: white;
        }

        .form-control {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid #dee2e6;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
        }

        .form-select {
            border-radius: 8px;
            padding: 0.75rem 1rem;
        }

        .alert {
            border-radius: 10px;
            border: none;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
            </a>
        </div>
    </nav>

    <div class="container py-4">
        <!-- Alert -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $_SESSION['success']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $_SESSION['error']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Statistik Karyawan -->
        <div class="row mb-4 g-3">
            <div class="col-md-4">
                <div class="card bg-primary text-white stats-card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Total Karyawan</h5>
                        <h3><?php echo $total_aktif + $total_nonaktif; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white stats-card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Karyawan Aktif</h5>
                        <h3><?php echo $total_aktif; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-danger text-white stats-card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Karyawan Nonaktif</h5>
                        <h3><?php echo $total_nonaktif; ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Karyawan -->
        <div class="card">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2 text-primary"></i>Data Karyawan
                </h5>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahKaryawanModal">
                    <i class="fas fa-plus me-2"></i>Tambah Karyawan
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Username</th>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>No. Telp</th>
                                <th>Alamat</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($result)):
                            ?>
                                <tr>
                                    <td class="align-middle"><?= $no++; ?></td>
                                    <td class="align-middle"><?= $row['username']; ?></td>
                                    <td class="align-middle"><?= $row['nama_lengkap']; ?></td>
                                    <td class="align-middle"><?= $row['email']; ?></td>
                                    <td class="align-middle">
                                        <a href="https://wa.me/<?= str_replace(['+', ' ', '-'], '', $row['no_telp']); ?>"
                                            class="btn btn-success btn-sm" target="_blank">
                                            <i class="fab fa-whatsapp"></i> <?= $row['no_telp']; ?>
                                        </a>
                                    </td>
                                    <td class="align-middle"><?= $row['alamat']; ?></td>
                                    <td class="align-middle">
                                        <form action="update_status_karyawan.php" method="POST" class="d-inline">
                                            <input type="hidden" name="id_karyawan" value="<?= $row['id_karyawan']; ?>">
                                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                                <option value="aktif" <?= $row['status'] == 'aktif' ? 'selected' : ''; ?>>Aktif</option>
                                                <option value="nonaktif" <?= $row['status'] == 'nonaktif' ? 'selected' : ''; ?>>Nonaktif</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="align-middle">
                                        <div class="btn-group">
                                            <a href="edit.php?id=<?= $row['id_karyawan']; ?>" class="btn btn-info btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="reset.php?id=<?= $row['id_karyawan']; ?>&username=<?= urlencode($row['username']); ?>"
                                                class="btn btn-warning btn-sm">
                                                <i class="fas fa-key"></i> Reset
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm"
                                                onclick="if(confirm('Yakin ingin menghapus karyawan ini?')) window.location.href='hapus_karyawan.php?id=<?= $row['id_karyawan']; ?>'">
                                                <i class="fas fa-trash"></i> Hapus
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

    <!-- Modal Tambah Karyawan -->
    <div class="modal fade" id="tambahKaryawanModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="tambah_karyawan.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Karyawan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" name="konfirmasi_password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama_lengkap" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" class="form-control" name="no_telp">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" name="alamat" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Reset Password -->
    <div class="modal fade" id="modalResetPassword" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="reset_password_karyawan.php" method="POST" id="formResetPassword">
                    <input type="hidden" name="id_karyawan" id="resetKaryawanId">
                    <div class="modal-header">
                        <h5 class="modal-title">Reset Password: <span id="resetKaryawanUsername"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Password Baru</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" name="konfirmasi_password" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Reset Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let modalResetPassword = null;

        document.addEventListener('DOMContentLoaded', function() {
            modalResetPassword = new bootstrap.Modal(document.getElementById('modalResetPassword'));

            // Reset form saat modal ditutup
            document.getElementById('modalResetPassword').addEventListener('hidden.bs.modal', function() {
                document.getElementById('formResetPassword').reset();
            });
        });

        function prepareResetPassword(id, username) {
            document.getElementById('resetKaryawanId').value = id;
            document.getElementById('resetKaryawanUsername').textContent = username;
            modalResetPassword.show();
        }
    </script>
</body>

</html>