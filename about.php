<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi</title>
    <link rel="stylesheet" href="style.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

<header>
    <nav>
        <div class="logo">SIPKL</div>
        
        <ul class="nav-links">
        <li><a href="dashboard.php"><i class="fa fa-th-large"></i> Dashboard</a></li>
        <li><a href="about.php" class="active"><i class="fas fa-info-circle"></i> About</a></li>
        <li><a href="laporan.php"><i class="fas fa-file-alt"></i> Laporan PKL</a></li>
        <li><a href="daftar-laporan.php"><i class="fas fa-list-alt"></i> Daftar Laporan</a></li>
        <li><a href="contact.php"><i class="fas fa-envelope"></i> Contact</a></li>
        <li><a href="login.php" id="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>
</header>

<main class="about-page">
    <div class="about-container">
        <h1 class="animate-fade-up delay-1">Materi PKL</h1>
        <div class="materi-grid">

            <div class="materi-card animate-fade-up delay-4">
                <div class="materi-icon"><i class="fab fa-html5"></i></div>
                <div class="materi-header">
                    <h3>Pengenalan HTML & CSS</h3>
                    <span class="difficulty dasar">Dasar</span>
                </div>
                <p class="materi-description">
                    Kami mempelajari fondasi pengembangan web, yaitu HTML untuk menstrukturkan konten dan CSS untuk mengatur tata letak, warna, serta animasi. Tujuannya adalah menciptakan halaman yang menarik dan responsif di berbagai perangkat menggunakan Flexbox dan Grid Layout.
                </p>
            </div>

            <div class="materi-card animate-fade-up delay-4">
                <div class="materi-icon"><i class="fab fa-js-square"></i></div>
                <div class="materi-header">
                    <h3>JavaScript Dasar</h3>
                    <span class="difficulty dasar">Dasar</span>
                </div>
                <p class="materi-description">
                    Materi ini fokus pada penambahan interaktivitas pada website. Kami mempelajari konsep inti seperti variabel, fungsi, dan manipulasi DOM untuk memberikan feedback instan kepada pengguna, serta melakukan validasi form tanpa perlu me-refresh halaman.
                </p>
            </div>

            <div class="materi-card animate-fade-up delay-5">
                <div class="materi-icon"><i class="fab fa-bootstrap"></i></div>
                <div class="materi-header">
                    <h3>Framework Frontend</h3>
                    <span class="difficulty menengah">Menengah</span>
                </div>
                <p class="materi-description">
                    Untuk efisiensi, kami menggunakan framework seperti Bootstrap yang menyediakan komponen UI siap pakai. Hal ini mempercepat proses development dan membantu menjaga konsistensi desain di seluruh halaman aplikasi web.
                </p>
            </div>
            
            <div class="materi-card animate-fade-up delay-6">
                <div class="materi-icon"><i class="fas fa-database"></i></div>
                <div class="materi-header">
                    <h3>Backend & Database</h3>
                    <span class="difficulty menengah">Menengah</span>
                </div>
                <p class="materi-description">
                    Kami membangun logika sisi server menggunakan API sebagai jembatan antara frontend dan database. Materi ini mencakup pengelolaan otentikasi pengguna dan operasi CRUD (Create, Read, Update, Delete) pada data.
                </p>
            </div>

            <div class="materi-card animate-fade-up delay-7">
                <div class="materi-icon"><i class="fas fa-rocket"></i></div>
                <div class="materi-header">
                    <h3>Testing & Deployment</h3>
                    <span class="difficulty mahir">Mahir</span>
                </div>
                <p class="materi-description">
                    Sebelum dirilis, aplikasi harus melewati tahap pengujian untuk memastikan semua fungsi berjalan tanpa bug. Setelah itu, aplikasi di-deploy ke server hosting agar dapat diakses secara online oleh pengguna di seluruh dunia.
                </p>
            </div>

            <div class="materi-card animate-fade-up delay-8">
                <div class="materi-icon"><i class="fas fa-award"></i></div>
                <div class="materi-header">
                    <h3>Kesimpulan PKL</h3>
                    <span class="difficulty-none"></span>
                </div>
                <p class="materi-description">
                   Secara keseluruhan, PKL memberikan pengalaman nyata dalam siklus pengembangan perangkat lunak, mulai dari ide, kerja sama tim, pemecahan masalah teknis, hingga perilisan produk digital yang fungsional.
                </p>
            </div>

        </div>
    </div>
</main>

</body>
</html>