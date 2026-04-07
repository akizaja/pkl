<?php
session_start();
// Cek status login untuk menyesuaikan tombol navigasi
$isLoggedIn = isset($_SESSION['user']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Jurnal PKL - Sistem Informasi Monitoring</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

<header>
    <nav>
        <div class="logo">
            <i class="fas fa-book-reader" style="color: #4a69ff; margin-right: 10px;"></i> E-JURNAL PKL
        </div>     
        <ul class="nav-links">
            <li><a href="home.php" class="active"><i class="fas fa-home"></i> Home</a></li>
            <?php if ($isLoggedIn): ?>
                <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                <?php endif; ?>
        </ul>
    </nav>
</header>

<section class="hero-section parallax-bg">
    <div class="layer layer-sky"></div>
    <div class="moon"></div>
    
    <div class="hero-content">
        <h1>Digitalisasi <br><span style="color: #4a69ff;">Jurnal PKL</span></h1>
        <p>
            Platform pelaporan kegiatan Praktik Kerja Lapangan yang modern, 
            efisien, dan transparan. Hubungkan siswa, sekolah, dan industri 
            dalam satu ekosistem digital.
        </p>
        
        <div style="margin-top: 30px;">
            <?php if ($isLoggedIn): ?>
                <a href="dashboard.php" class="btn-secondary">
                    <i class="fas fa-rocket"></i> Buka Dashboard
                </a>
            <?php else: ?>
                <a href="login.php" class="btn-secondary">
                    Mulai Sekarang <i class="fas fa-arrow-right"></i>
                </a>
                <a href="about.php" style="margin-left: 20px; color: #8a9fff; text-decoration: none; font-weight: 600;">
                    Pelajari Materi <i class="fas fa-book"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="dashboard-style" style="min-height: auto; padding-top: 50px; background: transparent;">
    <div class="dashboard-container animate-fade-up" style="border: none; background: transparent; box-shadow: none;">
        <div style="text-align: center; margin-bottom: 40px;">
            <h2 style="font-size: 2rem;">Mengapa Menggunakan E-Jurnal?</h2>
            <p style="color: #aeb8c4;">Memudahkan proses administrasi PKL bagi semua pihak.</p>
        </div>

        <div class="stat-grid">
            <div class="stat-card">
                <i class="fas fa-laptop-code" style="font-size: 2.5rem; color: #4a69ff; margin-bottom: 15px;"></i>
                <h3>Paperless</h3>
                <p style="font-size: 1rem; font-weight: 400; color: #cdd6e0;">
                    Tidak perlu lagi buku jurnal fisik. Isi laporan harian langsung dari gadget di mana saja.
                </p>
            </div>

            <div class="stat-card">
                <i class="fas fa-sync-alt" style="font-size: 2.5rem; color: #2dd4bf; margin-bottom: 15px;"></i>
                <h3>Real-time</h3>
                <p style="font-size: 1rem; font-weight: 400; color: #cdd6e0;">
                    Pembimbing sekolah dan industri dapat memantau aktivitas siswa secara langsung.
                </p>
            </div>

            <div class="stat-card">
                <i class="fas fa-file-pdf" style="font-size: 2.5rem; color: #bc6bff; margin-bottom: 15px;"></i>
                <h3>Auto Report</h3>
                <p style="font-size: 1rem; font-weight: 400; color: #cdd6e0;">
                    Unduh rekap kegiatan menjadi laporan PDF siap cetak dalam sekali klik.
                </p>
            </div>
        </div>
    </div>
</section>

<footer style="text-align: center; padding: 40px; color: #aeb8c4; border-top: 1px solid rgba(255,255,255,0.1); margin-top: 50px;">
    <p>&copy; <?php echo date('Y'); ?> Kelompok 5 - Rekayasa Perangkat Lunak.</p>
    <div class="social-media-links" style="border: none; margin-top: 10px;">
        <div class="icons">
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-github"></i></a>
            <a href="#"><i class="fas fa-globe"></i></a>
        </div>
    </div>
</footer>

</body>
</html>