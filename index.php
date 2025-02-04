<?php
require_once 'includes/config.php';

$query = "SELECT * FROM paket ORDER BY harga ASC";
$paket_result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pawspace.id - Penitipan Hewan Terpercaya</title>
    
    <link rel="icon" type="image/png" href="assets/images/hero-bg.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="page.css">
</head>
<body>
    <!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#">
            <i class="fas fa-paw me-2"></i>Pawspace.id
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#beranda">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#layanan">Layanan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#paket">Paket</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#kontak">Kontak</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-primary ms-2" href="user/login.php">
                        <i class="fas fa-sign-in-alt me-2"></i>Login
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-outline-primary ms-2" href="user/register.php">
                        <i class="fas fa-user-plus me-2"></i>Register
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-info ms-2" href="admin/login.php">
                        <i class="fas fa-user-shield me-2"></i>Admin
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-success ms-2" href="karyawan/login.php">
                        <i class="fas fa-user-tie me-2"></i>Karyawan
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

    <!-- Hero Section -->
    <section class="hero-section" id="beranda">
        <div class="container text-center">
            <h1 class="display-4 mb-4" data-aos="fade-up">Penitipan Hewan Terpercaya</h1>
            <p class="lead mb-4" data-aos="fade-up" data-aos-delay="200">
                Memberikan pelayanan terbaik untuk hewan kesayangan Anda dengan fasilitas lengkap dan staff yang berpengalaman.
            </p>
            <div class="hero-buttons" data-aos="fade-up" data-aos-delay="400">
                <a href="#paket" class="btn btn-primary btn-lg me-3">
                    <i class="fas fa-box-open me-2"></i>Lihat Paket
                </a>
                <a href="#kontak" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-phone me-2"></i>Hubungi Kami
                </a>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 mb-4" data-aos="fade-up">
                    <div class="stat-card">
                        <i class="fas fa-heart mb-3"></i>
                        <h2 class="counter">1000+</h2>
                        <p>Hewan Terbantu</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-card">
                        <i class="fas fa-users mb-3"></i>
                        <h2 class="counter">500+</h2>
                        <p>Pelanggan Puas</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="stat-card">
                        <i class="fas fa-certificate mb-3"></i>
                        <h2 class="counter">5+</h2>
                        <p>Tahun Pengalaman</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="600">
                    <div class="stat-card">
                        <i class="fas fa-star mb-3"></i>
                        <h2 class="counter">4.9</h2>
                        <p>Rating Pelanggan</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Layanan Section -->
    <section class="py-5" id="layanan">
        <div class="container">
            <div class="text-center mb-5">
                <h6 class="text-primary text-uppercase mb-2" data-aos="fade-up">Layanan Kami</h6>
                <h2 class="display-5 fw-bold mb-4" data-aos="fade-up" data-aos-delay="200">Apa yang Kami Tawarkan</h2>
                <div class="mx-auto" style="max-width: 600px;">
                    <p class="text-muted lead" data-aos="fade-up" data-aos-delay="400">
                        Kami menyediakan layanan penitipan hewan terbaik dengan fasilitas modern dan perawatan profesional
                    </p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6" data-aos="fade-up">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <div class="service-content">
                            <h4>Kandang Nyaman</h4>
                            <p>Kandang yang luas dan bersih dengan sistem ventilasi modern.</p>
                            <div class="service-features">
                                <span><i class="fas fa-check"></i> Area bermain terpisah</span>
                                <span><i class="fas fa-check"></i> Pembersihan rutin</span>
                                <span><i class="fas fa-check"></i> Ventilasi optimal</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <div class="service-content">
                            <h4>Staff Berpengalaman</h4>
                            <p>Tim profesional terlatih dan bersertifikat dalam perawatan hewan.</p>
                            <div class="service-features">
                                <span><i class="fas fa-check"></i> Dokter hewan siaga</span>
                                <span><i class="fas fa-check"></i> Perawat terlatih</span>
                                <span><i class="fas fa-check"></i> Groomer profesional</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="service-content">
                            <h4>Monitoring 24 Jam</h4>
                            <p>Sistem pemantauan CCTV 24 jam dengan staff yang selalu siaga.</p>
                            <div class="service-features">
                                <span><i class="fas fa-check"></i> CCTV 24 jam</span>
                                <span><i class="fas fa-check"></i> Update berkala</span>
                                <span><i class="fas fa-check"></i> Monitoring online</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Paket Section -->
    <section class="py-5 bg-light" id="paket">
        <div class="container">
            <div class="text-center mb-5">
                <h6 class="text-primary text-uppercase mb-2" data-aos="fade-up">Pilihan Paket</h6>
                <h2 class="display-5 fw-bold mb-4" data-aos="fade-up" data-aos-delay="200">Paket Penitipan</h2>
            </div>
            <div class="row g-4">
                <?php while($paket = mysqli_fetch_assoc($paket_result)): ?>
                <div class="col-md-4" data-aos="fade-up">
                    <div class="paket-card card">
                        <div class="card-body text-center">
                            <div class="package-icon mb-4">
                                <i class="fas fa-paw"></i>
                            </div>
                            <h3 class="card-title"><?php echo $paket['nama_paket']; ?></h3>
                            <div class="price my-4">
                                <span class="h2">Rp <?php echo number_format($paket['harga'], 0, ',', '.'); ?></span>
                                <span class="text-muted">/hari</span>
                            </div>
                            <p class="card-text"><?php echo $paket['deskripsi']; ?></p>
                            <a href="https://wa.me/6282257004434?text=Halo,%20saya%20tertarik%20dengan%20<?php echo urlencode($paket['nama_paket']); ?>" 
                               class="btn btn-primary w-100" target="_blank">
                                <i class="fab fa-whatsapp me-2"></i>Hubungi Kami
                            </a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- Kontak Section -->
    <section class="py-5" id="kontak">
        <div class="container">
            <div class="text-center mb-5">
                <h6 class="text-primary text-uppercase mb-2" data-aos="fade-up">Kontak Kami</h6>
                <h2 class="display-5 fw-bold mb-4" data-aos="fade-up" data-aos-delay="200">Hubungi Kami</h2>
            </div>
            <div class="row">
                <div class="col-md-6" data-aos="fade-right">
                    <div class="contact-info">
                        <h4 class="mb-4">Informasi Kontak</h4>
                        <div class="contact-item mb-3">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            <span>Jl. Niken Gandini No. 123, Jenangan, Ponorogo</span>
                        </div>
                        <div class="contact-item mb-3">
                            <i class="fas fa-phone me-2"></i>
                            <span>+62 822-5700-4434</span>
                        </div>
                        <div class="contact-item mb-3">
                            <i class="fas fa-envelope me-2"></i>
                            <span>pawspace@gmail.com</span>
                        </div>
                        <div class="operating-hours mt-4">
                            <h5>Jam Operasional:</h5>
                            <p>Senin - Minggu: 08.00 - 20.00</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" data-aos="fade-left">
                    <div class="contact-form card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Kirim Pesan</h4>
                            <form id="contactForm">
                                <div class="mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Pesan</label>
                                    <textarea class="form-control" rows="4" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-paper-plane me-2"></i>Kirim Pesan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
                </div>
                <!-- Kontak Section lanjutan -->
        </div>
    </section>

   <!-- Footer -->     
