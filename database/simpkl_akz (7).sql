-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 07, 2026 at 05:04 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simpkl_akz`
--

-- --------------------------------------------------------

--
-- Table structure for table `jurnalbimbingan`
--

CREATE TABLE `jurnalbimbingan` (
  `pembimbingsekolah` varchar(21) DEFAULT NULL,
  `pembimbingperusahaan` varchar(21) DEFAULT NULL,
  `tanggalbimbingan` date NOT NULL,
  `materibimbingan` varchar(21) DEFAULT NULL,
  `catatanbimbingan` varchar(21) DEFAULT NULL,
  `id_pembimbingsekolah` int(21) NOT NULL,
  `id_pembimbingperusahaan` int(21) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jurnalkegiatan`
--

CREATE TABLE `jurnalkegiatan` (
  `id_jurnalkegiatan` int(11) NOT NULL,
  `id_users` int(25) NOT NULL,
  `id_siswa` int(25) NOT NULL,
  `tanggal` date NOT NULL,
  `kegiatan` text NOT NULL,
  `waktu` varchar(50) NOT NULL,
  `status` enum('menunggu','disetujui','ditolak') DEFAULT 'menunggu',
  `paraf_pembimbing` varchar(100) DEFAULT NULL,
  `catatan_pembimbing` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jurnalkegiatan`
--

INSERT INTO `jurnalkegiatan` (`id_jurnalkegiatan`, `id_users`, `id_siswa`, `tanggal`, `kegiatan`, `waktu`, `status`, `paraf_pembimbing`, `catatan_pembimbing`) VALUES
(1, 16, 15, '2025-11-26', 'Aku disuruh ngeprint ijazah mulyono', '17:41', 'disetujui', NULL, 'Mantap...... Asli gak itu ijazah'),
(2, 4, 1, '2025-11-26', 'Aku disuruh nyeduh kopi', '17:42', 'disetujui', NULL, 'Mantao itu diarahkan jadi barista kamu '),
(3, 19, 18, '2025-11-27', 'Aku keren', '18:22', 'disetujui', NULL, 'Gapeduli.'),
(4, 13, 12, '2025-11-27', 'test', '14:24', 'disetujui', NULL, 'nice...'),
(5, 19, 18, '2025-11-30', 'Besok Presentasi Omagad', '09:41', 'disetujui', NULL, 'iyalagi iyalagi'),
(7, 4, 1, '2025-11-30', 'Today is gonna be the day', '11:12', 'disetujui', NULL, 'YESIRRRRRRRRRRRRRRRR'),
(9, 24, 22, '2026-04-06', 'Pengenalan Lingkungan Pkl dan membuat Portofolio', '03:23', 'menunggu', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` int(21) NOT NULL,
  `kelas` varchar(21) NOT NULL,
  `jurusan` varchar(21) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `kelas`, `jurusan`) VALUES
(1, 'XII-K1', 'Rekayasa Perangkat Lu'),
(2, 'XII-L2', 'Listrik'),
(3, 'XII-L1', 'Listrik'),
(4, 'XII-K2', 'Komputer'),
(5, 'XII-K3', 'Komputer'),
(6, 'XII-M1', 'Mesin'),
(7, 'XII-M2', 'Mesin'),
(8, 'XII-M3', 'Mesin'),
(9, 'XII-L3', 'Listrik');

-- --------------------------------------------------------

--
-- Table structure for table `nilaikompetensi`
--

CREATE TABLE `nilaikompetensi` (
  `id_nilai` int(11) NOT NULL,
  `id_siswa` int(25) NOT NULL,
  `nilai_teknis` int(3) NOT NULL DEFAULT 0,
  `nilai_non_teknis` int(3) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nilaikompetensi`
--

INSERT INTO `nilaikompetensi` (`id_nilai`, `id_siswa`, `nilai_teknis`, `nilai_non_teknis`) VALUES
(1, 11, 85, 75),
(2, 13, 8, 7);

-- --------------------------------------------------------

--
-- Table structure for table `perusahaan`
--

CREATE TABLE `perusahaan` (
  `id_perusahaan` int(21) NOT NULL,
  `nama_perusahaan` varchar(21) NOT NULL,
  `alamat` varchar(21) NOT NULL,
  `profil_lembaga_industri` varchar(21) NOT NULL,
  `nomer_telepon` int(21) NOT NULL,
  `bidang` varchar(21) NOT NULL,
  `tgl_mulai_mou` date DEFAULT NULL,
  `tgl_berakhir_mou` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `perusahaan`
--

INSERT INTO `perusahaan` (`id_perusahaan`, `nama_perusahaan`, `alamat`, `profil_lembaga_industri`, `nomer_telepon`, `bidang`, `tgl_mulai_mou`, `tgl_berakhir_mou`) VALUES
(322, 'PT. Kuul Jaya', 'JL.Ukuk', 'Keren', 8767567, 'Otomotif', '2025-09-03', '2026-06-11'),
(323, 'PT. Kururing', 'JL.Kururing No 69', 'Mantap', 34928567, 'IT', '2025-01-01', '2026-05-01'),
(325, 'PT.Kobo Klason', 'JL. Kobokanaer', 'Keren', 876554, 'Otomotif', NULL, NULL),
(326, 'PT. Kembang Jaya', 'JL>Sukses', 'Suksekj', 234234, 'Keuangan', NULL, NULL),
(327, 'PT. Harapan Pemuda', 'JL. Hariyanto NO.87', 'Perusahaan Berbidang ', 989832487, 'Sastra', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` int(25) NOT NULL,
  `nama_siswa` varchar(21) NOT NULL,
  `nis` int(21) DEFAULT NULL,
  `agama` varchar(21) NOT NULL,
  `ttl` date NOT NULL,
  `jenis_kelamin` enum('laki-laki','perempuan') NOT NULL,
  `g_darah` varchar(21) NOT NULL,
  `catatan_kesehatan` varchar(21) NOT NULL,
  `no_kontak` int(21) NOT NULL,
  `Alamat` varchar(21) NOT NULL,
  `kelas` int(20) NOT NULL,
  `id_users` int(11) NOT NULL,
  `id_kelas` int(21) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `nama_siswa`, `nis`, `agama`, `ttl`, `jenis_kelamin`, `g_darah`, `catatan_kesehatan`, `no_kontak`, `Alamat`, `kelas`, `id_users`, `id_kelas`) VALUES
