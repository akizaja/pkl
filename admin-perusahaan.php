<?php
session_start();
include "config.php";

// Cek Akses Admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// --- PROSES TAMBAH MITRA ---
if (isset($_POST['tambah_mitra'])) {
    $nama    = mysqli_real_escape_string($conn, $_POST['nama_perusahaan']);
    $alamat  = mysqli_real_escape_string($conn, $_POST['alamat']);
    $profil  = mysqli_real_escape_string($conn, $_POST['profil']);
    $telepon = mysqli_real_escape_string($conn, $_POST['telepon']);
    $bidang  = mysqli_real_escape_string($conn, $_POST['bidang']);

    // Generate ID Manual (Sesuai struktur DB kamu yang tidak auto increment)
    $max_id_query = mysqli_query($conn, "SELECT MAX(id_perusahaan) as max FROM perusahaan");
    $max_id = mysqli_fetch_assoc($max_id_query);
    $new_id = ($max_id['max'] ? $max_id['max'] + 1 : 1);

    $query = "INSERT INTO perusahaan (id_perusahaan, nama_perusahaan, alamat, profil_lembaga_industri, nomer_telepon, bidang) 
              VALUES ('$new_id', '$nama', '$alamat', '$profil', '$telepon', '$bidang')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Mitra Industri berhasil ditambahkan!'); window.location='admin-perusahaan.php';</script>";
    } else {
        echo "<script>alert('Gagal: " . mysqli_error($conn) . "');</script>";
    }
}

// --- PROSES EDIT MITRA ---
if (isset($_POST['update_mitra'])) {
    $id      = mysqli_real_escape_string($conn, $_POST['id_perusahaan']);
    $nama    = mysqli_real_escape_string($conn, $_POST['nama_perusahaan']);
    $alamat  = mysqli_real_escape_string($conn, $_POST['alamat']);
    $profil  = mysqli_real_escape_string($conn, $_POST['profil']);
    $telepon = mysqli_real_escape_string($conn, $_POST['telepon']);
    $bidang  = mysqli_real_escape_string($conn, $_POST['bidang']);

    $query = "UPDATE perusahaan SET 
              nama_perusahaan = '$nama', 
              alamat = '$alamat', 
              profil_lembaga_industri = '$profil', 
              nomer_telepon = '$telepon', 
              bidang = '$bidang' 
              WHERE id_perusahaan = '$id'";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data Mitra berhasil diperbarui!'); window.location='admin-perusahaan.php';</script>";
    } else {
        echo "<script>alert('Gagal Update: " . mysqli_error($conn) . "');</script>";
    }
}

