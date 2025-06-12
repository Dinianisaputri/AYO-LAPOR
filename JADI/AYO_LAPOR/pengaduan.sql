-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 15, 2025 at 02:04 PM
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
-- Database: `pengaduan`
--

-- --------------------------------------------------------

--
-- Table structure for table `pengaduan`
--

CREATE TABLE `pengaduan` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `isi` text DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `status` enum('baru','diproses','selesai') DEFAULT 'baru'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengaduan`
--

INSERT INTO `pengaduan` (`id`, `judul`, `isi`, `tanggal`, `lokasi`, `kategori`, `status`) VALUES
(3, 'jalan rusak', 'lamongan jalannya rusak', '2025-05-15', 'solokuro', 'pengaduan', 'baru'),
(60, 'gapura jelek', 'jelek banget bejir', '2025-05-15', 'lamkot', 'pengaduan', 'baru'),
(71, 'A', 'A', '2025-05-30', 'A', 'aspirasi', 'baru'),
(72, 'A', 'A', '2025-05-30', 'A', 'aspirasi', 'baru'),
(73, 'A', 'A', '2025-05-30', 'A', 'aspirasi', 'baru'),
(74, 'A', 'A', '2025-05-30', 'A', 'aspirasi', 'baru'),
(75, 'A', 'A', '2025-05-30', 'A', 'aspirasi', 'baru'),
(76, 'A', 'A', '2025-05-30', 'A', 'aspirasi', 'baru'),
(77, 'A', 'A', '2025-05-30', 'A', 'aspirasi', 'baru'),
(78, 'PP', 'PP', '2025-05-14', 'P', 'aspirasi', 'baru'),
(79, 'PP', 'PP', '2025-05-14', 'P', 'aspirasi', 'baru');

-- --------------------------------------------------------

--
-- Table structure for table `tanggapan`
--

CREATE TABLE `tanggapan` (
  `id` int(11) NOT NULL,
  `id_pengaduan` int(11) NOT NULL,
  `id_admin` int(11) DEFAULT NULL,
  `tanggapan` text NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tanggapan`
--

INSERT INTO `tanggapan` (`id`, `id_pengaduan`, `id_admin`, `tanggapan`, `tanggal`) VALUES
(1, 60, NULL, 'besok didandani', '2025-05-14 22:06:44'),
(14, 3, NULL, 'coba patungan buat dibenerin awogawog', '2025-05-14 22:18:19');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `whatsapp` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama_lengkap`, `username`, `email`, `whatsapp`, `password`, `created_at`) VALUES
(1, 'saya', 'kulonsampan', 'kulonsampan21@gmail.com', '+628234643726', '$2y$10$jqaYNkJmicef6rEvRU1OleYU/5R.Rl/Fc94KzGhNChD8R7YQCfyua', '2025-05-07 14:59:45'),
(5, 'ibad', 'ibad', 'kulon@gmail.com', '+628234643726', '$2y$10$WanZUbYPeOM1iUja8imKKe1QRvp0i7zlUl9gptqHEnO1UNd4S.de.', '2025-05-07 15:07:32'),
(6, 'saya', 'kensato', 'admin1112222@gmail.com', '+628234643726', '$2y$10$NDFkuioOTp7psbr/MwTh3OsNLIZJ0F0awnpGyV56zflfJPfFiZGNy', '2025-05-08 04:31:41'),
(7, 'ibad', 'bangku', 'slebew@gmail.com', '+628234643726', '$2y$10$K4ZBMiS7sSoSVZ82YY2JJ.cXKJ8H/PLzTOiKJrMId96jH9fqoe8ma', '2025-05-09 01:27:28'),
(8, 'pragos', 'pragos12', 'paragos12@gmail.com', '+628234643726', '$2y$10$elbp2MNvsddMWJ7nKkAYm.I7UcGHGDJFUWmzwSPxujdYhC5O3RylW', '2025-05-09 01:48:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pengaduan`
--
ALTER TABLE `pengaduan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tanggapan`
--
ALTER TABLE `tanggapan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pengaduan` (`id_pengaduan`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pengaduan`
--
ALTER TABLE `pengaduan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `tanggapan`
--
ALTER TABLE `tanggapan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tanggapan`
--
ALTER TABLE `tanggapan`
  ADD CONSTRAINT `tanggapan_ibfk_1` FOREIGN KEY (`id_pengaduan`) REFERENCES `pengaduan` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