<footer class="bg-dark text-white py-5">         
    <div class="container">             
        <div class="row g-4">                 
            <div class="col-lg-4 col-md-6" data-aos="fade-up">                     
                <div class="footer-content">                         
                    <h5 class="mb-4">                             
                        <i class="fas fa-paw me-2"></i>Pawspace.id                         
                    </h5>                         
                    <p>Memberikan pelayanan terbaik untuk hewan kesayangan Anda dengan fasilitas modern dan staff yang profesional.</p>                         
                    <div class="social-links mt-4">                             
                        <a href="https://web.facebook.com/profile.php?id=100079103724200" class="social-link">                                 
                            <i class="fab fa-facebook-f"></i>                             
                        </a>                             
                        <a href="https://www.instagram.com/pawspace.id/" class="social-link">                                 
                            <i class="fab fa-instagram"></i>                             
                        </a>                             
                        <a href="https://x.com/pawspc_01" class="social-link">                                 
                            <i class="fab fa-twitter"></i>                             
                        </a>                             
                        <a href="https://www.youtube.com/@pawspace.i" class="social-link">                                 
                            <i class="fab fa-youtube"></i>                             
                        </a>                         
                    </div>                     
                </div>                 
            </div>                                  
            
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">                     
                <div class="footer-content">                         
                    <h5 class="mb-4">Link Cepat</h5>                         
                    <ul class="footer-links">                             
                        <li><a href="#beranda">Beranda</a></li>                             
                        <li><a href="#layanan">Layanan</a></li>                             
                        <li><a href="#paket">Paket</a></li>                             
                        <li><a href="#kontak">Kontak</a></li>                             
                        <li><a href="user/login.php">Login</a></li>                             
                        <li><a href="user/register.php">Register</a></li>
                        <li><a href="https://faiz.pkl-edusupe.my.id/#" target="_blank" class="text-white">
                            <i class="fas fa-code me-1"></i>Web Developer Profile
                        </a></li>
                    </ul>                     
                </div>                 
            </div>                  

            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="400">                     
                <div class="footer-content">                         
                    <h5 class="mb-4">Newsletter</h5>                         
                    <p>Berlangganan newsletter kami untuk mendapatkan informasi terbaru.</p>                         
                    <form class="newsletter-form mt-4">                             
                        <div class="input-group">                                 
                            <input type="email" class="form-control" placeholder="Email address" required>                                 
                            <button class="btn btn-primary" type="submit">                                     
                                <i class="fas fa-paper-plane"></i>                                 
                            </button>                             
                        </div>                         
                    </form>                     
                </div>                 
            </div>             
        </div>              

        <hr class="my-4">              

        <div class="row">                 
            <div class="col-md-6">                     
                <p class="mb-md-0">© 2025 Pawspace.id. All rights reserved.</p>                 
            </div>                 
            <div class="col-md-6 text-md-end">                     
                <a href="#" class="text-white me-3">Terms of Service</a>                     
                <a href="#" class="text-white me-3">Privacy Policy</a>
            </div>             
        </div>         
    </div>     
</footer>

    <!-- Back to Top Button -->
    <a href="#" class="back-to-top">
        <i class="fas fa-arrow-up"></i>
    </a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                document.querySelector('.navbar').classList.add('scrolled');
            } else {
                document.querySelector('.navbar').classList.remove('scrolled');
            }
        });

        // Back to top button
        const backToTop = document.querySelector('.back-to-top');
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 100) {
                backToTop.classList.add('active');
            } else {
                backToTop.classList.remove('active');
            }
        });

        // Counter animation
        const counters = document.querySelectorAll('.counter');
        counters.forEach(counter => {
            const target = counter.innerText;
            const increment = target / 200;
            
            function updateCount() {
                const c = +counter.innerText.replace(/[^\d.-]/g, '');
                if (c < target) {
                    counter.innerText = Math.ceil(c + increment);
                    setTimeout(updateCount, 1);
                } else {
                    counter.innerText = target;
                }
            }
            
            updateCount();
        });

        // Form submission
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // Add your form submission logic here
            alert('Pesan telah terkirim! Kami akan menghubungi Anda segera.');
            this.reset();
        });
    </script>
</body>
</html>