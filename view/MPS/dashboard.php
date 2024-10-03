<?php
session_start();

// Periksa apakah pengguna sudah login dan dari departemen HRD
if (!isset($_SESSION["npk"]) || !isset($_SESSION["dept"]) || $_SESSION["dept"] !== "HRD IR" || !isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true) {
    echo "Anda harus login sebagai HRD untuk mengakses halaman ini";
    header("Location: ../forbidden.php");
    exit; // Pastikan untuk menghentikan eksekusi skrip setelah melakukan redirect
}

include __DIR__ . '/../../query/koneksi.php';
include __DIR__ . '/../../query/query.php';


$currentMonth = date('m');
$currentYear = date('Y');

// Filter data based on the selected month and year, or show data for the current month if no filter is applied
$selectedMonth = isset($_GET['bulan']) ? $_GET['bulan'] : $currentMonth;
$selectedYear = isset($_GET['tahun']) ? $_GET['tahun'] : $currentYear;

// Mengatur tanggal pada hari terakhir bulan yang dipilih
$endDate = date('Y-m-d', strtotime("last day of $selectedYear-$selectedMonth"));


// Adjust previous month and year based on the selected month
if ($selectedMonth == 1) {
    // If the selected month is January, adjust to the previous year and December
    $previousMonth = 12;
    $previousYear = $selectedYear - 1;
} else {
    // Otherwise, just decrement the month
    $previousMonth = $selectedMonth - 1;
    $previousYear = $selectedYear;
}

$previousMonthTimestamp = mktime(0, 0, 0, $previousMonth, 10, $previousYear);

// Function to build the URL for exporting to Excel
function buildExcelExportURL($selectedYear, $selectedMonth, $previousMonthTimestamp, $previousYear)
{
    $urlToPHPExcel = "../../output/excel2.php";
    if (isset($_GET['show_all'])) {
        $urlToPHPExcel .= "?show_all=1";
    } else {
        // Encode parameter values before adding them to the URL
        $urlToPHPExcel .= "?tahun=" . urlencode($selectedYear) . "&bulan=" . urlencode($selectedMonth) . "&previous_month_timestamp=" . urlencode($previousMonthTimestamp) . "&previous_year=" . urlencode($previousYear);
    }
    return $urlToPHPExcel;
}

$urlToPHPExcel = buildExcelExportURL($selectedYear, $selectedMonth, $previousMonthTimestamp, $previousYear);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Real Time Man Power | Dashboard</title>
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
    <link rel="stylesheet" href="../../asset/select/select.min.css">

    <link rel="stylesheet" href="../../style.css"> <!-- Hubungkan dengan file CSS terpisah -->
</head>

