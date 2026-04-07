<?php
session_start();
include 'config.php';

// Cek Login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// FIX: Ambil ID User dengan penanganan error session key
if (isset($_SESSION['user']['id_users'])) {
    $id_user = $_SESSION['user']['id_users'];
} else {
    $id_user = isset($_SESSION['user']['id_user']) ? $_SESSION['user']['id_user'] : 0;
}

// FIX: Cari ID Siswa berdasarkan user yang login
// Ini mencegah Siswa A melihat data Siswa B
$query_siswa = mysqli_query($conn, "SELECT id_siswa FROM siswa WHERE id_users = '$id_user'");
$siswa = mysqli_fetch_assoc($query_siswa);

// Inisialisasi variabel laporan
$laporan = false;

if ($siswa) {
    $id_siswa = $siswa['id_siswa'];
    // Ambil laporan HANYA milik siswa tersebut (WHERE id_siswa = '$id_siswa')
    $laporan = mysqli_query($conn, "SELECT * FROM jurnalkegiatan WHERE id_siswa = '$id_siswa' ORDER BY tanggal DESC");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Daftar Laporan</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    
<header>
  <nav>
    <div class="logo">LAPORAN</div>
    <ul class="nav-links">
        <li><a href="dashboard.php"><i class="fa fa-th-large"></i> Dashboard</a></li>
        <li><a href="about.php"><i class="fas fa-info-circle"></i> About</a></li>
        <li><a href="laporan.php"><i class="fas fa-file-alt"></i> Isi Laporan</a></li>
        <li><a href="daftar-laporan.php" class="active"><i class="fas fa-list-alt"></i> Riwayat</a></li>
        <li><a href="contact.php"><i class="fas fa-envelope"></i> Contact</a></li>
        <li><a href="login.php" id="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
     </ul>
  </nav>
</header>

<main class="data-page">
    <div class="data-container animate-fade-up">
        <h1>Riwayat Laporan Kamu</h1>
        <div class="table-wrapper">
            <table id="laporan-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kegiatan</th>
                        <th>Status</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Cek apakah ada datanya
                    if($laporan && mysqli_num_rows($laporan) > 0) {
                        while($row = mysqli_fetch_assoc($laporan)) { 
                    ?>
                    <tr>
                        <td><?php echo date('d-m-Y', strtotime($row['tanggal'])); ?></td>
                        <td><?php echo htmlspecialchars($row['kegiatan']); ?></td>
                        <td>
                            <?php 
                            if($row['status'] == 'menunggu') {
                                echo '<span style="color:#f59e0b; font-weight:bold;"><i class="fas fa-clock"></i> Menunggu</span>';
                            } elseif($row['status'] == 'disetujui') {
                                echo '<span style="color:#2dd4bf; font-weight:bold;"><i class="fas fa-check"></i> Disetujui</span>';
                            } else {
                                echo '<span style="color:#ef4444; font-weight:bold;"><i class="fas fa-times"></i> Ditolak</span>';
                            }
                            ?>
                        </td>
                        <td><?php echo !empty($row['catatan_pembimbing']) ? htmlspecialchars($row['catatan_pembimbing']) : '-'; ?></td>
                    </tr>
                    <?php 
                        } 
                    } else {
                        // Tampilan jika belum ada laporan atau akun siswa belum terhubung
                        echo "<tr><td colspan='4' style='text-align:center; padding: 20px;'>Belum ada laporan yang dibuat.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
</body>
</html>