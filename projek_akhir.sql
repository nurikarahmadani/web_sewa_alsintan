-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 30 Jul 2022 pada 07.57
-- Versi server: 10.4.14-MariaDB
-- Versi PHP: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `projek_akhir`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `nama_admin` varchar(255) NOT NULL,
  `pass_admin` varchar(255) NOT NULL,
  `noHP_admin` int(255) NOT NULL,
  `email_admin` varchar(255) NOT NULL,
  `status` set('submitted') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_admin`, `nama_admin`, `pass_admin`, `noHP_admin`, `email_admin`, `status`) VALUES
(1, 'Boss', '$2y$10$6KUMVQeRZJfcxubeBV8QoucSGuLlPLne8c0liLM5OkMiuMc6U6ZNW', 9999, 'boss@gmail.com', 'submitted'),
(10, 'Admin 2', '$2y$10$r53K9EbnX6BJ9', 1234, 'admin2@gmail.com', 'submitted');

-- --------------------------------------------------------

--
-- Struktur dari tabel `alsintan`
--

CREATE TABLE `alsintan` (
  `id_alsintan` int(11) NOT NULL,
  `nama_alsintan` varchar(255) NOT NULL,
  `harga_sewa` int(20) NOT NULL,
  `jumlah_unit` int(30) NOT NULL,
  `Foto` mediumblob NOT NULL,
  `spesifikasi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `alsintan`
--

INSERT INTO `alsintan` (`id_alsintan`, `nama_alsintan`, `harga_sewa`, `jumlah_unit`, `Foto`, `spesifikasi`) VALUES
(68, 'Alat Pemupuk', 30000, 50, 0x494d472d36316137323239353362346131362e35333331383732332e6a7067, 'Dimensi 48x28x98\r\nPanjang Selang 98 cm\r\nDiameter 40cm'),
(69, 'Combine Harvester', 500000, 50, 0x494d472d36316136643839393665313131332e31373930393234312e6a7067, 'Dilengkapi roda crawler karet, mesin ini dapat beroperasi pada lahan kering, lahan basah dan lahan yang berlumpur'),
(70, 'Alat Tanam Jagung', 30000, 50, 0x494d472d36316136643865323836353562322e37393539313037362e6a7067, 'hole seeding rate : 1-2 adjustable\r\nspike number : 7-10 ( can adjust)\r\nsparepart : 11 pcs seeds wheel for each planter\r\ng.n : 11kg'),
(71, 'Mesin Pemanen Jagung', 200000, 48, 0x494d472d36316136643932373461373232302e31353630353530352e6a7067, 'Dimensi terpasang (PxLxT) : 180×80×115 cm\r\nDimensi peti(sebelum pasang) : 156x57x101 cm\r\nMesin penggerak : Mesin bensin 9-10 HP\r\nBerat : 210 Kg'),
(72, 'Mesin Penanam Padi', 500000, 50, 0x494d472d36316136643936393136333735312e31303039333937302e6a7067, 'Dengan kapasitas bahan bakar 4 liter, alat ini dapat bertahan sekitar 6 sampai 8 jam. Selain itu alat ini mudah bermanuver karena memiliki 3 gigi penggerak dan kaki-kaki yang sanggup berjalan di lumpur yang membuat manuver lebih halus dan lincah.'),
(73, 'Rotavator', 300000, 50, 0x494d472d36316136643962326230633863352e35343137313139312e6a7067, 'Lebar (125,155 and 180 cm) dan cocok digunakan untuk sawah, pertanian\r\ndan ladang lainnya sebagai pengolahan tanah yang pertama atau ke dua.\r\nDigunakan untuk traktor horsepower kecil'),
(74, 'Traktor', 300000, 48, 0x494d472d36316136643966373866323539322e32373236323236312e6a7067, 'Merk/Model QUICK / G 600\r\nKecepatan 1 Kecepatan Maju\r\nSistem Transmisi Full Gear\r\nGear Case Casting Dual Part System');

-- --------------------------------------------------------

--
-- Struktur dari tabel `keterlambatan`
--

