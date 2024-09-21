<?php
// Load PHPExcel library
require_once __DIR__ . '/../asset/PHPExcel/Classes/PHPExcel.php';
require_once __DIR__ . '/../query/koneksi.php';


$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0); // Set aktif ke sheet pertama ('Real Time Man Power')
$objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri');
$objPHPExcel->getSheet(0)->setTitle('Hiring Data');

// Mengatur warna tab sheet
$objPHPExcel->getSheet(0)->getTabColor()->setRGB('00B0F0');

$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(35);
$objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(20);
$objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(26);
$objPHPExcel->getActiveSheet()->getRowDimension('5')->setRowHeight(23);
$objPHPExcel->getActiveSheet()->getRowDimension('6')->setRowHeight(30);

$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(27);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(37);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(14);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(13);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(29);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(12);

// Load gambar dari file
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Logo');
$objDrawing->setDescription('Logo');
$objDrawing->setPath('../asset/img/AOP.png'); // Ubah path ini sesuai dengan path gambar Anda
$objDrawing->setCoordinates('B2'); // Atur koordinat sel di mana gambar akan dimasukkan
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet()); // Masukkan gambar ke dalam lembar kerja

// Lakukan konfigurasi lainnya seperti mengatur dimensi gambar, jika diperlukan
$objDrawing->setHeight(300); // Atur tinggi gambar
$objDrawing->setWidth(300); // Atur lebar gambar

// Set the fill color for cell B4
$objPHPExcel->getActiveSheet()->getStyle('B4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('B4')->getFill()->getStartColor()->setRGB('2f74b5');

// Set the font color to white for cell B4
$objPHPExcel->getActiveSheet()->getStyle('B4')->getFont()->getColor()->setRGB('FFFFFF');

// Set the fill color for the range B6:L6
$objPHPExcel->getActiveSheet()->getStyle('B6:K6')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('B6:M6')->getFill()->getStartColor()->setRGB('2f74b5');

// Set the font color to white for the range B6:L6
$objPHPExcel->getActiveSheet()->getStyle('B4')->getFont()->getColor()->setRGB('FFFFFF');
$objPHPExcel->getActiveSheet()->getStyle('B6:K6')->getFont()->getColor()->setRGB('FFFFFF');


$objPHPExcel->setActiveSheetIndex(0);

$mergeCells = [
    'B4:D4',
    'B5:C5'
];

// Menggunakan array_unique untuk menghapus sel duplikat jika ada
$mergeCells = array_unique($mergeCells);

foreach ($mergeCells as $mergeRange) {
    $objPHPExcel->getActiveSheet()->mergeCells($mergeRange);
}

$objPHPExcel->getActiveSheet()
    ->getStyle('B4:K6')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('F:K')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('B6:K6')
    ->getFont()
    ->setBold(true);

$currentYear = date('Y');
$currentMonth = date('m');

// Define default month ranges based on the current month
if ($currentMonth >= 1 && $currentMonth <= 4) {
    $defaultMonthRange = '01-04';
} elseif ($currentMonth >= 5 && $currentMonth <= 8) {
    $defaultMonthRange = '05-08';
} else {
    $defaultMonthRange = '09-12';
}

// Set default value for month range if no filter is applied
$selectedMonth = isset($_GET['bulan']) ? $_GET['bulan'] : $defaultMonthRange;

// Filter data based on the selected month range and year, or show data for the current month range if no filter is applied
$selectedYear = isset($_GET['tahun']) ? $_GET['tahun'] : $currentYear;

// Parse start month and end month from the selected month range
$monthRange = explode("-", $selectedMonth);
$startMonth = date("F", mktime(0, 0, 0, $monthRange[0], 1));
$endMonth = date("F", mktime(0, 0, 0, $monthRange[1], 1));

$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B4', 'New Employee Hiring Database (All Golongan)')
    ->setCellValue('B5', 'Periode: ' . $startMonth . ' - ' . $endMonth . ' ' . $selectedYear)
    ->setCellValue('B6', 'No')
    ->setCellValue('C6', 'Nama')
    ->setCellValue('D6', 'Gender')
    ->setCellValue('E6', 'Asal Universitas')
    ->setCellValue('F6', 'Golongan')
    ->setCellValue('G6', 'Status')
    ->setCellValue('H6', 'Posisi')
    ->setCellValue('I6', 'Departemen')
    ->setCellValue('J6', 'Divisi')
    ->setCellValue('K6', 'Join Date');

// Add border to cells A1:D4 in the first sheet
$styleArray = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        ),
    ),
);

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

$currentMonth = date('m');

