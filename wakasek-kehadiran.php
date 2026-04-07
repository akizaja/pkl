<?php
session_start();
include "config.php";

// Cek Login & Role Wakasek
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'wakasek') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Kehadiran - Wakasek</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        /* CSS KHUSUS TEMA GELAP */
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
            background: rgba(0, 0, 0, 0.2);
            color: white;
        }
        .search-box input:focus {
            outline: none;
            border-color: #4a69ff;
        }

        /* Badge Status Keren (Dark Mode Friendly) */
        .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; display: inline-flex; align-items: center; gap: 5px; }
        
        /* Warna disesuaikan agar kontras di background gelap */
        .status-fire { background: rgba(39, 174, 96, 0.2); color: #4ade80; border: 1px solid #27ae60; } 
        .status-ok { background: rgba(74, 105, 255, 0.2); color: #8a9fff; border: 1px solid #4a69ff; } 
        .status-warn { background: rgba(245, 158, 11, 0.2); color: #fbbf24; border: 1px solid #f59e0b; } 
        .status-danger { background: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid #ef4444; } 

        .avatar-circle {
            width: 35px; height: 35px;
            background: linear-gradient(135deg, #4a69ff, #bc6bff);
            color: white;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: bold;
            font-size: 0.9rem;
            box-shadow: 0 0 10px rgba(74, 105, 255, 0.4);
        }
    </style>
</head>
<body>

<header>
    <nav>
        <div class="logo">JURNAL SISWA</div>
        <ul class="nav-links">
            <li><a href="dashboard wakasekubin.php"><i class="fa fa-th-large"></i> Dashboard</a></li>
            <li><a href="login.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>
</header>

<main class="data-page"> <div class="data-container animate-fade-up">
        <h1>Monitoring Jurnal Siswa</h1>
        <p class="subtitle">Pantau keaktifan siswa mengisi jurnal kegiatan harian.</p>

        <div class="search-box">
            <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Cari nama siswa atau kelas...">
        </div>

        <div class="table-wrapper">
            <table id="laporan-table"> 
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Total Jurnal</th>
                        <th>Terakhir Mengisi</th>
                        <th>Status Keaktifan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT s.id_siswa, s.nama_siswa, k.kelas, 
                                     COUNT(j.id_jurnalkegiatan) as total_jurnal,
                                     MAX(j.tanggal) as terakhir_isi
                              FROM siswa s
                              LEFT JOIN kelas k ON s.id_kelas = k.id_kelas
                              LEFT JOIN jurnalkegiatan j ON s.id_siswa = j.id_siswa
                              GROUP BY s.id_siswa
                              ORDER BY k.kelas ASC, s.nama_siswa ASC";
                              
                    $result = mysqli_query($conn, $query);
                    $no = 1;
                    
                    if (!$result) {
                        echo "<tr><td colspan='6' style='text-align:center; color:#f87171;'>Error SQL: Pastikan database sudah diperbaiki (kolom id_siswa).</td></tr>";
                    } else {
                        if(mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $nama = $row['nama_siswa'];
                                $inisial = strtoupper(substr($nama, 0, 1));
                                $kelas = $row['kelas'];
                                $total = $row['total_jurnal'];
                                $last_date = $row['terakhir_isi'];

                                // LOGIKA STATUS
                                $today = new DateTime(); 
                                
                                if ($last_date) {
                                    $last_entry = new DateTime($last_date);
                                    $diff = $today->diff($last_entry)->days; 
                                    $tgl_indo = date('d/m/Y', strtotime($last_date));
                                } else {
                                    $diff = 999; 
                                    $tgl_indo = "-";
                                }

                                if ($diff <= 1 && $total > 0) {
                                    $status = "<span class='status-badge status-fire'><i class='fas fa-fire'></i> Sangat Aktif</span>";
                                } elseif ($diff <= 3 && $total > 0) {
                                    $status = "<span class='status-badge status-ok'><i class='fas fa-check-circle'></i> Aktif</span>";
                                } elseif ($diff <= 7 && $total > 0) {
                                    $status = "<span class='status-badge status-warn'><i class='fas fa-clock'></i> Jarang</span>";
                                } else {
                                    $status = "<span class='status-badge status-danger'><i class='fas fa-exclamation-triangle'></i> Bermasalah</span>";
                                }
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td>
                            <div style="display:flex; align-items:center; gap:10px;">
                                <div class="avatar-circle"><?php echo $inisial; ?></div>
                                <span style="font-weight:600; color:white;"><?php echo $nama; ?></span>
                            </div>
                        </td>
                        <td><?php echo $kelas; ?></td>
                        <td>
                            <strong style="font-size:1.1rem; color:#8a9fff;"><?php echo $total; ?></strong> 
                            <span style="font-size:0.8rem; color:#aeb8c4;">Log</span>
                        </td>
                        <td><?php echo $tgl_indo; ?></td>
                        <td><?php echo $status; ?></td>
                    </tr>
                    <?php 
                            } 
                        } else {
                            echo "<tr><td colspan='6' style='text-align:center;'>Belum ada data siswa.</td></tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<script>
function searchTable() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("searchInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("laporan-table");
    tr = table.getElementsByTagName("tr");

    for (i = 0; i < tr.length; i++) {
        tdName = tr[i].getElementsByTagName("td")[1];
        tdClass = tr[i].getElementsByTagName("td")[2];
        
        if (tdName || tdClass) {
            txtValueName = tdName.textContent || tdName.innerText;
            txtValueClass = tdClass.textContent || tdClass.innerText;
            
            if (txtValueName.toUpperCase().indexOf(filter) > -1 || txtValueClass.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }       
    }
}
</script>

</body>
</html>