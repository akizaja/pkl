<?php
session_start();
include 'config.php';

// Cek login pembimbing
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'pembimbing') {
    header("Location: login.php");
    exit;
}

$today = date('Y-m-d');

// --- LOGIKA HITUNG ---

// 1. Total Siswa Bimbingan
$q_siswa = mysqli_query($conn, "SELECT COUNT(*) as total FROM siswa");
$total_siswa = mysqli_fetch_assoc($q_siswa)['total'];

// 2. Laporan Menunggu Validasi (Penting)
$q_pending = mysqli_query($conn, "SELECT COUNT(*) as total FROM jurnalkegiatan WHERE status = 'menunggu'");
$total_pending = mysqli_fetch_assoc($q_pending)['total'];

// 3. Laporan Disetujui (Buat perbandingan)
$q_acc = mysqli_query($conn, "SELECT COUNT(*) as total FROM jurnalkegiatan WHERE status = 'disetujui'");
$total_acc = mysqli_fetch_assoc($q_acc)['total'];

// 4. Siswa Belum Lapor Hari Ini (Fitur Monitoring)
$q_lazy = mysqli_query($conn, "
    SELECT COUNT(*) as total 
    FROM siswa 
    WHERE id_siswa NOT IN (
        SELECT id_siswa FROM jurnalkegiatan WHERE tanggal = '$today'
    )
");
$total_lazy = mysqli_fetch_assoc($q_lazy)['total'];

// 5. Tabel Tinjauan (5 Teratas yang statusnya menunggu)
$query_tinjauan = mysqli_query($conn, "
    SELECT j.*, s.nama_siswa, k.kelas 
    FROM jurnalkegiatan j
    JOIN siswa s ON j.id_siswa = s.id_siswa
    JOIN kelas k ON s.id_kelas = k.id_kelas
    WHERE j.status = 'menunggu'
    ORDER BY j.tanggal ASC
    LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Pembimbing</title>
  <link rel="stylesheet" href="style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

<header>
  <nav>
    <div class="logo">Laporan PKL Siswa</div>
    <ul class="nav-links">
        <li><a href="dashboard pembimbing.php" class="active"><i class="fa fa-th-large"></i> Dashboard</a></li>
        <li><a href="login.php" id="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
     </ul>
  </nav>
</header>

<main class="dashboard-style">
  <div class="dashboard-container">
    
    <h1 class="animate-fade-up">Halo, <span id="username"><?php echo $_SESSION['user']['username']; ?></span></h1>
    <div class="animate-fade-up delay-2">
      <h2 style="text-align: center; margin-top: 30px;">Ringkasan Aktivitas</h2>
      <div class="stat-grid">
          
          <div class="stat-card" style="border-color: #bc6bff;">
              <h3 style="color: #bc6bff;"><i class="fas fa-users"></i> Total Siswa</h3>
              <p><?php echo $total_siswa; ?></p> 
          </div>

          <div class="stat-card" style="border-color: #f59e0b;">
              <h3 style="color: #f59e0b;"><i class="fas fa-clock"></i> Menunggu</h3>
              <p><?php echo $total_pending; ?></p> 
          </div>

          <div class="stat-card" style="border-color: #10b981;">
              <h3 style="color: #10b981;"><i class="fas fa-check-circle"></i> Disetujui</h3>
              <p><?php echo $total_acc; ?></p> 
          </div>

          <div class="stat-card" style="border-color: #ef4444;">
              <h3 style="color: #ef4444;"><i class="fas fa-exclamation-circle"></i> Belum Lapor</h3>
              <p><?php echo $total_lazy; ?></p> 
              <small style="font-size: 0.7rem; color: #aeb8c4;">Hari Ini</small>
          </div>

      </div>
    </div>

    <div class="animate-fade-up delay-3" style="margin-top: 40px;">
        <h2>Perlu Tinjauan Segera</h2>
        
        <?php if(mysqli_num_rows($query_tinjauan) > 0) { ?>
        <div style="margin-top: 15px;">
            <table style="width: 100%; border-collapse: collapse; color: inherit;">
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($query_tinjauan)) { ?>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                        <td style="padding: 15px 5px;">
                            <div style="font-weight: bold; font-size: 1rem;"><?php echo $row['nama_siswa']; ?></div>
                            <div style="font-size: 0.85rem; color: #aeb8c4; margin-top: 4px;">
                                <i class="fas fa-layer-group"></i> <?php echo $row['kelas']; ?> &bull; 
                                <i class="far fa-calendar"></i> <?php echo $row['tanggal']; ?>
                            </div>
                        </td>
                        <td style="padding: 15px 5px; text-align: right;">
                             <a href="validasi-laporan.php?id=<?php echo $row['id_jurnalkegiatan']; ?>" class="btn-dashboard secondary" style="padding: 8px 15px; font-size: 0.8rem;">
                                <i class="fas fa-edit"></i> Periksa
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <?php } else { ?>
            <div style="text-align: center; padding: 40px; color: #aeb8c4; border: 1px dashed rgba(255,255,255,0.2); border-radius: 10px; margin-top: 15px;">
                <i class="fas fa-check-circle" style="font-size: 2rem; margin-bottom: 10px; color: #10b981;"></i>
                <p>Semua laporan aman! Tidak ada yang perlu divalidasi saat ini.</p>
            </div>
        <?php } ?>
    </div>
        <a href="data-siswa-pembimbing.php" class="btn-dashboard secondary">
            <i class="fas fa-users"></i> Data Siswa
        </a>
    </div>

  </div>
</main>
</body>
</html>