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
                                        <th rowspan="2" style="vertical-align: middle;">Tanggal Berlaku Mutasi</th>
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
                                                $statusMessage = 'Menunggu Direktur Asal';
                                                $badgeColor = 'badge-warning';
                                                break;
                                            case 9:
                                                $statusMessage = 'Menunggu HRD Asal';
                                                $badgeColor = 'badge-warning';
                                                break;
                                            case 10:
                                                $statusMessage = 'Menunggu HRD';
                                                $badgeColor = 'badge-warning';
                                                break;
                                            case 11:
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
                                        <th>Tanggal Berlaku</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="modal-body-content">
                                    <!-- Data will be inserted here by JavaScript -->
                                </tbody>
                            </table>

                            <!-- Add Feedback Section -->
                            <div class="mb-3">
                                <span id="addFeedback" style="color: blue; cursor: pointer;">Tambah feedback</span>
                                <textarea id="feedbackInput" class="form-control mt-2" rows="3" style="display: none;"
                                    placeholder="Masukkan feedback..."></textarea>
                            </div>

                            <!-- Toggle Button for Approval History -->
                            <div>
                                <button id="toggleApprovalHistoryButton" class="btn btn-link"
                                    style="padding: 0; color: blue; margin-top: -25px;">Lihat riwayat Approval
                                    Mutasi</button>
                            </div>

                            <!-- Approval History Section - initially hidden -->
                            <div id="approvalHistorySection" style="display: none;">
                                <h6 class="mb-3 font-weight-bold">Riwayat Status Batch Mutasi</h6>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Status</th>
                                            <th>Disetujui Oleh</th>
                                            <th>Tanggal Disetujui</th>
                                        </tr>
                                    </thead>
                                    <tbody id="second-table-body">
                                        <!-- Additional data for the approval history table will be inserted here -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- Toggle Button for Remarks -->
                            <div>
                                <button id="toggleRemarksButton" class="btn btn-link"
                                    style="padding: 0; color: blue; margin-top: -15px;">Lihat catatan</button>
                            </div>

                            <!-- Remarks Section - initially hidden -->
                            <div id="remarksSection" style="display: none; max-height: 200px; overflow-y: auto;">
                                <h6 class="mb-3 font-weight-bold">Remarks dan Feedbacks</h6>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Oleh</th>
                                            <th>Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody id="remarks-table-body">
                                        <!-- Remarks data will be populated here -->
                                    </tbody>
                                </table>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="check-all">
                                <label class="form-check-label" for="check-all">Pilih semua</label>
                                <br>
                                <label>Apakah anda yakin menyetujui pengajuan mutasi ini?</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="approveButton">Setujui</button>
                            <button type="button" class="btn btn-danger" id="remarkButton"
                                style="display: none;">Remark</button>

                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="remarkModal" tabindex="-1" role="dialog" aria-labelledby="remarkModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="remarkModalLabel">Tambahkan Remark untuk batch <span
                                    id="batchNumberRemark"></span></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="remarkTextArea">Remark:</label>
                                <textarea class="form-control" id="remarkTextArea" rows="4"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="saveRemarkButton">Simpan
                                Remark</button>
                            <button type="button" class="btn btn-danger" id="resetButton">Reset</button>
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
    <script src="../../asset/JS/search.js"></script>
    <script src="../../asset/JS/day.js"></script>
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

    function formatDate2(dateString) {
        const date = new Date(dateString);
        const day = date.getDate().toString().padStart(2, '0');
        const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        const month = monthNames[date.getMonth()];
        const year = date.getFullYear().toString().slice(-2); // Last 2 digits of the year

        return `${day} ${month} ${year}`; // Same as formatDate now
    }

    // Event handler for detail button click
    $(document).on('click', '.btn-detail', function() {
        var batchMutasi = $(this).data('id');
        var currentRole = '<?php echo $_SESSION['role']; ?>'; // Get the current role from PHP
        console.log('Batch Mutasi:', batchMutasi);
        console.log('Current Role:', currentRole); // Debugging output

        $.ajax({
            url: '../../query/detail.php',
            type: 'GET',
            data: {
                batchMutasi: batchMutasi
            },
            dataType: 'json',
            success: function(data) {
                console.log('AJAX Success:', data);

                var tbody = $('#modal-body-content');
                tbody.empty();

                if (data.error) {
                    console.error('Data Error:', data.error);
                    tbody.append('<tr><td colspan="5">Error: ' + data.error + '</td></tr>');
                } else if (data.IdMutasi.length > 0) {
                    $.each(data.IdMutasi, function(index, IdMutasi) {
                        var statusCode = data.status[index];
                        var status = getStatusMessage(statusCode);
                        var nama = data.nama[index];
                        var emno = data.emnos[index];
                        var tanggalBuat = formatDate(data.tanggalBuat[index]);
                        var tanggalMutasi = formatDate2(data.tanggalMutasi[index]);

                        var row = '<tr><td>' + (index + 1) + '</td>' +
                            '<td>' + emno + '</td>' +
                            '<td>' + nama + '</td>' +
                            '<td>' + tanggalBuat + '</td>' +
                            '<td>' + tanggalMutasi + '</td>' +
                            '<td><input type="checkbox" class="select-checkbox" data-id="' +
                            IdMutasi + '"></td></tr>';
                        tbody.append(row);
                    });

                    // Assuming you already have the role and data from the AJAX response

                    let showRemarkButton = false;

                    // Log the current role and the relevant data fields
                    console.log("Current Role:", currentRole);
                    console.log("FM:", data.FM);
                    console.log("SPV:", data.SPV);
                    console.log("Kadept1:", data.Kadept1);

                    // Check the role and set the showRemarkButton based on conditions
                    if (currentRole === 'Foreman' || currentRole === 'Foreman HRD') {
                        showRemarkButton = false; // No condition for these roles
                    } else if (currentRole === 'Supervisor' || currentRole === 'Supervisor HRD') {
                        // Show only if FM is not null or empty
                        showRemarkButton = (data.FM !== null && data.FM !== '');
                    } else if (currentRole === 'Kepala Departemen' || currentRole ===
                        'Kepala Departemen HRD') {
                        // Show only if SPV is not null or empty, or if SPV is empty but Kadept1 is present
                        showRemarkButton = (data.SPV !== null && data.SPV !== '') || (data.SPV ===
                            null && (data.Kadept1 !== null && data.Kadept1 !== ''));
                    }

                    // Log the result for showing the Remark button
                    console.log("Show Remark Button:", showRemarkButton);

                    // Show or hide the Remark button based on the conditions
                    if (showRemarkButton) {
                        $('#remarkButton').show(); // Show the Remark button
                    } else {
                        $('#remarkButton').hide(); // Hide the Remark button
                    }



                } else {
                    tbody.append('<tr><td colspan="7">No data available</td></tr>');
                }

                if (data.hasRemarks) {
                    $('#addFeedback').show();
                } else {
                    $('#addFeedback').hide();
                    $('#feedbackInput').hide();
                }

                // Update the second table
                var secondTableBody = $('#second-table-body');
                secondTableBody.empty();

                $.each(data.approvals, function(index, approval) {
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

                // Fetch remarks
                $.ajax({
                    url: '../../query/getRemark.php',
                    type: 'GET',
                    data: {
                        batchMutasi: batchMutasi
                    },
                    dataType: 'json',
                    success: function(data) {
                        console.log('Remarks AJAX Success:', data);
                        var remarksTableBody = $('#remarks-table-body');
                        remarksTableBody.empty();

                        if (data.error) {
                            console.error('Data Error:', data.error);
                            remarksTableBody.append('<tr><td colspan="3">Error: ' + data
                                .error + '</td></tr>');
                        } else if (data.data && data.data.length > 0) {
                            $.each(data.data, function(index, entry) {
                                var row = '<tr>' +
                                    '<td>' + entry.by + '</td>' +
                                    '<td>' + entry.message + '</td>' +
                                    '</tr>';
                                remarksTableBody.append(row);
                            });
                        } else {
                            remarksTableBody.append(
                                '<tr><td colspan="2">No remarks or feedbacks available</td></tr>'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Remarks AJAX Error:', status, error);
                        console.error('Response Text:', xhr.responseText);
                        var errorMessage = 'Error retrieving remarks.';
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.error) {
                                errorMessage = response.error;
                            }
                        } catch (e) {
                            errorMessage = 'Error retrieving remarks.';
                        }
                        Swal.fire('Error!', errorMessage, 'error');
                    }
                });

                $('#detailModal .modal-title').text('Mutasi dari dept ' + data.cwocAsal + ' ke ' +
                    data.cwocBaru);
                $('#detailModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                console.error('Response Text:', xhr.responseText);

                var errorMessage = 'Error retrieving data.';
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.error) {
                        errorMessage = response.error;
                    }
                } catch (e) {
                    errorMessage = 'Error retrieving data.';
                }

                Swal.fire('Error!', errorMessage, 'error');
            }
        });
    });


    $(document).on('change', '.select-checkbox', function() {
        // Check if all individual checkboxes are checked
        var allChecked = $('.select-checkbox').length === $('.select-checkbox:checked').length;

        // Toggle "Check All" checkbox based on whether all checkboxes are checked
        $('#check-all').prop('checked', allChecked);
    });

    // Event for "Check All" checkbox
    $(document).on('click', '#check-all', function() {
        var isChecked = $(this).is(':checked');
        // Check/uncheck all individual checkboxes based on the "Check All" checkbox status
        $('.select-checkbox').prop('checked', isChecked);
    });

    $(document).on('click', '#approveButton', function() {
        var batchMutasi = $('.btn-detail').data('id');
        var selectedIdMutasi = [];
        var feedback = $('#feedbackInput').val(); // Get feedback input

        $('.select-checkbox:checked').each(function() {
            selectedIdMutasi.push($(this).data('id'));
        });

        if (selectedIdMutasi.length === 0) {
            Swal.fire('Tidak Ada Yang Dipilih', 'Tidak ada karyawan yang dipilih.', 'info');
            return;
        }
        var modalTitle = $('#detailModal .modal-title').text();

        // Extract cwocAsal and cwocBaru from the title
        var departments = modalTitle.match(/Mutasi dari dept (.+) ke (.+)/);
        var cwocAsal = departments ? departments[1] : 'Unknown'; // Default to 'Unknown' if not found
        var cwocBaru = departments ? departments[2] : 'Unknown'; // Default to 'Unknown' if not found
        Swal.fire({
            title: 'Konfirmasi Mutasi',
            text: 'Apakah Anda yakin ingin menyetujui mutasi dari departemen ' + cwocAsal +
                ' ke departemen ' + cwocBaru + '?',
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
                        feedback: feedback
                    },
                    dataType: 'json',
                    success: function(response) {
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
                    error: function(xhr, status, error) {
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

    <script>
    // This handler is for the remark button click
    $(document).on('click', '#remarkButton', function() {
        // Clear previous remarks and prepare modal
        $('#remarkTextArea').val(''); // Clear previous remarks
        $('#detailModal').modal('hide'); // Hide detail modal when remark modal shows
        $('#remarkModal').modal('show'); // Show remark modal
    });

    // Handler for detail button click within the detail modal
    $(document).on('click', '.btn-detail', function() {
        var batchMutasi = $(this).data('id');
        console.log('Batch Mutasi:', batchMutasi);
        $('#batchNumberRemark').text(batchMutasi); // Set the batch number in the modal
        $('#detailModal').modal('hide'); // Hide detail modal when detail button is clicked
    });

    // Event handler for saving remark
    $(document).on('click', '#saveRemarkButton', function() {
        var remark = $('#remarkTextArea').val();
        var batchMutasi = $('#batchNumberRemark').text();
        console.log("Batch Mutasi:", batchMutasi); // Check if this logs correctly

        if (!remark) {
            Swal.fire('Peringatan!', 'Silakan masukkan remark sebelum menyimpan.', 'warning');
            return;
        }

        $.ajax({
            url: '../../query/remark.php', // Your endpoint to handle the remark
            type: 'POST',
            data: {
                remark: remark,
                batchMutasi: batchMutasi
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Remark Berhasil Disimpan',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        window.location.href =
                            'approvalMutasi.php'; // Redirect after success
                    });
                    $('#remarkModal').modal('hide'); // Hide the modal after saving
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: response.error || 'Gagal menyimpan remark.',
                        icon: 'error',
                        timer: 2000 // Show for 2 seconds
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat menyimpan remark.',
                    icon: 'error',
                    timer: 2000 // Show for 2 seconds
                });
            }
        });
    });

    // Event handler for the reset button
    $(document).on('click', '#resetButton', function() {
        $('#remarkTextArea').val(''); // Clear the text area
    });

    // Event for when the remark modal is closed
    $('#remarkModal').on('hidden.bs.modal', function() {
        $('#detailModal').modal('show'); // Show the detail modal again when remark modal is closed
    });
    </script>




    <script>
    // JavaScript to toggle the feedback input area
    document.getElementById('addFeedback').addEventListener('click', function() {
        var feedbackInput = document.getElementById('feedbackInput');
        feedbackInput.style.display = feedbackInput.style.display === 'none' ? 'block' : 'none';
        feedbackInput.focus(); // Optional: Focus the text area when shown
    });

    // Toggle for Approval History Section
    document.getElementById("toggleApprovalHistoryButton").addEventListener("click", function() {
        const approvalHistorySection = document.getElementById("approvalHistorySection");
        const buttonText = this.innerText === "Lihat riwayat Approval Mutasi" ? "Tutup" :
            "Lihat riwayat Approval Mutasi";
        this.innerText = buttonText; // Change button text
        approvalHistorySection.style.display = approvalHistorySection.style.display === "none" ?
            "block" : "none"; // Toggle visibility
    });

    // Toggle for Remarks Section
    document.getElementById("toggleRemarksButton").addEventListener("click", function() {
        const remarksSection = document.getElementById("remarksSection");
        const buttonText = this.innerText === "Lihat catatan" ? "Tutup" : "Lihat catatan";
        this.innerText = buttonText; // Change button text
        remarksSection.style.display = remarksSection.style.display === "none" ? "block" :
            "none"; // Toggle visibility
    });

    // Hide sections when the modal is closed
    $('#detailModal').on('hidden.bs.modal', function() {
        document.getElementById('approvalHistorySection').style.display = 'none'; // Hide approval history
        document.getElementById('remarksSection').style.display = 'none'; // Hide remarks
        // Optionally reset button texts if needed
        document.getElementById("toggleApprovalHistoryButton").innerText = "Lihat riwayat Approval Mutasi";
        document.getElementById("toggleRemarksButton").innerText = "Lihat catatan";
    });
    </script>

    <!-- <script>
    $(document).on('click', '#resetButton', function() {
        // Clear the remark text area
        $('#remarkTextArea').val('');
    });
    </script> -->

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

/* Custom styles for the modal dialog */
.custom-modal-remarks .modal-dialog {
    max-width: 60%;
    /* Max width for larger screens */
}

@media (max-width: 767.98px) {
    .custom-modal-remarks .modal-dialog {
        max-width: 95%;
        /* Max width for smaller screens */
    }
}
</style>