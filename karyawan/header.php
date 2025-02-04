<?php
session_start();
require_once '../includes/config.php';

// Cek jika belum login
if(!isset($_SESSION['karyawan_id'])) {
    header("Location: login.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Karyawan - Pawspace.id</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background: #28a745;
            color: white;
        }
        .nav-link {
            color: white;
        }
        .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        .active {
            background: rgba(255,255,255,0.2);
        }
        .content {
            padding: 20px;
        }
        .navbar-brand {
            color: white !important;
            font-size: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 sidebar">
                <div class="d-flex flex-column">
                    <a class="navbar-brand p-3" href="index.php">
                        <i class="fas fa-paw me-2"></i>Pawspace.id
                    </a>
                    <hr class="text-white">
                    <ul class="nav flex-column mb-auto">
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" href="index.php">
                                <i class="fas fa-home me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'riwayat_transaksi.php' ? 'active' : ''; ?>" href="riwayat_transaksi.php">
                                <i class="fas fa-history me-2"></i>Riwayat Transaksi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'profil.php' ? 'active' : ''; ?>" href="profil.php">
                                <i class="fas fa-user me-2"></i>Profil
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 content">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4><?php echo isset($page_title) ? $page_title : 'Dashboard'; ?></h4>
                    <div class="user-info">
                        <i class="fas fa-user-circle me-2"></i>
                        <?php echo $_SESSION['karyawan_nama']; ?>
                    </div>
                </div>