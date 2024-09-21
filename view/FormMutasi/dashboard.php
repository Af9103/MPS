<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["npk"]) || !isset($_SESSION["dept"]) || !isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true) {
  echo "Anda harus login sebagai HRD untuk mengakses halaman ini";
  header("Location: ../forbidden.php");
  exit; // Pastikan untuk menghentikan eksekusi skrip setelah melakukan redirect
}



include __DIR__ . '/../../query/koneksi.php';
include __DIR__ . '/../../query/detail.php';
include __DIR__ . '/../../query/delete.php';

$status = isset($_GET['status']) ? $_GET['status'] : '';

if (isset($_SESSION['role'])) {
  if ($_SESSION['role'] == 'Foreman HRD' || $_SESSION['role'] == 'Supervisor HRD' || $_SESSION['role'] == 'Kepala Departemen HRD') {
    $queryMutasi = "SELECT *
                  FROM mutasi
                  WHERE batchMutasi IS NOT NULL";
  } elseif ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01033') {
    $queryMutasi = "SELECT *
              FROM mutasi
              WHERE hapus IS NULL
              AND (cwocAsal IN ('QA', 'PDE 2W', 'PDE 4W', 'CQE 2W', 'CQE 4W')
                  OR 
                  cwocBaru IN ('QA', 'PDE 2W', 'PDE 4W', 'CQE 2W', 'CQE 4W'))
                      AND batchMutasi IS NOT NULL";
  } elseif ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01561') {
    $queryMutasi = "SELECT *
                FROM mutasi
                WHERE hapus IS NULL
                AND (cwocAsal IN ('HRD IR', 'GA', 'MIS')
                    OR 
                    cwocBaru IN ('HRD IR', 'GA', 'MIS')) AND
                    batchMutasi IS NOT NULL";
  } elseif ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01961') {
    $queryMutasi = "SELECT *
                  FROM mutasi
                  WHERE hapus IS NULL
                  AND (cwocAsal IN ('PCE', 'PE 2W', 'PE 4W')
                      OR 
                      cwocBaru IN ('PCE', 'PE 2W', 'PE 4W')) AND
                      batchMutasi IS NOT NULL";
  } elseif ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01166') {
    $queryMutasi = "SELECT *
                    FROM mutasi
                    WHERE hapus IS NULL
                    AND (cwocAsal IN ('MARKETING', 'PROCUREMENT', 'VENDOR DEVELOPMENT', 'GENERAL PURCHASE')
                        OR 
                        cwocBaru IN ('MARKETING', 'PROCUREMENT', 'VENDOR DEVELOPMENT', 'GENERAL PURCHASE')) AND
                        batchMutasi IS NOT NULL";
  } elseif ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01266') {
    $queryMutasi = "SELECT *
                    FROM mutasi
                    WHERE hapus IS NULL
                    AND (cwocAsal IN ('WAREHOUSE', 'PRODUCTION SYSTEM', 'PPC')
                        OR 
                        cwocBaru IN ('WAREHOUSE', 'PRODUCTION SYSTEM', 'PPC')) AND
                        batchMutasi IS NOT NULL";
  } elseif ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01577') {
    $queryMutasi = "SELECT *
                      FROM mutasi
                      WHERE hapus IS NULL
                      AND (cwocAsal IN ('PRODUCTION 1', 'PRODUCTION 2', 'PRODUCTION 3', 'PRODUCTION 4', 'PRODUCTION 5')
                          OR 
                          cwocBaru IN ('PRODUCTION 1', 'PRODUCTION 2', 'PRODUCTION 3', 'PRODUCTION 4', 'PRODUCTION 5')) AND
                          batchMutasi IS NOT NULL";
  } elseif ($_SESSION['role'] == 'Direktur Plant') {
    $queryMutasi = "SELECT *
                    FROM mutasi
                    WHERE hapus IS NULL AND cwocAsal IN ('PDE 2W', 'PDE 4W', 'QA', 'CQE 2W', 'CQE 4W', 'PCE', 'PE 2W', 'PE 4W', 'PRODUCTION 1', 'PRODUCTION 2', 'PRODUCTION 3', 'PRODUCTION 4', 'PRODUCTION 5', 'WAREHOUSE', 'PRODUCTION SYSTEM', 'PPC') AND
                    batchMutasi IS NOT NULL";

  } elseif ($_SESSION['role'] == 'Direktur Non Plant') {
    $queryMutasi = "SELECT *
                    FROM mutasi
                    WHERE hapus IS NULL AND cwocAsal IN ('HRD IR', 'MIS', 'GA', 'MARKETING', 'PROCUREMENT', 'VENDOR DEVELOPMENT', 'GENERAL PURCHASE') AND
                    batchMutasi IS NOT NULL";

  } else {
    $queryMutasi = "SELECT *
    FROM mutasi
    WHERE hapus IS NULL AND status !=100 
    AND (cwocAsal = '{$_SESSION['dept']}') 
    AND batchMutasi IS NOT NULL
";

  }
}

if ($status !== '') {
  $queryMutasi .= " AND status = $status";
}

// Apply the `status != 10` condition only if the role is not one of the specified roles
if (!($_SESSION['role'] == 'Foreman HRD' || $_SESSION['role'] == 'Supervisor HRD' || $_SESSION['role'] == 'Kepala Departemen HRD')) {
  $queryMutasi .= " AND status != 10";
}

$queryMutasi .= " GROUP BY batchMutasi ORDER BY status ASC, tanggalBuat ASC";

$resultMutasi = mysqli_query($koneksi3, $queryMutasi);
// Periksa apakah kueri berhasil dieksekusi
if (!$resultMutasi) {
  die("Query error: " . mysqli_error($koneksi3));
}

$batchCounts = [];

// Fetch the records for counting
$batchCountQuery = "SELECT batchMutasi, COUNT(*) as total FROM mutasi 
    WHERE hapus IS NULL AND status != 100 
    GROUP BY batchMutasi";

$batchCountResult = mysqli_query($koneksi3, $batchCountQuery);

