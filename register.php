<?php
include "config.php";

// Inisialisasi variabel pesan
$message = "";

if (isset($_POST['register'])) {
    // 1. Tangkap data dari form
    $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
    $nis      = mysqli_real_escape_string($conn, $_POST['nis']);
    $id_kelas = mysqli_real_escape_string($conn, $_POST['id_kelas']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm  = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // 2. Validasi sederhana
    if ($password != $confirm) {
        $message = "Password konfirmasi tidak cocok!";
    } else {
        // Cek username kembar
        $cek_user = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
        if (mysqli_num_rows($cek_user) > 0) {
            $message = "Username sudah terpakai, ganti yang lain.";
        } else {
            // --- MULAI PROSES PENYIMPANAN (TRANSAKSI 2 TABEL) ---
            
            // STEP A: Masukkan ke tabel USERS dulu
            $query_user = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', 'siswa')";
            
            if (mysqli_query($conn, $query_user)) {
                
                // === BAGIAN PENTING (KUNCI SUKSES) ===
                // Ambil ID dari user yang BARUSAN dibuat
                $id_user_baru = mysqli_insert_id($conn); 

                // STEP B: Masukkan ke tabel SISWA pakai ID user tadi ($id_user_baru)
                // PERBAIKAN: Dulu kamu pakai $id_users (variabel gak jelas), sekarang pakai $id_user_baru
                $query_siswa = "INSERT INTO siswa (nama_siswa, nis, id_kelas, id_users) 
                                VALUES ('$nama', '$nis', '$id_kelas', '$id_user_baru')";

                if (mysqli_query($conn, $query_siswa)) {
                    echo "<script>
                        alert('Registrasi Berhasil! Silakan Login.');
                        window.location = 'login.php';
                    </script>";
                } else {
                    // Kalau gagal simpan siswa, hapus user yang tadi dibuat biar database bersih
                    mysqli_query($conn, "DELETE FROM users WHERE id_users = '$id_user_baru'");
                    $message = "Gagal menyimpan data siswa: " . mysqli_error($conn);
                }
            } else {
                $message = "Gagal membuat akun: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register Siswa</title>
  <link rel="stylesheet" href="style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

<header>
  <nav>
    <div style="display: flex; align-items: center; gap: 15px;">
        <img src="img-stuff/1757683154177.jpg" alt="Logo" style="height: 60px; width: auto;">
    </div>
    <ul class="nav-links">
        <li><a href="home.php"><i class="fas fa-home"></i> Home</a></li>
        <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
     </ul>
  </nav>
</header>

<main class="login-page">
  <div class="login-container" style="max-width: 500px;"> 
    <?php if($message != "") { ?>
        <div style="background: #fee2e2; color: #991b1b; padding: 10px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
            <?php echo $message; ?>
        </div>
    <?php } ?>

    <form action="" method="POST">
      <h2>Daftar Akun Siswa</h2>
      <p class="subtitle">Lengkapi data diri untuk memulai PKL</p>

      <div class="form-group">
        <label>Nama Lengkap</label>
        <input type="text" name="nama" required placeholder="Contoh: Ahmad Fauzi">
      </div>

      <div class="form-group" style="display: flex; gap: 15px;">
          <div style="flex: 1;">
            <label>NIS</label>
            <input type="number" name="nis" required placeholder="123456">
          </div>
          <div style="flex: 1;">
            <label>Kelas</label>
            <select name="id_kelas" required>
                <option value="">-- Pilih Kelas --</option>
                <?php
                    // Ambil data kelas dari database biar dinamis
                    $sql_kelas = mysqli_query($conn, "SELECT * FROM kelas ORDER BY kelas ASC");
                    while($k = mysqli_fetch_array($sql_kelas)){
                        echo "<option value='".$k['id_kelas']."'>".$k['kelas']."</option>";
                    }
                ?>
            </select>
          </div>
      </div>

      <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.1); margin: 20px 0;">

      <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" required placeholder="Buat username unik">
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" required placeholder="Buat Password">
      </div>
      <div class="form-group">
        <label>Ulangi Password</label>
        <input type="password" name="confirm_password" required placeholder="Ketik ulang password">
      </div>

      <button type="submit" name="register" class="btn-login">Daftar Sekarang</button>
      <p class="login-link">Sudah punya akun? <a class="login-btn" href="login.php">Login di sini</a></p>
    </form>
  </div>
</main>
</body>
</html>