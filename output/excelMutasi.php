<?php
// Load PHPExcel library
require_once __DIR__ . '/../asset/PHPExcel/Classes/PHPExcel.php';
require_once __DIR__ . '/../query/koneksi.php';
if (isset($_GET['show_all'])) {
    // Query to retrieve all mutation data without any filter on date
    $queryMutasi = "SELECT *
                FROM mutasi
                WHERE hapus IS NULL
                AND status = '10'";
} else {
    $currentMonth = date('m');
    $currentYear = date('Y');

    // Filter data based on the selected month and year, or show data for the current month if no filter is applied
    $selectedMonth = isset($_GET['bulan']) ? $_GET['bulan'] : $currentMonth;
    $selectedYear = isset($_GET['tahun']) ? $_GET['tahun'] : $currentYear;

    // Adjust previous month and year based on the selected month
    if ($selectedMonth == 1) {
        // If the selected month is January, adjust to the previous year
        $previousMonth = 12;
        $previousYear = $selectedYear - 1;
    } else {
        // Otherwise, just decrement the month
        $previousMonth = $selectedMonth - 1;
        $previousYear = $selectedYear;
    }

    $previousMonthTimestamp = mktime(0, 0, 0, $previousMonth, 10, $previousYear);
    $previousMonthName = date('F', $previousMonthTimestamp);


    // Query to retrieve mutation data based on the selected month and year
    $queryMutasi = "SELECT *
                FROM mutasi
                WHERE hapus IS NULL
                AND status = '10' AND
                     MONTH(mutasi.tanggalMutasi) = '$selectedMonth' AND YEAR(mutasi.tanggalMutasi) = '$selectedYear'";
}

$resultMutasi = mysqli_query($koneksi3, $queryMutasi);
if (!$resultMutasi) {
    die("Query error: " . mysqli_error($koneksi3));
}


$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0); // Set aktif ke sheet pertama ('Real Time Man Power')
$objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri');
$objPHPExcel->getSheet(0)->setTitle('Daftar Mutasi'); // Mengatur judul lembar pertama


$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(5);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(48);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(17);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(21);

$objPHPExcel->getActiveSheet()->getStyle('B4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('B4')->getFill()->getStartColor()->setRGB('D9E1F2');

$objPHPExcel->getActiveSheet()->mergeCells('B4:J4');
$objPHPExcel->getActiveSheet()->mergeCells('B5:B6');
$objPHPExcel->getActiveSheet()->mergeCells('C5:C6');
$objPHPExcel->getActiveSheet()->mergeCells('D5:D6');
$objPHPExcel->getActiveSheet()->mergeCells('E5:E6');
$objPHPExcel->getActiveSheet()->mergeCells('F5:G5');
$objPHPExcel->getActiveSheet()->mergeCells('H5:I5');
$objPHPExcel->getActiveSheet()->mergeCells('J5:J6');

$objPHPExcel->getActiveSheet()
    ->getStyle('B4:J6')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('B:J')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('B4:J6')
    ->getFont()
    ->setBold(true);

$currentMonth = date('m');
$currentYear = date('Y');

// Filter data based on the selected month and year, or show data for the current month if no filter is applied
$selectedMonth = isset($_GET['bulan']) ? $_GET['bulan'] : $currentMonth;
$selectedYear = isset($_GET['tahun']) ? $_GET['tahun'] : $currentYear;

// Convert the selected month number to the month name
$selectedMonthName = date('F', mktime(0, 0, 0, $selectedMonth, 10));

$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B4', "Daftar Mutasi: $selectedMonthName $selectedYear")
    ->setCellValue('B5', 'No')
    ->setCellValue('C5', 'NPK')
    ->setCellValue('D5', 'Nama')
    ->setCellValue('E5', 'Tanggal Buat')
    ->setCellValue('F5', 'Asal')
    ->setCellValue('H5', 'Tujuan')
    ->setCellValue('F6', 'Departemen')
    ->setCellValue('G6', 'Seksi')
    ->setCellValue('H6', 'Departemen')
    ->setCellValue('I6', 'Seksi')
    ->setCellValue('J5', 'Tanggal Efektif');

$styleArray = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        ),
    ),
);

// Terapkan gaya tepi di dalam sel (tipis)
$objPHPExcel->getActiveSheet()->getStyle('B5:J6')->applyFromArray($styleArray);

// Definisikan gaya tepi (border) tipis
$styleArray = [
    'borders' => [
        'allborders' => [
            'style' => PHPExcel_Style_Border::BORDER_THIN
        ]
    ]
];

$sheet = $objPHPExcel->getActiveSheet();
$sheet->freezePane('A7');



$no = 1; // Start number
$start_row = 7; // Start row number for data

// Terapkan border ke baris 6 terlebih dahulu
$objPHPExcel->getActiveSheet()->getStyle('B7:J7')->applyFromArray($styleArray);

while ($user_data = mysqli_fetch_array($resultMutasi)) {
    $emno = isset($user_data['emno']) ? $user_data['emno'] : null;
    $nama = isset($user_data['nama']) ? $user_data['nama'] : null;

    $cwocAsal = isset($user_data['cwocAsal']) ? $user_data['cwocAsal'] : null;
    $cwocBaru = isset($user_data['cwocBaru']) ? $user_data['cwocBaru'] : null;
    $tanggalMutasi = isset($user_data['tanggalMutasi']) ? date('d/M/y', strtotime($user_data['tanggalMutasi'])) : null;
    $tanggalBuat = isset($user_data['tanggalBuat']) ? date('d/M/y', strtotime($user_data['tanggalBuat'])) : null;

    $sectAsal = isset($user_data['sectAsal']) ? $user_data['sectAsal'] : null;
    $sectBaru = isset($user_data['sectBaru']) ? $user_data['sectBaru'] : null;
    // Tampilkan data dalam tabel
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

    // Set cell values
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B' . ($no + $start_row - 1), $no) // Set the row number in column B
        ->setCellValue('C' . ($no + $start_row - 1), $emno)
        ->setCellValue('D' . ($no + $start_row - 1), $nama)
        ->setCellValue('E' . ($no + $start_row - 1), $tanggalBuat)
        ->setCellValue('F' . ($no + $start_row - 1), $row['sectAsalDesc'])
        ->setCellValue('G' . ($no + $start_row - 1), $row['sectBaruDesc'])
        ->setCellValue('H' . ($no + $start_row - 1), $cwocAsal)
        ->setCellValue('I' . ($no + $start_row - 1), $cwocBaru)
        ->setCellValue('J' . ($no + $start_row - 1), $tanggalMutasi);

    // Terapkan gaya tepi (border) ke baris saat ini
    $currentRow = $no + $start_row - 1;
    $objPHPExcel->getActiveSheet()->getStyle('B' . $currentRow . ':J' . $currentRow)->applyFromArray($styleArray);

    $no++; // Increment number
}



$nama_file = "Daftar Mutasi_$selectedMonthName $selectedYear.xls";

$objPHPExcel->setActiveSheetIndex(0);

// Set header dengan nama file yang telah dibuat
header("Content-Disposition: attachment;filename=\"$nama_file\"");
header('Content-Type: application/vnd.ms-excel'); // Mengatur tipe konten untuk format xls
header('Cache-Control: max-age=0');

// Menyimpan file dalam format Excel 97-2003 (.xls) dan mengirimkannya ke output
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>