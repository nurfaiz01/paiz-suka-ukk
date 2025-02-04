<?php
session_start();
require_once '../includes/config.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Ambil data user
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id_user = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$user_data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

// Proses update profil
if(isset($_POST['update_profile'])) {
    $nama_lengkap = clean($_POST['nama_lengkap']);
    $email = clean($_POST['email']);
    $no_telp = clean($_POST['no_telp']);
    $alamat = clean($_POST['alamat']);

    $update_query = "UPDATE users SET nama_lengkap = ?, email = ?, no_telp = ?, alamat = ? WHERE id_user = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "ssssi", $nama_lengkap, $email, $no_telp, $alamat, $user_id);
    
    if(mysqli_stmt_execute($stmt)) {
        $_SESSION['user_nama'] = $nama_lengkap;
        $success = "Data profil berhasil diperbarui!";
        
        // Refresh user data
        $query_user = "SELECT * FROM users WHERE id_user = ?";
        $stmt_user = mysqli_prepare($conn, $query_user);
        mysqli_stmt_bind_param($stmt_user, "i", $user_id);
        mysqli_stmt_execute($stmt_user);
        $user_data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_user));
    } else {
        $error = "Gagal memperbarui data profil.";
    }
}

// Proses update password
if(isset($_POST['update_password'])) {
    $password_lama = $_POST['password_lama'];
    $password_baru = $_POST['password_baru'];
    $konfirmasi_password = $_POST['konfirmasi_password'];

    if(password_verify($password_lama, $user_data['password'])) {
        if($password_baru === $konfirmasi_password) {
            $hashed_password = password_hash($password_baru, PASSWORD_DEFAULT);
            $update_query = "UPDATE users SET password = ? WHERE id_user = ?";
            $stmt = mysqli_prepare($conn, $update_query);
            mysqli_stmt_bind_param($stmt, "si", $hashed_password, $user_id);
            
            if(mysqli_stmt_execute($stmt)) {
                $success = "Password berhasil diperbarui!";
            } else {
                $error = "Gagal memperbarui password.";
            }
        } else {
            $error = "Password baru dan konfirmasi tidak cocok.";
        }
    } else {
        $error = "Password lama tidak sesuai.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Pawspace.id</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .card {
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,.05);
            border-radius: 10px;
            border: none;
        }
        .nav-pills .nav-link {
            color: #333;
        }
        .nav-pills .nav-link.active {
            background-color: #0d6efd;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">
                <i class="fas fa-paw"></i> Pawspace.id
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="daftar_paket.php">
                            <i class="fas fa-box"></i> Paket
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="transaksi.php">
                            <i class="fas fa-list"></i> Transaksi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="profil.php">
                            <i class="fas fa-user"></i> Profil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="fas fa-sign-out-alt"></i> Keluar
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <?php if(isset($success)): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                            </div>
                        <?php endif; ?>

                        <?php if(isset($error)): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Tab Navigation -->
                        <ul class="nav nav-pills mb-4" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#data-profil">
                                    <i class="fas fa-user me-2"></i>Data Profil
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#ubah-password">
                                    <i class="fas fa-lock me-2"></i>Ubah Password
                                </button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content">
                            <!-- Data Profil Tab -->
                            <div class="tab-pane fade show active" id="data-profil">
                                <form method="post" action="">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control" name="nama_lengkap" 
                                               value="<?php echo $user_data['nama_lengkap']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" 
                                               value="<?php echo $user_data['email']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">No. Telepon</label>
                                        <input type="tel" class="form-control" name="no_telp" 
                                               value="<?php echo $user_data['no_telp']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Alamat</label>
                                        <textarea class="form-control" name="alamat" rows="3" 
                                                  required><?php echo $user_data['alamat']; ?></textarea>
                                    </div>
                                    <button type="submit" name="update_profile" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                                    </button>
                                </form>
                            </div>

                            <!-- Ubah Password Tab -->
                            <div class="tab-pane fade" id="ubah-password">
                                <form method="post" action="">
                                    <div class="mb-3">
                                        <label class="form-label">Password Lama</label>
                                        <input type="password" class="form-control" name="password_lama" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Password Baru</label>
                                        <input type="password" class="form-control" name="password_baru" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Konfirmasi Password Baru</label>
                                        <input type="password" class="form-control" name="konfirmasi_password" required>
                                    </div>
                                    <button type="submit" name="update_password" class="btn btn-primary">
                                        <i class="fas fa-key me-2"></i>Ubah Password
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>