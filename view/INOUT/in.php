<?php
session_start();

// Periksa apakah pengguna sudah login dan dari departemen HRD
if (!isset($_SESSION["npk"]) || !isset($_SESSION["dept"]) || $_SESSION["dept"] !== "HRD IR" || !isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true) {
  echo "Anda harus login sebagai HRD untuk mengakses halaman ini";
  header("Location: ../forbidden.php");
  exit; // Pastikan untuk menghentikan eksekusi skrip setelah melakukan redirect
}
include __DIR__ . '/../../query/koneksi.php';

$currentYear = date('Y');
$selectedYear = isset($_GET['tahun']) ? $_GET['tahun'] : $currentYear;
$urlToPHPExcel = "../../output/excel3.php";
if (isset($_GET['show_all'])) {
  $urlToPHPExcel .= "?show_all=1";
} else {
  // Jika bulan dipilih
  if (isset($_GET['bulan'])) {
    $selectedMonth = $_GET['bulan'];
    $urlToPHPExcel .= "?tahun=$selectedYear&bulan=$selectedMonth";
  } else {
    // Jika tidak ada bulan yang dipilih, gunakan hanya tahun
    $urlToPHPExcel .= "?tahun=$selectedYear";
  }
}

$currentMonth = date('m');

if (isset($_GET['show_all'])) {
  // Query to retrieve all mutation data without any filter on date
  $queryIn = "SELECT karyawan.*, nama.nama_karyawan, subsect.desc AS subsect_desc, sect.desc AS sect_desc 
                FROM karyawan 
                LEFT JOIN nama ON karyawan.emno = nama.emno 
                LEFT JOIN sect ON karyawan.sect = sect.Id_sect 
                LEFT JOIN subsect ON karyawan.subsect = subsect.id_subsect
                ORDER BY karyawan.joindate ASC";
} else {
  // Determine default month range based on the current month
  if ($currentMonth >= 1 && $currentMonth <= 4) {
    $defaultMonthRange = '01-04';
  } elseif ($currentMonth >= 5 && $currentMonth <= 8) {
    $defaultMonthRange = '05-08';
  } else {
    $defaultMonthRange = '09-12';
  }

  // Set default value for month range if no filter is applied
  $selectedMonth = isset($_GET['bulan']) ? $_GET['bulan'] : $defaultMonthRange;

  // Filter data based on the selected month range and year, or show data for the current month range if no filter is applied
  $selectedYear = isset($_GET['tahun']) ? $_GET['tahun'] : $currentYear;

  // Parse start month and end month from the selected month range
  $monthRange = explode("-", $selectedMonth);
  $startMonth = $monthRange[0];
  $endMonth = $monthRange[1];

  // Query to retrieve mutation data based on the selected month range and year
  $queryIn = "SELECT karyawan.*, nama.nama_karyawan, subsect.desc AS subsect_desc, sect.desc AS sect_desc 
                FROM karyawan 
                LEFT JOIN nama ON karyawan.emno = nama.emno 
                LEFT JOIN sect ON karyawan.sect = sect.Id_sect 
                LEFT JOIN subsect ON karyawan.subsect = subsect.id_subsect
                WHERE MONTH(karyawan.joindate) BETWEEN '$startMonth' AND '$endMonth' AND YEAR(karyawan.joindate) = '$selectedYear' AND MONTH(karyawan.joindate) BETWEEN '$startMonth' AND '$endMonth' AND YEAR(karyawan.joindate) = '$selectedYear'
                ORDER BY karyawan.joindate ASC";
}

// Execute the query
$resultIn = mysqli_query($koneksi, $queryIn);
if (!$resultIn) {
  die("Query error: " . mysqli_error($koneksi));
}

