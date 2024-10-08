<?php
session_start();
include __DIR__ . '/koneksi.php'; // Ensure the connection file is correctly included

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize input
    $batchMutasi = isset($_POST['batchMutasi']) ? $_POST['batchMutasi'] : '';
    $remark = isset($_POST['remark']) ? $_POST['remark'] : '';
    date_default_timezone_set('Asia/Jakarta');
    $date = date('Y-m-d H:i:s'); // Get the current date and time
    $by = isset($_SESSION['npk']) ? $_SESSION['npk'] : '';

    // Escape the inputs to prevent SQL injection
    $batchMutasi = mysqli_real_escape_string($koneksi3, $batchMutasi);
    $remark = mysqli_real_escape_string($koneksi3, $remark);
    $by = mysqli_real_escape_string($koneksi3, $by);

    $queryGetStatus = "SELECT status, emno, cwocAsal, cwocBaru, sectAsal, sectBaru, tanggalMutasi, nama, batchMutasi FROM mutasi WHERE batchMutasi = '$batchMutasi'";
    $resultGetStatus = mysqli_query($koneksi3, $queryGetStatus);
    if (!$resultGetStatus) {
        die("Query error: " . mysqli_error($koneksi3));
    }

    $employeeDetails = array();
    $emnoArray = array();
    $counter = 1; // Initialize a counter to add numbering
    while ($row = mysqli_fetch_assoc($resultGetStatus)) {
        $status = $row['status'];
        $emno = $row['emno'];
        $cwocAsal = $row['cwocAsal'];
        $batchMutasi1 = $row['batchMutasi'];
        $cwocBaru = $row['cwocBaru'];
        $sectAsal = $row['sectAsal'];
        $sectBaru = $row['sectBaru'];
        $tanggalMutasi = $row['tanggalMutasi'];
        $nama = $row['nama'];

        // Fetch sectAsalDesc from the hrd_sect table
        $querySectAsal = "SELECT `desc` AS sectAsalDesc FROM hrd_sect WHERE sect = '$sectAsal'";
        $resultSectAsal = mysqli_query($koneksi2, $querySectAsal);
        if ($resultSectAsal && mysqli_num_rows($resultSectAsal) > 0) {
            $sectAsalData = mysqli_fetch_assoc($resultSectAsal);
            $sectAsalDesc = $sectAsalData['sectAsalDesc'];
        } else {
            $sectAsalDesc = 'Unknown Section'; // Default value if not found
        }

        // Fetch sectBaruDesc from the hrd_sect table
        $querySectBaru = "SELECT `desc` AS sectBaruDesc FROM hrd_sect WHERE sect = '$sectBaru'";
        $resultSectBaru = mysqli_query($koneksi2, $querySectBaru);
        if ($resultSectBaru && mysqli_num_rows($resultSectBaru) > 0) {
            $sectBaruData = mysqli_fetch_assoc($resultSectBaru);
            $sectBaruDesc = $sectBaruData['sectBaruDesc'];
        } else {
            $sectBaruDesc = 'Unknown Section'; // Default value if not found
        }

        $employeeDetails[] = "$counter. $emno - $nama";
        $counter++; // Increment counter for the next employee
        $emnoArray[] = $emno;
    }

    $kelompok1 = ['QA', 'PDE 2W', 'PDE 4W', 'CQE 2W', 'CQE 4W'];
    $kelompok2 = ['HRD IR', 'GA', 'MIS'];
    $kelompok3 = ['PCE', 'PE 2W', 'PE 4W'];
    $kelompok4 = ['MARKETING', 'PROCUREMENT', 'VENDOR DEVELOPMENT', 'GENERAL PURCHASE'];
    $kelompok5 = ['WAREHOUSE', 'PRODUCTION SYSTEM', 'PPC'];
    $kelompok6 = ['PRODUCTION 1', 'PRODUCTION 2', 'PRODUCTION 3', 'PRODUCTION 4', 'PRODUCTION 5'];

    if (in_array($cwocAsal, $kelompok1)) {
        $divisi = "Quality Assurance";
    } elseif (in_array($cwocAsal, $kelompok2)) {
        $divisi = "HRGA & MIS";
    } elseif (in_array($cwocAsal, $kelompok3)) {
        $divisi = "Engineering";
    } elseif (in_array($cwocAsal, $kelompok4)) {
        $divisi = "Marketing & Procurement";
    } elseif (in_array($cwocAsal, $kelompok5)) {
        $divisi = "Production Control";
    } elseif (in_array($cwocAsal, $kelompok6)) {
        $divisi = "Production";
    } else {
        $divisi = "Non Divisi"; // Fallback if none matches
    }

    if (in_array($cwocBaru, $kelompok1)) {
        $divisiBaru = "Quality Assurance";
    } elseif (in_array($cwocBaru, $kelompok2)) {
        $divisiBaru = "HRGA & MIS";
    } elseif (in_array($cwocBaru, $kelompok3)) {
        $divisiBaru = "Engineering";
    } elseif (in_array($cwocBaru, $kelompok4)) {
        $divisiBaru = "Marketing & Procurement";
    } elseif (in_array($cwocBaru, $kelompok5)) {
        $divisiBaru = "Production Control";
    } elseif (in_array($cwocBaru, $kelompok6)) {
        $divisiBaru = "Production";
    } else {
        $divisiBaru = "Non Divisi"; // Fallback if none matches
    }

    // Function to check if two CWOCs belong to the same group
    function sameGroup($cwoc1, $cwoc2, $groups)
    {
        foreach ($groups as $group) {
            if (in_array($cwoc1, $group) && in_array($cwoc2, $group)) {
                return true;
            }
        }
        return false;
    }

    $grup1 = ['QA', 'PDE 2W', 'PDE 4W', 'CQE 2W', 'CQE 4W'];
    $grup2 = ['HRD IR', 'MIS', 'GA'];
    $grup3 = ['MARKETING', 'PROCUREMENT', 'VENDOR DEVELOPMENT', 'GENERAL PURCHASE'];
    $grup4 = ['PRODUCTION SYSTEM', 'PPC'];
    $grup5 = ['PRODUCTION 1', 'PRODUCTION 2', 'PRODUCTION 3', 'PRODUCTION 4', 'PRODUCTION 5'];

    $npk = '';

    // Check if either $cwocAsal or $cwocBaru is in any of the groups
    if (in_array($cwocAsal, $grup1)) {
        $npk = '01033';
    } elseif (in_array($cwocAsal, $grup2)) {
        $npk = '01561';
    } elseif (in_array($cwocAsal, $grup3)) {
        $npk = '01166';
    } elseif (in_array($cwocAsal, $grup4)) {
        $npk = '01266';
    } elseif (in_array($cwocAsal, $grup5)) {
        $npk = '01577';
    }

    if (in_array($cwocBaru, $grup1)) {
        $npk2 = '01033';
    } elseif (in_array($cwocBaru, $grup2)) {
        $npk2 = '01561';
    } elseif (in_array($cwocBaru, $grup3)) {
        $npk2 = '01166';
    } elseif (in_array($cwocBaru, $grup4)) {
        $npk2 = '01266';
    } elseif (in_array($cwocBaru, $grup5)) {
        $npk2 = '01577';
    }


    $plantGroups = ['PDE 2W', 'PDE 4W', 'QA', 'CQE 2W', 'CQE 4W', 'PCE', 'PE 2W', 'PE 4W', 'WAREHOUSE', 'PRODUCTION SYSTEM', 'PPC', 'PRODUCTION 1', 'PRODUCTION 2', 'PRODUCTION 3', 'PRODUCTION 4', 'PRODUCTION 5'];
    $nonPlantGroups = ['HRD IR', 'MIS', 'GA', 'MARKETING', 'PROCUREMENT', 'VENDOR DEVELOPMENT', 'GENERAL PURCHASE'];

    // Initialize department variable
    $deptTarget = '';

    // Determine the target department based on cwocAsal
    if (in_array($cwocAsal, $plantGroups)) {
        $deptTarget = 'BOD PLANT';
    } elseif (in_array($cwocAsal, $nonPlantGroups)) {
        $deptTarget = 'BOD Non Plant';
    }

    // Define all groups in an array
    $allGroups = [$kelompok1, $kelompok2, $kelompok3, $kelompok4, $kelompok5, $kelompok6];
    $specialGroups = ['PDCA CPC', 'EHS', 'PLANNING BUDGETING', 'FINANCE ACCOUNTING'];

    $newStatus = $status;
    $message = '';
    $flags = "queue";

    // Determine the status and newstatus based on user role
    if ($_SESSION['role'] == 'Supervisor' || $_SESSION['role'] == 'Supervisor HRD') {
        $status = 3;
        $newstatus = 2;
        $message = "Pemberitahuan Mutasi Karyawan!!\n\nSupervisor telah memberikan remark pada batch mutasi $batchMutasi1 sebagai berikut:\n$remark\nDaftar Karyawan yang terlibat dalam mutasi ini:\n" . implode("\n", $employeeDetails) . "";
        $query_npk = "SELECT npk FROM ct_users WHERE golongan = 3 AND acting = 2 AND dept = '$cwocAsal'";

    } elseif ($_SESSION['role'] == 'Kepala Departemen' || $_SESSION['role'] == 'Kepala Departemen HRD') {
        if ($status == 4) {
            $newstatus = 3;
            $message = "Pemberitahuan Mutasi Karyawan!!\n\nKepala Departemen telah memberikan remark pada batch mutasi $batchMutasi1 sebagai berikut:\n$remark\nDaftar Karyawan yang terlibat dalam mutasi ini:\n" . implode("\n", $employeeDetails) . "";
            $query_npk = "SELECT npk FROM ct_users WHERE golongan = 4 AND acting = 2 AND dept = '$cwocAsal'";
        } elseif ($status == 5) {
            if ($cwocAsal == $cwocBaru) {
                $newstatus = 3;
                $message = "Pemberitahuan Mutasi Karyawan!!\n\nKepala Departemen telah memberikan remark pada batch mutasi $batchMutasi1 sebagai berikut:\n$remark\nDaftar Karyawan yang terlibat dalam mutasi ini:\n" . implode("\n", $employeeDetails) . "";
                $query_npk = "SELECT npk FROM ct_users WHERE golongan = 4 AND acting = 2 AND dept = '$cwocAsal'";
            } else {
                $newstatus = 4;
                $message = "Pemberitahuan Mutasi Karyawan!!\n\nKepala Departemen $cwocBaru telah memberikan remark pada batch mutasi $batchMutasi1 sebagai berikut:\n$remark\nDaftar Karyawan yang terlibat dalam mutasi ini:\n" . implode("\n", $employeeDetails) . "";
                $query_npk = "SELECT npk FROM ct_users WHERE dept = '$cwocAsal' AND npk IN (SELECT npk FROM hrd_so WHERE tipe = 1)";
            }
        }

    } elseif ($_SESSION['role'] == 'Kepala Divisi') {
        if ($status == 6) {
            if ($cwocAsal == $cwocBaru) {
                $newstatus = 4;
                $message = "Pemberitahuan Mutasi Karyawan!!\n\nKepala Divisi $divisi telah memberikan remark pada batch mutasi $batchMutasi1 sebagai berikut:\n$remark\nDaftar Karyawan yang terlibat dalam mutasi ini:\n" . implode("\n", $employeeDetails);
                $query_npk = "SELECT npk FROM ct_users WHERE dept =  '$cwocAsal' AND npk IN (SELECT npk FROM hrd_so WHERE tipe = 1)";
            } else {
                $newstatus = 5;
                $message = "Pemberitahuan Mutasi Karyawan!!\n\nKepala Divisi $divisi telah memberikan remark pada batch mutasi $batchMutasi1 sebagai berikut:\n$remark\nDaftar Karyawan yang terlibat dalam mutasi ini:\n" . implode("\n", $employeeDetails);
                $query_npk = "SELECT npk FROM ct_users WHERE dept = '$cwocBaru' AND npk IN (SELECT npk FROM hrd_so WHERE tipe = 1)";
            }
        } elseif ($status == 7) {
            if (sameGroup($cwocAsal, $cwocBaru, $allGroups)) {
                $newstatus = 5;
                $message = "Pemberitahuan Mutasi Karyawan!!\n\nKepala Divisi $divisiBaru telah memberikan remark pada batch mutasi $batchMutasi1 sebagai berikut:\n $remark\nDaftar Karyawan yang terlibat dalam mutasi ini:\n" . implode("\n", $employeeDetails);
                $query_npk = "SELECT npk FROM ct_users WHERE dept = '$cwocBaru' AND npk IN (SELECT npk FROM hrd_so WHERE tipe = 1)";
            } elseif (in_array($cwocAsal, $specialGroups)) {
                $newstatus = 5;
                $message = "Pemberitahuan Mutasi Karyawan!!\n\nKepala Divisi $divisiBaru telah memberikan remark pada batch mutasi $batchMutasi1 sebagai berikut:\n $remark\nDaftar Karyawan yang terlibat dalam mutasi ini:\n" . implode("\n", $employeeDetails);
                $query_npk = "SELECT npk FROM ct_users WHERE dept = '$cwocBaru' AND npk IN (SELECT npk FROM hrd_so WHERE tipe = 1)";
            } else {
                $newstatus = 6;
                $message = "Pemberitahuan Mutasi Karyawan!!\n\nKepala Divisi $divisiBaru telah memberikan remark pada batch mutasi $batchMutasi1 sebagai berikut:\n$remark\nDaftar Karyawan yang terlibat dalam mutasi ini:\n" . implode("\n", $employeeDetails);
                $query_npk = "SELECT npk FROM ct_users WHERE npk = '$npk'";
            }
        }

    } elseif ($_SESSION['role'] == 'Direktur Plant' || $_SESSION['role'] == 'Direktur Non Plant') {
        if ($status == 8) {
            if (in_array($cwocAsal, $specialGroups) && in_array($cwocBaru, $specialGroups)) {
                $newstatus = 5;
                $message = "Pemberitahuan Mutasi Karyawan!!\n\nDirektur telah memberikan remark pada batch mutasi $batchMutasi1 sebagai berikut:\n$remark\nDaftar Karyawan yang terlibat dalam mutasi ini:\n" . implode("\n", $employeeDetails) . "";
                $query_npk = "SELECT npk FROM ct_users WHERE dept = '$cwocBaru' AND npk IN (SELECT npk FROM hrd_so WHERE tipe = 1)";
            } elseif (sameGroup($cwocAsal, $cwocBaru, $allGroups)) {
                $newstatus = 6;
                $message = "Pemberitahuan Mutasi Karyawan!!\n\nDirektur telah memberikan remark pada batch mutasi $batchMutasi1 sebagai berikut:\n$remark\nDaftar Karyawan yang terlibat dalam mutasi ini:\n" . implode("\n", $employeeDetails) . "";
                $query_npk = "SELECT npk FROM ct_users WHERE npk = '$npk';";
            } elseif (in_array($cwocBaru, $specialGroups)) {
                $newstatus = 6;
                $message = "Pemberitahuan Mutasi Karyawan!!\n\nDirektur telah memberikan remark pada batch mutasi $batchMutasi1 sebagai berikut:\n$remark\nDaftar Karyawan yang terlibat dalam mutasi ini:\n" . implode("\n", $employeeDetails) . "";
                $query_npk = "SELECT npk FROM ct_users WHERE npk = '$npk';";
            } else {
                $newstatus = 7;
                $message = "Pemberitahuan Mutasi Karyawan!!\n\nDirektur telah memberikan remark pada batch mutasi $batchMutasi1 sebagai berikut:\n$remark\nDaftar Karyawan yang terlibat dalam mutasi ini:\n" . implode("\n", $employeeDetails) . "";
                $query_npk = "SELECT npk FROM ct_users WHERE npk = '$npk2'";
            }

        } elseif ($status == 9) {
            $newstatus = 8;
            $message = "Pemberitahuan Mutasi Karyawan!!\n\nDirektur Tujuan telah memberikan remark pada batch mutasi $batchMutasi1 sebagai berikut:\n$remark\nDaftar Karyawan yang terlibat dalam mutasi ini:\n" . implode("\n", $employeeDetails) . "";
            $query_npk = "SELECT npk FROM ct_users WHERE npk = '$deptTarget';";
        }
    }


    // Construct the SQL query to insert the remark
    $sqlInsert = "INSERT INTO remarks (batchMutasi, remark, date, `by`) VALUES ('$batchMutasi', '$remark', '$date', '$by')";

    // Execute the insert query
    if (mysqli_query($koneksi3, $sqlInsert)) {
        // If insert was successful, update the status in the mutasi table
        $sqlUpdate = "UPDATE mutasi SET status = $newstatus WHERE batchMutasi = '$batchMutasi'";

        if (mysqli_query($koneksi3, $sqlUpdate)) {


            $result_npk = mysqli_query($koneksi2, $query_npk);

            // Collect npk
            $npk_list = [];
            if ($result_npk) {
                while ($row = mysqli_fetch_assoc($result_npk)) {
                    $npk_list[] = "'" . $row['npk'] . "'";
                }
            }

            // Check for npk and fetch phone numbers if available
            if (!empty($npk_list)) {
                $npk_list_str = implode(',', $npk_list);
                $query_phone = "SELECT no_hp FROM isd.hp WHERE npk IN ($npk_list_str)";
                $result_phone = mysqli_query($koneksi4, $query_phone);

                if ($result_phone && mysqli_num_rows($result_phone) > 0) {
                    while ($phone_row = mysqli_fetch_assoc($result_phone)) {
                        $phone_number = $phone_row['no_hp'];
                        $queryInsertNotif = "INSERT INTO notif (phone_number, message, flags) VALUES ('$phone_number', '$message', '$flags')";
                        mysqli_query($koneksi3, $queryInsertNotif);
                    }
                }
            }
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => mysqli_error($koneksi3)]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => mysqli_error($koneksi3)]);
    }

    // Close the database connection
    mysqli_close($koneksi3);
} else {
    // Handle non-POST requests, if necessary
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}
?>