// --- PROSES HAPUS MITRA ---
if (isset($_GET['hapus'])) {
    $id_hapus = mysqli_real_escape_string($conn, $_GET['hapus']);
    $query = "DELETE FROM perusahaan WHERE id_perusahaan = '$id_hapus'";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data Mitra berhasil dihapus.'); window.location='admin-perusahaan.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus! Error: " . mysqli_error($conn) . "'); window.location='admin-perusahaan.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Mitra - Admin PKL</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        .header-action { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        td { vertical-align: middle; }
        .text-truncate { max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: inline-block; }
        .btn-edit-row { background: #f59e0b; color: white; padding: 5px 10px; border-radius: 4px; text-decoration: none; margin-right: 5px; }
        .btn-delete-row { background: #ef4444; color: white; padding: 5px 10px; border-radius: 4px; text-decoration: none; border: none; cursor: pointer; }
    </style>
</head>
<body>

<header>
    <nav>
        <div class="logo">MANAGE MITRA</div>
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
                <h1>Data Mitra Industri</h1>
                <p class="subtitle" style="text-align: left; margin-bottom: 0;">Kelola daftar perusahaan tempat PKL.</p>
            </div>
            <button class="btn-simpan-laporan" onclick="openModal('tambah')" style="width: auto; margin-top: 0;">
                <i class="fas fa-plus"></i> Tambah Mitra
            </button>
        </div>
        
        <div class="table-wrapper">
            <table id="laporan-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Perusahaan</th>
                        <th>Bidang</th>
                        <th>Telepon</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $result = mysqli_query($conn, "SELECT * FROM perusahaan ORDER BY id_perusahaan DESC");
                    while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td style="font-weight: bold;"><?php echo $row['nama_perusahaan']; ?></td>
                        <td><span style= "padding: 4px 8px; border-radius: 4px; font-size: 0.85rem;"><?php echo $row['bidang']; ?></span></td>
                        <td><?php echo $row['nomer_telepon']; ?></td>
                        <td><span class="text-truncate" title="<?php echo $row['alamat']; ?>"><?php echo $row['alamat']; ?></span></td>
                        <td>
                            <a href="javascript:void(0)" 
                               class="btn-edit-row"
                               onclick="editMitra(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                               <i class="fas fa-edit"></i>
                            </a>
                            <a href="admin-perusahaan.php?hapus=<?php echo $row['id_perusahaan']; ?>" 
                               class="btn-delete-row" 
                               onclick="return confirm('Yakin ingin menghapus perusahaan ini?');">
                               <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="mitraModal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <h2 id="modalTitle">Tambah Perusahaan Baru</h2>
            <form action="" method="POST">
                <input type="hidden" name="id_perusahaan" id="form_id">

                <div class="form-group">
                    <label>Nama Perusahaan</label>
                    <input type="text" name="nama_perusahaan" id="form_nama" required placeholder="Contoh: PT. Teknologi Maju">
                </div>
                
                <div class="form-group" style="display: flex; gap: 10px;">
                    <div style="flex: 1;">
                        <label>Bidang Usaha</label>
                        <input type="text" name="bidang" id="form_bidang" required placeholder="Contoh: IT / Otomotif">
                    </div>
                    <div style="flex: 1;">
                        <label>No. Telepon</label>
                        <input type="number" name="telepon" id="form_telepon" required placeholder="021xxxx">
                    </div>
                </div>

                <div class="form-group">
                    <label>Profil Singkat</label>
                    <input type="text" name="profil" id="form_profil" placeholder="Deskripsi singkat perusahaan...">
                </div>

                <div class="form-group">
                    <label>Alamat Lengkap</label>
                    <textarea name="alamat" id="form_alamat" required rows="3" style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ddd;" placeholder="Masukkan alamat lengkap"></textarea>
                </div>

                <div class="form-actions" style="text-align: right; margin-top: 20px;">
                    <button type="button" class="btn-dashboard secondary" onclick="closeModal()">Batal</button>
                    <button type="submit" id="submitBtn" name="tambah_mitra" class="btn-dashboard">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    const modal = document.getElementById('mitraModal');
    
    function openModal(mode) {
        modal.style.display = 'flex';
        if(mode === 'tambah') {
            document.getElementById('modalTitle').innerText = 'Tambah Perusahaan Baru';
            document.getElementById('submitBtn').name = 'tambah_mitra';
            document.getElementById('submitBtn').innerText = 'Simpan';
            // Reset form
            document.getElementById('form_id').value = '';
            document.getElementById('form_nama').value = '';
            document.getElementById('form_bidang').value = '';
            document.getElementById('form_telepon').value = '';
            document.getElementById('form_profil').value = '';
            document.getElementById('form_alamat').value = '';
        }
    }

    function editMitra(data) {
        openModal('edit');
        document.getElementById('modalTitle').innerText = 'Edit Data Mitra';
        document.getElementById('submitBtn').name = 'update_mitra';
        document.getElementById('submitBtn').innerText = 'Update Data';
        
        // Isi form dengan data dari baris yang diklik
        document.getElementById('form_id').value = data.id_perusahaan;
        document.getElementById('form_nama').value = data.nama_perusahaan;
        document.getElementById('form_bidang').value = data.bidang;
        document.getElementById('form_telepon').value = data.nomer_telepon;
        document.getElementById('form_profil').value = data.profil_lembaga_industri;
        document.getElementById('form_alamat').value = data.alamat;
    }

    function closeModal() { modal.style.display = 'none'; }
    
    window.onclick = function(event) {
        if (event.target == modal) { closeModal(); }
    }
</script>
</body>
</html>