<?php
session_start();
include "config.php"; // Pastikan koneksi disertakan

// Cek Login
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// --- LOGIKA HITUNG STATISTIK ---
// 1. Hitung Total Siswa (dari tabel siswa biar akurat datanya real)
$query_siswa = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='siswa'");
$data_siswa = mysqli_fetch_assoc($query_siswa);
$total_siswa = $data_siswa['total'];

// 2. Hitung Pembimbing (ambil dari tabel users yang rolenya pembimbing)
$query_pembimbing = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='pembimbing'");
$data_pembimbing = mysqli_fetch_assoc($query_pembimbing);
$total_pembimbing = $data_pembimbing['total'];

// 3. Hitung Mitra Industri (dari tabel perusahaan)
$query_mitra = mysqli_query($conn, "SELECT COUNT(*) as total FROM perusahaan");
$data_mitra = mysqli_fetch_assoc($query_mitra);
$total_mitra = $data_mitra['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Admin</title>
  <link rel="stylesheet" href="style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

<header>
  <nav>
    <div class="logo">ADMIN PANEL</div>
    <ul class="nav-links">
        <li><a href="dashboard admin.php" class="active"><i class="fa fa-th-large"></i> Dashboard</a></li>
        <li><a href="login.php" id="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
     </ul>
  </nav>
</header>

<main class="dashboard-style">
  <div class="dashboard-container">
    
    <h1 class="animate-fade-up">Panel Admin</h1>
    <p class="subtitle animate-fade-up delay-1">
      Pusat pengelolaan data master dan konfigurasi sistem PKL.
    </p>

    <div class="user-identity animate-fade-up delay-2">
        <div class="user-info">
            <p><strong>Operator:</strong> <span id="user-name"><?php echo $_SESSION['user']['username']; ?></span></p>
            <p><strong>Status:</strong> <span style="color: #2dd4bf;">Online</span></p>
        </div>
    </div>

    <div class="animate-fade-up delay-3">
      <h2 style="text-align: center; margin-top: 30px;">Data Master</h2>
      <div class="stat-grid">
          <div class="stat-card" >
              <h3><i class="fas fa-users" ></i> Total Siswa</h3>
              <p><?php echo $total_siswa; ?></p> 
          </div>
          <div class="stat-card" style="border-color: #f59e0b;">
              <h3 style="color: #f59e0b;"><i class="fas fa-chalkboard-teacher"></i> Pembimbing</h3>
              <p><?php echo $total_pembimbing; ?></p> 
          </div>
          <div class="stat-card" style="border-color: #ef4444;">
              <h3 style="color: #ef4444;"><i class="fas fa-building"></i> Perusahaan</h3>
              <p><?php echo $total_mitra; ?></p> 
          </div>
      </div>
    </div>

    <div class="action-buttons animate-fade-up delay-5" style="flex-wrap: wrap;">
        <a href="admin-users.php" class="btn-dashboard secondary">
            <i class="fas fa-user-plus"></i> User
        </a>
        <a href="admin-perusahaan.php" class="btn-dashboard secondary">
            <i class="fas fa-city"></i> Tambah Perusahaan
        </a>
    </div>
  </div>
</main>
</body>
</html>