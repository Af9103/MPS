<?php
session_start();

// Periksa apakah pengguna sudah login, jika belum, redirect ke halaman login
if (!isset($_SESSION["npk"]) || !isset($_SESSION["dept"]) || !isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true) {
    echo "Anda harus login terlebih dahulu";
    header("Location: ../forbidden.php");
    exit; // Pastikan untuk menghentikan eksekusi skrip setelah melakukan redirect
}
include __DIR__ . '/../../query/koneksi.php';

$currentMonth = date('m');
$currentYear = date('Y');

// Filter data based on the selected month and year, or show data for the current month if no filter is applied
$selectedMonth = isset($_GET['bulan']) ? $_GET['bulan'] : $currentMonth;
$selectedYear = isset($_GET['tahun']) ? $_GET['tahun'] : $currentYear;

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


if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01033') {
        $queryMutasi = "SELECT *
                                FROM mutasi
                                WHERE mutasi.hapus IS NULL
                                AND mutasi.cwocAsal IN ('QA', 'PDE 2W', 'PDE 4W', 'CQE 2W', 'CQE 4W')
                                AND mutasi.status = '10'";
    } elseif ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01561') {
        $queryMutasi = "SELECT *
                        FROM mutasi
                        WHERE hapus IS NULL
                        AND mutasi.cwocAsal IN ('HRD IR', 'GA', 'MIS') AND mutasi.status = '10'";
    } elseif ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01961') {
        $queryMutasi = "SELECT *
                    FROM mutasi
                    WHERE hapus IS NULL
                    AND mutasi.cwocAsal IN ('PCE', 'PE 2W', 'PE 4W') AND mutasi.status = '10'";
    } elseif ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01166') {
        $queryMutasi = "SELECT *
                      FROM mutasi
                      WHERE hapus IS NULL
                      AND mutasi.cwocAsal IN ('MARKETING', 'PROCUREMENT', 'VENDOR DEVELOPMENT', 'GENERAL PURCHASE') AND mutasi.status = '10'";
    } elseif ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01266') {
        $queryMutasi = "SELECT *
                      FROM mutasi
                      WHERE hapus IS NULL
                      AND mutasi.cwocAsal IN ('WAREHOUSE', 'PRODUCTION SYSTEM', 'PPC') AND mutasi.status = '10'";
    } elseif ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01577') {
        $queryMutasi = "SELECT *
                        FROM mutasi
                        WHERE hapus IS NULL
                        AND mutasi.cwocAsal IN ('PRODUCTION 1', 'PRODUCTION 2', 'PRODUCTION 3', 'PRODUCTION 4', 'PRODUCTION 5') AND mutasi.status = '10'
";
    } elseif ($_SESSION['role'] == 'Direktur Plant') {
        $queryMutasi = "SELECT *
                        FROM mutasi
                        WHERE hapus IS NULL AND mutasi.status = '10' AND mutasi.cwocAsal IN ('PDE 2W', 'PDE 4W', 'QA', 'CQE 2W', 'CQE 4W', 'PCE', 'PE 2W', 'PE 4W', 'PRODUCTION 1', 'PRODUCTION 2', 'PRODUCTION 3', 'PRODUCTION 4', 'PRODUCTION 5')
";
    } elseif ($_SESSION['role'] == 'Direktur Non Plant') {
        $queryMutasi = "SELECT *
                        FROM mutasi
                        WHERE hapus IS NULL AND mutasi.status = '10' AND mutasi.cwocAsal IN ('HRD IR', 'MIS', 'GA', 'MARKETING', 'PROCUREMENT', 'VENDOR DEVELOPMENT', 'GENERAL PURCHASE')
";

    } else {
        $queryMutasi = "SELECT *
                        FROM mutasi
                        WHERE hapus IS NULL
                        AND mutasi.status = '10'
                        AND mutasi.cwocAsal = '{$_SESSION['dept']}'";
    }
}


if (!isset($_GET['show_all'])) {
    $queryMutasi .= " AND MONTH(mutasi.tanggalMutasi) = '$selectedMonth' AND YEAR(mutasi.tanggalMutasi) = '$selectedYear'";
}

// Append ORDER BY clause at the end
$queryMutasi .= " GROUP BY batchMutasi ORDER BY mutasi.status ASC";

$resultMutasi = mysqli_query($koneksi3, $queryMutasi);
if (!$resultMutasi) {
    die("Query error: " . mysqli_error($koneksi3));
}

