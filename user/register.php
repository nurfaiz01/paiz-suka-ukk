<?php
session_start();
require_once '../includes/config.php';

if(isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = clean($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nama_lengkap = clean($_POST['nama_lengkap']);
    $email = clean($_POST['email']);
    $no_telp = clean($_POST['no_telp']);
    $alamat = clean($_POST['alamat']);

    // Cek username
    $check = mysqli_query($conn, "SELECT id_user FROM users WHERE username = '$username'");
    if(mysqli_num_rows($check) > 0) {
        $error = "Username sudah digunakan";
    } else {
        // Cek email
        $check = mysqli_query($conn, "SELECT id_user FROM users WHERE email = '$email'");
        if(mysqli_num_rows($check) > 0) {
            $error = "Email sudah digunakan";
        } else {
            $query = "INSERT INTO users (username, password, nama_lengkap, email, no_telp, alamat) 
                      VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ssssss", $username, $password, $nama_lengkap, $email, $no_telp, $alamat);
            
            if(mysqli_stmt_execute($stmt)) {
                $_SESSION['success'] = "Registrasi berhasil! Silakan login.";
                header("Location: login.php");
                exit;
            } else {
                $error = "Gagal melakukan registrasi";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register User - Pawspace.id</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../assets/images/hero-bg.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>
    <div class="login-container">
        <div class="card login-card" data-aos="fade-up">
            <div class="card-body p-4">
                <div class="brand-logo">
                    <i class="fas fa-paw"></i>
                    <div class="brand-name">Pawspace.id</div>
                    <p class="text-muted">Register User</p>
                </div>
                
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text border-0 bg-transparent">
                                <i class="fas fa-user text-muted"></i>
                            </span>
                            <input type="text" class="form-control ps-0 border-start-0" 
                                   name="username" required placeholder="Masukkan username">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text border-0 bg-transparent">
                                <i class="fas fa-lock text-muted"></i>
                            </span>
                            <input type="password" class="form-control ps-0 border-start-0" 
                                   name="password" required placeholder="Masukkan password">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <div class="input-group">
                            <span class="input-group-text border-0 bg-transparent">
                                <i class="fas fa-user-circle text-muted"></i>
                            </span>
                            <input type="text" class="form-control ps-0 border-start-0" 
                                   name="nama_lengkap" required placeholder="Masukkan nama lengkap">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text border-0 bg-transparent">
                                <i class="fas fa-envelope text-muted"></i>
                            </span>
                            <input type="email" class="form-control ps-0 border-start-0" 
                                   name="email" required placeholder="Masukkan email">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">No. Telepon</label>
                        <div class="input-group">
                            <span class="input-group-text border-0 bg-transparent">
                                <i class="fas fa-phone text-muted"></i>
                            </span>
                            <input type="tel" class="form-control ps-0 border-start-0" 
                                   name="no_telp" required placeholder="Masukkan no. telepon">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Alamat</label>
                        <div class="input-group">
                            <span class="input-group-text border-0 bg-transparent">
                                <i class="fas fa-home text-muted"></i>
                            </span>
                            <textarea class="form-control ps-0 border-start-0" 
                                      name="alamat" rows="3" required 
                                      placeholder="Masukkan alamat"></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        <i class="fas fa-user-plus me-2"></i>Daftar
                    </button>
                    
                    <div class="divider">
                        <span>atau</span>
                    </div>
                    
                    <div class="text-center">
                        <p class="mb-3">Sudah punya akun? 
                            <a href="login.php" class="text-primary fw-bold">Login disini</a>
                        </p>
                        <a href="../index.php" class="back-link">
                            <i class="fas fa-arrow-left"></i>
                            Kembali ke Beranda
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true
        });
    </script>
</body>
</html>