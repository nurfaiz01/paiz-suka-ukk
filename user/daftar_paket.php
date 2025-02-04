<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Ambil data paket
$query = "SELECT * FROM paket ORDER BY harga ASC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Paket - Pawspace.id</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: white !important;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .navbar-brand {
            color: #333 !important;
            font-weight: bold;
        }
        .nav-link {
            color: #555 !important;
            font-weight: 500;
        }
        .nav-link:hover {
            color: #007bff !important;
        }
        .package-card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 0 15px rgba(0,0,0,.05);
            transition: transform 0.3s, box-shadow 0.3s;
            overflow: hidden;
        }
        .package-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,.1);
        }
        .card-header {
            background: linear-gradient(45deg, #007bff, #0056b3);
            color: white;
            border: none;
            padding: 20px;
        }
        .price-tag {
            background: rgba(255,255,255,0.1);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9em;
            margin-top: 5px;
            display: inline-block;
        }
        .feature-list {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }
        .feature-list li {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .feature-list li:last-child {
            border-bottom: none;
        }
        .feature-list li i {
            color: #28a745;
            margin-right: 10px;
        }
        .btn-order {
            border-radius: 25px;
            padding: 8px 25px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header-section {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,.05);
            padding: 20px;
            margin-bottom: 30px;
        }
        .header-section h2 {
            margin: 0;
            color: #333;
            font-size: 1.75rem;
            font-weight: 600;
        }
        .header-section p {
            color: #666;
            margin: 10px 0 0 0;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">
                <i class="fas fa-paw me-2"></i>Pawspace.id
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
                        <a class="nav-link active" href="daftar_paket.php">
                            <i class="fas fa-box me-1"></i> Paket
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="transaksi.php">
                            <i class="fas fa-list me-1"></i> Transaksi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profil.php">
                            <i class="fas fa-user me-1"></i> Profil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="fas fa-sign-out-alt me-1"></i> Keluar
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="container py-4">
        <!-- Header Section -->
        <div class="header-section">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2>Daftar Paket Penitipan</h2>
                    <p>Pilih paket terbaik untuk hewan peliharaan Anda</p>
                </div>
            </div>
        </div>

        <!-- Packages Grid -->
        <div class="row">
            <?php while($paket = mysqli_fetch_assoc($result)): ?>
            <div class="col-md-4 mb-4">
                <div class="card package-card h-100">
                    <div class="card-header text-center">
                        <h3 class="mb-0"><?php echo $paket['nama_paket']; ?></h3>
                        <div class="price-tag">
                            Rp <?php echo number_format($paket['harga'], 0, ',', '.'); ?> /hari
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <p class="text-muted mb-4"><?php echo $paket['deskripsi']; ?></p>
                        <ul class="feature-list text-start">
                            <?php
                            $fitur = explode("\n", $paket['deskripsi']);
                            foreach($fitur as $f):
                                if(trim($f)):
                            ?>
                            <li><i class="fas fa-check-circle"></i> <?php echo trim($f); ?></li>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </ul>
                        <a href="pesan_paket.php?id=<?php echo $paket['id_paket']; ?>" 
                           class="btn btn-primary btn-order">
                            <i class="fas fa-shopping-cart me-2"></i>Pesan Sekarang
                        </a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>