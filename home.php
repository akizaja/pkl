<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="io/styl.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head> 
<body>
        <header>
        <nav>
    <div style="display: flex; align-items: center; gap: 15px;">
        <img src="img-stuff/1757683154177.jpg" alt="Logo" style="height: 60px; width: auto;">
    </div>
            <ul class="nav-links">
            <li><a href="home.php" class="active"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
            </ul>
            </nav>
        </header>
    <main class="public-view-page">
        
        <div class="dashboard-container" style="max-width: 900px;">
            
            <div class="animate-fade-up" style="text-align: center; margin-bottom: 40px;">
                <h1 style="font-size: 2.5rem; margin-bottom: 10px;">Showcase Web PKL</h1>
                <p style="color: #cdd6e0;">Berikut adalah ringkasan progres Web PKL.</p>
            </div>

            <div class="project-summary-card animate-fade-up delay-1">
                <h2>Sistem Informasi Manajemen Aset (SIMA)</h2>
                <p>
                    Proyek web PKL adalah pengembangan Sistem Informasi Manajemen Aset (SIMA) berbasis web untuk mengoptimalkan pencatatan, pemantauan, dan pelaporan inventaris kantor. Tujuan utama sistem ini adalah mengurangi kesalahan data, mempercepat proses audit, dan menyediakan visualisasi status aset secara real-time.
                </p>
            </div>
            
            <div class="module-showcase-section animate-fade-up delay-2">
                <h2 class="section-title"> Fitur Fitur Yang Ada </h2>
                
                <div class="feature-grid">
                    
                    <div class="feature-card animate-fade-up delay-3">
                        <h4 class="feature-title"><i class="fas fa-lock"></i> Autentikasi</h4>
                        <p class="feature-description">Sistem login, logout, dan register berbasis hak akses (Admin & Staff) dengan enkripsi password.</p>
                    </div>
            
                    <div class="feature-card animate-fade-up delay-4">
                        <h4 class="feature-title"><i class="fas fa-database"></i> Manajemen Data Dasar</h4>
                        <p class="feature-description">Fungsi CRUD (Create, Read, Update, Delete) untuk data Kategori Aset dan Lokasi Penyimpanan.</p>
                    </div>
            
                    <div class="feature-card animate-fade-up delay-3">
                        <h4 class="feature-title"><i class="fas fa-cubes"></i> Daftar Inventaris Aset</h4>
                        <p class="feature-description">Tabel data aset utama dengan fitur pencarian dan pagination untuk data berskala besar.</p>
                    </div>
            
                    <div class="feature-card animate-fade-up delay-4">
                        <h4 class="feature-title"><i class="fas fa-chart-bar"></i> Dashboard Ringkasan</h4>
                        <p class="feature-description">Tampilan statistik grafis sederhana mengenai jumlah total aset dan aset yang membutuhkan perawatan.</p>
                    </div>
       
            
         
                </div>
            </div>

            <div class="animate-fade-up delay-5" style="text-align: center; padding: 30px; background: rgba(0,0,0,0.2); border-radius: 16px; border: 1px dashed rgba(255,255,255,0.1);">
                <h3 style="margin-bottom: 10px; font-weight: 600;">Ingin melihat detail laporan lengkap?</h3>
                <p style="color: #aeb8c4; margin-bottom: 20px; font-size: 0.9rem;">
                    Akses penuh ke jurnal harian, file laporan, dan fitur manajemen hanya tersedia untuk anggota terdaftar.
                </p>
                <a href="login.php" class="btn btn-secondary" style="margin-top: 0;">
                    Masuk ke Sistem
                </a>
            </div>

        </div> 
        </main>

</body>
</html>