if (isset($_GET['show_all'])) {
  // If "Tampilkan semua" link is clicked
  $judulDaftar = "Daftar Karyawan";
} else {
  // If not clicked, set the title based on selected month and year
  if ($selectedYear !== null) {
    $selectedMonth = isset($_GET['bulan']) ? $_GET['bulan'] : null;
    if ($selectedMonth !== null) {
      $monthRange = explode("-", $selectedMonth);
      $startMonth = date("F", mktime(0, 0, 0, $monthRange[0], 1));
      $endMonth = date("F", mktime(0, 0, 0, $monthRange[1], 1));
      $judulDaftar = "Daftar Karyawan Perekrutan $startMonth - $endMonth $selectedYear";
    } else {
      // Determine default month range based on the current month
      $currentMonth = date("n");
      if ($currentMonth >= 1 && $currentMonth <= 4) {
        $startMonth = date("F", mktime(0, 0, 0, 1, 1));
        $endMonth = date("F", mktime(0, 0, 0, 4, 1));
      } elseif ($currentMonth >= 5 && $currentMonth <= 8) {
        $startMonth = date("F", mktime(0, 0, 0, 5, 1));
        $endMonth = date("F", mktime(0, 0, 0, 8, 1));
      } else {
        $startMonth = date("F", mktime(0, 0, 0, 9, 1));
        $endMonth = date("F", mktime(0, 0, 0, 12, 1));
      }
      $judulDaftar = "Daftar Karyawan Perekrutan $startMonth - $endMonth $selectedYear";
    }
  } else {
    $judulDaftar = "Daftar Karyawan Perekrutan";
  }
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
  <link rel="stylesheet" href="../../asset/select/select.min.css">

  <link rel="stylesheet" href="../../style.css"> <!-- Hubungkan dengan file CSS terpisah -->



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
            <h1 class="m-0">Riwayat Perekrutan Karyawan</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../MPS/dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Riwayat Perekrutan Karyawan</li>
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
                $minYear = 2010;
                // Retrieve the selected year from URL parameter or default to current year
                $selectedYear = isset($_GET['tahun']) ? $_GET['tahun'] : $currentYear;
                // Generate options for the select element, starting from the minimum year to the current year
                for ($i = $minYear; $i <= $currentYear; $i++) {
                  $selected = ($i == $selectedYear) ? "selected" : "";
                  echo "<option value=\"$i\" $selected>$i</option>";
                }
                ?>
              </select>
            </div>

            <div class="input-group-prepend" style="width: 150px; margin-left:10px; margin-top:-20px;">
              <select id="bulan" class="selectpicker" data-live-search="true" onchange="filterByMonth()">
                <?php
                // Retrieve the selected month from URL parameter or default to current month
                $selectedMonth = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
                // Generate options for the select element for each 4-month range
                for ($i = 1; $i <= 12; $i += 4) {
                  $startMonth = str_pad($i, 2, '0', STR_PAD_LEFT);
                  $endMonth = str_pad($i + 3, 2, '0', STR_PAD_LEFT);
                  // Define the range label
                  $rangeLabel = date("F", mktime(0, 0, 0, $i, 1)) . " - " . date("F", mktime(0, 0, 0, $i + 3, 1));
                  $selected = ($selectedMonth >= $startMonth && $selectedMonth <= $endMonth) ? "selected" : "";
                  echo "<option value=\"$startMonth-$endMonth\" $selected>$rangeLabel</option>";
                }
                ?>
              </select>
            </div>

            <p style="margin-left: 10px; margin-top: -12px;">
              <a href="#" id="showAllLink" style="text-decoration: none; color: inherit;">Tampilkan semua</a>
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
                <div class="input-group input-group-sm justify-content-end" style="width: 250px;">
                  <!-- Tempatkan tombol export to excel di sini -->
                  <a href="#" class="btn btn-success btn-sm" id="exportExcel">
                    <i class="fas fa-file-excel"></i> &nbsp;Laporan Excel
                  </a>
                </div>

              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
              <table class="table table-head-fixed text-nowrap mx-auto" style="width: 100%;">
                <thead class="thead-fixed">
                  <tr>
                    <th>No</th>
                    <th>NPK</th>
                    <th>Nama</th>
                    <th>Jenis Kelamin</th>
                    <th>Golongan</th>
                    <th>Status</th>
                    <th>Posisi</th>
                    <th>Departemen</th>
                    <th>Seksi</th>
                    <th>Tanggal Masuk</th>
                  </tr>
                </thead>
                <tr id='loading' style='display: none;'>
                  <td colspan='11'>
                    <div class='spinner-container'>
                      <svg class="spinner" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                        <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30">
                        </circle>
                      </svg>
                      <div class='loading-text'>Loading...</div>
                    </div>
                  </td>
                </tr>
                <tbody id="table-body">
                  <?php
                  $no = 1; // Definisikan variabel $no di sini
                  if (mysqli_num_rows($resultIn) > 0) {
                    while ($row = mysqli_fetch_assoc($resultIn)) {
                      // Tampilkan data dalam tabel
                      echo "<tr>";
                      echo "<td>" . $no++ . "</td>"; // Menampilkan nomor urutan (increment $no setelah digunakan)
                      echo "<td><a href='../MPS/profile.php?emno={$row['emno']}' style='color:black;'>" . $row['emno'] . "</td>";
                      echo "<td>" . $row['nama_karyawan'] . "</td>";
                      echo "<td>";
                      if ($row['sexe'] == 'L') {
                        echo "Laki Laki";
                      } elseif ($row['sexe'] == 'P') {
                        echo "Perempuan";
                      } else {
                        echo "Unknown"; // Optional: handle other cases if needed
                      }
                      echo "</td>";

                      echo "<td>" . $row['gol'] . "</td>";
                      echo "<td>";
                      if (strpos($row['emno'], 'K') === 0) {
                        echo "Kontrak";
                      } elseif (preg_match('/^0/', $row['emno']) || preg_match('/^[0-9]+$/', $row['emno'] || strpos($row['emno'], 'EXP') === 0)) {
                        echo "Permanen";
                      } elseif (strpos($row['emno'], 'P') === 0) {
                        echo "Trainee";
                      } else {
                        echo "Unknown"; // Optional: handle other cases if needed
                      }
                      echo "</td>";

                      echo "<td>";
                      if ($row['gol'] >= 0 && $row['gol'] <= 2) {
                        echo "Operator";
                      } elseif ($row['gol'] == 3) {
                        echo "Foreman";
                      } elseif ($row['gol'] == 4) {
                        echo "Supervisor";
                      } elseif ($row['gol'] == 5) {
                        echo "Manager";
                      } elseif ($row['gol'] == 6) {
                        echo "BOD";
                      } else {
                        echo "Unknown";
                      }
                      echo "</td>";

                      echo "<td>" . $row['cwoc'] . "</td>";
                      echo "<td>" . $row['sect_desc'] . "</td>";
                      echo "<td>" . date('d F Y', strtotime($row['joindate'])) . "</td>";
                      echo "</tr>";
                    }
                  } else {
                    // Jika tidak ada data ditemukan, tampilkan pesan dalam sebuah baris tabel
                    echo "<tr><td colspan='11'>Result Not Found</td></tr>";
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
  <script src="../../asset/js/day.js"></script>
  <script src="../../asset/select/select.min.js"></script>

  <script>
    $(document).ready(function () {
      var exportBaseUrl = '../../output/excel3.php';

      // Fungsi untuk menangani klik pada tombol Export Excel
      function handleExportExcel(e) {
        e.preventDefault();

        // Ambil nilai bulan dan tahun yang sedang dipilih
        var selectedMonth = $('#bulan').val();
        var selectedYear = $('#tahun').val();
        var exportUrl = exportBaseUrl;

        // Cek apakah sedang menampilkan semua data
        if ($('#showAllLink').data('show-all')) {
          exportUrl += '?show_all=1';
        } else {
          exportUrl += '?tahun=' + selectedYear + '&bulan=' + selectedMonth;
        }

        // Redirect ke halaman ekspor Excel
        window.location.href = exportUrl;
      }

      $('#exportExcel').click(handleExportExcel);

      // Fungsi untuk meng-handle perubahan pada dropdown bulan dan tahun
      $('#bulan, #tahun').change(function () {
        $('#showAllLink').data('show-all', false);
        filterByMonthAndYear();
      });

      function filterByMonthAndYear() {
        var selectedMonth = $('#bulan').val();
        var selectedYear = $('#tahun').val();
        $('#loading').show();
        $('#table-body').hide();
        var url = 'in.php'; // Sesuaikan dengan nama halaman PHP Anda

        // Lakukan permintaan AJAX
        $.ajax({
          type: 'GET',
          url: url,
          data: {
            bulan: selectedMonth,
            tahun: selectedYear
          },
          success: function (data) {
            // Dapatkan tabel dari respons data
            $('#loading').hide();
            var table = $(data).find('.table-responsive').html();
            // Perbarui tabel di halaman saat ini
            $('.table-responsive').html(table);
            $('#table-body').show();

            var title = $(data).find('.card-title').text();
            $('h3').text(title);
          }
        });
      }

      // Fungsi untuk menangani klik pada tautan "Tampilkan semua"
      $('#showAllLink').click(function (e) {
        e.preventDefault();
        $('#loading').show();
        $('#table-body').hide();
        var url = 'out.php'; // Sesuaikan dengan nama halaman PHP Anda

        // Lakukan permintaan AJAX tanpa parameter bulan dan tahun
        $.ajax({
          type: 'GET',
          url: url,
          data: {
            show_all: '1'
          },
          success: function (data) {
            $('#loading').hide();
            // Dapatkan tabel dari respons data
            var table = $(data).find('.table-responsive').html();
            // Perbarui tabel di halaman saat ini
            $('.table-responsive').html(table);
            $('#table-body').show();

            var title = $(data).find('.card-title').text();
            $('h3').text(title);

            // Tandai bahwa sedang menampilkan semua data
            $('#showAllLink').data('show-all', true);
          }
        });
      });
    });
  </script>

</body>

</html>
<style>
  .spinner {
    -webkit-animation: rotator 1.4s linear infinite;
    animation: rotator 1.4s linear infinite;
    width: 20px;
    /* Adjusted size */
    height: 20px;
    /* Adjusted size */
  }

  @-webkit-keyframes rotator {
    0% {
      transform: rotate(0deg);
    }

    100% {
      transform: rotate(270deg);
    }
  }

  @keyframes rotator {
    0% {
      transform: rotate(0deg);
    }

    100% {
      transform: rotate(270deg);
    }
  }

  .path {
    stroke-dasharray: 187;
    stroke-dashoffset: 0;
    transform-origin: center;
    -webkit-animation: dash 1.4s ease-in-out infinite, colors 5.6s ease-in-out infinite;
    animation: dash 1.4s ease-in-out infinite, colors 5.6s ease-in-out infinite;
  }

  @-webkit-keyframes colors {
    0% {
      stroke: #4285F4;
    }

    25% {
      stroke: #DE3E35;
    }

    50% {
      stroke: #F7C223;
    }

    75% {
      stroke: #1B9A59;
    }

    100% {
      stroke: #4285F4;
    }
  }

  @keyframes colors {
    0% {
      stroke: #4285F4;
    }

    25% {
      stroke: #DE3E35;
    }

    50% {
      stroke: #F7C223;
    }

    75% {
      stroke: #1B9A59;
    }

    100% {
      stroke: #4285F4;
    }
  }

  @-webkit-keyframes dash {
    0% {
      stroke-dashoffset: 187;
    }

    50% {
      stroke-dashoffset: 46.75;
      transform: rotate(135deg);
    }

    100% {
      stroke-dashoffset: 187;
      transform: rotate(450deg);
    }
  }

  @keyframes dash {
    0% {
      stroke-dashoffset: 187;
    }

    50% {
      stroke-dashoffset: 46.75;
      transform: rotate(135deg);
    }

    100% {
      stroke-dashoffset: 187;
      transform: rotate(450deg);
    }
  }
</style>