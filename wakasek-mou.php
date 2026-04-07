<?php
session_start();
include "config.php";

// Cek Login & Role Wakasek
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'wakasek') {
    header("Location: login.php");
    exit;
}

// --- PROSES UPDATE TANGGAL MOU ---
if (isset($_POST['update_mou'])) {
    $id_pt  = $_POST['id_perusahaan'];
    $mulai  = $_POST['tgl_mulai'];
    $akhir  = $_POST['tgl_akhir'];

    $query = "UPDATE perusahaan SET tgl_mulai_mou='$mulai', tgl_berakhir_mou='$akhir' WHERE id_perusahaan='$id_pt'";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Durasi MoU berhasil diperbarui!'); window.location='wakasek-mou.php';</script>";
    } else {
        echo "<script>alert('Gagal update!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola MoU - Wakasek Hubin</title>
    <link rel="stylesheet" href="style.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <style>
        /* --- STYLE KHUSUS TEMA GELAP (MENYESUAIKAN ADMIN) --- */
        
        /* Kotak Pencarian Gelap */
        .search-box {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }
        .search-box input {
            padding: 12px 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            width: 100%;
            max-width: 400px;
            font-family: 'Poppins', sans-serif;
            background: rgba(0, 0, 0, 0.2); /* Gelap Transparan */
            color: #fff;
        }
        .search-box input:focus {
            outline: none;
            border-color: #4a69ff;
        }

        /* Badge Status untuk Dark Mode */
        .badge { padding: 6px 12px; border-radius: 30px; font-size: 0.8rem; font-weight: 600; display: inline-block;}
        .badge-aktif { background: rgba(39, 174, 96, 0.2); color: #4ade80; border: 1px solid #27ae60; }
        .badge-expired { background: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid #ef4444; }
        .badge-null { background: rgba(255, 255, 255, 0.1); color: #94a3b8; }
        .badge-warning { background: rgba(245, 158, 11, 0.2); color: #fbbf24; border: 1px solid #f59e0b; }

        /* Tombol Aksi */
        .btn-action {
            background: rgba(74, 105, 255, 0.2);
            color: #8a9fff;
            border: 1px solid rgba(74, 105, 255, 0.3);
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.85rem;
        }
        .btn-action:hover {
            background: #4a69ff;
            color: white;
        }

        /* Modal Overlay */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.8);
            z-index: 9999;
            display: none;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(5px);
        }

        /* Kotak Modal Dark Mode */
        .modal-box {
            background: #1f2937; /* Warna Gelap */
            width: 90%;
            max-width: 450px;
            padding: 30px;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 25px rgba(74, 105, 255, 0.2);
            animation: slideIn 0.3s ease;
            color: white;
        }
        
        .modal-box h2 { color: #fff; margin-top: 0; margin-bottom: 20px; text-align: center;}
        .modal-box label { display: block; margin-bottom: 8px; font-weight: 600; color: #cbd5e1; }
        .modal-box input { 
            width: 100%; padding: 10px; margin-bottom: 15px;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: white;
        }
        
        .modal-buttons { text-align: right; margin-top: 20px; }
        .btn-cancel { background: transparent; color: #cbd5e1; padding: 10px 20px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.2); cursor: pointer; margin-right: 10px;}
        .btn-save { background: #4a69ff; color: white; padding: 10px 20px; border-radius: 8px; border: none; cursor: pointer;}
        .btn-save:hover { background: #6a89ff; }

        @keyframes slideIn {
            from { transform: translateY(-30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body>

<header>
    <nav>
        <div class="logo">HUBIN PANEL</div>
        <ul class="nav-links">
            <li><a href="dashboard wakasekubin.php"><i class="fa fa-th-large"></i> Dashboard</a></li>
            <li><a href="login.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>
</header>

<main class="data-page">
    <div class="data-container animate-fade-up">
        
        <h1>Daftar Kerjasama (MoU)</h1>
        <p class="subtitle">Monitoring masa berlaku kontrak industri mitra.</p>
        
        <div class="search-box">
            <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Cari nama perusahaan...">
        </div>

        <div class="table-wrapper">
            <table id="laporan-table"> 
                <thead>
                    <tr>
                        <th width="30%">Perusahaan</th>
                        <th>Periode Kontrak</th>
                        <th>Sisa Waktu</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $today = date('Y-m-d');
                    $query = mysqli_query($conn, "SELECT * FROM perusahaan ORDER BY tgl_berakhir_mou ASC");
                    
                    if(mysqli_num_rows($query) > 0) {
                        while ($row = mysqli_fetch_assoc($query)) {
                            $tgl_mulai = $row['tgl_mulai_mou'];
                            $tgl_akhir = $row['tgl_berakhir_mou'];
                            
                            // Logic Status
                            if ($tgl_mulai == NULL || $tgl_akhir == NULL) {
                                $status_badge = "<span class='badge badge-null'>Belum Ada MoU</span>";
                                $sisa_txt = "-";
                                $periode_txt = "Belum diatur";
                            } elseif ($tgl_akhir < $today) {
                                $status_badge = "<span class='badge badge-expired'>EXPIRED</span>";
                                $sisa_txt = "0 Hari";
                                $periode_txt = date('d/m/Y', strtotime($tgl_mulai)) . " - " . date('d/m/Y', strtotime($tgl_akhir));
                            } else {
                                $diff = strtotime($tgl_akhir) - strtotime($today);
                                $hari = floor($diff / (60 * 60 * 24));
                                
                                if ($hari < 30) {
                                    $status_badge = "<span class='badge badge-warning'>Segera Habis</span>";
                                } else {
                                    $status_badge = "<span class='badge badge-aktif'>AKTIF</span>";
                                }
                                $sisa_txt = "<strong style='color:#fff;'>$hari</strong> Hari lagi";
                                $periode_txt = date('d/m/Y', strtotime($tgl_mulai)) . " - " . date('d/m/Y', strtotime($tgl_akhir));
                            }
                    ?>
                    <tr>
                        <td>
                            <strong style="color:#fff; font-size:1rem;"><?php echo $row['nama_perusahaan']; ?></strong><br>
                            <small style="color:#aeb8c4;"><?php echo $row['bidang']; ?></small>
                        </td>
                        <td><?php echo $periode_txt; ?></td>
                        <td><?php echo $sisa_txt; ?></td>
                        <td><?php echo $status_badge; ?></td>
                        <td>
                            <button type="button" class="btn-action" 
                                onclick="openModal('<?php echo $row['id_perusahaan']; ?>', '<?php echo htmlspecialchars($row['nama_perusahaan'], ENT_QUOTES); ?>', '<?php echo $tgl_mulai; ?>', '<?php echo $tgl_akhir; ?>')">
                                <i class="fas fa-edit"></i> Perpanjang
                            </button>
                        </td>
                    </tr>
                    <?php 
                        } 
                    } else {
                        echo "<tr><td colspan='5' style='text-align:center;'>Belum ada data perusahaan.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>
</main>

<div id="mouModal" class="modal-overlay">
    <div class="modal-box">
        <h2>Perbarui Kontrak</h2>
        <p style="color:#cbd5e1; margin-bottom:20px; text-align:center;">
            Perusahaan: <strong id="modalPtName" style="color:#4a69ff;">-</strong>
        </p>

        <form action="" method="POST">
            <input type="hidden" name="id_perusahaan" id="modalIdPt">
            
            <div style="margin-bottom:15px;">
                <label>Tanggal Mulai Baru</label>
                <input type="date" name="tgl_mulai" id="modalMulai" required>
            </div>
            
            <div style="margin-bottom:15px;">
                <label>Tanggal Berakhir Baru</label>
                <input type="date" name="tgl_akhir" id="modalAkhir" required>
            </div>

            <div class="modal-buttons">
                <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
                <button type="submit" name="update_mou" class="btn-save">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Search Function (Disesuaikan dengan ID tabel baru)
    function filterTable() {
        let input = document.getElementById("searchInput");
        let filter = input.value.toUpperCase();
        let table = document.getElementById("laporan-table"); // Target ID Baru
        let tr = table.getElementsByTagName("tr");

        for (let i = 1; i < tr.length; i++) {
            let td = tr[i].getElementsByTagName("td")[0];
            if (td) {
                let txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }

    // Modal Script
    const modal = document.getElementById('mouModal');

    function openModal(id, nama, mulai, akhir) {
        document.getElementById('modalIdPt').value = id;
        document.getElementById('modalPtName').innerText = nama;
        document.getElementById('modalMulai').value = mulai;
        document.getElementById('modalAkhir').value = akhir;
        modal.style.display = 'flex';
    }

    function closeModal() {
        modal.style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            closeModal();
        }
    }
</script>

</body>
</html>