<?php
session_start();
include "config.php";

// Inisialisasi variabel error agar tidak undefined
$error = "";

if (isset($_POST['login'])) {
    // Tangkap data & amankan input
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role     = mysqli_real_escape_string($conn, $_POST['role']);

    // Cek user di database
    $query = "SELECT * FROM users WHERE username='$username' AND role='$role'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);

        // Cek Password (sesuai database kamu yang belum di-hash)
        if ($password == $data['password']) {
            
            // Simpan session user
            $_SESSION['user'] = [
                'id_user'  => $data['id_users'],
                'username' => $data['username'],
                'role'     => $data['role']
            ];

            // Redirect sesuai role
            if ($role == 'admin') {
                header("Location: dashboard admin.php");
            } else if ($role == 'pembimbing') {
                header("Location: dashboard pembimbing.php");
            } else if ($role == 'wakasek') {
                header("Location: dashboard wakasekubin.php");
            } else if ($role == 'siswa') {
                header("Location: dashboard.php");
            }
            exit;
        } else {
            $error = "Password yang Anda masukkan salah!";
        }
    } else {
        $error = "User tidak ditemukan atau Role tidak sesuai!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
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
        <li><a href="register.php"><i class="fas fa-user-plus"></i> Register</a></li>
     </ul>
  </nav>
</header>

<main class="login-page">
  <div class="login-container">

    <?php if ($error != "") { ?>
        <div style="background-color: #fae6e6ff; border: 1px solid #ef4444; color: #b91c1c; padding: 10px; border-radius: 8px; margin-bottom: 20px; text-align: center; font-size: 0.9rem;">
            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
        </div>
    <?php } ?>

    <form action="" method="POST">
      <h2>Masukan Akun</h2>
      <p class="subtitle">Selamat datang kembali! Silakan login.</p>
    
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required placeholder="Masukkan Username">
      </div>
      
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required placeholder="Masukkan Password">
      </div>
      
      <div class="form-group">
        <label style="margin-bottom: 10px; display:block;">Login Sebagai:</label>
        <div class="role-selection">
            <input type="radio" id="role-siswa" name="role" value="siswa" checked>
            <label for="role-siswa">Siswa</label>

            <input type="radio" id="role-admin" name="role" value="admin">
            <label for="role-admin">Admin</label>
            
            <input type="radio" id="role-pembimbing" name="role" value="pembimbing">
            <label for="role-pembimbing">Pembimbing</label>
            
            <input type="radio" id="role-wakasek" name="role" value="wakasek">
            <label for="role-wakasek">Wakasek</label>
        </div>
      </div>

      <button type="submit" name="login" class="btn-login">Login</button>
      <p class="login-link">Belum punya akun? <a class="login-btn" href="register.php">Register di sini</a></p>
    </form>
    
    </div>
</main>
</body>
</html>