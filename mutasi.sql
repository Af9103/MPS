-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 08, 2024 at 10:25 AM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 5.6.39

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mutasi`
--

-- --------------------------------------------------------

--
-- Table structure for table `batch`
--

CREATE TABLE `batch` (
  `Id` int(11) NOT NULL,
  `batchMutasi` varchar(50) NOT NULL,
  `npk` varchar(5) NOT NULL,
  `hapus` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `batch`
--

INSERT INTO `batch` (`Id`, `batchMutasi`, `npk`, `hapus`) VALUES
(36, 'Mutasi - QA - EHS - 050924125626', 'K0101', NULL),
(37, 'Mutasi - QA - EHS - 050924125626', 'P0101', NULL),
(85, 'Mutasi - QA - VDD - 060924093554', '01900', NULL),
(87, 'Mutasi - QA - MIS - 060924093617', 'P0101', NULL),
(88, 'Mutasi - QA - MIS - 060924093617', 'P0122', NULL),
(91, 'Mutasi - QA - PPC - 060924104057', 'K0101', NULL),
(92, 'Mutasi - QA - PPC - 060924104057', 'P0122', NULL),
(93, 'Mutasi - QA - MIS - 090924075848', '01920', NULL),
(94, '260924152448', 'P0101', NULL),
(95, '011024140906', '01922', NULL),
(96, '011024140906', '234', NULL),
(97, '021024100855', '00000', NULL),
(98, '021024101456', '00000', NULL),
(99, '041024074425', '00000', NULL),
(100, '071024144904', '9191', NULL),
(101, '081024151840', '00000', NULL),
(102, '081024151934', '00000', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id_feedback` int(11) NOT NULL,
  `batchMutasi` varchar(20) DEFAULT NULL,
  `feedback` varchar(200) DEFAULT NULL,
  `oleh` varchar(100) DEFAULT NULL,
  `date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `feedbacks`
--

INSERT INTO `feedbacks` (`id_feedback`, `batchMutasi`, `feedback`, `oleh`, `date`) VALUES
(1, '100924103409', 'dsadsads', 'rw', '2024-09-26 10:08:07'),
(2, '130924073354', 'lakhaq', '345', '2024-09-26 10:09:32'),
(3, '130924073354', 'asSAAS', '345', '2024-09-26 10:11:01'),
(4, '130924073354', 'saAS', '345', '2024-09-26 10:11:44'),
(5, '130924073354', 'oke banget udah', '345', '2024-09-26 10:12:28'),
(6, '110924073639', 'addsads', 'Aldo', '2024-09-26 15:05:42'),
(7, '110924073639', 'as,jbasd', 'Aldo', '2024-09-26 15:06:52'),
(8, '110924073639', 'asasassasasassa', 'Aldo', '2024-09-26 15:09:01'),
(9, '110924073639', 'dsaddsadskijfgewKFewfew[OJFE', 'Aldo', '2024-09-26 15:13:09'),
(10, '110924073639', 'sudah dikurangi 1', 'Aldo', '2024-09-26 15:20:45'),
(11, '130924073354', 'ndasldjlafjeb[gvsfjnfiewjfsdkdmvkdsngioewggewenvjdsncvjsdnvgoewg', '345', '2024-10-01 08:53:35'),
(12, '130924073354', 'lalalalaldaksassakslakslakslkslasks', '345', '2024-10-01 08:54:47'),
(13, '100924103409', 'sudah dikurangi 1', 'wew', '2024-10-01 14:23:12'),
(14, '041024074425', 'sudah lah', 'favian', '2024-10-04 07:46:42'),
(15, '071024144904', 'aaaaaassssssss', '345', '2024-10-07 14:53:10');

-- --------------------------------------------------------

--
-- Table structure for table `mutasi`
--

CREATE TABLE `mutasi` (
  `IdMutasi` int(11) NOT NULL,
  `batchMutasi` varchar(50) DEFAULT NULL,
  `emno` varchar(7) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `cwocAsal` varchar(100) DEFAULT NULL,
  `sectAsal` varchar(100) DEFAULT NULL,
  `subsectAsal` int(11) DEFAULT NULL,
  `tanggalBuat` datetime NOT NULL,
  `tanggalMutasi` date NOT NULL,
  `cwocBaru` varchar(50) DEFAULT NULL,
  `sectBaru` varchar(50) DEFAULT NULL,
  `subsectBaru` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `Req` varchar(100) DEFAULT NULL,
  `FM` varchar(100) DEFAULT NULL,
  `tgl_fm` datetime DEFAULT NULL,
  `SPV` varchar(100) DEFAULT NULL,
  `tgl_spv` datetime DEFAULT NULL,
  `Kadept1` varchar(100) DEFAULT NULL,
  `tgl_kadept1` datetime DEFAULT NULL,
  `Kadept2` varchar(100) DEFAULT NULL,
  `tgl_kadept2` datetime DEFAULT NULL,
  `Kadiv1` varchar(100) DEFAULT NULL,
  `tgl_kadiv1` datetime DEFAULT NULL,
  `Kadiv2` varchar(100) DEFAULT NULL,
  `tgl_kadiv2` datetime DEFAULT NULL,
  `Direktur` varchar(100) DEFAULT NULL,
  `tgl_direktur` datetime DEFAULT NULL,
  `Direktur2` varchar(100) DEFAULT NULL,
  `tgl_direktur2` datetime DEFAULT NULL,
  `HRD` varchar(100) DEFAULT NULL,
  `tgl_apv_hrd` datetime DEFAULT NULL,
  `reject` varchar(255) DEFAULT NULL,
  `tgl_reject` datetime DEFAULT NULL,
  `cek` int(11) DEFAULT NULL,
  `tgl_cek` datetime DEFAULT NULL,
  `hapus` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mutasi`