<body class="hold-transition sidebar-mini layout-fixed sidebar-collapse">
    <div class="wrapper">

        <!-- Preloader -->
        <!-- <div class="preloader flex-column justify-content-center align-items-center">
            <img src="../../asset/img/k-logo.jpg" alt="KayabaLogo" height="100" width="100">
            <div class="man-power-text">Real Time Man Power</div>
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
        </aside>
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
                        <h3 class="m-0">Halaman Utama</h3>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item active"><a href="../MPS/index.php">Beranda</a></li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">

            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-2 col-md-6 col-6" style="margin-top: -18px;">
                        <div class="input-group-prepend">
                            <select id="bulan" class="selectpicker form-control" data-live-search="true"
                                onchange="filterByMonth()">
                                <?php
                                // Retrieve the selected month from URL parameter
                                $selectedMonth = isset($_GET['bulan']) ? $_GET['bulan'] : date('m'); // default to current month if not set
                                // Retrieve the selected year from URL parameter
                                $selectedYear = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y'); // default to current year if not set
                                
                                // Determine the end month based on the selected year
                                $endMonth = ($selectedYear == date('Y')) ? date('n') : 12;

                                // Generate options for the select element for each month until the end month
                                for ($i = 1; $i <= $endMonth; $i++) {
                                    $month = str_pad($i, 2, '0', STR_PAD_LEFT);
                                    $selected = ($month == $selectedMonth) ? "selected" : "";
                                    $monthName = date("F", mktime(0, 0, 0, $i, 1));
                                    echo "<option value=\"$month\" $selected>$monthName</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-6 col-6" style="margin-top: -18px;">
                        <div class="input-group-prepend">
                            <select id="tahun" class="selectpicker form-control" data-live-search="true">
                                <?php
                                // Retrieve the current year
                                $currentYear = date('Y');
                                // Set minimum year
                                $minYear = 2010;
                                // Retrieve the selected year from URL parameter
                                $selectedYear = isset($_GET['tahun']) ? $_GET['tahun'] : $currentYear; // default to current year if not set
                                // Generate options for the select element, starting from the minimum year to the current year
                                for ($i = $minYear; $i <= $currentYear; $i++) {
                                    $selected = ($i == $selectedYear) ? "selected" : "";
                                    echo "<option value=\"$i\" $selected>$i</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>


            <div class="container-fluid" style="margin-top:10px;">
                <div class="row">
                    <div class="col-md-3">
                        <!-- CARD 1 -->
                        <div class="card card-dark">
                            <div class="card-header" style="height: 25px;">
                                <h6 class="card-title" style="margin-top: -9px; font-size: 17px;">Umur</h6>
                                <i class="fas fa-info-circle"
                                    style="position: absolute; top: 0; right: 0; margin-top: 5px; margin-right: 7px; cursor: pointer;"
                                    onclick="$('#umurModal').modal('show');"></i>
                                <div class="card-tools">
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart">
                                    <canvas id="barChartUmur"
                                        style="min-height: 110px; height: 110px; max-height: 110px; max-width: 100%;"></canvas>
                                </div>
                                <!-- Tambahkan tulisan "Grand Total" di bawah chart -->
                                <h6 style="margin-top: 10px;">&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Jumlah
                                    Keseluruhan :
                                    <?php echo $pria18 + $pria18_25 + $pria26_30 + $pria31_35 + $pria36_40 + $pria41_45 + $pria46_50 + $pria51_55 + $pria55 + $perempuan18 + $perempuan18_25 + $perempuan26_30 + $perempuan31_35 + $perempuan36_40 + $perempuan41_45 + $perempuan46_50 + $perempuan51_55 + $perempuan55 ?>
                                </h6>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->

                    <!-- Modal -->
                    <div class="modal fade" id="umurModal" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" style="max-width: 85%;" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Detail Umur</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-head-fixed text-nowrap" style="height: 340px;">
                                        <thead class="thead-fixed">
                                            <tr>
                                                <th rowspan="3" style="vertical-align: middle;">Departemen</th>
                                                <th colspan="18">Umur</th>
                                            </tr>
                                            <tr>
                                                <th colspan="2" style="vertical-align: middle;">&lt;18</th>
                                                <th colspan="2" style="vertical-align: middle;">18-25</th>
                                                <th colspan="2" style="vertical-align: middle;">26-30</th>
                                                <th colspan="2" style="vertical-align: middle;">31-35</th>
                                                <th colspan="2" style="vertical-align: middle;">36-40</th>
                                                <th colspan="2" style="vertical-align: middle;">41-45</th>
                                                <th colspan="2" style="vertical-align: middle;">46-50</th>
                                                <th colspan="2" style="vertical-align: middle;">51-55</th>
                                                <th colspan="2" style="vertical-align: middle;">>55</th>
                                            </tr>
                                            <tr>
                                                <th rowspan="1">Pria</th>
                                                <th rowspan="1">Wanita</th>
                                                <th rowspan="1">Pria</th>
                                                <th rowspan="1">Wanita</th>
                                                <th rowspan="1">Pria</th>
                                                <th rowspan="1">Wanita</th>
                                                <th rowspan="1">Pria</th>
                                                <th rowspan="1">Wanita</th>
                                                <th rowspan="1">Pria</th>
                                                <th rowspan="1">Wanita</th>
                                                <th rowspan="1">Pria</th>
                                                <th rowspan="1">Wanita</th>
                                                <th rowspan="1">Pria</th>
                                                <th rowspan="1">Wanita</th>
                                                <th rowspan="1">Pria</th>
                                                <th rowspan="1">Wanita</th>
                                                <th rowspan="1">Pria</th>
                                                <th rowspan="1">Wanita</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php
                                            while ($user_data = mysqli_fetch_array($RMPModalResult)) {
                                                // Pastikan variabel $cwoc telah didefinisikan sebelum digunakan
                                                $cwoc = isset($user_data['cwoc']) ? $user_data['cwoc'] : null;
                                                echo "<tr>";
                                                $display_text = str_replace(
                                                    ['BOD TA EXP', 'HRD IR', 'FINANCE ACCOUNTING', 'PLANNING BUDGETING', 'PDCA CPC'],
                                                    ['BOD & TA EXP', 'HRD & IR', 'FINANCE & ACCOUNTING', 'PLANNING & BUDGETING', 'PDCA & CPC'],
                                                    $cwoc
                                                );

                                                echo "<td><a href='sect.php?cwoc={$cwoc}' style='color:black;'>{$display_text}</a></td>";
                                                echo "<td>" . (isset($ModalUmurByCwoc[$cwoc]['<18 Pria']) ? $ModalUmurByCwoc[$cwoc]['<18 Pria'] : 0) . "</td>";
                                                echo "<td>" . (isset($ModalUmurByCwoc[$cwoc]['<18 Perempuan']) ? $ModalUmurByCwoc[$cwoc]['<18 Perempuan'] : 0) . "</td>";
                                                echo "<td>" . (isset($ModalUmurByCwoc[$cwoc]['18-25 Pria']) ? $ModalUmurByCwoc[$cwoc]['18-25 Pria'] : 0) . "</td>";
                                                echo "<td>" . (isset($ModalUmurByCwoc[$cwoc]['18-25 Perempuan']) ? $ModalUmurByCwoc[$cwoc]['18-25 Perempuan'] : 0) . "</td>";
                                                echo "<td>" . (isset($ModalUmurByCwoc[$cwoc]['26-30 Pria']) ? $ModalUmurByCwoc[$cwoc]['26-30 Pria'] : 0) . "</td>";
                                                echo "<td>" . (isset($ModalUmurByCwoc[$cwoc]['26-30 Perempuan']) ? $ModalUmurByCwoc[$cwoc]['26-30 Perempuan'] : 0) . "</td>";
                                                echo "<td>" . (isset($ModalUmurByCwoc[$cwoc]['31-35 Pria']) ? $ModalUmurByCwoc[$cwoc]['31-35 Pria'] : 0) . "</td>";
                                                echo "<td>" . (isset($ModalUmurByCwoc[$cwoc]['31-35 Perempuan']) ? $ModalUmurByCwoc[$cwoc]['31-35 Perempuan'] : 0) . "</td>";
                                                echo "<td>" . (isset($ModalUmurByCwoc[$cwoc]['36-40 Pria']) ? $ModalUmurByCwoc[$cwoc]['36-40 Pria'] : 0) . "</td>";
                                                echo "<td>" . (isset($ModalUmurByCwoc[$cwoc]['36-40 Perempuan']) ? $ModalUmurByCwoc[$cwoc]['36-40 Perempuan'] : 0) . "</td>";
                                                echo "<td>" . (isset($ModalUmurByCwoc[$cwoc]['41-45 Pria']) ? $ModalUmurByCwoc[$cwoc]['41-45 Pria'] : 0) . "</td>";
                                                echo "<td>" . (isset($ModalUmurByCwoc[$cwoc]['41-45 Perempuan']) ? $ModalUmurByCwoc[$cwoc]['41-45 Perempuan'] : 0) . "</td>";
                                                echo "<td>" . (isset($ModalUmurByCwoc[$cwoc]['46-50 Pria']) ? $ModalUmurByCwoc[$cwoc]['46-50 Pria'] : 0) . "</td>";
                                                echo "<td>" . (isset($ModalUmurByCwoc[$cwoc]['46-50 Perempuan']) ? $ModalUmurByCwoc[$cwoc]['46-50 Perempuan'] : 0) . "</td>";
                                                echo "<td>" . (isset($ModalUmurByCwoc[$cwoc]['41-55 Pria']) ? $ModalUmurByCwoc[$cwoc]['41-55 Pria'] : 0) . "</td>";
                                                echo "<td>" . (isset($ModalUmurByCwoc[$cwoc]['41-55 Perempuan']) ? $ModalUmurByCwoc[$cwoc]['41-55 Perempuan'] : 0) . "</td>";
                                                echo "<td>" . (isset($ModalUmurByCwoc[$cwoc]['>55 Pria']) ? $ModalUmurByCwoc[$cwoc]['>55 Pria'] : 0) . "</td>";
                                                echo "<td>" . (isset($ModalUmurByCwoc[$cwoc]['>55 Perempuan']) ? $ModalUmurByCwoc[$cwoc]['>55 Perempuan'] : 0) . "</td>";
                                                echo "</tr>";
                                            }
                                            ?>

                                        </tbody>
                                        <tfoot class="table table-head-fixed text-nowrap"
                                            style="background-color: #f9f9f9;">
                                            <tr>
                                                <th colspan="1">Total</th>
                                                <td><?php echo $pria18 ?></td>
                                                <td><?php echo $perempuan18 ?></td>
                                                <td><?php echo $pria18_25 ?></td>
                                                <td><?php echo $perempuan18_25 ?></td>
                                                <td><?php echo $pria26_30 ?></td>
                                                <td><?php echo $perempuan26_30 ?></td>
                                                <td><?php echo $pria31_35 ?></td>
                                                <td><?php echo $perempuan31_35 ?></td>
                                                <td><?php echo $pria36_40 ?></td>
                                                <td><?php echo $perempuan36_40 ?></td>
                                                <td><?php echo $pria41_45 ?></td>
                                                <td><?php echo $perempuan41_45 ?></td>
                                                <td><?php echo $pria46_50 ?></td>
                                                <td><?php echo $perempuan46_50 ?></td>
                                                <td><?php echo $pria51_55 ?></td>
                                                <td><?php echo $perempuan51_55 ?></td>
                                                <td><?php echo $pria55 ?></td>
                                                <td><?php echo $perempuan55 ?></td>
                                            </tr>
                                            <tr>
                                                <th colspan="1">Subtotal</th>
                                                <td colspan="2"><?php echo $pria18 + $perempuan18 ?></td>
                                                <td colspan="2"><?php echo $pria18_25 + $perempuan18_25 ?></td>
                                                <td colspan="2"><?php echo $pria26_30 + $perempuan26_30 ?></td>
                                                <td colspan="2"><?php echo $pria31_35 + $perempuan31_35 ?></td>
                                                <td colspan="2"><?php echo $pria36_40 + $perempuan36_40 ?></td>
                                                <td colspan="2"><?php echo $pria41_45 + $perempuan41_45 ?></td>
                                                <td colspan="2"><?php echo $pria46_50 + $perempuan46_50 ?></td>
                                                <td colspan="2"><?php echo $pria51_55 + $perempuan51_55 ?></td>
                                                <td colspan="2"><?php echo $pria55 + $perempuan55 ?></td>

                                            </tr>
                                            <tr>
                                                <th colspan="1">Jumlah Keseluruhan</th>
                                                <td colspan="18">
                                                    <?php echo $pria18 + $pria18_25 + $pria26_30 + $pria31_35 + $pria36_40 + $pria41_45 + $pria46_50 + $pria51_55 + $pria55 + $perempuan18 + $perempuan18_25 + $perempuan26_30 + $perempuan31_35 + $perempuan36_40 + $perempuan41_45 + $perempuan46_50 + $perempuan51_55 + $perempuan55 ?>
                                                </td>

                                            </tr>
                                        </tfoot>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="col-md-3">
                        <!-- CARD 2 -->
                        <div class="card card-dark">
                            <div class="card-header" style="height: 25px;">
                                <h6 class="card-title" style="margin-top: -9px; font-size: 17px;">Masa Kerja</h6>
                                <i class="fas fa-info-circle"
                                    style="position: absolute; top: 0; right: 0; margin-top: 5px; margin-right: 7px; cursor: pointer;"
                                    onclick="$('#MKModal').modal('show');"></i>
                            </div>
                            <div class="card-body">
                                <div class="chart">
                                    <canvas id="barChartMK"
                                        style="min-height: 110px; height: 110px; max-height: 110px; max-width: 100%;"></canvas>
                                </div>
                                <h6 style="margin-top: 10px;">&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Jumlah
                                    Keselurahan :
                                    <?php echo $jumlah_pria_kurang_dari_5 + $jumlah_pria_6_10 + $jumlah_pria_11_15 + $jumlah_pria_16_20 + $jumlah_pria_21_25 + $jumlah_pria_26_30 + $jumlah_pria_lebih_dari_30 + $jumlah_perempuan_kurang_dari_5 + $jumlah_perempuan_6_10 + $jumlah_perempuan_11_15 + $jumlah_perempuan_16_20 + $jumlah_perempuan_21_25 + $jumlah_perempuan_26_30 + $jumlah_perempuan_lebih_dari_30; ?>
                                </h6>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->

                    <div class="modal fade" id="MKModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" style="max-width: 75%;" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-head-fixed text-nowrap" style="height: 340px;">
                                        <thead class="thead-fixed">
                                            <tr>
                                                <th rowspan="3" style="vertical-align: middle;">Departemen</th>
                                                <th colspan="14">Masa Kerja</th>
                                            </tr>
                                            <tr>
                                                <th colspan="2" style="vertical-align: middle;">&lt;5</th>
                                                <th colspan="2" style="vertical-align: middle;">6-10</th>
                                                <th colspan="2" style="vertical-align: middle;">11-15</th>
                                                <th colspan="2" style="vertical-align: middle;">16-20</th>
                                                <th colspan="2" style="vertical-align: middle;">21-25</th>
                                                <th colspan="2" style="vertical-align: middle;">26-30</th>
                                                <th colspan="2" style="vertical-align: middle;">>30</th>
                                            </tr>
                                            <tr>
                                                <th rowspan="1">Pria</th>
                                                <th rowspan="1">Wanita</th>
                                                <th rowspan="1">Pria</th>
                                                <th rowspan="1">Wanita</th>
                                                <th rowspan="1">Pria</th>
                                                <th rowspan="1">Wanita</th>
                                                <th rowspan="1">Pria</th>
                                                <th rowspan="1">Wanita</th>
                                                <th rowspan="1">Pria</th>
                                                <th rowspan="1">Wanita</th>
                                                <th rowspan="1">Pria</th>
                                                <th rowspan="1">Wanita</th>
                                                <th rowspan="1">Pria</th>
                                                <th rowspan="1">Wanita</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php
                                            $RMPModalResult = mysqli_query($koneksi, $queryRMPModal);
                                            while ($user_data = mysqli_fetch_array($RMPModalResult)) {
                                                // Pastikan variabel $cwoc telah didefinisikan sebelum digunakan
                                                $cwoc = isset($user_data['cwoc']) ? $user_data['cwoc'] : null;
                                                echo "<tr>";
                                                $display_text = str_replace(
                                                    ['BOD TA EXP', 'HRD IR', 'FINANCE ACCOUNTING', 'PLANNING BUDGETING', 'PDCA CPC'],
                                                    ['BOD & TA EXP', 'HRD & IR', 'FINANCE & ACCOUNTING', 'PLANNING & BUDGETING', 'PDCA & CPC'],
                                                    $cwoc
                                                );

                                                echo "<td><a href='sect.php?cwoc={$cwoc}' style='color:black;'>{$display_text}</a></td>";
                                                echo "<td>" . (isset($ModalMKByCwoc[$cwoc]['0-5 Pria Modal']) ? $ModalMKByCwoc[$cwoc]['0-5 Pria Modal'] : 0) . "</td>";
                                                echo "<td>" . (isset($ModalMKByCwoc[$cwoc]['0-5 Perempuan Modal']) ? $ModalMKByCwoc[$cwoc]['0-5 Perempuan Modal'] : 0) . "</td>";
                                                echo "<td>" . (isset($ModalMKByCwoc[$cwoc]['6-10 Pria Modal']) ? $ModalMKByCwoc[$cwoc]['6-10 Pria Modal'] : 0) . "</td>";
                                                echo "<td>" . (isset($ModalMKByCwoc[$cwoc]['6-10 Perempuan Modal']) ? $ModalMKByCwoc[$cwoc]['6-10 Perempuan Modal'] : 0) . "</td>";
                                                echo "<td>" . (isset($ModalMKByCwoc[$cwoc]['11-15 Pria Modal']) ? $ModalMKByCwoc[$cwoc]['11-15 Pria Modal'] : 0) . "</td>";
                                                echo "<td>" . (isset($ModalMKByCwoc[$cwoc]['11-15 Perempuan Modal']) ? $ModalMKByCwoc[$cwoc]['11-15 Perempuan Modal'] : 0) . "</td>";
                                                echo "<td>" . (isset($ModalMKByCwoc[$cwoc]['16-20 Pria Modal']) ? $ModalMKByCwoc[$cwoc]['16-20 Pria Modal'] : 0) . "</td>";
                                                echo "<td>" . (isset($ModalMKByCwoc[$cwoc]['16-20 Perempuan Modal']) ? $ModalMKByCwoc[$cwoc]['16-20 Perempuan Modal'] : 0) . "</td>";
                                                echo "<td>" . (isset($ModalMKByCwoc[$cwoc]['21-25 Pria Modal']) ? $ModalMKByCwoc[$cwoc]['21-25 Pria Modal'] : 0) . "</td>";
                                                echo "<td>" . (isset($ModalMKByCwoc[$cwoc]['21-25 Perempuan Modal']) ? $ModalMKByCwoc[$cwoc]['21-25 Perempuan Modal'] : 0) . "</td>";
                                                echo "<td>" . (isset($ModalMKByCwoc[$cwoc]['26-30 Pria Modal']) ? $ModalMKByCwoc[$cwoc]['26-30 Pria Modal'] : 0) . "</td>";
                                                echo "<td>" . (isset($ModalMKByCwoc[$cwoc]['26-30 Perempuan Modal']) ? $ModalMKByCwoc[$cwoc]['26-30 Perempuan Modal'] : 0) . "</td>";
                                                echo "<td>" . (isset($ModalMKByCwoc[$cwoc]['>30 Pria Modal']) ? $ModalMKByCwoc[$cwoc]['>30 Pria Modal'] : 0) . "</td>";
                                                echo "<td>" . (isset($ModalMKByCwoc[$cwoc]['>30 Perempuan Modal']) ? $ModalMKByCwoc[$cwoc]['>30 Perempuan Modal'] : 0) . "</td>";
                                                echo "</tr>";
                                            }
                                            ?>

                                        </tbody>
                                        <tfoot class="table table-head-fixed text-nowrap"
                                            style="background-color: #f9f9f9;">
                                            <tr>
                                                <th colspan="1">Jumlah</th>
                                                <td><?php echo $jumlah_pria_kurang_dari_5 ?></td>
                                                <td><?php echo $jumlah_perempuan_kurang_dari_5 ?></td>
                                                <td><?php echo $jumlah_pria_6_10 ?></td>
                                                <td><?php echo $jumlah_perempuan_6_10 ?></td>
                                                <td><?php echo $jumlah_pria_11_15 ?></td>
                                                <td><?php echo $jumlah_perempuan_11_15 ?></td>
                                                <td><?php echo $jumlah_pria_16_20 ?></td>
                                                <td><?php echo $jumlah_perempuan_16_20 ?></td>
                                                <td><?php echo $jumlah_pria_21_25 ?></td>
                                                <td><?php echo $jumlah_perempuan_21_25 ?></td>
                                                <td><?php echo $jumlah_pria_26_30 ?></td>
                                                <td><?php echo $jumlah_perempuan_26_30 ?></td>
                                                <td><?php echo $jumlah_pria_lebih_dari_30 ?></td>
                                                <td><?php echo $jumlah_perempuan_lebih_dari_30 ?></td>
                                            </tr>
                                            <tr>
                                                <th colspan="1">Subtotal</th>
                                                <td colspan="2">
                                                    <?php echo $jumlah_pria_kurang_dari_5 + $jumlah_perempuan_kurang_dari_5 ?>
                                                </td>
                                                <td colspan="2"><?php echo $jumlah_pria_6_10 + $jumlah_perempuan_6_10 ?>
                                                </td>
                                                <td colspan="2">
                                                    <?php echo $jumlah_pria_11_15 + $jumlah_perempuan_11_15 ?>
                                                </td>
                                                <td colspan="2">
                                                    <?php echo $jumlah_pria_16_20 + $jumlah_perempuan_16_20 ?>
                                                </td>
                                                <td colspan="2">
                                                    <?php echo $jumlah_pria_21_25 + $jumlah_perempuan_21_25 ?>
                                                </td>
                                                <td colspan="2">
                                                    <?php echo $jumlah_pria_26_30 + $jumlah_perempuan_26_30 ?>
                                                </td>
                                                <td colspan="2">
                                                    <?php echo $jumlah_pria_lebih_dari_30 + $jumlah_perempuan_lebih_dari_30 ?>
                                                </td>

                                            </tr>
                                            <tr>
                                                <th colspan="1">Jumlah Keselurahan</th>
                                                <td colspan="14">
                                                    <?php echo $jumlah_pria_kurang_dari_5 + $jumlah_pria_6_10 + $jumlah_pria_11_15 + $jumlah_pria_16_20 + $jumlah_pria_21_25 + $jumlah_pria_26_30 + $jumlah_pria_lebih_dari_30 + $jumlah_perempuan_kurang_dari_5 + $jumlah_perempuan_6_10 + $jumlah_perempuan_11_15 + $jumlah_perempuan_16_20 + $jumlah_perempuan_21_25 + $jumlah_perempuan_26_30 + $jumlah_perempuan_lebih_dari_30; ?>
                                                </td>

                                            </tr>
                                        </tfoot>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Penambahan 3 blok kartu lagi -->
                    <div class="col-md-3">
                        <!-- CARD 4 -->
                        <div class="card card-dark">
                            <div class="card-header" style="height: 25px;">
                                <h6 class="card-title" style="margin-top: -9px; font-size: 17px;">Pendidikan</h6>
                                <i class="fas fa-info-circle"
                                    style="position: absolute; top: 0; right: 0; margin-top: 5px; margin-right: 7px; cursor: pointer;"
                                    onclick="$('#PendidikanModal').modal('show');"></i>
                            </div>
                            <div class="card-body">
                                <div class="chart">
                                    <!-- Ganti id dan judul chart sesuai kebutuhan -->
                                    <canvas id="barChartPend"
                                        style="min-height: 110px; height: 110px; max-height: 110px; max-width: 100%;"></canvas>
                                </div>
                                <!-- Tambahkan tulisan "Grand Total" di bawah chart -->
                                <h6 style="margin-top: 10px;">&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Jumlah
                                    Keselurahan :
                                    <?php echo $jumlah_pria_SD + $jumlah_pria_SLTP + $jumlah_pria_SMA + $jumlah_pria_Diploma + $jumlah_pria_S1 + $jumlah_pria_S2 + $jumlah_pria_S3 + $jumlah_perempuan_SD + $jumlah_perempuan_SLTP + $jumlah_perempuan_SMA + $jumlah_perempuan_Diploma + $jumlah_perempuan_S1 + $jumlah_perempuan_S2 + $jumlah_perempuan_S3; ?>
                                </h6>
                            </div>
                            <!-- /.card-body -->
                            <!-- Modal -->
                            <div class="modal fade" id="PendidikanModal" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" style="max-width: 55%;" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <table class="table table-head-fixed text-nowrap" style="height: 340px;">
                                                <thead class="thead-fixed">
                                                    <tr>
                                                        <th rowspan="3" style="vertical-align: middle;">Departemen</th>
                                                        <th colspan="12">Education</th>
                                                    </tr>
                                                    <tr>
                                                        <th colspan="2" style="vertical-align: middle;">SD, SLTP</th>
                                                        <th colspan="2" style="vertical-align: middle;">SLTA</th>
                                                        <th colspan="2" style="vertical-align: middle;">Diploma</th>
                                                        <th colspan="2" style="vertical-align: middle;">S1</th>
                                                        <th colspan="2" style="vertical-align: middle;">S2, 23</th>
                                                    </tr>
                                                    <tr>
                                                        <th rowspan="1">Pria</th>
                                                        <th rowspan="1">Wanita</th>
                                                        <th rowspan="1">Pria</th>
                                                        <th rowspan="1">Wanita</th>
                                                        <th rowspan="1">Pria</th>
                                                        <th rowspan="1">Wanita</th>
                                                        <th rowspan="1">Pria</th>
                                                        <th rowspan="1">Wanita</th>
                                                        <th rowspan="1">Pria</th>
                                                        <th rowspan="1">Wanita</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    <?php

                                                    $RMPModalResult = mysqli_query($koneksi, $queryRMPModal);

                                                    while ($user_data = mysqli_fetch_array($RMPModalResult)) {
                                                        // Pastikan variabel $cwoc telah didefinisikan sebelum digunakan
                                                        $cwoc = isset($user_data['cwoc']) ? $user_data['cwoc'] : null;
                                                        echo "<tr>";
                                                        $display_text = str_replace(
                                                            ['BOD TA EXP', 'HRD IR', 'FINANCE ACCOUNTING', 'PLANNING BUDGETING', 'PDCA CPC'],
                                                            ['BOD & TA EXP', 'HRD & IR', 'FINANCE & ACCOUNTING', 'PLANNING & BUDGETING', 'PDCA & CPC'],
                                                            $cwoc
                                                        );

                                                        echo "<td><a href='sect.php?cwoc={$cwoc}' style='color:black;'>{$display_text}</a></td>";
                                                        echo "<td>" . (isset($ModalPendidikanByCwoc[$cwoc]['Pria SD, SLTP Modal']) ? $ModalPendidikanByCwoc[$cwoc]['Pria SD, SLTP Modal'] : 0) . "</td>";
                                                        echo "<td>" . (isset($ModalPendidikanByCwoc[$cwoc]['Perempuan SD, SLTP Modal']) ? $ModalPendidikanByCwoc[$cwoc]['Perempuan SD, SLTP Modal'] : 0) . "</td>";
                                                        echo "<td>" . (isset($ModalPendidikanByCwoc[$cwoc]['Pria SMA Modal']) ? $ModalPendidikanByCwoc[$cwoc]['Pria SMA Modal'] : 0) . "</td>";
                                                        echo "<td>" . (isset($ModalPendidikanByCwoc[$cwoc]['Perempuan SMA Modal']) ? $ModalPendidikanByCwoc[$cwoc]['Perempuan SMA Modal'] : 0) . "</td>";
                                                        echo "<td>" . (isset($ModalPendidikanByCwoc[$cwoc]['Pria Diploma Modal']) ? $ModalPendidikanByCwoc[$cwoc]['Pria Diploma Modal'] : 0) . "</td>";
                                                        echo "<td>" . (isset($ModalPendidikanByCwoc[$cwoc]['Perempuan Diploma Modal']) ? $ModalPendidikanByCwoc[$cwoc]['Perempuan Diploma Modal'] : 0) . "</td>";
                                                        echo "<td>" . (isset($ModalPendidikanByCwoc[$cwoc]['Pria S1 Modal']) ? $ModalPendidikanByCwoc[$cwoc]['Pria S1 Modal'] : 0) . "</td>";
                                                        echo "<td>" . (isset($ModalPendidikanByCwoc[$cwoc]['Perempuan S1 Modal']) ? $ModalPendidikanByCwoc[$cwoc]['Perempuan S1 Modal'] : 0) . "</td>";
                                                        echo "<td>" . (isset($ModalPendidikanByCwoc[$cwoc]['Pria S2, S3 Modal']) ? $ModalPendidikanByCwoc[$cwoc]['Pria S2, S3 Modal'] : 0) . "</td>";
                                                        echo "<td>" . (isset($ModalPendidikanByCwoc[$cwoc]['Perempuan S2, S3 Modal']) ? $ModalPendidikanByCwoc[$cwoc]['Perempuan S2, S3 Modal'] : 0) . "</td>";
                                                        echo "</tr>";
                                                    }
                                                    ?>

                                                </tbody>
                                                <tfoot class="table table-head-fixed text-nowrap"
                                                    style="background-color: #f9f9f9;">
                                                    <tr>
                                                        <th colspan="1">Total</th>
                                                        <td><?php echo $jumlah_pria_SD + $jumlah_pria_SLTP ?></td>
                                                        <td><?php echo $jumlah_perempuan_SD + $jumlah_perempuan_SLTP ?>
                                                        </td>
                                                        <td><?php echo $jumlah_pria_SMA ?></td>
                                                        <td><?php echo $jumlah_perempuan_SMA ?></td>
                                                        <td><?php echo $jumlah_pria_Diploma ?></td>
                                                        <td><?php echo $jumlah_perempuan_Diploma ?></td>
                                                        <td><?php echo $jumlah_pria_S1 ?></td>
                                                        <td><?php echo $jumlah_perempuan_S1 ?></td>
                                                        <td><?php echo $jumlah_pria_S2 + $jumlah_perempuan_S3 ?></td>
                                                        <td><?php echo $jumlah_perempuan_S2 + $jumlah_perempuan_S3 ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th colspan="1">Subtotal</th>
                                                        <td colspan="2">
                                                            <?php echo $jumlah_pria_SD + $jumlah_pria_SLTP + $jumlah_perempuan_SD + $jumlah_perempuan_SLTP ?>
                                                        </td>
                                                        <td colspan="2">
                                                            <?php echo $jumlah_pria_SMA + $jumlah_perempuan_SMA ?>
                                                        </td>
                                                        <td colspan="2">
                                                            <?php echo $jumlah_pria_Diploma + $jumlah_perempuan_Diploma ?>
                                                        </td>
                                                        <td colspan="2">
                                                            <?php echo $jumlah_pria_S1 + $jumlah_perempuan_S1 ?>
                                                        </td>
                                                        <td colspan="2">
                                                            <?php echo $jumlah_pria_S2 + $jumlah_perempuan_S2 + $jumlah_pria_S3 + $jumlah_perempuan_S3 ?>
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <th colspan="1">Jumlah Keseluruhan</th>
                                                        <td colspan="10">
                                                            <?php echo $jumlah_pria_SD + $jumlah_pria_SLTP + $jumlah_pria_SMA + $jumlah_pria_Diploma + $jumlah_pria_S1 + $jumlah_pria_S2 + $jumlah_pria_S3 + $jumlah_perempuan_SD + $jumlah_perempuan_SLTP + $jumlah_perempuan_SMA + $jumlah_perempuan_Diploma + $jumlah_perempuan_S1 + $jumlah_perempuan_S2 + $jumlah_perempuan_S3; ?>
                                                        </td>

                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->

                    <div class="col-md-3">
                        <!-- CARD 3 -->
                        <div class="card card-dark">
                            <div class="card-header" style="height: 25px;">
                                <h6 class="card-title" style="margin-top: -9px; font-size: 17px;">Jenis Kelamin</h6>
                                <i class="fas fa-info-circle"
                                    style="position: absolute; top: 0; right: 0; margin-top: 5px; margin-right: 7px; cursor: pointer;"
                                    onclick="$('#JKModal').modal('show');"></i>
                            </div>
                            <div class="card-body">
                                <div class="chart">
                                    <canvas id="barChartJK"
                                        style="min-height: 110px; height: 110px; max-height: 110px; max-width: 100%;"></canvas>
                                </div>
                                <h6 style="margin-top: 10px;">&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Jumlah
                                    Keseluruhan :
                                    <?php echo $pria + $perempuan; ?>
                                </h6>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->

                    <div class="modal fade" id="JKModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-head-fixed text-nowrap" style="height: 340px;">
                                        <thead class="thead-fixed">
                                            <tr>
                                                <th rowspan="2" style="vertical-align: middle;">Departemen</th>
                                                <th colspan="2">Gender</th>
                                            </tr>
                                            <tr>
                                                <th rowspan="1">Pria</th>
                                                <th rowspan="1">Wanita</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php

                                            $RMPModalResult = mysqli_query($koneksi, $queryRMPModal);

                                            while ($user_data = mysqli_fetch_array($RMPModalResult)) {
                                                // Pastikan variabel $cwoc telah didefinisikan sebelum digunakan
                                                $cwoc = isset($user_data['cwoc']) ? $user_data['cwoc'] : null;
                                                echo "<tr>";
                                                $display_text = str_replace(
                                                    ['BOD TA EXP', 'HRD IR', 'FINANCE ACCOUNTING', 'PLANNING BUDGETING', 'PDCA CPC'],
                                                    ['BOD & TA EXP', 'HRD & IR', 'FINANCE & ACCOUNTING', 'PLANNING & BUDGETING', 'PDCA & CPC'],
                                                    $cwoc
                                                );

                                                echo "<td><a href='sect.php?cwoc={$cwoc}' style='color:black;'>{$display_text}</a></td>";
                                                echo "<td>" . (isset($ModalJKByCwoc[$cwoc]['Pria Modal']) ? $ModalJKByCwoc[$cwoc]['Pria Modal'] : 0) . "</td>";
                                                echo "<td>" . (isset($ModalJKByCwoc[$cwoc]['Perempuan Modal']) ? $ModalJKByCwoc[$cwoc]['Perempuan Modal'] : 0) . "</td>";
                                                echo "</tr>";
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot class="table table-head-fixed text-nowrap"
                                            style="background-color: #f9f9f9;">
                                            <tr>
                                                <th colspan="1">Total</th>
                                                <td><?php echo $pria ?></td>
                                                <td><?php echo $perempuan ?></td>
                                            </tr>
                                            <tr>
                                                <th colspan="1">Grandtotal</th>
                                                <td colspan="2"><?php echo $pria + $perempuan ?></td>

                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Man Power Actual
                                <?php
                                // Generate the title based on selected month and year
                                if (isset($_GET['bulan']) && isset($_GET['tahun'])) {
                                    $monthName = date("F", mktime(0, 0, 0, $selectedMonth, 1));
                                    echo "$monthName $selectedYear";
                                } elseif (isset($_GET['bulan'])) {
                                    // If only month is selected, display selected month and current year
                                    $monthName = date("F", mktime(0, 0, 0, $selectedMonth, 1));
                                    echo "$monthName $currentYear";
                                } elseif (isset($_GET['tahun'])) {
                                    // If only year is selected, display current month and selected year
                                    $monthName = date("F", mktime(0, 0, 0, $currentMonth, 1));
                                    echo "$monthName $selectedYear";
                                } else {
                                    // If no filter applied, show "Today"
                                    echo '<span id="current-day"></span>';
                                }
                                ?>
                                <!-- <span id="current-day" class="d-block ps-2" style="position: absolute; top: 0; right: 0; margin-right:25px; margin-top:13px;"></span> -->
                            </h3>

                            <div class="card-tools">
                                <div class="input-group input-group-sm" style="width: 150px;">
                                    <a href="<?php echo $urlToPHPExcel; ?>"
                                        class="btn btn-success btn-sm export-excel-btn" style="margin-left:30px;">
                                        <i class="fas fa-file-excel"></i> &nbsp;Laporan Excel
                                    </a>

                                    <!-- Alert untuk loading -->
                                    <div class="loading-alert" id="loadingAlert">
                                        Mohon tunggu, sedang memproses...
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0" style="height: 315px;">
                            <table class="table table-head-fixed text-nowrap mx-auto" style="width: 100%;">
                                <!-- Tambahkan mx-auto untuk memusatkan tabel dan width: 100% untuk melebarkan tabel -->
                                <thead class="thead-fixed">
                                    <tr>
                                        <th rowspan="3" style="vertical-align: middle;">No</th>
                                        <th rowspan="3" style="vertical-align: middle;">Departemen</th>
                                        <th colspan="13">Golongan</th>
                                        <th rowspan="3" style="vertical-align: middle;">Grand<br>Total</th>
                                    </tr>
                                    <tr>
                                        <th rowspan="2" style="vertical-align: middle;">Kontrak</th>
                                        <th>I</th>
                                        <th colspan="2">II</th>
                                        <th colspan="2">III</th>
                                        <th colspan="2">IV</th>
                                        <th>V</th>
                                        <th rowspan="2" style="vertical-align: middle;">VI-VII</th>
                                        <th colspan="3">Total</th>
                                    </tr>
                                    <tr>
                                        <th rowspan="1">Tetap</th>
                                        <th rowspan="1">Tetap</th>
                                        <th rowspan="1">Trainee</th>
                                        <th rowspan="1">Tetap</th>
                                        <th rowspan="1">Trainee</th>
                                        <th rowspan="1">Tetap</th>
                                        <th rowspan="1">Trainee</th>
                                        <th rowspan="1">Tetap</th>
                                        <th rowspan="1">Tetap</th>
                                        <th rowspan="1">Trainee</th>
                                        <th rowspan="1">Kontrak</th>
                                    </tr>
                                </thead>


                                <tbody>
                                    <?php
                                    $no = 1; // Definisikan variabel $no di sini
                                    while ($user_data = mysqli_fetch_array($resultRMP)) {
                                        // Pastikan variabel $cwoc telah didefinisikan sebelum digunakan
                                        $cwoc = isset($user_data['cwoc']) ? $user_data['cwoc'] : null;
                                        echo "<tr>";
                                        echo "<td>" . $no++ . "</td>"; // Menampilkan nomor urutan (increment $no setelah digunakan)
                                        $cwoc = $user_data['cwoc'];
                                        $display_text = str_replace(
                                            ['BOD TA EXP', 'HRD IR', 'FINANCE ACCOUNTING', 'PLANNING BUDGETING', 'PDCA CPC'],
                                            ['BOD & TA EXP', 'HRD & IR', 'FINANCE & ACCOUNTING', 'PLANNING & BUDGETING', 'PDCA & CPC'],
                                            $cwoc
                                        );

                                        echo "<td><a href='sect.php?cwoc={$cwoc}' style='color:black;'>{$display_text}</a></td>";
                                        echo "<td>" . ($kontrak = isset($DashboardByCwoc[$cwoc]['Kontrak']) ? $DashboardByCwoc[$cwoc]['Kontrak'] : 0) . "</td>";
                                        echo "<td>" . ($tetap1 = isset($DashboardByCwoc[$cwoc]['Tetap1']) ? $DashboardByCwoc[$cwoc]['Tetap1'] : 0) . "</td>";
                                        echo "<td>" . ($tetap2 = isset($DashboardByCwoc[$cwoc]['Tetap2']) ? $DashboardByCwoc[$cwoc]['Tetap2'] : 0) . "</td>";
                                        echo "<td>" . ($trainee2 = isset($DashboardByCwoc[$cwoc]['Trainee2']) ? $DashboardByCwoc[$cwoc]['Trainee2'] : 0) . "</td>";
                                        echo "<td>" . ($tetap3 = isset($DashboardByCwoc[$cwoc]['Tetap3']) ? $DashboardByCwoc[$cwoc]['Tetap3'] : 0) . "</td>";
                                        echo "<td>" . ($trainee3 = isset($DashboardByCwoc[$cwoc]['Trainee3']) ? $DashboardByCwoc[$cwoc]['Trainee3'] : 0) . "</td>";
                                        echo "<td>" . ($tetap4 = isset($DashboardByCwoc[$cwoc]['Tetap4']) ? $DashboardByCwoc[$cwoc]['Tetap4'] : 0) . "</td>";
                                        echo "<td>" . ($trainee4 = isset($DashboardByCwoc[$cwoc]['Trainee4']) ? $DashboardByCwoc[$cwoc]['Trainee4'] : 0) . "</td>";
                                        echo "<td>" . ($tetap5 = isset($DashboardByCwoc[$cwoc]['Tetap5']) ? $DashboardByCwoc[$cwoc]['Tetap5'] : 0) . "</td>";
                                        echo "<td>" . ($tetap6_7 = isset($DashboardByCwoc[$cwoc]['Tetap6_7']) ? $DashboardByCwoc[$cwoc]['Tetap6_7'] : 0) . "</td>";
                                        echo "<td>" . ($tetap = isset($DashboardByCwoc[$cwoc]['Tetap']) ? $DashboardByCwoc[$cwoc]['Tetap'] : 0) . "</td>";
                                        echo "<td>" . ($trainee = ((isset($trainee3) ? $trainee3 : 0) + (isset($trainee4) ? $trainee4 : 0) + +(isset($trainee2) ? $trainee2 : 0))) . "</td>";
                                        echo "<td>" . ($kontrak = isset($DashboardByCwoc[$cwoc]['Kontrak']) ? $DashboardByCwoc[$cwoc]['Kontrak'] : 0) . "</td>";
                                        echo "<td>" . ($total = (isset($kontrak) ? $kontrak : 0) + (isset($trainee3) ? $trainee3 : 0) + (isset($trainee4) ? $trainee4 : 0) + (isset($tetap) ? $tetap : 0) + +(isset($trainee2) ? $trainee2 : 0)) . "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                                <tfoot class="table table-head-fixed text-nowrap">
                                    <tr>
                                        <th colspan="2">Total</th>
                                        <th><?php echo $Kontrak ?></th>
                                        <th><?php echo $Tetap1 ?></th>
                                        <th><?php echo $Tetap2 ?></th>
                                        <th><?php echo $Trainee2 ?></th>
                                        <th><?php echo $Tetap3 ?></th>
                                        <th><?php echo $Trainee3 ?></th>
                                        <th><?php echo $Tetap4 ?></th>
                                        <th><?php echo $Trainee4 ?></th>
                                        <th><?php echo $Tetap5 ?></th>
                                        <th><?php echo $Tetap6_7 ?></th>
                                        <th><?php echo $Tetap ?></th>
                                        <th><?php echo $Trainee3 + $Trainee4 + +$Trainee2 ?></th>
                                        <th><?php echo $Kontrak ?></th>
                                        <th><?php echo $Kontrak + $Tetap + $Trainee3 + $Trainee4 + $Trainee2 ?></th>
                                    </tr>
                                </tfoot>

                            </table>

                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <?php include '../../layout/footer.php'; ?>
    </footer>

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
    <script src="../../asset/js/search.js"></script>
    <script src="../../asset/JS/time.js"></script>
    <script src="../../asset/JS/day.js"></script>
    <script src="../../asset/select/select.min.js"></script>

    <script>
    function filterByMonth() {
        var selectedMonth = document.getElementById("bulan").value;
        var selectedYear = document.getElementById("tahun").value;
        var currentUrl = window.location.href;
        currentUrl = updateQueryStringParameter(currentUrl, 'bulan', selectedMonth);
        currentUrl = updateQueryStringParameter(currentUrl, 'tahun', selectedYear);
        window.location.href = currentUrl;
    }

    function updateQueryStringParameter(uri, key, value) {
        var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
        var separator = uri.indexOf('?') !== -1 ? "&" : "?";
        if (uri.match(re)) {
            return uri.replace(re, '$1' + key + "=" + value + '$2');
        } else {
            return uri + separator + key + "=" + value;
        }
    }

    // Buat fungsi untuk menangani perubahan bulan
    function handleMonthChange() {
        var selectedMonth = document.getElementById("bulan").value; // Dapatkan nilai bulan yang dipilih
        var currentUrl = window.location.href; // Dapatkan URL saat ini
        var url = new URL(currentUrl); // Buat objek URL dari URL saat ini

        // Dapatkan nilai tahun yang telah dipilih dari URL jika ada
        var selectedYear = url.searchParams.get('tahun');

        // Periksa apakah tahun telah dipilih sebelumnya
        if (selectedYear !== null) {
            // Jika tahun telah dipilih, perbarui parameter bulan di URL
            url.searchParams.set('bulan', selectedMonth);
        } else {
            // Jika tahun belum dipilih, tambahkan parameter bulan ke URL
            url.searchParams.set('bulan', selectedMonth);

            // Jika tahun tidak dipilih, hapus parameter tahun dari URL agar tahun tetap tidak berubah
            url.searchParams.delete('tahun');
        }

        // Muat ulang halaman dengan URL yang diperbarui
        window.location.href = url.href;
    }

    // Tambahkan event listener untuk onchange pada pilihan bulan
    document.getElementById('bulan').addEventListener('change', function(event) {
        // Panggil fungsi handleMonthChange() ketika bulan berubah
        handleMonthChange();
    });

    // Buat fungsi untuk menangani perubahan tahun
    function handleYearChange() {
        var selectedYear = document.getElementById("tahun").value; // Dapatkan nilai tahun yang dipilih
        var currentUrl = window.location.href; // Dapatkan URL saat ini
        var url = new URL(currentUrl); // Buat objek URL dari URL saat ini

        // Dapatkan nilai bulan yang telah dipilih dari URL jika ada
        var selectedMonth = url.searchParams.get('bulan');

        // Periksa apakah bulan telah dipilih sebelumnya
        if (selectedMonth !== null) {
            // Jika bulan telah dipilih, perbarui parameter tahun di URL
            url.searchParams.set('tahun', selectedYear);
        } else {
            // Jika bulan belum dipilih, tambahkan parameter tahun ke URL
            url.searchParams.set('tahun', selectedYear);

            // Jika bulan tidak dipilih, hapus parameter bulan dari URL agar bulan tetap tidak berubah
            url.searchParams.delete('bulan');
        }

        // Muat ulang halaman dengan URL yang diperbarui
        window.location.href = url.href;
    }

    // Tambahkan event listener untuk onchange pada pilihan tahun
    document.getElementById('tahun').addEventListener('change', function(event) {
        // Panggil fungsi handleYearChange() ketika tahun berubah
        handleYearChange();
    });
    </script>

    <script>
    function filterByMonth() {
        var selectedMonth = document.getElementById("bulan").value;
        window.location.href = "?bulan=" + selectedMonth;
    }
    /* ChartJS
     * -------
     * Disini kita akan membuat beberapa grafik menggunakan ChartJS
     */

    //-------------
    //- BAR CHART Umur -
    //-------------
    var barChartCanvasUmur = $('#barChartUmur').get(0).getContext('2d')

    var barChartDataUmur = {
        labels: ['<18', '18-25', '26-30', '31-35', '36-40', '41-45', '46-50', '51-55', '>55'],
        datasets: [{
                label: 'Pria',
                backgroundColor: 'rgba(60,141,188,0.9)',
                borderColor: 'rgba(60,141,188,0.8)',
                pointRadius: false,
                pointColor: '#3b8bba',
                pointStrokeColor: 'rgba(60,141,188,1)',
                pointHighlightFill: '#fff',
                pointHighlightStroke: 'rgba(60,141,188,1)',
                data: [<?php echo $pria18; ?>, <?php echo $pria18_25; ?>, <?php echo $pria26_30; ?>,
                    <?php echo $pria31_35; ?>, <?php echo $pria36_40; ?>, <?php echo $pria41_45; ?>,
                    <?php echo $pria46_50; ?>, <?php echo $pria51_55; ?>, <?php echo $pria55; ?>
                ]
            },
            {
                label: 'Perempuan',
                backgroundColor: 'rgba(210, 214, 222, 1)',
                borderColor: 'rgba(210, 214, 222, 1)',
                pointRadius: false,
                pointColor: 'rgba(210, 214, 222, 1)',
                pointStrokeColor: '#c1c7d1',
                pointHighlightFill: '#fff',
                pointHighlightStroke: 'rgba(220,220,220,1)',
                data: [<?php echo $perempuan18; ?>, <?php echo $perempuan18_25; ?>,
                    <?php echo $perempuan26_30; ?>, <?php echo $perempuan31_35; ?>,
                    <?php echo $perempuan36_40; ?>, <?php echo $perempuan41_45; ?>,
                    <?php echo $perempuan46_50; ?>, <?php echo $perempuan51_55; ?>,
                    <?php echo $perempuan55; ?>
                ]
            }
        ]
    }

    var barChartOptionsUmur = {
        responsive: true,
        maintainAspectRatio: false,
        datasetFill: false
    }

    new Chart(barChartCanvasUmur, {
        type: 'bar',
        data: barChartDataUmur,
        options: barChartOptionsUmur
    })


    //-------------
    //- BAR CHART MK -
    //-------------
    var barChartCanvasMK = $('#barChartMK').get(0).getContext('2d')

    var barChartDataMK = {
        labels: ['0-5', '6-10', '11-15', '16-20', '21-25', '26-30', '>30'],
        datasets: [{
                label: 'Pria',
                backgroundColor: 'rgba(60,141,188,0.9)',
                borderColor: 'rgba(60,141,188,0.8)',
                pointRadius: false,
                pointColor: '#3b8bba',
                pointStrokeColor: 'rgba(60,141,188,1)',
                pointHighlightFill: '#fff',
                pointHighlightStroke: 'rgba(60,141,188,1)',
                data: [<?php echo $jumlah_pria_kurang_dari_5; ?>, <?php echo $jumlah_pria_6_10; ?>,
                    <?php echo $jumlah_pria_11_15; ?>, <?php echo $jumlah_pria_16_20; ?>,
                    <?php echo $jumlah_pria_21_25; ?>, <?php echo $jumlah_pria_26_30; ?>,
                    <?php echo $jumlah_pria_lebih_dari_30; ?>
                ]
            },
            {
                label: 'Perempuan',
                backgroundColor: 'rgba(210, 214, 222, 1)',
                borderColor: 'rgba(210, 214, 222, 1)',
                pointRadius: false,
                pointColor: 'rgba(210, 214, 222, 1)',
                pointStrokeColor: '#c1c7d1',
                pointHighlightFill: '#fff',
                pointHighlightStroke: 'rgba(220,220,220,1)',
                data: [<?php echo $jumlah_perempuan_kurang_dari_5; ?>, <?php echo $jumlah_perempuan_6_10; ?>,
                    <?php echo $jumlah_perempuan_11_15; ?>, <?php echo $jumlah_perempuan_16_20; ?>,
                    <?php echo $jumlah_perempuan_21_25; ?>, <?php echo $jumlah_perempuan_26_30; ?>,
                    <?php echo $jumlah_perempuan_lebih_dari_30; ?>
                ]
            }
        ]
    }

    var barChartOptionsMK = {
        responsive: true,
        maintainAspectRatio: false,
        datasetFill: false
    }

    new Chart(barChartCanvasMK, {
        type: 'bar',
        data: barChartDataMK,
        options: barChartOptionsMK
    })

    //-------------
    //- BAR CHART JK -
    //-------------
    var barChartCanvasJK = $('#barChartJK').get(0).getContext('2d');

    var barChartDataJK = {
        labels: ['Pria', 'Wanita'],
        datasets: [{
            label: 'Jenis Kelamin',
            backgroundColor: ['rgba(60,141,188,0.9)', 'rgba(210, 214, 222, 1)'],
            borderColor: ['rgba(60,141,188,0.8)', 'rgba(210, 214, 222, 1)'],
            pointRadius: false,
            pointColor: '#3b8bba',
            pointStrokeColor: 'rgba(60,141,188,1)',
            pointHighlightFill: '#fff',
            pointHighlightStroke: 'rgba(60,141,188,1)',
            data: [<?php echo $pria; ?>, <?php echo $perempuan; ?>]
        }]
    };

    var barChartOptionsJK = {
        responsive: true,
        maintainAspectRatio: false,
        datasetFill: false
    };

    new Chart(barChartCanvasJK, {
        type: 'pie',
        data: barChartDataJK,
        options: barChartOptionsJK
    })

    //-------------
    //- BAR CHART Pendidikan -
    //-------------
    var barChartCanvasPend = $('#barChartPend').get(0).getContext('2d')

    var barChartDataPend = {
        labels: ['SD-, SLTP', 'SLTA', 'Diploma', 'S1', 'S2, S3'],
        datasets: [{
                label: 'Pria',
                backgroundColor: 'rgba(60,141,188,0.9)',
                borderColor: 'rgba(60,141,188,0.8)',
                pointRadius: false,
                pointColor: '#3b8bba',
                pointStrokeColor: 'rgba(60,141,188,1)',
                pointHighlightFill: '#fff',
                pointHighlightStroke: 'rgba(60,141,188,1)',
                data: [<?php echo $jumlah_pria_SD + $jumlah_pria_SLTP; ?>, <?php echo $jumlah_pria_SMA; ?>,
                    <?php echo $jumlah_pria_Diploma; ?>, <?php echo $jumlah_pria_S1; ?>,
                    <?php echo $jumlah_pria_S2 + $jumlah_pria_S3; ?>
                ]
            },
            {
                label: 'Perempuan',
                backgroundColor: 'rgba(210, 214, 222, 1)',
                borderColor: 'rgba(210, 214, 222, 1)',
                pointRadius: false,
                pointColor: 'rgba(210, 214, 222, 1)',
                pointStrokeColor: '#c1c7d1',
                pointHighlightFill: '#fff',
                pointHighlightStroke: 'rgba(220,220,220,1)',
                data: [<?php echo $jumlah_perempuan_SD + $jumlah_perempuan_SLTP; ?>,
                    <?php echo $jumlah_perempuan_SMA; ?>, <?php echo $jumlah_perempuan_Diploma; ?>,
                    <?php echo $jumlah_perempuan_S1; ?>,
                    <?php echo $jumlah_perempuan_S2 + $jumlah_perempuan_S3; ?>
                ]
            }
        ]
    }

    var barChartOptionsPend = {
        responsive: true,
        maintainAspectRatio: false,
        datasetFill: false
    }

    new Chart(barChartCanvasPend, {
        type: 'bar',
        data: barChartDataPend,
        options: barChartOptionsPend
    })
    </script>

    <script>
    $(document).ready(function() {
        $('.export-excel-btn').on('click', function(e) {
            $('#loadingAlert').show();
            // Biarkan pengunduhan berlangsung setelah menunjukkan alert
            setTimeout(function() {
                    $('#loadingAlert').hide();
                },
                5000
            ); // Sembunyikan alert setelah 5 detik (Anda bisa menyesuaikan durasi sesuai kebutuhan)
        });
    });
    </script>
</body>

</html>

<style>
.loading-alert {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    padding: 20px;
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
    border-radius: 5px;
    z-index: 1000;
}

.btn-success.btn-sm.export-excel-btn {
    color: white;
}

.btn-success.btn-sm.export-excel-btn:hover {
    background-color: white;
    color: #28a745;
    /* Bootstrap success color */
}
</style>