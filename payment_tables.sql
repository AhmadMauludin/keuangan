-- Tabel Aturan Pembayaran (Setting Biaya Sekolah)
CREATE TABLE `aturan_pembayaran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_biaya` varchar(100) NOT NULL,
  `nominal` int(11) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel Pembayaran
CREATE TABLE `pembayaran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_siswa` int(11) NOT NULL,
  `id_aturan` int(11) NOT NULL,
  `nominal_bayar` int(11) NOT NULL,
  `metode_bayar` enum('cash','transfer','ewallet') NOT NULL,
  `bukti_bayar` varchar(255) DEFAULT NULL,
  `status` enum('pending','dikonfirmasi','ditolak') NOT NULL DEFAULT 'pending',
  `keterangan` text DEFAULT NULL,
  `catatan_admin` text DEFAULT NULL,
  `tanggal_bayar` date NOT NULL,
  `tanggal_konfirmasi` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_siswa` (`id_siswa`),
  KEY `id_aturan` (`id_aturan`),
  CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `datasiswa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pembayaran_ibfk_2` FOREIGN KEY (`id_aturan`) REFERENCES `aturan_pembayaran` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data contoh aturan pembayaran
INSERT INTO `aturan_pembayaran` (`nama_biaya`, `nominal`, `keterangan`) VALUES
('SPP Bulanan', 350000, 'SPP per bulan untuk semua siswa'),
('SPP Tahunan', 3500000, 'Pembayaran SPP untuk 1 tahun ajaran'),
('Uang Bangunan', 500000, 'Kontribusi pembangunan fasilitas sekolah'),
('Uang Pendidikan', 250000, 'Biaya pendidikan dan pengembangan'),
('Uang Seragam', 200000, 'Pembelian seragam sekolah'),
('Uang Ujian', 150000, 'Biaya pelaksanaan ujian');
