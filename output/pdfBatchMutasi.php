<?php
require __DIR__ . '/../asset/fpdf/fpdf.php';
include __DIR__ . '/../query/koneksi.php';
// require('../phpqrcode/qrlib.php');


$pdf = new FPDF('p', 'mm', 'A5');
$pdf->AddFont('ARIALN', '', 'ARIALN.php');
$pdf->AddFont('ARIALNB', '', 'ARIALNB.php');
// $pdf->SetFont('ArialNarrow', '', 9); // Mengatur font ke Arial Narrow
$pdf->AddPage();
$pdf->SetAutoPageBreak(false, 5);
$pdf->SetFont('arial', 'BU', 10);

$pdf->Cell(45, 10, 'PT KAYABA INDONESIA', 0, 1);
$pdf->Cell(130, 10, 'SURAT PEMBERITAHUAN MUTASI KARYAWAN', 1, 1, 'C');
// Fetch data from query
$batchMutasi = $_GET['batchMutasi']; // Ensure this is properly sanitized

// First query to get the total count of records for the batch
$countQuery = "SELECT COUNT(*) as total FROM mutasi WHERE batchMutasi = '$batchMutasi' AND status != 100";
$countResult = $koneksi3->query($countQuery);
$countRow = $countResult->fetch_assoc();
$totalRecords = $countRow['total'];

// Query to get the actual data
$queryMutasi = "SELECT * FROM mutasi WHERE batchMutasi = '$batchMutasi' AND status != 100";
$result = $koneksi3->query($queryMutasi);


// Check if there's only one record
$isSingleRecord = ($totalRecords == 1);
$isSingleRecord1 = ($totalRecords > 1);