if (isset($_GET['show_all'])) {
    // Query to retrieve all mutation data without any filter on date
    $queryIn = "SELECT karyawan.*, nama.nama_karyawan, subsect.desc AS subsect_desc, sect.desc AS sect_desc 
                FROM karyawan 
                LEFT JOIN nama ON karyawan.emno = nama.emno 
                LEFT JOIN sect ON karyawan.sect = sect.Id_sect 
                LEFT JOIN subsect ON karyawan.subsect = subsect.id_subsect
                WHERE karyawan.resdate is NULL";
} else {

    // Set default value for month range if no filter is applied
    $selectedMonth = isset($_GET['bulan']) ? $_GET['bulan'] : $defaultMonthRange;

    // Filter data based on the selected month range and year, or show data for the current month range if no filter is applied
    $selectedYear = isset($_GET['tahun']) ? $_GET['tahun'] : $currentYear;

    // Parse start month and end month from the selected month range
    $monthRange = explode("-", $selectedMonth);
    $startMonth = $monthRange[0];
    $endMonth = $monthRange[1];

    // Query to retrieve mutation data based on the selected month range and year
    $queryIn = "SELECT karyawan.*, nama.nama_karyawan, subsect.desc AS subsect_desc, sect.desc AS sect_desc 
                FROM karyawan 
                LEFT JOIN nama ON karyawan.emno = nama.emno 
                LEFT JOIN sect ON karyawan.sect = sect.Id_sect 
                LEFT JOIN subsect ON karyawan.subsect = subsect.id_subsect
                WHERE karyawan.resdate is NULL AND MONTH(karyawan.joindate) BETWEEN '$startMonth' AND '$endMonth' AND YEAR(karyawan.joindate) = '$selectedYear'";
}

// Execute the query
$resultIn = mysqli_query($koneksi, $queryIn);
if (!$resultIn) {
    die("Query error: " . mysqli_error($koneksi));
}

$no = 1; // Start number
$start_row = 7; // Start row number for data

// Terapkan border ke baris 6 terlebih dahulu
$objPHPExcel->getActiveSheet()->getStyle('B6:K6')->applyFromArray($styleArray);

while ($user_data = mysqli_fetch_array($resultIn)) {
    $nama = isset($user_data['nama_karyawan']) ? $user_data['nama_karyawan'] : null;
    $sexe = isset($user_data['sexe']) ? ($user_data['sexe'] == 'P' ? 'Perempuan' : ($user_data['sexe'] == 'L' ? 'Laki-laki' : null)) : null;
    $gol = isset($user_data['gol']) ? $user_data['gol'] : null;
    $status = isset($user_data['emno']) ? (
        strpos($user_data['emno'], 'K') === 0 ? 'Kontrak' :
        (preg_match('/^0/', $user_data['emno']) || preg_match('/^[0-9]+$/', $user_data['emno']) ? 'Permanen' :
            (strpos($user_data['emno'], 'P') === 0 ? 'Trainee' : 'Unknown'))) : null;
    // Logika untuk posisi berdasarkan gol
    $posisi = isset($user_data['gol']) ? (
        $user_data['gol'] >= 0 && $user_data['gol'] <= 2 ? 'Operator' :
        ($user_data['gol'] == 3 ? 'Foreman' :
            ($user_data['gol'] == 4 ? 'Supervisor' :
                ($user_data['gol'] == 5 ? 'Manager' :
                    ($user_data['gol'] == 6 ? 'BOD' : 'Unknown'))))
    ) : null;
    $cwoc = isset($user_data['cwoc']) ? $user_data['cwoc'] : null;
    $joindate = isset($user_data['joindate']) ? date('d/M/y', strtotime($user_data['joindate'])) : null;

    // Set cell values
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B' . ($no + $start_row - 1), $no) // Set the row number in column B
        ->setCellValue('C' . ($no + $start_row - 1), $nama)
        ->setCellValue('D' . ($no + $start_row - 1), $sexe)
        ->setCellValue('F' . ($no + $start_row - 1), $gol)
        ->setCellValue('G' . ($no + $start_row - 1), $status)
        ->setCellValue('H' . ($no + $start_row - 1), $posisi)
        ->setCellValue('I' . ($no + $start_row - 1), $cwoc)
        ->setCellValue('K' . ($no + $start_row - 1), $joindate);

    // Terapkan gaya tepi (border) ke baris saat ini
    $currentRow = $no + $start_row - 1;
    $objPHPExcel->getActiveSheet()->getStyle('B' . $currentRow . ':K' . $currentRow)->applyFromArray($styleArray);

    $no++; // Increment number
}

$objPHPExcel->createSheet(); // Membuat sheet baru
$objPHPExcel->setActiveSheetIndex(1); // Set aktif ke sheet baru

// Mengatur judul lembar baru
$objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri');
$objPHPExcel->getSheet(1)->setTitle('Turnover Data'); // Mengatur judul lembar pertama
$objPHPExcel->getSheet(1)->getTabColor()->setRGB('92D050');