CREATE TABLE `keterlambatan` (
  `id_keterlambatan` int(11) NOT NULL,
  `id_transaksi` int(11) NOT NULL,
  `lama_keterlambatan` int(20) NOT NULL,
  `jumlah_denda` int(50) NOT NULL,
  `bukti_pembayaran` mediumblob NOT NULL,
  `status_pembayaran` set('Lunas','Belum Lunas') NOT NULL DEFAULT 'Belum Lunas'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `keterlambatan`
--

INSERT INTO `keterlambatan` (`id_keterlambatan`, `id_transaksi`, `lama_keterlambatan`, `jumlah_denda`, `bukti_pembayaran`, `status_pembayaran`) VALUES
(18, 10, 2, 300000, 0x494d472d36316137353835303030666464312e36333533353134382e706e67, 'Lunas');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penyewa`
--

CREATE TABLE `penyewa` (
  `id_penyewa` int(11) NOT NULL,
  `nama_penyewa` varchar(255) NOT NULL,
  `pass_penyewa` varchar(100) NOT NULL,
  `noHP_penyewa` int(30) NOT NULL,
  `email_penyewa` varchar(255) NOT NULL,
  `tanggal_daftar` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `penyewa`
--

INSERT INTO `penyewa` (`id_penyewa`, `nama_penyewa`, `pass_penyewa`, `noHP_penyewa`, `email_penyewa`, `tanggal_daftar`) VALUES
(1, 'Nurika', '$2y$10$O/7oCpaoXQ7cpJ79SvOldO7siM5cmb6I2BCMgaOO7pQezRe4ml2sm', 81234, 'nurika@gmail.com', '2021-12-01');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penyewaan`
--

CREATE TABLE `penyewaan` (
  `id_penyewaan` int(11) NOT NULL,
  `id_transaksi` int(11) NOT NULL,
  `id_alsintan` int(11) NOT NULL,
  `jumlah_alsintan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `penyewaan`
--

INSERT INTO `penyewaan` (`id_penyewaan`, `id_transaksi`, `id_alsintan`, `jumlah_alsintan`) VALUES
(15, 8, 68, 3),
(16, 8, 69, 2),
(17, 9, 71, 2),
(18, 9, 74, 2),
(19, 10, 70, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_penyewa` int(11) NOT NULL,
  `tanggal_sewa` date NOT NULL,
  `waktu_tranksaksi` datetime NOT NULL,
  `jangka_waktu_sewa` int(30) NOT NULL,
  `tanggal_pengembalian` date NOT NULL,
  `total` int(255) NOT NULL,
  `bukti_pembayaran` mediumblob NOT NULL,
  `status_pembayaran` set('Lunas','Belum Lunas') NOT NULL,
  `status_pengembalian` set('Sudah Kembali','Belum Kembali') NOT NULL,
  `status` set('selesai','belum selesai','','') NOT NULL,
  `sisa_waktu_pembayaran` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_penyewa`, `tanggal_sewa`, `waktu_tranksaksi`, `jangka_waktu_sewa`, `tanggal_pengembalian`, `total`, `bukti_pembayaran`, `status_pembayaran`, `status_pengembalian`, `status`, `sisa_waktu_pembayaran`) VALUES
(8, 1, '2021-12-05', '2021-12-01 06:26:16', 5, '2021-12-10', 5450000, 0x494d472d36316137346561366139386265312e31393339353632392e6a7067, 'Lunas', 'Sudah Kembali', 'selesai', 3),
(9, 1, '2021-12-03', '2021-12-01 07:07:24', 1, '2021-12-04', 1000000, '', 'Belum Lunas', 'Belum Kembali', '', 3),
(10, 1, '2021-11-28', '2021-12-01 07:08:40', 1, '2021-11-29', 30000, 0x494d472d36316137353763353862663036352e35343031323632372e706e67, 'Lunas', 'Sudah Kembali', 'selesai', 3);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indeks untuk tabel `alsintan`
--
ALTER TABLE `alsintan`
  ADD PRIMARY KEY (`id_alsintan`);

--
-- Indeks untuk tabel `keterlambatan`
--
ALTER TABLE `keterlambatan`
  ADD PRIMARY KEY (`id_keterlambatan`),
  ADD KEY `id_transaksi` (`id_transaksi`);

--
-- Indeks untuk tabel `penyewa`
--
ALTER TABLE `penyewa`
  ADD PRIMARY KEY (`id_penyewa`);

--
-- Indeks untuk tabel `penyewaan`
--
ALTER TABLE `penyewaan`
  ADD PRIMARY KEY (`id_penyewaan`),
  ADD KEY `id_alsintan` (`id_alsintan`),
  ADD KEY `id_transaksi` (`id_transaksi`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_penyewa` (`id_penyewa`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `alsintan`
--
ALTER TABLE `alsintan`
  MODIFY `id_alsintan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT untuk tabel `keterlambatan`
--
ALTER TABLE `keterlambatan`
  MODIFY `id_keterlambatan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `penyewa`
--
ALTER TABLE `penyewa`
  MODIFY `id_penyewa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `penyewaan`
--
ALTER TABLE `penyewaan`
  MODIFY `id_penyewaan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `keterlambatan`
--
ALTER TABLE `keterlambatan`
  ADD CONSTRAINT `keterlambatan_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `penyewaan`
--
ALTER TABLE `penyewaan`
  ADD CONSTRAINT `penyewaan_ibfk_2` FOREIGN KEY (`id_alsintan`) REFERENCES `alsintan` (`id_alsintan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `penyewaan_ibfk_3` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_penyewa`) REFERENCES `penyewa` (`id_penyewa`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
