<?php
session_start();
include "config.php";

// Cek Akses Admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// --- PROSES TAMBAH USER ---
if (isset($_POST['tambah_user'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']); // Password belum di-hash sesuai request
    $role     = mysqli_real_escape_string($conn, $_POST['role']);

    // Cek username kembar
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Username sudah terpakai!');</script>";
    } else {
        // Insert ke tabel users
        // Kolom id_users biasanya auto_increment, jadi tidak perlu dimasukkan kalau settingan DB benar.
        // Tapi di skrip SQL kamu id_users tidak auto_increment. 
        // Kita pakai trik: cari ID terbesar + 1 (manual auto increment)
        $max_id = mysqli_fetch_assoc(mysqli_query($conn, "SELECT MAX(id_users) as max FROM users"));
        $new_id = $max_id['max'] + 1;

        $query = "INSERT INTO users (id_users, username, password, role) VALUES ('$new_id', '$username', '$password', '$role')";
        
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('User berhasil ditambahkan!'); window.location='admin-users.php';</script>";
        } else {
            echo "<script>alert('Gagal: " . mysqli_error($conn) . "');</script>";
        }
    }
}

// --- PROSES HAPUS USER (YANG SUDAH DIPERBAIKI) ---
if (isset($_GET['hapus'])) {
    $id_hapus = mysqli_real_escape_string($conn, $_GET['hapus']);
    
    // Cek apakah admin mau hapus diri sendiri?
    if ($id_hapus == $_SESSION['user']['id_user']) {
        echo "<script>alert('Anda tidak bisa menghapus akun sendiri!'); window.location='admin-users.php';</script>";
        exit;
    }

    // TAHAP 1: Hapus/Putuskan dulu data di tabel SISWA yang terikat dengan user ini
    // (Opsional: Kalau mau data siswanya tetap ada tapi akunnya hilang, pakai UPDATE siswa SET id_user=NULL ...)
    // Tapi di sini kita asumsikan kalau user dihapus, data profil siswa juga ikut dihapus biar bersih:
    $delete_siswa = mysqli_query($conn, "DELETE FROM siswa WHERE id_siswa = '$id_hapus'");

    // TAHAP 2: Baru hapus akun di tabel USERS
    $query_hapus = "DELETE FROM users WHERE id_users = '$id_hapus'";
    
    if (mysqli_query($conn, $query_hapus)) {
        echo "<script>alert('User dan data terkait berhasil dihapus.'); window.location='admin-users.php';</script>";
    } else {
        // Tampilkan pesan error asli dari MySQL biar ketahuan salahnya apa
        echo "<script>alert('Gagal menghapus! Error: " . mysqli_error($conn) . "'); window.location='admin-users.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola User - Admin PKL</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        .badge { padding: 5px 10px; border-radius: 50px; font-size: 0.8rem; font-weight: 600; }
        .badge-admin { background: #ef4444; color: white; }
        .badge-pembimbing { background: #f59e0b; color: white; } /* Ganti nama class biar sesuai role */
        .badge-siswa { background: #2dd4bf; color: white; }
        .badge-wakasek { background: #8b5cf6; color: white; }
        .header-action { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    </style>
</head>
<body>

<header>
    <nav>
        <div class="logo">MANAGE USER</div>
        <ul class="nav-links">
            <li><a href="dashboard admin.php"><i class="fa fa-th-large"></i> Dashboard</a></li>
            <li><a href="login.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>
</header>

<main class="data-page">
    <div class="data-container animate-fade-up">
        <div class="header-action">
            <div>
                <h1>Manajemen Pengguna</h1>
                <p class="subtitle" style="text-align: left; margin-bottom: 0;">Kelola akun login sistem.</p>
            </div>
            <button class="btn-simpan-laporan" onclick="openModal()" style="width: auto; margin-top: 0;">
                <i class="fas fa-plus"></i> Tambah User
            </button>
        </div>
        
        <div class="table-wrapper">
            <table id="laporan-table">
                <thead>
                    <tr>
                        <th>ID User</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Ambil data users
                    $result = mysqli_query($conn, "SELECT * FROM users ORDER BY id_users DESC");
                    while ($row = mysqli_fetch_assoc($result)) {
                        // Tentukan warna badge
                        $badgeClass = "badge-siswa";
                        if ($row['role'] == 'admin') $badgeClass = "badge-admin";
                        elseif ($row['role'] == 'pembimbing') $badgeClass = "badge-pembimbing";
                        elseif ($row['role'] == 'wakasek') $badgeClass = "badge-wakasek";
                    ?>
                    <tr>
                        <td><?php echo $row['id_users']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><span class="badge <?php echo $badgeClass; ?>"><?php echo ucfirst($row['role']); ?></span></td>
                        <td>
                            <a href="admin-users.php?hapus=<?php echo $row['id_users']; ?>" 
                               class="btn-download-row" 
                               style="background: #ef4444; display:inline-block; text-decoration:none;"
                               onclick="return confirm('Yakin ingin menghapus user ini?');">
                               <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="userModal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <h2>Tambah User Baru</h2>
            <form action="" method="POST">
                <div class="form-group">
                    <label>Username (untuk Login)</label>
                    <input type="text" name="username" required placeholder="Contoh: siswa2">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="text" name="password" required placeholder="Contoh: 123">
                </div>
                <div class="form-group">
                    <label>Role</label>
                    <select name="role" required>
                        <option value="siswa">Siswa</option>
                        <option value="pembimbing">Pembimbing</option>
                        <option value="wakasek">Wakasek</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div class="form-actions" style="text-align: right; margin-top: 20px;">
                    <button type="button" class="btn-dashboard secondary" onclick="closeModal()">Batal</button>
                    <button type="submit" name="tambah_user" class="btn-dashboard">Simpan</button>
                </div>
            </form>
        </div>
    </div>

</main>

<script>
    // Script sederhana untuk Modal
    function openModal() {
        document.getElementById('userModal').style.display = 'flex';
    }
    function closeModal() {
        document.getElementById('userModal').style.display = 'none';
    }
    // Tutup modal kalau klik di luar kotak
    window.onclick = function(event) {
        var modal = document.getElementById('userModal');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>
</body>
</html>