<?php
include __DIR__ . '/../../query/koneksi.php';


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Permohonan Mutasi | Pengajuan Mutasi</title>
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
    <!-- Bootstrap Select -->
    <link rel="stylesheet" href="../../asset/select/select.min.css">
    <link rel="stylesheet" href="../../asset/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../../asset/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- Bootstrap4 Duallistbox -->
    <link rel="stylesheet" href="../../asset/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
    <link rel="stylesheet" href="../../asset/plugins/bs-stepper/css/bs-stepper.min.css">
    <!-- dropzonejs -->
    <link rel="stylesheet" href="../../asset/plugins/dropzone/min/dropzone.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../../asset/dist/css/adminlte.min.css">

    <link href="../../asset/jquery-ui/jquery-ui.css" rel="stylesheet">
    <!-- Custom Style -->
    <link rel="stylesheet" href="../../style.css">
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

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <?php include '../../layout/sidebar.php'; ?>
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Pengajuan Mutasi Karyawan</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">Pengajuan Mutasi Karyawan</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="col-md-12">
                    <div class="card card-dark card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Form Pengajuan Mutasi Karyawan</h3>
                        </div>
                        <div class="card-body">
                            <form id="formMutasi" action="../../query/submitMutasi.php" method="POST">

                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <label for="cwocBaru" class="col-form-label">Departemen Baru</label>
                                        <select class="form-control select2" id="cwocBaru" name="cwocBaru"
                                            data-live-search="true">
                                            <option value="" disabled selected hidden>Pilih Departemen Baru</option>
                                            <?php
                                            $query = mysqli_query($koneksi2, "SELECT DISTINCT dept FROM ct_users WHERE dept <> 'BOD'");
                                            while ($row = mysqli_fetch_array($query)) {
                                                echo "<option value='" . htmlspecialchars($row['dept'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row['dept'], ENT_QUOTES, 'UTF-8') . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>


                                <div class="text-right mb-3">
                                    <button type="submit" class="btn btn-primary custom-button mr-2">Ajukan</button>
                                    <button type="button" class="btn btn-danger custom-button"
                                        onclick="resetForm()">Reset</button>
                                </div>
                            </form>


                        </div>
                    </div>
            </section>
        </div>

        <footer class="main-footer">
            <?php include '../../layout/footer.php'; ?>
        </footer>

        <aside class="control-sidebar control-sidebar-dark"></aside>
    </div>

    <!-- jQuery -->
    <script src="../../asset/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../../asset/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../../asset/dist/js/adminlte.js"></script>
    <script src="../../asset/jquery-ui/jquery-ui.js"></script>
    <!-- Select2 -->
    <script src="../../asset/plugins/select2/js/select2.full.min.js"></script>
    <!-- Bootstrap4 Duallistbox -->
    <script src="../../asset/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
    <!-- InputMask -->
    <script src="../../asset/plugins/moment/moment.min.js"></script>
    <script src="../../asset/sweetalert2/sweetalert2.all.min.js"></script>

    <script src="../../asset/JS/search.js"></script>
    <script src="../../asset/JS/day.js"></script>




    <!-- Bootstrap Select JS -->
    <script src="../../asset/select/select.min.js"></script>

    <script>
    $(document).ready(function() {
        // Initialize Select2 Elements
        $('.select2').select2();

        // Initialize Select2 Elements with Bootstrap 4 theme
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        });
    });
    </script>

</body>

</html>
<style>
.btn-primary.custom-button {
    color: white;
}

.btn-primary.custom-button:hover {
    background-color: white;
    color: #007bff;
    /* Bootstrap primary color */
}

.btn-danger.custom-button {
    color: white;
}

.btn-danger.custom-button:hover {
    background-color: white;
    color: #dc3545;
    /* Bootstrap danger color */
}
</style>