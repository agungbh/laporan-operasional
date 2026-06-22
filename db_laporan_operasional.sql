-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 22, 2026 at 05:22 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_laporan_operasional`
--

-- --------------------------------------------------------

--
-- Table structure for table `laporan`
--

CREATE TABLE `laporan` (
  `id` int NOT NULL,
  `tanggal` date NOT NULL,
  `bulan_pengiriman` varchar(20) DEFAULT NULL,
  `no_kartu` varchar(50) DEFAULT NULL,
  `keterangan` text NOT NULL,
  `jumlah_saldo` int DEFAULT '0',
  `debet` int DEFAULT '0',
  `kredit` int DEFAULT '0',
  `saldo` int DEFAULT '0',
  `diserahkan_oleh` varchar(100) DEFAULT NULL,
  `foto_kartu` varchar(255) DEFAULT NULL,
  `foto_saldo` varchar(255) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `laporan`
--

INSERT INTO `laporan` (`id`, `tanggal`, `bulan_pengiriman`, `no_kartu`, `keterangan`, `jumlah_saldo`, `debet`, `kredit`, `saldo`, `diserahkan_oleh`, `foto_kartu`, `foto_saldo`, `gambar`) VALUES
(1, '2026-04-07', '04-2026', '0145-0082-0141-4963', 'Diterima Kartu Flash Untuk Mobil Operasional KK B 2649 TBW Bulan April 2026', 1000000, 1000000, 0, 1000000, 'Kintan', 'foto_kartu_1782100049_6a38b051bf744.jpeg', 'foto_saldo_1782102176_6a38b8a09304c.jpeg', ''),
(2, '2026-05-04', '04-2026', '', 'Kegiatan Sosialisasi Guru BK dan penyerahan LoA Batch 1 dan SK Rektor ke sekolah penerima beasiswa jalur undangan dan Sosialisasi LoA Batch 2.', 0, 0, 323600, 676400, '', '', '', 'gambar_1782100263_6a38b1277e8d7.jpeg'),
(3, '2026-06-04', '04-2026', '', 'Kegiatan penyerahan fee apresasi sekolah: SMKN 1 Kawali, SMKN 3 Tasikmalaya, SMAN 10 Tasikmalaya, SMAN 6 Tasikmalaya, SMK YPC Tasikmalaya', 0, 0, 224700, 451700, '', '', '', 'gambar_1782100371_6a38b19399fb9.jpeg'),
(4, '2026-06-11', '04-2026', '', 'Kegiatan Rapat KK dan Markom Wilayah 2, 3 Tasikmalaya, Purwokerto & Tegal tgl 11 Juni 2026, Bertempat Di Kampus UBSI Purwokerto', 0, 0, 273100, 178600, '', '', '', 'gambar_1782100442_6a38b1dabd2ba.jpeg'),
(5, '2026-06-22', '04-2026', '', 'Kegiatan Sosialisasi Guru BK dan penyerahan LoA Batch 2 dan SK Rektor ke sekolah penerima beasiswa jalur undangan.', 0, 0, 150000, 28600, '', '', '', 'gambar_1782100489_6a38b2098df4a.jpeg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `laporan`
--
ALTER TABLE `laporan`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `laporan`
--
ALTER TABLE `laporan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
