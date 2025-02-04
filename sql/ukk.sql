-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 04, 2025 at 12:35 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ukk`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_lengkap` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_general_ci DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`, `nama_lengkap`, `email`, `created_at`, `status`) VALUES
(1, 'admin', '$2y$10$KcODVoT/hIUpRauESFXotu3Zggvyn6jdIvkGUD.ZpEmZy3kvwNTKK', 'Admin Paizz', 'adminpaiz@mail.com', '2025-01-14 16:00:55', 'aktif');

-- --------------------------------------------------------

--
-- Table structure for table `detail_laporan`
--

CREATE TABLE `detail_laporan` (
  `id_detail` int NOT NULL,
  `kd_tr` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal` datetime NOT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_karyawan` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_laporan`
--

INSERT INTO `detail_laporan` (`id_detail`, `kd_tr`, `tanggal`, `keterangan`, `created_at`, `id_karyawan`) VALUES
(1, 'TR20250114397', '2025-01-14 23:22:19', 'aman', '2025-01-14 16:22:19', NULL),
(2, 'TR202501163449', '2025-01-17 01:37:26', 'aman', '2025-01-16 18:37:26', NULL),
(3, 'TR202501162386', '2025-01-30 08:04:38', 'Bagus\r\n', '2025-01-30 01:04:38', NULL),
(4, 'TR202501304562', '2025-01-30 09:06:20', 'Sehat', '2025-01-30 02:06:20', NULL),
(5, 'TR202501301642', '2025-01-30 11:23:24', 'Sehat', '2025-01-30 04:23:24', NULL),
(6, 'TR202501302072', '2025-01-30 11:53:37', 'Sehat dan sedang diberi fitamin', '2025-01-30 04:53:37', NULL),
(7, 'TR202501301642', '2025-01-31 07:58:49', 'Sudah diberi makan', '2025-01-31 00:58:49', NULL),
(8, 'TR202501311351', '2025-01-31 11:03:53', 'Hewan Sudah Masuk', '2025-01-31 04:03:53', NULL),
(9, 'TR202501311351', '2025-01-31 11:31:30', 'Imoet', '2025-01-31 04:31:30', NULL),
(10, 'TR202501312734', '2025-01-31 12:37:50', 'Hewan sudah masuk\r\n', '2025-01-31 05:37:50', NULL),
(11, 'TR202501312734', '2025-01-31 12:43:06', 'Sehat', '2025-01-31 05:43:06', NULL),
(12, 'TR202501312734', '2025-01-31 12:43:34', 'Diberi Vitamin', '2025-01-31 05:43:34', NULL),
(13, 'TR202501312734', '2025-01-31 12:44:03', 'di ambil penyewa', '2025-01-31 05:44:03', NULL),
(14, 'TR202502012847', '2025-02-01 15:57:07', 'Tugas diambil oleh karyawan', '2025-02-01 08:57:07', 1),
(15, 'TR202502012847', '2025-02-01 16:07:01', 'sehat ', '2025-02-01 09:07:01', 1),
(16, 'TR202502012847', '2025-02-01 16:09:23', 'Tugas telah selesai', '2025-02-01 09:09:23', 1),
(17, 'TR202502018222', '2025-02-01 19:41:57', 'Tugas diambil oleh karyawan', '2025-02-01 12:41:57', 1),
(18, 'TR202502014335', '2025-02-02 01:06:02', 'Tugas diambil oleh karyawan', '2025-02-01 18:06:02', 1),
(19, 'TR202502014335', '2025-02-02 01:07:35', 'Sudah sehat', '2025-02-01 18:07:35', 1),
(20, 'TR202502015169', '2025-02-02 01:59:14', 'Tugas diambil oleh karyawan', '2025-02-01 18:59:14', 3),
(21, 'TR202502015169', '2025-02-02 02:00:29', 'Sudah dimandikan, sudah di beri vitamin', '2025-02-01 19:00:29', 3),
(22, 'TR202502015169', '2025-02-02 23:07:51', 'Tugas telah selesai', '2025-02-02 16:07:51', 3),
(23, 'TR202502032830', '2025-02-03 07:21:45', 'Tugas diambil oleh karyawan', '2025-02-03 00:21:45', 3),
(24, 'TR202502034116', '2025-02-03 10:39:26', 'Tugas diambil oleh karyawan', '2025-02-03 03:39:26', 3),
(25, 'TR202502034116', '2025-02-03 10:40:03', 'Sudah mulai aktif', '2025-02-03 03:40:03', 3),
(26, 'TR202502034116', '2025-02-03 10:40:39', 'Diberi vitamin dsbg', '2025-02-03 03:40:39', 3),
(27, 'TR202502037688', '2025-02-03 14:48:10', 'Tugas diambil oleh karyawan', '2025-02-03 07:48:10', 3),
(28, 'TR202502038549', '2025-02-04 02:02:45', 'Pendaftaran:\nNama Hewan: Sarang\nCatatan: Tidak aktif', '2025-02-03 19:02:45', NULL),
(29, 'TR202502038549', '2025-02-04 02:08:08', 'Tugas diambil oleh karyawan', '2025-02-03 19:08:08', 9),
(30, 'TR202502038549', '2025-02-04 02:09:02', 'Sudah di kasih vitamin', '2025-02-03 19:09:02', 9),
(31, 'TR202502031652', '2025-02-04 02:11:14', 'Pendaftaran:\nNama Hewan: Kiko\nCatatan: Tidak aktif seperti biasanya', '2025-02-03 19:11:14', NULL),
(32, 'TR202502031652', '2025-02-04 02:11:46', 'Tugas diambil oleh karyawan', '2025-02-03 19:11:46', 9),
(33, 'TR202502031652', '2025-02-04 02:18:23', 'Tugas telah selesai', '2025-02-03 19:18:23', 9),
(34, 'TR202502038549', '2025-02-04 02:18:42', 'Tugas telah selesai', '2025-02-03 19:18:42', 9),
(35, 'TR202502034990', '2025-02-04 00:00:00', 'Pendaftaran:\nNama Hewan: Nita\nCatatan: Tidak aktif', '2025-02-03 19:22:33', NULL),
(36, 'TR202502034990', '2025-02-04 02:22:52', 'Tugas diambil oleh karyawan', '2025-02-03 19:22:52', 9),
(37, 'TR202502034990', '2025-02-03 00:00:00', 'Tugas telah selesai', '2025-02-03 19:23:24', 9),
(38, 'TR202502033798', '2025-02-04 00:00:00', 'Pendaftaran:\nNama Hewan: Roki\nCatatan: Tidak aktif seperti biasanya', '2025-02-03 19:33:20', NULL),
(39, 'TR202502033798', '2025-02-04 02:33:49', 'Tugas diambil oleh karyawan', '2025-02-03 19:33:49', 10),
(40, 'TR202502033798', '2025-02-08 00:00:00', 'Diberi vitamin', '2025-02-03 19:34:40', 10),
(41, 'TR202502033798', '2025-02-11 00:00:00', 'sudah mulai aktiv', '2025-02-03 19:34:51', 10),
(42, 'TR202502033798', '2025-02-15 00:00:00', 'Sudah sempurna dan besok bisa di ambil', '2025-02-03 19:35:08', 10),
(43, 'TR202502033798', '2025-02-03 00:00:00', 'Tugas telah selesai', '2025-02-03 19:36:17', 10),
(44, 'TR202502048490', '2025-02-04 00:00:00', 'Pendaftaran:\nNama Hewan: Alung\nCatatan: lemas dan tidak aktif ', '2025-02-04 00:16:07', NULL),
(45, 'TR202502048490', '2025-02-04 07:17:23', 'Tugas diambil oleh karyawan', '2025-02-04 00:17:23', 10);

