-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 02, 2026 at 06:39 AM
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
-- Database: `keuangan`
--

-- --------------------------------------------------------

--
-- Table structure for table `aturan_pembayaran`
--

CREATE TABLE `aturan_pembayaran` (
  `id` int(11) NOT NULL,
  `nama_biaya` varchar(100) NOT NULL,
  `nominal` int(11) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `target` enum('semua','kelas','tingkat','siswa') NOT NULL DEFAULT 'semua',
  `target_id` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `aturan_pembayaran`
--

INSERT INTO `aturan_pembayaran` (`id`, `nama_biaya`, `nominal`, `keterangan`, `target`, `target_id`, `created_at`) VALUES
(9, 'PPDB', 50000, '', 'siswa', '1', '2026-08-31 06:42:37'),
(10, 'SPP Bulan Juli', 100000, '', 'semua', NULL, '2026-08-31 06:43:50');

-- --------------------------------------------------------

--
-- Table structure for table `dataguru`
--

CREATE TABLE `dataguru` (
  `id` int(5) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `nip` varchar(20) NOT NULL,
  `mapel` varchar(50) NOT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dataguru`
--

INSERT INTO `dataguru` (`id`, `nama`, `nip`, `mapel`, `alamat`, `foto`) VALUES
(1, 'Ahmad Mauludin, S.Kom', '1990081720200410', 'P. Web', 'Cibaduyut', 'avatar-guru-lk.jpg'),
(2, 'Inar, S.Kom', '1990081720200411', 'Basis Data', 'Sumedang', 'avatar-guru-lk.jpg'),
(3, 'Tita Nurhayati', '1990081720200419', 'Teknologi Nuklir', 'Sumedang', 'images (3).jpg'),
(4, 'Ai Siti Nurwaskanah, S.Pd', '198001012005012001', 'Matematika', 'Jl. Merdeka No. 1', 'images (3).jpg'),
(5, 'Eneng Mella M, S.Pd', '198202022006022002', 'Bahasa Indonesia', 'Jl. Sudirman No. 2', 'images (3).jpg'),
(6, 'Lala Mardiana, S.Kom', '198503032007032003', 'Informatika', 'Jl. Gatot Subroto No. 3', 'man-avatar-icon-free-vector.jpg'),
(7, 'Cintiany Dewi, S.Pd', '198704042008042004', 'Bahasa Inggris', 'Jl. Thamrin No. 4', 'man-avatar-icon-free-vector.jpg'),
(8, 'Tegar Ananda Putra Riyadi, S.Pd', '199005052010052005', 'Seni Budaya', 'Jl. Sudirman No. 5', 'man-avatar-icon-free-vector.jpg'),
(9, 'Fauzi Abdul Azis, S.Pd', '198306062009062006', 'Sejarah', 'Jl. Merdeka No. 6', 'avatar-guru-lk.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `datakelas`
--

CREATE TABLE `datakelas` (
  `id_kelas` int(11) NOT NULL,
  `namakelas` varchar(100) NOT NULL,
  `tingkat` enum('X','XI','XII') NOT NULL,
  `tahunajaran` varchar(20) NOT NULL,
  `idguru` int(11) DEFAULT NULL,
  `idsiswa` int(11) DEFAULT NULL,
  `idruang` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `datakelas`
--

INSERT INTO `datakelas` (`id_kelas`, `namakelas`, `tingkat`, `tahunajaran`, `idguru`, `idsiswa`, `idruang`) VALUES
(1, 'X A', 'X', '2024/2025', 1, 10, 1),
(2, 'X B', 'X', '2024/2025', 2, 14, 2),
(3, 'X C', 'X', '2024/2025', 3, 24, 3),
(4, 'XI A', 'XI', '2024/2025', 4, 38, 4),
(5, 'XI B', 'XI', '2024/2025', 5, 41, 5),
(6, 'XI C', 'XI', '2024/2025', 6, 47, 6),
(7, 'XII A', 'XII', '2024/2025', 7, 52, 7),
(8, 'XII B', 'XII', '2024/2025', 8, 67, 8),
(9, 'XII C', 'XII', '2024/2025', 9, 68, 9);

-- --------------------------------------------------------

--
-- Table structure for table `dataruang`
--

CREATE TABLE `dataruang` (
  `id_ruang` int(11) NOT NULL,
  `nama_ruang` varchar(100) NOT NULL,
  `kapasitas` int(11) DEFAULT 0,
  `keterangan` text DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dataruang`
--

INSERT INTO `dataruang` (`id_ruang`, `nama_ruang`, `kapasitas`, `keterangan`, `foto`) VALUES
(1, 'Ruang 1', 40, 'Ruang kelas X', 'default-ruang.jpg'),
(2, 'Ruang 2', 40, 'Ruang kelas X', 'default-ruang.jpg'),
(3, 'Ruang 3', 40, 'Ruang kelas X', 'default-ruang.jpg'),
(4, 'Ruang 4', 40, 'Ruang kelas XI', 'default-ruang.jpg'),
(5, 'Ruang 5', 40, 'Ruang kelas XI', 'default-ruang.jpg'),
(6, 'Ruang 6', 40, 'Ruang kelas XI', 'default-ruang.jpg'),
(7, 'Ruang 7', 40, 'Ruang kelas XII', 'default-ruang.jpg'),
(8, 'Ruang 8', 40, 'Ruang kelas XII', 'default-ruang.jpg'),
(9, 'Ruang 9', 40, 'Ruang kelas XII', 'default-ruang.jpg'),
(10, 'Ruang 10', 40, 'jhgjhagsjdhgaj', 'default-ruang.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `datasiswa`
--

CREATE TABLE `datasiswa` (
  `id` int(5) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `status` enum('aktif','tidak aktif') NOT NULL DEFAULT 'aktif',
  `tanggal_lahir` date NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `id_kelas` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `datasiswa`
--

INSERT INTO `datasiswa` (`id`, `nama`, `status`, `tanggal_lahir`, `alamat`, `foto`, `id_kelas`) VALUES
(1, 'Abdul Hadi', 'aktif', '2011-08-24', 'Alamat Abdul Hadi', 'avatar-siswa-lk.jpg', 1),
(2, 'Abian Aeruliansyah', 'aktif', '2011-08-24', 'Alamat Abian Aeruliansyah', 'avatar-siswa-lk.jpg', 1),
(3, 'Agus Suhendar', 'aktif', '2009-08-24', 'Alamat Agus Suhendar', 'avatar-siswa-lk.jpg', 1),
(4, 'Ahmad Fauzan Zamzami', 'aktif', '2008-08-24', 'Alamat Ahmad Fauzan Zamzami', 'avatar-siswa-lk.jpg', 1),
(5, 'Ahmad Rizki Mubarok', 'aktif', '2010-08-24', 'Alamat Ahmad Rizki Mubarok', 'avatar-siswa-lk.jpg', 1),
(6, 'Aji Asril Sidiq', 'aktif', '2011-08-24', 'Alamat Aji Asril Sidiq', 'avatar-siswa-lk.jpg', 1),
(7, 'Azzam Althaaf Dzaki', 'aktif', '2008-08-24', 'Alamat Azzam Althaaf Dzaki', 'avatar-siswa-lk.jpg', 1),
(8, 'El Ghifari Zaqi Mubarok', 'aktif', '2008-08-24', 'Alamat El Ghifari Zaqi Mubarok', 'avatar-siswa-lk.jpg', 1),
(9, 'Evan Maulidin', 'aktif', '2009-08-24', 'Alamat Evan Maulidin', 'avatar-siswa-lk.jpg', 1),
(10, 'Fajar Hanafi', 'aktif', '2009-08-24', 'Alamat Fajar Hanafi', 'avatar-siswa-lk.jpg', 1),
(11, 'Fitri Nuehasanah', 'aktif', '2008-08-24', 'Alamat Fitri Nuehasanah', 'avatar-siswa-pr.jpg', 1),
(12, 'Haikal Ramdan Faturohman', 'aktif', '2010-08-24', 'Alamat Haikal Ramdan Faturohman', 'avatar-siswa-lk.jpg', 2),
(13, 'Hamdan Hilmawan', 'aktif', '2010-08-24', 'Alamat Hamdan Hilmawan', 'avatar-siswa-lk.jpg', 2),
(14, 'Jajang Jaenudin', 'aktif', '2008-08-24', 'Alamat Jajang Jaenudin', 'avatar-siswa-lk.jpg', 2),
(15, 'Kamelia', 'aktif', '2009-08-24', 'Alamat Kamelia', 'avatar-siswa-pr.jpg', 2),
(16, 'M. Fadhli Khoiri', 'aktif', '2010-08-24', 'Alamat M. Fadhli Khoiri', 'avatar-siswa-lk.jpg', 2),
(17, 'Mohamad Chandra Nirwansyah', 'aktif', '2010-08-24', 'Alamat Mohamad Chandra Nirwansyah', 'avatar-siswa-lk.jpg', 2),
(18, 'Muhammad Aditya Ramli', 'aktif', '2009-08-24', 'Alamat Muhammad Aditya Ramli', 'avatar-siswa-lk.jpg', 2),
(19, 'Muhammad Reza Nugraha', 'aktif', '2011-08-24', 'Alamat Muhammad Reza Nugraha', 'avatar-siswa-lk.jpg', 2),
(20, 'Muhammad Shidqi Zaidan Khoirusabri', 'aktif', '2009-08-24', 'Alamat Muhammad Shidqi Zaidan Khoirusabri', 'avatar-siswa-lk.jpg', 2),
(21, 'Nabilah', 'aktif', '2011-08-24', 'Alamat Nabilah', 'avatar-siswa-pr.jpg', 2),
(22, 'Naufal Abdul Jalal', 'aktif', '2010-08-24', 'Alamat Naufal Abdul Jalal', 'avatar-siswa-lk.jpg', 2),
(23, 'Nazma Luthfia Hilmi', 'aktif', '2009-08-24', 'Alamat Nazma Luthfia Hilmi', 'avatar-siswa-pr.jpg', 3),
(24, 'Nisrina Zakiah Maharani', 'aktif', '2008-08-24', 'Alamat Nisrina Zakiah Maharani', 'avatar-siswa-pr.jpg', 3),
(25, 'Nurjanah', 'aktif', '2011-08-24', 'Alamat Nurjanah', 'avatar-siswa-pr.jpg', 3),
(26, 'Razka Aditya', 'aktif', '2011-08-24', 'Alamat Razka Aditya', 'avatar-siswa-lk.jpg', 3),
(27, 'Rehan Adit Pratama', 'aktif', '2010-08-24', 'Alamat Rehan Adit Pratama', 'avatar-siswa-lk.jpg', 3),
(28, 'Restina Fitri Saputra', 'aktif', '2009-08-24', 'Alamat Restina Fitri Saputra', 'avatar-siswa-lk.jpg', 3),
(29, 'Reza Angga Setiawan Suhendar', 'aktif', '2009-08-24', 'Alamat Reza Angga Setiawan Suhendar', 'avatar-siswa-lk.jpg', 3),
(30, 'Salsya Desiawati', 'aktif', '2011-08-24', 'Alamat Salsya Desiawati', 'avatar-siswa-pr.jpg', 3),
(31, 'Tio Nofiana', 'aktif', '2010-08-24', 'Alamat Tio Nofiana', 'avatar-siswa-lk.jpg', 3),
(32, 'Zahra Nur Aulia', 'aktif', '2009-08-24', 'Alamat Zahra Nur Aulia', 'avatar-siswa-pr.jpg', 3),
(33, 'Agus Ramdani Alfariji', 'aktif', '2011-08-24', 'Alamat Agus Ramdani Alfariji', 'avatar-siswa-lk.jpg', 4),
(34, 'Dinda Raihana Kamil', 'aktif', '2009-08-24', 'Alamat Dinda Raihana Kamil', 'avatar-siswa-pr.jpg', 4),
(35, 'Fahri Ahmad Ihsanudin', 'aktif', '2008-08-24', 'Alamat Fahri Ahmad Ihsanudin', 'avatar-siswa-lk.jpg', 4),
(36, 'Fatima Azzahra Atmaja', 'aktif', '2009-08-24', 'Alamat Fatima Azzahra Atmaja', 'avatar-siswa-pr.jpg', 4),
(37, 'Gaesha Urvia Maylani', 'aktif', '2008-08-24', 'Alamat Gaesha Urvia Maylani', 'avatar-siswa-pr.jpg', 4),
(38, 'Ihsal Salahudin', 'aktif', '2008-08-24', 'Alamat Ihsal Salahudin', 'avatar-siswa-lk.jpg', 4),
(39, 'Indra Lesmana Permana', 'aktif', '2010-08-24', 'Alamat Indra Lesmana Permana', 'avatar-siswa-lk.jpg', 5),
(40, 'Iqlima Nur Fatima', 'aktif', '2008-08-24', 'Alamat Iqlima Nur Fatima', 'avatar-siswa-pr.jpg', 5),
(41, 'Kosasih', 'aktif', '2008-08-24', 'Alamat Kosasih', 'avatar-siswa-lk.jpg', 5),
(42, 'Lilis Nuraeni', 'aktif', '2008-08-24', 'Alamat Lilis Nuraeni', 'avatar-siswa-pr.jpg', 5),
(43, 'Miftahul Aini', 'aktif', '2010-08-24', 'Alamat Miftahul Aini', 'avatar-siswa-pr.jpg', 5),
(44, 'Muhammad Naufal Kholil', 'aktif', '2008-08-24', 'Alamat Muhammad Naufal Kholil', 'avatar-siswa-lk.jpg', 5),
(45, 'Najdi Fahman Dzulhilmi', 'aktif', '2008-08-24', 'Alamat Najdi Fahman Dzulhilmi', 'avatar-siswa-lk.jpg', 6),
(46, 'Nurul Hidayah', 'aktif', '2009-08-24', 'Alamat Nurul Hidayah', 'avatar-siswa-pr.jpg', 6),
(47, 'Rahma Alida', 'aktif', '2011-08-24', 'Alamat Rahma Alida', 'avatar-siswa-pr.jpg', 6),
(48, 'Reihan Iqbal Prasetio', 'aktif', '2011-08-24', 'Alamat Reihan Iqbal Prasetio', 'avatar-siswa-lk.jpg', 6),
(49, 'Usi Rahmawati', 'aktif', '2008-08-24', 'Alamat Usi Rahmawati', 'avatar-siswa-pr.jpg', 6),
(50, 'Amelia Chintia Sari', 'aktif', '2011-08-24', 'Alamat Amelia Chintia Sari', 'avatar-siswa-pr.jpg', 7),
(51, 'Andini Nur Fadilah', 'aktif', '2010-08-24', 'Alamat Andini Nur Fadilah', 'avatar-siswa-pr.jpg', 7),
(52, 'Dewi Kamilah', 'aktif', '2009-08-24', 'Alamat Dewi Kamilah', 'avatar-siswa-pr.jpg', 7),
(53, 'Farrel Imaduddin Al Ashari', 'aktif', '2010-08-24', 'Alamat Farrel Imaduddin Al Ashari', 'avatar-siswa-lk.jpg', 7),
(54, 'Fauzi Rahmawati', 'aktif', '2008-08-24', 'Alamat Fauzi Rahmawati', 'avatar-siswa-pr.jpg', 7),
(55, 'Fithriyah Zahra Fatinah', 'aktif', '2011-08-24', 'Alamat Fithriyah Zahra Fatinah', 'avatar-siswa-pr.jpg', 7),
(56, 'Gumelar Ramadhan Nugraha', 'aktif', '2010-08-24', 'Alamat Gumelar Ramadhan Nugraha', 'avatar-siswa-lk.jpg', 7),
(57, 'Hamzah Alafi Iskandar', 'aktif', '2009-08-24', 'Alamat Hamzah Alafi Iskandar', 'avatar-siswa-lk.jpg', 7),
(58, 'Lupi Maulana', 'aktif', '2008-08-24', 'Alamat Lupi Maulana', 'avatar-siswa-pr.jpg', 7),
(59, 'Muhamad Arya', 'aktif', '2009-08-24', 'Alamat Muhamad Arya', 'avatar-siswa-lk.jpg', 8),
(60, 'Muhammad Abdul Kholiq', 'aktif', '2008-08-24', 'Alamat Muhammad Abdul Kholiq', 'avatar-siswa-lk.jpg', 8),
(61, 'Muhammad Adzka Satria Pratama', 'aktif', '2011-08-24', 'Alamat Muhammad Adzka Satria Pratama', 'avatar-siswa-lk.jpg', 8),
(62, 'Nadira Fitra Oktaviani', 'aktif', '2011-08-24', 'Alamat Nadira Fitra Oktaviani', 'avatar-siswa-pr.jpg', 8),
(63, 'Nandra Rizky Alpauzan', 'aktif', '2009-08-24', 'Alamat Nandra Rizky Alpauzan', 'avatar-siswa-pr.jpg', 8),
(64, 'Nizma Nurrahna Reydienna', 'aktif', '2008-08-24', 'Alamat Nizma Nurrahna Reydienna', 'avatar-siswa-pr.jpg', 8),
(65, 'Nurfitri Aulia', 'aktif', '2008-08-24', 'Alamat Nurfitri Aulia', 'avatar-siswa-pr.jpg', 8),
(66, 'Rahma Yulia Nasihin', 'aktif', '2009-08-24', 'Alamat Rahma Yulia Nasihin', 'avatar-siswa-pr.jpg', 8),
(67, 'Saif Paqihul Kamal', 'aktif', '2011-08-24', 'Alamat Saif Paqihul Kamal', 'avatar-siswa-lk.jpg', 8),
(68, 'Salwa Ufairah', 'aktif', '2011-08-24', 'Alamat Salwa Ufairah', 'avatar-siswa-pr.jpg', 9),
(69, 'Shelyna Siti Solihhah', 'aktif', '2011-08-24', 'Alamat Shelyna Siti Solihhah', 'avatar-siswa-pr.jpg', 9),
(70, 'Siti Aliyatul Munawaroh', 'aktif', '2008-08-24', 'Alamat Siti Aliyatul Munawaroh', 'avatar-siswa-pr.jpg', 9),
(71, 'Siti Fadhilah Kamelia', 'aktif', '2009-08-24', 'Alamat Siti Fadhilah Kamelia', 'avatar-siswa-pr.jpg', 9),
(72, 'Siti Hasna Aminah', 'aktif', '2008-08-24', 'Alamat Siti Hasna Aminah', 'avatar-siswa-pr.jpg', 9),
(73, 'Sry Ayu Nurwahiddah', 'aktif', '2009-08-24', 'Alamat Sry Ayu Nurwahiddah', 'avatar-siswa-pr.jpg', 9),
(74, 'Wildan Azmi Muzakki', 'aktif', '2011-08-24', 'Alamat Wildan Azmi Muzakki', 'avatar-siswa-lk.jpg', 9),
(75, 'Yanti Nuraeni', 'aktif', '2009-08-24', 'Alamat Yanti Nuraeni', 'avatar-siswa-pr.jpg', 9),
(76, 'Zahran Fauzan Zamzami', 'aktif', '2010-08-24', 'Alamat Zahran Fauzan Zamzami', 'avatar-siswa-lk.jpg', 9);

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `id_aturan` int(11) NOT NULL,
  `nominal_bayar` int(11) NOT NULL,
  `metode_bayar` enum('cash','transfer','ewallet') NOT NULL,
  `bukti_bayar` varchar(255) DEFAULT NULL,
  `status` enum('belum','pending','dikonfirmasi','ditolak') NOT NULL DEFAULT 'belum',
  `keterangan` text DEFAULT NULL,
  `catatan_admin` text DEFAULT NULL,
  `tanggal_bayar` date NOT NULL,
  `tanggal_konfirmasi` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id`, `id_siswa`, `id_aturan`, `nominal_bayar`, `metode_bayar`, `bukti_bayar`, `status`, `keterangan`, `catatan_admin`, `tanggal_bayar`, `tanggal_konfirmasi`, `created_at`) VALUES
(3, 1, 9, 50000, 'cash', 'bukti_1_1788158585.jpg', 'dikonfirmasi', '', NULL, '2026-08-31', '2026-08-31 08:43:17', '2026-08-31 06:42:37'),
(4, 1, 10, 100000, 'transfer', 'bukti_1_1788159308.jpg', 'dikonfirmasi', '', NULL, '2026-08-31', '2026-08-31 08:55:27', '2026-08-31 06:43:50'),
(5, 2, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(6, 3, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(7, 4, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(8, 5, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(9, 6, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(10, 7, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(11, 8, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(12, 9, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(13, 10, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(14, 11, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(15, 12, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(16, 13, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(17, 14, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(18, 15, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(19, 16, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(20, 17, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(21, 18, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(22, 19, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(23, 20, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(24, 21, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(25, 22, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(26, 23, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(27, 24, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(28, 25, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(29, 26, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(30, 27, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(31, 28, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(32, 29, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(33, 30, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(34, 31, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(35, 32, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(36, 33, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(37, 34, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(38, 35, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(39, 36, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(40, 37, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(41, 38, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(42, 39, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(43, 40, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(44, 41, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(45, 42, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(46, 43, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(47, 44, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(48, 45, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(49, 46, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(50, 47, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(51, 48, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(52, 49, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(53, 50, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(54, 51, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(55, 52, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(56, 53, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(57, 54, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(58, 55, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(59, 56, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(60, 57, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(61, 58, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(62, 59, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(63, 60, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(64, 61, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(65, 62, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(66, 63, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(67, 64, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(68, 65, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(69, 66, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(70, 67, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(71, 68, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(72, 69, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(73, 70, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(74, 71, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(75, 72, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(76, 73, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(77, 74, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(78, 75, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(79, 76, 10, 100000, '', NULL, 'belum', '', NULL, '2026-08-31', NULL, '2026-08-31 06:43:50'),
(80, 2, 10, 100000, 'cash', NULL, 'dikonfirmasi', '', NULL, '2026-09-02', '2026-09-02 11:39:20', '2026-09-02 04:39:20');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `role` enum('admin','kepala','user') NOT NULL DEFAULT 'user',
  `id_guru` int(11) DEFAULT NULL,
  `id_siswa` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama_lengkap`, `role`, `id_guru`, `id_siswa`, `created_at`) VALUES
(1, 'admin', '$2y$10$EUNmdP1HCuxKDzvMSUBntua2HkW.ATfataZdLAo3efL4QDBgsJBdW', 'Administrator', 'admin', NULL, NULL, '2026-08-26 04:38:54'),
(2, 'kepala', '$2y$10$Vg0f1pqVuwhlKMCZP8tcJuEAslJ9Odxl327CB8sCC/ZYto3opxLXa', 'Fauzi Rachman', 'kepala', 9, NULL, '2026-08-26 04:56:52'),
(3, 'ahmad', '$2y$10$651MQZqlPaDiflsNMvOoSOhNL6tRp00Uz4WjHQYg9iTJVnozOPe9a', 'Ahmad Mauludin, S.Kom', 'user', 1, NULL, '2026-08-26 04:56:52'),
(4, 'abdul', '$2y$10$tAWXPyWXEoV2hSc1aU4IH.E0qNwbeJkWUBT9O0nWk0OL30Xln0tf2', 'Abdul Hadi', 'user', NULL, 1, '2026-08-26 04:56:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aturan_pembayaran`
--
ALTER TABLE `aturan_pembayaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dataguru`
--
ALTER TABLE `dataguru`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `datakelas`
--
ALTER TABLE `datakelas`
  ADD PRIMARY KEY (`id_kelas`),
  ADD KEY `idguru` (`idguru`),
  ADD KEY `idsiswa` (`idsiswa`),
  ADD KEY `idruang` (`idruang`);

--
-- Indexes for table `dataruang`
--
ALTER TABLE `dataruang`
  ADD PRIMARY KEY (`id_ruang`);

--
-- Indexes for table `datasiswa`
--
ALTER TABLE `datasiswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_kelas` (`id_kelas`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_siswa` (`id_siswa`),
  ADD KEY `id_aturan` (`id_aturan`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `id_guru` (`id_guru`),
  ADD KEY `id_siswa` (`id_siswa`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aturan_pembayaran`
--
ALTER TABLE `aturan_pembayaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `dataguru`
--
ALTER TABLE `dataguru`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `datakelas`
--
ALTER TABLE `datakelas`
  MODIFY `id_kelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `dataruang`
--
ALTER TABLE `dataruang`
  MODIFY `id_ruang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `datasiswa`
--
ALTER TABLE `datasiswa`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `datakelas`
--
ALTER TABLE `datakelas`
  ADD CONSTRAINT `datakelas_ibfk_1` FOREIGN KEY (`idguru`) REFERENCES `dataguru` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `datakelas_ibfk_2` FOREIGN KEY (`idsiswa`) REFERENCES `datasiswa` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `datakelas_ibfk_3` FOREIGN KEY (`idruang`) REFERENCES `dataruang` (`id_ruang`) ON DELETE SET NULL;

--
-- Constraints for table `datasiswa`
--
ALTER TABLE `datasiswa`
  ADD CONSTRAINT `datasiswa_ibfk_1` FOREIGN KEY (`id_kelas`) REFERENCES `datakelas` (`id_kelas`) ON DELETE SET NULL;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `datasiswa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pembayaran_ibfk_2` FOREIGN KEY (`id_aturan`) REFERENCES `aturan_pembayaran` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_guru`) REFERENCES `dataguru` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`id_siswa`) REFERENCES `datasiswa` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