while ($row = $result->fetch_assoc()) {
    $emno = $row['emno'];
    $nama = $row['nama'];
    $sectAsal = $row['sectAsal'];
    $sectBaru = $row['sectBaru'];

    $querySectAsal = "SELECT `desc` AS sectAsalDesc FROM hrd_sect WHERE sect = '$sectAsal'";
    $resultSectAsal = mysqli_query($koneksi2, $querySectAsal);
    $sectAsalData = mysqli_fetch_assoc($resultSectAsal);
    $sectAsalDesc = isset($sectAsalData['sectAsalDesc']) ? $sectAsalData['sectAsalDesc'] : 'N/A';

    // Fetch sectBaruDesc from hrd_sect table
    $querySectBaru = "SELECT `desc` AS sectBaruDesc FROM hrd_sect WHERE sect = '$sectBaru'";
    $resultSectBaru = mysqli_query($koneksi2, $querySectBaru);
    $sectBaruData = mysqli_fetch_assoc($resultSectBaru);
    $sectBaruDesc = isset($sectBaruData['sectBaruDesc']) ? $sectBaruData['sectBaruDesc'] : 'N/A';

    // Fetch full_name from ct_users table
    $queryFullName = "SELECT full_name FROM ct_users WHERE npk = '$emno'";
    $resultFullName = mysqli_query($koneksi2, $queryFullName);
    $fullNameData = mysqli_fetch_assoc($resultFullName);
    $fullName = isset($fullNameData['full_name']) ? $fullNameData['full_name'] : 'N/A';

    $pdf->SetFont('arial', '', 10);

    $pdf->SetXY(10, 32); // Set position for Nama Karyawan
    $pdf->Cell(41, 5, 'Nama Karyawan             : ' . ($isSingleRecord ? $nama : 'Terlampir'), 0, 0);
    $pdf->Cell(35, 5, '', 'B', 0);
    $pdf->SetXY(88, 32);
    $pdf->Cell(15, 5, 'NPK   ' . ($isSingleRecord1 ? 'Terlampir' : $emno), 0, 0);
    $pdf->SetXY(98, 31);
    $pdf->Cell(42, 5, '', 'B', 1);

    $pdf->SetFont('arial', '', 10);
    $pdf->SetY(37);
    $pdf->Cell(41, 5, 'Dari Departemen            : ', 0, 0);
    $pdf->Cell(35, 5, '', 'B', 0);
    $pdf->SetFont('arial', '', 7);
    $pdf->SetXY(50, 38);
    $pdf->Cell(0, 5, $row['cwocAsal'], 0, 0);

    // Optionally, revert back to the original font size if needed for further content
    $pdf->SetFont('arial', '', 10);
    $pdf->SetXY(88, 39);
    $pdf->Cell(15, 5, 'Seksi   ', 0, 0);
    $pdf->SetFont('arial', '', 7);
    $pdf->SetXY(98, 39);
    $pdf->Cell(15, 5, $sectAsalDesc, 0, 0);
    $pdf->SetFont('arial', '', 10);
    $pdf->SetXY(98, 38);
    $pdf->Cell(42, 5, '', 'B', 1);

    $pdf->SetY(44);
    $pdf->Cell(40, 5, 'Terjadi Mutasi Nomor     :', 0, 0);
    $pdf->Cell(37, 5, '7', 'B', 0);
    $pdf->SetXY(90, 44);
    $pdf->Cell(50, 5, '(                     Tujuh                 )', 'B', 1);

    setlocale(LC_TIME, 'id_ID');
    $tanggalMutasi = date('d/m/Y', strtotime($row['tanggalMutasi']));
    $tanggalBuat = date('j-M-y', strtotime($row['tanggalBuat']));
    $tanggalMutasi1 = strftime('%d %B %Y', strtotime($row['tanggalMutasi']));
    $tgl_apv_hrd = $row['tgl_apv_hrd'];
    $hrd = strftime('%d %B %Y %H:%M:%S', strtotime($tgl_apv_hrd));

    // Cek apakah tanggal valid
    if ($tgl_apv_hrd && $hrd !== '01 Januari 1970') {
        $tanggal_hrd = 'Bekasi, ' . $hrd;
    } else {
        $tanggal_hrd = 'Bekasi, ';
    }

    $pdf->SetY(51);
    $pdf->Cell(40, 5, 'Pada Tanggal                 : ' . $tanggalMutasi, 0, 0);
    $pdf->Cell(90, 5, '', 'B', 0);

    $pdf->SetY(58);
    $pdf->Cell(40, 5, 'Keperluan / Keterangan :', 0, 0);
    $pdf->Cell(45, 5, 'Mutasi', 0, 0);

    $pdf->SetXY(100, 65);
    $pdf->Cell(20, 5, 'Bekasi, ' . $tanggalBuat, 0, 0);
    $pdf->SetXY(113, 64);
    $pdf->Cell(27, 5, '', 'B', 1);

    $pdf->SetY(78);
    $pdf->Cell(130, 5, '', 'B', 1);
    $pdf->Cell(20, 7, 'Keterangan / Penjelasan Departemen : ', 0, 1);
    $pdf->SetFont('arial', '', 7);
    $pdf->Cell(20, 5, ' Yang bersangkutan dimutasikan dari Dept ' . $row['cwocAsal'] . ' section ' . $sectAsalDesc, 0, 1);
    $pdf->Cell(20, 3, ' ke Dept. ' . $row['cwocBaru'] . ' Section ' . $sectBaruDesc . ' terhitung tanggal ' . $tanggalMutasi1, 0, 1);
    $pdf->Cell(130, 2, '', 'B', 1);

    $pdf->SetFont('arial', 'U', 11);
    $pdf->Cell(20, 5, 'DISETUJUI / TIDAK DISETUJUI : (Coret yang tidak perlu) ', 0, 1);

    $pdf->SetFont('arial', '', 8);
    $pdf->Cell(19, 7, 'Direktur,', 0, 0, 'C');
    $pdf->Cell(39, 7, 'Kepala Divisi,', 0, 0, 'C');
    $pdf->Cell(39, 7, 'Kepala Departemen,', 0, 0, 'C');
    $pdf->Cell(19, 7, 'Kesie,', 0, 0, 'C');
    $pdf->Cell(19, 7, 'Ka. Sub. Sie,', 0, 0, 'C');

    if (!function_exists('getFullName1')) {
        function getFullName1($koneksi2, $npk)
        {
            $query = "SELECT full_name FROM ct_users WHERE npk = '$npk'";
            $result = mysqli_query($koneksi2, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                $user = mysqli_fetch_assoc($result);
                return $user['full_name'];
            } else {
                return $npk; // Return the npk itself if the full name is not found
            }
        }
    }


    // Assuming $status holds the value of the status
    if (!empty($row['Direktur'])) {
        $pdf->Image('../asset/img/R.png', 14, 111, 9, 9);
    }

    if ($row['Kadiv1'] === $row['Kadiv2'] || (is_null($row['Kadiv1']) && !is_null($row['Kadiv2']))) {
        // When Kadiv1 and Kadiv2 are the same
        if (!empty($row['Kadiv2'])) {
            $pdf->Image('../asset/img/R.png', 44, 111, 9, 9);
        }
    } else {
        // When Kadiv1 and Kadiv2 are different
        if (!empty($row['Kadiv2'])) {
            $pdf->Image('../asset/img/R.png', 34, 111, 9, 9);
        }
        if (!empty($row['Kadiv1'])) {
            $pdf->Image('../asset/img/R.png', 54, 111, 9, 9);
        }
    }

    if ($row['Kadept1'] === $row['Kadept2'] || (is_null($row['Kadept1']) && !is_null($row['Kadept2']))) {
        // When Kadept1 and Kadept2 are the same
        if (!empty($row['Kadept2'])) {
            $pdf->Image('../asset/img/R.png', 83, 111, 9, 9);
        }
    } else {
        // When Kadept1 and Kadept2 are different
        if (!empty($row['Kadept2'])) {
            $pdf->Image('../asset/img/R.png', 74, 111, 9, 9);
        }
        if (!empty($row['Kadept1'])) {
            $pdf->Image('../asset/img/R.png', 94, 111, 9, 9);
        }
    }


    if (!empty($row['SPV'])) {
        $pdf->Image('../asset/img/R.png', 112, 111, 9, 9);
    }

    if (!empty($row['FM'])) {
        $pdf->Image('../asset/img/R.png', 131, 111, 9, 9);
    }

    $pdf->SetFont('arial', '', 8);
    $pdf->SetY(125);

    if (!function_exists('getFullName')) {
        function getFullName($koneksi2, $npk)
        {
            $query = "SELECT full_name FROM ct_users WHERE npk = '$npk'";
            $result = mysqli_query($koneksi2, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                $user = mysqli_fetch_assoc($result);
                return $user['full_name'];
            } else {
                return $npk; // Return the npk itself if the full name is not found
            }
        }
    }


    if (!function_exists('truncateName')) {
        function truncateName($name, $maxLength = 17)
        {
            return strlen($name) > $maxLength ? substr($name, 0, $maxLength) . '...' : $name;
        }
    }
    $fullNameDirektur = truncateName(getFullName($koneksi2, $row['Direktur']));
    $fullNameKadiv1 = truncateName(getFullName($koneksi2, $row['Kadiv1']));
    $fullNameKadiv2 = truncateName(getFullName($koneksi2, $row['Kadiv2']));
    $fullNameKadept1 = truncateName(getFullName($koneksi2, $row['Kadept1']));
    $fullNameKadept2 = truncateName(getFullName($koneksi2, $row['Kadept2']));
    $fullNameSPV = truncateName(getFullName($koneksi2, $row['SPV']));
    $fullNameFM = truncateName(getFullName($koneksi2, $row['FM']));
    $HRD = truncateName(getFullName($koneksi2, $row['HRD']));


    $pdf->SetFont('arial', '', 7);
    // Display full names in PDF
    $pdf->SetXY(10, 120); // Set position for Direktur
    $pdf->MultiCell(17, 2, !empty($row['tgl_direktur']) ? date('d-m-Y H:i:s', strtotime($row['tgl_direktur'])) : '', 0, 'C');
    $pdf->SetXY(10, 125); // Set position for Direktur
    $pdf->MultiCell(17, 2, $fullNameDirektur, 0, 'C');

    if ($row['Kadiv1'] === $row['Kadiv2'] || (empty($row['Kadiv1']) && !empty($row['Kadiv2']))) {
        // Kadiv1 and Kadiv2 are the same or Kadiv1 is empty and Kadiv2 is not
        $pdf->SetXY(29, 120);
        $pdf->MultiCell(36, 2, !empty($row['tgl_kadiv2']) ? date('d-m-Y H:i:s', strtotime($row['tgl_kadiv2'])) : '', 0, 'C');
        $pdf->SetXY(29, 125);
        $pdf->MultiCell(36, 2, $fullNameKadiv2, 0, 'C');
    } else {
        // Kadiv1 and Kadiv2 are different
        $pdf->SetXY(29, 120);
        $pdf->MultiCell(18, 2, !empty($row['tgl_kadiv2']) ? date('d-m-Y H:i:s', strtotime($row['tgl_kadiv2'])) : '', 0, 'C');
        $pdf->SetXY(29, 125);
        $pdf->MultiCell(18, 2, $fullNameKadiv2, 0, 'C');
        $pdf->SetXY(49, 120);
        $pdf->MultiCell(18, 2, !empty($row['tgl_kadiv1']) ? date('d-m-Y H:i:s', strtotime($row['tgl_kadiv1'])) : '', 0, 'C');
        $pdf->SetXY(49, 125);
        $pdf->MultiCell(18, 2, $fullNameKadiv1, 0, 'C');
    }

    if ($row['Kadept1'] === $row['Kadept2'] || (empty($row['Kadept1']) && !empty($row['Kadept2']))) {
        // Kadept1 and Kadept2 are the same or Kadept1 is empty and Kadept2 is not
        $pdf->SetXY(68, 120);
        $pdf->MultiCell(36, 2, !empty($row['tgl_kadept2']) ? date('d-m-Y H:i:s', strtotime($row['tgl_kadept2'])) : '', 0, 'C');
        $pdf->SetXY(68, 125);
        $pdf->MultiCell(36, 2, $fullNameKadept2, 0, 'C');
    } else {
        // Kadept1 and Kadept2 are different
        $pdf->SetXY(70, 120);
        $pdf->MultiCell(17, 2, !empty($row['tgl_kadept2']) ? date('d-m-Y H:i:s', strtotime($row['tgl_kadept2'])) : '', 0, 'C');
        $pdf->SetXY(69, 125);
        $pdf->MultiCell(18, 2, $fullNameKadept2, 0, 'C');
        $pdf->SetXY(89, 120);
        $pdf->MultiCell(17, 2, !empty($row['tgl_kadept1']) ? date('d-m-Y H:i:s', strtotime($row['tgl_kadept1'])) : '', 0, 'C');
        $pdf->SetXY(88, 125);
        $pdf->MultiCell(18, 2, $fullNameKadept1, 0, 'C');
    }

    $pdf->SetXY(108, 120); // Set position for SPV
    $pdf->MultiCell(17, 2, !empty($row['tgl_spv']) ? date('d-m-Y H:i:s', strtotime($row['tgl_spv'])) : '', 0, 'C');

    $pdf->SetXY(108, 125); // Set position for SPV
    $pdf->MultiCell(17, 2, $fullNameSPV, 0, 'C');

    $pdf->SetXY(127, 120); // Set position for FM
    $pdf->MultiCell(17, 2, !empty($row['tgl_fm']) ? date('d-m-Y H:i:s', strtotime($row['tgl_fm'])) : '', 0, 'C');

    $pdf->SetXY(127, 125); // Set position for FM
    $pdf->MultiCell(17, 2, $fullNameFM, 0, 'C');



    $pdf->Cell(130, 5, '', 'B', 1);
    $pdf->SetFont('arial', 'U', 11);
    $pdf->Cell(20, 7, 'CATATAN / KETERANGAN DARI DEPT. HRD', 0, 1);
    $pdf->SetFont('arial', '', 8);
    if ($row['status'] == 10) {
        $pdf->MultiCell(100, 3, "Terhitung tanggal " . $tanggalMutasi1 . " ybs dimutasikan dari Section " . $sectAsalDesc . "Departemen " . $row['cwocAsal'] . " Ke Section " . $sectBaruDesc . " Departemen " . $row['cwocBaru'] . " Perpindahan ini tidak merubah ruang gaji, pangkat, golongan serta sudah dibukukan ke dalam file yang bersangkutan.\nDemikianlah pemberitahuan ini agar yang bersangkutan mengetahui", 0, '1');
    }


    if ($row['status'] >= 9) {
        $pdf->Image('../asset/img/R.png', 120, 137, 10, 10);
    }
    $pdf->SetXY(105, 150);
    $pdf->SetFont('arial', '', 8);
    if ($row['status'] == 10) {
        $pdf->Cell(40, 5, $HRD, 0, 1, 'C');
        $pdf->SetXY(100, 155);
        $pdf->Cell(20, 5, $tanggal_hrd, 0, 1);
        $pdf->SetXY(113, 155);
        $pdf->Cell(27, 4, '', 'B', 1);
    }

    $pdf->SetY(158);
    $pdf->Cell(130, 3, '', 'B', 1);
    $pdf->SetFont('arial', 'U', 10);
    $pdf->Cell(20, 5, 'KETERANGAN NOMOR MUTASI :', 0, 1);

    $pdf->SetFont('arial', 'U', 9);
    $pdf->Cell(70, 5, 'MUTASI YANG DILAKUKAN KARYAWAN :', 0, 0);
    $pdf->Cell(70, 5, 'MUTASI YANG DILAKUKAN PIMPINAN :', 0, 1);

    $pdf->SetFont('arial', '', 8);
    $pdf->MultiCell(70, 3, "1.Cuti Tahunan\n2.Cuti Besar\n3.Nikah / Cerai\n4.Tambahan Pendidikan / Kursus\n5.Perubahan Alamat Rumah\n6.Kelahiran\n7.Dipindahkan ke Dept. Lain\n8.Perubahan Status Karyawan", 0, 1);
    $pdf->SetXY(80, 172);
    $pdf->MultiCell(70, 3, "9.Pindah Jabatan\n10.Kenaikan Pangkat\n11.Perubahan Gaji Pokok/Golongan\n12.Perubahan Isi Pekerjaan / Mutasi Kerja\n13.Pengunduran diri / PHK\n14.Aspek Lingkungan / K3\n15.Lain-lain", 0, 1);


    $pdf->SetFont('arial', '', 9);
    $pdf->SetY(191);
    $pdf->Cell(130, 5, '', 'B', 1);
    $pdf->Cell(40, 5, 'Dibuat rangkap 2 (dua)', 0, 0);
    $pdf->MultiCell(70, 5, "1.1.Asli            :Karyawan\n2.Tembusan    :Arsip di Dept.HRD.", 0, 1);

}

