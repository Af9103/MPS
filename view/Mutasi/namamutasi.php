<?php
session_start();

if (!isset($_SESSION["npk"]) || !isset($_SESSION["dept"]) || $_SESSION["dept"] !== "HRD IR" || !isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true) {
    echo "Anda harus login sebagai HRD untuk mengakses halaman ini";
    header("Location: ../forbidden.php");
    exit; // Pastikan untuk menghentikan eksekusi skrip setelah melakukan redirect
}
include __DIR__ . '/../../query/koneksi.php';

// Initialize current month and year
$currentMonth = date('m');
$currentYear = date('Y');

// Retrieve 'cwoc' parameter from the GET request
$cwoc = isset($_GET['cwoc']) ? $_GET['cwoc'] : '';
$sect = isset($_GET['sect']) ? $_GET['sect'] : '';

// Check if "Tampilkan semua" link is clicked
if (isset($_GET['show_all'])) {
    // Query to retrieve all mutation data based on cwoc without any filter on date
    $queryMutasi = "SELECT *
                FROM mutasi
                WHERE hapus IS NULL
                AND status = '10' AND
    IF('$cwoc' != '', cwocBaru = '$cwoc', 1) AND
    IF('$sect' != '', sectBaru = '$sect', 1)";
    $judulDaftar = "Daftar Seluruh Mutasi $cwoc";

} else {
    // Retrieve selected month and year from the GET request, or default to current month and year
    $selectedMonth = isset($_GET['bulan']) ? $_GET['bulan'] : $currentMonth;
    $selectedYear = isset($_GET['tahun']) ? $_GET['tahun'] : $currentYear;

    // Query to retrieve mutation data based on the selected month and year
    $queryMutasi = "SELECT *
                FROM mutasi
                WHERE hapus IS NULL
                AND status = '10' AND hapus IS NULL AND MONTH(tanggalMutasi) = '$selectedMonth' 
        AND YEAR(tanggalMutasi) = '$selectedYear'
        AND IF('$cwoc' != '', cwocBaru = '$cwoc', 1) AND
    IF('$sect' != '', sectBaru = '$sect', 1)";

    // Convert selectedMonth to an integer and get the month name
    $selectedMonthInt = intval($selectedMonth);
    $namaBulan = $selectedMonthInt !== 0 ? date("F", mktime(0, 0, 0, $selectedMonthInt, 1)) : "";

    // Define the title based on selected month and year
    if ($selectedMonth !== null && $selectedYear !== null && $selectedMonthInt !== 0) {
        $judulDaftar = "Daftar Mutasi $namaBulan $selectedYear $cwoc";
    } else {
        $judulDaftar = "Daftar Mutasi Berdasarkan CWOC: $cwoc";
    }
}

// Execute the query
$resultMutasi = mysqli_query($koneksi3, $queryMutasi);
if (!$resultMutasi) {
    die("Query error: " . mysqli_error($koneksi3));
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Real Time Man Power | karyawan</title>
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
            <img class="animation__shake" src="../../img/k-logo.jpg" alt="AdminLTELogo" height="100" width="100">
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
                        <h1 class="m-0">History Mutasi</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="../MPS/dashboard.php">Home</a></li>
                            <li class="breadcrumb-item active">History Mutasi</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="card-header">
                <div class="card-tools">
                    <div class="input-group input-group-sm">

                        <div class="input-group-prepend" style="width: 150px; margin-top:-20px;">
                            <select id="tahun" class="selectpicker" data-live-search="true">
                                <?php
                                // Retrieve the current year
                                $currentYear = date('Y');
                                // Set minimum year
                                $minYear = 2012;
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
                        <!-- Elemen filter bulan -->
                        <div class="input-group-prepend" style="width: 150px; margin-left:10px; margin-top:-20px;">
                            <select id="bulan" class="selectpicker" data-live-search="true" onchange="filterByMonth()">
                                <?php
                                // Retrieve the selected month from URL parameter
                                $selectedMonth = isset($_GET['bulan']) ? $_GET['bulan'] : date('m'); // default to current month if not set
                                // Generate options for the select element for each month
                                for ($i = 1; $i <= 12; $i++) {
                                    $month = str_pad($i, 2, '0', STR_PAD_LEFT);
                                    $selected = ($month == $selectedMonth) ? "selected" : "";
                                    $monthName = date("F", mktime(0, 0, 0, $i, 1));
                                    echo "<option value=\"$month\" $selected>$monthName</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <!-- Label "Tampilkan semua" -->
                        <p style="margin-left: 10px; margin-top: -12px;">
                            <a href="namamutasi.php?show_all=1&cwoc=<?php echo urlencode($cwoc); ?>&sect=<?php echo urlencode($sect); ?>"
                                style="text-decoration: none; color: inherit;">Tampilkan semua</a>
                        </p>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo $judulDaftar; ?></h3>
                            <div class="card-tools">
                                <div class="input-group input-group-sm" style="width: 150px;">

                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-head-fixed text-nowrap mx-auto" style="width: 100%;">
                                <thead class="thead-fixed">
                                    <tr>
                                        <th rowspan="2" style="vertical-align: middle;">No</th>
                                        <th rowspan="2" style="vertical-align: middle;">NPK</th>
                                        <th rowspan="2" style="vertical-align: middle;">Nama</th>
                                        <th colspan="2">Dari</th>
                                        <th colspan="2">Ke</th>
                                        <th rowspan="2" style="vertical-align: middle;">Tanggal Mutasi</th>
                                    </tr>
                                    <tr>
                                        <th rowspan="1">Departemen</th>
                                        <th rowspan="1">Seksi</th>
                                        <th rowspan="1">Departemen</th>
                                        <th rowspan="1">Seksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1; // Definisikan variabel $no di sini
                                    if (mysqli_num_rows($resultMutasi) > 0) {
                                        while ($row = mysqli_fetch_assoc($resultMutasi)) {
                                            $sectAsal = $row['sectAsal'];
                                            $sectBaru = $row['sectBaru'];
                                            // Tampilkan data dalam tabel
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
                                            // Tampilkan data dalam tabel
                                            echo "<tr>";
                                            echo "<td>" . $no++ . "</td>"; // Menampilkan nomor urutan (increment $no setelah digunakan)
                                            echo "<td><a href='../MPS/profile.php?emno={$row['emno']}' style='color:black;'>" . $row['emno'] . "</td>";
                                            echo "<td>" . $row['nama'] . "</td>";
                                            echo "<td>" . $row['cwocAsal'] . "</td>";
                                            echo "<td>" . $row['sectAsalDesc'] . "</td>";
                                            echo "<td>" . $row['cwocBaru'] . "</td>";
                                            echo "<td>" . $row['sectBaruDesc'] . "</td>";
                                            echo "<td>" . date('d F Y', strtotime($row['tanggalMutasi'])) . "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        // Jika tidak ada data ditemukan, tampilkan pesan dalam sebuah baris tabel
                                        echo "<tr><td colspan='8'>Result Not Found</td></tr>";
                                    }
                                    ?>
                                </tbody>
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



</body>

</html>