<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'pembimbing') {
    header("Location: login.php");
    exit;
}

// Ambil semua data siswa beserta kelasnya
$query = mysqli_query($conn, "
    SELECT s.*, k.kelas as nama_kelas 
    FROM siswa s
    LEFT JOIN kelas k ON s.id_kelas = k.id_kelas
    ORDER BY k.kelas ASC, s.nama_siswa ASC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Data Siswa Bimbingan</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
<header>
  <nav>
    <div class="logo"></div>
    <ul class="nav-links">
        <li><a href="dashboard pembimbing.php"><i class="fa fa-th-large"></i> Dashboard</a></li>
        <li><a href="login.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
     </ul>
  </nav>
</header>

<main class="dashboard-style">
    <div class="dashboard-container animate-fade-up">
        <h1>Data Siswa Bimbingan</h1>
        <div style="margin-top: 30px; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; min-width: 600px;">
                <thead>
                    <tr style="border-bottom: 2px solid #334155; text-align: left;">
                        <th style="padding: 15px;">Nama Siswa</th>
                        <th style="padding: 15px;">Kelas</th>
                        <th style="padding: 15px;">No. Kontak</th>
                        <th style="padding: 15px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($query)) { ?>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                        <td style="padding: 15px; font-weight: bold;"><?php echo $row['nama_siswa']; ?></td>
                        <td style="padding: 15px;"><?php echo $row['nama_kelas']; ?></td>
                        <td style="padding: 15px;"><?php echo $row['no_kontak']; ?></td>
                        <td style="padding: 15px; text-align: center;">
                            <a href="input-nilai.php?id=<?php echo $row['id_siswa']; ?>" 
                               class="btn-dashboard secondary" 
                               style="padding: 8px 12px; font-size: 0.8rem; margin-right: 5px;">
                               <i class="fas fa-star"></i> Nilai
                            </a>
                            
                            <a href="laporan-siswa.php?id=<?php echo $row['id_siswa']; ?>" 
                               class="btn-dashboard" 
                               style="padding: 8px 12px; font-size: 0.8rem;">
                               <i class="fas fa-book"></i> Riwayat Laporan
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
</body>
</html>