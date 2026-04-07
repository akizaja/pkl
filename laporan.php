<?php
session_start();
include 'config.php';

// 1. Cek Login & Role
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'siswa') {
    header("Location: login.php");
    exit;
}

// FIX: Pastikan key array session benar 'id_users'
if (isset($_SESSION['user']['id_users'])) {
    $id_user = $_SESSION['user']['id_users'];
} else {
    // Fallback jika session key beda (misal id_user) untuk mencegah error
    $id_user = isset($_SESSION['user']['id_user']) ? $_SESSION['user']['id_user'] : 0;
}

// 2. Ambil Data Siswa
// Query ini aman asalkan $id_user terisi dengan benar (tidak 0 atau kosong)
$query_siswa = mysqli_query($conn, "
    SELECT siswa.*, kelas.kelas as nama_kelas 
    FROM siswa 
    LEFT JOIN kelas ON siswa.id_kelas = kelas.id_kelas
    WHERE siswa.id_users = '$id_user'
");
$data_siswa = mysqli_fetch_assoc($query_siswa);

// Validasi jika data siswa tidak ditemukan (User login tapi belum didaftarkan di tabel siswa)
if (!$data_siswa) {
    echo "<script>alert('Akun Anda belum terhubung ke data Siswa. Silakan hubungi Admin/Pembimbing.'); window.location='dashboard.php';</script>";
    exit;
}

// 3. Proses Simpan
if (isset($_POST['simpan'])) {
    $id_siswa = $data_siswa['id_siswa'];
    $tanggal  = $_POST['tanggal'];
    $kegiatan = mysqli_real_escape_string($conn, $_POST['kegiatan']);
    $waktu    = date('H:i'); 

    $sql = "INSERT INTO jurnalkegiatan (id_users, id_siswa, tanggal, kegiatan, waktu, status) 
            VALUES ('$id_user', '$id_siswa', '$tanggal', '$kegiatan', '$waktu', 'menunggu')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Laporan berhasil dikirim ke Pembimbing!'); window.location='daftar-laporan.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Input Laporan PKL</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">LAPORAN</div>
            <ul class="nav-links">
    <ul class="nav-links">
        <li><a href="dashboard.php"><i class="fa fa-th-large"></i> Dashboard</a></li>
        <li><a href="about.php"><i class="fas fa-info-circle"></i> About</a></li>
        <li><a href="laporan.php" class="active"><i class="fas fa-file-alt"></i> Isi Laporan</a></li>
        <li><a href="daftar-laporan.php"><i class="fas fa-list-alt"></i> Riwayat</a></li>
        <li><a href="contact.php"><i class="fas fa-envelope"></i> Contact</a></li>
        <li><a href="login.php" id="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
     </ul>
            </ul>
        </nav>
    </header>

    <main class="dashboard-style">
        <div class="dashboard-container animate-fade-up">
            <h1><i class="fas fa-pen-fancy"></i> Tulis Laporan Harian</h1>
            <p class="subtitle">Catat kegiatanmu hari ini agar pembimbing bisa memantau perkembanganmu.</p>
            
            <form method="POST" action="" style="margin-top: 30px;">
                <div class="stat-card" style="margin-bottom: 20px; display: block; text-align: left;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div>
                            <label style="color: #aeb8c4; font-size: 0.9rem;">Nama Siswa</label>
                            <input type="text" value="<?php echo htmlspecialchars($data_siswa['nama_siswa']); ?>" readonly 
                                   style="width: 100%; background: transparent; border: none; color: white; font-weight: bold; font-size: 1.1rem; border-bottom: 1px solid #444; padding: 5px 0;">
                        </div>
                        <div>
                            <label style="color: #aeb8c4; font-size: 0.9rem;">Kelas</label>
                            <input type="text" value="<?php echo htmlspecialchars($data_siswa['nama_kelas']); ?>" readonly 
                                   style="width: 100%; background: transparent; border: none; color: white; font-weight: bold; font-size: 1.1rem; border-bottom: 1px solid #444; padding: 5px 0;">
                        </div>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display:block; margin-bottom: 10px; font-weight: 600;">Tanggal Kegiatan</label>
                    <input type="date" name="tanggal" required 
                           style="width: 100%; padding: 12px; background: #1e293b; border: 1px solid #334155; color: white; border-radius: 8px;">
                </div>

                <div class="form-group" style="margin-bottom: 30px;">
                    <label style="display:block; margin-bottom: 10px; font-weight: 600;">Deskripsi Kegiatan</label>
                    <textarea name="kegiatan" rows="6" required placeholder="Ceritakan apa yang kamu kerjakan hari ini..." 
                              style="width: 100%; padding: 12px; background: #1e293b; border: 1px solid #334155; color: white; border-radius: 8px; resize: vertical;"></textarea>
                </div>

                <button type="submit" name="simpan" class="btn-dashboard" style="width: 100%; border: none; cursor: pointer;">
                    <i class="fas fa-paper-plane"></i> Kirim Laporan
                </button>
            </form>
        </div>
    </main>
</body>
</html>