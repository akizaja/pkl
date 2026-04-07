<?php
session_start();
include 'config.php';

// 1. Cek Login & Role Pembimbing
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'pembimbing') {
    header("Location: login.php");
    exit;
}

// 2. Ambil ID Siswa dari URL
if (!isset($_GET['id'])) {
    header("Location: data-siswa-pembimbing.php");
    exit;
}
$id_siswa = mysqli_real_escape_string($conn, $_GET['id']);

// 3. Ambil Data Siswa (untuk Judul)
$query_siswa = mysqli_query($conn, "
    SELECT s.*, k.kelas as nama_kelas 
    FROM siswa s
    LEFT JOIN kelas k ON s.id_kelas = k.id_kelas
    WHERE s.id_siswa = '$id_siswa'
");
$siswa = mysqli_fetch_assoc($query_siswa);

// Jika siswa tidak ditemukan
if (!$siswa) {
    echo "<script>alert('Siswa tidak ditemukan!'); window.location='data-siswa-pembimbing.php';</script>";
    exit;
}

// 4. Ambil Riwayat Laporan Siswa Tersebut
$query_laporan = mysqli_query($conn, "
    SELECT * FROM jurnalkegiatan 
    WHERE id_siswa = '$id_siswa' 
    ORDER BY tanggal DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Riwayat Jurnal - <?php echo htmlspecialchars($siswa['nama_siswa']); ?></title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

<header>
  <nav>
    <div class="logo"></div>
    <ul class="nav-links">
        <li><a href="dashboard wakasekubin.php"><i class="fa fa-th-large"></i> Dashboard</a></li>
        <li><a href="data-siswa-pembimbing.php"><i class="fas fa-arrow-left"></i> Kembali</a></li>
     </ul>
  </nav>
</header>

<main class="dashboard-style">
    <div class="dashboard-container animate-fade-up">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div>
                <a href="data-siswa-pembimbing.php" style="color: #94a3b8; text-decoration: none; font-size: 0.9rem;">
                    <i class="fas fa-arrow-left"></i> Kembali ke Data Siswa
                </a>
                <h1 style="margin-top: 10px;">Jurnal Kegiatan Siswa</h1>
                <div style="color: #e2e8f0; margin-top: 5px;">
                    <span style="font-weight: bold; color: #38bdf8;"><?php echo htmlspecialchars($siswa['nama_siswa']); ?></span> 
                    &bull; <?php echo htmlspecialchars($siswa['nama_kelas']); ?>
                </div>
            </div>
            
            <div style="text-align: right; background: rgba(255,255,255,0.05); padding: 10px 20px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.1);">
                <span style="display: block; font-size: 0.8rem; color: #94a3b8;">Total Laporan</span>
                <span style="font-size: 1.5rem; font-weight: bold;"><?php echo mysqli_num_rows($query_laporan); ?></span>
            </div>
        </div>

        <div class="table-wrapper">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #334155; text-align: left;">
                        <th style="padding: 15px;">Tanggal</th>
                        <th style="padding: 15px; width: 40%;">Kegiatan</th>
                        <th style="padding: 15px;">Status</th>
                        <th style="padding: 15px;">Catatan Anda</th>
                        <th style="padding: 15px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if(mysqli_num_rows($query_laporan) > 0) {
                        while($row = mysqli_fetch_assoc($query_laporan)) { 
                    ?>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                        <td style="padding: 15px; vertical-align: top;">
                            <?php echo date('d-m-Y', strtotime($row['tanggal'])); ?>
                            <br>
                            <small style="color: #94a3b8;"><?php echo substr($row['waktu'], 0, 5); ?> WIB</small>
                        </td>
                        <td style="padding: 15px; vertical-align: top; line-height: 1.6;">
                            <?php echo nl2br(htmlspecialchars($row['kegiatan'])); ?>
                        </td>
                        <td style="padding: 15px; vertical-align: top;">
                            <?php 
                            if($row['status'] == 'menunggu') {
                                echo '<span style="color:#f59e0b; font-weight:bold; font-size: 0.9rem;"><i class="fas fa-clock"></i> Menunggu</span>';
                            } elseif($row['status'] == 'disetujui') {
                                echo '<span style="color:#2dd4bf; font-weight:bold; font-size: 0.9rem;"><i class="fas fa-check"></i> Disetujui</span>';
                            } else {
                                echo '<span style="color:#ef4444; font-weight:bold; font-size: 0.9rem;"><i class="fas fa-times"></i> Ditolak</span>';
                            }
                            ?>
                        </td>
                        <td style="padding: 15px; vertical-align: top; font-style: italic; color: #cbd5e1;">
                            <?php echo !empty($row['catatan_pembimbing']) ? htmlspecialchars($row['catatan_pembimbing']) : '<span style="color: #64748b;">- Tidak ada catatan -</span>'; ?>
                        </td>
                        <td style="padding: 15px; vertical-align: top; text-align: center;">
                            <?php if($row['status'] == 'menunggu') { ?>
                                <a href="validasi-laporan.php?id=<?php echo $row['id_jurnalkegiatan']; ?>" class="btn-dashboard secondary" style="padding: 5px 10px; font-size: 0.8rem;">
                                    Validasi
                                </a>
                            <?php } else { ?>
                                <span style="font-size: 1.2rem; color: #475569;"><i class="fas fa-lock"></i></span>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php 
                        } 
                    } else {
                        echo "<tr><td colspan='5' style='text-align:center; padding: 40px; color: #94a3b8;'>Siswa ini belum membuat laporan sama sekali.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>
</main>
</body>
</html>