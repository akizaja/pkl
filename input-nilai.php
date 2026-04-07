<?php
session_start();
include 'config.php';

// Cek akses pembimbing
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'pembimbing') {
    header("Location: login.php");
    exit;
}

$id_siswa = $_GET['id'];

// Ambil data siswa
$q_siswa = mysqli_query($conn, "SELECT nama_siswa FROM siswa WHERE id_siswa = '$id_siswa'");
$d_siswa = mysqli_fetch_assoc($q_siswa);

// Ambil nilai lama jika ada
$q_nilai = mysqli_query($conn, "SELECT * FROM nilaikompetensi WHERE id_siswa = '$id_siswa'");
$d_nilai = mysqli_fetch_assoc($q_nilai);

// Nilai default 0 jika belum ada
$nilai_teknis = isset($d_nilai['nilai_teknis']) ? $d_nilai['nilai_teknis'] : 0;
$nilai_non = isset($d_nilai['nilai_non_teknis']) ? $d_nilai['nilai_non_teknis'] : 0;

if (isset($_POST['simpan_nilai'])) {
    $n_teknis = $_POST['nilai_teknis'];
    $n_non = $_POST['nilai_non_teknis'];

    // Cek apakah data sudah ada?
    $cek = mysqli_query($conn, "SELECT id_nilai FROM nilaikompetensi WHERE id_siswa = '$id_siswa'");
    
    if (mysqli_num_rows($cek) > 0) {
        // UPDATE
        $sql = "UPDATE nilaikompetensi SET nilai_teknis='$n_teknis', nilai_non_teknis='$n_non' WHERE id_siswa='$id_siswa'";
    } else {
        // INSERT
        $sql = "INSERT INTO nilaikompetensi (id_siswa, nilai_teknis, nilai_non_teknis) VALUES ('$id_siswa', '$n_teknis', '$n_non')";
    }

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Nilai berhasil disimpan!'); window.location='data-siswa-pembimbing.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Input Nilai PKL</title>
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
    <div class="dashboard-container animate-fade-up" style="max-width: 600px; margin: 0 auto;">
        <h1 style="text-align: center;">Input Nilai PKL</h1>
        <p class="subtitle" style="text-align: center; margin-bottom: 30px;">
            Siswa: <strong style="color: #4a69ff;"><?php echo $d_siswa['nama_siswa']; ?></strong>
        </p>

        <form method="POST" action="">
            <div class="stat-card" style="display: block; margin-bottom: 20px;">
                <h3 style="margin-bottom: 15px; color: #f59e0b;"><i class="fas fa-cogs"></i> Kompetensi Teknis</h3>
                <p style="font-size: 0.9rem; margin-bottom: 10px; color: #aeb8c4;">Nilai kemampuan skill/praktek siswa di lapangan.</p>
                <input type="number" name="nilai_teknis" min="0" max="100" value="<?php echo $nilai_teknis; ?>" required 
                       style="width: 100%; padding: 15px; font-size: 1.5rem; text-align: center; border-radius: 8px; border: 2px solid #334155; background: #0f172a; color: white; font-weight: bold;">
            </div>

            <div class="stat-card" style="display: block; margin-bottom: 30px;">
                <h3 style="margin-bottom: 15px; color: #10b981;"><i class="fas fa-user-tie"></i> Non-Teknis (Sikap)</h3>
                <p style="font-size: 0.9rem; margin-bottom: 10px; color: #aeb8c4;">Nilai kedisiplinan, etika, dan komunikasi.</p>
                <input type="number" name="nilai_non_teknis" min="0" max="100" value="<?php echo $nilai_non; ?>" required 
                       style="width: 100%; padding: 15px; font-size: 1.5rem; text-align: center; border-radius: 8px; border: 2px solid #334155; background: #0f172a; color: white; font-weight: bold;">
            </div>

            <button type="submit" name="simpan_nilai" class="btn-dashboard" style="width: 100%; justify-content: center;">
                <i class="fas fa-save"></i> Simpan Nilai
            </button>
        </form>
    </div>
</main>
</body>
</html>