<?php
session_start();
require_once '../includes/config.php';

if(isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = clean($_POST['username']);
    $password = $_POST['password'];
    
    $query = "SELECT * FROM users WHERE username = ? AND status = 'aktif'";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if($user = mysqli_fetch_assoc($result)) {
        if(password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['user_nama'] = $user['nama_lengkap'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Password salah";
        }
    } else {
        $error = "Username tidak ditemukan atau akun nonaktif";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login User - Pawspace.id</title>
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
                    <p class="text-muted">Login User</p>
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

                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text border-0 bg-transparent">
                                <i class="fas fa-lock text-muted"></i>
                            </span>
                            <input type="password" class="form-control ps-0 border-start-0" 
                                   name="password" required placeholder="Masukkan password">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        <i class="fas fa-sign-in-alt me-2"></i>Login
                    </button>
                    
                    <div class="divider">
                        <span>atau</span>
                    </div>
                    
                    <div class="text-center">
                        <p class="mb-3">Belum punya akun? 
                            <a href="register.php" class="text-primary fw-bold">Daftar disini</a>
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