// Populate the batchCounts array
while ($batchRow = mysqli_fetch_assoc($batchCountResult)) {
  $batchCounts[$batchRow['batchMutasi']] = $batchRow['total'];
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Dashboard Mutasi</title>
  <link href="../../asset/img/k-logo.jpg" rel="icon">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../asset/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="../../asset/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../../asset/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="../../asset/plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../asset/dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../../asset/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../../asset/plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="../../asset/plugins/summernote/summernote-bs4.min.css">

  <link rel="stylesheet" href="../../style.css"> <!-- Hubungkan dengan file CSS terpisah -->
  <link rel="stylesheet" href="../../asset/select/select.min.css">

</head>

<body class="hold-transition sidebar-mini layout-fixed sidebar-collapse">
  <div class="wrapper">

    <!-- Preloader -->
    <!-- <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__shake" src="../../asset/img/k-logo.jpg" alt="AdminLTELogo" height="100" width="100">
    </div> -->

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <?php include '../../layout/header.php'; ?>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <?php include '../../layout/sidebar.php'; ?>
      </nav>
      <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">

      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <?php
                $bulan_ini = date('Y-m'); // Format YYYY-MM untuk bulan ini
                if (isset($_SESSION['role'])) {
                  if ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01033') {
                    $queryJumlahData = "SELECT COUNT(DISTINCT batchMutasi) AS jumlah FROM mutasi WHERE hapus IS NULL AND cwocBaru IN ('QA', 'PDE 2W', 'PDE 4W', 'CQE 2W', 'CQE 4W') AND status= 10 AND DATE_FORMAT(tanggalMutasi, '%Y-%m') = '{$bulan_ini}'";
                  } elseif ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01561') {
                    $queryJumlahData = "SELECT COUNT(DISTINCT batchMutasi) AS jumlah FROM mutasi WHERE hapus IS NULL AND cwocBaru IN ('HRD IR', 'GA', 'MIS') AND status= 10 AND DATE_FORMAT(tanggalMutasi, '%Y-%m') = '{$bulan_ini}'";
                  } elseif ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01961') {
                    $queryJumlahData = "SELECT COUNT(DISTINCT batchMutasi) AS jumlah FROM mutasi WHERE hapus IS NULL AND cwocBaru IN ('PCE', 'PE 2W', 'PE 4W') AND status= 10 AND DATE_FORMAT(tanggalMutasi, '%Y-%m') = '{$bulan_ini}'";
                  } elseif ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01166') {
                    $queryJumlahData = "SELECT COUNT(DISTINCT batchMutasi) AS jumlah FROM mutasi WHERE hapus IS NULL AND cwocBaru IN ('MARKETING', 'PROCUREMENT', 'VENDOR DEVELOPMENT', 'GENERAL PURCHASE') AND status= 10 AND DATE_FORMAT(tanggalMutasi, '%Y-%m') = '{$bulan_ini}'";
                  } elseif ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01266') {
                    $queryJumlahData = "SELECT COUNT(DISTINCT batchMutasi) AS jumlah FROM mutasi WHERE hapus IS NULL AND cwocBaru IN ('WAREHOUSE', 'PRODUCTION SYSTEM', 'PPC') AND status= 10 AND DATE_FORMAT(tanggalMutasi, '%Y-%m') = '{$bulan_ini}'";
                  } elseif ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01577') {
                    $queryJumlahData = "SELECT COUNT(DISTINCT batchMutasi) AS jumlah FROM mutasi WHERE hapus IS NULL AND cwocBaru IN ('PRODUCTION 1', 'PRODUCTION 2', 'PRODUCTION 3', 'PRODUCTION 4', 'PRODUCTION 5') AND status= 10 AND DATE_FORMAT(tanggalMutasi, '%Y-%m') = '{$bulan_ini}'";
                  } elseif ($_SESSION['role'] == 'Direktur Plant') {
                    $queryJumlahData = "SELECT COUNT(DISTINCT batchMutasi) AS jumlah FROM mutasi WHERE hapus IS NULL AND cwocBaru IN ('PDE 2W', 'PDE 4W', 'QA', 'CQE 2W', 'CQE 4W', 'PCE', 'PE 2W', 'PE 4W', 'PRODUCTION 1', 'PRODUCTION 2', 'PRODUCTION 3', 'PRODUCTION 4', 'PRODUCTION 5', 'WAREHOUSE', 'PRODUCTION SYSTEM', 'PPC') AND status= 10 AND DATE_FORMAT(tanggalMutasi, '%Y-%m') = '{$bulan_ini}'";
                  } elseif ($_SESSION['role'] == 'Direktur Non Plant') {
                    $queryJumlahData = "SELECT COUNT(DISTINCT batchMutasi) AS jumlah FROM mutasi WHERE hapus IS NULL AND cwocBaru IN ('HRD IR', 'MIS', 'GA', 'MARKETING', 'PROCUREMENT', 'VENDOR DEVELOPMENT', 'GENERAL PURCHASE') AND status= 10 AND DATE_FORMAT(tanggalMutasi, '%Y-%m') = '{$bulan_ini}'";
                  } else {
                    $queryJumlahData = "SELECT COUNT(DISTINCT batchMutasi) AS jumlah FROM mutasi WHERE hapus IS NULL AND cwocBaru = '{$_SESSION['dept']}' AND status= 10 AND DATE_FORMAT(tanggalMutasi, '%Y-%m') = '{$bulan_ini}'";
                  }
                }

                $resultJumlahData = $koneksi3->query($queryJumlahData);

                if ($resultJumlahData) {
                  // Ambil hasil dari query
                  $row = $resultJumlahData->fetch_assoc();
                  $jumlahStatus = $row['jumlah'];
                }
                ?>
                <h3><?php echo $jumlahStatus; ?></h3>
                <p>Mutasi karyawan masuk bulan ini</p>

              </div>
              <div class="icon">
                <i class="fas fa-sign-in-alt"></i> <!-- Ganti dengan ikon yang sesuai -->
              </div>
              <a href="mutasiIn.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <?php
                $bulan_ini = date('Y-m'); // Format YYYY-MM untuk bulan ini
                
                if (isset($_SESSION['role'])) {
                  if ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01033') {
                    $queryJumlahData = "SELECT COUNT(DISTINCT batchMutasi) AS jumlah FROM mutasi WHERE hapus IS NULL AND cwocAsal IN ('QA', 'PDE 2W', 'PDE 4W', 'CQE 2W', 'CQE 4W') AND status= 10 AND DATE_FORMAT(tanggalMutasi, '%Y-%m') = '{$bulan_ini}'";
                  } elseif ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01561') {
                    $queryJumlahData = "SELECT COUNT(DISTINCT batchMutasi) AS jumlah FROM mutasi WHERE hapus IS NULL AND cwocAsal IN ('HRD IR', 'GA', 'MIS') AND status= 10 AND DATE_FORMAT(tanggalMutasi, '%Y-%m') = '{$bulan_ini}'";
                  } elseif ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01961') {
                    $queryJumlahData = "SELECT COUNT(DISTINCT batchMutasi) AS jumlah FROM mutasi WHERE hapus IS NULL AND cwocAsal IN ('PCE', 'PE 2W', 'PE 4W') AND status= 10 AND DATE_FORMAT(tanggalMutasi, '%Y-%m') = '{$bulan_ini}'";
                  } elseif ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01166') {
                    $queryJumlahData = "SELECT COUNT(DISTINCT batchMutasi) AS jumlah FROM mutasi WHERE hapus IS NULL AND cwocAsal IN ('MARKETING', 'PROCUREMENT', 'VENDOR DEVELOPMENT', 'GENERAL PURCHASE') AND status= 10 AND DATE_FORMAT(tanggalMutasi, '%Y-%m') = '{$bulan_ini}'";
                  } elseif ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01266') {
                    $queryJumlahData = "SELECT COUNT(DISTINCT batchMutasi) AS jumlah FROM mutasi WHERE hapus IS NULL AND cwocAsal IN ('WAREHOUSE', 'PRODUCTION SYSTEM', 'PPC') AND status= 10 AND DATE_FORMAT(tanggalMutasi, '%Y-%m') = '{$bulan_ini}'";
                  } elseif ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01577') {
                    $queryJumlahData = "SELECT COUNT(DISTINCT batchMutasi) AS jumlah FROM mutasi WHERE hapus IS NULL AND cwocAsal IN ('PRODUCTION 1', 'PRODUCTION 2', 'PRODUCTION 3', 'PRODUCTION 4', 'PRODUCTION 5') AND status= 10 AND DATE_FORMAT(tanggalMutasi, '%Y-%m') = '{$bulan_ini}'";
                  } elseif ($_SESSION['role'] == 'Direktur Plant') {
                    $queryJumlahData = "SELECT COUNT(DISTINCT batchMutasi) AS jumlah FROM mutasi WHERE hapus IS NULL AND cwocAsal IN ('PDE 2W', 'PDE 4W', 'QA', 'CQE 2W', 'CQE 4W', 'PCE', 'PE 2W', 'PE 4W', 'PRODUCTION 1', 'PRODUCTION 2', 'PRODUCTION 3', 'PRODUCTION 4', 'PRODUCTION 5', 'WAREHOUSE', 'PRODUCTION SYSTEM', 'PPC') AND status= 10 AND DATE_FORMAT(tanggalMutasi, '%Y-%m') = '{$bulan_ini}'";
                  } elseif ($_SESSION['role'] == 'Direktur Non Plant') {
                    $queryJumlahData = "SELECT COUNT(DISTINCT batchMutasi) AS jumlah FROM mutasi WHERE hapus IS NULL AND cwocAsal IN ('HRD IR', 'MIS', 'GA', 'MARKETING', 'PROCUREMENT', 'VENDOR DEVELOPMENT', 'GENERAL PURCHASE') AND status= 10 AND DATE_FORMAT(tanggalMutasi, '%Y-%m') = '{$bulan_ini}'";
                  } else {
                    $queryJumlahData = "SELECT COUNT(DISTINCT batchMutasi) AS jumlah FROM mutasi WHERE hapus IS NULL AND cwocAsal = '{$_SESSION['dept']}' AND status= 10 AND DATE_FORMAT(tanggalMutasi, '%Y-%m') = '{$bulan_ini}'";
                  }
                }

                $resultJumlahData = $koneksi3->query($queryJumlahData);

                if ($resultJumlahData) {
                  // Ambil hasil dari query
                  $row = $resultJumlahData->fetch_assoc();
                  $jumlahStatus = $row['jumlah'];
                }
                ?>
                <h3><?php echo $jumlahStatus; ?></h3>
                <p>Mutasi karyawan keluar bulan ini</p>
              </div>
              <div class="icon">
                <i class="fas fa-sign-out-alt"></i> <!-- Ganti dengan ikon yang sesuai -->
              </div>
              <a href="mutasiOut.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <?php
                if (isset($_SESSION['role'])) {
                  if ($_SESSION['role'] == 'Foreman') {
                    $queryJumlahData = "SELECT COUNT(DISTINCT batchMutasi) AS jumlah FROM mutasi WHERE hapus IS NULL AND mutasi.cwocAsal = '{$_SESSION['dept']}' AND mutasi.status = '2'";
                  } elseif ($_SESSION['role'] == 'Foreman HRD') {
                    $queryJumlahData = "SELECT COUNT(DISTINCT batchMutasi) AS jumlah FROM mutasi WHERE hapus IS NULL AND (mutasi.status = '9' OR (mutasi.status = '2' AND mutasi.cwocAsal = '{$_SESSION['dept']}'))";
                  } elseif ($_SESSION['role'] == 'Supervisor') {
                    $queryJumlahData = "SELECT COUNT(DISTINCT batchMutasi) AS jumlah FROM mutasi WHERE hapus IS NULL AND mutasi.cwocAsal = '{$_SESSION['dept']}' AND mutasi.status = '3'";
                  } elseif ($_SESSION['role'] == 'Supervisor HRD') {
                    $queryJumlahData = "SELECT COUNT(DISTINCT batchMutasi) AS jumlah FROM mutasi WHERE hapus IS NULL AND (mutasi.status = '9' OR (mutasi.status = '3' AND mutasi.cwocAsal = '{$_SESSION['dept']}'))";
                  } elseif ($_SESSION['role'] == 'Kepala Departemen' || $_SESSION['role'] == 'Kepala Departemen HRD') {
                    $queryJumlahData = "SELECT COUNT(DISTINCT batchMutasi) AS jumlah FROM mutasi WHERE hapus IS NULL AND ((mutasi.cwocAsal = '{$_SESSION['dept']}' AND mutasi.status = '4') OR (mutasi.cwocBaru = '{$_SESSION['dept']}' AND mutasi.status = '5'))";
                  } elseif ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01561') {
                    $queryJumlahData = "SELECT COUNT(DISTINCT batchMutasi) AS jumlah FROM mutasi WHERE hapus IS NULL AND ((mutasi.cwocAsal IN ('HRD IR', 'MIS', 'GA') AND mutasi.status = '6') OR (mutasi.cwocBaru IN ('HRD IR', 'MIS', 'GA') AND mutasi.status = '7'))";
                  } elseif ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01033') {
                    $queryJumlahData = "SELECT COUNT(DISTINCT batchMutasi) AS jumlah FROM mutasi WHERE hapus IS NULL AND ((mutasi.cwocAsal IN ('QA', 'PDE 2W', 'PDE 4W', 'CQE 2W', 'CQE 4W') AND mutasi.status = '6') OR (mutasi.cwocBaru IN ('QA', 'PDE 2W', 'PDE 4W', 'CQE 2W', 'CQE 4W') AND mutasi.status = '7'))";
                  } elseif ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01961') {
                    $queryJumlahData = "SELECT COUNT(DISTINCT batchMutasi) AS jumlah FROM mutasi WHERE hapus IS NULL AND ((mutasi.cwocAsal IN ('PCE', 'PE 2W', 'PE 4W') AND mutasi.status = '6') OR (mutasi.cwocBaru IN ('PCE', 'PE 2W', 'PE 4W') AND mutasi.status = '7'))";
                  } elseif ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01166') {
                    $queryJumlahData = "SELECT COUNT(DISTINCT batchMutasi) AS jumlah FROM mutasi WHERE hapus IS NULL AND ((mutasi.cwocAsal IN ('MARKETING', 'PROCUREMENT', 'VENDOR DEVELOPMENT', 'GENERAL PURCHASE') AND mutasi.status = '6') OR (mutasi.cwocBaru IN ('MARKETING', 'PROCUREMENT', 'VENDOR DEVELOPMENT', 'GENERAL PURCHASE') AND mutasi.status = '7'))";
                  } elseif ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01266') {
                    $queryJumlahData = "SELECT COUNT(DISTINCT batchMutasi) AS jumlah FROM mutasi WHERE hapus IS NULL AND ((mutasi.cwocAsal IN ('WAREHOUSE', 'PRODUCTION SYSTEM', 'PPC') AND mutasi.status = '6') OR (mutasi.cwocBaru IN ('WAREHOUSE', 'PRODUCTION SYSTEM', 'PPC') AND mutasi.status = '7'))";
                  } elseif ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01577') {
                    $queryJumlahData = "SELECT COUNT(DISTINCT batchMutasi) AS jumlah FROM mutasi WHERE hapus IS NULL AND ((mutasi.cwocAsal IN ('PRODUCTION 1', 'PRODUCTION 2', 'PRODUCTION 3', 'PRODUCTION 4', 'PRODUCTION 5') AND mutasi.status = '6') OR (mutasi.cwocBaru IN ('PRODUCTION 1', 'PRODUCTION 2', 'PRODUCTION 3', 'PRODUCTION 4', 'PRODUCTION 5') AND mutasi.status = '7'))";
                  } elseif ($_SESSION['role'] == 'Direktur Plant') {
                    $queryJumlahData = "SELECT COUNT(DISTINCT batchMutasi) AS jumlah FROM mutasi WHERE hapus IS NULL AND mutasi.status = '8' AND mutasi.cwocAsal IN ('PDE 2W', 'PDE 4W', 'QA', 'CQE 2W', 'CQE 4W', 'PCE', 'PE 2W', 'PE 4W', 'PRODUCTION 1', 'PRODUCTION 2', 'PRODUCTION 3', 'PRODUCTION 4', 'PRODUCTION 5', 'WAREHOUSE', 'PRODUCTION SYSTEM', 'PPC')";
                  } elseif ($_SESSION['role'] == 'Direktur Non Plant') {
                    $queryJumlahData = "SELECT COUNT(DISTINCT batchMutasi) AS jumlah FROM mutasi WHERE  hapus IS NULL AND mutasi.status = '8' AND mutasi.cwocAsal IN ('HRD IR', 'MIS', 'GA', 'MARKETING', 'PROCUREMENT', 'VENDOR DEVELOPMENT', 'GENERAL PURCHASE')";
                  }
                }

                $resultJumlahData = $koneksi3->query($queryJumlahData);

                if ($resultJumlahData) {
                  // Ambil hasil dari query
                  $row = $resultJumlahData->fetch_assoc();
                  $jumlahStatus = $row['jumlah'];
                }
                ?>
                <h3><?php echo $jumlahStatus; ?></h3>
                <p>Memerlukan tindakan</p>
              </div>
              <div class="icon">
                <i class="nav-icon fas fa-exchange-alt"></i> <!-- Ganti dengan ikon yang sesuai -->
              </div>
              <a href="approvalMutasi.php" class="small-box-footer">More info <i
                  class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->

        </div>
        <!-- ./col -->
        <br>

      </div>

      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-2 col-md-6 col-6" style="margin-bottom: 20px;">

            <!-- Elemen filter tahun -->
            <div class="input-group-prepend" style="width: 150px; margin-top:-20px;">
              <select id="statusFilter" class="selectpicker" data-live-search="true">
                <option value="">Semua Status</option>
                <option value="2">Menunggu Foreman</option>
                <option value="3">Menunggu Supervisor</option>
                <option value="4">Menunggu Ka.Dept Asal</option>
                <option value="5">Menunggu Ka.Dept Tujuan</option>
                <option value="6">Menunggu Ka.Div Asal</option>
                <option value="7">Menunggu Ka.Div Tujuan</option>
                <option value="8">Menunggu Direktur</option>
                <option value="9">Menunggu HRD</option>
                <!-- <option value="10">Finish</option> -->
              </select>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <?php
                if ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01033') {
                  echo 'Daftar Mutasi Divisi Quality Assurance';
                } elseif ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01561') {
                  echo 'Daftar Mutasi Divisi HRGA & MIS';
                } elseif ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01961') {
                  echo 'Daftar Mutasi Divisi ENGINEERING';
                } elseif ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01166') {
                  echo 'Daftar Mutasi Divisi Marketing & Procurement';
                } elseif ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01266') {
                  echo 'Daftar Mutasi Divisi Production Control';
                } elseif ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01577') {
                  echo 'Daftar Mutasi Divisi PRODUCTION';
                } elseif ($_SESSION['dept'] == 'BOD PLANT') {
                  echo 'Daftar Mutasi Plant';
                } elseif ($_SESSION['dept'] == 'BOD Non Plant') {
                  echo 'Daftar Mutasi Non Plant';
                } elseif ($_SESSION['role'] == 'Foreman HRD' || $_SESSION['role'] == 'Supervisor HRD' || $_SESSION['role'] == 'Kepala Departemen HRD') {
                  echo 'Daftar Seluruh Mutasi';
                } else {
                  echo 'Daftar Mutasi Departemen ' . $_SESSION['dept'];
                }
                ?>
              </h3>

              <div class="card-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                  <input type="text" name="table_search" class="form-control float-right" placeholder="Search">
                  <div class="input-group-append">
                    <button type="submit" class="btn btn-default">
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.card-header -->
            <div id="dasbor" class="card-body table-responsive p-0" style="max-height: 330px;">
              <table class="table table-head-fixed text-nowrap mx-auto" style="width: 100%;">
                <thead class="thead-fixed">
                  <tr>
                    <th rowspan="2" style="vertical-align: middle;">No</th>
                    <th rowspan="2" style="vertical-align: middle;">Batch Mutasi</th>
                    <th colspan="2">Dari</th>
                    <th colspan="2">Ke</th>
                    <th rowspan="2" style="vertical-align: middle;">Tanggal Mutasi</th>
                    <th rowspan="2" style="vertical-align: middle;">Jumlah</th>
                    <th rowspan="2" style="vertical-align: middle;">Status</th>
                    <th rowspan="2" style="vertical-align: middle;">Aksi</th>
                  </tr>
                  <tr>
                    <th rowspan="1">Departemen</th>
                    <th rowspan="1">Seksi</th>
                    <th rowspan="1">Departemen</th>
                    <th rowspan="1">Seksi</th>
                  </tr>
                </thead>

                <tbody id="table-body">
                  <?php
                  $no = 1; // Definisikan variabel $no di sini
                  while ($row = mysqli_fetch_assoc($resultMutasi)) {
                    $statusMessage = '';
                    $badgeColor = '';

                    switch ($row['status']) {
                      case 2:
                        $statusMessage = 'Menunggu Foreman';
                        $badgeColor = 'badge-warning';
                        break;
                      case 3:
                        $statusMessage = 'Menunggu Supervisor';
                        $badgeColor = 'badge-warning';
                        break;
                      case 4:
                        $statusMessage = 'Menunggu Ka.Dept Asal';
                        $badgeColor = 'badge-warning';
                        break;
                      case 5:
                        $statusMessage = 'Menunggu Ka.Dept Tujuan';
                        $badgeColor = 'badge-warning';
                        break;
                      case 6:
                        $statusMessage = 'Menunggu Ka.Div Asal';
                        $badgeColor = 'badge-warning';
                        break;
                      case 7:
                        $statusMessage = 'Menunggu Ka.Div Tujuan';
                        $badgeColor = 'badge-warning';
                        break;
                      case 8:
                        $statusMessage = 'Menunggu Direktur';
                        $badgeColor = 'badge-warning';
                        break;
                      case 9:
                        $statusMessage = 'Menunggu HRD';
                        $badgeColor = 'badge-warning';
                        break;
                      case 10:
                        $statusMessage = 'Selesai';
                        $badgeColor = 'badge-success';
                        break;
                      default:
                        $statusMessage = 'Unknown';
                        $badgeColor = 'badge-danger';
                        break;
                    }

                    $date = new DateTime($row['tanggalMutasi']);
                    $formattedDate = $date->format('d M Y');

                    $dateBuat = new DateTime($row['tanggalBuat']);
                    $formattedDateBuat = $dateBuat->format('d M Y');
                    $sectAsal = $row['sectAsal'];
                    $sectBaru = $row['sectBaru'];
                    $emno = $row['emno'];

                    // Fetch sectAsalDesc from the hrd_sect table using $koneksi2
                    $querySectAsal = "SELECT `desc` AS sectAsalDesc FROM hrd_sect WHERE sect = '$sectAsal'";
                    $resultSectAsal = mysqli_query($koneksi2, $querySectAsal);
                    if ($resultSectAsal) {
                      $sectAsalData = mysqli_fetch_assoc($resultSectAsal);
                      $row['sectAsalDesc'] = $sectAsalData['sectAsalDesc'];
                    }

                    // Fetch sectBaruDesc from the hrd_sect table using $koneksi2
                    $querySectBaru = "SELECT `desc` AS sectBaruDesc FROM hrd_sect WHERE sect = '$sectBaru'";
                    $resultSectBaru = mysqli_query($koneksi2, $querySectBaru);
                    if ($resultSectBaru) {
                      $sectBaruData = mysqli_fetch_assoc($resultSectBaru);
                      $row['sectBaruDesc'] = $sectBaruData['sectBaruDesc'];
                    }

                    // Fetch full_name from the ct_users table using $koneksi2
                    $queryFullName = "SELECT full_name FROM ct_users WHERE npk = '$emno'";
                    $resultFullName = mysqli_query($koneksi2, $queryFullName);
                    if ($resultFullName) {
                      $fullNameData = mysqli_fetch_assoc($resultFullName);
                      $row['full_name'] = $fullNameData['full_name'];
                    } else {
                      $row['full_name'] = "N/A";  // Handle the case where the name is not found
                    }

                    $batchMutasiCount = isset($batchCounts[$row['batchMutasi']]) ? $batchCounts[$row['batchMutasi']] : 0;

                    echo "<tr>";
                    echo "<td>" . $no++ . "</td>";
                    echo "<td>" . $row['batchMutasi'] . "</td>";
                    echo "<td>" . $row['cwocAsal'] . "</td>";
                    echo "<td>" . $row['sectAsalDesc'] . "</td>"; // Use sectAsalDesc
                    echo "<td>" . $row['cwocBaru'] . "</td>";
                    echo "<td>" . $row['sectBaruDesc'] . "</td>";
                    echo "<td>" . $formattedDate . "</td>";
                    echo '<td style="display:none;">' . $formattedDateBuat . '</td>';
                    echo '<td style="display:none;">' . $row['sectBaru'] . '</td>';
                    echo '<td style="display:none;">' . $row['subsectBaru'] . '</td>';
                    echo '<td style="display:none;">' . $row['tanggalMutasi'] . '</td>';
                    echo '<td>' . $batchMutasiCount . '</td>';
                    echo "<td><span class='badge $badgeColor'>" . $statusMessage . "</span></td>";
                    echo "<td>
                    <a href='../../output/pdfBatchMutasi.php?batchMutasi=" . htmlspecialchars($row['batchMutasi']) . "' class='btn btn-success btn-sm btn-pdf' target='_blank' style='margin-right: 3px;'>
                        <i class='fas fa-file-pdf' style='color: white;'></i>
                    </a>";

                    echo "<a><button class='btn btn-primary btn-sm btn-detail' data-toggle='modal' data-target='#detailModal' data-id='" . htmlspecialchars($row['batchMutasi']) . "'>
                        <i class='fas fa-info-circle' style='color: white;'></i>
                    </button></a>";

                    $roles_status_map = [
                      'Foreman' => [2],
                      'Foreman HRD' => [2],
                      'Supervisor' => [2, 3],
                      'Supervisor HRD' => [2, 3],
                      'Kepala Departemen' => [2, 3, 4, 5],
                      'Kepala Departemen HRD' => [2, 3, 4, 5],
                      'Kepala Divisi' => [2, 3, 4, 5, 6, 7],
                      'Direktur Plant' => [2, 3, 4, 5, 6, 7, 8],
                      'Direktur Non Plant' => [2, 3, 4, 5, 6, 7, 8]
                    ];

                    $current_role = $_SESSION['role'];
                    $cwocAsal = $row['cwocAsal']; // Assuming cwocAsal is available in $row
                    $hrdRoles = ['Foreman HRD', 'Supervisor HRD', 'Kepala Departemen HRD'];

                    if (
                      isset($roles_status_map[$current_role]) &&
                      in_array($row['status'], $roles_status_map[$current_role]) &&
                      (
                        !in_array($current_role, $hrdRoles) || $cwocAsal === 'HRD IR'
                      )
                    ) {
                      echo "<a>
                              <button onclick=\"confirmDelete('" . htmlspecialchars($row['batchMutasi']) . "', '" . htmlspecialchars($row['batchMutasi']) . "')\" class='btn btn-danger btn-sm'>
                                  <i class='fas fa-trash' style='color: white;'></i>
                              </button>
                            </a>";
                    }




                    echo "</td>";



                    echo "</tr>";
                  }
                  // If no data is found, display a message in a table row
                  if (mysqli_num_rows($resultMutasi) == 0) {
                    echo "<tr><td colspan='10'>Result Not Found</td></tr>";
                  }

                  echo "<script>";
                  echo "function confirmDelete(batchMutasi) {"; // Menambahkan parameter reg_no
                  echo "  Swal.fire({";
                  echo "    title: 'Yakin ingin menghapus Pengajuan Mutasi ini?',";
                  echo "    icon: 'warning',";
                  echo "    showCancelButton: true,";
                  echo "    confirmButtonColor: '#d33',";
                  echo "    cancelButtonColor: '#3085d6',";
                  echo "    confirmButtonText: 'Ya, hapus!',";
                  echo "    cancelButtonText: 'Batal'";
                  echo "  }).then((result) => {";
                  echo "    if (result.isConfirmed) {";
                  echo "      Swal.fire({";
                  echo "        position: 'center',";
                  echo "        icon: 'success',";
                  echo "        title: 'Data berhasil dihapus',";
                  echo "        showConfirmButton: false,";
                  echo "        timer: 2000";
                  echo "      });";
                  echo "      setTimeout(function(){ window.location.href = '?update=' + IdMutasi; }, 2000);"; // Redirect ke halaman index.php setelah menutup SweetAlert
                  echo "    }";
                  echo "  });";
                  echo "}";
                  echo "</script>";
                  ?>
                </tbody>
              </table>


            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>
      <!-- /.container-fluid -->

      <div class="modal fade custom-modal" id="detailModal" tabindex="-1" role="dialog"
        aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable custom-width" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="detailModalLabel">Mutasi dari dept</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <!-- First Table -->
              <h6 class="mb-3 font-weight-bold">Detail Batch Mutasi</h6>
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>NPK</th>
                    <th>Nama</th>
                    <th>Tanggal Buat</th>
                    <th>Tanggal Mutasi</th>
                    <th class="aksi-header">Aksi</th>
                  </tr>
                </thead>
                <tbody id="modal-body-content">
                  <!-- Data will be inserted here by JavaScript -->
                </tbody>
              </table>

              <!-- Second Table -->
              <h6 class="mt-4 mb-3 font-weight-bold">Riwayat Status Batch Mutasi</h6>
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Status</th>
                    <th>Disetujui Oleh</th>
                    <th>Tanggal Disetujui</th>
                  </tr>
                </thead>
                <tbody id="second-table-body">
                  <!-- Additional data for the second table will be inserted here -->
                </tbody>
              </table>
              <div class="form-check aksi-check-all">
                <input type="checkbox" class="form-check-input" id="check-all">
                <label class="form-check-label" for="check-all">Check All</label>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" id="delete-selected" class="btn btn-danger aksi-check-all">Hapus</button>
            </div>
          </div>
        </div>
      </div>



      <?php
      // Periksa apakah parameter 'update' telah diset
      if (isset($_GET['update'])) {
        // Periksa apakah nilai 'update' adalah angka
        if (is_numeric($_GET['update'])) {
          // Mengamankan inputan $_GET['update']
          $update_id = mysqli_real_escape_string($koneksi3, $_GET['update']);

          // Query untuk mendapatkan batchMutasi terkait dengan IdMutasi
          $queryBatchMutasi = "SELECT batchMutasi FROM mutasi WHERE batchMutasi = '$update_id'";
          $result = mysqli_query($koneksi3, $queryBatchMutasi);

          if ($result) {
            $row = mysqli_fetch_assoc($result);
            if ($row) {
              $batchMutasi = $row['batchMutasi'];

              // Query untuk menghapus data berdasarkan batchMutasi
              $queryDelete = "DELETE FROM mutasi WHERE batchMutasi = '$batchMutasi'";

              $queryDelete2 = "DELETE FROM batch WHERE batchMutasi = '$batchMutasi'";

              // Menjalankan query dan menangani kesalahan jika terjadi
              if (mysqli_query($koneksi3, $queryDelete) && mysqli_query($koneksi3, $queryDelete2)) {
                // Redirect ke halaman dashboard.php setelah menghapus data
                echo "<script>document.location='dashboard.php';</script>";
                // Hentikan eksekusi skrip setelah melakukan redirect
                exit();
              } else {
                // Menampilkan pesan kesalahan jika query gagal dieksekusi
                echo "Error: " . mysqli_error($koneksi3);
              }
            }
          } else {
            echo "Error: " . mysqli_error($koneksi3);
          }
        } else {
          // Menampilkan pesan jika nilai 'update' tidak valid
          echo "Error: Nilai 'update' tidak valid";
        }
      }
      ?>




    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <?php include '../../layout/footer.php'; ?>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

  <!-- jQuery -->
  <script src="../../asset/plugins/jquery/jquery.min.js"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="../../asset/plugins/jquery-ui/jquery-ui.min.js"></script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button)
  </script>
  <!-- Bootstrap 4 -->
  <script src="../../asset/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- ChartJS -->
  <script src="../../asset/plugins/chart.js/Chart.min.js"></script>
  <!-- Sparkline -->
  <script src="../../asset/plugins/sparklines/sparkline.js"></script>
  <!-- JQVMap -->
  <script src="../../asset/plugins/jqvmap/jquery.vmap.min.js"></script>
  <script src="../../asset/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
  <!-- jQuery Knob Chart -->
  <script src="../../asset/plugins/jquery-knob/jquery.knob.min.js"></script>
  <!-- daterangepicker -->
  <script src="../../asset/plugins/moment/moment.min.js"></script>
  <script src="../../asset/plugins/daterangepicker/daterangepicker.js"></script>
  <!-- Tempusdominus Bootstrap 4 -->
  <script src="../../asset/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
  <!-- Summernote -->
  <script src="../../asset/plugins/summernote/summernote-bs4.min.js"></script>
  <!-- overlayScrollbars -->
  <script src="../../asset/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <!-- AdminLTE App -->
  <script src="../../asset/dist/js/adminlte.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="../../asset/dist/js/demo.js"></script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="../../asset/dist/js/pages/dashboard.js"></script>

  <!-- Skrip JavaScript yang Anda tambahkan -->
  <script src="../../asset/JS/search.js"></script>
  <script src="../../asset/js/day.js"></script>
  <script src="../../asset/select/select.min.js"></script>


  <script>
    // Assuming you have set this variable somewhere in your HTML template
    var currentRole = '<?php echo $_SESSION['role']; ?>';

    function getStatusMessage(statusCode, rejectMessage) {
      switch (parseInt(statusCode, 10)) {
        case 2:
          return 'Menunggu Foreman';
        case 3:
          return 'Menunggu Supervisor';
        case 4:
          return 'Menunggu Ka.Dept Asal';
        case 5:
          return 'Menunggu Ka.Dept Tujuan';
        case 6:
          return 'Menunggu Ka.Div Asal';
        case 7:
          return 'Menunggu Ka.Div Tujuan';
        case 8:
          return 'Menunggu Direktur';
        case 9:
          return 'Menunggu HRD';
        case 10:
          return 'Finish';
        default:
          return `Ditolak oleh ${rejectMessage}`;
      }
    }

    function canShowCheckbox(statusCode, cwocAsal) {
      var rolesStatusMap = {
        'Foreman': ['Menunggu Foreman'],
        'Foreman HRD': ['Menunggu Foreman'],
        'Supervisor': ['Menunggu Foreman', 'Menunggu Supervisor'],
        'Supervisor HRD': ['Menunggu Foreman', 'Menunggu Supervisor'],
        'Kepala Departemen': ['Menunggu Foreman', 'Menunggu Supervisor', 'Menunggu Ka.Dept Asal',
          'Menunggu Ka.Dept Tujuan'
        ],
        'Kepala Departemen HRD': ['Menunggu Foreman', 'Menunggu Supervisor', 'Menunggu Ka.Dept Asal',
          'Menunggu Ka.Dept Tujuan'
        ],
        'Kepala Divisi': ['Menunggu Foreman', 'Menunggu Supervisor', 'Menunggu Ka.Dept Asal',
          'Menunggu Ka.Dept Tujuan', 'Menunggu Ka.Div Asal', 'Menunggu Ka.Div Tujuan'
        ],
        'Direktur Plant': ['Menunggu Foreman', 'Menunggu Supervisor', 'Menunggu Ka.Dept Asal',
          'Menunggu Ka.Dept Tujuan', 'Menunggu Ka.Div Asal', 'Menunggu Ka.Div Tujuan',
          'Menunggu Direktur'
        ],
        'Direktur Non Plant': ['Menunggu Foreman', 'Menunggu Supervisor', 'Menunggu Ka.Dept Asal',
          'Menunggu Ka.Dept Tujuan', 'Menunggu Ka.Div Asal', 'Menunggu Ka.Div Tujuan',
          'Menunggu Direktur'
        ]
      };

      // Special case for HRD roles
      if (['Foreman HRD', 'Supervisor HRD', 'Kepala Departemen HRD'].includes(currentRole)) {
        if (cwocAsal === 'HRD IR') {
          return rolesStatusMap[currentRole] && rolesStatusMap[currentRole].includes(statusCode);
        }
        return false;
      }

      return rolesStatusMap[currentRole] && rolesStatusMap[currentRole].includes(statusCode);
    }

    function formatDate(dateString) {
      const date = new Date(dateString);
      const day = date.getDate().toString().padStart(2, '0');
      const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
      const month = monthNames[date.getMonth()];
      const year = date.getFullYear().toString().slice(-2); // Last 2 digits of the year

      const hours = date.getHours().toString().padStart(2, '0');
      const minutes = date.getMinutes().toString().padStart(2, '0');

      return `${day} ${month} ${year} ${hours}:${minutes}`;
    }

    $(document).on('click', '.btn-detail', function () {
      var batchMutasi = $(this).data('id');
      console.log('Batch Mutasi:', batchMutasi);

      $.ajax({
        url: '../../query/detail.php',
        type: 'GET',
        data: {
          batchMutasi: batchMutasi
        },
        dataType: 'json',
        success: function (data) {
          console.log('AJAX Success:', data);

          var tbody = $('#modal-body-content');
          tbody.empty();

          var showAksi = false; // Flag to control visibility of "Aksi" column

          if (data.error) {
            console.error('Data Error:', data.error);
            tbody.append('<tr><td colspan="5">Error: ' + data.error + '</td></tr>');
          } else if (data.IdMutasi.length > 0) {
            $.each(data.IdMutasi, function (index, IdMutasi) {
              var statusCode = data.status[index];
              var rejectMessage = data.reject[index];
              var status = getStatusMessage(statusCode, rejectMessage);
              var nama = data.nama[index];
              var emno = data.emnos[index];
              var cwocAsal = data.cwocAsal; // Get cwocAsal
              var tanggalBuat = formatDate(data.tanggalBuat[index]);
              var tanggalMutasi = formatDate(data.tanggalMutasi[index]);
              var IdMutasi = data.IdMutasi[index];

              // Only display checkboxes based on role and status
              var checkbox = '';
              if (statusCode === 10 || status === "Finish" || !canShowCheckbox(
                status, cwocAsal)) {
                checkbox =
                  ''; // No checkbox if status is Finish or role does not permit
              } else {
                checkbox =
                  '<td><input type="checkbox" class="select-checkbox" data-id="' +
                  IdMutasi + '"></td>';
                showAksi = true; // Set flag to true if checkbox is shown
              }

              var row = '<tr><td>' + (index + 1) + '</td>' +
                '<td>' + emno + '</td>' +
                '<td>' + nama + '</td>' +
                '<td>' + tanggalBuat + '</td>' +
                '<td>' + tanggalMutasi + '</td>' + checkbox + '</tr>';

              tbody.append(row);
            });
          } else {
            tbody.append('<tr><td colspan="5">No data available</td></tr>');
          }

          var secondTableBody = $('#second-table-body');
          secondTableBody.empty();

          $.each(data.approvals, function (index, approval) {
            var status = getStatusMessage(approval.status);
            var approver = approval.approver;
            var status2 = approval.status2 ||
              ''; // Ensure status2 has a default value if undefined
            var date = formatDate(approval.date);

            var row = '<tr>' +
              '<td>' + status2 + '</td>' +
              '<td>' + approver + '</td>' +
              '<td>' + date + '</td>' +
              '</tr>';
            secondTableBody.append(row);
          });

          // Conditionally hide or show the "Aksi" column and check-all checkbox
          if (showAksi) {
            $('.aksi-header').show();
            $('.aksi-check-all').show();
          } else {
            $('.aksi-header').hide();
            $('.aksi-check-all').hide();
          }

          $('#detailModal .modal-title').text('Mutasi dari dept ' + data.cwocAsal + ' ke ' +
            data.cwocBaru);
          $('#detailModal').modal('show');
        },

        error: function (xhr, status, error) {
          console.error('AJAX Error:', status, error);
          alert('Error retrieving data.');
        }
      });
    });


    $(document).on('click', '#check-all', function () {
      var isChecked = $(this).is(':checked');
      $('.select-checkbox').prop('checked', isChecked);
    });

    $(document).on('click', '#delete-selected', function () {
      var batchMutasi = $('.btn-detail').data('id'); // Ensure this is correctly retrieved
      var selectedIdMutasi = [];

      $('.select-checkbox:checked').each(function () {
        selectedIdMutasi.push($(this).data('id'));
      });

      if (selectedIdMutasi.length === 0) {
        Swal.fire('No Selection', 'No employee numbers selected for deletion.', 'info');
        return;
      }

      Swal.fire({
        title: 'Yakin ingin menghapus Pengajuan Mutasi ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: '../../query/delete.php',
            type: 'POST',
            data: {
              batchMutasi: batchMutasi,
              IdMutasi: selectedIdMutasi
            },
            dataType: 'json',
            success: function (response) {
              if (response.success) {
                Swal.fire({
                  position: 'center',
                  icon: 'success',
                  title: 'Data berhasil dihapus',
                  showConfirmButton: false,
                  timer: 2000
                }).then(() => {
                  document.location = 'dashboard.php';
                });
              } else {
                Swal.fire('Error!', 'Error: ' + response.error, 'error');
              }
            },
            error: function (xhr, status, error) {
              console.error('AJAX Error:', status, error);
              Swal.fire('Error!', 'Error deleting records.', 'error');
            }
          });
        }
      });
    });
  </script>


  <script>
    $(document).ready(function () {
      function fetchData(status) {
        // $('#loading').show();
        // $('#table-body').hide();
        $.ajax({
          url: 'dashboard.php',
          type: 'GET',
          data: { status: status },
          success: function (response) {
            // Assume response contains the HTML to update the table
            $('#loading').hide();
            var table = $(response).find('#dasbor').html();
            // Update the table on the current page
            $('#dasbor').html(table);
            $('#table-body').show();
          },
          error: function () {
            alert('Error fetching data');
          }
        });
      }

      // Initial fetch with no filter
      fetchData('');

      // Fetch data when filter changes
      $('#statusFilter').change(function () {
        var status = $(this).val();
        fetchData(status);
      });
    });
  </script>



</body>

</html>

<style>
  .custom-modal .modal-dialog.custom-width {
    max-width: 60%;
    /* Adjust the percentage as needed */
  }

  @media (max-width: 767.98px) {
    .custom-modal .modal-dialog.custom-width {
      max-width: 95%;
      /* Adjust for smaller screens */
    }

    .custom-modal .modal-dialog {
      margin: 0;
      /* Ensure there's no extra margin on smaller screens */
    }
  }
</style>