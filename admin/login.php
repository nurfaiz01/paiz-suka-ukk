<?php
session_start();
require_once '../includes/config.php';

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit;
}

// Proses login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = clean($_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM admin WHERE username = ? AND status = 'aktif'";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($admin = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id_admin'];
            $_SESSION['admin_nama'] = $admin['nama_lengkap'];

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
    <title>Login Admin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }

        .login-container {
            max-width: 400px;
            width: 100%;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            padding: 30px;
            animation: fadeInDown 1s ease-in-out;
        }

        .login-container h2 {
            color: #333;
            font-weight: 700;
            margin-bottom: 20px;
            text-align: center;
        }

        .form-control {
            border-radius: 10px;
            padding: 10px 15px;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 10px rgba(102, 126, 234, 0.5);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 10px;
            padding: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .alert {
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .back-link {
            color: #667eea;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: #764ba2;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="login-container animate__animated animate__fadeInDown">
        <h2>Login Admin</h2>

        <?php if (isset($error)) : ?>
            <div class="alert alert-danger animate__animated animate__shakeX">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">
                <i class="fas fa-sign-in-alt me-2"></i> Login
            </button>

            <div class="text-center">
                <a href="../index.php" class="back-link">
                    <i class="fas fa-arrow-left me-2"></i> Kembali ke Beranda
                </a>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>