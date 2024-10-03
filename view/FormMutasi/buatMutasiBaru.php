<?php
include __DIR__ . '/../../query/koneksi.php';
include __DIR__ . '/../../query/function.php';


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
                                    <div class="col-sm-3">
                                        <label for="cwocAsal" class="col-form-label">Departemen Asal</label>
                                        <select class="form-control selectpicker" id="cwocAsal" name="cwocAsal"
                                            data-live-search="true" disabled>
                                            <option value="" disabled selected hidden>Pilih Departemen Asal</option>
                                            <?php
                                            $query = mysqli_query($koneksi2, "SELECT DISTINCT dept FROM ct_users");
                                            while ($row = mysqli_fetch_array($query)) {
                                                $selected = ($row['dept'] == $user_department) ? 'selected' : '';
                                                echo "<option value='" . htmlspecialchars($row['dept'], ENT_QUOTES, 'UTF-8') . "' $selected>" . htmlspecialchars($row['dept'], ENT_QUOTES, 'UTF-8') . "</option>";
                                            }
                                            ?>
                                        </select>
                                        <input type="hidden" name="cwocAsal"
                                            value="<?php echo htmlspecialchars($user_department, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>

                                    <div class="col-sm-3">
                                        <label for="sectAsal" class="col-form-label">Seksi Asal</label>
                                        <select class="form-control selectpicker" id="sectAsal" name="sectAsal"
                                            data-live-search="true">
                                            <option value="" disabled selected hidden>Pilih Seksi Asal</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-3">
                                        <label for="sectAsal" class="col-form-label">Subseksi Asal</label>
                                        <select class="form-control selectpicker" id="subsectAsal" name="subsectAsal"
                                            data-live-search="true" data-placeholder="Select NPK">
                                            <option value="" disabled hidden>Pilh Subskesi Asal</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-3">
                                        <label for="golongan" class="col-form-label">Golongan</label>
                                        <select class="form-control selectpicker" id="golongan" name="golongan"
                                            data-live-search="true" disabled>
                                            <option value="" disabled selected hidden>Pilih Golongan</option>
                                            <option value="0">Golongan 0</option>
                                            <option value="1">Golongan 1</option>
                                            <option value="2">Golongan 2</option>
                                            <option value="3">Golongan 3</option>
                                            <option value="4_acting_2">Golongan 4</option>
                                        </select>
                                    </div>

                                </div>


                                <div class="row mb-3">
                                    <div class="col-sm-12">
                                        <label for="emno" class="col-form-label">Karyawan</label>
                                        <select class="form-control select2" id="emno" name="emno[]" multiple="multiple"
                                            data-placeholder="Pilih Karyawan">
                                            <!-- Placeholder tanpa opsi terpilih -->
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        <label for="emno" class="col-form-label">Keperluan</label>
                                        <input type="text" class="form-control" disabled value="Mutasi">
                                    </div>

                                    <div class="col-sm-6">
                                        <label for="npk2" class="col-form-label">Tanggal Berlaku Mutasi</label>
                                        <input type="text" class="form-control" id="tanggalMutasi" name="tanggalMutasi"
                                            autocomplete="off">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <label for="cwocBaru" class="col-form-label">Departemen Baru</label>
                                        <select class="form-control selectpicker" id="cwocBaru" name="cwocBaru"
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

                                    <div class="col-sm-4">
                                        <label for="sectAsal" class="col-form-label">Seksi Baru</label>
                                        <select class="form-control selectpicker" id="sectBaru" name="sectBaru"
                                            data-live-search="true">
                                            <option value="" disabled selected hidden>Pilih Seksi Baru</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="sectAsal" class="col-form-label">Subseksi Asal</label>
                                        <select class="form-control selectpicker" id="subsectBaru" name="subsectBaru"
                                            data-live-search="true">
                                            <option value="" disabled selected hidden>Pilih Subseksi Asal</option>
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


    <script>
    $(document).ready(function() {
        // Initialize Select2 Elements
        $('.select2').select2();

        // Initialize Select2 Elements with Bootstrap 4 theme
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        });


        // Ketika departemen asal berubah
        $('#cwocAsal').on('change', function() {
            var cwoc = $(this).val();
            if (cwoc) {
                $.ajax({
                    type: 'POST',
                    url: 'buatMutasiBaru.php', // Pastikan path ini benar
                    data: {
                        cwoc: cwoc
                    },
                    success: function(html) {
                        $('#sectAsal').html(html);
                        $('#sectAsal').prop('disabled', false); // Enable select
                        $('#sectAsal').selectpicker('refresh'); // Refresh selectpicker UI

                        $('#subsectAsal').html(
                            '<option value="" disabled selected hidden>Pilih Subseksi</option>'
                        ).prop('disabled', true).selectpicker('refresh');

                        $('#emno').prop('disabled', true); // Enable select
                        $('#emno').selectpicker('refresh'); // Refresh selectpicker UI

                        $('#nama').val('').prop('disabled', true);

                        // Refresh Select2 elements
                        $('.select2').val('').trigger('change');
                    }
                });
            } else {
                $('#sectAsal').html(
                    '<option value="" disabled selected hidden>Pilih Seksi Asal</option>');
                $('#sectAsal').prop('disabled', true); // Disable select
                $('#sectAsal').selectpicker('refresh'); // Refresh selectpicker UI
            }
        });

        // Ketika section asal berubah
        $('#sectAsal').on('change', function() {
            var id_sect = $(this).val();
            if (id_sect) {
                $.ajax({
                    type: 'POST',
                    url: 'buatMutasiBaru.php', // Pastikan path ini benar
                    data: {
                        id_sect: id_sect
                    },
                    success: function(html) {
                        $('#subsectAsal').html(html);
                        $('#subsectAsal').prop('disabled', false); // Enable select
                        $('#subsectAsal').selectpicker(
                            'refresh'); // Refresh selectpicker UI

                        $('#emno').html(
                                '<option value="" disabled selected hidden>Pilih NPK</option>'
                            ).prop('disabled', true)
                            .selectpicker('refresh');
                        $('#golongan').html(
                            '<option value="" disabled selected hidden>Pilih Golongan</option>' +
                            '<option value="0">Golongan 0</option>' +
                            '<option value="1">Golongan 1</option>' +
                            '<option value="2">Golongan 2</option>' +
                            '<option value="3">Golongan 3</option>' +
                            '<option value="4_acting_2">Golongan 4</option>'
                        ).prop('disabled', true).selectpicker(
                            'refresh'); // Refresh selectpicker UI

                    }
                });
            } else {
                $('#subsectAsal').html(
                    '<option value="" disabled selected hidden>Pilih Karyawan</option>');
                $('#subsectAsal').prop('disabled', true); // Disable select
                $('#subsectAsal').selectpicker('refresh'); // Refresh selectpicker UI
            }
        });

        var id_sect = $('#sectAsal').val();
        if (!id_sect) {
            // Jika belum terpilih, disable subsect dan atur opsi default
            $('#subsectAsal').html('<option value="" disabled selected hidden>Pilih Subseksi</option>');
            $('#subsectAsal').prop('disabled', true); // Disable select
            $('#subsectAsal').selectpicker('refresh'); // Refresh selectpicker UI

        }

        // Ketika subsectAsal berubah
        // Cek apakah sect asal sudah terpilih saat halaman dimuat
        var id_sect = $('#sectAsal').val();
        if (!id_sect) {
            // Jika belum terpilih, disable subsect dan atur opsi default
            $('#subsectAsal').html('<option value="" disabled selected hidden>Pilih Subseksi</option>');
            $('#subsectAsal').prop('disabled', true); // Disable select
            $('#subsectAsal').selectpicker('refresh'); // Refresh selectpicker UI
        }

        // Ketika subsectAsal berubah
        $('#subsectAsal').on('change', function() {
            var id_subsect = $(this).val();

            if (id_subsect) {
                $.ajax({
                    type: 'POST',
                    url: 'buatMutasiBaru.php',
                    data: {
                        id_subsect: id_subsect
                    },
                    success: function(html) {
                        $('#emno').html(
                                '<option value="" disabled selected hidden>Pilih NPK</option>'
                            ).prop('disabled', true)
                            .selectpicker('refresh');

                        // Determine the role from PHP before this script runs
                        var role = '<?= $_SESSION['role'] ?>'; // Get the role from PHP
                        // Inside the success function when setting golongan options
                        var golonganOptions =
                            '<option value="" disabled selected hidden>Pilih Golongan</option>';

                        if (role === 'Foreman' || role === 'Foreman HRD') {
                            golonganOptions += '<option value="0">Golongan 0</option>';
                            golonganOptions += '<option value="1">Golongan 1</option>';
                            golonganOptions += '<option value="2">Golongan 2</option>';
                        } else if (role === 'Supervisor' || role === 'Supervisor HRD') {
                            golonganOptions += '<option value="0">Golongan 0</option>';
                            golonganOptions += '<option value="1">Golongan 1</option>';
                            golonganOptions += '<option value="2">Golongan 2</option>';
                            golonganOptions += '<option value="3">Golongan 3</option>';
                        } else if (role === 'Kepala Departemen' || role ===
                            'Kepala Departemen HRD') {
                            golonganOptions += '<option value="0">Golongan 0</option>';
                            golonganOptions += '<option value="1">Golongan 1</option>';
                            golonganOptions += '<option value="2">Golongan 2</option>';
                            golonganOptions += '<option value="3">Golongan 3</option>';
                            golonganOptions +=
                                '<option value="4_acting_2">Golongan 4</option>';
                        }

                        $('#golongan').html(golonganOptions) // Set the options for golongan
                            .prop('disabled', false) // Enable golongan select
                            .selectpicker('refresh'); // Refresh selectpicker UI

                    }
                });
            } else {
                $('#golongan').prop('disabled', true); // Disable Golongan select
                $('#golongan').selectpicker('refresh'); // Refresh selectpicker UI
            }
        });


        $('#golongan').on('change', function() {
            var id_subsect = $('#subsectAsal').val();
            var selected_golongan = $(this).val();

            if (id_subsect && selected_golongan) {
                $.ajax({
                    type: 'POST',
                    url: 'buatMutasiBaru.php',
                    data: {
                        id_subsect: id_subsect,
                        selected_golongan: selected_golongan
                    },
                    success: function(html) {
                        $('#emno').html(html);
                        $('#emno').prop('disabled', false); // Enable select for Karyawan
                        $('#emno').selectpicker('refresh'); // Refresh selectpicker UI

                        // Keep golongan enabled until emno is selected
                        // No need to disable golongan here
                    }
                });
            } else {
                $('#emno').html('<option value="" disabled hidden>Pilih Karyawan</option>');
                $('#emno').prop('disabled', true); // Disable Karyawan select
                $('#emno').selectpicker('refresh'); // Refresh selectpicker UI
            }
        });

        // Disable golongan when an employee is selected
        $('#emno').on('change', function() {
            // Only disable golongan after selecting emno
            $('#golongan').prop('disabled', true).selectpicker(
            'refresh'); // Disable golongan when an employee is selected
        });



        $('#cwocBaru').on('change', function() {
            var cwoc = $(this).val();
            if (cwoc) {
                $.ajax({
                    type: 'POST',
                    url: 'buatMutasiBaru.php', // Pastikan path ini benar
                    data: {
                        cwoc: cwoc
                    },
                    success: function(html) {
                        $('#sectBaru').html(html);
                        $('#sectBaru').prop('disabled', false); // Enable select
                        $('#sectBaru').selectpicker('refresh'); // Refresh selectpicker UI

                        $('#subsectBaru').html(
                            '<option value="" disabled selected hidden>Pilih Subseksi</option>'
                        ).prop('disabled', true).selectpicker('refresh');
                    }
                });
            } else {
                $('#sectBaru').html(
                    '<option value="" disabled selected hidden>Pilih Seksi</option>');
                $('#sectBaru').prop('disabled', true); // Disable select
                $('#sectBaru').selectpicker('refresh'); // Refresh selectpicker UI

                $('#emno').html('<option value="" disabled hidden>Pilih Karyawan</option>');
                $('#emno').prop('disabled', true); // Disable select
                $('#emno').selectpicker('refresh'); // Refresh selectpicker UI

            }
        });

        $('#sectBaru').on('change', function() {
            var id_sect = $(this).val();
            if (id_sect) {
                $.ajax({
                    type: 'POST',
                    url: 'buatMutasiBaru.php', // Pastikan path ini benar
                    data: {
                        id_sect: id_sect
                    },
                    success: function(html) {
                        $('#subsectBaru').html(html);
                        $('#subsectBaru').prop('disabled', false); // Enable select
                        $('#subsectBaru').selectpicker(
                            'refresh'); // Refresh selectpicker UI
                    }
                });
            } else {
                $('#subsectBaru').html(
                    '<option value="" disabled selected hidden>Pilih Subseksi</option>');
                $('#subsectBaru').prop('disabled', true); // Disable select
                $('#subsectBaru').selectpicker('refresh'); // Refresh selectpicker UI

            }
        });

        // Memastikan bahwa saat halaman dimuat ulang, status dropdown diatur sesuai keadaan awal
        // Cek apakah sect asal sudah terpilih saat halaman dimuat
        var id_sect = $('#sectAsal').val();
        if (!id_sect) {
            // Jika belum terpilih, disable subsect dan atur opsi default
            $('#subsectBaru').html('<option value="" disabled selected hidden>Pilih Subseksi</option>');
            $('#subsectBaru').prop('disabled', true); // Disable select
            $('#subsectBaru').selectpicker('refresh'); // Refresh selectpicker UI
        }

        // Ketika npk 1 berubah
        $('#emno').on('change', function() {
            var emno = $(this).val();
            if (emno) {
                $.ajax({
                    type: 'POST',
                    url: 'buatMutasiBaru.php', // Pastikan path ini benar
                    data: {
                        emno: emno
                    },
                    success: function(response) {
                        $('#nama').val(response);
                        $('#emno option[value=""]').remove(); // Remove "Select NPK" option
                        $('#emno').selectpicker('refresh'); // Refresh selectpicker UI
                    }
                });
            }
        });

        $('#cwocAsal').trigger('change');
        $('#cwocBaru').trigger('change');
    });

    function resetForm() {
        document.getElementById("formMutasi").reset();

        $('#sectAsal').prop('disabled', false)
            .selectpicker('refresh');
        $('#subsectAsal').html('<option value="" disabled selected hidden>Pilih Subseksi</option>').prop('disabled',
            true).selectpicker('refresh');
        $('#emno').html('<option value="" disabled selected hidden>Pilih NPK</option>').prop('disabled', true)
            .selectpicker('refresh');
        $('#cwocBaru').val('').trigger('change');
        $('#sectBaru').html('<option value="" disabled selected hidden>Pilih Seksi</option>').prop('disabled', true)
            .selectpicker('refresh');
        $('#subsectBaru').html('<option value="" disabled selected hidden>Pilih Subseksi</option>').prop('disabled',
            true).selectpicker('refresh');
        $('#golongan').html('<option value="" disabled selected hidden>Pilih Golongan</option>').prop('disabled',
            true).selectpicker('refresh');
        $('.select2').val('').trigger('change');
    }

    $('#formMutasi').submit(function(event) {
        event.preventDefault();
        var form = $(this);

        var valid = true;
        var errorMsg = "<strong>Perhatian!</strong> Terdapat kesalahan dalam pengisian form:<br><ul>";

        if (!$('#cwocAsal').val()) {
            valid = false;
            errorMsg += "<li>Departemen Asal harus dipilih.</li>";
        }
        if (!$('#sectAsal').val()) {
            valid = false;
            errorMsg += "<li>Section Asal harus dipilih.</li>";
        }
        if (!$('#subsectAsal').val()) {
            valid = false;
            errorMsg += "<li>Sub Section Asal harus dipilih.</li>";
        }
        if (!$('#emno').val()) {
            valid = false;
            errorMsg += "<li>NPK harus dipilih.</li>";
        }
        if (!$('#cwocBaru').val()) {
            valid = false;
            errorMsg += "<li>Departemen Baru harus dipilih.</li>";
        }
        if (!$('#tanggalMutasi').val()) {
            valid = false;
            errorMsg += "<li>Tanggal Mutasi harus diisi.</li>";
        }

        errorMsg += "</ul>";

        if (!valid) {
            // Jika ada kesalahan, tampilkan pesan error dengan SweetAlert2
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: errorMsg
            });
        } else {
            // Jika validasi sukses, lakukan pengajuan mutasi via AJAX
            var departemenAsal = $('#cwocAsal option:selected').text();
            var departemenBaru = $('#cwocBaru option:selected').text();
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: `Apakah anda yakin untuk mengajukan mutasi dari departemen ${departemenAsal} ke Departemen ${departemenBaru}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Ajukan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: form.attr('method'),
                        url: form.attr('action'),
                        data: form.serialize(),
                        success: function(response) {
                            Swal.fire({
                                position: "center",
                                icon: "success",
                                title: "Berhasil melakukan pengajuan mutasi",
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                window.location.href = "Dashboard.php";
                            });
                        }
                    });
                }
            });

        }
    });

    $(function() {
        $("#tanggalMutasi").datepicker({
            changeMonth: true,
            changeYear: true,
            minDate: 0 // Hanya tanggal hari ini atau setelahnya yang dapat dipilih
        });
    });
    </script>

    <!-- Bootstrap Select JS -->
    <script src="../../asset/select/select.min.js"></script>

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