$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(35);
$objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(20);
$objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(26);
$objPHPExcel->getActiveSheet()->getRowDimension('5')->setRowHeight(23);
$objPHPExcel->getActiveSheet()->getRowDimension('6')->setRowHeight(30);

$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(27);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(14);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(13);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(29);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(31);

// Set the fill color for cell B4
$objPHPExcel->getActiveSheet()->getStyle('B4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('B4')->getFill()->getStartColor()->setRGB('2f74b5');

// Set the font color to white for cell B4
$objPHPExcel->getActiveSheet()->getStyle('B4')->getFont()->getColor()->setRGB('FFFFFF');

// Set the fill color for the range B6:L6
$objPHPExcel->getActiveSheet()->getStyle('B6:K6')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('B6:M6')->getFill()->getStartColor()->setRGB('2f74b5');

// Set the font color to white for the range B6:L6
$objPHPExcel->getActiveSheet()->getStyle('B4')->getFont()->getColor()->setRGB('FFFFFF');
$objPHPExcel->getActiveSheet()->getStyle('B6:K6')->getFont()->getColor()->setRGB('FFFFFF');

$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Logo');
$objDrawing->setDescription('Logo');
$objDrawing->setPath('../asset/img/AOP.png'); // Ubah path ini sesuai dengan path gambar Anda
$objDrawing->setCoordinates('B2'); // Atur koordinat sel di mana gambar akan dimasukkan
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet()); // Masukkan gambar ke dalam lembar kerja

// Lakukan konfigurasi lainnya seperti mengatur dimensi gambar, jika diperlukan
$objDrawing->setHeight(300); // Atur tinggi gambar
$objDrawing->setWidth(300); // Atur lebar gambar



$objPHPExcel->setActiveSheetIndex(1);

$mergeCells = [
    'B4:D4',
    'B5:C5'
];


// Menggunakan array_unique untuk menghapus sel duplikat jika ada
$mergeCells = array_unique($mergeCells);

foreach ($mergeCells as $mergeRange) {
    $objPHPExcel->getActiveSheet()->mergeCells($mergeRange);
}

$objPHPExcel->getActiveSheet()
    ->getStyle('B4:K6')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('E:I')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('B6:K6')
    ->getFont()
    ->setBold(true);


// Add border to cells A1:D4 in the first sheet
$styleArray = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        ),
    ),
);

$currentYear = date('Y');
$currentMonth = date('m');

// Define default month ranges based on the current month
if ($currentMonth >= 1 && $currentMonth <= 4) {
    $defaultMonthRange = '01-04';
} elseif ($currentMonth >= 5 && $currentMonth <= 8) {
    $defaultMonthRange = '05-08';
} else {
    $defaultMonthRange = '09-12';
}

// Set default value for month range if no filter is applied
$selectedMonth = isset($_GET['bulan']) ? $_GET['bulan'] : $defaultMonthRange;

// Filter data based on the selected month range and year, or show data for the current month range if no filter is applied
$selectedYear = isset($_GET['tahun']) ? $_GET['tahun'] : $currentYear;

// Parse start month and end month from the selected month range
$monthRange = explode("-", $selectedMonth);
$startMonth = date("F", mktime(0, 0, 0, $monthRange[0], 1));
$endMonth = date("F", mktime(0, 0, 0, $monthRange[1], 1));

$objPHPExcel->setActiveSheetIndex(1)
    ->setCellValue('B4', 'Turnover Database(All Golongan)')
    ->setCellValue('B5', 'Periode: ' . $startMonth . ' - ' . $endMonth . ' ' . $selectedYear)
    ->setCellValue('B6', 'No')
    ->setCellValue('C6', 'Nama')
    ->setCellValue('D6', 'Gender')
    ->setCellValue('E6', 'Golongan')
    ->setCellValue('F6', 'Status')
    ->setCellValue('G6', 'Posisi')
    ->setCellValue('H6', 'Departemen')
    ->setCellValue('I6', 'Divisi')
    ->setCellValue('J6', 'Last Efective Date')
    ->setCellValue('K6', 'Reason');


// Add border to cells A1:D4 in the first sheet
$styleArray = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        ),
    ),
);

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

$currentMonth = date('m');