(1, 'Ahmad', 123456, '', '0000-00-00', 'laki-laki', '', '', 0, '', 0, 4, 1),
(10, 'kobo klakson', 1234234, '', '0000-00-00', 'laki-laki', '', '', 0, '', 0, 10, 3),
(11, 'as', 123123, '', '0000-00-00', 'laki-laki', '', '', 0, '', 0, 0, 3),
(12, 'Airani iofi', 123123, '', '0000-00-00', 'laki-laki', '', '', 0, '', 0, 13, 1),
(13, 'Kanaeru', 23212, '', '0000-00-00', 'laki-laki', '', '', 0, '', 0, 14, 3),
(14, 'aku', 4563456, '', '0000-00-00', 'laki-laki', '', '', 0, '', 0, 15, 2),
(16, 'Supri', 2147483647, '', '0000-00-00', 'laki-laki', '', '', 0, '', 0, 17, 5),
(18, 'P. Reine', 23563245, '', '0000-00-00', 'laki-laki', '', '', 0, '', 0, 19, 6),
(19, 'Sari Cinta Alam Indah', 1267534, '', '0000-00-00', 'laki-laki', '', '', 0, '', 0, 20, 7),
(20, 'Akuy Andri', 234562345, '', '0000-00-00', 'laki-laki', '', '', 0, '', 0, 21, 1),
(21, 'Kanaeru', 433288498, '', '0000-00-00', 'laki-laki', '', '', 0, '', 0, 23, 4),
(22, 'Aress G. Gunandhika', 22457637, '', '0000-00-00', 'laki-laki', '', '', 0, '', 0, 24, 5);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_users` int(11) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` enum('admin','wakasek','pembimbing','siswa') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_users`, `username`, `password`, `role`) VALUES
(1, 'Admin', '123', 'admin'),
(3, 'Sutisna', '123', 'pembimbing'),
(4, 'Ahmad', '123', 'siswa'),
(5, 'Kukang', '123', 'wakasek'),
(6, 'Eko', '123', 'siswa'),
(7, 'Ares', '123', 'pembimbing'),
(8, 'parto', '123', 'siswa'),
(10, 'Kobokan', '123', 'siswa'),
(13, 'iofi', '123', 'siswa'),
(14, 'kobo', '123', 'siswa'),
(16, 'moona', '123', 'siswa'),
(18, 'Jaja', '123', 'siswa'),
(19, 'Reine', '123', 'siswa'),
(20, 'Sari', '123', 'siswa'),
(21, 'Akuy', '123', 'siswa'),
(22, 'wako', '123', 'wakasek'),
(23, 'Kan', '123', 'siswa'),
(24, 'Aresaja', '123', 'siswa');

-- --------------------------------------------------------

--
-- Table structure for table `wali kelas`
--

CREATE TABLE `wali kelas` (
  `id_walikelas` int(21) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `no_kontak` int(21) NOT NULL,
  `alamat` varchar(21) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jurnalbimbingan`
--
ALTER TABLE `jurnalbimbingan`
  ADD PRIMARY KEY (`tanggalbimbingan`,`id_pembimbingsekolah`,`id_pembimbingperusahaan`);

--
-- Indexes for table `jurnalkegiatan`
--
ALTER TABLE `jurnalkegiatan`
  ADD PRIMARY KEY (`id_jurnalkegiatan`),
  ADD KEY `id_siswa` (`id_siswa`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`);

--
-- Indexes for table `nilaikompetensi`
--
ALTER TABLE `nilaikompetensi`
  ADD PRIMARY KEY (`id_nilai`),
  ADD UNIQUE KEY `unique_siswa` (`id_siswa`);

--
-- Indexes for table `perusahaan`
--
ALTER TABLE `perusahaan`
  ADD PRIMARY KEY (`id_perusahaan`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`,`id_users`,`id_kelas`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_users`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `wali kelas`
--
ALTER TABLE `wali kelas`
  ADD PRIMARY KEY (`id_walikelas`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jurnalkegiatan`
--
ALTER TABLE `jurnalkegiatan`
  MODIFY `id_jurnalkegiatan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `nilaikompetensi`
--
ALTER TABLE `nilaikompetensi`
  MODIFY `id_nilai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_users` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
