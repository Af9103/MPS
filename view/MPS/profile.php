<?php
session_start();

if (!isset($_SESSION["npk"]) || !isset($_SESSION["dept"]) || $_SESSION["dept"] !== "HRD IR" || !isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true) {
    echo "Anda harus login sebagai HRD untuk mengakses halaman ini";
    header("Location: ../forbidden.php");
    exit; // Pastikan untuk menghentikan eksekusi skrip setelah melakukan redirect
}
include __DIR__ . '/../../query/koneksi.php';
include __DIR__ . '/../../query/query.php';

// Ambil emno dari URL
$emno = $_GET['emno'];
$query = "SELECT 
    karyawan.*, 
    nama.nama_karyawan, 
    subsect.desc AS subsect_desc, 
    sect.desc AS sect_desc,
    histori.cwoc AS histori_cwoc,
    histori.tanggal, 
    (SELECT sect.desc FROM sect WHERE sect.Id_sect = histori.sect) AS histori_sect,
    (SELECT DATE_SUB(MIN(histori_selanjutnya.tanggal), INTERVAL 1 DAY)
        FROM histori AS histori_selanjutnya
        WHERE histori_selanjutnya.emno = karyawan.emno
            AND histori_selanjutnya.tanggal > histori.tanggal) AS tanggal_selanjutnya
FROM 
    karyawan 
LEFT JOIN 
    nama ON karyawan.emno = nama.emno 
INNER JOIN 
    sect ON karyawan.sect = sect.Id_sect 
INNER JOIN 
    subsect ON karyawan.subsect = subsect.id_subsect
LEFT JOIN 
    histori ON karyawan.emno = histori.emno
WHERE 
    karyawan.emno = '$emno'";

$result = mysqli_query($koneksi, $query);

// Check if the query was executed successfully
if (!$result) {
    die("Query error: " . mysqli_error($koneksi));
}

// Fetch employee profile data
$dataKaryawan = mysqli_fetch_assoc($result);

// Display employee profile data
$nama_karyawan = $dataKaryawan['nama_karyawan'];
$sexe = $dataKaryawan['sexe'];
$birthday = $dataKaryawan['birthday'];
$tanggal_lahir = $dataKaryawan['birthday'];
$joindate = $dataKaryawan['joindate'];
$tanggal_masuk = $dataKaryawan['joindate'];
$pendidikan = $dataKaryawan['pendidikan'];
$cwoc = $dataKaryawan['cwoc'];
$desc1 = $dataKaryawan['sect_desc']; // Mengambil kolom desc dari tabel sect
$desc2 = $dataKaryawan['subsect_desc']; // Mengambil kolom desc dari tabel subsect
$sect = $dataKaryawan['sect'];
$subsect = $dataKaryawan['subsect'];
$gol = $dataKaryawan['gol'];
$status = $dataKaryawan['emno'];
$posisi = $dataKaryawan['gol'];

if (strpos($status, 'K') === 0) {
    $status = "Kontrak";
} elseif (preg_match('/^0/', $status) || preg_match('/^[0-9]+$/', $status)) {
    $status = "Tetap";
} elseif (strpos($status, 'P') === 0) {
    $status = "Trainee";
} else {
    $status = "Unknown"; // Optional: handle other cases if needed
}

if ($posisi >= 0 && $posisi <= 2) {
    $posisi = 'Operator';
} elseif ($posisi == 3) {
    $posisi = 'Foreman';
} elseif ($posisi == 4) {
    $posisi = 'Supervisor';
} elseif ($posisi == 5) {
    $posisi = 'Manager';
} elseif ($posisi == 6) {
    $posisi = 'BOD';
} else {
    // Tindakan yang diambil jika nilai 'posisi' tidak sesuai dengan yang diharapkan
    $posisi = 'Tidak Diketahui';
}

if ($sexe == 'P') {
    $sexe = 'Perempuan';
} elseif ($sexe == 'L') {
    $sexe = 'Laki-Laki';
} else {
    // Tindakan yang diambil jika nilai 'sexe' tidak sesuai dengan yang diharapkan
    $sexe = 'Tidak Diketahui';
}

