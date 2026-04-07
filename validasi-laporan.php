<?php
session_start();
include 'config.php';

// Pastikan yang akses pembimbing
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'pembimbing') {
    die("Akses ditolak.");
}

$id_jurnal = $_GET['id'];
$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : ''; // 'terima' atau 'tolak'

// Jika form catatan disubmit
if (isset($_POST['submit_validasi'])) {
    $id = $_POST['id_jurnalkegiatan'];
    $status = $_POST['status_validasi']; // disetujui atau ditolak
    $catatan = $_POST['catatan'];
    
    // Update Database
    $query = "UPDATE jurnalkegiatan SET status='$status', catatan_pembimbing='$catatan' WHERE id_jurnalkegiatan='$id'";
    mysqli_query($conn, $query);
    
    echo "<script>alert('Laporan berhasil divalidasi!'); window.location='dashboard pembimbing.php';</script>";
    exit;
}

// Ambil detail laporan
$q = mysqli_query($conn, "SELECT * FROM jurnalkegiatan WHERE id_jurnalkegiatan='$id_jurnal'");
$data = mysqli_fetch_assoc($q);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Validasi Laporan</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet"/>
</head>
<body style="background: #0f172a; color: white; display: flex; justify-content: center; align-items: center; min-height: 100vh;">

<div class="stat-card" style="width: 100%; max-width: 500px; display: block;">
    <h2 style="margin-bottom: 20px;">Validasi Laporan</h2>
    
    <div style="background: rgba(255,255,255,0.05); padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <small style="color: #aeb8c4;">Kegiatan:</small>
        <p><?php echo $data['kegiatan']; ?></p>
        <small style="color: #aeb8c4; display:block; margin-top:10px;">Tanggal:</small>
        <p><?php echo $data['tanggal']; ?></p>
    </div>

    <form method="POST">
        <input type="hidden" name="id_jurnalkegiatan" value="<?php echo $data['id_jurnalkegiatan']; ?>">
        
        <label>Status:</label>
        <select name="status_validasi" style="width: 100%; padding: 10px; margin-bottom: 15px; background: #1e293b; color: white; border: 1px solid #334155; border-radius: 5px;">
            <option value="disetujui">Setujui (ACC)</option>
            <option value="ditolak">Tolak / Revisi</option>
        </select>

        <label>Catatan Pembimbing:</label>
        <textarea name="catatan" rows="3" placeholder="Berikan komentar..." style="width: 100%; padding: 10px; margin-bottom: 20px; background: #1e293b; color: white; border: 1px solid #334155; border-radius: 5px;"></textarea>
        
        <div style="display: flex; gap: 10px;">
            <button type="submit" name="submit_validasi" class="btn-dashboard" style="flex: 1; justify-content: center;">Simpan</button>
            <a href="dashboard pembimbing.php" class="btn-dashboard secondary" style="flex: 1; text-align: center; justify-content: center; text-decoration: none;">Batal</a>
        </div>
    </form>
</div>

</body>
</html>