--

INSERT INTO `mutasi` (`IdMutasi`, `batchMutasi`, `emno`, `nama`, `cwocAsal`, `sectAsal`, `subsectAsal`, `tanggalBuat`, `tanggalMutasi`, `cwocBaru`, `sectBaru`, `subsectBaru`, `status`, `Req`, `FM`, `tgl_fm`, `SPV`, `tgl_spv`, `Kadept1`, `tgl_kadept1`, `Kadept2`, `tgl_kadept2`, `Kadiv1`, `tgl_kadiv1`, `Kadiv2`, `tgl_kadiv2`, `Direktur`, `tgl_direktur`, `Direktur2`, `tgl_direktur2`, `HRD`, `tgl_apv_hrd`, `reject`, `tgl_reject`, `cek`, `tgl_cek`, `hapus`) VALUES
(233, '100924090312', 'K0000', 'HJDFA', 'PRODUCTION 1', '10', 11, '2024-09-10 09:03:12', '2024-09-23', 'PROCESS ENGINEERING', '90', 91, 3, 'tqtqtq', '30000', '2024-09-10 09:09:18', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(234, '100924090312', 'K0001', 'RATY', 'PRODUCTION 1', '10', 11, '2024-09-10 09:03:12', '2024-09-23', 'PROCESS ENGINEERING', '90', 91, 3, 'tqtqtq', '30000', '2024-09-10 09:09:18', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(235, '100924090312', 'K0002', 'LAKSLQ', 'PRODUCTION 1', '10', 11, '2024-09-10 09:03:12', '2024-09-23', 'PROCESS ENGINEERING', '90', 91, 100, 'tqtqtq', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'tqtqtq', '2024-09-10 09:09:18', NULL, NULL, NULL),
(236, '100924091844', 'K0002', 'ABCDE FGHI JKLI HJKL HUJK BHYA BH', 'PRODUCTION 1', '10', 11, '2024-09-10 09:18:44', '2024-09-17', 'PRODUCTION SYSTEM', '60', 61, 3, 'tqtqtq', '30000', '2024-09-10 09:21:07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL),
(237, '100924091844', 'K0003', 'HUASD', 'PRODUCTION 1', '10', 11, '2024-09-10 09:18:44', '2024-09-17', 'PRODUCTION SYSTEM', '60', 61, 3, 'tqtqtq', '30000', '2024-09-10 09:21:07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL),
(238, '100924091844', 'K0004', 'HUAD', 'PRODUCTION 1', '10', 11, '2024-09-10 09:18:44', '2024-09-17', 'PRODUCTION SYSTEM', '60', 61, 3, 'tqtqtq', '30000', '2024-09-10 09:21:07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(239, '100924091844', 'K0005', 'NADF', 'PRODUCTION 1', '10', 11, '2024-09-10 09:18:44', '2024-09-17', 'PRODUCTION SYSTEM', '60', 61, 100, 'tqtqtq', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'tqtqtq', '2024-09-10 09:21:07', NULL, NULL, NULL),
(240, '100924093311', '00000', 'HHHA', 'QA', '130', 131, '2024-09-10 09:33:11', '2024-09-16', 'PPC', '120', 121, 10, 'rw', '122', '2024-09-10 09:33:59', '234', '2024-09-10 09:34:30', '345', '2024-09-10 09:35:02', '789', '2024-09-10 09:35:50', '01033', '2024-09-10 09:36:50', '01266', '2024-09-10 09:39:30', '00101', '2024-09-10 09:40:37', NULL, NULL, '12121', '2024-09-10 10:11:02', NULL, NULL, NULL, NULL, NULL),
(241, '100924093311', '01900', 'HAHAHAQA', 'QA', '130', 131, '2024-09-10 09:33:11', '2024-09-16', 'PPC', '120', 121, 10, 'rw', '122', '2024-09-10 09:33:59', '234', '2024-09-10 09:34:30', '345', '2024-09-10 09:35:02', '789', '2024-09-10 09:35:50', '01033', '2024-09-10 09:36:50', '01266', '2024-09-10 09:39:30', '00101', '2024-09-10 09:40:37', NULL, NULL, '12121', '2024-09-10 10:11:02', NULL, NULL, NULL, NULL, NULL),
(242, '100924103409', 'K0100', 'HUADF', 'QA', '130', 131, '2024-09-10 10:34:09', '2024-09-24', 'PPC', '210', 211, 6, 'favian', '122', '2024-09-10 10:35:22', '234', '2024-10-01 14:23:12', '345', '2024-10-01 14:23:58', '789', '2024-10-01 14:40:50', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'rw', '2024-09-27 07:51:17', NULL, NULL, NULL),
(243, '100924103409', 'P0177', 'BKJDSV', 'QA', '130', 131, '2024-09-10 10:34:09', '2024-09-24', 'PPC', '210', 211, 100, 'favian', '122', '2024-09-10 10:35:22', '234', '2024-09-10 13:19:07', '345', '2024-09-27 07:51:17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'fafa', '2024-10-01 14:40:50', NULL, NULL, NULL),
(247, '110924073639', 'K1029', 'Muhhammad Abdul Pandu Winoto', 'HRD IR', '240', 241, '2024-09-11 07:36:39', '2024-09-17', 'MIS', '210', 211, 3, 'OKE', '90010', '2024-09-26 15:20:45', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(249, '120924101010', 'K010', 'JKADS', 'PRODUCTION 1', '10', 11, '2024-09-12 00:00:00', '2024-09-19', 'PRODUCTION 2', '20', 21, 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '90010', '2024-09-26 13:36:20', 'OKE', '2024-09-13 14:44:01', 1, '2024-09-13 15:19:50', NULL),
(250, '120924100059', '01900', 'HAHAHAQA', 'QA', '130', 131, '2024-09-12 10:00:59', '2024-09-24', 'VDD', '170', 171, 10, 'rw', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(251, '120924101010', 'K0192', 'GAHB AGGA', 'PRODUCTION 1', '10', 11, '2024-09-12 00:00:00', '2024-09-19', 'PRODUCTION 2', '20', 21, 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '90010', '2024-09-26 13:36:20', 'Aldo', '2024-09-26 13:34:08', 1, '2024-09-17 09:58:29', NULL),
(252, '130924073354', '122', 'favian', 'QA', '130', 131, '2024-09-13 07:33:54', '2024-09-23', 'PPC', '210', 211, 4, 'rw', '122', '2024-10-14 00:00:00', 'lala', '2024-10-08 00:00:00', NULL, '2024-10-01 08:54:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(271, '170924110504', '01920', 'HJS', 'QA', '130', 131, '2024-09-17 11:05:04', '2024-09-22', 'PPC', '120', 121, 11, 'favian', '122', '2024-09-17 11:07:05', '234', '2024-09-17 11:07:55', '345', '2024-09-17 11:08:43', '789', '2024-09-17 12:09:10', '01033', '2024-09-17 13:05:09', '01033', '2024-09-17 13:17:08', '00101', '2024-09-17 13:18:09', '00101', NULL, '90010', '2024-09-17 15:17:40', NULL, NULL, NULL, NULL, NULL),
(272, '170924110504', '9103', 'albin', 'QA', '130', 131, '2024-09-17 11:05:04', '2024-09-22', 'PPC', '120', 121, 11, 'favian', '122', '2024-09-17 11:07:05', '234', '2024-09-17 11:07:55', '345', '2024-09-17 11:08:43', '789', '2024-09-17 12:09:10', '01033', '2024-09-17 13:05:09', '01033', '2024-09-17 13:17:08', '00101', '2024-09-17 13:18:09', '00101', NULL, '90010', '2024-09-17 15:17:40', NULL, NULL, 1, '2024-09-17 14:51:25', NULL),
(284, '230924082349', '01920', 'HJS', 'QA', '130', 131, '2024-09-23 08:23:49', '2024-09-29', 'PPC', '120', 121, 10, 'rw', '122', '2024-09-23 08:28:14', '234', '2024-09-23 08:34:10', '345', '2024-09-23 08:40:00', '789', '2024-09-23 09:09:33', '01033', '2024-09-23 09:11:53', '01266', '2024-09-23 09:14:38', '00101', '2024-09-23 09:16:56', NULL, NULL, '90010', '2024-09-23 09:22:02', NULL, NULL, NULL, NULL, NULL),
(286, '240924144820', '01900', 'HAHAHAQA', 'QA', '130', 131, '2024-09-24 14:48:20', '2024-09-30', 'EHS', '260', 261, 2, 'wew', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(287, '240924144820', '01920', 'HJS', 'QA', '130', 131, '2024-09-24 14:48:20', '2024-09-30', 'EHS', '260', 261, 2, 'wew', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(289, '250924094043', '9103', 'albin', 'QA', '130', 131, '2024-09-25 09:40:43', '2024-09-30', 'PPC', '210', 221, 7, 'rw', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(290, '260924152448', 'P0101', 'FAFAFA', 'QA', '130', 131, '2024-09-26 15:24:48', '2024-09-29', 'EHS', '260', 261, 2, 'rw', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(291, '011024140906', '01922', 'KGHADF', 'QA', '130', 131, '2024-10-01 14:09:06', '2024-10-16', 'PPC', '210', 211, 8, 'rw', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '00101', '2024-10-02 08:53:33', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(292, '011024140906', '234', 'wew', 'QA', '130', 131, '2024-10-01 14:09:06', '2024-10-16', 'PPC', '210', 211, 8, 'rw', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '00101', '2024-10-02 08:53:33', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(293, '041024074425', '00000', 'HHHA', 'QA', '120', 121, '2024-10-04 07:44:25', '2024-10-20', 'VDD', '170', 171, 10, 'favian', '122', '2024-10-04 07:46:42', '234', '2024-10-04 07:49:40', '345', '2024-10-04 07:53:09', '231', '2024-10-04 07:55:46', '01033', '2024-10-04 08:09:19', '01166', '2024-10-04 08:11:06', '00101', '2024-10-04 08:14:26', '01201', '2024-10-04 08:16:33', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(294, '071024144904', '9191', NULL, 'QA', '130', 131, '2024-10-07 14:49:04', '2024-10-27', 'MIS', '210', 211, 5, '345', '122', '2024-10-07 06:24:54', '234', '2024-10-09 07:40:57', '345', '2024-10-07 14:53:10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(296, '081024151934', '00000', 'HHHA', 'QA', '120', 121, '2024-10-08 15:19:34', '2024-10-20', 'VDD', '170', 171, 2, '345', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notif`
--

CREATE TABLE `notif` (
  `id` int(11) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `message` text,
  `flags` varchar(10) DEFAULT NULL,
  `send_dt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notif`
--

INSERT INTO `notif` (`id`, `phone_number`, `message`, `flags`, `send_dt`) VALUES
(15, '0891912', 'Pemberitahuan Mutasi Karyawan!!\n\nKami informasikan bahwa Batch Mutasi [041024074425] telah di-approve oleh Kepala Departemen QA. Status saat ini adalah menunggu persetujuan Kepala Divisi Quality Assurance.\nBerikut daftar karyawan yang disetujui:\n1. 00000 - HHHA\n\nMohon untuk memproses persetujuan sesuai prosedur yang berlaku.\nTerima kasih atas perhatian dan kerjasamanya.', 'queue', NULL),
(16, '0891912', 'Pemberitahuan Mutasi Karyawan!!\n\nKami informasikan bahwa Batch Mutasi [041024074425] telah di-approve oleh Kepala Departemen QA. Status saat ini adalah menunggu persetujuan Kepala Divisi Quality Assurance.\nBerikut daftar karyawan yang disetujui:\n1. 00000 - HHHA\n\nMohon untuk memproses persetujuan sesuai prosedur yang berlaku.\nTerima kasih atas perhatian dan kerjasamanya.', 'queue', NULL),
(17, '0892919192', 'Pemberitahuan Mutasi Karyawan!!\n\nKami informasikan bahwa Batch Mutasi [041024074425] telah di-approve oleh Kepala Departemen QA. Status saat ini adalah menunggu persetujuan Kepala Departemen VDD.\nBerikut daftar karyawan yang disetujui:\n1. 00000 - HHHA\n\nMohon untuk memproses persetujuan sesuai prosedur yang berlaku.\nTerima kasih atas perhatian dan kerjasamanya.', 'queue', NULL),
(18, '08919271821', 'Pemberitahuan Mutasi Karyawan!!\n\nKepala Departemen telah memberikan remark pada batch mutasi 041024074425 sebagai berikut:\nASASASSAAS\nDaftar Karyawan yang terlibat dalam mutasi ini:\n1. 00000 - HHHA', 'queue', NULL),
(19, '0891912', 'Pemberitahuan Mutasi Karyawan!!\n\nKami informasikan bahwa Batch Mutasi [041024074425] telah di-approve oleh Kepala Departemen VDD. Status saat ini adalah menunggu persetujuan Kepala Divisi Quality Assurance.\nBerikut daftar karyawan yang disetujui:\n1. 00000 - HHHA\n\nMohon untuk memproses persetujuan sesuai prosedur yang berlaku.\nTerima kasih atas perhatian dan kerjasamanya.', 'queue', NULL),
(20, '08999999999', 'Pemberitahuan Mutasi Karyawan!!\n\nKepala Divisi Quality Assurance telah memberikan remark pada batch mutasi 041024074425 sebagai berikut:\nlalaljjsjsa\nDaftar Karyawan yang terlibat dalam mutasi ini:\n1. 00000 - HHHA', 'queue', NULL),
(21, '0892919192', 'Pemberitahuan Mutasi Karyawan!!\n\nKepala Divisi Quality Assurance telah memberikan remark pada batch mutasi 041024074425 sebagai berikut:\nAAA\nDaftar Karyawan yang terlibat dalam mutasi ini:\n1. 00000 - HHHA', 'queue', NULL),
(22, '0899919292', 'Pemberitahuan Mutasi Karyawan!!\n\nKami informasikan bahwa Batch Mutasi [041024074425] telah di-approve oleh Kepala Divisi Quality Assurance. Status saat ini adalah menunggu persetujuan Kepala Divisi Marketing & Procurement.\nBerikut daftar karyawan yang disetujui:\n1. 00000 - HHHA\n\nMohon untuk memproses persetujuan sesuai prosedur yang berlaku.\nTerima kasih atas perhatian dan kerjasamanya.', 'queue', NULL),
(23, '0899919292', 'Pemberitahuan Mutasi Karyawan!!\n\nKami informasikan bahwa Batch Mutasi [041024074425] telah di-approve oleh Kepala Divisi Quality Assurance. Status saat ini adalah menunggu persetujuan Kepala Divisi Marketing & Procurement.\nBerikut daftar karyawan yang disetujui:\n1. 00000 - HHHA\n\nMohon untuk memproses persetujuan sesuai prosedur yang berlaku.\nTerima kasih atas perhatian dan kerjasamanya.', 'queue', NULL),
(24, '08787878787', 'Pemberitahuan Mutasi Karyawan!!\n\nKami informasikan bahwa Batch Mutasi [041024074425] telah di-approve oleh Kepala Divisi Quality Assurance. Status saat ini adalah menunggu persetujuan HRD.\nBerikut daftar karyawan yang disetujui:\n1. 00000 - HHHA\n\nMohon untuk memproses persetujuan sesuai prosedur yang berlaku.\nTerima kasih atas perhatian dan kerjasamanya.', 'queue', NULL),
(25, '08918291', 'Pemberitahuan Mutasi Karyawan!!\n\nKami informasikan bahwa Batch Mutasi [041024074425] telah di-approve oleh Kepala Divisi Quality Assurance. Status saat ini adalah menunggu persetujuan HRD.\nBerikut daftar karyawan yang disetujui:\n1. 00000 - HHHA\n\nMohon untuk memproses persetujuan sesuai prosedur yang berlaku.\nTerima kasih atas perhatian dan kerjasamanya.', 'queue', NULL),
(26, '0891912', 'Pemberitahuan Mutasi Karyawan!!\n\nKepala Divisi Non Divisi telah memberikan remark pada batch mutasi 041024074425 sebagai berikut:\naaa\nDaftar Karyawan yang terlibat dalam mutasi ini:\n1. 00000 - HHHA', 'queue', NULL),
(27, '08123456789', 'Pemberitahuan Mutasi Karyawan!!\n\nKami informasikan bahwa Batch Mutasi [041024074425] telah di-approve oleh Kepala Divisi Marketing & Procurement. Status saat ini adalah menunggu persetujuan Direktur.\nBerikut daftar karyawan yang disetujui:\n1. 00000 - HHHA\n\nMohon untuk memproses persetujuan sesuai prosedur yang berlaku.\nTerima kasih atas perhatian dan kerjasamanya.', 'queue', NULL),
(28, '08918291', 'Pemberitahuan Mutasi Karyawan!!\n\nDirektur telah memberikan remark pada batch mutasi 041024074425 sebagai berikut:\nAAA\nDaftar Karyawan yang terlibat dalam mutasi ini:\n1. 00000 - HHHA', 'queue', NULL),
(29, '08123456789', 'Pemberitahuan Mutasi Karyawan!!\n\nKami informasikan bahwa Batch Mutasi [041024074425] telah di-approve oleh Direktur Asal. Status saat ini adalah menunggu Direktur Tujuan.\nBerikut daftar karyawan yang disetujui:\n1. 00000 - HHHA\n\nMohon untuk memproses persetujuan sesuai prosedur yang berlaku.\nTerima kasih atas perhatian dan kerjasamanya.', 'queue', NULL),
(30, '089991821', 'Pemberitahuan Mutasi Karyawan!!\n\nKami informasikan bahwa Batch Mutasi [041024074425] telah di-approve oleh Direktur Asal. Status saat ini adalah menunggu Direktur Tujuan.\nBerikut daftar karyawan yang disetujui:\n1. 00000 - HHHA\n\nMohon untuk memproses persetujuan sesuai prosedur yang berlaku.\nTerima kasih atas perhatian dan kerjasamanya.', 'queue', NULL),
(31, '08787878787', 'Pemberitahuan Mutasi Karyawan!!\n\nKami informasikan bahwa Batch Mutasi [041024074425] telah di-approve oleh Direktur. Status saat ini adalah menunggu HRD.\nBerikut daftar karyawan yang disetujui:\n1. 00000 - HHHA\n\nMohon untuk memproses persetujuan sesuai prosedur yang berlaku.\nTerima kasih atas perhatian dan kerjasamanya.', 'queue', NULL),
(32, '08918291', 'Pemberitahuan Mutasi Karyawan!!\n\nKami informasikan bahwa Batch Mutasi [041024074425] telah di-approve oleh Direktur. Status saat ini adalah menunggu HRD.\nBerikut daftar karyawan yang disetujui:\n1. 00000 - HHHA\n\nMohon untuk memproses persetujuan sesuai prosedur yang berlaku.\nTerima kasih atas perhatian dan kerjasamanya.', 'queue', NULL),
(33, '09142514261', 'Pemberitahuan mutasi!\n\nKami informasikan bahwa Batch Mutasi [071024144904] sedang dalam proses pemindahan karyawan dari Departemen QA section OQLO ke departemen MIS section IQIQ, status saat ini menunggu approval Foreman QA.\nBerikut daftar karyawan yang dimutasi:\n1. 9191 - ala\n\n\nMohon untuk segera memproses dan memberikan persetujuan sesuai prosedur yang berlaku.\nTerima kasih atas perhatian dan kerjasamanya.', 'queue', NULL),
(34, '01019201', 'Pemberitahuan mutasi!\n\nKami informasikan bahwa Batch Mutasi [071024144904] sedang dalam proses pemindahan karyawan dari Departemen QA section OQLO ke departemen MIS section IQIQ, status saat ini menunggu approval Foreman QA.\nBerikut daftar karyawan yang dimutasi:\n1. 9191 - ala\n\n\nMohon untuk segera memproses dan memberikan persetujuan sesuai prosedur yang berlaku.\nTerima kasih atas perhatian dan kerjasamanya.', 'queue', NULL),
(35, '091029111', 'Pemberitahuan mutasi!\n\nKami informasikan bahwa Batch Mutasi [071024144904] sedang dalam proses pemindahan karyawan dari Departemen QA section OQLO ke departemen MIS section IQIQ, status saat ini menunggu approval Foreman QA.\nBerikut daftar karyawan yang dimutasi:\n1. 9191 - ala\n\n\nMohon untuk segera memproses dan memberikan persetujuan sesuai prosedur yang berlaku.\nTerima kasih atas perhatian dan kerjasamanya.', 'queue', NULL),
(36, '0821309871', 'Pemberitahuan Mutasi Karyawan!!\n\nKepala Departemen telah memberikan remark pada batch mutasi 071024144904 sebagai berikut:\naaaaaaaa\nDaftar Karyawan yang terlibat dalam mutasi ini:\n1. 9191 - ', 'queue', NULL),
(37, '0891919281', 'Pemberitahuan Mutasi Karyawan!!\n\nKepala Departemen telah memberikan remark pada batch mutasi 071024144904 sebagai berikut:\naaaaaaaa\nDaftar Karyawan yang terlibat dalam mutasi ini:\n1. 9191 - ', 'queue', NULL),
(38, '087182718271', 'Pemberitahuan Mutasi Karyawan!!\n\nKami informasikan bahwa Batch Mutasi [071024144904] telah di-approve oleh Kepala Departemen QA. Status saat ini adalah menunggu persetujuan Kepala Departemen MIS.\nBerikut daftar karyawan yang disetujui:\n1. 9191 - \n\nMohon untuk memproses persetujuan sesuai prosedur yang berlaku.\nTerima kasih atas perhatian dan kerjasamanya.', 'queue', NULL),
(39, '09142514261', 'Pemberitahuan mutasi!\n\nKami informasikan bahwa Batch Mutasi [081024151840] sedang dalam proses pemindahan karyawan dari Departemen QA section OP1O ke departemen VDD section WUW, status saat ini menunggu approval Foreman QA.\nBerikut daftar karyawan yang dimutasi:\n1. 00000 - HHHA\n\n\nMohon untuk segera memproses dan memberikan persetujuan sesuai prosedur yang berlaku.\nTerima kasih atas perhatian dan kerjasamanya.', 'queue', NULL),
(40, '01019201', 'Pemberitahuan mutasi!\n\nKami informasikan bahwa Batch Mutasi [081024151840] sedang dalam proses pemindahan karyawan dari Departemen QA section OP1O ke departemen VDD section WUW, status saat ini menunggu approval Foreman QA.\nBerikut daftar karyawan yang dimutasi:\n1. 00000 - HHHA\n\n\nMohon untuk segera memproses dan memberikan persetujuan sesuai prosedur yang berlaku.\nTerima kasih atas perhatian dan kerjasamanya.', 'queue', NULL),
(41, '091029111', 'Pemberitahuan mutasi!\n\nKami informasikan bahwa Batch Mutasi [081024151840] sedang dalam proses pemindahan karyawan dari Departemen QA section OP1O ke departemen VDD section WUW, status saat ini menunggu approval Foreman QA.\nBerikut daftar karyawan yang dimutasi:\n1. 00000 - HHHA\n\n\nMohon untuk segera memproses dan memberikan persetujuan sesuai prosedur yang berlaku.\nTerima kasih atas perhatian dan kerjasamanya.', 'queue', NULL),
(42, '09142514261', 'Pemberitahuan mutasi!\n\nKami informasikan bahwa Batch Mutasi [081024151934] sedang dalam proses pemindahan karyawan dari Departemen QA section OP1O ke departemen VDD section WUW, status saat ini menunggu approval Foreman QA.\nBerikut daftar karyawan yang dimutasi:\n1. 00000 - HHHA\n\n\nMohon untuk segera memproses dan memberikan persetujuan sesuai prosedur yang berlaku.\nTerima kasih atas perhatian dan kerjasamanya.', 'queue', NULL),
(43, '01019201', 'Pemberitahuan mutasi!\n\nKami informasikan bahwa Batch Mutasi [081024151934] sedang dalam proses pemindahan karyawan dari Departemen QA section OP1O ke departemen VDD section WUW, status saat ini menunggu approval Foreman QA.\nBerikut daftar karyawan yang dimutasi:\n1. 00000 - HHHA\n\n\nMohon untuk segera memproses dan memberikan persetujuan sesuai prosedur yang berlaku.\nTerima kasih atas perhatian dan kerjasamanya.', 'queue', NULL),
(44, '091029111', 'Pemberitahuan mutasi!\n\nKami informasikan bahwa Batch Mutasi [081024151934] sedang dalam proses pemindahan karyawan dari Departemen QA section OP1O ke departemen VDD section WUW, status saat ini menunggu approval Foreman QA.\nBerikut daftar karyawan yang dimutasi:\n1. 00000 - HHHA\n\n\nMohon untuk segera memproses dan memberikan persetujuan sesuai prosedur yang berlaku.\nTerima kasih atas perhatian dan kerjasamanya.', 'queue', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `otp`
--

CREATE TABLE `otp` (
  `Id` int(11) NOT NULL,
  `npk` varchar(5) NOT NULL,
  `otp` int(11) NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `send` int(11) DEFAULT NULL,
  `send_date` datetime DEFAULT NULL,
  `use` int(11) DEFAULT NULL,
  `use_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `otp`
--

INSERT INTO `otp` (`Id`, `npk`, `otp`, `no_hp`, `send`, `send_date`, `use`, `use_date`) VALUES
(30, '345', 21471, '08999999999', 2, NULL, 2, NULL),
(31, '234', 813456, '0891919281', 2, NULL, 2, NULL),
(32, '10000', 31093, '08123456789', 2, NULL, 2, NULL),
(33, '122', 771023, '09142514261', 2, NULL, 2, NULL),
(34, '01561', 334747, '099918281', 2, NULL, 2, NULL),
(35, '12389', 37721, '087182718271', 2, NULL, 2, NULL),
(36, '789', 531144, '083333333', 2, NULL, 2, NULL),
(37, '30000', 61612, '08917281911', 2, NULL, 2, NULL),
(38, '20000', 64755, '08987654321', 2, NULL, 2, NULL),
(39, '01033', 869839, '0891912', 2, NULL, 2, NULL),
(40, '01266', 278962, '08918291', 2, NULL, 2, NULL),
(41, '00101', 862795, '08123456789', 2, NULL, 2, NULL),
(42, '12121', 65351, '08787878787', 2, NULL, 2, NULL),
(43, '21212', 456738, '08918291', 2, NULL, 2, NULL),
(44, '90010', 199179, '08918291', 2, NULL, 2, NULL),
(45, '231', 815692, '0892919192', 2, NULL, 2, NULL),
(46, '01166', 507644, '0899919292', 2, NULL, 2, NULL),
(47, '01201', 916739, '089991821', 2, NULL, 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `remarks`
--

CREATE TABLE `remarks` (
  `id_remark` int(11) NOT NULL,
  `batchMutasi` varchar(20) DEFAULT NULL,
  `remark` varchar(200) DEFAULT NULL,
  `by` varchar(100) DEFAULT NULL,
  `date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `remarks`
--

INSERT INTO `remarks` (`id_remark`, `batchMutasi`, `remark`, `by`, `date`) VALUES
(1, '130924073354', 'saasas', '345', '2024-09-25 04:16:42'),
(4, '130924073354', 'asdads', '345', '2024-09-25 04:29:38'),
(5, '250924094043', 'asdbkhjkdsa', 'rw', '2024-09-25 04:41:06'),
(6, '250924094043', 'dsadsadsa', 'rw', '2024-09-25 05:03:10'),
(7, '250924094043', 'sdad', 'rw', '2024-09-25 05:04:50'),
(8, '130924073354', 'dsasda', '345', '2024-09-25 05:05:40'),
(9, '130924073354', 'adsdsadsa', '345', '2024-09-25 05:06:44'),
(10, '', 'ddsdsd', 'rw', '2024-09-25 05:25:02'),
(11, '130924073354', 'dsads', '345', '2024-09-25 06:12:49'),
(12, '130924073354', 'asdds', '345', '2024-09-25 06:18:06'),
(13, '130924073354', 'asddasds', '345', '2024-09-25 06:19:08'),
(14, '130924073354', 'dsads', '345', '2024-09-25 06:19:48'),
(15, '', 'asdds', 'rw', '2024-09-25 06:20:47'),
(16, '130924073354', 'sasdasdasdas', '345', '2024-09-25 06:40:13'),
(17, '130924073354', 'bkjdfsdfs', '345', '2024-09-25 06:56:59'),
(18, '250924094043', 'adsads', 'rw', '2024-09-25 08:09:17'),
(19, '130924073354', 'sadkjbasd', 'Kepala Divisi Production Control', '2024-09-25 09:02:08'),
(20, '130924073354', 'kjgfaugaef', 'Kepala Divisi Production Control', '2024-09-25 09:02:53'),
(21, '130924073354', 'saddsa', 'Kepala Divisi Production Control', '2024-09-25 09:04:12'),
(22, '130924073354', 'asasas', 'Kepala Divisi Production Control', '2024-09-25 09:05:37'),
(23, '130924073354', 'dsdsadasads', 'Kepala Divisi Production Control', '2024-09-25 09:06:31'),
(25, '250924094043', 'asass', 'rw', '2024-09-25 09:08:38'),
(26, '250924094043', 'dssadsd', 'rw', '2024-09-25 09:10:20'),
(27, '250924094043', 'asas', 'rw', '2024-09-25 09:12:24'),
(28, '250924094043', 'asASAS', 'rw', '2024-09-25 09:13:56'),
(29, '250924094043', 'LALALALALAL\n', 'rw', '2024-09-25 09:14:40'),
(30, '250924094043', 'AAAAAA', 'rw', '2024-09-25 09:15:37'),
(31, '250924094043', 'JLWEF', 'rw', '2024-09-25 09:16:55'),
(32, '250924094043', 'saasas', 'rw', '2024-09-25 09:20:01'),
(33, '250924094043', 'assa', 'rw', '2024-09-25 09:22:40'),
(34, '250924094043', 'assa', 'rw', '2024-09-25 09:26:44'),
(35, '250924094043', 'sasa', 'rw', '2024-09-25 09:29:38'),
(36, '250924094043', 'assa', 'rw', '2024-09-25 09:31:13'),
(37, '250924094043', 'asas', 'rw', '2024-09-25 09:32:33'),
(38, '250924094043', 'assa', 'rw', '2024-09-25 09:32:59'),
(39, '250924094043', 'sasa', 'rw', '2024-09-25 09:33:19'),
(40, '250924094043', 'saas', 'fafa', '2024-09-25 09:34:45'),
(41, '250924094043', 'SASASA', 'fafa', '2024-09-25 09:35:32'),
(42, '250924094043', 'sasdsd', 'fafa', '2024-09-25 09:38:01'),
(43, '250924094043', 'saas', 'fafa', '2024-09-25 09:39:40'),
(44, '250924094043', 'asas', 'fafa', '2024-09-25 09:41:30'),
(45, '250924094043', 'kurang', 'Kepala Divisi Quality Assurance', '2024-09-25 09:43:50'),
(46, '250924094043', 'sas', 'Kepala Divisi Quality Assurance', '2024-09-25 09:44:40'),
(47, '250924094043', 'AA', 'Kepala Divisi Quality Assurance', '2024-09-25 09:45:51'),
(48, '250924094043', 'sasas', 'rw', '2024-09-25 09:47:08'),
(49, '250924094043', 'saas', 'Kepala Divisi Quality Assurance', '2024-09-25 09:49:22'),
(50, '250924094043', 'assa', 'Kepala Divisi Quality Assurance', '2024-09-25 09:50:22'),
(51, '250924094043', 'asddsa', 'Kepala Divisi Quality Assurance', '2024-09-25 09:53:09'),
(52, '250924094043', 'gajelas', 'Kepala Divisi Quality Assurance', '2024-09-25 09:54:49'),
(53, '250924094043', 'sadbkjda', 'Kepala Divisi Production Control', '2024-09-25 09:56:14'),
(54, '130924073354', 'xczsa', 'Kepala Divisi Production Control', '2024-09-25 09:59:05'),
(55, '250924094043', 'hi\'oadfs\'jlGRSBJ\'gr', 'Kepala Divisi Production Control', '2024-09-25 10:01:08'),
(56, '130924073354', 'bkjdsbjsadbjsda', '345', '2024-09-26 11:43:55'),
(57, '130924073354', 'dsadssad', '345', '2024-09-27 07:47:35'),
(58, '130924073354', '130924073354', '345', '2024-10-01 08:52:29'),
(59, '100924103409', 'kurangin 1', 'rw', '2024-10-01 14:15:21'),
(60, '100924103409', 'kurangi 2', 'Kepala Divisi Quality Assurance', '2024-10-01 14:41:37'),
(61, '100924103409', 'kfff', 'Kepala Divisi Quality Assurance', '2024-10-01 14:41:53'),
(62, '100924103409', 'aasas', 'Kepala Divisi Quality Assurance', '2024-10-01 14:50:03'),
(63, '100924103409', 'aa', 'Kepala Divisi Quality Assurance', '2024-10-01 14:51:07'),
(64, '100924103409', 'asas', 'Kepala Divisi Quality Assurance', '2024-10-01 14:51:59'),
(65, '100924103409', 'aas', 'Kepala Divisi Quality Assurance', '2024-10-01 14:53:42'),
(66, '100924103409', 'assa', 'Kepala Divisi Quality Assurance', '2024-10-01 14:55:29'),
(67, '100924103409', 'asas', 'Kepala Divisi Quality Assurance', '2024-10-01 14:57:12'),
(68, '100924103409', 'aa', 'Kepala Divisi Quality Assurance', '2024-10-01 14:57:48'),
(69, '100924103409', 'assa', 'Kepala Divisi Quality Assurance', '2024-10-01 14:58:31'),
(70, '100924103409', 'aaa', 'Kepala Divisi Quality Assurance', '2024-10-01 15:00:07'),
(71, '011024140906', 'aaa', 'rw', '2024-10-01 15:00:59'),
(72, '011024140906', 'aaa', 'Direktur Plant', '2024-10-01 15:02:17'),
(73, '011024140906', 'aa', 'Direktur Plant', '2024-10-01 15:04:07'),
(74, '011024140906', 'aa', 'Direktur Plant', '2024-10-01 15:05:24'),
(75, '011024140906', 'aa', 'Direktur Plant', '2024-10-01 15:06:11'),
(76, '011024140906', 'aa', 'Direktur Plant', '2024-10-01 15:08:50'),
(77, '011024140906', 'aa', 'Kepala Divisi Quality Assurance', '2024-10-01 15:11:42'),
(78, '041024074425', 'lah', 'wew', '2024-10-04 07:45:34'),
(79, '041024074425', 'lah lagi\n', 'rw', '2024-10-04 07:48:03'),
(80, '041024074425', 'ASASASSAAS', 'yuay', '2024-10-04 07:54:11'),
(81, '041024074425', 'lalaljjsjsa', 'Kepala Divisi Quality Assurance', '2024-10-04 07:58:04'),
(82, '041024074425', 'AAA', 'Kepala Divisi Quality Assurance', '2024-10-04 07:58:53'),
(83, '041024074425', 'aaa', 'Kepala Divisi Marketing & Procurement', '2024-10-04 08:10:25'),
(84, '041024074425', 'AAA', 'Direktur Plant', '2024-10-04 08:12:44'),
(85, '041024074425', 'AAA', 'Direktur Non Plant', '2024-10-04 08:16:17'),
(86, '071024144904', 'aaaaaaaa', '345', '2024-10-07 14:52:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `batch`
--
ALTER TABLE `batch`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id_feedback`),
  ADD KEY `batchMutasi` (`batchMutasi`);

--
-- Indexes for table `mutasi`
--
ALTER TABLE `mutasi`
  ADD PRIMARY KEY (`IdMutasi`),
  ADD KEY `FK_emno` (`emno`);

--
-- Indexes for table `notif`
--
ALTER TABLE `notif`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `otp`
--
ALTER TABLE `otp`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `remarks`
--
ALTER TABLE `remarks`
  ADD PRIMARY KEY (`id_remark`),
  ADD KEY `batchMutasi` (`batchMutasi`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `batch`
--
ALTER TABLE `batch`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id_feedback` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `mutasi`
--
ALTER TABLE `mutasi`
  MODIFY `IdMutasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=297;

--
-- AUTO_INCREMENT for table `notif`
--
ALTER TABLE `notif`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `otp`
--
ALTER TABLE `otp`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `remarks`
--
ALTER TABLE `remarks`
  MODIFY `id_remark` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
