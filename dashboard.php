<?php
session_start();
include "config.php";

// 1. Cek Login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// 2. Cek Role
if ($_SESSION['user']['role'] != 'siswa') {
    echo "Anda tidak memiliki akses ke halaman ini.";
    exit;
}

$user = $_SESSION['user'];

// --- PERBAIKAN BUG DISINI ---
// Kita prioritaskan 'id_user' (sesuai kode aslimu)
// Tapi kita jaga-jaga kalau suatu saat login.php berubah
if (isset($user['id_user'])) {
    $id_user = $user['id_user'];
} elseif (isset($user['id_users'])) {
    $id_user = $user['id_users'];
} else {
    // Jika tidak ada ID sama sekali, tendang ke login
    header("Location: login.php");
    exit;
}

// 3. Ambil Data Siswa (Moona, dll)
$query = "SELECT siswa.*, kelas.kelas AS nama_kelas 
          FROM siswa 
          LEFT JOIN kelas ON siswa.id_kelas = kelas.id_kelas 
          WHERE siswa.id_users = '$id_user'";

$result_siswa = mysqli_query($conn, $query);
$data_siswa   = mysqli_fetch_assoc($result_siswa);

// Validasi jika data siswa tidak ditemukan
if (!$data_siswa) {
    // Fallback tampilan saja
    $nama_lengkap = $user['username'];
    $kelas = "-";
    $id_siswa = 0; 
} else {
    $nama_lengkap = $data_siswa['nama_siswa'];
    $kelas = isset($data_siswa['nama_kelas']) ? $data_siswa['nama_kelas'] : "-";
    $id_siswa = $data_siswa['id_siswa'];
}

// --- LOGIC STAT CARD (Permintaan Kamu) ---
// 1. Total Laporan
$q_total = mysqli_query($conn, "SELECT COUNT(*) as total FROM jurnalkegiatan WHERE id_siswa = '$id_siswa'");
$d_total = mysqli_fetch_assoc($q_total);
$total_laporan = $d_total['total'];

// 2. Laporan Minggu Ini
$q_week = mysqli_query($conn, "SELECT COUNT(*) as weekly FROM jurnalkegiatan WHERE id_siswa = '$id_siswa' AND YEARWEEK(tanggal, 1) = YEARWEEK(CURDATE(), 1)");
$d_week = mysqli_fetch_assoc($q_week);
$minggu_ini = $d_week['weekly'];

// 3. Status Disetujui (Pengganti Hari PKL)
$q_acc = mysqli_query($conn, "SELECT COUNT(*) as acc FROM jurnalkegiatan WHERE id_siswa = '$id_siswa' AND status = 'disetujui'");
$d_acc = mysqli_fetch_assoc($q_acc);
$disetujui = $d_acc['acc'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard - Laporan PKL</title>
  <link rel="stylesheet" href="style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

<header>
  <nav>
    <div class="logo"><S></S></div>
    <ul class="nav-links">
        <li><a href="dashboard.php" class="active"><i class="fa fa-th-large"></i> Dashboard</a></li>
        <li><a href="about.php"><i class="fas fa-info-circle"></i> About</a></li>
        <li><a href="laporan.php"><i class="fas fa-file-alt"></i> Isi Laporan</a></li>
        <li><a href="daftar-laporan.php"><i class="fas fa-list-alt"></i> Riwayat</a></li>
        <li><a href="contact.php"><i class="fas fa-envelope"></i> Contact</a></li>
        <li><a href="login.php" id="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
     </ul>
  </nav>
</header>

<main class="dashboard-style">
  <div class="dashboard-container">
    
    <h1 class="animate-fade-up">Selamat Datang, <span id="username"><?php echo htmlspecialchars($nama_lengkap); ?></span>!</h1>
    <p class="subtitle animate-fade-up delay-1" style="text-align: center; color: #aeb8c4; margin-bottom: 30px;">
      Ini adalah pusat kendali Anda. Tetap semangat dan jangan lupa isi laporan harian Anda.
    </p>

    <div class="user-identity animate-fade-up delay-2">
        <div class="user-info">
            <p><strong>Nama:</strong> <span><?php echo htmlspecialchars($nama_lengkap); ?></span></p>
            <p><strong>Kelas:</strong> <span><?php echo htmlspecialchars($kelas); ?></span></p>
            <p><strong>Role:</strong> <span><?php echo $user['role']; ?></span></p>
        </div>
    </div>

    <div class="animate-fade-up delay-3">
      <h2 style="text-align: center; margin-top: 30px;">Statistik Aktivitas</h2>
      <div class="stat-grid">
          <a href="daftar-laporan.php" class="card-link">
              <div class="stat-card">
                  <h3>Total Laporan</h3>
                  <p><?php echo $total_laporan; ?></p>
              </div>
          </a>
          <a href="daftar-laporan.php" class="card-link">
              <div class="stat-card">
                  <h3>Minggu Ini</h3>
                  <p><?php echo $minggu_ini; ?></p>
              </div>
          </a>
          <a href="daftar-laporan.php" class="card-link">
              <div class="stat-card">
                  <h3>Disetujui</h3>
                  <p><?php echo $disetujui; ?></p>
              </div>
          </a>
      </div>
    </div>

    <div class="action-buttons animate-fade-up delay-5" style="margin-top: 40px;">
        <a href="laporan.php" class="btn-dashboard">
            <i class="fas fa-plus-circle"></i> Isi Laporan Hari Ini
        </a>
        <a href="daftar-laporan.php" class="btn-dashboard secondary">
            <i class="fas fa-eye"></i> Lihat Semua Laporan
        </a>
    </div>
  </div>
</main>
<script src="script.js"></script>
</body>
</html>