$tanggal_lahir = new DateTime($tanggal_lahir);
$today = new DateTime('today');
$usia = $today->diff($tanggal_lahir)->y;

$joindate = date('d F Y', strtotime($joindate));

$tanggal_masuk = new DateTime($tanggal_masuk);
$today = new DateTime('today');
$masa_kerja = $today->diff($tanggal_masuk)->y;

$birthday = date('d F Y', strtotime($birthday));

$queryDescSect = "SELECT karyawan.*, sect.`desc` AS 'sect_desc', subsect.`desc` AS 'subsect_desc'
                  FROM karyawan 
                  INNER JOIN sect ON karyawan.sect = sect.Id_sect 
                  INNER JOIN subsect ON karyawan.subsect = subsect.id_subsect 
                  WHERE karyawan.emno='$emno'";
$resultDescSect = mysqli_query($koneksi, $queryDescSect);

$descSect = "Nilai tidak tersedia";  // Nilai default
if ($resultDescSect) {
    if (mysqli_num_rows($resultDescSect) > 0) {
        $row = mysqli_fetch_assoc($resultDescSect);
        $descSect = $row['sect_desc']; // Menggunakan nama alias kolom
    } else {
        echo "Tidak ada data yang cocok dengan kriteria pencarian.";
    }
} else {
    echo "Error: " . mysqli_error($koneksi);
}


// Query untuk mendapatkan cwoc
$queryCwoc = "SELECT `cwoc` FROM `karyawan` WHERE karyawan.emno='$emno'";
$resultCwoc = mysqli_query($koneksi, $queryCwoc);

$cwoc = "Nilai tidak tersedia";  // Default value
if ($resultCwoc && mysqli_num_rows($resultCwoc) > 0) {
    $row = mysqli_fetch_assoc($resultCwoc);
    $cwoc = $row['cwoc'];
}

// Query untuk mendapatkan deskripsi subsect
$queryDescSub = "SELECT karyawan.*, subsect.`desc` 
                 FROM karyawan 
                 INNER JOIN subsect ON karyawan.subsect = subsect.id_subsect 
                 WHERE karyawan.emno='$emno'";
$resultDescSub = mysqli_query($koneksi, $queryDescSub);

