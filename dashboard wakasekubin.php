<?php
session_start();
include "config.php"; 

// 1. Cek apakah user sudah login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// 2. Cek apakah role-nya benar Wakasek
if ($_SESSION['user']['role'] != 'wakasek') {
    echo "Anda tidak memiliki akses ke halaman ini.";
    exit;
}

// --- HITUNG DATA MOU ---
$today = date('Y-m-d');
$q_aktif = mysqli_query($conn, "SELECT COUNT(*) as total FROM perusahaan WHERE tgl_berakhir_mou >= '$today'");
$aktif = mysqli_fetch_assoc($q_aktif)['total'];

$q_expired = mysqli_query($conn, "SELECT COUNT(*) as total FROM perusahaan WHERE tgl_berakhir_mou < '$today' AND tgl_berakhir_mou IS NOT NULL");
$expired = mysqli_fetch_assoc($q_expired)['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Hubin</title>
  <link rel="stylesheet" href="style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <style>
      /* Tambahan dikit biar card kehadiran bisa di-hover */
      .clickable-card {
          cursor: pointer;
          transition: transform 0.2s, box-shadow 0.2s;
          text-decoration: none;
          color: inherit;
          display: block;
      }
      .clickable-card:hover {
          transform: translateY(-5px);
          box-shadow: 0 10px 20px rgba(0,0,0,0.1);
      }
  </style>
</head>
<body>

<header>
  <nav>
    <div class="logo">WAKASEK PANEL</div>
    <ul class="nav-links">
        <li><a href="dashboard wakasekubin.php" class="active"><i class="fa fa-th-large"></i> Dashboard</a></li>
        <li><a href="wakasek-mou.php"><i class="fas fa-handshake"></i> Data MoU</a></li>
        <li><a href="wakasek-kehadiran.php"><i class="fas fa-clipboard-user"></i> Data Jurnal Kegiatan</a></li>
        <li><a href="login.php" id="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
     </ul>
  </nav>
</header>

<main class="dashboard-style">
  <div class="dashboard-container">
    
    <h1 class="animate-fade-up">Selamat Datang, <span id="username"><?php echo $_SESSION['user']['username']; ?></span>!</h1>
    
    <div class="animate-fade-up delay-2">
      <h2 style="text-align: center;">Status Penempatan PKL</h2>
      <div class="progress-section" style="margin-top: 10px;">
          <div class="progress-bar-container">
              <div class="progress-bar" style="width: 90%; background: #27ae60;"></div>
          </div>
          <p style="text-align:center; font-size:0.9rem; margin-top:5px; color:#666;">90% Siswa sudah mendapat tempat</p>
      </div>
    </div>

    <div class="animate-fade-up delay-3" style="margin-top: 30px;">
      <div class="stat-grid">
          <div class="stat-card" style="border-left: 4px solid #bc6bff;">
              <h3 style="color: #bc6bff;"><i class="fas fa-handshake"></i> MoU Aktif</h3>
              <p style="font-size: 2rem; font-weight:bold; color:#bc6bff;"><?php echo $aktif; ?></p>
              <small>Perusahaan</small>
          </div>
          
          <div class="stat-card" style="border-left: 4px solid #ef4444;">
              <h3 style="color: #ef4444;"><i class="fas fa-file-contract"></i> MoU Expired</h3>
              <p style="font-size: 2rem; font-weight:bold; color: #ef4444;"><?php echo $expired; ?></p>
              <small>Perlu Diperbarui</small>
          </div>
          
          <a href="wakasek-kehadiran.php" class="stat-card clickable-card" style="border-left: 4px solid #3b82f6;">
              <h3 style="color: #3b82f6;"><i class="fas fa-users"></i> Monitoring Siswa</h3>
              <p style="font-size: 1.2rem; margin-top:10px;">Cek Kehadiran &rarr;</p>
              <small>Data Jurnal Kegiatan</small>
          </a>
      </div>
    </div>

  </div> 
</main>

</body>
</html>