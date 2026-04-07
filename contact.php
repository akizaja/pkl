<?php
session_start();
include 'config.php';

// 1. Cek apakah user sudah login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// 2. Ambil data Siswa untuk Auto-Fill form
$id_user = $_SESSION['user']['id_users'] ?? $_SESSION['user']['id_user'];

// Query ambil nama siswa dan nama kelas
$query = mysqli_query($conn, "
    SELECT siswa.nama_siswa, kelas.kelas 
    FROM siswa 
    LEFT JOIN kelas ON siswa.id_kelas = kelas.id_kelas 
    WHERE siswa.id_users = '$id_user'
");
$siswa = mysqli_fetch_assoc($query);

// Jika data belum lengkap, set default kosong
$nama_siswa = $siswa['nama_siswa'] ?? '';
$nama_kelas = $siswa['kelas'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak - Kelompok 5</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

<header>
    <nav>
        <div class="logo">KONTAK</div>
        <ul class="nav-links">
            <li><a href="dashboard.php"><i class="fa     fa-th-large"></i> Dashboard</a></li>
            <li><a href="about.php"><i class="fas fa-info-circle"></i> About</a></li>
            <li><a href="laporan.php"><i class="fas fa-file-alt"></i> Isi Laporan</a></li>
            <li><a href="daftar-laporan.php"><i class="fas fa-list-alt"></i> Riwayat</a></li>
            <li><a href="contact.php" class="active"><i class="fas fa-envelope"></i> Contact</a></li>
            <li><a href="login.php" id="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>
</header>

<main class="contact-page">
    <div class="contact-container animate-fade-up">
        <h1>Contact Us!</h1>
        <p class="subtitle">Punya masukan? Jangan ragu buat kasih masukan kalian disini</p>
        
        <form action="https://formspree.io/f/xgvzlgol" method="POST">
            <div class="contact-text">
                <label for="nama">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" required value="<?php echo htmlspecialchars($nama_siswa); ?>" readonly style="background: #1e293b; color: #94a3b8;">
            </div>
            <div class="contact-text">
                <label for="kelas">Kelas</label>
                <input type="text" id="kelas" name="kelas" required value="<?php echo htmlspecialchars($nama_kelas); ?>" readonly style="background: #1e293b; color: #94a3b8;">
            </div>
            <div class="contact-text">
                <label for="email">Alamat Email</label>
                <input type="email" id="email" name="email" required placeholder="email@kamu.com">
            </div>
            <div class="contact-text">
                <label for="pesan">Pesan</label>
                <textarea id="pesan" name="pesan" rows="5" required placeholder="Tulis pesanmu di sini..."></textarea>
            </div>
            <button type="submit" class="btn-kirim">Kirim Pesan</button>
        </form>

        <div class="social-media-links">
            <p>Atau temukan kami di:</p>
            <div class="icons">
                <a href="https://github.com/akizaja" aria-label="GitHub"><i class="fab fa-github"></i></a>
            </div>
        </div>
    </div>
</main>
</body>
</html>