$descSub = "Nilai tidak tersedia";  // Nilai default
if ($resultDescSub && mysqli_num_rows($resultDescSub) > 0) {
    $row = mysqli_fetch_assoc($resultDescSub);
    $descSub = $row['desc'];
} else {
    // Handle kesalahan jika query gagal
    $descSub = "Nilai tidak tersedia";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Real Time Man Power | Profile <?php echo $nama_karyawan; ?></title>
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
                        <h1 class="m-0">Profile</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                            <li class="breadcrumb-item"><a
                                    href="sect.php?cwoc=<?php echo urlencode($cwoc); ?>"><?php echo $cwoc; ?></a></li>
                            <li class="breadcrumb-item"><a
                                    href="subsect.php?sect=<?php echo urlencode($sect); ?>"><?php echo $descSect; ?></a>
                            </li>
                            <li class="breadcrumb-item"><a
                                    href="nama.php?subsect=<?php echo urlencode($subsect); ?>"><?php echo $descSub; ?></a>
                            </li>
                            <li class="breadcrumb-item active"><?php echo $nama_karyawan; ?></a></a></li>
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
                    <div class="col-md-3">
                        <!-- Profile Image -->
                        <div class="card card-dark card-outline">
                            <div class="card-body box-profile">
                                <div class="text-center">
                                    <div class="p-picture-container">
                                        <img src="../../asset/img/baldo.jpg" alt="User profile picture" width="140"
                                            height="140">
                                    </div>
                                </div>
                                <h3 class="profile-username text-center"><?php echo $nama_karyawan; ?></h3>
                                <p class="text-muted text-center"><?php echo $cwoc; ?></p>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                        <div class="card card-dark card-outline mt-3">
                            <div class="card-header">
                                <h3 class="card-title">Histori Mutasi</h3>
                                <div class="card-tools">
                                    <!-- Optional tools can go here -->
                                </div>
                            </div>
                            <div class="card-body">
                                <?php
                                // Check if historical records exist
                                $historicalRecordsExist = false;
                                do {
                                    $historycwoc = $dataKaryawan['histori_cwoc'];
                                    $sectDesc = $dataKaryawan['histori_sect'];
                                    if ($dataKaryawan['tanggal_selanjutnya'] !== null) {
                                        $tanggal_selanjutnya = date('d F Y', strtotime($dataKaryawan['tanggal_selanjutnya']));
                                    } else {
                                        $tanggal_selanjutnya = "Now";
                                    }
                                    $tanggal = date('d F Y', strtotime($dataKaryawan['tanggal']));
                                    if ($historycwoc !== null) {
                                        $historicalRecordsExist = true;
                                        echo "<label for='inputText' class='col-sm-12 col-form-label'>&nbsp; &nbsp;$historycwoc - $sectDesc</label>";
                                        echo "<label for='inputText' class='col-sm-12 col-form-label' style='margin-top: -15px;'>&nbsp; &nbsp;($tanggal - $tanggal_selanjutnya)</label>";
                                    }
                                } while ($dataKaryawan = mysqli_fetch_assoc($result));
                                // Display message if no historical records exist
                                if (!$historicalRecordsExist) {
                                    echo "<label for='inputText' class='col-sm-12 col-form-label'>&nbsp; &nbsp; Tidak ada histori mutasi</label>";
                                }
                                ?>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>

                    <div class="col-md-9">
                        <!-- Konten yang ingin Anda tambahkan di sini -->
                        <div class="card card-dark card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Profil <?php echo $nama_karyawan; ?></h3>
                                <div class="card-tools">
                                    <div class="input-group input-group-sm" style="width: 150px;">
                                        <!-- Tambahkan elemen input di sini jika diperlukan -->
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="row mb-3">
                                    <label for="inputText" class="col-sm-4 col-form-label">NPK</label>
                                    <div class="col-sm-8">
                                        <label for="inputText" class="col-sm-8 col-form-label">:&nbsp;
                                            &nbsp;<?php echo $emno; ?></label>
                                    </div>
                                </div>
                                <!-- Tambahkan baris tambahan sesuai kebutuhan -->
                                <div class="row mb-3">
                                    <label for="inputText" class="col-sm-4 col-form-label">Nama</label>
                                    <div class="col-sm-8">
                                        <label for="inputText" class="col-sm-8 col-form-label">:&nbsp;
                                            &nbsp;<?php echo $nama_karyawan; ?></label>
                                    </div>
                                </div>
                                <!-- Tambahkan baris tambahan sesuai kebutuhan -->
                                <div class="row mb-3">
                                    <label for="inputText" class="col-sm-4 col-form-label">Jenis Kelamin</label>
                                    <div class="col-sm-8">
                                        <label for="inputText" class="col-sm-8 col-form-label">:&nbsp;
                                            &nbsp;<?php echo $sexe; ?></label>
                                    </div>
                                </div>
                                <!-- Tambahkan baris tambahan sesuai kebutuhan -->
                                <div class="row mb-3">
                                    <label for="inputText" class="col-sm-4 col-form-label">Golongan</label>
                                    <div class="col-sm-8">
                                        <label for="inputText" class="col-sm-8 col-form-label">:&nbsp;
                                            &nbsp;<?php echo $gol; ?></label>
                                    </div>
                                </div>
                                <!-- Tambahkan baris tambahan sesuai kebutuhan -->
                                <div class="row mb-3">
                                    <label for="inputText" class="col-sm-4 col-form-label">Status</label>
                                    <div class="col-sm-8">
                                        <label for="inputText" class="col-sm-8 col-form-label">:&nbsp;
                                            &nbsp;<?php echo $status; ?></label>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputText" class="col-sm-4 col-form-label">Posisi</label>
                                    <div class="col-sm-8">
                                        <label for="inputText" class="col-sm-8 col-form-label">:&nbsp;
                                            &nbsp;<?php echo $posisi; ?></label>
                                    </div>
                                </div>
                                <!-- Tambahkan baris tambahan sesuai kebutuhan -->
                                <div class="row mb-3">
                                    <label for="inputText" class="col-sm-4 col-form-label">Tanggal Lahir</label>
                                    <div class="col-sm-8">
                                        <label for="inputText" class="col-sm-8 col-form-label">:&nbsp;
                                            &nbsp;<?php echo $birthday; ?></label>
                                    </div>
                                </div>
                                <!-- Tambahkan baris tambahan sesuai kebutuhan -->
                                <div class="row mb-3">
                                    <label for="inputText" class="col-sm-4 col-form-label">Usia</label>
                                    <div class="col-sm-8">
                                        <label for="inputText" class="col-sm-8 col-form-label">:&nbsp;
                                            &nbsp;<?php echo $usia; ?> Tahun</label>
                                    </div>
                                </div>
                                <!-- Tambahkan baris tambahan sesuai kebutuhan -->
                                <div class="row mb-3">
                                    <label for="inputText" class="col-sm-4 col-form-label">Tanggal Masuk</label>
                                    <div class="col-sm-8">
                                        <label for="inputText" class="col-sm-8 col-form-label">:&nbsp;
                                            &nbsp;<?php echo $joindate; ?></label>
                                    </div>
                                </div>
                                <!-- Tambahkan baris tambahan sesuai kebutuhan -->
                                <div class="row mb-3">
                                    <label for="inputText" class="col-sm-4 col-form-label">Masa Kerja</label>
                                    <div class="col-sm-8">
                                        <label for="inputText" class="col-sm-8 col-form-label">:&nbsp;
                                            &nbsp;<?php echo $masa_kerja; ?> Tahun</label>
                                    </div>
                                </div>
                                <!-- Tambahkan baris tambahan sesuai kebutuhan -->
                                <div class="row mb-3">
                                    <label for="inputText" class="col-sm-4 col-form-label">Pendidikan</label>
                                    <div class="col-sm-8">
                                        <label for="inputText" class="col-sm-8 col-form-label">:&nbsp;
                                            &nbsp;<?php echo $pendidikan; ?></label>
                                    </div>
                                </div>
                                <!-- Tambahkan baris tambahan sesuai kebutuhan -->
                                <div class="row mb-3">
                                    <label for="inputText" class="col-sm-4 col-form-label">Departemen</label>
                                    <div class="col-sm-8">
                                        <label for="inputText" class="col-sm-8 col-form-label">:&nbsp;
                                            &nbsp;<?php echo $cwoc; ?></label>
                                    </div>
                                </div>
                                <!-- Tambahkan baris tambahan sesuai kebutuhan -->
                                <div class="row mb-3">
                                    <label for="inputText" class="col-sm-4 col-form-label">Section</label>
                                    <div class="col-sm-8">
                                        <label for="inputText" class="col-sm-8 col-form-label">:&nbsp;
                                            &nbsp;<?php echo $desc1; ?></label>
                                    </div>
                                </div>
                                <!-- Tambahkan baris tambahan sesuai kebutuhan -->
                                <div class="row mb-3">
                                    <label for="inputText" class="col-sm-4 col-form-label">Sub Section</label>
                                    <div class="col-sm-8">
                                        <label for="inputText" class="col-sm-8 col-form-label">:&nbsp;
                                            &nbsp;<?php echo $desc2; ?></label>
                                    </div>
                                </div>
                                <!-- Tambahkan baris tambahan sesuai kebutuhan -->
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
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

    <!-- Page specific script -->

    <!-- Skrip JavaScript yang Anda tambahkan -->
    <script src="../../asset/js/search.js"></script>
    <script src="../../asset/js/day.js"></script>

</body>

</html>
<style>
    .p-picture-container {
        height: 100%;
    }

    .p-picture-container img {
        border-radius: 16px;
    }
</style>