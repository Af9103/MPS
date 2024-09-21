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

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Real Time Man Power | karyawan <?php echo $descSub; ?></title>
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
            <h1 class="m-0">Daftar karyawan</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../MPS/dashboard.php">Home</a></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">


      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Departemen</h3>
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
                    <th>No</th>
                    <th>Departemen</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $no = 1; // Definisikan variabel $no di sini
                  while ($user_data = mysqli_fetch_array($resultRMP)) {
                    // Tampilkan data dalam tabel
                    echo "<tr>";
                    echo "<td>" . $no++ . "</td>"; // Menampilkan nomor urutan (increment $no setelah digunakan)
                    $cwoc = $user_data['cwoc'];
                    $display_text = str_replace(
                      ['BOD TA EXP', 'HRD IR', 'FINANCE ACCOUNTING', 'PLANNING BUDGETING'],
                      ['BOD & TA EXP', 'HRD & IR', 'FINANCE & ACCOUNTING', 'PLANNING & BUDGETING'],
                      $cwoc
                    );

                    if (in_array($cwoc, ["PRODUCTION 1", "PRODUCTION 2", "PRODUCTION 3", "PRODUCTION 4", "PRODUCTION 5"])) {
                      // Jika cwoc adalah salah satu dari nilai yang diinginkan, arahkan ke sectmutasi
                      echo "<td><a href='sectmutasi.php?cwoc={$cwoc}' style='color:black;'>{$display_text}</a></td>";
                    } else {
                      // Jika cwoc bukan salah satu dari nilai yang diinginkan, arahkan ke namamutasi
                      echo "<td><a href='namamutasi.php?cwoc={$cwoc}' style='color:black;'>{$display_text}</a></td>";
                    }
                    echo "</tr>";
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
  <script src="../../asset/js/time.js"></script>


</body>

</html>