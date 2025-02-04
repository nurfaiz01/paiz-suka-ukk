</div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
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
                
                <div class="col-lg-4 col-md-6">
                    <div class="footer-content">
                        <h5 class="mb-4">Link Cepat</h5>
                        <ul class="footer-links">
                            <li><a href="../index.php">Beranda</a></li>
                            <li><a href="../index.php#layanan">Layanan</a></li>
                            <li><a href="../index.php#paket">Paket</a></li>
                            <li><a href="../index.php#kontak">Kontak</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="footer-content">
                        <h5 class="mb-4">Kontak Info</h5>
                        <ul class="footer-contact">
                            <li>
                                <i class="fas fa-map-marker-alt me-2"></i>
                                Jl. Niken Gandini No. 123, Jenangan, Ponorogo
                            </li>
                            <li>
                                <i class="fas fa-phone me-2"></i>
                                +62 822-5700-4434
                            </li>
                            <li>
                                <i class="fas fa-envelope me-2"></i>
                                pawspace@gmail.com
                            </li>
                        </ul>
                        <div class="mt-4">
                            <h6>Jam Operasional:</h6>
                            <p class="mb-0">Senin - Minggu: 08.00 - 20.00</p>
                        </div>
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
                    <a href="#" class="text-white">Privacy Policy</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <a href="#" class="back-to-top">
        <i class="fas fa-arrow-up"></i>
    </a>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
    .footer-links {
        list-style: none;
        padding: 0;
    }
    .footer-links li {
        margin-bottom: 10px;
    }
    .footer-links a {
        color: white;
        text-decoration: none;
    }
    .footer-links a:hover {
        color: #28a745;
    }
    .social-links {
        display: flex;
        gap: 15px;
    }
    .social-link {
        color: white;
        background: rgba(255,255,255,0.1);
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        text-decoration: none;
        transition: all 0.3s;
    }
    .social-link:hover {
        background: #28a745;
        color: white;
    }
    .footer-contact {
        list-style: none;
        padding: 0;
    }
    .footer-contact li {
        margin-bottom: 15px;
        color: rgba(255,255,255,0.8);
    }
    .back-to-top {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: #28a745;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        opacity: 0;
        transition: all 0.3s;
    }
    .back-to-top.active {
        opacity: 1;
    }
    </style>

    <script>
    // Close alert after 3 seconds
    $(document).ready(function(){
        setTimeout(function(){
            $(".alert").alert('close');
        }, 3000);

        // Back to top button
        $(window).scroll(function() {
            if($(this).scrollTop() > 100) {
                $('.back-to-top').addClass('active');
            } else {
                $('.back-to-top').removeClass('active');
            }
        });

        $('.back-to-top').click(function(e) {
            e.preventDefault();
            $('html, body').animate({scrollTop: 0}, 'slow');
        });
    });
    </script>

</body>
</html>