// Tentukan batas jumlah baris per halaman
// Define maximum rows per page
$maxRowsPerPage = 30; // Adjust as needed
$rowCount = 0; // Initialize row counter

if ($totalRecords > 1) {
    // Add the first page
    $pdf->AddPage('P', 'A4');

    // Set title and header
    $pdf->SetFont('arial', 'B', 12);
    $pdf->Cell(0, 10, 'Lampiran Karyawan Mutasi', 0, 1, 'C');

    $pdf->SetFont('arial', '', 10);
    $pdf->SetXY(10, 30);
    $pdf->Cell(0, 10, 'Daftar Karyawan yang Terkena Mutasi:', 0, 1);

    // Set column headers
    $pdf->SetXY(10, 40);
    $pdf->SetFont('arial', '', 6);
    $pdf->Cell(10, 10, 'No', 1, 0, 'C');
    $pdf->Cell(15, 10, 'NPK', 1, 0, 'C');
    $pdf->Cell(50, 10, 'Nama', 1, 0, 'C');
    $pdf->Cell(50, 5, 'Asal', 1, 0, 'C');
    $pdf->Cell(50, 5, 'Ke', 1, 0, 'C');
    $pdf->Cell(20, 10, 'Paraf', 1, 0, 'C');

    $pdf->SetXY(85, 45);
    $pdf->Cell(25, 5, 'Dept', 1, 0, 'C');
    $pdf->Cell(25, 5, 'Seksi', 1, 0, 'C');
    $pdf->Cell(25, 5, 'Dept', 1, 0, 'C');
    $pdf->Cell(25, 5, 'Seksi', 1, 0, 'C');
    $pdf->Ln();

    // Fetch and display all records
    $result->data_seek(0); // Reset result pointer to fetch records again
    $no = 1; // Initialize counter

    while ($row = $result->fetch_assoc()) {
        // Extract data
        $emno = $row['emno'];
        $nama = $row['nama'];
        $cwocAsal = $row['cwocAsal'];
        $sectAsal = $row['sectAsal'];
        $cwocBaru = $row['cwocBaru'];
        $sectBaru = $row['sectBaru'];
        $cek = $row['cek'];

        // Check if we need to add a new page
        if ($rowCount >= $maxRowsPerPage) {
            $pdf->AddPage('P', 'A4'); // Add new page

            // Reprint headers on new page
            $pdf->SetFont('arial', 'B', 12);
            $pdf->Cell(0, 10, 'Lampiran Karyawan Mutasi', 0, 1, 'C');

            $pdf->SetFont('arial', '', 10);
            $pdf->SetXY(10, 30);
            $pdf->Cell(0, 10, 'Daftar Karyawan yang Terkena Mutasi:', 0, 1);

            $pdf->SetXY(10, 40);
            $pdf->SetFont('arial', '', 6);
            $pdf->Cell(10, 10, 'No', 1, 0, 'C');
            $pdf->Cell(15, 10, 'NPK', 1, 0, 'C');
            $pdf->Cell(50, 10, 'Nama', 1, 0, 'C');
            $pdf->Cell(50, 5, 'Asal', 1, 0, 'C');
            $pdf->Cell(50, 5, 'Ke', 1, 0, 'C');
            $pdf->Cell(20, 10, 'Paraf', 1, 0, 'C');

            $pdf->SetXY(85, 45);
            $pdf->Cell(25, 5, 'Dept', 1, 0, 'C');
            $pdf->Cell(25, 5, 'Seksi', 1, 0, 'C');
            $pdf->Cell(25, 5, 'Dept', 1, 0, 'C');
            $pdf->Cell(25, 5, 'Seksi', 1, 0, 'C');
            $pdf->Ln();

            $rowCount = 0; // Reset row count for new page
        }

        // Print record
        $pdf->Cell(10, 5, $no, 1, 0, 'C'); // Display serial number
        $pdf->Cell(15, 5, $emno, 1, 0, 'C');
        $pdf->Cell(50, 5, $nama, 1, 0, 'C');
        $pdf->Cell(25, 5, $cwocAsal, 1, 0, 'C');
        $pdf->Cell(25, 5, $sectAsalDesc, 1, 0, 'C');
        $pdf->Cell(25, 5, $cwocBaru, 1, 0, 'C');
        $pdf->Cell(25, 5, $sectBaruDesc, 1, 0, 'C');

        if ($cek == 1) {
            // Get current X and Y coordinates
            $x = $pdf->GetX();
            $y = $pdf->GetY();

            // Define cell width and height
            $cellWidth = 20;
            $cellHeight = 5;

            // Get image dimensions
            $imageWidth = 5; // width of the image in mm
            $imageHeight = 5; // height of the image in mm

            // Calculate the position to center the image
            $xPos = $x + ($cellWidth - $imageWidth) / 2;
            $yPos = $y + ($cellHeight - $imageHeight) / 2;

            // Add the image centered in the cell
            $pdf->Image('ceklis.png', $xPos, $yPos, $imageWidth, $imageHeight); // Adding centered image
            $pdf->Cell($cellWidth, $cellHeight, '', 1, 0, 'C'); // Empty text in cell
        } else {
            $pdf->Cell(20, 5, '', 1, 0, 'C'); // Empty cell if $cek != 1
        }

        $pdf->Ln();
        $no++; // Increment counter
        $rowCount++; // Increment row count
    }
}



$pdf->Output();
?>