// Tentukan judul daftar
if (isset($_GET['show_all'])) {
    $judulDaftar = "Daftar Seluruh Mutasi";
} else {
    $namaBulan = date("F", mktime(0, 0, 0, $selectedMonth, 1));
    $judulDaftar = "Daftar Mutasi $namaBulan $selectedYear";
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

    <title>Permohonan Mutasi | Mutasi Keluar</title>
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
                        <h1 class="m-0">Mutation Keluar</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="dashboard.php">Beranda</a></li>
                            <li class="breadcrumb-item active">Riwayat Mutasi</li>
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

                        <!-- Elemen filter tahun -->
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
                            <a href="#" id="showAllLink" style="text-decoration: none; color: inherit;">Tampilkan
                                semua</a>
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
                                        echo '<td>' . $batchMutasiCount . '</td>';
                                        echo '<td style="display:none;">' . $row['sectBaru'] . '</td>';
                                        echo '<td style="display:none;">' . $row['subsectBaru'] . '</td>';
                                        echo '<td style="display:none;">' . $row['tanggalMutasi'] . '</td>';
                                        echo "<td>
<a href='../../output/pdfBatchMutasi.php?batchMutasi=" . htmlspecialchars($row['batchMutasi']) . "' class='btn btn-success btn-sm btn-pdf' target='_blank' style='margin-right: 3px;'>
                        <i class='fas fa-file-pdf' style='color: white;'></i>
                    </a>
            <a><button class='btn btn-primary btn-sm btn-detail' data-toggle='modal' data-target='#detailModal' data-id='" . htmlspecialchars($row['batchMutasi']) . "'>
                        <i class='fas fa-info-circle' style='color: white;'></i>
                    </button></a>
            
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
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable custom-width" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="detailModalLabel">Mutasi dari dept</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Employee Number</th>
                                            <th>Nama</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="modal-body-content">
                                        <!-- Data will be inserted here by JavaScript -->
                                    </tbody>
                                </table>
                            </div>
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
    <script src="../../plugins/jquery-ui/jquery-ui.min.js"></script>
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
    $(document).ready(function() {
        var exportBaseUrl = '../output/excelMutasi.php';

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
        $('#bulan, #tahun').change(function() {
            $('#showAllLink').data('show-all', false);
            filterByMonthAndYear();
        });

        function filterByMonthAndYear() {
            var selectedMonth = $('#bulan').val();
            var selectedYear = $('#tahun').val();
            var url = 'mutasiOut.php'; // Sesuaikan dengan nama halaman PHP Anda

            // Lakukan permintaan AJAX
            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    bulan: selectedMonth,
                    tahun: selectedYear
                },
                success: function(data) {
                    // Dapatkan tabel dari respons data
                    var table = $(data).find('.table-responsive').html();
                    // Perbarui tabel di halaman saat ini
                    $('.table-responsive').html(table);

                    var title = $(data).find('.card-title').text();
                    $('h3').text(title);
                }
            });
        }

        // Fungsi untuk menangani klik pada tautan "Tampilkan semua"
        $('#showAllLink').click(function(e) {
            e.preventDefault();

            var url = 'mutasiOut.php'; // Sesuaikan dengan nama halaman PHP Anda

            // Lakukan permintaan AJAX tanpa parameter bulan dan tahun
            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    show_all: '1'
                },
                success: function(data) {
                    // Dapatkan tabel dari respons data
                    var table = $(data).find('.table-responsive').html();
                    // Perbarui tabel di halaman saat ini
                    $('.table-responsive').html(table);

                    var title = $(data).find('.card-title').text();
                    $('h3').text(title);

                    // Tandai bahwa sedang menampilkan semua data
                    $('#showAllLink').data('show-all', true);
                }
            });
        });
    });
    </script>

    <script>
    function getStatusMessage(statusCode, rejectMessage) {
        switch (parseInt(statusCode, 10)) {
            case 2:
                return 'Waiting Foreman';
            case 3:
                return 'Waiting Supervisor';
            case 4:
                return 'Waiting Ka.Dept Applicant';
            case 5:
                return 'Waiting Ka.Dept Recipient';
            case 6:
                return 'Waiting Ka.Div Applicant';
            case 7:
                return 'Waiting Ka.Div Recipient';
            case 8:
                return 'Waiting Direktur';
            case 9:
                return 'Waiting HRD';
            case 10:
                return 'Finish';
            default:
                return `Reject by ${rejectMessage}`;
        }
    }

    $(document).on('click', '.btn-detail', function() {
        var batchMutasi = $(this).data('id');
        console.log('Batch Mutasi:', batchMutasi);

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
                        var rejectMessage = data.reject[index];
                        var status = getStatusMessage(statusCode, rejectMessage);
                        var nama = data.nama[index];
                        var emno = data.emnos[index];
                        var cwocAsal = data.cwocAsal; // Get cwocAsal
                        var IdMutasi = data.IdMutasi[index];

                        var row = '<tr><td>' + (index + 1) + '</td>' +
                            '<td>' + emno + '</td>' +
                            '<td>' + nama + '</td>' +
                            '<td>' + status + '</td>'
                        '</tr>';

                        tbody.append(row);
                    });
                } else {
                    tbody.append('<tr><td colspan="5">No data available</td></tr>');
                }

                $('#detailModal .modal-title').text('Mutasi dari dept ' + data.cwocAsal + ' ke ' +
                    data.cwocBaru);
                $('#detailModal').modal('show');
            },

            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                alert('Error retrieving data.');
            }
        });
    });
    </script>
</body>

</html>