-- --------------------------------------------------------

--
-- Table structure for table `dokumentasi`
--

CREATE TABLE `dokumentasi` (
  `id_dokumentasi` int NOT NULL,
  `kd_tr` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_file` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `jenis_file` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `url_file` text COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_karyawan` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dokumentasi`
--

INSERT INTO `dokumentasi` (`id_dokumentasi`, `kd_tr`, `nama_file`, `jenis_file`, `url_file`, `created_at`, `id_karyawan`) VALUES
(1, 'TR20250114397', 'WhatsApp Image 2025-01-13 at 10.13.25_c6d6a076.jpg', 'image/jpeg', 'uploads/dokumentasi/67868f3098f21.jpg', '2025-01-14 16:22:08', NULL),
(3, 'TR202501163449', 'Screenshot 2025-01-30 072306.png', 'image/png', 'uploads/dokumentasi/679acfcc0cfd6_1738198988.png', '2025-01-30 01:03:08', NULL),
(4, 'TR202501162386', 'Screenshot 2025-01-30 072306.png', 'image/png', 'uploads/dokumentasi/679ad0191e86c_1738199065.png', '2025-01-30 01:04:25', NULL),
(6, 'TR202501304562', 'miyak.jpg', 'image/jpeg', 'uploads/dokumentasi/679afd1064a1b_1738210576.jpg', '2025-01-30 04:16:16', NULL),
(7, 'TR202501301642', 'miyak.jpg', 'image/jpeg', 'uploads/dokumentasi/679afeaadddaf.jpg', '2025-01-30 04:23:06', NULL),
(8, 'TR202501302072', 'Burung kacer.jpg', 'image/jpeg', 'uploads/dokumentasi/679b06287880f.jpg', '2025-01-30 04:55:04', NULL),
(9, 'TR202501302072', 'download.jpg', 'image/jpeg', 'uploads/dokumentasi/679b06490a984.jpg', '2025-01-30 04:55:37', NULL),
(13, 'TR202501311351', 'imgp.jpeg', 'image/jpeg', 'uploads/dokumentasi/679c520d5d89f.jpeg', '2025-01-31 04:31:09', NULL),
(14, 'TR202501311351', 'imgp (1).jpeg', 'image/jpeg', 'uploads/dokumentasi/679c521268dfd.jpeg', '2025-01-31 04:31:14', NULL),
(15, 'TR202501303748', 'imgp (3).jpeg', 'image/jpeg', 'uploads/dokumentasi/679c52f1bbbfc.jpeg', '2025-01-31 04:34:57', NULL),
(16, 'TR202501303748', 'imgp (4).jpeg', 'image/jpeg', 'uploads/dokumentasi/679c52f618784.jpeg', '2025-01-31 04:35:02', NULL),
(17, 'TR202501303748', 'imgp (2).jpeg', 'image/jpeg', 'uploads/dokumentasi/679c52fab6e85.jpeg', '2025-01-31 04:35:06', NULL),
(18, 'TR202501312734', 'imgp (5).jpeg', 'image/jpeg', 'uploads/dokumentasi/679c62cb74930.jpeg', '2025-01-31 05:42:35', NULL),
(19, 'TR202501312734', 'imgp (6).jpeg', 'image/jpeg', 'uploads/dokumentasi/679c62d01bd9f.jpeg', '2025-01-31 05:42:40', NULL),
(20, 'TR202501312734', 'imgp (7).jpeg', 'image/jpeg', 'uploads/dokumentasi/679c62d4be7e3.jpeg', '2025-01-31 05:42:44', NULL),
(21, 'TR202502012847', 'imgp (7).jpeg', 'image/jpeg', 'uploads/dokumentasi/679de42771630_1738400807.jpeg', '2025-02-01 09:06:47', 1),
(22, 'TR202502014335', 'imgp (1).jpeg', 'image/jpeg', 'uploads/dokumentasi/679e62ce0e100_1738433230.jpeg', '2025-02-01 18:07:10', 1),
(23, 'TR202502014335', 'imgp.jpeg', 'image/jpeg', 'uploads/dokumentasi/679e62dc9c90b_1738433244.jpeg', '2025-02-01 18:07:24', 1),
(24, 'TR202502015169', 'imgp.jpeg', 'image/jpeg', 'uploads/dokumentasi/679e6f2b8835f_1738436395.jpeg', '2025-02-01 18:59:55', 3),
(25, 'TR202502015169', 'imgp (1).jpeg', 'image/jpeg', 'uploads/dokumentasi/679e6f358f333_1738436405.jpeg', '2025-02-01 19:00:05', 3),
(26, 'TR202502034116', 'imgp (7).jpeg', 'image/jpeg', 'uploads/dokumentasi/67a03ac8270c6_1738554056.jpeg', '2025-02-03 03:40:56', 3),
(27, 'TR202502034116', 'imgp (6).jpeg', 'image/jpeg', 'uploads/dokumentasi/67a03ad0e38c9_1738554064.jpeg', '2025-02-03 03:41:04', 3),
(28, 'TR202502033798', 'imgp (7).jpeg', 'image/jpeg', 'uploads/dokumentasi/67a11a7d18c41_1738611325.jpeg', '2025-02-03 19:35:25', 10),
(29, 'TR202502033798', 'imgp (5).jpeg', 'image/jpeg', 'uploads/dokumentasi/67a11a891092b_1738611337.jpeg', '2025-02-03 19:35:37', 10),
(30, 'TR202502033798', 'imgp (6).jpeg', 'image/jpeg', 'uploads/dokumentasi/67a11a960c3d0_1738611350.jpeg', '2025-02-03 19:35:50', 10);

-- --------------------------------------------------------

--
-- Table structure for table `karyawan`
--

CREATE TABLE `karyawan` (
  `id_karyawan` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_lengkap` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `no_telp` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_general_ci,
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_general_ci DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `karyawan`
--

INSERT INTO `karyawan` (`id_karyawan`, `username`, `password`, `nama_lengkap`, `email`, `no_telp`, `alamat`, `status`, `created_at`) VALUES
(1, 'karyawan1', '$2y$10$XpNMhbs/kect93T2M3vfeOFS2Pi1P/L22ayfaHLftLbc6NqoMUDjG', 'Ade Rohimat', 'karyawan1@mail.com', '082257004434', 'Jl. Karyawan No. 1', 'aktif', '2025-01-31 19:23:39'),
(3, 'karyawan2', '$2y$10$xPFDkd8vlnMCClUulCc9t.h21zvgxNYQeUBRAqvdzgWLh7ZSFRCMq', 'Deny Sumargo', 'densu@gmail.com', '08258205825', 'Jl Densu', 'aktif', '2025-02-01 18:32:11'),
(7, 'karyawan3', '$2y$10$iz4Z29Qi6qsURAdYIy5iGuePtSGP/mIeg/jDsMg6yTHLO6FC.V3YC', 'Saipudin', 'saipudin@mail.com', '089764322', 'Jenangan', 'nonaktif', '2025-02-02 07:32:11'),
(8, 'karyawan4', '$2y$10$ub9gJXZWeVtuw8mCAgU5Ke4ZB0vtLYltKH.MUQ..cyJSg2aqci6hG', 'Dodit M', 'dodit@gmail.com', '082257004434', 'Jl Raya Ponorogo-Pacitan No 3', 'aktif', '2025-02-03 06:29:21'),
(9, 'karyawan5', '$2y$10$naGNECFhzszbWB74BckC.uTp0wcCTLhwqM4CI4KZWGrDQa4y3utV.', 'Surapto', 'surapto@mail.com', '082257004434', 'Jl Niken Gandini Jenangan , Ponorogo', 'aktif', '2025-02-03 19:07:43'),
(10, 'karyawan6', '$2y$10$8PJEnNUwePeDGdg.3mARR.Egzn2d/m6XZHqUK42fOu/fD0l7YRqEi', 'Paiz', 'paiz@gmail.com', '082257004434', 'Jl Raya Ponorogo Pacitan , Balong , Ponorogo', 'aktif', '2025-02-03 19:32:19');

-- --------------------------------------------------------

--
-- Table structure for table `kategori_hewan`
--

CREATE TABLE `kategori_hewan` (
  `id_kategori` int NOT NULL,
  `nama_kategori` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci,
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_general_ci DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori_hewan`
--

INSERT INTO `kategori_hewan` (`id_kategori`, `nama_kategori`, `deskripsi`, `status`, `created_at`) VALUES
(1, 'Kucing', 'Kategori untuk semua jenis kucing peliharaan', 'aktif', '2025-02-03 18:35:04'),
(2, 'Anjing', 'Kategori untuk semua jenis anjing peliharaan', 'aktif', '2025-02-03 18:35:04'),
(3, 'Burung', 'Kategori untuk semua jenis burung peliharaan', 'aktif', '2025-02-03 18:35:04'),
(4, 'Hamster', 'Kategori untuk hamster dan tikus hias', 'aktif', '2025-02-03 18:35:04'),
(6, 'Kelinci', 'Kategori untuk semua jenis kelinci', 'aktif', '2025-02-03 18:35:04');

-- --------------------------------------------------------

--
-- Table structure for table `paket`
--

CREATE TABLE `paket` (
  `id_paket` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_paket` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paket`
--

INSERT INTO `paket` (`id_paket`, `nama_paket`, `harga`, `deskripsi`, `created_at`) VALUES
('A', 'Paket Basic', '100000.00', 'Paket penitipan dasar asli', '2025-01-14 15:39:04'),
('B', 'Paket Standard', '150000.00', 'Paket penitipan standar dengan grooming', '2025-01-14 15:39:04'),
('C', 'Paket Premium', '250000.00', 'Paket penitipan premium dengan grooming dan perawatan khusus', '2025-01-14 15:39:04'),
('D', 'Paket Super Premium', '300000.00', 'gg', '2025-01-15 17:50:02'),
('E', 'Paket Jutawan', '350000.00', 'Yang Kaya Aja', '2025-01-18 05:28:47'),
('F', 'Paket Milyuner', '450000.00', 'Yang Kaya Aja', '2025-01-18 05:29:14');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `kd_tr` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `id_admin` int DEFAULT NULL,
  `id_paket` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_kategori` int DEFAULT NULL,
  `tgl_transaksi` date NOT NULL,
  `tgl_awal` date NOT NULL,
  `tgl_akhir` date NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `status` enum('pending','proses','selesai','diambil') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_user` int DEFAULT NULL,
  `id_karyawan` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`kd_tr`, `id_admin`, `id_paket`, `id_kategori`, `tgl_transaksi`, `tgl_awal`, `tgl_akhir`, `total_harga`, `status`, `created_at`, `id_user`, `id_karyawan`) VALUES
('TR20250114397', 1, 'A', 1, '2025-01-14', '2025-01-14', '2025-01-18', '500000.00', 'selesai', '2025-01-14 16:21:50', NULL, NULL),
('TR202501162386', NULL, 'A', 1, '2025-01-16', '2025-01-16', '2025-01-20', '500000.00', 'selesai', '2025-01-16 16:58:02', 1, NULL),
('TR202501163449', NULL, 'D', 1, '2025-01-17', '2025-01-27', '2025-01-31', '1500000.00', 'selesai', '2025-01-16 17:08:40', 1, NULL),
('TR202501301642', NULL, 'F', 1, '2025-01-30', '2025-02-01', '2025-02-08', '3600000.00', 'proses', '2025-01-30 04:22:18', 1, NULL),
('TR202501302072', NULL, 'E', 1, '2025-01-30', '2025-01-30', '2025-02-03', '1750000.00', 'proses', '2025-01-30 04:52:50', 3, NULL),
('TR202501303748', NULL, 'A', 1, '2025-01-30', '2025-02-01', '2025-02-14', '1400000.00', 'proses', '2025-01-30 14:48:29', 4, NULL),
('TR202501304562', NULL, 'F', 1, '2025-01-30', '2025-01-31', '2025-02-10', '4950000.00', 'selesai', '2025-01-30 02:04:20', 1, NULL),
('TR202501311351', NULL, 'E', 1, '2025-01-31', '2025-03-01', '2025-03-04', '1400000.00', 'proses', '2025-01-31 03:05:34', 4, NULL),
('TR202501312734', NULL, 'D', 1, '2025-01-31', '2025-02-01', '2025-02-07', '2100000.00', 'selesai', '2025-01-31 05:37:11', 1, NULL),
('TR202502012847', NULL, 'C', 1, '2025-02-01', '2025-02-05', '2025-02-14', '2500000.00', 'selesai', '2025-02-01 08:56:52', 1, 1),
('TR202502014335', NULL, 'F', 1, '2025-02-02', '2025-02-04', '2025-02-10', '3150000.00', 'proses', '2025-02-01 18:05:30', 1, 1),
('TR202502015169', NULL, 'F', 1, '2025-02-02', '2025-02-02', '2025-02-24', '10350000.00', 'selesai', '2025-02-01 18:58:43', 5, 3),
('TR202502018222', NULL, 'A', 1, '2025-02-01', '2025-02-01', '2025-02-03', '300000.00', 'proses', '2025-02-01 12:41:16', 1, 1),
('TR202502031652', NULL, 'B', 1, '2025-02-04', '2025-02-05', '2025-02-10', '900000.00', 'selesai', '2025-02-03 19:11:14', 1, 9),
('TR202502032830', NULL, 'F', 1, '2025-02-03', '2025-02-05', '2025-02-10', '2700000.00', 'proses', '2025-02-03 00:21:22', 1, 3),
('TR202502033666', NULL, 'C', 1, '2025-02-03', '2025-02-03', '2037-12-03', '99999999.99', 'pending', '2025-02-03 01:24:42', 1, NULL),
('TR202502033798', NULL, 'F', 2, '2025-02-04', '2025-02-07', '2025-02-16', '4500000.00', 'selesai', '2025-02-03 19:33:20', 6, 10),
('TR202502034116', NULL, 'F', 1, '2025-02-03', '2025-02-05', '2025-02-15', '4950000.00', 'proses', '2025-02-03 03:38:22', 1, 3),
('TR202502034570', NULL, 'F', 1, '2025-02-04', '2025-02-07', '2025-02-10', '1800000.00', 'pending', '2025-02-03 18:31:10', 1, NULL),
('TR202502034990', NULL, 'F', 2, '2025-02-04', '2025-02-05', '2025-02-09', '2250000.00', 'selesai', '2025-02-03 19:22:33', 1, 9),
('TR202502037688', NULL, 'E', 1, '2025-02-03', '2025-02-06', '2025-02-12', '2450000.00', 'proses', '2025-02-03 07:08:01', 1, 3),
('TR202502038549', NULL, 'C', 2, '2025-02-04', '2025-02-08', '2025-02-10', '750000.00', 'selesai', '2025-02-03 19:02:45', 1, 9),
('TR202502038887', NULL, 'F', NULL, '2025-02-04', '2025-02-14', '2025-02-21', '3600000.00', 'pending', '2025-02-03 18:54:56', 1, NULL),
('TR202502048490', NULL, 'F', 1, '2025-02-04', '2025-02-06', '2025-02-12', '3150000.00', 'proses', '2025-02-04 00:16:07', 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_lengkap` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `no_telp` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_general_ci,
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_general_ci DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `nama_lengkap`, `email`, `no_telp`, `alamat`, `status`, `created_at`) VALUES
(1, 'demo', '$2y$10$3bbVTjhWD/CwsawpaVIAuu.45ERHWJDN/MKGOKmrna2MDTSt1AVem', 'demo2', 'demo@mail.com', '08123456789', 'Jl demo', 'aktif', '2025-01-15 22:41:19'),
(2, 'abil', '$2y$10$WutwiF5VU8hF2Ksg.XewDebRynRHk/n6IM7w2hIop8RTbkV1erIvW', 'abil', 'abil@mail.com', '012318', 'balong', 'aktif', '2025-01-16 19:49:05'),
(3, 'user', '$2y$10$Al/IT3fodtJ9Nbeffo6ooemkcEzlYZQnxu3vsHxS2vquShrWZDcCW', 'user', 'user@gmail.com', '0258205825', 'Jl user', 'aktif', '2025-01-30 04:52:15'),
(4, 'deny', '$2y$10$JVPnFzNIVWLHk4gBiJllse5p3X1S8ZK90toxcsqZY3dFaX211Hdui', 'Deny Sugeng', 'densu@gmail.com', '085258552', 'jl deny', 'aktif', '2025-01-30 14:37:22'),
(5, 'presentasi', '$2y$10$R0bsBvInO71dLw9Mgbdk4u5d3k9jAkSavEFBa29bpkf/tzI5kUgdW', 'Presentasi', 'presentasi@mail.com', '082257004434', 'Jl presentasi', 'aktif', '2025-02-01 18:58:25'),
(6, 'nrfaiz', '$2y$10$/u89reuCAondzGfroZ0d0O5nOsB/ibF7Put3qdgCd6vWobQJFVizu', 'Abil Prima', 'nrfaiz@mail.com', '082257004434', 'Jl Raya Ponorogo Pacitan , Balong , Ponorogo', 'aktif', '2025-02-03 19:31:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `detail_laporan`
--
ALTER TABLE `detail_laporan`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `kd_tr` (`kd_tr`),
  ADD KEY `fk_karyawan_laporan` (`id_karyawan`),
  ADD KEY `idx_tanggal` (`tanggal`);

--
-- Indexes for table `dokumentasi`
--
ALTER TABLE `dokumentasi`
  ADD PRIMARY KEY (`id_dokumentasi`),
  ADD KEY `kd_tr` (`kd_tr`),
  ADD KEY `fk_karyawan_dokumentasi` (`id_karyawan`);

--
-- Indexes for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`id_karyawan`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `kategori_hewan`
--
ALTER TABLE `kategori_hewan`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `paket`
--
ALTER TABLE `paket`
  ADD PRIMARY KEY (`id_paket`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`kd_tr`),
  ADD KEY `id_admin` (`id_admin`),
  ADD KEY `id_paket` (`id_paket`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `fk_karyawan` (`id_karyawan`),
  ADD KEY `fk_kategori_hewan` (`id_kategori`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `detail_laporan`
--
ALTER TABLE `detail_laporan`
  MODIFY `id_detail` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `dokumentasi`
--
ALTER TABLE `dokumentasi`
  MODIFY `id_dokumentasi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `karyawan`
--
ALTER TABLE `karyawan`
  MODIFY `id_karyawan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `kategori_hewan`
--
ALTER TABLE `kategori_hewan`
  MODIFY `id_kategori` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_laporan`
--
ALTER TABLE `detail_laporan`
  ADD CONSTRAINT `detail_laporan_ibfk_1` FOREIGN KEY (`kd_tr`) REFERENCES `transaksi` (`kd_tr`),
  ADD CONSTRAINT `fk_detail_transaksi` FOREIGN KEY (`kd_tr`) REFERENCES `transaksi` (`kd_tr`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_karyawan_laporan` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id_karyawan`);

--
-- Constraints for table `dokumentasi`
--
ALTER TABLE `dokumentasi`
  ADD CONSTRAINT `dokumentasi_ibfk_1` FOREIGN KEY (`kd_tr`) REFERENCES `transaksi` (`kd_tr`),
  ADD CONSTRAINT `fk_karyawan_dokumentasi` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id_karyawan`);

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `fk_karyawan` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id_karyawan`),
  ADD CONSTRAINT `fk_kategori_hewan` FOREIGN KEY (`id_kategori`) REFERENCES `kategori_hewan` (`id_kategori`),
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`),
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`id_paket`) REFERENCES `paket` (`id_paket`),
  ADD CONSTRAINT `transaksi_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
