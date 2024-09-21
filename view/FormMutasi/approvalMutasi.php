<?php
include __DIR__ . '/../../query/koneksi.php';
include __DIR__ . '/../../query/approve.php';
include __DIR__ . '/../../query/detail.php';


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Permohonan Mutasi | Approval Mutasi</title>
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
                        <h1 class="m-0">Persetujuan Mutasi</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="dashboard.php">Beranda</a></li>
                            <li class="breadcrumb-item active">Persetujuan Mutasi</li>
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
                                } else {
                                    echo 'Daftar Mutasi Departemen ' . $_SESSION['dept'];
                                }

                                ?>
                            </h3>

                            <div class="card-tools">
                                <div class="input-group input-group-sm" style="width: 150px;">
                                    <input type="text" name="table_search" class="form-control float-right"
                                        placeholder="Search">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
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
                                <tbody>
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
                                                $statusMessage = 'Finish';
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
            <button type='button' class='btn btn-success btn-sm btn-detail' data-toggle='modal' data-target='#detailModal' data-id='" . htmlspecialchars($row['batchMutasi']) . "'>
                <i class='fas fa-check-circle' style='color: white;'></i>
            </button>
        </td>";

                                        echo "</tr>";
                                    }
                                    if (mysqli_num_rows($resultMutasi) == 0) {
                                        echo "<tr><td colspan='10'>Result Not Found</td></tr>";
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

            <div class="modal fade custom-modal" id="detailModal" tabindex="-1" role="dialog"
                aria-labelledby="detailModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered custom-width" role="document">
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
                                        <th>Aksi</th>
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

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="check-all">
                                <label class="form-check-label" for="check-all">Pilih semua</label>
                                <br>
                                <label>Apakah anda yakin meng approve pengajuan mutasi ini?</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="approveButton">Setujui</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                        </div>
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

    <!-- Skrip JavaScript yang Anda tambahkan -->
    <script src="../../asset/js/search.js"></script>
    <script src="../../asset/js/day.js"></script>
    <script src="../../asset/sweetalert2/sweetalert2.all.min.js"></script>

    <script>
        // Function to get status message based on status code
        function getStatusMessage(statusCode) {
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
                    return 'Unknown';
            }
        }

        // Function to format date in 'DD MMM YY' format
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




        // Event handler for detail button click
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

                    if (data.error) {
                        console.error('Data Error:', data.error);
                        tbody.append('<tr><td colspan="5">Error: ' + data.error + '</td></tr>');
                    } else if (data.IdMutasi.length > 0) {
                        $.each(data.IdMutasi, function (index, IdMutasi) {
                            var statusCode = data.status[index];
                            var status = getStatusMessage(statusCode);
                            var nama = data.nama[index];
                            var emno = data.emnos[index];
                            var tanggalBuat = formatDate(data.tanggalBuat[index]);
                            var tanggalMutasi = formatDate(data.tanggalMutasi[index]);

                            var row = '<tr><td>' + (index + 1) + '</td>' +
                                '<td>' + emno + '</td>' +
                                '<td>' + nama + '</td>' +
                                '<td>' + tanggalBuat + '</td>' +
                                '<td>' + tanggalMutasi + '</td>' +
                                '<td><input type="checkbox" class="select-checkbox" data-id="' +
                                IdMutasi + '"></td></tr>';
                            tbody.append(row);
                        });
                    } else {
                        tbody.append('<tr><td colspan="7">No data available</td></tr>');
                    }

                    // Update the second table
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


                    $('#detailModal .modal-title').text('Mutasi dari dept ' + data.cwocAsal + ' ke ' +
                        data.cwocBaru);
                    $('#detailModal').modal('show');
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    console.error('Response Text:', xhr.responseText);

                    var errorMessage = 'Error retrieving data.';
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.error) {
                            errorMessage = response.error;
                        }
                    } catch (e) {
                        // If JSON parsing fails, fall back to a generic error message
                        errorMessage = 'Error retrieving data.';
                    }

                    Swal.fire('Error!', errorMessage, 'error');
                }
            });
        });

        // Event handler for checking/unchecking all checkboxes
        $(document).on('click', '#check-all', function () {
            var isChecked = $(this).is(':checked');
            $('.select-checkbox').prop('checked', isChecked);
        });

        $(document).on('click', '#approveButton', function () {
            var batchMutasi = $('.btn-detail').data('id');
            var selectedIdMutasi = [];

            $('.select-checkbox:checked').each(function () {
                selectedIdMutasi.push($(this).data('id'));
            });

            if (selectedIdMutasi.length === 0) {
                Swal.fire('Tidak Ada Yang Dipilih', 'Tidak ada karyawan yang dipilih.', 'info');
                return;
            }

            Swal.fire({
                title: 'Apakah Anda yakin ingin menyetujui mutasi ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Setujui!',
                cancelButtonText: 'Tidak, batalkan!',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../../query/approve.php',
                        type: 'POST',
                        data: {
                            batchMutasi: batchMutasi,
                            IdMutasi: selectedIdMutasi,
                        },
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    position: 'center',
                                    icon: 'success',
                                    title: 'Mutation Approved',
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then(() => {
                                    window.location.href =
                                        'approvalMutasi.php'; // Redirect after success
                                });
                            } else {
                                Swal.fire('Error!', 'Error: ' + response.error, 'error');
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('AJAX Error:', status, error);
                            console.error('Response Text:', xhr.responseText);

                            var contentType = xhr.getResponseHeader('Content-Type');
                            var errorMessage = 'Error updating records.';

                            // If response is JSON, try to extract the error message
                            if (contentType && contentType.indexOf('application/json') !== -1) {
                                try {
                                    var response = JSON.parse(xhr.responseText);
                                    if (response.error) {
                                        errorMessage = response.error;
                                    }
                                } catch (e) {
                                    errorMessage = 'Error parsing JSON response.';
                                }
                            } else {
                                errorMessage = 'Server responded with an unexpected format.';
                            }

                            Swal.fire('Error!', errorMessage, 'error');
                        }
                    });
                }
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
    }
</style>