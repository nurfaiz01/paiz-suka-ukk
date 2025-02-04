<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Ambil data admin
$id_admin = $_SESSION['admin_id'];
$query = "SELECT * FROM admin WHERE id_admin = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id_admin);
mysqli_stmt_execute($stmt);
$admin = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Admin</title>
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
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Profil Admin</h5>
                    </div>
                    
                    <?php include 'flash_massage.php'; ?>
                    
                    <div class="card-body">
                        <form action="update_profil.php" method="post">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($admin['username']); ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" name="nama_lengkap" 
                                       value="<?php echo htmlspecialchars($admin['nama_lengkap']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" 
                                       value="<?php echo htmlspecialchars($admin['email']); ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Profil</button>
                        </form>

                        <hr class="my-4">

                        <h5>Ganti Password</h5>
                        <form action="ganti_password.php" method="post">
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
                            <button type="submit" class="btn btn-warning">Ganti Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