if (isset($_GET['show_all'])) {
    // Query to retrieve all mutation data without any filter on date
    $queryOut = "SELECT karyawan.*, nama.nama_karyawan, subsect.desc AS subsect_desc, sect.desc AS sect_desc 
                FROM karyawan 
                LEFT JOIN nama ON karyawan.emno = nama.emno 
                LEFT JOIN sect ON karyawan.sect = sect.Id_sect 
                LEFT JOIN subsect ON karyawan.subsect = subsect.id_subsect
                WHERE karyawan.resdate is NOT NULL";
} else {
    // Set default value for month range if no filter is applied
    $selectedMonth = isset($_GET['bulan']) ? $_GET['bulan'] : $defaultMonthRange;

    // Filter data based on the selected month range and year, or show data for the current month range if no filter is applied
    $selectedYear = isset($_GET['tahun']) ? $_GET['tahun'] : $currentYear;

    // Parse start month and end month from the selected month range
    $monthRange = explode("-", $selectedMonth);
    $startMonth = $monthRange[0];
    $endMonth = $monthRange[1];

    // Query to retrieve mutation data based on the selected month range and year
    $queryOut = "SELECT karyawan.*, nama.nama_karyawan, subsect.desc AS subsect_desc, sect.desc AS sect_desc 
                FROM karyawan 
                LEFT JOIN nama ON karyawan.emno = nama.emno 
                LEFT JOIN sect ON karyawan.sect = sect.Id_sect 
                LEFT JOIN subsect ON karyawan.subsect = subsect.id_subsect
                WHERE karyawan.resdate is NOT NULL AND MONTH(karyawan.resdate) BETWEEN '$startMonth' AND '$endMonth' AND YEAR(karyawan.resdate) = '$selectedYear'";
}

// Execute the query
$resultOut = mysqli_query($koneksi, $queryOut);
if (!$resultOut) {
    die("Query error: " . mysqli_error($koneksi));
}

$no = 1; // Start number
$start_row = 7; // Start row number for data

// Terapkan border ke baris 6 terlebih dahulu
$objPHPExcel->getActiveSheet()->getStyle('B6:K6')->applyFromArray($styleArray);

while ($user_data = mysqli_fetch_array($resultOut)) {
    $nama = isset($user_data['nama_karyawan']) ? $user_data['nama_karyawan'] : null;
    $sexe = isset($user_data['sexe']) ? ($user_data['sexe'] == 'P' ? 'Perempuan' : ($user_data['sexe'] == 'L' ? 'Laki-laki' : null)) : null;
    $gol = isset($user_data['gol']) ? $user_data['gol'] : null;
    $status = isset($user_data['emno']) ? (
        strpos($user_data['emno'], 'K') === 0 ? 'Kontrak' :
        (preg_match('/^0/', $user_data['emno']) || preg_match('/^[0-9]+$/', $user_data['emno']) ? 'Permanen' :
            (strpos($user_data['emno'], 'P') === 0 ? 'Trainee' : 'Unknown'))) : null;
    // Logika untuk posisi berdasarkan gol
    $posisi = isset($user_data['gol']) ? (
        $user_data['gol'] >= 0 && $user_data['gol'] <= 2 ? 'Operator' :
        ($user_data['gol'] == 3 ? 'Foreman' :
            ($user_data['gol'] == 4 ? 'Supervisor' :
                ($user_data['gol'] == 5 ? 'Manager' :
                    ($user_data['gol'] == 6 ? 'BOD' : 'Unknown'))))
    ) : null;
    $cwoc = isset($user_data['cwoc']) ? $user_data['cwoc'] : null;
    $resdate = isset($user_data['resdate']) ? date('d/M/y', strtotime($user_data['resdate'])) : null;
    $reason = isset($user_data['reason']) ? $user_data['reason'] : null;

    // Set cell values
    $objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('B' . ($no + $start_row - 1), $no) // Set the row number in column B
        ->setCellValue('C' . ($no + $start_row - 1), $nama)
        ->setCellValue('D' . ($no + $start_row - 1), $sexe)
        ->setCellValue('E' . ($no + $start_row - 1), $gol)
        ->setCellValue('F' . ($no + $start_row - 1), $status)
        ->setCellValue('G' . ($no + $start_row - 1), $posisi)
        ->setCellValue('H' . ($no + $start_row - 1), $cwoc)
        ->setCellValue('J' . ($no + $start_row - 1), $resdate)
        ->setCellValue('K' . ($no + $start_row - 1), $reason);

    // Terapkan gaya tepi (border) ke baris saat ini
    $currentRow = $no + $start_row - 1;
    $objPHPExcel->getActiveSheet()->getStyle('B' . $currentRow . ':K' . $currentRow)->applyFromArray($styleArray);

    $no++; // Increment number
}

if (isset($_GET['show_all'])) {
    $nama_file = "PTKYBI-Hiring Turnover Database - AOP Group all.xls";
} else {
    $nama_bulan_start = date("F", mktime(0, 0, 0, $startMonth, 1));
    $nama_bulan_end = date("F", mktime(0, 0, 0, $endMonth, 1));

    $nama_file = "PTKYBI-Hiring Turnover Database - AOP Group $selectedYear ($nama_bulan_start - $nama_bulan_end).xls";
}

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