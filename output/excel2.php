<?php
// Load PHPExcel library
require_once __DIR__ . '/../asset/PHPExcel/Classes/PHPExcel.php';
require_once __DIR__ . '/../query/koneksi.php';
require_once __DIR__ . '/../query/query.php';
require_once __DIR__ . '/../query/queryYos.php';
require_once __DIR__ . '/../query/queryStatusOut.php';


$tanggal_hari_ini = date("d F Y");

$currentMonth = date('m');
$currentYear = date('Y');

// Mendapatkan bulan dan tahun yang dipilih dari query string
$selectedMonth = isset($_GET['bulan']) ? (int) $_GET['bulan'] : (int) $currentMonth;
$selectedYear = isset($_GET['tahun']) ? (int) $_GET['tahun'] : (int) $currentYear;

// Mendapatkan tanggal terakhir dari bulan yang dipilih
$endDate = date('Y-m-d', strtotime("last day of $selectedYear-$selectedMonth +1 day"));

// Menghitung bulan dan tahun sebelumnya
if ($selectedMonth == 1) {
    $previousMonth = 12; // Januari sebelumnya adalah Desember
    $previousYear = $selectedYear - 1;
} else {
    $previousMonth = $selectedMonth - 1; // Bulan sebelumnya
    $previousYear = $selectedYear;
}

// Mendapatkan tanggal terakhir dari bulan sebelumnya
$endDate2 = date('Y-m-d', strtotime("last day of $previousYear-$previousMonth +1 day"));

$selectedMonthName = date('F', mktime(0, 0, 0, $selectedMonth, 10));
$previousMonthTimestamp = mktime(0, 0, 0, $selectedMonth - 1, 10);
$previousMonthName = date('F', $previousMonthTimestamp);

// Eksekusi kueri menggunakan objek koneksi dari file koneksi.php
$resultUmur = mysqli_query($koneksi, $queryUmur);
$resultMK = mysqli_query($koneksi, $queryMK);
$resultJK = mysqli_query($koneksi, $queryJK);
$resultPend = mysqli_query($koneksi, $queryPend);

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0); // Set aktif ke sheet pertama ('Real Time Man Power')
$objPHPExcel->getDefaultStyle()->getFont()->setName('Times New Roman');
$objPHPExcel->getSheet(0)->setTitle('Real Time Man Power'); // Mengatur judul lembar pertama
$sheet = $objPHPExcel->getActiveSheet();
$sheet->getDefaultColumnDimension()->setWidth(12); // Set the default width to 15 units
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(17);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(23);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(27);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(27);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(8);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(29);
$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(11);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(13);
$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(13);

//MERGECELL
$objPHPExcel->getActiveSheet()->mergeCells('B3:B4');
$objPHPExcel->getActiveSheet()->mergeCells('C3:D3');
$objPHPExcel->getActiveSheet()->mergeCells('C12:D12');
$objPHPExcel->getActiveSheet()->mergeCells('K1:Y1');
$objPHPExcel->getActiveSheet()->mergeCells('K2:M2');
$objPHPExcel->getActiveSheet()->mergeCells('K3:K5');
$objPHPExcel->getActiveSheet()->mergeCells('L3:L5');
$objPHPExcel->getActiveSheet()->mergeCells('M3:Y3');
$objPHPExcel->getActiveSheet()->mergeCells('W4:Y4');
$objPHPExcel->getActiveSheet()->mergeCells('K35:L35');
$objPHPExcel->getActiveSheet()->mergeCells('M4:M5');
$objPHPExcel->getActiveSheet()->mergeCells('B28:B29');
$objPHPExcel->getActiveSheet()->mergeCells('C28:D28');
$objPHPExcel->getActiveSheet()->mergeCells('C36:D36');
$objPHPExcel->getActiveSheet()->mergeCells('Q4:R4');
$objPHPExcel->getActiveSheet()->mergeCells('S4:T4');
$objPHPExcel->getActiveSheet()->mergeCells('V4:V5');
$objPHPExcel->getActiveSheet()->mergeCells('O4:P4');
$objPHPExcel->getActiveSheet()->mergeCells('W36:X36');
$objPHPExcel->getActiveSheet()->mergeCells('W37:X37');
$objPHPExcel->getActiveSheet()->mergeCells('B39:B40');
$objPHPExcel->getActiveSheet()->mergeCells('C39:D39');
$objPHPExcel->getActiveSheet()->mergeCells('E39:F39');
$objPHPExcel->getActiveSheet()->mergeCells('G39:H39');
$objPHPExcel->getActiveSheet()->mergeCells('I39:I40');
$objPHPExcel->getActiveSheet()->mergeCells('B53:B55');
$objPHPExcel->getActiveSheet()->mergeCells('C53:R53');
$objPHPExcel->getActiveSheet()->mergeCells('S53:S55');
$objPHPExcel->getActiveSheet()->mergeCells('S56:S57');
$objPHPExcel->getActiveSheet()->mergeCells('C54:D54');
$objPHPExcel->getActiveSheet()->mergeCells('E54:F54');
$objPHPExcel->getActiveSheet()->mergeCells('G54:H54');
$objPHPExcel->getActiveSheet()->mergeCells('I54:J54');
$objPHPExcel->getActiveSheet()->mergeCells('K54:L54');
$objPHPExcel->getActiveSheet()->mergeCells('M54:N54');
$objPHPExcel->getActiveSheet()->mergeCells('O54:P54');
$objPHPExcel->getActiveSheet()->mergeCells('Q54:R54');

$objPHPExcel->getActiveSheet()->mergeCells('C57:D57');
$objPHPExcel->getActiveSheet()->mergeCells('E57:F57');
$objPHPExcel->getActiveSheet()->mergeCells('G57:H57');
$objPHPExcel->getActiveSheet()->mergeCells('I57:J57');
$objPHPExcel->getActiveSheet()->mergeCells('K57:L57');
$objPHPExcel->getActiveSheet()->mergeCells('M57:N57');
$objPHPExcel->getActiveSheet()->mergeCells('O57:P57');
$objPHPExcel->getActiveSheet()->mergeCells('Q57:R57');

$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B3', 'UMUR')
    ->setCellValue('C3', 'QTY')
    ->setCellValue('C4', 'Pria')
    ->setCellValue('D4', 'Wanita')
    ->setCellValue('B5', '<18')
    ->setCellValue('C5', $jumlah_pria_kurang_dari_18)
    ->setCellValue('D5', $jumlah_perempuan_kurang_dari_18)
    ->setCellValue('B6', '18 - 25')
    ->setCellValue('C6', $jumlah_pria_18_25)
    ->setCellValue('D6', $jumlah_perempuan_18_25)
    ->setCellValue('B7', '26 - 35')
    ->setCellValue('C7', $jumlah_pria_26_35)
    ->setCellValue('D7', $jumlah_perempuan_26_35)
    ->setCellValue('B8', '36 - 45')
    ->setCellValue('C8', $jumlah_pria_36_45)
    ->setCellValue('D8', $jumlah_perempuan_36_45)
    ->setCellValue('B9', '46 - 55')
    ->setCellValue('C9', $jumlah_pria_46_55)
    ->setCellValue('D9', $jumlah_perempuan_46_55)
    ->setCellValue('B10', '>55')
    ->setCellValue('C10', $jumlah_pria_lebih_dari_55)
    ->setCellValue('D10', $jumlah_perempuan_lebih_dari_55)
    ->setCellValue('B11', 'Sub Total')
    ->setCellValue('C11', $jumlah_pria_kurang_dari_18 + $jumlah_pria_18_25 + $jumlah_pria_26_35 + $jumlah_pria_36_45 + $jumlah_pria_46_55 + $jumlah_pria_lebih_dari_55)
    ->setCellValue('D11', $jumlah_perempuan_kurang_dari_18 + $jumlah_perempuan_18_25 + $jumlah_perempuan_26_35 + $jumlah_perempuan_36_45 + $jumlah_perempuan_46_55 + $jumlah_perempuan_lebih_dari_55)
    ->setCellValue('B12', 'Total')
    ->setCellValue('C12', $jumlah_pria_kurang_dari_18 + $jumlah_pria_18_25 + $jumlah_pria_26_35 + $jumlah_pria_36_45 + $jumlah_pria_46_55 + $jumlah_pria_lebih_dari_55 + $jumlah_perempuan_kurang_dari_18 + $jumlah_perempuan_18_25 + $jumlah_perempuan_26_35 + $jumlah_perempuan_36_45 + $jumlah_perempuan_46_55 + $jumlah_perempuan_lebih_dari_55);



// Add background color to specific cells
$objPHPExcel->getActiveSheet()->getStyle('B3:D4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('B3:D4')->getFill()->getStartColor()->setRGB('FCE4D6');

$objPHPExcel->getActiveSheet()->getStyle('C12:D12')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('C12:D12')->getFill()->getStartColor()->setRGB('FCE4D6');

$objPHPExcel->getActiveSheet()->getStyle('B53:S55')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('B53:S55')->getFill()->getStartColor()->setRGB('BDD7EE');



$objPHPExcel->getActiveSheet()
    ->getStyle('B3')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('B39:I49')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('B53:S57')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('F8:H8')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('F13:G13')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('F18:I18')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('F24:I24')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('F33:I33')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('F33:I33')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('G9:H11')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('G19:I22')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('G25:I31')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('G34:I37')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('B3:D12')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('B3:D4')
    ->getFont()
    ->setBold(true);

$objPHPExcel->getActiveSheet()
    ->getStyle('B11')
    ->getFont()
    ->setBold(true);

$objPHPExcel->getActiveSheet()
    ->getStyle('B12:C12')
    ->getFont()
    ->setBold(true);

$objPHPExcel->getActiveSheet()
    ->getStyle('B39:I40')
    ->getFont()
    ->setBold(true);

$objPHPExcel->getActiveSheet()
    ->getStyle('B49:I49')
    ->getFont()
    ->setBold(true);

$objPHPExcel->getActiveSheet()
    ->getStyle('B53:S55')
    ->getFont()
    ->setBold(true);

$objPHPExcel->getActiveSheet()
    ->getStyle('B57')
    ->getFont()
    ->setBold(true);

$objPHPExcel->getActiveSheet()
    ->getStyle('F8:H8')
    ->getFont()
    ->setBold(true);

$objPHPExcel->getActiveSheet()
    ->getStyle('F11:H11')
    ->getFont()
    ->setBold(true);

$objPHPExcel->getActiveSheet()
    ->getStyle('F13:G13')
    ->getFont()
    ->setBold(true);

$objPHPExcel->getActiveSheet()
    ->getStyle('F16:G11')
    ->getFont()
    ->setBold(true);

$objPHPExcel->getActiveSheet()
    ->getStyle('F18:I18')
    ->getFont()
    ->setBold(true);

$objPHPExcel->getActiveSheet()
    ->getStyle('F22:I24')
    ->getFont()
    ->setBold(true);

$objPHPExcel->getActiveSheet()
    ->getStyle('F33:I33')
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
$styleArrayOutside = array(
    'borders' => array(
        'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_THICK,
        ),
    ),
);

// Terapkan gaya tepi di dalam sel (tipis)
$objPHPExcel->getActiveSheet()->getStyle('B3:D12')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('B39:I49')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('B53:S57')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('F8:H11')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('F13:G16')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('F18:I22')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('F24:I31')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('F33:I37')->applyFromArray($styleArray);

// Terapkan gaya tepi di luar sel (tebal)
$objPHPExcel->getActiveSheet()->getStyle('B3:D12')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('B11:D11')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('B12:D12')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('B39:I49')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('B49:I49')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('B53:S57')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('F8:H11')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('F13:G16')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('F18:I22')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('F24:I31')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('F33:I37')->applyFromArray($styleArrayOutside);


//MASA KERJA
$objPHPExcel->getActiveSheet()->mergeCells('B16:B17');
$objPHPExcel->getActiveSheet()->mergeCells('C16:D16');
$objPHPExcel->getActiveSheet()->mergeCells('C26:D26');

$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B16', 'Masa Kerja')
    ->setCellValue('C16', 'QTY')
    ->setCellValue('C17', 'Pria')
    ->setCellValue('D17', 'Wanita')
    ->setCellValue('B18', '<5')
    ->setCellValue('C18', $jumlah_pria_kurang_dari_5)
    ->setCellValue('D18', $jumlah_perempuan_kurang_dari_5)
    ->setCellValue('B19', '6 - 10')
    ->setCellValue('C19', $jumlah_pria_6_10)
    ->setCellValue('D19', $jumlah_perempuan_6_10)
    ->setCellValue('B20', '11 - 15')
    ->setCellValue('C20', $jumlah_pria_11_15)
    ->setCellValue('D20', $jumlah_perempuan_11_15)
    ->setCellValue('B21', '16 - 20')
    ->setCellValue('C21', $jumlah_pria_16_20)
    ->setCellValue('D21', $jumlah_perempuan_16_20)
    ->setCellValue('B22', '21 - 25')
    ->setCellValue('C22', $jumlah_pria_21_25)
    ->setCellValue('D22', $jumlah_perempuan_21_25)
    ->setCellValue('B23', '26 - 30')
    ->setCellValue('C23', $jumlah_pria_26_30)
    ->setCellValue('D23', $jumlah_perempuan_26_30)
    ->setCellValue('B24', '>30')
    ->setCellValue('C24', $jumlah_pria_lebih_dari_30)
    ->setCellValue('D24', $jumlah_perempuan_lebih_dari_30)
    ->setCellValue('B25', 'Sub Total')
    ->setCellValue('C25', $jumlah_pria_kurang_dari_5 + $jumlah_pria_6_10 + $jumlah_pria_11_15 + $jumlah_pria_16_20 + $jumlah_pria_21_25 + $jumlah_pria_26_30 + $jumlah_pria_lebih_dari_30)
    ->setCellValue('D25', $jumlah_perempuan_kurang_dari_5 + $jumlah_perempuan_6_10 + $jumlah_perempuan_11_15 + $jumlah_perempuan_16_20 + $jumlah_perempuan_21_25 + $jumlah_perempuan_26_30 + $jumlah_perempuan_lebih_dari_30)
    ->setCellValue('B26', 'Total')
    ->setCellValue('C26', $jumlah_pria_kurang_dari_5 + $jumlah_pria_6_10 + $jumlah_pria_11_15 + $jumlah_pria_16_20 + $jumlah_pria_21_25 + $jumlah_pria_26_30 + $jumlah_pria_lebih_dari_30 + $jumlah_perempuan_kurang_dari_5 + $jumlah_perempuan_6_10 + $jumlah_perempuan_11_15 + $jumlah_perempuan_16_20 + $jumlah_perempuan_21_25 + $jumlah_perempuan_26_30 + $jumlah_perempuan_lebih_dari_30);


// Add background color to specific cells
$objPHPExcel->getActiveSheet()->getStyle('B16:D17')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('B16:D17')->getFill()->getStartColor()->setRGB('FCE4D6');

$objPHPExcel->getActiveSheet()->getStyle('C26:D26')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('C26:D26')->getFill()->getStartColor()->setRGB('FCE4D6');

$objPHPExcel->getActiveSheet()
    ->getStyle('B16')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('B16:D26')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('B16:D17')
    ->getFont()
    ->setBold(true);

$objPHPExcel->getActiveSheet()
    ->getStyle('B25')
    ->getFont()
    ->setBold(true);

$objPHPExcel->getActiveSheet()
    ->getStyle('B26:C26')
    ->getFont()
    ->setBold(true);

$objPHPExcel->getActiveSheet()
    ->getStyle('G16')
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
$styleArrayOutside = array(
    'borders' => array(
        'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_THICK,
        ),
    ),
);

// Terapkan gaya tepi di dalam sel (tipis)
$objPHPExcel->getActiveSheet()->getStyle('B16:D26')->applyFromArray($styleArray);

// Terapkan gaya tepi di luar sel (tebal)
$objPHPExcel->getActiveSheet()->getStyle('B16:D26')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('B25:D25')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('B26:D26')->applyFromArray($styleArrayOutside);

//jk
foreach ($resultJK as $row) {
    $pria = $row['Pria'];
    $perempuan = $row['Perempuan'];
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('F3', 'Gender')
        ->setCellValue('G3', 'Jumlah')
        ->setCellValue('F4', 'Pria')
        ->setCellValue('G4', $pria)
        ->setCellValue('F5', 'Wanita')
        ->setCellValue('G5', $perempuan)
        ->setCellValue('F6', 'Total')
        ->setCellValue('G6', $perempuan + $pria);
}

$objPHPExcel->getActiveSheet()->getStyle('F3:G3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('F3:G3')->getFill()->getStartColor()->setRGB('FCE4D6');


$objPHPExcel->getActiveSheet()->getStyle('B52')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('B52')->getFill()->getStartColor()->setRGB('FFFF00');

$objPHPExcel->getActiveSheet()->getStyle('B49:I49')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('B49:I49')->getFill()->getStartColor()->setRGB('BDD7EE');

$objPHPExcel->getActiveSheet()->getStyle('F11:H11')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('F11:H11')->getFill()->getStartColor()->setRGB('BDD7EE');

$objPHPExcel->getActiveSheet()->getStyle('F16:G16')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('F16:G16')->getFill()->getStartColor()->setRGB('BDD7EE');

$objPHPExcel->getActiveSheet()->getStyle('F22:I22')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('F22:I22')->getFill()->getStartColor()->setRGB('BDD7EE');

$objPHPExcel->getActiveSheet()->getStyle('B39:I40')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('B39:I40')->getFill()->getStartColor()->setRGB('F2DCDB');

$objPHPExcel->getActiveSheet()->getStyle('F8:H8')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('F8:H8')->getFill()->getStartColor()->setRGB('F2DCDB');

$objPHPExcel->getActiveSheet()->getStyle('F13:G13')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('F13:G13')->getFill()->getStartColor()->setRGB('F2DCDB');

$objPHPExcel->getActiveSheet()->getStyle('F18:I18')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('F18:I18')->getFill()->getStartColor()->setRGB('F2DCDB');

$objPHPExcel->getActiveSheet()->getStyle('F24:I24')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('F24:I24')->getFill()->getStartColor()->setRGB('F2DCDB');

$objPHPExcel->getActiveSheet()->getStyle('F33:I33')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('F33:I33')->getFill()->getStartColor()->setRGB('F2DCDB');

$objPHPExcel->getActiveSheet()->getStyle('F6:G6')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('F6:G6')->getFill()->getStartColor()->setRGB('BDD7EE');

$objPHPExcel->getActiveSheet()
    ->getStyle('F3:G6')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('F3:G3')
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
$styleArrayOutside = array(
    'borders' => array(
        'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_THICK,
        ),
    ),
);

// Terapkan gaya tepi di dalam sel (tipis)
$objPHPExcel->getActiveSheet()->getStyle('F3:G6')->applyFromArray($styleArray);

// Terapkan gaya tepi di luar sel (tebal)
$objPHPExcel->getActiveSheet()->getStyle('F3:G6')->applyFromArray($styleArrayOutside);

//PENDIDIKAN

$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B28', 'Pendidikan')
    ->setCellValue('C28', 'QTY')
    ->setCellValue('C29', 'Pria')
    ->setCellValue('D29', 'Wanita')
    ->setCellValue('B30', 'SD,SLTP')
    ->setCellValue('B31', 'SLTA')
    ->setCellValue('B32', 'Diploma')
    ->setCellValue('B33', 'S-1')
    ->setCellValue('B34', 'S2,S3')
    ->setCellValue('B35', 'Sub Total')
    ->setCellValue('B36', 'Total')

    ->setCellValue('C30', $jumlah_pria_SD + $jumlah_pria_SLTP)
    ->setCellValue('D30', $jumlah_perempuan_SD + $jumlah_perempuan_SLTP)

    ->setCellValue('C31', $jumlah_pria_SMA)
    ->setCellValue('D31', $jumlah_perempuan_SMA)

    ->setCellValue('C32', $jumlah_pria_Diploma)
    ->setCellValue('D32', $jumlah_perempuan_Diploma)

    ->setCellValue('C33', $jumlah_pria_S1)
    ->setCellValue('D33', $jumlah_perempuan_S1)

    ->setCellValue('C34', $jumlah_pria_S2 + $jumlah_pria_S3)
    ->setCellValue('D34', $jumlah_perempuan_S2 + $jumlah_perempuan_S3)

    ->setCellValue('C35', '=SUM(C30:C34)')
    ->setCellValue('D35', '=SUM(D30:D34)')

    ->setCellValue('C36', '=SUM(C35:D35)');

// Add background color to specific cells
$objPHPExcel->getActiveSheet()->getStyle('B28:D29')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('B28:D29')->getFill()->getStartColor()->setRGB('FCE4D6');

$objPHPExcel->getActiveSheet()->getStyle('C36:D36')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('C36:D36')->getFill()->getStartColor()->setRGB('FCE4D6');

$objPHPExcel->getActiveSheet()
    ->getStyle('B28')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('B28:D36')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('B28:D29')
    ->getFont()
    ->setBold(true);

$objPHPExcel->getActiveSheet()
    ->getStyle('B35:B36')
    ->getFont()
    ->setBold(true);

$objPHPExcel->getActiveSheet()
    ->getStyle('B36:D36')
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
$styleArrayOutside = array(
    'borders' => array(
        'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_THICK,
        ),
    ),
);

// Terapkan gaya tepi di dalam sel (tipis)
$objPHPExcel->getActiveSheet()->getStyle('B28:D36')->applyFromArray($styleArray);

// Terapkan gaya tepi di luar sel (tebal)
$objPHPExcel->getActiveSheet()->getStyle('B28:D36')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('B35:D35')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('B36:D36')->applyFromArray($styleArrayOutside);

$currentDay = date('d');
// Check if the month and year are selected and set the date value accordingly
if (!empty($selectedMonth) && !empty($selectedYear)) {
    if ($selectedMonth == $currentMonth && $selectedYear == $currentYear) {
        // If selected month is the current month, include the current day
        $selectedDate = sprintf("%02d %s %s", $currentDay, $selectedMonthName, $selectedYear);
    } else {
        // If not, just use the month and year
        $selectedDate = sprintf("%s %s", $selectedMonthName, $selectedYear);
    }
} else {
    // Default to the current date
    $selectedDate = $tanggal_hari_ini;
}

//HEADER
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('K1', 'REAL TIME MAN POWER TODAY')
    ->setCellValue('K2', $selectedDate);

$objPHPExcel->getActiveSheet()->getStyle('K2:M2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('K2:M2')->getFill()->getStartColor()->setRGB('FFFF00');

$objPHPExcel->getActiveSheet()->getStyle('Z35')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('Z35')->getFill()->getStartColor()->setRGB('FFFF00');

$objPHPExcel->getActiveSheet()
    ->getStyle('K1:M30')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('K1')
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
$styleArrayOutside = array(
    'borders' => array(
        'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_THICK,
        ),
    ),
);

// Terapkan gaya tepi di dalam sel (tipis)
$objPHPExcel->getActiveSheet()->getStyle('K3:Y35')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('X6:Z35')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('W36:Y37')->applyFromArray($styleArray);

// Terapkan gaya tepi di luar sel (tebal)
$objPHPExcel->getActiveSheet()->getStyle('K1:Z1')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('K3:Z35')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('K3:Z5')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('K35:Z35')->applyFromArray($styleArrayOutside);

//DEPT
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('K3', 'NO')
    ->setCellValue('L3', 'DEPARTEMEN')
    ->setCellValue('M3', 'GOLONGAN')
    ->setCellValue('Z3', 'GRAND')
    ->setCellValue('M4', 'KONTRAK')
    ->setCellValue('N4', 'I')
    ->setCellValue('O4', 'II')
    ->setCellValue('Q4', 'III')
    ->setCellValue('S4', 'IV')
    ->setCellValue('U4', 'V')
    ->setCellValue('V4', 'VI-VII')
    ->setCellValue('W4', 'TOTAL')
    ->setCellValue('Z4', 'TOTAL')
    ->setCellValue('N5', 'TETAP')
    ->setCellValue('O5', 'TETAP')
    ->setCellValue('P5', 'TRAINEE')
    ->setCellValue('Q5', 'TETAP')
    ->setCellValue('R5', 'TRAINEE')
    ->setCellValue('S5', 'TETAP')
    ->setCellValue('T5', 'TRAINEE')
    ->setCellValue('U5', 'TETAP')
    ->setCellValue('W5', 'TETAP')
    ->setCellValue('X5', 'TRAINEE')
    ->setCellValue('Y5', 'KONTRAK')
    // ->setCellValue('X34', 'TOTAL')
    ->setCellValue('K35', 'TOTAL')
    ->setCellValue('M35', '=SUM(M6:M34)')
    ->setCellValue('N35', '=SUM(N6:N34)')
    ->setCellValue('O35', '=SUM(O6:O34)')
    ->setCellValue('P35', '=SUM(P6:P34)')
    ->setCellValue('Q35', '=SUM(Q6:Q34)')
    ->setCellValue('R35', '=SUM(R6:R34)')
    ->setCellValue('S35', '=SUM(S6:S34)')
    ->setCellValue('T35', '=SUM(T6:T34)')
    ->setCellValue('U35', '=SUM(U6:U34)')
    ->setCellValue('V35', '=SUM(V6:V34)')
    ->setCellValue('W35', '=SUM(W6:W34)')
    ->setCellValue('X35', '=SUM(X6:X34)')
    ->setCellValue('Y35', '=SUM(Y6:Y34)')
    ->setCellValue('Z35', '=SUM(Z6:Z34)')
    ->setCellValue('W36', '=SUM(W35:X35)')
    ->setCellValue('Y36', '=+Y35')
    ->setCellValue('W37', '=(W36/Z35)')
    ->setCellValue('Y37', '=(Y36/Z35)');

$objPHPExcel->getActiveSheet()
    ->getStyle('W37')
    ->getNumberFormat()
    ->setFormatCode('0.0%');

$objPHPExcel->getActiveSheet()
    ->getStyle('Y37')
    ->getNumberFormat()
    ->setFormatCode('0.0%');

$no = 6; // Start row number
while ($user_data = mysqli_fetch_array($resultRMP)) {
    $cwoc = isset($user_data['cwoc']) ? $user_data['cwoc'] : null;
    $display_text = str_replace(
        ['BOD TA EXP', 'HRD IR', 'FINANCE ACCOUNTING', 'PLANNING BUDGETING', 'PDCA CPC'],
        ['BOD & TA EXP', 'HRD & IR', 'FINANCE & ACCOUNTING', 'PLANNING & BUDGETING', 'PDCA & CPC'],
        $cwoc
    );
    $kontrak = isset($DashboardByCwoc[$cwoc]['Kontrak']) ? $DashboardByCwoc[$cwoc]['Kontrak'] : 0;
    $tetap1 = isset($DashboardByCwoc[$cwoc]['Tetap1']) ? $DashboardByCwoc[$cwoc]['Tetap1'] : 0;
    $tetap2 = isset($DashboardByCwoc[$cwoc]['Tetap2']) ? $DashboardByCwoc[$cwoc]['Tetap2'] : 0;
    $trainee2 = isset($DashboardByCwoc[$cwoc]['Trainee2']) ? $DashboardByCwoc[$cwoc]['Trainee2'] : 0;
    $tetap3 = isset($DashboardByCwoc[$cwoc]['Tetap3']) ? $DashboardByCwoc[$cwoc]['Tetap3'] : 0;
    $trainee3 = isset($DashboardByCwoc[$cwoc]['Trainee3']) ? $DashboardByCwoc[$cwoc]['Trainee3'] : 0;
    $tetap4 = isset($DashboardByCwoc[$cwoc]['Tetap4']) ? $DashboardByCwoc[$cwoc]['Tetap4'] : 0;
    $trainee4 = isset($DashboardByCwoc[$cwoc]['Trainee4']) ? $DashboardByCwoc[$cwoc]['Trainee4'] : 0;
    $Tetap6 = isset($DashboardByCwoc[$cwoc]['Tetap6']) ? $DashboardByCwoc[$cwoc]['Tetap6'] : 0;
    $tetap6_7 = isset($DashboardByCwoc[$cwoc]['Tetap6_7']) ? $DashboardByCwoc[$cwoc]['Tetap6_7'] : 0;
    $tetap = isset($DashboardByCwoc[$cwoc]['Tetap']) ? $DashboardByCwoc[$cwoc]['Tetap'] : 0;
    $total = $kontrak + $trainee2 + $trainee3 + $trainee4 + $tetap;
    // Populate the rest of the variables similarly

    // Set cell values
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('L' . $no, $display_text)
        ->setCellValue('M' . $no, $kontrak)
        ->setCellValue('N' . $no, $tetap1)
        ->setCellValue('O' . $no, $tetap2)
        ->setCellValue('P' . $no, $trainee2)
        ->setCellValue('Q' . $no, $tetap3)
        ->setCellValue('R' . $no, $trainee3)
        ->setCellValue('S' . $no, $tetap4)
        ->setCellValue('T' . $no, $trainee4)
        ->setCellValue('U' . $no, $Tetap6)
        ->setCellValue('V' . $no, $tetap6_7)
        ->setCellValue('W' . $no, $tetap)
        ->setCellValue('Y' . $no, $kontrak)
        ->setCellValue('X' . $no, $trainee2 + $trainee3 + $trainee4)
        ->setCellValue('Z' . $no, $total);
    $no++; // Increment row number
}


$objPHPExcel->getActiveSheet()
    ->getStyle('K3:L5')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('W36:Y37')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('M4:M5')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('K3:Z35')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('V4')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('G14:G16')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

// Array dengan nomor 1-28
$numbers = range(1, 29);

// Mendapatkan kolom K dari baris 6 hingga 33
$kolom_K = range('K', 'K');

// Loop untuk menetapkan nilai ke setiap sel
for ($i = 0; $i < count($numbers); $i++) {
    $cell = $kolom_K[0] . ($i + 6); // Memulai dari baris 7 dan mengubah nomor baris
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cell, $numbers[$i]); // Set nilai ke sel
}


$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B39', 'Golongan')
    ->setCellValue('C39', 'Karyawan Tetap')
    ->setCellValue('E39', 'PKWT')
    ->setCellValue('G39', 'Asing')
    ->setCellValue('I39', 'Total')
    ->setCellValue('C40', 'Pria')
    ->setCellValue('D40', 'Wanita')
    ->setCellValue('E40', 'Pria')
    ->setCellValue('F40', 'Wanita')
    ->setCellValue('G40', 'Pria')
    ->setCellValue('H40', 'Wanita')

    ->setCellValue('B41', 'Non gol')
    ->setCellValue('B42', 'I')
    ->setCellValue('B43', 'II')
    ->setCellValue('B44', 'III')
    ->setCellValue('B45', 'IV')
    ->setCellValue('B46', 'V')
    ->setCellValue('B47', 'VI')
    ->setCellValue('B48', 'VII')
    ->setCellValue('B49', 'Total')

    ->setCellValue('C41', $Tetap0L)
    ->setCellValue('D41', $Tetap0P)
    ->setCellValue('E41', $Kontrak0L)
    ->setCellValue('F41', $Kontrak0P)
    ->setCellValue('G41', $Asing0L)
    ->setCellValue('H41', $Asing0P)

    ->setCellValue('C42', $Tetap1L)
    ->setCellValue('D42', $Tetap1P)
    ->setCellValue('E42', $Kontrak1L)
    ->setCellValue('F42', $Kontrak1P)
    ->setCellValue('G42', $Asing1L)
    ->setCellValue('H42', $Asing1P)

    ->setCellValue('C43', $Tetap2L)
    ->setCellValue('D43', $Tetap2P)
    ->setCellValue('E43', $Kontrak2L)
    ->setCellValue('F43', $Kontrak2P)
    ->setCellValue('G43', $Asing2L)
    ->setCellValue('H43', $Asing2P)

    ->setCellValue('C44', $Tetap3L)
    ->setCellValue('D44', $Tetap3P)
    ->setCellValue('E44', $Kontrak3L)
    ->setCellValue('F44', $Kontrak3P)
    ->setCellValue('G44', $Asing3L)
    ->setCellValue('H44', $Asing3P)

    ->setCellValue('C45', $Tetap4L)
    ->setCellValue('D45', $Tetap4P)
    ->setCellValue('E45', $Kontrak4L)
    ->setCellValue('F45', $Kontrak4P)
    ->setCellValue('G45', $Asing4L)
    ->setCellValue('H45', $Asing4P)

    ->setCellValue('C46', $Tetap5L)
    ->setCellValue('D46', $Tetap5P)
    ->setCellValue('E46', $Kontrak5L)
    ->setCellValue('F46', $Kontrak5P)
    ->setCellValue('G46', $Asing5L)
    ->setCellValue('H46', $Asing5P)

    ->setCellValue('C47', $Tetap6L)
    ->setCellValue('D47', $Tetap6P)
    ->setCellValue('E47', $Kontrak6L)
    ->setCellValue('F47', $Kontrak6P)
    ->setCellValue('G47', $Asing6L)
    ->setCellValue('H47', $Asing6P)

    ->setCellValue('C48', $Tetap7L)
    ->setCellValue('D48', $Tetap7P)
    ->setCellValue('E48', $Kontrak7L)
    ->setCellValue('F48', $Kontrak7P)
    ->setCellValue('G48', $Asing7L)
    ->setCellValue('H48', $Asing7P);


function setSumFormulas($startRow, $endRow, $formulaColumn, $sumRangeStartColumn, $sumRangeEndColumn, $objPHPExcel)
{
    for ($row = $startRow; $row <= $endRow; $row++) {
        $cell = $formulaColumn . $row;
        $sumRange = $sumRangeStartColumn . $row . ':' . $sumRangeEndColumn . $row;
        $objPHPExcel->getActiveSheet()->setCellValue($cell, "=SUM($sumRange)");
    }
}

// Panggil fungsi untuk setiap rangkaian sel yang diinginkan
setSumFormulas(41, 49, 'I', 'C', 'H', $objPHPExcel);

// Fungsi untuk mengatur rumus SUM pada kolom target dan baris yang ditentukan
function setSumFormula($objPHPExcel, $startColumn, $endColumn, $targetRow, $sumRangeStartRow, $sumRangeEndRow)
{
    for ($col = $startColumn; $col <= $endColumn; $col++) {
        $cell = $col . $targetRow;
        $sumRange = $col . $sumRangeStartRow . ':' . $col . $sumRangeEndRow;
        $objPHPExcel->getActiveSheet()->setCellValue($cell, "=SUM($sumRange)");
    }
}

setSumFormula($objPHPExcel, 'C', 'H', 49, 41, 48);

$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B52', 'By Age')
    ->setCellValue('B53', 'Bulan')
    ->setCellValue('C53', 'By Usia (Rentang Usia 5 Tahun)')
    ->setCellValue('C54', '<26')
    ->setCellValue('E54', '26-30')
    ->setCellValue('G54', '31-35')
    ->setCellValue('I54', '36-40')
    ->setCellValue('K54', '41-45')
    ->setCellValue('M54', '46-50')
    ->setCellValue('O54', '51-55')
    ->setCellValue('Q54', '>55')
    ->setCellValue('S53', 'Total')

    ->setCellValue('C55', 'Pria')
    ->setCellValue('D55', 'Wanita')
    ->setCellValue('E55', 'Pria')
    ->setCellValue('F55', 'Wanita')
    ->setCellValue('G55', 'Pria')
    ->setCellValue('H55', 'Wanita')
    ->setCellValue('I55', 'Pria')
    ->setCellValue('J55', 'Wanita')
    ->setCellValue('K55', 'Pria')
    ->setCellValue('L55', 'Wanita')
    ->setCellValue('M55', 'Pria')
    ->setCellValue('N55', 'Wanita')
    ->setCellValue('O55', 'Pria')
    ->setCellValue('P55', 'Wanita')
    ->setCellValue('Q55', 'Pria')
    ->setCellValue('R55', 'Wanita')

    ->setCellValue('C56', $pria26)
    ->setCellValue('D56', $perempuan26)
    ->setCellValue('E56', $pria26_30)
    ->setCellValue('F56', $perempuan26_30)
    ->setCellValue('G56', $pria31_35)
    ->setCellValue('H56', $perempuan31_35)
    ->setCellValue('I56', $pria36_40)
    ->setCellValue('J56', $perempuan36_40)
    ->setCellValue('K56', $pria41_45)
    ->setCellValue('L56', $perempuan41_45)
    ->setCellValue('M56', $pria46_50)
    ->setCellValue('N56', $perempuan46_50)
    ->setCellValue('O56', $pria51_55)
    ->setCellValue('P56', $perempuan51_55)
    ->setCellValue('Q56', $pria55)
    ->setCellValue('R56', $perempuan55)

    ->setCellValue('B57', 'Subtotal')
    ->setCellValue('B57', 'Total')
    ->setCellValue('S56', '=SUM(C57:R57)');

$columns = range('C', 'R');
foreach ($columns as $key => $column) {
    // Cek apakah indeks adalah genap dan pastikan nextColumn berada dalam array
    if ($key % 2 == 0 && isset($columns[$key + 1])) {
        $nextColumn = $columns[$key + 1];
        $currentRow = 57;
        $previousRow = 56;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($column . $currentRow, "=SUM($column$previousRow:$nextColumn$previousRow)");
    }
}

$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('F8', 'Kelompok Jabatan')
    ->setCellValue('G8', 'Jumlah Pekerja')
    ->setCellValue('H8', 'Jumlah Jam Pelatihan*')
    ->setCellValue('F9', 'Managers (G5 up)')
    ->setCellValue('F10', 'Non Managers (G1-4)')
    ->setCellValue('G9', $Manager)
    ->setCellValue('G10', $NonManager)
    ->setCellValue('F11', 'Total')
    ->setCellValue('G11', '=SUM(G9:G10)')
    ->setCellValue('H11', '=SUM(H9:H10)');

$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('F13', 'STATUS')
    ->setCellValue('G13', 'Jumlah')
    ->setCellValue('F14', 'Tetap')
    ->setCellValue('F15', 'Kontrak')
    ->setCellValue('F16', 'Total')
    ->setCellValue('G14', $Tetap + $Trainee3 + $Trainee4 + $Trainee2)
    ->setCellValue('G15', $Kontrak)
    ->setCellValue('G16', '=SUM(G14:G15)');

$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('F18', 'STATUS')
    ->setCellValue('G18', 'PRIA')
    ->setCellValue('H18', 'WANITA')
    ->setCellValue('I18', 'TOTAL')
    ->setCellValue('F19', 'DL')
    ->setCellValue('F20', 'IDL')
    ->setCellValue('F21', 'SGA')
    ->setCellValue('G19', $DLPria)
    ->setCellValue('H19', $DLPerempuan)
    ->setCellValue('G20', $IDL1Pria + $IDL2Pria)
    ->setCellValue('H20', $IDL1Perempuan + $IDL2Perempuan)
    ->setCellValue('G21', $SGAPria)
    ->setCellValue('H21', $SGAPerempuan)
    ->setCellValue('F22', 'Total');


$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('F24', 'STATUS')
    ->setCellValue('G24', 'PRIA')
    ->setCellValue('H24', 'WANITA')
    ->setCellValue('I24', 'TOTAL')
    ->setCellValue('F25', 'DL TETAP')
    ->setCellValue('F26', 'DL KONTRAK')
    ->setCellValue('F27', 'IDL TETAP')
    ->setCellValue('F28', 'IDL KONTRAK')
    ->setCellValue('F29', 'SGA TETAP')
    ->setCellValue('F30', 'SGA KONTRAK')

    ->setCellValue('G25', $DLPriaTetap)
    ->setCellValue('H25', $DLPerempuanTetap)
    ->setCellValue('G26', $DLPriaKontrak)
    ->setCellValue('H26', $DLPerempuanKontrak)
    ->setCellValue('G27', $IDL1PriaTetap + $IDL2PriaTetap)
    ->setCellValue('H27', $IDL1PerempuanTetap + $IDL2PerempuanTetap)
    ->setCellValue('G28', $IDL1PriaKontrak + $IDL2PriaKontrak)
    ->setCellValue('H28', $IDL1PerempuanKontrak + $IDL2PerempuanKontrak)
    ->setCellValue('G29', $SGAPriaTetap)
    ->setCellValue('H29', $SGAPerempuanTetap)
    ->setCellValue('G30', $SGAPriaKontrak)
    ->setCellValue('H30', $SGAPerempuanKontrak);

$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('F33', 'STATUS')
    ->setCellValue('G33', 'PRIA')
    ->setCellValue('H33', 'WANITA')
    ->setCellValue('F34', 'DL KONTRAK')
    ->setCellValue('F35', 'IDL + SGA TETAP')
    ->setCellValue('F36', 'IDL + SGA KONTRAK')

    ->setCellValue('G34', $DLPriaKontrak)
    ->setCellValue('H34', $DLPerempuanKontrak)
    ->setCellValue('G35', $IDL1PriaTetap + $IDL2PriaTetap + $SGAPriaTetap)
    ->setCellValue('H35', $IDL1PerempuanTetap + $IDL2PerempuanTetap + $SGAPerempuanTetap)
    ->setCellValue('G36', $IDL1PriaKontrak + $IDL2PriaKontrak + $SGAPriaKontrak)
    ->setCellValue('H36', $IDL1PerempuanKontrak + $IDL2PerempuanKontrak + $SGAPerempuanKontrak);

setSumFormulas(19, 22, 'I', 'G', 'H', $objPHPExcel);
setSumFormulas(25, 31, 'I', 'G', 'H', $objPHPExcel);
setSumFormulas(34, 37, 'I', 'G', 'H', $objPHPExcel);

setSumFormula($objPHPExcel, 'G', 'H', 22, 19, 21);
setSumFormula($objPHPExcel, 'G', 'H', 31, 25, 30);
setSumFormula($objPHPExcel, 'G', 'H', 37, 34, 36);


$objPHPExcel->createSheet(); // Membuat sheet baru
$objPHPExcel->setActiveSheetIndex(1); // Set aktif ke sheet baru

// Mengatur judul lembar baru
$objPHPExcel->getActiveSheet()->setTitle('Pendidikan');

$objPHPExcel->getDefaultStyle()->getFont()->setName('Times New Roman');
$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(22);
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(34);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(14);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(11);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);

$objPHPExcel->getActiveSheet()->getStyle('K36')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('K36')->getFill()->getStartColor()->setRGB('FFFF00');


$mergeCells = [
    'A1:J1',
    'A4:A6',
    'B4:B6',
    'C4:I4',
    'J4:J6',
    'K4:K6',
    'C5:I5',
    'A36:B36'
];

// Menggunakan array_unique untuk menghapus sel duplikat jika ada
$mergeCells = array_unique($mergeCells);

foreach ($mergeCells as $mergeRange) {
    $objPHPExcel->getActiveSheet()->mergeCells($mergeRange);
}

// Add border to cells A1:D4 in the first sheet
$styleArray = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        ),
    ),
);
$styleArrayOutside = array(
    'borders' => array(
        'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_THICK,
        ),
    ),
);

// Terapkan gaya tepi di dalam sel (tipis)
$objPHPExcel->getActiveSheet()->getStyle('A4:K36')->applyFromArray($styleArray);

// Terapkan gaya tepi di luar sel (tebal)
$objPHPExcel->getActiveSheet()->getStyle('A4:K36')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('A4:K6')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('A36:K36')->applyFromArray($styleArrayOutside);


$objPHPExcel->setActiveSheetIndex(1)
    ->setCellValue('A1', 'MAN POWER PT KAYABA INDONESIA')
    ->setCellValue('B3', $tanggal_hari_ini)
    ->setCellValue('A4', 'NO')
    ->setCellValue('B4', 'DEPARTEMEN')
    ->setCellValue('C4', 'KARYAWAN TETAP & TRAINEE')
    ->setCellValue('J4', 'KARYAWAN KONTRAK')
    ->setCellValue('K4', 'TOTAL')
    ->setCellValue('C5', 'PENDIDIKAN')
    ->setCellValue('C6', 'S2, S3')
    ->setCellValue('D6', 'S1')
    ->setCellValue('E6', 'D3')
    ->setCellValue('F6', 'D1 & D2')
    ->setCellValue('G6', 'SLTA')
    ->setCellValue('H6', 'SLTP')
    ->setCellValue('I6', 'SD')
    ->setCellValue('A36', 'TOTAL')
    ->setCellValue('C36', '=SUM(C7:C35)')
    ->setCellValue('D36', '=SUM(D7:D35)')
    ->setCellValue('E36', '=SUM(E7:E35)')
    ->setCellValue('F36', '=SUM(F7:F35)')
    ->setCellValue('G36', '=SUM(G7:G35)')
    ->setCellValue('H36', '=SUM(H7:H35)')
    ->setCellValue('I36', '=SUM(I7:I35)')
    ->setCellValue('J36', '=SUM(J7:J35)')
    ->setCellValue('K36', '=SUM(K7:K35)');


$objPHPExcel->getActiveSheet()
    ->getStyle('A1')
    ->getFont()
    ->setSize(16)
    ->setBold(true);

$objPHPExcel->getActiveSheet()
    ->getStyle('A36:K36')
    ->getFont()
    ->setBold(true);

// Mengatur horizontal dan vertical alignment untuk 'A1'
$objPHPExcel->getActiveSheet()
    ->getStyle('A1')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

// Mengatur horizontal dan vertical alignment untuk 'B4'
$objPHPExcel->getActiveSheet()
    ->getStyle('A4:K36')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('J4')
    ->getAlignment()
    ->setWrapText(true);

$sheet = $objPHPExcel->getActiveSheet();
$sheet->freezePane('A7');

$no = 1; // Start number
$start_row = 7; // Start row number

while ($user_data = mysqli_fetch_array($resultRMP2)) {
    $cwoc = isset($user_data['cwoc']) ? $user_data['cwoc'] : null;
    // Mengganti nilai cwoc dengan menggunakan str_replace
    $display_text = str_replace(
        ['BOD TA EXP', 'HRD IR', 'FINANCE ACCOUNTING', 'PLANNING BUDGETING', 'PDCA CPC'],
        ['BOD & TA EXP', 'HRD & IR', 'FINANCE & ACCOUNTING', 'PLANNING & BUDGETING', 'PDCA & CPC'],
        $cwoc
    );
    $SD = isset($ModalPendidikanByCwoc[$cwoc]['SD']) ? $ModalPendidikanByCwoc[$cwoc]['SD'] : 0;
    $SLTP = isset($ModalPendidikanByCwoc[$cwoc]['SLTP']) ? $ModalPendidikanByCwoc[$cwoc]['SLTP'] : 0;
    $SLTA = isset($ModalPendidikanByCwoc[$cwoc]['SLTA']) ? $ModalPendidikanByCwoc[$cwoc]['SLTA'] : 0;
    $D1_D2 = isset($ModalPendidikanByCwoc[$cwoc]['D1_D2']) ? $ModalPendidikanByCwoc[$cwoc]['D1_D2'] : 0;
    $D3 = isset($ModalPendidikanByCwoc[$cwoc]['D3']) ? $ModalPendidikanByCwoc[$cwoc]['D3'] : 0;
    $S1 = isset($ModalPendidikanByCwoc[$cwoc]['S1']) ? $ModalPendidikanByCwoc[$cwoc]['S1'] : 0;
    $S2_S3 = isset($ModalPendidikanByCwoc[$cwoc]['S2_S3']) ? $ModalPendidikanByCwoc[$cwoc]['S2_S3'] : 0;
    $kontrak = isset($DashboardByCwoc[$cwoc]['Kontrak']) ? $DashboardByCwoc[$cwoc]['Kontrak'] : 0;
    $total = $SD + $SLTP + $SLTA + $D1_D2 + $D3 + $S1 + $S2_S3 + $kontrak;

    // Set cell values
    $objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('A' . ($no + $start_row - 1), $no) // Set the row number in column A
        ->setCellValue('B' . ($no + $start_row - 1), $display_text)
        ->setCellValue('C' . ($no + $start_row - 1), $S2_S3)
        ->setCellValue('D' . ($no + $start_row - 1), $S1)
        ->setCellValue('E' . ($no + $start_row - 1), $D3)
        ->setCellValue('F' . ($no + $start_row - 1), $D1_D2)
        ->setCellValue('G' . ($no + $start_row - 1), $SLTA)
        ->setCellValue('H' . ($no + $start_row - 1), $SLTP)
        ->setCellValue('I' . ($no + $start_row - 1), $SD)
        ->setCellValue('J' . ($no + $start_row - 1), $kontrak)
        ->setCellValue('K' . ($no + $start_row - 1), $total);

    $no++; // Increment number
}


$objPHPExcel->createSheet(); // Membuat sheet baru
$objPHPExcel->setActiveSheetIndex(2); // Set aktif ke sheet baru

// Mengatur judul lembar baru
$objPHPExcel->getActiveSheet()->setTitle('Year of Service & Age');

$objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(22);
// Dapatkan sheet aktif
$sheet = $objPHPExcel->getActiveSheet();
$sheet->getDefaultRowDimension()->setRowHeight(18);


// Daftar sel yang akan digabungkan
$mergeCells = [
    'B6:C7',
    'D6:M6',
    'B8:B15',
    'B16:C16',
    'B19:C20',
    'D19:M19',
    'B21:B28',
    'B29:C29',
    'B32:C33',
    'D32:M32',
    'B34:B41',
    'B42:C42',
    'B45:C46',
    'D45:M45',
    'B47:B54',
    'B55:C55',
    'B58:C59',
    'D58:M58',
    'B60:B67',
    'B68:C68',
    'B71:C72',
    'D71:M71',
    'B73:B80',
    'B81:C81',
    'B2:K3',
];

// Menghapus sel duplikat jika ada
$mergeCells = array_unique($mergeCells);

// Menggabungkan sel sesuai dengan range yang telah ditentukan
foreach ($mergeCells as $mergeRange) {
    $objPHPExcel->getActiveSheet()->mergeCells($mergeRange);
}


// Add border to cells A1:D4 in the first sheet
$styleArray = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        ),
    ),
);
$styleArrayOutside = array(
    'borders' => array(
        'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_THICK,
        ),
    ),
);

// Terapkan gaya tepi di dalam sel (tipis)
$objPHPExcel->getActiveSheet()->getStyle('B6:M16')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('B19:M29')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('B32:M42')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('B45:M55')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('B58:M68')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('B71:M81')->applyFromArray($styleArray);

// Terapkan gaya tepi di luar sel (tebal)
$objPHPExcel->getActiveSheet()->getStyle('B6:M16')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('B19:M29')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('B32:M42')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('B45:M55')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('B58:M68')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('B71:M81')->applyFromArray($styleArrayOutside);

// Mengatur horizontal dan vertical alignment untuk 'B4'
$objPHPExcel->getActiveSheet()
    ->getStyle('B1:M81')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

// Dapatkan sheet aktif
$sheet = $objPHPExcel->getActiveSheet();

// Daftar range yang akan diberi gaya pengisian
$ranges = [
    ['D6:M6', 'D19:M19', 'D32:M32', 'D45:M45', 'D58:M58', 'D71:M71', 'M7:M16', 'B16:C16', 'M20:M29', 'B29:C29', 'M33:M42', 'B42:C42', 'M46:M55', 'B55:C55', 'M59:M68', 'B68:C68', 'M72:M81', 'B81:C81'], // Rentang dengan warna RGB BDD7EE
    ['B8:C15', 'B21:C28', 'B34:C41', 'B47:C54', 'B60:C67', 'B73:C80'], // Rentang dengan warna RGB 00008B
];

// Mengatur gaya pengisian solid dengan warna RGB tertentu untuk setiap range
foreach ($ranges as $colorIndex => $colorRanges) {
    $color = $colorIndex == 0 ? 'BDD7EE' : '002060'; // Tentukan warna berdasarkan indeks
    foreach ($colorRanges as $range) {
        $sheet->getStyle($range)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle($range)->getFill()->getStartColor()->setRGB($color);
    }
}

// Dapatkan sheet aktif
$sheet = $objPHPExcel->getActiveSheet();

// Daftar sel yang akan diatur gaya dan ukuran fontnya
$cells = ['B2', 'B6', 'B16', 'B19', 'B29', 'B32', 'B42', 'B45', 'B55', 'B58', 'B68', 'B71', 'B81'];

// Iterasi melalui setiap sel dan atur gaya dan ukuran font
foreach ($cells as $cell) {
    $style = $sheet->getStyle($cell);
    if ($style) {
        $font = $style->getFont();
        if ($font) {
            $font->setSize(16)->setBold(true);
        }
    }
}

// Dapatkan sheet aktif
$sheet = $objPHPExcel->getActiveSheet();

// Daftar sel yang akan diatur gaya dan ukuran fontnya
$cells = ['B8:C15', 'B21:C28', 'B34:C41', 'B47:C54', 'B60:C67', 'B73:C80'];

// Iterasi melalui setiap sel dan atur gaya dan ukuran font
foreach ($cells as $cell) {
    $objPHPExcel->getActiveSheet()->getStyle($cell)->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_WHITE));
}

$objPHPExcel->setActiveSheetIndex(2)
    ->setCellValue('B2', 'Data Karyawan berdasarkan Masa Kerja (Permanen)')
    ->setCellValue('B6', 'Gol 1')
    ->setCellValue('D6', 'AGE')
    ->setCellValue('D7', '18-20')
    ->setCellValue('E7', '21-25')
    ->setCellValue('F7', '26-30')
    ->setCellValue('G7', '31-35')
    ->setCellValue('H7', '36-40')
    ->setCellValue('I7', '41-45')
    ->setCellValue('J7', '46-50')
    ->setCellValue('K7', '51-55')
    ->setCellValue('L7', '>55')
    ->setCellValue('M7', 'Total')
    ->setCellValue('B8', 'YOS')
    ->setCellValue('C8', '0-2')
    ->setCellValue('C9', '3-6')
    ->setCellValue('C10', '7-10')
    ->setCellValue('C11', '11-15')
    ->setCellValue('C12', '16-20')
    ->setCellValue('C13', '21-25')
    ->setCellValue('C14', '26-31')
    ->setCellValue('C15', '>31')
    ->setCellValue('B16', 'Total')

    ->setCellValue('B19', 'Gol 2')
    ->setCellValue('D19', 'AGE')
    ->setCellValue('D20', '18-20')
    ->setCellValue('E20', '21-25')
    ->setCellValue('F20', '26-30')
    ->setCellValue('G20', '31-35')
    ->setCellValue('H20', '36-40')
    ->setCellValue('I20', '41-45')
    ->setCellValue('J20', '46-50')
    ->setCellValue('K20', '51-55')
    ->setCellValue('L20', '>55')
    ->setCellValue('M20', 'Total')
    ->setCellValue('B21', 'YOS')
    ->setCellValue('C21', '0-2')
    ->setCellValue('C22', '3-6')
    ->setCellValue('C23', '7-10')
    ->setCellValue('C24', '11-15')
    ->setCellValue('C25', '16-20')
    ->setCellValue('C26', '21-25')
    ->setCellValue('C27', '26-31')
    ->setCellValue('C28', '>31')
    ->setCellValue('B29', 'Total')

    ->setCellValue('B32', 'Gol 3')
    ->setCellValue('D32', 'AGE')
    ->setCellValue('D33', '18-20')
    ->setCellValue('E33', '21-25')
    ->setCellValue('F33', '26-30')
    ->setCellValue('G33', '31-35')
    ->setCellValue('H33', '36-40')
    ->setCellValue('I33', '41-45')
    ->setCellValue('J33', '46-50')
    ->setCellValue('K33', '51-55')
    ->setCellValue('L33', '>55')
    ->setCellValue('M33', 'Total')
    ->setCellValue('B34', 'YOS')
    ->setCellValue('C34', '0-2')
    ->setCellValue('C35', '3-6')
    ->setCellValue('C36', '7-10')
    ->setCellValue('C37', '11-15')
    ->setCellValue('C38', '16-20')
    ->setCellValue('C39', '21-25')
    ->setCellValue('C40', '26-31')
    ->setCellValue('C41', '>31')
    ->setCellValue('B42', 'Total')

    ->setCellValue('B45', 'Gol 4')
    ->setCellValue('D45', 'AGE')
    ->setCellValue('D46', '18-20')
    ->setCellValue('E46', '21-25')
    ->setCellValue('F46', '26-30')
    ->setCellValue('G46', '31-35')
    ->setCellValue('H46', '36-40')
    ->setCellValue('I46', '41-45')
    ->setCellValue('J46', '46-50')
    ->setCellValue('K46', '51-55')
    ->setCellValue('L46', '>55')
    ->setCellValue('M46', 'Total')
    ->setCellValue('B47', 'YOS')
    ->setCellValue('C47', '0-2')
    ->setCellValue('C48', '3-6')
    ->setCellValue('C49', '7-10')
    ->setCellValue('C50', '11-15')
    ->setCellValue('C51', '16-20')
    ->setCellValue('C52', '21-25')
    ->setCellValue('C53', '26-31')
    ->setCellValue('C54', '>31')
    ->setCellValue('B55', 'Total')

    ->setCellValue('B58', 'Gol 5')
    ->setCellValue('D58', 'AGE')
    ->setCellValue('D59', '18-20')
    ->setCellValue('E59', '21-25')
    ->setCellValue('F59', '26-30')
    ->setCellValue('G59', '31-35')
    ->setCellValue('H59', '36-40')
    ->setCellValue('I59', '41-45')
    ->setCellValue('J59', '46-50')
    ->setCellValue('K59', '51-55')
    ->setCellValue('L59', '>55')
    ->setCellValue('M59', 'Total')
    ->setCellValue('B60', 'YOS')
    ->setCellValue('C60', '0-2')
    ->setCellValue('C61', '3-6')
    ->setCellValue('C62', '7-10')
    ->setCellValue('C63', '11-15')
    ->setCellValue('C64', '16-20')
    ->setCellValue('C65', '21-25')
    ->setCellValue('C66', '26-31')
    ->setCellValue('C67', '>31')
    ->setCellValue('B68', 'Total')

    ->setCellValue('B71', 'Gol 6')
    ->setCellValue('D71', 'AGE')
    ->setCellValue('D72', '18-20')
    ->setCellValue('E72', '21-25')
    ->setCellValue('F72', '26-30')
    ->setCellValue('G72', '31-35')
    ->setCellValue('H72', '36-40')
    ->setCellValue('I72', '41-45')
    ->setCellValue('J72', '46-50')
    ->setCellValue('K72', '51-55')
    ->setCellValue('L72', '>55')
    ->setCellValue('M72', 'Total')
    ->setCellValue('B73', 'YOS')
    ->setCellValue('C73', '0-2')
    ->setCellValue('C74', '3-6')
    ->setCellValue('C75', '7-10')
    ->setCellValue('C76', '11-15')
    ->setCellValue('C77', '16-20')
    ->setCellValue('C78', '21-25')
    ->setCellValue('C79', '26-31')
    ->setCellValue('C80', '>31')
    ->setCellValue('B81', 'Total');



// Panggil fungsi untuk setiap rangkaian sel yang diinginkan
setSumFormulas(8, 16, 'M', 'D', 'L', $objPHPExcel);
setSumFormulas(21, 29, 'M', 'D', 'L', $objPHPExcel);
setSumFormulas(34, 42, 'M', 'D', 'L', $objPHPExcel);
setSumFormulas(47, 55, 'M', 'D', 'L', $objPHPExcel);
setSumFormulas(60, 68, 'M', 'D', 'L', $objPHPExcel);
setSumFormulas(73, 81, 'M', 'D', 'L', $objPHPExcel);


// Fungsi untuk mengatur rumus SUM pada kolom target dan baris yang ditentukan

setSumFormula($objPHPExcel, 'D', 'L', 16, 8, 15);
setSumFormula($objPHPExcel, 'D', 'L', 29, 21, 28);
setSumFormula($objPHPExcel, 'D', 'L', 42, 34, 41);
setSumFormula($objPHPExcel, 'D', 'L', 55, 47, 54);
setSumFormula($objPHPExcel, 'D', 'L', 68, 60, 67);
setSumFormula($objPHPExcel, 'D', 'L', 81, 73, 80);

$resultgol1 = mysqli_query($koneksi, $querygol1);
$row = mysqli_fetch_assoc($resultgol1);


$objPHPExcel->setActiveSheetIndex(2)
    ->setCellValue('D8', $row['Tetap1_02_1820'] != 0 ? $row['Tetap1_02_1820'] : '')
    ->setCellValue('E8', $row['Tetap1_02_2125'] != 0 ? $row['Tetap1_02_2125'] : '')
    ->setCellValue('F8', $row['Tetap1_02_2630'] != 0 ? $row['Tetap1_02_2630'] : '')
    ->setCellValue('G8', $row['Tetap1_02_3135'] != 0 ? $row['Tetap1_02_3135'] : '')
    ->setCellValue('H8', $row['Tetap1_02_3640'] != 0 ? $row['Tetap1_02_3640'] : '')
    ->setCellValue('I8', $row['Tetap1_02_4145'] != 0 ? $row['Tetap1_02_4145'] : '')
    ->setCellValue('J8', $row['Tetap1_02_4650'] != 0 ? $row['Tetap1_02_4650'] : '')
    ->setCellValue('K8', $row['Tetap1_02_5155'] != 0 ? $row['Tetap1_02_5155'] : '')
    ->setCellValue('L8', $row['Tetap1_02_55'] != 0 ? $row['Tetap1_02_55'] : '')

    ->setCellValue('D9', $row['Tetap1_36_1820'] != 0 ? $row['Tetap1_36_1820'] : '')
    ->setCellValue('E9', $row['Tetap1_36_2125'] != 0 ? $row['Tetap1_36_2125'] : '')
    ->setCellValue('F9', $row['Tetap1_36_2630'] != 0 ? $row['Tetap1_36_2630'] : '')
    ->setCellValue('G9', $row['Tetap1_36_3135'] != 0 ? $row['Tetap1_36_3135'] : '')
    ->setCellValue('H9', $row['Tetap1_36_3640'] != 0 ? $row['Tetap1_36_3640'] : '')
    ->setCellValue('I9', $row['Tetap1_36_4145'] != 0 ? $row['Tetap1_36_4145'] : '')
    ->setCellValue('J9', $row['Tetap1_36_4650'] != 0 ? $row['Tetap1_36_4650'] : '')
    ->setCellValue('K9', $row['Tetap1_36_5155'] != 0 ? $row['Tetap1_36_5155'] : '')
    ->setCellValue('L9', $row['Tetap1_36_55'] != 0 ? $row['Tetap1_36_55'] : '')

    ->setCellValue('D10', $row['Tetap1_710_1820'] != 0 ? $row['Tetap1_710_1820'] : '')
    ->setCellValue('E10', $row['Tetap1_710_2125'] != 0 ? $row['Tetap1_710_2125'] : '')
    ->setCellValue('F10', $row['Tetap1_710_2630'] != 0 ? $row['Tetap1_710_2630'] : '')
    ->setCellValue('G10', $row['Tetap1_710_3135'] != 0 ? $row['Tetap1_710_3135'] : '')
    ->setCellValue('H10', $row['Tetap1_710_3640'] != 0 ? $row['Tetap1_710_3640'] : '')
    ->setCellValue('I10', $row['Tetap1_710_4145'] != 0 ? $row['Tetap1_710_4145'] : '')
    ->setCellValue('J10', $row['Tetap1_710_4650'] != 0 ? $row['Tetap1_710_4650'] : '')
    ->setCellValue('K10', $row['Tetap1_710_5155'] != 0 ? $row['Tetap1_710_5155'] : '')
    ->setCellValue('L10', $row['Tetap1_710_55'] != 0 ? $row['Tetap1_710_55'] : '')

    ->setCellValue('D11', $row['Tetap1_1115_1820'] != 0 ? $row['Tetap1_1115_1820'] : '')
    ->setCellValue('E11', $row['Tetap1_1115_2125'] != 0 ? $row['Tetap1_1115_2125'] : '')
    ->setCellValue('F11', $row['Tetap1_1115_2630'] != 0 ? $row['Tetap1_1115_2630'] : '')
    ->setCellValue('G11', $row['Tetap1_1115_3135'] != 0 ? $row['Tetap1_1115_3135'] : '')
    ->setCellValue('H11', $row['Tetap1_1115_3640'] != 0 ? $row['Tetap1_1115_3640'] : '')
    ->setCellValue('I11', $row['Tetap1_1115_4145'] != 0 ? $row['Tetap1_1115_4145'] : '')
    ->setCellValue('J11', $row['Tetap1_1115_4650'] != 0 ? $row['Tetap1_1115_4650'] : '')
    ->setCellValue('K11', $row['Tetap1_1115_5155'] != 0 ? $row['Tetap1_1115_5155'] : '')
    ->setCellValue('L11', $row['Tetap1_1115_55'] != 0 ? $row['Tetap1_1115_55'] : '')

    ->setCellValue('D12', $row['Tetap1_1620_1820'] != 0 ? $row['Tetap1_1620_1820'] : '')
    ->setCellValue('E12', $row['Tetap1_1620_2125'] != 0 ? $row['Tetap1_1620_2125'] : '')
    ->setCellValue('F12', $row['Tetap1_1620_2630'] != 0 ? $row['Tetap1_1620_2630'] : '')
    ->setCellValue('G12', $row['Tetap1_1620_3135'] != 0 ? $row['Tetap1_1620_3135'] : '')
    ->setCellValue('H12', $row['Tetap1_1620_3640'] != 0 ? $row['Tetap1_1620_3640'] : '')
    ->setCellValue('I12', $row['Tetap1_1620_4145'] != 0 ? $row['Tetap1_1620_4145'] : '')
    ->setCellValue('J12', $row['Tetap1_1620_4650'] != 0 ? $row['Tetap1_1620_4650'] : '')
    ->setCellValue('K12', $row['Tetap1_1620_5155'] != 0 ? $row['Tetap1_1620_5155'] : '')
    ->setCellValue('L12', $row['Tetap1_1620_55'] != 0 ? $row['Tetap1_1620_55'] : '')

    ->setCellValue('D13', $row['Tetap1_2125_1820'] != 0 ? $row['Tetap1_2125_1820'] : '')
    ->setCellValue('E13', $row['Tetap1_2125_2125'] != 0 ? $row['Tetap1_2125_2125'] : '')
    ->setCellValue('F13', $row['Tetap1_2125_2630'] != 0 ? $row['Tetap1_2125_2630'] : '')
    ->setCellValue('G13', $row['Tetap1_2125_3135'] != 0 ? $row['Tetap1_2125_3135'] : '')
    ->setCellValue('H13', $row['Tetap1_2125_3640'] != 0 ? $row['Tetap1_2125_3640'] : '')
    ->setCellValue('I13', $row['Tetap1_2125_4145'] != 0 ? $row['Tetap1_2125_4145'] : '')
    ->setCellValue('J13', $row['Tetap1_2125_4650'] != 0 ? $row['Tetap1_2125_4650'] : '')
    ->setCellValue('K13', $row['Tetap1_2125_5155'] != 0 ? $row['Tetap1_2125_5155'] : '')
    ->setCellValue('L13', $row['Tetap1_2125_55'] != 0 ? $row['Tetap1_2125_55'] : '')

    ->setCellValue('D14', $row['Tetap1_2630_1820'] != 0 ? $row['Tetap1_2630_1820'] : '')
    ->setCellValue('E14', $row['Tetap1_2630_2125'] != 0 ? $row['Tetap1_2630_2125'] : '')
    ->setCellValue('F14', $row['Tetap1_2630_2630'] != 0 ? $row['Tetap1_2630_2630'] : '')
    ->setCellValue('G14', $row['Tetap1_2630_3135'] != 0 ? $row['Tetap1_2630_3135'] : '')
    ->setCellValue('H14', $row['Tetap1_2630_3640'] != 0 ? $row['Tetap1_2630_3640'] : '')
    ->setCellValue('I14', $row['Tetap1_2630_4145'] != 0 ? $row['Tetap1_2630_4145'] : '')
    ->setCellValue('J14', $row['Tetap1_2630_4650'] != 0 ? $row['Tetap1_2630_4650'] : '')
    ->setCellValue('K14', $row['Tetap1_2630_5155'] != 0 ? $row['Tetap1_2630_5155'] : '')
    ->setCellValue('L14', $row['Tetap1_2630_55'] != 0 ? $row['Tetap1_2630_55'] : '')

    ->setCellValue('D15', $row['Tetap1_31_1820'] != 0 ? $row['Tetap1_31_1820'] : '')
    ->setCellValue('E15', $row['Tetap1_31_2125'] != 0 ? $row['Tetap1_31_2125'] : '')
    ->setCellValue('F15', $row['Tetap1_31_2630'] != 0 ? $row['Tetap1_31_2630'] : '')
    ->setCellValue('G15', $row['Tetap1_31_3135'] != 0 ? $row['Tetap1_31_3135'] : '')
    ->setCellValue('H15', $row['Tetap1_31_3640'] != 0 ? $row['Tetap1_31_3640'] : '')
    ->setCellValue('I15', $row['Tetap1_31_4145'] != 0 ? $row['Tetap1_31_4145'] : '')
    ->setCellValue('J15', $row['Tetap1_31_4650'] != 0 ? $row['Tetap1_31_4650'] : '')
    ->setCellValue('K15', $row['Tetap1_31_5155'] != 0 ? $row['Tetap1_31_5155'] : '')
    ->setCellValue('L15', $row['Tetap1_31_55'] != 0 ? $row['Tetap1_31_55'] : '');


$resultgol2 = mysqli_query($koneksi, $querygol2);
$row = mysqli_fetch_assoc($resultgol2);
$objPHPExcel->setActiveSheetIndex(2)
    ->setCellValue('D21', $row['Tetap2_02_1820'] != 0 ? $row['Tetap2_02_1820'] : '')
    ->setCellValue('E21', $row['Tetap2_02_2125'] != 0 ? $row['Tetap2_02_2125'] : '')
    ->setCellValue('F21', $row['Tetap2_02_2630'] != 0 ? $row['Tetap2_02_2630'] : '')
    ->setCellValue('G21', $row['Tetap2_02_3135'] != 0 ? $row['Tetap2_02_3135'] : '')
    ->setCellValue('H21', $row['Tetap2_02_3640'] != 0 ? $row['Tetap2_02_3640'] : '')
    ->setCellValue('I21', $row['Tetap2_02_4145'] != 0 ? $row['Tetap2_02_4145'] : '')
    ->setCellValue('J21', $row['Tetap2_02_4650'] != 0 ? $row['Tetap2_02_4650'] : '')
    ->setCellValue('K21', $row['Tetap2_02_5155'] != 0 ? $row['Tetap2_02_5155'] : '')
    ->setCellValue('L21', $row['Tetap2_02_55'] != 0 ? $row['Tetap2_02_55'] : '')
    // Row 22
    ->setCellValue('D22', $row['Tetap2_36_1820'] != 0 ? $row['Tetap2_36_1820'] : '')
    ->setCellValue('E22', $row['Tetap2_36_2125'] != 0 ? $row['Tetap2_36_2125'] : '')
    ->setCellValue('F22', $row['Tetap2_36_2630'] != 0 ? $row['Tetap2_36_2630'] : '')
    ->setCellValue('G22', $row['Tetap2_36_3135'] != 0 ? $row['Tetap2_36_3135'] : '')
    ->setCellValue('H22', $row['Tetap2_36_3640'] != 0 ? $row['Tetap2_36_3640'] : '')
    ->setCellValue('I22', $row['Tetap2_36_4145'] != 0 ? $row['Tetap2_36_4145'] : '')
    ->setCellValue('J22', $row['Tetap2_36_4650'] != 0 ? $row['Tetap2_36_4650'] : '')
    ->setCellValue('K22', $row['Tetap2_36_5155'] != 0 ? $row['Tetap2_36_5155'] : '')
    ->setCellValue('L22', $row['Tetap2_36_55'] != 0 ? $row['Tetap2_36_55'] : '')
    // Row 23
    ->setCellValue('D23', $row['Tetap2_710_1820'] != 0 ? $row['Tetap2_710_1820'] : '')
    ->setCellValue('E23', $row['Tetap2_710_2125'] != 0 ? $row['Tetap2_710_2125'] : '')
    ->setCellValue('F23', $row['Tetap2_710_2630'] != 0 ? $row['Tetap2_710_2630'] : '')
    ->setCellValue('G23', $row['Tetap2_710_3135'] != 0 ? $row['Tetap2_710_3135'] : '')
    ->setCellValue('H23', $row['Tetap2_710_3640'] != 0 ? $row['Tetap2_710_3640'] : '')
    ->setCellValue('I23', $row['Tetap2_710_4145'] != 0 ? $row['Tetap2_710_4145'] : '')
    ->setCellValue('J23', $row['Tetap2_710_4650'] != 0 ? $row['Tetap2_710_4650'] : '')
    ->setCellValue('K23', $row['Tetap2_710_5155'] != 0 ? $row['Tetap2_710_5155'] : '')
    ->setCellValue('L23', $row['Tetap2_710_55'] != 0 ? $row['Tetap2_710_55'] : '')
    // Row 24
    ->setCellValue('D24', $row['Tetap2_1115_1820'] != 0 ? $row['Tetap2_1115_1820'] : '')
    ->setCellValue('E24', $row['Tetap2_1115_2125'] != 0 ? $row['Tetap2_1115_2125'] : '')
    ->setCellValue('F24', $row['Tetap2_1115_2630'] != 0 ? $row['Tetap2_1115_2630'] : '')
    ->setCellValue('G24', $row['Tetap2_1115_3135'] != 0 ? $row['Tetap2_1115_3135'] : '')
    ->setCellValue('H24', $row['Tetap2_1115_3640'] != 0 ? $row['Tetap2_1115_3640'] : '')
    ->setCellValue('I24', $row['Tetap2_1115_4145'] != 0 ? $row['Tetap2_1115_4145'] : '')
    ->setCellValue('J24', $row['Tetap2_1115_4650'] != 0 ? $row['Tetap2_1115_4650'] : '')
    ->setCellValue('K24', $row['Tetap2_1115_5155'] != 0 ? $row['Tetap2_1115_5155'] : '')
    ->setCellValue('L24', $row['Tetap2_1115_55'] != 0 ? $row['Tetap2_1115_55'] : '')
    // Row 25
    ->setCellValue('D25', $row['Tetap2_1620_1820'] != 0 ? $row['Tetap2_1620_1820'] : '')
    ->setCellValue('E25', $row['Tetap2_1620_2125'] != 0 ? $row['Tetap2_1620_2125'] : '')
    ->setCellValue('F25', $row['Tetap2_1620_2630'] != 0 ? $row['Tetap2_1620_2630'] : '')
    ->setCellValue('G25', $row['Tetap2_1620_3135'] != 0 ? $row['Tetap2_1620_3135'] : '')
    ->setCellValue('H25', $row['Tetap2_1620_3640'] != 0 ? $row['Tetap2_1620_3640'] : '')
    ->setCellValue('I25', $row['Tetap2_1620_4145'] != 0 ? $row['Tetap2_1620_4145'] : '')
    ->setCellValue('J25', $row['Tetap2_1620_4650'] != 0 ? $row['Tetap2_1620_4650'] : '')
    ->setCellValue('K25', $row['Tetap2_1620_5155'] != 0 ? $row['Tetap2_1620_5155'] : '')
    ->setCellValue('L25', $row['Tetap2_1620_55'] != 0 ? $row['Tetap2_1620_55'] : '')
    // Row 26
    ->setCellValue('D26', $row['Tetap2_2125_1820'] != 0 ? $row['Tetap2_2125_1820'] : '')
    ->setCellValue('E26', $row['Tetap2_2125_2125'] != 0 ? $row['Tetap2_2125_2125'] : '')
    ->setCellValue('F26', $row['Tetap2_2125_2630'] != 0 ? $row['Tetap2_2125_2630'] : '')
    ->setCellValue('G26', $row['Tetap2_2125_3135'] != 0 ? $row['Tetap2_2125_3135'] : '')
    ->setCellValue('H26', $row['Tetap2_2125_3640'] != 0 ? $row['Tetap2_2125_3640'] : '')
    ->setCellValue('I26', $row['Tetap2_2125_4145'] != 0 ? $row['Tetap2_2125_4145'] : '')
    ->setCellValue('J26', $row['Tetap2_2125_4650'] != 0 ? $row['Tetap2_2125_4650'] : '')
    ->setCellValue('K26', $row['Tetap2_2125_5155'] != 0 ? $row['Tetap2_2125_5155'] : '')
    ->setCellValue('L26', $row['Tetap2_2125_55'] != 0 ? $row['Tetap2_2125_55'] : '')
    // Row 27
    ->setCellValue('D27', $row['Tetap2_2630_1820'] != 0 ? $row['Tetap2_2630_1820'] : '')
    ->setCellValue('E27', $row['Tetap2_2630_2125'] != 0 ? $row['Tetap2_2630_2125'] : '')
    ->setCellValue('F27', $row['Tetap2_2630_2630'] != 0 ? $row['Tetap2_2630_2630'] : '')
    ->setCellValue('G27', $row['Tetap2_2630_3135'] != 0 ? $row['Tetap2_2630_3135'] : '')
    ->setCellValue('H27', $row['Tetap2_2630_3640'] != 0 ? $row['Tetap2_2630_3640'] : '')
    ->setCellValue('I27', $row['Tetap2_2630_4145'] != 0 ? $row['Tetap2_2630_4145'] : '')
    ->setCellValue('J27', $row['Tetap2_2630_4650'] != 0 ? $row['Tetap2_2630_4650'] : '')
    ->setCellValue('K27', $row['Tetap2_2630_5155'] != 0 ? $row['Tetap2_2630_5155'] : '')
    ->setCellValue('L27', $row['Tetap2_2630_55'] != 0 ? $row['Tetap2_2630_55'] : '')
    // Row 28
    ->setCellValue('D28', $row['Tetap2_31_1820'] != 0 ? $row['Tetap2_31_1820'] : '')
    ->setCellValue('E28', $row['Tetap2_31_2125'] != 0 ? $row['Tetap2_31_2125'] : '')
    ->setCellValue('F28', $row['Tetap2_31_2630'] != 0 ? $row['Tetap2_31_2630'] : '')
    ->setCellValue('G28', $row['Tetap2_31_3135'] != 0 ? $row['Tetap2_31_3135'] : '')
    ->setCellValue('H28', $row['Tetap2_31_3640'] != 0 ? $row['Tetap2_31_3640'] : '')
    ->setCellValue('I28', $row['Tetap2_31_4145'] != 0 ? $row['Tetap2_31_4145'] : '')
    ->setCellValue('J28', $row['Tetap2_31_4650'] != 0 ? $row['Tetap2_31_4650'] : '')
    ->setCellValue('K28', $row['Tetap2_31_5155'] != 0 ? $row['Tetap2_31_5155'] : '')
    ->setCellValue('L28', $row['Tetap2_31_55'] != 0 ? $row['Tetap2_31_55'] : '');

$resultgol3 = mysqli_query($koneksi, $querygol3);
$row = mysqli_fetch_assoc($resultgol3);
$objPHPExcel->setActiveSheetIndex(2)
    ->setCellValue('D34', $row['Tetap3_02_1820'] != 0 ? $row['Tetap3_02_1820'] : '')
    ->setCellValue('E34', $row['Tetap3_02_2125'] != 0 ? $row['Tetap3_02_2125'] : '')
    ->setCellValue('F34', $row['Tetap3_02_2630'] != 0 ? $row['Tetap3_02_2630'] : '')
    ->setCellValue('G34', $row['Tetap3_02_3135'] != 0 ? $row['Tetap3_02_3135'] : '')
    ->setCellValue('H34', $row['Tetap3_02_3640'] != 0 ? $row['Tetap3_02_3640'] : '')
    ->setCellValue('I34', $row['Tetap3_02_4145'] != 0 ? $row['Tetap3_02_4145'] : '')
    ->setCellValue('J34', $row['Tetap3_02_4650'] != 0 ? $row['Tetap3_02_4650'] : '')
    ->setCellValue('K34', $row['Tetap3_02_5155'] != 0 ? $row['Tetap3_02_5155'] : '')
    ->setCellValue('L34', $row['Tetap3_02_55'] != 0 ? $row['Tetap3_02_55'] : '')

    ->setCellValue('D35', $row['Tetap3_36_1820'] != 0 ? $row['Tetap3_36_1820'] : '')
    ->setCellValue('E35', $row['Tetap3_36_2125'] != 0 ? $row['Tetap3_36_2125'] : '')
    ->setCellValue('F35', $row['Tetap3_36_2630'] != 0 ? $row['Tetap3_36_2630'] : '')
    ->setCellValue('G35', $row['Tetap3_36_3135'] != 0 ? $row['Tetap3_36_3135'] : '')
    ->setCellValue('H35', $row['Tetap3_36_3640'] != 0 ? $row['Tetap3_36_3640'] : '')
    ->setCellValue('I35', $row['Tetap3_36_4145'] != 0 ? $row['Tetap3_36_4145'] : '')
    ->setCellValue('J35', $row['Tetap3_36_4650'] != 0 ? $row['Tetap3_36_4650'] : '')
    ->setCellValue('K35', $row['Tetap3_36_5155'] != 0 ? $row['Tetap3_36_5155'] : '')
    ->setCellValue('L35', $row['Tetap3_36_55'] != 0 ? $row['Tetap3_36_55'] : '')

    ->setCellValue('D36', $row['Tetap3_710_1820'] != 0 ? $row['Tetap3_710_1820'] : '')
    ->setCellValue('E36', $row['Tetap3_710_2125'] != 0 ? $row['Tetap3_710_2125'] : '')
    ->setCellValue('F36', $row['Tetap3_710_2630'] != 0 ? $row['Tetap3_710_2630'] : '')
    ->setCellValue('G36', $row['Tetap3_710_3135'] != 0 ? $row['Tetap3_710_3135'] : '')
    ->setCellValue('H36', $row['Tetap3_710_3640'] != 0 ? $row['Tetap3_710_3640'] : '')
    ->setCellValue('I36', $row['Tetap3_710_4145'] != 0 ? $row['Tetap3_710_4145'] : '')
    ->setCellValue('J36', $row['Tetap3_710_4650'] != 0 ? $row['Tetap3_710_4650'] : '')
    ->setCellValue('K36', $row['Tetap3_710_5155'] != 0 ? $row['Tetap3_710_5155'] : '')
    ->setCellValue('L36', $row['Tetap3_710_55'] != 0 ? $row['Tetap3_710_55'] : '')

    ->setCellValue('D37', $row['Tetap3_1115_1820'] != 0 ? $row['Tetap3_1115_1820'] : '')
    ->setCellValue('E37', $row['Tetap3_1115_2125'] != 0 ? $row['Tetap3_1115_2125'] : '')
    ->setCellValue('F37', $row['Tetap3_1115_2630'] != 0 ? $row['Tetap3_1115_2630'] : '')
    ->setCellValue('G37', $row['Tetap3_1115_3135'] != 0 ? $row['Tetap3_1115_3135'] : '')
    ->setCellValue('H37', $row['Tetap3_1115_3640'] != 0 ? $row['Tetap3_1115_3640'] : '')
    ->setCellValue('I37', $row['Tetap3_1115_4145'] != 0 ? $row['Tetap3_1115_4145'] : '')
    ->setCellValue('J37', $row['Tetap3_1115_4650'] != 0 ? $row['Tetap3_1115_4650'] : '')
    ->setCellValue('K37', $row['Tetap3_1115_5155'] != 0 ? $row['Tetap3_1115_5155'] : '')
    ->setCellValue('L37', $row['Tetap3_1115_55'] != 0 ? $row['Tetap3_1115_55'] : '')

    ->setCellValue('D38', $row['Tetap3_1620_1820'] != 0 ? $row['Tetap3_1620_1820'] : '')
    ->setCellValue('E38', $row['Tetap3_1620_2125'] != 0 ? $row['Tetap3_1620_2125'] : '')
    ->setCellValue('F38', $row['Tetap3_1620_2630'] != 0 ? $row['Tetap3_1620_2630'] : '')
    ->setCellValue('G38', $row['Tetap3_1620_3135'] != 0 ? $row['Tetap3_1620_3135'] : '')
    ->setCellValue('H38', $row['Tetap3_1620_3640'] != 0 ? $row['Tetap3_1620_3640'] : '')
    ->setCellValue('I38', $row['Tetap3_1620_4145'] != 0 ? $row['Tetap3_1620_4145'] : '')
    ->setCellValue('J38', $row['Tetap3_1620_4650'] != 0 ? $row['Tetap3_1620_4650'] : '')
    ->setCellValue('K38', $row['Tetap3_1620_5155'] != 0 ? $row['Tetap3_1620_5155'] : '')
    ->setCellValue('L38', $row['Tetap3_1620_55'] != 0 ? $row['Tetap3_1620_55'] : '')

    ->setCellValue('D39', $row['Tetap3_2125_1820'] != 0 ? $row['Tetap3_2125_1820'] : '')
    ->setCellValue('E39', $row['Tetap3_2125_2125'] != 0 ? $row['Tetap3_2125_2125'] : '')
    ->setCellValue('F39', $row['Tetap3_2125_2630'] != 0 ? $row['Tetap3_2125_2630'] : '')
    ->setCellValue('G39', $row['Tetap3_2125_3135'] != 0 ? $row['Tetap3_2125_3135'] : '')
    ->setCellValue('H39', $row['Tetap3_2125_3640'] != 0 ? $row['Tetap3_2125_3640'] : '')
    ->setCellValue('I39', $row['Tetap3_2125_4145'] != 0 ? $row['Tetap3_2125_4145'] : '')
    ->setCellValue('J39', $row['Tetap3_2125_4650'] != 0 ? $row['Tetap3_2125_4650'] : '')
    ->setCellValue('K39', $row['Tetap3_2125_5155'] != 0 ? $row['Tetap3_2125_5155'] : '')
    ->setCellValue('L39', $row['Tetap3_2125_55'] != 0 ? $row['Tetap3_2125_55'] : '')

    ->setCellValue('D40', $row['Tetap3_2630_1820'] != 0 ? $row['Tetap3_2630_1820'] : '')
    ->setCellValue('E40', $row['Tetap3_2630_2125'] != 0 ? $row['Tetap3_2630_2125'] : '')
    ->setCellValue('F40', $row['Tetap3_2630_2630'] != 0 ? $row['Tetap3_2630_2630'] : '')
    ->setCellValue('G40', $row['Tetap3_2630_3135'] != 0 ? $row['Tetap3_2630_3135'] : '')
    ->setCellValue('H40', $row['Tetap3_2630_3640'] != 0 ? $row['Tetap3_2630_3640'] : '')
    ->setCellValue('I40', $row['Tetap3_2630_4145'] != 0 ? $row['Tetap3_2630_4145'] : '')
    ->setCellValue('J40', $row['Tetap3_2630_4650'] != 0 ? $row['Tetap3_2630_4650'] : '')
    ->setCellValue('K40', $row['Tetap3_2630_5155'] != 0 ? $row['Tetap3_2630_5155'] : '')
    ->setCellValue('L40', $row['Tetap3_2630_55'] != 0 ? $row['Tetap3_2630_55'] : '')

    ->setCellValue('D41', $row['Tetap3_31_1820'] != 0 ? $row['Tetap3_31_1820'] : '')
    ->setCellValue('E41', $row['Tetap3_31_2125'] != 0 ? $row['Tetap3_31_2125'] : '')
    ->setCellValue('F41', $row['Tetap3_31_2630'] != 0 ? $row['Tetap3_31_2630'] : '')
    ->setCellValue('G41', $row['Tetap3_31_3135'] != 0 ? $row['Tetap3_31_3135'] : '')
    ->setCellValue('H41', $row['Tetap3_31_3640'] != 0 ? $row['Tetap3_31_3640'] : '')
    ->setCellValue('I41', $row['Tetap3_31_4145'] != 0 ? $row['Tetap3_31_4145'] : '')
    ->setCellValue('J41', $row['Tetap3_31_4650'] != 0 ? $row['Tetap3_31_4650'] : '')
    ->setCellValue('K41', $row['Tetap3_31_5155'] != 0 ? $row['Tetap3_31_5155'] : '')
    ->setCellValue('L41', $row['Tetap3_31_55'] != 0 ? $row['Tetap3_31_55'] : '');

$resultgol4 = mysqli_query($koneksi, $querygol4);
$row = mysqli_fetch_assoc($resultgol4);
$objPHPExcel->setActiveSheetIndex(2)
    ->setCellValue('D47', $row['Tetap4_02_1820'] != 0 ? $row['Tetap4_02_1820'] : '')
    ->setCellValue('E47', $row['Tetap4_02_2125'] != 0 ? $row['Tetap4_02_2125'] : '')
    ->setCellValue('F47', $row['Tetap4_02_2630'] != 0 ? $row['Tetap4_02_2630'] : '')
    ->setCellValue('G47', $row['Tetap4_02_3135'] != 0 ? $row['Tetap4_02_3135'] : '')
    ->setCellValue('H47', $row['Tetap4_02_3640'] != 0 ? $row['Tetap4_02_3640'] : '')
    ->setCellValue('I47', $row['Tetap4_02_4145'] != 0 ? $row['Tetap4_02_4145'] : '')
    ->setCellValue('J47', $row['Tetap4_02_4650'] != 0 ? $row['Tetap4_02_4650'] : '')
    ->setCellValue('K47', $row['Tetap4_02_5155'] != 0 ? $row['Tetap4_02_5155'] : '')
    ->setCellValue('L47', $row['Tetap4_02_55'] != 0 ? $row['Tetap4_02_55'] : '')

    ->setCellValue('D48', $row['Tetap4_36_1820'] != 0 ? $row['Tetap4_36_1820'] : '')
    ->setCellValue('E48', $row['Tetap4_36_2125'] != 0 ? $row['Tetap4_36_2125'] : '')
    ->setCellValue('F48', $row['Tetap4_36_2630'] != 0 ? $row['Tetap4_36_2630'] : '')
    ->setCellValue('G48', $row['Tetap4_36_3135'] != 0 ? $row['Tetap4_36_3135'] : '')
    ->setCellValue('H48', $row['Tetap4_36_3640'] != 0 ? $row['Tetap4_36_3640'] : '')
    ->setCellValue('I48', $row['Tetap4_36_4145'] != 0 ? $row['Tetap4_36_4145'] : '')
    ->setCellValue('J48', $row['Tetap4_36_4650'] != 0 ? $row['Tetap4_36_4650'] : '')
    ->setCellValue('K48', $row['Tetap4_36_5155'] != 0 ? $row['Tetap4_36_5155'] : '')
    ->setCellValue('L48', $row['Tetap4_36_55'] != 0 ? $row['Tetap4_36_55'] : '')

    ->setCellValue('D49', $row['Tetap4_710_1820'] != 0 ? $row['Tetap4_710_1820'] : '')
    ->setCellValue('E49', $row['Tetap4_710_2125'] != 0 ? $row['Tetap4_710_2125'] : '')
    ->setCellValue('F49', $row['Tetap4_710_2630'] != 0 ? $row['Tetap4_710_2630'] : '')
    ->setCellValue('G49', $row['Tetap4_710_3135'] != 0 ? $row['Tetap4_710_3135'] : '')
    ->setCellValue('H49', $row['Tetap4_710_3640'] != 0 ? $row['Tetap4_710_3640'] : '')
    ->setCellValue('I49', $row['Tetap4_710_4145'] != 0 ? $row['Tetap4_710_4145'] : '')
    ->setCellValue('J49', $row['Tetap4_710_4650'] != 0 ? $row['Tetap4_710_4650'] : '')
    ->setCellValue('K49', $row['Tetap4_710_5155'] != 0 ? $row['Tetap4_710_5155'] : '')
    ->setCellValue('L49', $row['Tetap4_710_55'] != 0 ? $row['Tetap4_710_55'] : '')

    ->setCellValue('D50', $row['Tetap4_1115_1820'] != 0 ? $row['Tetap4_1115_1820'] : '')
    ->setCellValue('E50', $row['Tetap4_1115_2125'] != 0 ? $row['Tetap4_1115_2125'] : '')
    ->setCellValue('F50', $row['Tetap4_1115_2630'] != 0 ? $row['Tetap4_1115_2630'] : '')
    ->setCellValue('G50', $row['Tetap4_1115_3135'] != 0 ? $row['Tetap4_1115_3135'] : '')
    ->setCellValue('H50', $row['Tetap4_1115_3640'] != 0 ? $row['Tetap4_1115_3640'] : '')
    ->setCellValue('I50', $row['Tetap4_1115_4145'] != 0 ? $row['Tetap4_1115_4145'] : '')
    ->setCellValue('J50', $row['Tetap4_1115_4650'] != 0 ? $row['Tetap4_1115_4650'] : '')
    ->setCellValue('K50', $row['Tetap4_1115_5155'] != 0 ? $row['Tetap4_1115_5155'] : '')
    ->setCellValue('L50', $row['Tetap4_1115_55'] != 0 ? $row['Tetap4_1115_55'] : '')

    ->setCellValue('D51', $row['Tetap4_1620_1820'] != 0 ? $row['Tetap4_1620_1820'] : '')
    ->setCellValue('E51', $row['Tetap4_1620_2125'] != 0 ? $row['Tetap4_1620_2125'] : '')
    ->setCellValue('F51', $row['Tetap4_1620_2630'] != 0 ? $row['Tetap4_1620_2630'] : '')
    ->setCellValue('G51', $row['Tetap4_1620_3135'] != 0 ? $row['Tetap4_1620_3135'] : '')
    ->setCellValue('H51', $row['Tetap4_1620_3640'] != 0 ? $row['Tetap4_1620_3640'] : '')
    ->setCellValue('I51', $row['Tetap4_1620_4145'] != 0 ? $row['Tetap4_1620_4145'] : '')
    ->setCellValue('J51', $row['Tetap4_1620_4650'] != 0 ? $row['Tetap4_1620_4650'] : '')
    ->setCellValue('K51', $row['Tetap4_1620_5155'] != 0 ? $row['Tetap4_1620_5155'] : '')
    ->setCellValue('L51', $row['Tetap4_1620_55'] != 0 ? $row['Tetap4_1620_55'] : '')

    ->setCellValue('D52', $row['Tetap4_2125_1820'] != 0 ? $row['Tetap4_2125_1820'] : '')
    ->setCellValue('E52', $row['Tetap4_2125_2125'] != 0 ? $row['Tetap4_2125_2125'] : '')
    ->setCellValue('F52', $row['Tetap4_2125_2630'] != 0 ? $row['Tetap4_2125_2630'] : '')
    ->setCellValue('G52', $row['Tetap4_2125_3135'] != 0 ? $row['Tetap4_2125_3135'] : '')
    ->setCellValue('H52', $row['Tetap4_2125_3640'] != 0 ? $row['Tetap4_2125_3640'] : '')
    ->setCellValue('I52', $row['Tetap4_2125_4145'] != 0 ? $row['Tetap4_2125_4145'] : '')
    ->setCellValue('J52', $row['Tetap4_2125_4650'] != 0 ? $row['Tetap4_2125_4650'] : '')
    ->setCellValue('K52', $row['Tetap4_2125_5155'] != 0 ? $row['Tetap4_2125_5155'] : '')
    ->setCellValue('L52', $row['Tetap4_2125_55'] != 0 ? $row['Tetap4_2125_55'] : '')

    ->setCellValue('D53', $row['Tetap4_2630_1820'] != 0 ? $row['Tetap4_2630_1820'] : '')
    ->setCellValue('E53', $row['Tetap4_2630_2125'] != 0 ? $row['Tetap4_2630_2125'] : '')
    ->setCellValue('F53', $row['Tetap4_2630_2630'] != 0 ? $row['Tetap4_2630_2630'] : '')
    ->setCellValue('G53', $row['Tetap4_2630_3135'] != 0 ? $row['Tetap4_2630_3135'] : '')
    ->setCellValue('H53', $row['Tetap4_2630_3640'] != 0 ? $row['Tetap4_2630_3640'] : '')
    ->setCellValue('I53', $row['Tetap4_2630_4145'] != 0 ? $row['Tetap4_2630_4145'] : '')
    ->setCellValue('J53', $row['Tetap4_2630_4650'] != 0 ? $row['Tetap4_2630_4650'] : '')
    ->setCellValue('K53', $row['Tetap4_2630_5155'] != 0 ? $row['Tetap4_2630_5155'] : '')
    ->setCellValue('L53', $row['Tetap4_2630_55'] != 0 ? $row['Tetap4_2630_55'] : '')

    ->setCellValue('D54', $row['Tetap4_31_1820'] != 0 ? $row['Tetap4_31_1820'] : '')
    ->setCellValue('E54', $row['Tetap4_31_2125'] != 0 ? $row['Tetap4_31_2125'] : '')
    ->setCellValue('F54', $row['Tetap4_31_2630'] != 0 ? $row['Tetap4_31_2630'] : '')
    ->setCellValue('G54', $row['Tetap4_31_3135'] != 0 ? $row['Tetap4_31_3135'] : '')
    ->setCellValue('H54', $row['Tetap4_31_3640'] != 0 ? $row['Tetap4_31_3640'] : '')
    ->setCellValue('I54', $row['Tetap4_31_4145'] != 0 ? $row['Tetap4_31_4145'] : '')
    ->setCellValue('J54', $row['Tetap4_31_4650'] != 0 ? $row['Tetap4_31_4650'] : '')
    ->setCellValue('K54', $row['Tetap4_31_5155'] != 0 ? $row['Tetap4_31_5155'] : '')
    ->setCellValue('L54', $row['Tetap4_31_55'] != 0 ? $row['Tetap4_31_55'] : '');

$resultgol5 = mysqli_query($koneksi, $querygol5);
$row = mysqli_fetch_assoc($resultgol5);
$objPHPExcel->setActiveSheetIndex(2)
    ->setCellValue('D60', $row['Tetap5_02_1820'] != 0 ? $row['Tetap5_02_1820'] : '')
    ->setCellValue('E60', $row['Tetap5_02_2125'] != 0 ? $row['Tetap5_02_2125'] : '')
    ->setCellValue('F60', $row['Tetap5_02_2630'] != 0 ? $row['Tetap5_02_2630'] : '')
    ->setCellValue('G60', $row['Tetap5_02_3135'] != 0 ? $row['Tetap5_02_3135'] : '')
    ->setCellValue('H60', $row['Tetap5_02_3640'] != 0 ? $row['Tetap5_02_3640'] : '')
    ->setCellValue('I60', $row['Tetap5_02_4145'] != 0 ? $row['Tetap5_02_4145'] : '')
    ->setCellValue('J60', $row['Tetap5_02_4650'] != 0 ? $row['Tetap5_02_4650'] : '')
    ->setCellValue('K60', $row['Tetap5_02_5155'] != 0 ? $row['Tetap5_02_5155'] : '')
    ->setCellValue('L60', $row['Tetap5_02_55'] != 0 ? $row['Tetap5_02_55'] : '')

    ->setCellValue('D61', $row['Tetap5_36_1820'] != 0 ? $row['Tetap5_36_1820'] : '')
    ->setCellValue('E61', $row['Tetap5_36_2125'] != 0 ? $row['Tetap5_36_2125'] : '')
    ->setCellValue('F61', $row['Tetap5_36_2630'] != 0 ? $row['Tetap5_36_2630'] : '')
    ->setCellValue('G61', $row['Tetap5_36_3135'] != 0 ? $row['Tetap5_36_3135'] : '')
    ->setCellValue('H61', $row['Tetap5_36_3640'] != 0 ? $row['Tetap5_36_3640'] : '')
    ->setCellValue('I61', $row['Tetap5_36_4145'] != 0 ? $row['Tetap5_36_4145'] : '')
    ->setCellValue('J61', $row['Tetap5_36_4650'] != 0 ? $row['Tetap5_36_4650'] : '')
    ->setCellValue('K61', $row['Tetap5_36_5155'] != 0 ? $row['Tetap5_36_5155'] : '')
    ->setCellValue('L61', $row['Tetap5_36_55'] != 0 ? $row['Tetap5_36_55'] : '')

    ->setCellValue('D62', $row['Tetap5_710_1820'] != 0 ? $row['Tetap5_710_1820'] : '')
    ->setCellValue('E62', $row['Tetap5_710_2125'] != 0 ? $row['Tetap5_710_2125'] : '')
    ->setCellValue('F62', $row['Tetap5_710_2630'] != 0 ? $row['Tetap5_710_2630'] : '')
    ->setCellValue('G62', $row['Tetap5_710_3135'] != 0 ? $row['Tetap5_710_3135'] : '')
    ->setCellValue('H62', $row['Tetap5_710_3640'] != 0 ? $row['Tetap5_710_3640'] : '')
    ->setCellValue('I62', $row['Tetap5_710_4145'] != 0 ? $row['Tetap5_710_4145'] : '')
    ->setCellValue('J62', $row['Tetap5_710_4650'] != 0 ? $row['Tetap5_710_4650'] : '')
    ->setCellValue('K62', $row['Tetap5_710_5155'] != 0 ? $row['Tetap5_710_5155'] : '')
    ->setCellValue('L62', $row['Tetap5_710_55'] != 0 ? $row['Tetap5_710_55'] : '')

    ->setCellValue('D63', $row['Tetap5_1115_1820'] != 0 ? $row['Tetap5_1115_1820'] : '')
    ->setCellValue('E63', $row['Tetap5_1115_2125'] != 0 ? $row['Tetap5_1115_2125'] : '')
    ->setCellValue('F63', $row['Tetap5_1115_2630'] != 0 ? $row['Tetap5_1115_2630'] : '')
    ->setCellValue('G63', $row['Tetap5_1115_3135'] != 0 ? $row['Tetap5_1115_3135'] : '')
    ->setCellValue('H63', $row['Tetap5_1115_3640'] != 0 ? $row['Tetap5_1115_3640'] : '')
    ->setCellValue('I63', $row['Tetap5_1115_4145'] != 0 ? $row['Tetap5_1115_4145'] : '')
    ->setCellValue('J63', $row['Tetap5_1115_4650'] != 0 ? $row['Tetap5_1115_4650'] : '')
    ->setCellValue('K63', $row['Tetap5_1115_5155'] != 0 ? $row['Tetap5_1115_5155'] : '')
    ->setCellValue('L63', $row['Tetap5_1115_55'] != 0 ? $row['Tetap5_1115_55'] : '')

    ->setCellValue('D64', $row['Tetap5_1620_1820'] != 0 ? $row['Tetap5_1620_1820'] : '')
    ->setCellValue('E64', $row['Tetap5_1620_2125'] != 0 ? $row['Tetap5_1620_2125'] : '')
    ->setCellValue('F64', $row['Tetap5_1620_2630'] != 0 ? $row['Tetap5_1620_2630'] : '')
    ->setCellValue('G64', $row['Tetap5_1620_3135'] != 0 ? $row['Tetap5_1620_3135'] : '')
    ->setCellValue('H64', $row['Tetap5_1620_3640'] != 0 ? $row['Tetap5_1620_3640'] : '')
    ->setCellValue('I64', $row['Tetap5_1620_4145'] != 0 ? $row['Tetap5_1620_4145'] : '')
    ->setCellValue('J64', $row['Tetap5_1620_4650'] != 0 ? $row['Tetap5_1620_4650'] : '')
    ->setCellValue('K64', $row['Tetap5_1620_5155'] != 0 ? $row['Tetap5_1620_5155'] : '')
    ->setCellValue('L64', $row['Tetap5_1620_55'] != 0 ? $row['Tetap5_1620_55'] : '')

    ->setCellValue('D65', $row['Tetap5_2125_1820'] != 0 ? $row['Tetap5_2125_1820'] : '')
    ->setCellValue('E65', $row['Tetap5_2125_2125'] != 0 ? $row['Tetap5_2125_2125'] : '')
    ->setCellValue('F65', $row['Tetap5_2125_2630'] != 0 ? $row['Tetap5_2125_2630'] : '')
    ->setCellValue('G65', $row['Tetap5_2125_3135'] != 0 ? $row['Tetap5_2125_3135'] : '')
    ->setCellValue('H65', $row['Tetap5_2125_3640'] != 0 ? $row['Tetap5_2125_3640'] : '')
    ->setCellValue('I65', $row['Tetap5_2125_4145'] != 0 ? $row['Tetap5_2125_4145'] : '')
    ->setCellValue('J65', $row['Tetap5_2125_4650'] != 0 ? $row['Tetap5_2125_4650'] : '')
    ->setCellValue('K65', $row['Tetap5_2125_5155'] != 0 ? $row['Tetap5_2125_5155'] : '')
    ->setCellValue('L65', $row['Tetap5_2125_55'] != 0 ? $row['Tetap5_2125_55'] : '')

    ->setCellValue('D66', $row['Tetap5_2630_1820'] != 0 ? $row['Tetap5_2630_1820'] : '')
    ->setCellValue('E66', $row['Tetap5_2630_2125'] != 0 ? $row['Tetap5_2630_2125'] : '')
    ->setCellValue('F66', $row['Tetap5_2630_2630'] != 0 ? $row['Tetap5_2630_2630'] : '')
    ->setCellValue('G66', $row['Tetap5_2630_3135'] != 0 ? $row['Tetap5_2630_3135'] : '')
    ->setCellValue('H66', $row['Tetap5_2630_3640'] != 0 ? $row['Tetap5_2630_3640'] : '')
    ->setCellValue('I66', $row['Tetap5_2630_4145'] != 0 ? $row['Tetap5_2630_4145'] : '')
    ->setCellValue('J66', $row['Tetap5_2630_4650'] != 0 ? $row['Tetap5_2630_4650'] : '')
    ->setCellValue('K66', $row['Tetap5_2630_5155'] != 0 ? $row['Tetap5_2630_5155'] : '')
    ->setCellValue('L66', $row['Tetap5_2630_55'] != 0 ? $row['Tetap5_2630_55'] : '')

    ->setCellValue('D67', $row['Tetap5_31_1820'] != 0 ? $row['Tetap5_31_1820'] : '')
    ->setCellValue('E67', $row['Tetap5_31_2125'] != 0 ? $row['Tetap5_31_2125'] : '')
    ->setCellValue('F67', $row['Tetap5_31_2630'] != 0 ? $row['Tetap5_31_2630'] : '')
    ->setCellValue('G67', $row['Tetap5_31_3135'] != 0 ? $row['Tetap5_31_3135'] : '')
    ->setCellValue('H67', $row['Tetap5_31_3640'] != 0 ? $row['Tetap5_31_3640'] : '')
    ->setCellValue('I67', $row['Tetap5_31_4145'] != 0 ? $row['Tetap5_31_4145'] : '')
    ->setCellValue('J67', $row['Tetap5_31_4650'] != 0 ? $row['Tetap5_31_4650'] : '')
    ->setCellValue('K67', $row['Tetap5_31_5155'] != 0 ? $row['Tetap5_31_5155'] : '')
    ->setCellValue('L67', $row['Tetap5_31_55'] != 0 ? $row['Tetap5_31_55'] : '');

$resultgol6 = mysqli_query($koneksi, $querygol6);
$row = mysqli_fetch_assoc($resultgol6);
$objPHPExcel->setActiveSheetIndex(2)
    ->setCellValue('D73', $row['Tetap6_02_1820'] != 0 ? $row['Tetap6_02_1820'] : '')
    ->setCellValue('E73', $row['Tetap6_02_2125'] != 0 ? $row['Tetap6_02_2125'] : '')
    ->setCellValue('F73', $row['Tetap6_02_2630'] != 0 ? $row['Tetap6_02_2630'] : '')
    ->setCellValue('G73', $row['Tetap6_02_3135'] != 0 ? $row['Tetap6_02_3135'] : '')
    ->setCellValue('H73', $row['Tetap6_02_3640'] != 0 ? $row['Tetap6_02_3640'] : '')
    ->setCellValue('I73', $row['Tetap6_02_4145'] != 0 ? $row['Tetap6_02_4145'] : '')
    ->setCellValue('J73', $row['Tetap6_02_4650'] != 0 ? $row['Tetap6_02_4650'] : '')
    ->setCellValue('K73', $row['Tetap6_02_5155'] != 0 ? $row['Tetap6_02_5155'] : '')
    ->setCellValue('L73', $row['Tetap6_02_55'] != 0 ? $row['Tetap6_02_55'] : '')

    ->setCellValue('D74', $row['Tetap6_36_1820'] != 0 ? $row['Tetap6_36_1820'] : '')
    ->setCellValue('E74', $row['Tetap6_36_2125'] != 0 ? $row['Tetap6_36_2125'] : '')
    ->setCellValue('F74', $row['Tetap6_36_2630'] != 0 ? $row['Tetap6_36_2630'] : '')
    ->setCellValue('G74', $row['Tetap6_36_3135'] != 0 ? $row['Tetap6_36_3135'] : '')
    ->setCellValue('H74', $row['Tetap6_36_3640'] != 0 ? $row['Tetap6_36_3640'] : '')
    ->setCellValue('I74', $row['Tetap6_36_4145'] != 0 ? $row['Tetap6_36_4145'] : '')
    ->setCellValue('J74', $row['Tetap6_36_4650'] != 0 ? $row['Tetap6_36_4650'] : '')
    ->setCellValue('K74', $row['Tetap6_36_5155'] != 0 ? $row['Tetap6_36_5155'] : '')
    ->setCellValue('L74', $row['Tetap6_36_55'] != 0 ? $row['Tetap6_36_55'] : '')

    ->setCellValue('D75', $row['Tetap6_710_1820'] != 0 ? $row['Tetap6_710_1820'] : '')
    ->setCellValue('E75', $row['Tetap6_710_2125'] != 0 ? $row['Tetap6_710_2125'] : '')
    ->setCellValue('F75', $row['Tetap6_710_2630'] != 0 ? $row['Tetap6_710_2630'] : '')
    ->setCellValue('G75', $row['Tetap6_710_3135'] != 0 ? $row['Tetap6_710_3135'] : '')
    ->setCellValue('H75', $row['Tetap6_710_3640'] != 0 ? $row['Tetap6_710_3640'] : '')
    ->setCellValue('I75', $row['Tetap6_710_4145'] != 0 ? $row['Tetap6_710_4145'] : '')
    ->setCellValue('J75', $row['Tetap6_710_4650'] != 0 ? $row['Tetap6_710_4650'] : '')
    ->setCellValue('K75', $row['Tetap6_710_5155'] != 0 ? $row['Tetap6_710_5155'] : '')
    ->setCellValue('L75', $row['Tetap6_710_55'] != 0 ? $row['Tetap6_710_55'] : '')

    ->setCellValue('D76', $row['Tetap6_1115_1820'] != 0 ? $row['Tetap6_1115_1820'] : '')
    ->setCellValue('E76', $row['Tetap6_1115_2125'] != 0 ? $row['Tetap6_1115_2125'] : '')
    ->setCellValue('F76', $row['Tetap6_1115_2630'] != 0 ? $row['Tetap6_1115_2630'] : '')
    ->setCellValue('G76', $row['Tetap6_1115_3135'] != 0 ? $row['Tetap6_1115_3135'] : '')
    ->setCellValue('H76', $row['Tetap6_1115_3640'] != 0 ? $row['Tetap6_1115_3640'] : '')
    ->setCellValue('I76', $row['Tetap6_1115_4145'] != 0 ? $row['Tetap6_1115_4145'] : '')
    ->setCellValue('J76', $row['Tetap6_1115_4650'] != 0 ? $row['Tetap6_1115_4650'] : '')
    ->setCellValue('K76', $row['Tetap6_1115_5155'] != 0 ? $row['Tetap6_1115_5155'] : '')
    ->setCellValue('L76', $row['Tetap6_1115_55'] != 0 ? $row['Tetap6_1115_55'] : '')

    ->setCellValue('D77', $row['Tetap6_1620_1820'] != 0 ? $row['Tetap6_1620_1820'] : '')
    ->setCellValue('E77', $row['Tetap6_1620_2125'] != 0 ? $row['Tetap6_1620_2125'] : '')
    ->setCellValue('F77', $row['Tetap6_1620_2630'] != 0 ? $row['Tetap6_1620_2630'] : '')
    ->setCellValue('G77', $row['Tetap6_1620_3135'] != 0 ? $row['Tetap6_1620_3135'] : '')
    ->setCellValue('H77', $row['Tetap6_1620_3640'] != 0 ? $row['Tetap6_1620_3640'] : '')
    ->setCellValue('I77', $row['Tetap6_1620_4145'] != 0 ? $row['Tetap6_1620_4145'] : '')
    ->setCellValue('J77', $row['Tetap6_1620_4650'] != 0 ? $row['Tetap6_1620_4650'] : '')
    ->setCellValue('K77', $row['Tetap6_1620_5155'] != 0 ? $row['Tetap6_1620_5155'] : '')
    ->setCellValue('L77', $row['Tetap6_1620_55'] != 0 ? $row['Tetap6_1620_55'] : '')

    ->setCellValue('D78', $row['Tetap6_2125_1820'] != 0 ? $row['Tetap6_2125_1820'] : '')
    ->setCellValue('E78', $row['Tetap6_2125_2125'] != 0 ? $row['Tetap6_2125_2125'] : '')
    ->setCellValue('F78', $row['Tetap6_2125_2630'] != 0 ? $row['Tetap6_2125_2630'] : '')
    ->setCellValue('G78', $row['Tetap6_2125_3135'] != 0 ? $row['Tetap6_2125_3135'] : '')
    ->setCellValue('H78', $row['Tetap6_2125_3640'] != 0 ? $row['Tetap6_2125_3640'] : '')
    ->setCellValue('I78', $row['Tetap6_2125_4145'] != 0 ? $row['Tetap6_2125_4145'] : '')
    ->setCellValue('J78', $row['Tetap6_2125_4650'] != 0 ? $row['Tetap6_2125_4650'] : '')
    ->setCellValue('K78', $row['Tetap6_2125_5155'] != 0 ? $row['Tetap6_2125_5155'] : '')
    ->setCellValue('L78', $row['Tetap6_2125_55'] != 0 ? $row['Tetap6_2125_55'] : '')

    ->setCellValue('D79', $row['Tetap6_2630_1820'] != 0 ? $row['Tetap6_2630_1820'] : '')
    ->setCellValue('E79', $row['Tetap6_2630_2125'] != 0 ? $row['Tetap6_2630_2125'] : '')
    ->setCellValue('F79', $row['Tetap6_2630_2630'] != 0 ? $row['Tetap6_2630_2630'] : '')
    ->setCellValue('G79', $row['Tetap6_2630_3135'] != 0 ? $row['Tetap6_2630_3135'] : '')
    ->setCellValue('H79', $row['Tetap6_2630_3640'] != 0 ? $row['Tetap6_2630_3640'] : '')
    ->setCellValue('I79', $row['Tetap6_2630_4145'] != 0 ? $row['Tetap6_2630_4145'] : '')
    ->setCellValue('J79', $row['Tetap6_2630_4650'] != 0 ? $row['Tetap6_2630_4650'] : '')
    ->setCellValue('K79', $row['Tetap6_2630_5155'] != 0 ? $row['Tetap6_2630_5155'] : '')
    ->setCellValue('L79', $row['Tetap6_2630_55'] != 0 ? $row['Tetap6_2630_55'] : '')

    ->setCellValue('D80', $row['Tetap6_31_1820'] != 0 ? $row['Tetap6_31_1820'] : '')
    ->setCellValue('E80', $row['Tetap6_31_2125'] != 0 ? $row['Tetap6_31_2125'] : '')
    ->setCellValue('F80', $row['Tetap6_31_2630'] != 0 ? $row['Tetap6_31_2630'] : '')
    ->setCellValue('G80', $row['Tetap6_31_3135'] != 0 ? $row['Tetap6_31_3135'] : '')
    ->setCellValue('H80', $row['Tetap6_31_3640'] != 0 ? $row['Tetap6_31_3640'] : '')
    ->setCellValue('I80', $row['Tetap6_31_4145'] != 0 ? $row['Tetap6_31_4145'] : '')
    ->setCellValue('J80', $row['Tetap6_31_4650'] != 0 ? $row['Tetap6_31_4650'] : '')
    ->setCellValue('K80', $row['Tetap6_31_5155'] != 0 ? $row['Tetap6_31_5155'] : '')
    ->setCellValue('L80', $row['Tetap6_31_55'] != 0 ? $row['Tetap6_31_55'] : '');



$objPHPExcel->createSheet(); // Membuat sheet baru
$objPHPExcel->setActiveSheetIndex(3); // Set aktif ke sheet baru

// Mengatur judul lembar baru
$objPHPExcel->getActiveSheet()->setTitle('Man Power Status');

$objPHPExcel->getDefaultStyle()->getFont()->setName('Century Gothic');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(9);
$sheet = $objPHPExcel->getActiveSheet();
$sheet->getDefaultColumnDimension()->setWidth(7); // Set the default width to 15 units
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(4);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);

$sheet = $objPHPExcel->getActiveSheet();
$sheet->freezePane('D5');


// Daftar sel yang akan digabungkan
$mergeCells = [
    'B1:C2',
    'D1:G2',
    'H1:K2',
    'B3:B4',
    'C3:C4',
    'D3:I3',
    'J3:Q3',
    'R3:S3'
];

// Menghapus sel duplikat jika ada
$mergeCells = array_unique($mergeCells);

// Menggabungkan sel sesuai dengan range yang telah ditentukan
foreach ($mergeCells as $mergeRange) {
    $objPHPExcel->getActiveSheet()->mergeCells($mergeRange);
}

// Add border to cells A1:D4 in the first sheet
$styleArray = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        ),
    ),
);
$styleArrayOutside = array(
    'borders' => array(
        'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
        ),
    ),
);


// Mengatur horizontal dan vertical alignment untuk 'B4'
$objPHPExcel->getActiveSheet()
    ->getStyle('B3:S4')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

// Mengatur horizontal dan vertical alignment untuk 'B4'
$objPHPExcel->getActiveSheet()
    ->getStyle('D5:S95')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('B5:B95')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

// Mengatur horizontal dan vertical alignment untuk 'B4'
$objPHPExcel->getActiveSheet()
    ->getStyle('D1:F2')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$bulanini = date('F'); // Mengambil nama bulan saat ini
$Tahunini = date('Y'); // Mengambil nama bulan saat ini
$objPHPExcel->setActiveSheetIndex(3)
    ->setCellValue('B1', "HUMAN RESOURCES DEV.\nPT KAYABA INDONESIA")
    ->setCellValue('D1', "MAN POWER STATUS")
    ->setCellValue('H1', "MONTH : $selectedMonthName                        Year      :  $selectedYear")
    ->setCellValue('B3', "NO")
    ->setCellValue('C3', "DEPARTEMEN/SECTION")
    ->setCellValue('D3', $previousMonthName)
    ->setCellValue('J3', $selectedMonthName)
    ->setCellValue('R3', 'MP PLAN')
    ->setCellValue('D4', 'III-IV')
    ->setCellValue('E4', 'T')
    ->setCellValue('F4', 'I-II')
    ->setCellValue('G4', 'C2')
    ->setCellValue('H4', 'C1')
    ->setCellValue('I4', 'Total')
    ->setCellValue('J4', 'III-IV')
    ->setCellValue('K4', 'T')
    ->setCellValue('L4', 'I-II')
    ->setCellValue('M4', 'C2')
    ->setCellValue('N4', 'C1')
    ->setCellValue('O4', 'Total')
    ->setCellValue('P4', 'IN/OUT')
    ->setCellValue('Q4', 'Mutasi')
    ->setCellValue('R4', '+/-')
    ->setCellValue('S4', $selectedYear)
    ->setCellValue('B5', 'I')
    ->setCellValue('C5', 'DIRECT LABOUR')
    ->setCellValue('C6', 'PRODUCTION 1');


$objPHPExcel->getActiveSheet()
    ->getStyle('B1')
    ->getAlignment()
    ->setWrapText(true);

$objPHPExcel->getActiveSheet()
    ->getStyle('H1')
    ->getAlignment()
    ->setWrapText(true);

$objPHPExcel->getActiveSheet()
    ->getStyle('B5:C6')
    ->getFont()
    ->setBold(true);

// Function to fetch and set production data
function fetchProductionData($koneksi, $cwoc, $start_row, &$objPHPExcel, $Excelbysect, $ExcelMutasiSectbysect, $ExcelMutasiInSectbysect, $ExcelInOutbysect, $ExcelbysectBulanLalu)
{
    $query = "SELECT sect.Id_sect, sect.desc, karyawan.sect, karyawan.cwoc
          FROM karyawan
          INNER JOIN sect ON karyawan.sect = sect.Id_sect
          WHERE karyawan.cwoc = '$cwoc'
          GROUP BY sect.Id_sect, sect.desc, karyawan.sect, karyawan.cwoc";

    $result = mysqli_query($koneksi, $query);

    $no = 1; // Start number

    while ($user_data = mysqli_fetch_array($result)) {
        $sect = isset($user_data['sect']) ? trim($user_data['sect']) : null;
        $desc = isset($user_data['desc']) ? $user_data['desc'] : null;



        // Retrieve data from $Excelbysect array
        $III = isset($Excelbysect[$sect]['III']) ? $Excelbysect[$sect]['III'] : 0;
        $T = isset($Excelbysect[$sect]['T']) ? $Excelbysect[$sect]['T'] : 0;
        $I_II = isset($Excelbysect[$sect]['I_II']) ? $Excelbysect[$sect]['I_II'] : 0;
        $C2 = isset($Excelbysect[$sect]['C2']) ? $Excelbysect[$sect]['C2'] : 0;
        $C1 = isset($Excelbysect[$sect]['C1']) ? $Excelbysect[$sect]['C1'] : 0;

        // Retrieve data from $ExcelbysectBulanLalu array
        $IIILalu = isset($ExcelbysectBulanLalu[$sect]['III']) ? $ExcelbysectBulanLalu[$sect]['III'] : 0;
        $TLalu = isset($ExcelbysectBulanLalu[$sect]['T']) ? $ExcelbysectBulanLalu[$sect]['T'] : 0;
        $I_IILalu = isset($ExcelbysectBulanLalu[$sect]['I_II']) ? $ExcelbysectBulanLalu[$sect]['I_II'] : 0;
        $C2Lalu = isset($ExcelbysectBulanLalu[$sect]['C2']) ? $ExcelbysectBulanLalu[$sect]['C2'] : 0;
        $C1Lalu = isset($ExcelbysectBulanLalu[$sect]['C1']) ? $ExcelbysectBulanLalu[$sect]['C1'] : 0;

        // Retrieve data from $ExcelMutasiSectbysect array
        $MutasiOut = isset($ExcelMutasiSectbysect[$sect]['MutasiOut']) ? $ExcelMutasiSectbysect[$sect]['MutasiOut'] : null;
        $MutasiIn = isset($ExcelMutasiInSectbysect[$sect]['MutasiIn']) ? $ExcelMutasiInSectbysect[$sect]['MutasiIn'] : null;

        // Retrieve data from $ExcelInOutbysect array
        $OUT = isset($ExcelInOutbysect[$sect]['OUT']) ? $ExcelInOutbysect[$sect]['OUT'] : '';
        $IN = isset($ExcelInOutbysect[$sect]['IN']) ? $ExcelInOutbysect[$sect]['IN'] : '';

        // Process MutasiOut and MutasiIn values
        $IN = isset($ExcelInOutbysect[$sect]['IN']) ? $ExcelInOutbysect[$sect]['IN'] : null;
        $OUT = isset($ExcelInOutbysect[$sect]['OUT']) ? $ExcelInOutbysect[$sect]['OUT'] : null;

        if ($MutasiOut === null) {
            $MutasiOut = '';
        } else {
            // Tambahkan "-" di depan nilai $MutasiOut jika tidak null
            $MutasiOut = '-' . $MutasiOut;
        }

        if ($MutasiIn === null) {
            $MutasiIn = '';
        } else {
            // Tambahkan "+" di depan nilai $MutasiIn jika tidak null
            $MutasiIn = "'+" . $MutasiIn;
        }

        // Gabungkan nilai $MutasiOut dan $MutasiIn
        $Mutasi = '';

        // Cek apakah keduanya tidak null
        if ($MutasiOut !== '' && $MutasiIn !== '') {
            $Mutasi = $MutasiIn . '/' . $MutasiOut;
        } else {
            // Jika salah satu atau keduanya null, gabungkan keduanya tanpa tanda "/"
            $Mutasi = $MutasiIn . $MutasiOut;
        }

        // Jika keduanya kosong (null), maka ubah menjadi string kosong ("")
        if ($Mutasi === '/') {
            $Mutasi = '';
        }

        //
        if ($OUT === 0 || $OUT === '0') { // Perubahan: Memeriksa apakah $OUT adalah 0 atau '0'
            $OUT = ''; // Diubah dari 0 menjadi string kosong
        } else {
            // Tambahkan "-" di depan nilai $OUT jika tidak null
            $OUT = '-' . $OUT;
        }

        if ($IN === 0 || $IN === '0') { // Perubahan: Memeriksa apakah $IN adalah 0 atau '0'
            $IN = ''; // Diubah dari 0 menjadi string kosong
        } else {
            // Tambahkan "+" di depan nilai $IN jika tidak null
            $IN = "'+" . $IN;
        }

        // Gabungkan nilai $OUT dan $IN
        $INOUT = '';

        // Cek apakah keduanya tidak kosong
        if ($OUT !== '' && $IN !== '') {
            $INOUT = $IN . '/' . $OUT;
        } else {
            // Jika salah satu atau keduanya kosong, gabungkan keduanya tanpa tanda "/"
            $INOUT = $IN . $OUT;
        }

        // Jika keduanya kosong (null), maka ubah menjadi string kosong ("")
        if ($INOUT === '/') {
            $INOUT = '';
        }

        // Set cell values in the Excel sheet
        $objPHPExcel->setActiveSheetIndex(3)
            ->setCellValue('B' . ($start_row), $no)
            ->setCellValue('C' . ($start_row), $desc)
            ->setCellValue('D' . ($start_row), $IIILalu)
            ->setCellValue('E' . ($start_row), $TLalu)
            ->setCellValue('F' . ($start_row), $I_IILalu)
            ->setCellValue('G' . ($start_row), $C2Lalu)
            ->setCellValue('H' . ($start_row), $C1Lalu)
            ->setCellValue('J' . ($start_row), $III)
            ->setCellValue('K' . ($start_row), $T)
            ->setCellValue('L' . ($start_row), $I_II)
            ->setCellValue('M' . ($start_row), $C2)
            ->setCellValue('N' . ($start_row), $C1)
            ->setCellValue('Q' . ($start_row), $Mutasi)
            ->setCellValue('P' . ($start_row), $INOUT);

        $start_row++; // Increment row for next data
        $no++; // Increment number
    }

    return $start_row; // Return the updated row index
}

$no = 1;
$start_row = 7; // Adjust based on your actual start row

// Fetch data for PRODUCTION 1
$start_row = fetchProductionData($koneksi, 'PRODUCTION 1', $start_row, $objPHPExcel, $Excelbysect, $ExcelMutasiSectbysect, $ExcelMutasiInSectbysect, $ExcelInOutbysect, $ExcelbysectBulanLalu);

// Set active sheet index
$objPHPExcel->setActiveSheetIndex(3);

// Set cell value for 'Sub Total Produksi 2' and apply bold and right alignment
$objPHPExcel->getActiveSheet()
    ->setCellValue('C' . $start_row, 'Sub Total Produksi 1')
    ->getStyle('C' . $start_row . ':O' . $start_row)
    ->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()
    ->getStyle('C' . $start_row)
    ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

$startRow1 = 7;
$endRow1 = $start_row - 1;
$subTotalRow1 = $start_row;

// Apply the sum formula for 'Sub Total Produksi 1'
setSumFormula($objPHPExcel, 'D', 'O', $subTotalRow1, $startRow1, $endRow1);

setSumFormulas($startRow1, $endRow1, 'I', 'D', 'H', $objPHPExcel);
setSumFormulas($startRow1, $endRow1, 'O', 'J', 'N', $objPHPExcel);


$start_row++; // Increment number for the next row

// Add Produksi 3 in column C, after Sub Total Produksi 2
$objPHPExcel->setActiveSheetIndex(3)
    ->setCellValue('C' . ($start_row), 'Produksi 2')
    ->getStyle('C' . $start_row)
    ->getFont()->setBold(true);

$start_row++; // Increment number for the next row

// Fetch data for PRODUCTION 2
$start_row = fetchProductionData($koneksi, 'PRODUCTION 2', $start_row, $objPHPExcel, $Excelbysect, $ExcelMutasiSectbysect, $ExcelMutasiInSectbysect, $ExcelInOutbysect, $ExcelbysectBulanLalu);

$objPHPExcel->getActiveSheet()
    ->setCellValue('C' . $start_row, 'Sub Total Produksi 2')
    ->getStyle('C' . $start_row . ':O' . $start_row)
    ->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()
    ->getStyle('C' . $start_row)
    ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

// Assume $startRow2 and $endRow2 are determined dynamically
$startRow2 = $subTotalRow1 + 2;
$endRow2 = $start_row - 1;
$subTotalRow2 = $start_row;

// Apply the sum formula for 'Sub Total Produksi 2'
setSumFormula($objPHPExcel, 'D', 'O', $subTotalRow2, $startRow2, $endRow2);

setSumFormulas($startRow2, $endRow2, 'I', 'D', 'H', $objPHPExcel);
setSumFormulas($startRow2, $endRow2, 'O', 'J', 'N', $objPHPExcel);

$start_row++; // Increment number for the next row

// Add Produksi 3 in column C, after Sub Total Produksi 2
$objPHPExcel->setActiveSheetIndex(3)
    ->setCellValue('C' . ($start_row), 'Produksi 3')
    ->getStyle('C' . $start_row)
    ->getFont()->setBold(true);

$start_row++; // Increment number for the next row

// Fetch data for PRODUCTION 2
$start_row = fetchProductionData($koneksi, 'PRODUCTION 3', $start_row, $objPHPExcel, $Excelbysect, $ExcelMutasiSectbysect, $ExcelMutasiInSectbysect, $ExcelInOutbysect, $ExcelbysectBulanLalu);

// Add Sub Total Produksi 2 right after PRODUCTION 2
$objPHPExcel->setActiveSheetIndex(3)
    ->setCellValue('C' . ($start_row), 'Sub Total Produksi 3')
    ->getStyle('C' . $start_row . ':O' . $start_row)
    ->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()
    ->getStyle('C' . $start_row)
    ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

$startRow3 = $subTotalRow2 + 2;
$endRow3 = $start_row - 1;
$subTotalRow3 = $start_row;

// Apply the sum formula for 'Sub Total Produksi 2'
setSumFormula($objPHPExcel, 'D', 'O', $subTotalRow3, $startRow3, $endRow3);

setSumFormulas($startRow3, $endRow3, 'I', 'D', 'H', $objPHPExcel);
setSumFormulas($startRow3, $endRow3, 'O', 'J', 'N', $objPHPExcel);

$start_row++; // Increment number for the next row

// Add Produksi 3 in column C, after Sub Total Produksi 2
$objPHPExcel->setActiveSheetIndex(3)
    ->setCellValue('C' . ($start_row), 'Produksi 4')
    ->getStyle('C' . $start_row)
    ->getFont()->setBold(true);

$start_row++; // Increment number for the next row

// Fetch data for PRODUCTION 2
$start_row = fetchProductionData($koneksi, 'PRODUCTION 4', $start_row, $objPHPExcel, $Excelbysect, $ExcelMutasiSectbysect, $ExcelMutasiInSectbysect, $ExcelInOutbysect, $ExcelbysectBulanLalu);

// Add Sub Total Produksi 2 right after PRODUCTION 2
$objPHPExcel->setActiveSheetIndex(3)
    ->setCellValue('C' . ($start_row), 'Sub Total Produksi 4')
    ->getStyle('C' . $start_row . ':O' . $start_row)
    ->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()
    ->getStyle('C' . $start_row)
    ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

$startRow4 = $subTotalRow3 + 2;
$endRow4 = $start_row - 1;
$subTotalRow4 = $start_row;

// Apply the sum formula for 'Sub Total Produksi 2'
setSumFormula($objPHPExcel, 'D', 'O', $subTotalRow4, $startRow4, $endRow4);

setSumFormulas($startRow4, $endRow4, 'I', 'D', 'H', $objPHPExcel);
setSumFormulas($startRow4, $endRow4, 'O', 'J', 'N', $objPHPExcel);

$start_row++; // Increment number for the next row

// Add Produksi 3 in column C, after Sub Total Produksi 2
$objPHPExcel->setActiveSheetIndex(3)
    ->setCellValue('C' . ($start_row), 'Produksi 5')
    ->getStyle('C' . $start_row)
    ->getFont()->setBold(true);

$start_row++; // Increment number for the next row

// Fetch data for PRODUCTION 2
$start_row = fetchProductionData($koneksi, 'PRODUCTION 5', $start_row, $objPHPExcel, $Excelbysect, $ExcelMutasiSectbysect, $ExcelMutasiInSectbysect, $ExcelInOutbysect, $ExcelbysectBulanLalu);

// Add Sub Total Produksi 2 right after PRODUCTION 2
$objPHPExcel->setActiveSheetIndex(3)
    ->setCellValue('C' . ($start_row), 'Sub Total Produksi 5')
    ->getStyle('C' . $start_row . ':O' . $start_row)
    ->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()
    ->getStyle('C' . $start_row)
    ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

$startRow5 = $subTotalRow4 + 2;
$endRow5 = $start_row - 1;
$subTotalRow5 = $start_row;

// Apply the sum formula for 'Sub Total Produksi 2'
setSumFormula($objPHPExcel, 'D', 'O', $subTotalRow5, $startRow5, $endRow5);

setSumFormulas($startRow5, $endRow5, 'I', 'D', 'H', $objPHPExcel);
setSumFormulas($startRow5, $endRow5, 'O', 'J', 'N', $objPHPExcel);

$start_row++; // Increment number for the next row

$subTotalSumRow = $start_row;
// Add Sub Total Produksi 2 right after PRODUCTION 2
$objPHPExcel->setActiveSheetIndex(3)
    ->setCellValue('C' . ($start_row), 'Total DL')
    ->getStyle('C' . $start_row . ':O' . $start_row)
    ->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()
    ->getStyle('C' . $start_row)
    ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

for ($col = 'D'; $col <= 'O'; $col++) {
    $formula = "=SUM($col$subTotalRow1,$col$subTotalRow2,$col$subTotalRow3,$col$subTotalRow4,$col$subTotalRow5)";
    $objPHPExcel->getActiveSheet()->setCellValue($col . $start_row, $formula);
}

$start_row++;

// Add Sub Total Produksi 2 right after PRODUCTION 2
$objPHPExcel->setActiveSheetIndex(3)
    ->setCellValue('B' . ($start_row), 'II')
    ->setCellValue('C' . ($start_row), 'Indirect Labour')
    ->getStyle('B' . $start_row . ':C' . $start_row)
    ->getFont()->setBold(true);

$start_row++; // Increment number for the next row

$queryIL = "SELECT karyawan.cwoc
                FROM karyawan
                WHERE karyawan.cwoc IN ('PRODUCTION 1','PRODUCTION 2','PRODUCTION 3','PRODUCTION 4','PRODUCTION 5')
                GROUP BY karyawan.cwoc
                ORDER BY FIELD(karyawan.cwoc, 'PRODUCTION 1','PRODUCTION 2','PRODUCTION 3','PRODUCTION 4','PRODUCTION 5')";


$resultIL = mysqli_query($koneksi, $queryIL);

$no = 1; // Start number

// Looping untuk mengisi nilai sel dalam objek Excel
while ($user_data = mysqli_fetch_array($resultIL)) {
    $sect = isset($user_data['sect']) ? $user_data['sect'] : null; // Inisialisasi $sect
    $cwoc = isset($user_data['cwoc']) ? $user_data['cwoc'] : null;
    $IV_VI = isset($ExcelDLbycwoc[$cwoc]['IV_VI']) ? $ExcelDLbycwoc[$cwoc]['IV_VI'] : 0;
    $T = isset($ExcelDLbycwoc[$cwoc]['T']) ? $ExcelDLbycwoc[$cwoc]['T'] : 0;
    $C2 = isset($ExcelDLbycwoc[$cwoc]['C2']) ? $ExcelDLbycwoc[$cwoc]['C2'] : 0;
    $C1 = isset($ExcelDLbycwoc[$cwoc]['C1']) ? $ExcelDLbycwoc[$cwoc]['C1'] : 0;

    $IV_VILalu = isset($ExcelDLbycwocLalu[$cwoc]['IV_VI']) ? $ExcelDLbycwocLalu[$cwoc]['IV_VI'] : 0;
    $TLalu = isset($ExcelDLbycwocLalu[$cwoc]['T']) ? $ExcelDLbycwocLalu[$cwoc]['T'] : 0;
    $C2Lalu = isset($ExcelDLbycwocLalu[$cwoc]['C2']) ? $ExcelDLbycwocLalu[$cwoc]['C2'] : 0;
    $C1Lalu = isset($ExcelDLbycwocLalu[$cwoc]['C1']) ? $ExcelDLbycwocLalu[$cwoc]['C1'] : 0;

    $jumlah_mutasi_out = isset($ExcelMutOut2bycwoc[$cwoc]['jumlah_mutasi_out']) ? $ExcelMutOut2bycwoc[$cwoc]['jumlah_mutasi_out'] : null;
    $jumlah_mutasi_in = isset($ExcelMutIn2bycwoc[$cwoc]['jumlah_mutasi_in']) ? $ExcelMutIn2bycwoc[$cwoc]['jumlah_mutasi_in'] : null;
    $IN = isset($ExcelInOut2bycwoc[$cwoc]['IN']) ? $ExcelInOut2bycwoc[$cwoc]['IN'] : 0;
    $OUT = isset($ExcelInOut2bycwoc[$cwoc]['OUT']) ? $ExcelInOut2bycwoc[$cwoc]['OUT'] : 0;

    if ($jumlah_mutasi_out === null) {
        $jumlah_mutasi_out = '';
    } else {
        // Tambahkan "-" di depan nilai $jumlah_mutasi_out jika tidak null
        $jumlah_mutasi_out = '-' . $jumlah_mutasi_out;
    }

    if ($jumlah_mutasi_in === null) {
        $jumlah_mutasi_in = '';
    } else {
        // Tambahkan "+" di depan nilai $jumlah_mutasi_in jika tidak null
        $jumlah_mutasi_in = "'+" . $jumlah_mutasi_in;
    }

    // Gabungkan nilai $jumlah_mutasi_out dan $jumlah_mutasi_in
    $nilai_sel = '';

    // Cek apakah keduanya tidak null
    if ($jumlah_mutasi_out !== '' && $jumlah_mutasi_in !== '') {
        $nilai_sel = $jumlah_mutasi_in . '/' . $jumlah_mutasi_out;
    } else {
        // Jika salah satu atau keduanya null, gabungkan keduanya tanpa tanda "/"
        $nilai_sel = $jumlah_mutasi_in . $jumlah_mutasi_out;
    }

    // Jika keduanya kosong (null), maka ubah menjadi string kosong ("")
    if ($nilai_sel === '/') {
        $nilai_sel = '';
    }

    //
    if ($OUT === 0 || $OUT === '0') {
        $OUT = '';
    } else {
        $OUT = '-' . $OUT;
    }

    if ($IN === 0 || $IN === '0') {
        $IN = '';
    } else {
        $IN = "'+" . $IN;
    }

    // Gabungkan nilai IN dan OUT
    $INOUT = '';

    if ($OUT !== '' && $IN !== '') {
        $INOUT = $IN . '/' . $OUT;
    } else {
        $INOUT = $IN . $OUT;
    }

    if ($INOUT === '/') {
        $INOUT = '';
    }

    // Set cell values
    $objPHPExcel->setActiveSheetIndex(3)
        ->setCellValue('B' . ($no + $start_row - 1), $no) // Set the row number in column B
        ->setCellValue('C' . ($no + $start_row - 1), $cwoc) // Set sect value in column C
        ->setCellValue('D' . ($no + $start_row - 1), $IV_VILalu)
        ->setCellValue('E' . ($no + $start_row - 1), $TLalu)
        ->setCellValue('F' . ($no + $start_row - 1), '0')
        ->setCellValue('G' . ($no + $start_row - 1), $C2Lalu)
        ->setCellValue('H' . ($no + $start_row - 1), $C1Lalu)
        ->setCellValue('J' . ($no + $start_row - 1), $IV_VI)
        ->setCellValue('K' . ($no + $start_row - 1), $T)
        ->setCellValue('L' . ($no + $start_row - 1), '0')
        ->setCellValue('M' . ($no + $start_row - 1), $C2)
        ->setCellValue('N' . ($no + $start_row - 1), $C1)
        ->setCellValue('Q' . ($no + $start_row - 1), $nilai_sel)
        ->setCellValue('P' . ($no + $start_row - 1), $INOUT);

    $no++; // Increment number
}

$queryIL2 = "SELECT karyawan.cwoc
                FROM karyawan
                WHERE karyawan.cwoc IN ('PRODUCTION SYSTEM','PPC','PCE','PE 2W','PE 4W','PDE 2W','PDE 4W','QA','CQE 2W','CQE 4W','GENERAL PURCHASE','PROCUREMENT','VENDOR DEVELOPMENT','WAREHOUSE')
                GROUP BY karyawan.cwoc
                ORDER BY FIELD(karyawan.cwoc, 'PRODUCTION SYSTEM','PPC','PCE','PE 2W','PE 4W','PDE 2W','PDE 4W','QA','CQE 2W','CQE 4W','GENERAL PURCHASE','PROCUREMENT','VENDOR DEVELOPMENT','WAREHOUSE')";


$resultIL2 = mysqli_query($koneksi, $queryIL2);

$no = 6; // Start number for the first section

// Loop to populate cell values in the Excel object
while ($user_data = mysqli_fetch_array($resultIL2)) {
    $sect = isset($user_data['sect']) ? $user_data['sect'] : null; // Initialize $sect
    $cwoc = isset($user_data['cwoc']) ? $user_data['cwoc'] : null;
    $III_VI = isset($ExcelIDL2bycwoc[$cwoc]['III_VI']) ? $ExcelIDL2bycwoc[$cwoc]['III_VI'] : 0;
    $I_II = isset($ExcelIDL2bycwoc[$cwoc]['I_II']) ? $ExcelIDL2bycwoc[$cwoc]['I_II'] : 0;
    $T = isset($ExcelIDL2bycwoc[$cwoc]['T']) ? $ExcelIDL2bycwoc[$cwoc]['T'] : 0;
    $C2 = isset($ExcelIDL2bycwoc[$cwoc]['C2']) ? $ExcelIDL2bycwoc[$cwoc]['C2'] : 0;
    $C1 = isset($ExcelIDL2bycwoc[$cwoc]['C1']) ? $ExcelIDL2bycwoc[$cwoc]['C1'] : 0;

    $III_VILalu = isset($ExcelIDL2bycwocLalu[$cwoc]['III_VI']) ? $ExcelIDL2bycwocLalu[$cwoc]['III_VI'] : 0;
    $I_IILalu = isset($ExcelIDL2bycwocLalu[$cwoc]['I_II']) ? $ExcelIDL2bycwocLalu[$cwoc]['I_II'] : 0;
    $TLalu = isset($ExcelIDL2bycwocLalu[$cwoc]['T']) ? $ExcelIDL2bycwocLalu[$cwoc]['T'] : 0;
    $C2Lalu = isset($ExcelIDL2bycwocLalu[$cwoc]['C2']) ? $ExcelIDL2bycwocLalu[$cwoc]['C2'] : 0;
    $C1Lalu = isset($ExcelIDL2bycwocLalu[$cwoc]['C1']) ? $ExcelIDL2bycwocLalu[$cwoc]['C1'] : 0;

    $jumlah_mutasi_out = isset($ExcelMutOutbycwoc[$cwoc]['jumlah_mutasi_out']) ? $ExcelMutOutbycwoc[$cwoc]['jumlah_mutasi_out'] : null;
    $jumlah_mutasi_in = isset($ExcelMutInbycwoc[$cwoc]['jumlah_mutasi_in']) ? $ExcelMutInbycwoc[$cwoc]['jumlah_mutasi_in'] : null;
    $IN = isset($ExcelInOutbycwoc[$cwoc]['IN']) ? $ExcelInOutbycwoc[$cwoc]['IN'] : null;
    $OUT = isset($ExcelInOutbycwoc[$cwoc]['OUT']) ? $ExcelInOutbycwoc[$cwoc]['OUT'] : null;

    if ($jumlah_mutasi_out === null) {
        $jumlah_mutasi_out = '';
    } else {
        // Add "-" in front of the value if not null
        $jumlah_mutasi_out = '-' . $jumlah_mutasi_out;
    }

    if ($jumlah_mutasi_in === null) {
        $jumlah_mutasi_in = '';
    } else {
        // Add "+" in front of the value if not null
        $jumlah_mutasi_in = "'+" . $jumlah_mutasi_in;
    }

    // Combine $jumlah_mutasi_out and $jumlah_mutasi_in
    $nilai_sel = '';

    // Check if both are not null
    if ($jumlah_mutasi_out !== '' && $jumlah_mutasi_in !== '') {
        $nilai_sel = $jumlah_mutasi_in . '/' . $jumlah_mutasi_out;
    } else {
        // If one or both are null, combine them without the "/" sign
        $nilai_sel = $jumlah_mutasi_in . $jumlah_mutasi_out;
    }

    // If both are empty (null), change to empty string ("")
    if ($nilai_sel === '/') {
        $nilai_sel = '';
    }

    //
    if ($OUT === 0 || $OUT === '0') { // Check if $OUT is 0 or '0'
        $OUT = ''; // Change from 0 to empty string
    } else {
        // Add "-" in front of the value if not null
        $OUT = '-' . $OUT;
    }

    if ($IN === 0 || $IN === '0') { // Check if $IN is 0 or '0'
        $IN = ''; // Change from 0 to empty string
    } else {
        // Add "+" in front of the value if not null
        $IN = "'+" . $IN;
    }

    // Combine $OUT and $IN
    $INOUT = '';

    // Check if both are not empty
    if ($OUT !== '' && $IN !== '') {
        $INOUT = $IN . '/' . $OUT;
    } else {
        // If one or both are empty, combine them without the "/" sign
        $INOUT = $IN . $OUT;
    }

    // If both are empty (null), change to empty string ("")
    if ($INOUT === '/') {
        $INOUT = '';
    }

    // Set cell values
    $objPHPExcel->setActiveSheetIndex(3)
        ->setCellValue('B' . ($no + $start_row - 1), $no) // Set the row number in column B
        ->setCellValue('C' . ($no + $start_row - 1), $cwoc) // Set sect value in column C
        ->setCellValue('D' . ($no + $start_row - 1), $III_VILalu)
        ->setCellValue('E' . ($no + $start_row - 1), $TLalu)
        ->setCellValue('F' . ($no + $start_row - 1), $I_IILalu)
        ->setCellValue('G' . ($no + $start_row - 1), $C2Lalu)
        ->setCellValue('H' . ($no + $start_row - 1), $C1Lalu)
        ->setCellValue('J' . ($no + $start_row - 1), $III_VI)
        ->setCellValue('K' . ($no + $start_row - 1), $T)
        ->setCellValue('L' . ($no + $start_row - 1), $I_II)
        ->setCellValue('M' . ($no + $start_row - 1), $C2)
        ->setCellValue('N' . ($no + $start_row - 1), $C1)
        ->setCellValue('Q' . ($no + $start_row - 1), $nilai_sel)
        ->setCellValue('P' . ($no + $start_row - 1), $INOUT);

    $no++; // Increment number
}

$start_row += $no;

$objPHPExcel->setActiveSheetIndex(3)
    ->setCellValue('C' . ($start_row - 1), 'Total IDL')
    ->getStyle('C' . ($start_row - 1) . ':O' . ($start_row - 1))
    ->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()
    ->getStyle('C' . ($start_row - 1))
    ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

$startRow6 = $subTotalRow5 + 3;
$endRow6 = $start_row - 2;
$TotalIDLRow = $start_row - 1;

// Apply the sum formula for 'Sub Total Produksi 2'
setSumFormula($objPHPExcel, 'D', 'O', $TotalIDLRow, $startRow6, $endRow6);

setSumFormulas($startRow6, $endRow6, 'I', 'D', 'H', $objPHPExcel);
setSumFormulas($startRow6, $endRow6, 'O', 'J', 'N', $objPHPExcel);

$start_row++; // Increment number for the next row

$objPHPExcel->setActiveSheetIndex(3)
    ->setCellValue('B' . ($start_row - 1), 'III')
    ->setCellValue('C' . ($start_row - 1), 'GENERAL ADMINISTRATION')
    ->getStyle('B' . ($start_row - 1) . ':C' . ($start_row - 1))
    ->getFont()->setBold(true);

$start_row++; // Increment number for the next row

$queryGA = "SELECT karyawan.cwoc
FROM karyawan
WHERE karyawan.cwoc IN ('FINANCE ACCOUNTING', 'PLANNING BUDGETING', 'HRD IR', 'GA', 'EHS','COMITTEE QCC - SS', 'PDCA CPC', 'MARKETING', 'MIS', 'BOD INDONESIA')
GROUP BY karyawan.cwoc
ORDER BY FIELD(karyawan.cwoc,'FINANCE ACCOUNTING', 'PLANNING BUDGETING', 'HRD IR', 'GA', 'EHS','COMITTEE QCC - SS', 'PDCA CPC', 'MARKETING', 'MIS', 'BOD INDONESIA')";

$resultGA = mysqli_query($koneksi, $queryGA);

$no = 1; // Initialize variable $no for GENERAL ADMINISTRATION

while ($user_data = mysqli_fetch_array($resultGA)) {
    $cwoc = isset($user_data['cwoc']) ? $user_data['cwoc'] : null;

    // Replace cwoc values using str_replace
    $display_text = str_replace(
        ['HRD IR', 'FINANCE ACCOUNTING', 'PLANNING BUDGETING', 'PDCA CPC'],
        ['HRD & IR', 'FINANCE & ACCOUNTING', 'PLANNING & BUDGETING', 'PDCA & CPC'],
        $cwoc
    );

    // Set default values if the array keys do not exist
    $III_VI = isset($ExcelIDL2bycwoc[$cwoc]['III_VI']) ? $ExcelIDL2bycwoc[$cwoc]['III_VI'] : 0;
    $I_II = isset($ExcelIDL2bycwoc[$cwoc]['I_II']) ? $ExcelIDL2bycwoc[$cwoc]['I_II'] : 0;
    $T = isset($ExcelIDL2bycwoc[$cwoc]['T']) ? $ExcelIDL2bycwoc[$cwoc]['T'] : 0;
    $C2 = isset($ExcelIDL2bycwoc[$cwoc]['C2']) ? $ExcelIDL2bycwoc[$cwoc]['C2'] : 0;
    $C1 = isset($ExcelIDL2bycwoc[$cwoc]['C1']) ? $ExcelIDL2bycwoc[$cwoc]['C1'] : 0;

    $III_VILalu = isset($ExcelIDL2bycwocLalu[$cwoc]['III_VI']) ? $ExcelIDL2bycwocLalu[$cwoc]['III_VI'] : 0;
    $I_IILalu = isset($ExcelIDL2bycwocLalu[$cwoc]['I_II']) ? $ExcelIDL2bycwocLalu[$cwoc]['I_II'] : 0;
    $TLalu = isset($ExcelIDL2bycwocLalu[$cwoc]['T']) ? $ExcelIDL2bycwocLalu[$cwoc]['T'] : 0;
    $C2Lalu = isset($ExcelIDL2bycwocLalu[$cwoc]['C2']) ? $ExcelIDL2bycwocLalu[$cwoc]['C2'] : 0;
    $C1Lalu = isset($ExcelIDL2bycwocLalu[$cwoc]['C1']) ? $ExcelIDL2bycwocLalu[$cwoc]['C1'] : 0;

    $jumlah_mutasi_out = isset($ExcelMutOutbycwoc[$cwoc]['jumlah_mutasi_out']) ? $ExcelMutOutbycwoc[$cwoc]['jumlah_mutasi_out'] : null;
    $jumlah_mutasi_in = isset($ExcelMutInbycwoc[$cwoc]['jumlah_mutasi_in']) ? $ExcelMutInbycwoc[$cwoc]['jumlah_mutasi_in'] : null;
    $IN = isset($ExcelInOutbycwoc[$cwoc]['IN']) ? $ExcelInOutbycwoc[$cwoc]['IN'] : null;
    $OUT = isset($ExcelInOutbycwoc[$cwoc]['OUT']) ? $ExcelInOutbycwoc[$cwoc]['OUT'] : null;

    if ($jumlah_mutasi_out === null) {
        $jumlah_mutasi_out = '';
    } else {
        // Tambahkan "-" di depan nilai $jumlah_mutasi_out jika tidak null
        $jumlah_mutasi_out = '-' . $jumlah_mutasi_out;
    }

    if ($jumlah_mutasi_in === null) {
        $jumlah_mutasi_in = '';
    } else {
        // Tambahkan "+" di depan nilai $jumlah_mutasi_in jika tidak null
        $jumlah_mutasi_in = "'+" . $jumlah_mutasi_in;
    }

    // Gabungkan nilai $jumlah_mutasi_out dan $jumlah_mutasi_in
    $nilai_sel = '';

    // Cek apakah keduanya tidak null
    if ($jumlah_mutasi_out !== '' && $jumlah_mutasi_in !== '') {
        $nilai_sel = $jumlah_mutasi_in . '/' . $jumlah_mutasi_out;
    } else {
        // Jika salah satu atau keduanya null, gabungkan keduanya tanpa tanda "/"
        $nilai_sel = $jumlah_mutasi_in . $jumlah_mutasi_out;
    }

    // Jika keduanya kosong (null), maka ubah menjadi string kosong ("")
    if ($nilai_sel === '/') {
        $nilai_sel = '';
    }

    //
    if ($OUT === 0 || $OUT === '0') {
        $OUT = '';
    } else {
        $OUT = '-' . $OUT;
    }

    if ($IN === 0 || $IN === '0') {
        $IN = '';
    } else {
        $IN = "'+" . $IN;
    }

    // Gabungkan nilai IN dan OUT
    $INOUT = '';

    if ($OUT !== '' && $IN !== '') {
        $INOUT = $OUT . '/' . $IN;
    } else {
        $INOUT = $IN . $OUT;
    }

    if ($INOUT === '/') {
        $INOUT = '';
    }

    // Set cell values
    $objPHPExcel->setActiveSheetIndex(3)
        ->setCellValue('B' . ($no + $start_row - 2), $no) // Set the row number in column B
        ->setCellValue('C' . ($no + $start_row - 2), $display_text) // Set sect value in column C
        ->setCellValue('D' . ($no + $start_row - 2), $III_VILalu)
        ->setCellValue('E' . ($no + $start_row - 2), $TLalu)
        ->setCellValue('F' . ($no + $start_row - 2), $I_IILalu)
        ->setCellValue('G' . ($no + $start_row - 2), $C2Lalu)
        ->setCellValue('H' . ($no + $start_row - 2), $C1Lalu)
        ->setCellValue('J' . ($no + $start_row - 2), $III_VI)
        ->setCellValue('K' . ($no + $start_row - 2), $T)
        ->setCellValue('L' . ($no + $start_row - 2), $I_II)
        ->setCellValue('M' . ($no + $start_row - 2), $C2)
        ->setCellValue('N' . ($no + $start_row - 2), $C1)
        ->setCellValue('Q' . ($no + $start_row - 2), $nilai_sel)
        ->setCellValue('P' . ($no + $start_row - 2), $INOUT);

    $styleArray = array(
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
            ),
        ),
    );

    $styleArrayOutside = array(
        'borders' => array(
            'outline' => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
            ),
        ),
    );

    $range = 'B3:S' . ($no + $start_row + 2);

    $objPHPExcel->getActiveSheet()
        ->getStyle($range)
        ->applyFromArray($styleArray); // Apply thin borders to all cells

    $objPHPExcel->getActiveSheet()
        ->getStyle($range)
        ->applyFromArray($styleArrayOutside); // Apply double borders to the outer edges

    $objPHPExcel->getActiveSheet()->getStyle('I7:I' . ($no + $start_row + 2))->applyFromArray(
        array(
            'font' => array(
                'bold' => true
            )
        )
    );

    $objPHPExcel->getActiveSheet()->getStyle('O7:O' . ($no + $start_row + 2))->applyFromArray(
        array(
            'font' => array(
                'bold' => true
            )
        )
    );
    $no++; // Increment number
}

$start_row += $no;

$resultBODIndoLalu = mysqli_query($koneksi, $queryBODIndoLalu);
$row = mysqli_fetch_assoc($resultBODIndoLalu);
$objPHPExcel->setActiveSheetIndex(3)
    ->setCellValue('B' . ($start_row - 2), $no)
    ->setCellValue('C' . ($start_row - 2), 'BOD INDONESIA')
    ->setCellValue('D' . ($start_row - 2), isset($row['III_VILalu']) ? $row['III_VILalu'] : 0)
    ->setCellValue('E' . ($start_row - 2), isset($row['TLalu']) ? $row['TLalu'] : 0)
    ->setCellValue('F' . ($start_row - 2), isset($row['I_IILalu']) ? $row['I_IILalu'] : 0)
    ->setCellValue('G' . ($start_row - 2), isset($row['C2Lalu']) ? $row['C2Lalu'] : 0)
    ->setCellValue('H' . ($start_row - 2), isset($row['C1Lalu']) ? $row['C1Lalu'] : 0)
    ->getStyle('D' . ($start_row - 2) . ':O' . ($start_row - 2));

$resultBODIndo = mysqli_query($koneksi, $queryBODIndo);
$row = mysqli_fetch_assoc($resultBODIndo);
$objPHPExcel->setActiveSheetIndex(3)
    ->setCellValue('J' . ($start_row - 2), isset($row['III_VI']) ? $row['III_VI'] : 0)
    ->setCellValue('K' . ($start_row - 2), isset($row['T']) ? $row['T'] : 0)
    ->setCellValue('L' . ($start_row - 2), isset($row['I_II']) ? $row['I_II'] : 0)
    ->setCellValue('M' . ($start_row - 2), isset($row['C2']) ? $row['C2'] : 0)
    ->setCellValue('N' . ($start_row - 2), isset($row['C1']) ? $row['C1'] : 0);

$resultInOutBODIndo = mysqli_query($koneksi, $queryInOutBODIndo);
$row = mysqli_fetch_assoc($resultInOutBODIndo);

// Handle IN value
$IN = ($row['IN'] != 0) ? "'+" . $row['IN'] : '';

// Handle OUT value
$OUT = ($row['OUT'] != 0) ? '-' . $row['OUT'] : '';

// Combine IN and OUT values
$INOUT = ($IN || $OUT) ? $IN . '/' . $OUT : '';
if ($INOUT === '/') {
    $INOUT = '';
}

// Set the cell value in the Excel sheet
$objPHPExcel->setActiveSheetIndex(3)
    ->setCellValue('P' . ($start_row - 2), $INOUT);

$resultMutInBODIndo = mysqli_query($koneksi3, $queryMutInBODIndo);
$resultMutOutBODIndo = mysqli_query($koneksi3, $queryMutOutBODIndo);

$rowMutIn = mysqli_fetch_assoc($resultMutInBODIndo);
$rowMutOut = mysqli_fetch_assoc($resultMutOutBODIndo);

// Handle IN value
$IN = ($rowMutIn['jumlah_mutasi_in'] != 0) ? '+' . $rowMutIn['jumlah_mutasi_in'] : '';

// Handle OUT value
$OUT = ($rowMutOut['jumlah_mutasi_out'] != 0) ? '-' . $rowMutOut['jumlah_mutasi_out'] : '';

// Combine IN and OUT values
$INOUT = ($IN || $OUT) ? $IN . '/' . $OUT : '';

// Check if $INOUT is empty to avoid setting '/'
if ($INOUT === '/') {
    $INOUT = '';
}

// Set the cell value in the Excel sheet
$objPHPExcel->setActiveSheetIndex(3)
    ->setCellValue('Q' . ($start_row - 2), $INOUT);


$start_row++; // Increment number for the next row

$resultBODEXPLalu = mysqli_query($koneksi, $queryBODEXPLalu);
$row = mysqli_fetch_assoc($resultBODEXPLalu);
$objPHPExcel->setActiveSheetIndex(3)
    ->setCellValue('B' . ($start_row - 2), $no + 1)
    ->setCellValue('C' . ($start_row - 2), 'BOD & TA EXP')
    ->setCellValue('D' . ($start_row - 2), isset($row['III_VILalu']) ? $row['III_VILalu'] : 0)
    ->setCellValue('E' . ($start_row - 2), '0')
    ->setCellValue('F' . ($start_row - 2), isset($row['I_IILalu']) ? $row['I_IILalu'] : 0)
    ->setCellValue('G' . ($start_row - 2), '0')
    ->setCellValue('H' . ($start_row - 2), '0')
    ->getStyle('D' . ($start_row - 2) . ':O' . ($start_row - 2));

$resultBODEXP = mysqli_query($koneksi, $queryBODEXP);
$row = mysqli_fetch_assoc($resultBODEXP);
$objPHPExcel->setActiveSheetIndex(3)
    ->setCellValue('J' . ($start_row - 2), isset($row['III_VI']) ? $row['III_VI'] : 0)
    ->setCellValue('K' . ($start_row - 2), '0')
    ->setCellValue('L' . ($start_row - 2), isset($row['I_II']) ? $row['I_II'] : 0)
    ->setCellValue('M' . ($start_row - 2), '0')
    ->setCellValue('N' . ($start_row - 2), '0');

$resultInOutBODEXP = mysqli_query($koneksi, $queryInOutBODEXP);
$row = mysqli_fetch_assoc($resultInOutBODEXP);

// Initialize variables
$IN = '';
$OUT = '';

// Handle IN value
if ($row['IN'] != 0) {
    $IN = "'+" . $row['IN'];
}

// Handle OUT value
if ($row['OUT'] != 0) {
    $OUT = '-' . $row['OUT'];
}

// Combine IN and OUT values
$INOUT = '';
if (!empty($IN) && !empty($OUT)) {
    $INOUT = $IN . '/' . $OUT;
} elseif (!empty($IN) || !empty($OUT)) {
    $INOUT = $IN . $OUT;
}

// Check if $INOUT is empty to avoid setting '/'
if ($INOUT === '/') {
    $INOUT = '';
}

// Set the cell value in the Excel sheet
$objPHPExcel->setActiveSheetIndex(3)
    ->setCellValue('P' . ($start_row - 2), $INOUT);


$resultMutInBODEXP = mysqli_query($koneksi3, $queryMutInBODEXP);
$resultMutOutBODEXP = mysqli_query($koneksi3, $queryMutOutBODEXP);

$rowMutIn = mysqli_fetch_assoc($resultMutInBODEXP);
$rowMutOut = mysqli_fetch_assoc($resultMutOutBODEXP);

// Initialize variables
$IN = '';
$OUT = '';

// Handle IN value
if ($rowMutIn['jumlah_mutasi_in'] != 0) {
    $IN = "'+" . $rowMutIn['jumlah_mutasi_in'];
}

// Handle OUT value
if ($rowMutOut['jumlah_mutasi_out'] != 0) {
    $OUT = '-' . $rowMutOut['jumlah_mutasi_out'];
}

// Combine IN and OUT values
$INOUT = '';
if (!empty($IN) && !empty($OUT)) {
    $INOUT = $IN . '/' . $OUT;
} elseif (!empty($IN) || !empty($OUT)) {
    $INOUT = $IN . $OUT;
}

// Check if $INOUT is empty to avoid setting '/'
if ($INOUT === '/') {
    $INOUT = '';
}

// Set the cell value in the Excel sheet
$objPHPExcel->setActiveSheetIndex(3)
    ->setCellValue('Q' . ($start_row - 2), $INOUT);


$start_row++; // Increment number for the next row

$objPHPExcel->setActiveSheetIndex(3)
    ->setCellValue('C' . ($start_row - 2), 'Total GA')
    ->getStyle('C' . ($start_row - 2) . ':O' . ($start_row - 2))
    ->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()
    ->getStyle('C' . ($start_row - 2))
    ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

$startRow7 = $TotalIDLRow + 2;
$endRow7 = $start_row - 3;
$TotalGARow = $start_row - 2;

// Apply the sum formula for 'Sub Total Produksi 2'
setSumFormula($objPHPExcel, 'D', 'O', $TotalGARow, $startRow7, $endRow7);

setSumFormulas($startRow7, $endRow7, 'I', 'D', 'H', $objPHPExcel);
setSumFormulas($startRow7, $endRow7, 'O', 'J', 'N', $objPHPExcel);

$start_row++; // Increment number for the next row

$objPHPExcel->setActiveSheetIndex(3)
    ->setCellValue('C' . ($start_row - 2), 'Grand Total');

$objPHPExcel->getActiveSheet()
    ->getStyle('C' . ($start_row - 2))
    ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

$objPHPExcel->getActiveSheet()
    ->getStyle('C' . ($start_row - 2) . ':O' . ($start_row - 2))
    ->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()
    ->getStyle('C' . ($start_row - 2))
    ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

for ($col = 'D'; $col <= 'O'; $col++) {
    $formula = "=SUM($col$subTotalSumRow,$col$TotalIDLRow,$col$TotalGARow)";
    $objPHPExcel->getActiveSheet()->setCellValue($col . ($start_row - 2), $formula);

    // Set background color for column I
    if ($col == 'I') {
        $objPHPExcel->getActiveSheet()
            ->getStyle($col . ($start_row - 2))
            ->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()->setRGB('FFFF00'); // Set to yellow color
    }

    // Set background color for column O
    if ($col == 'O') {
        $objPHPExcel->getActiveSheet()
            ->getStyle($col . ($start_row - 2))
            ->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()->setRGB('FFFF00'); // Set to yellow color
    }
}


$start_row++; // Increment number for the next row


$objPHPExcel->createSheet(); // Membuat sheet baru
$objPHPExcel->setActiveSheetIndex(4); // Set aktif ke sheet baru

// Mengatur judul lembar baru
$objPHPExcel->getActiveSheet()->setTitle('Employee Turn Over Status');

$objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(9);
$sheet = $objPHPExcel->getActiveSheet();
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);

$mergeCells = [
    'B3:B4',
    'C3:D3',
    'E3:F3',
    'G3:H3',
    'I3:J3',
    'K3:L3',
    'M3:N3',
    'O3:O4'
];

// Menggunakan array_unique untuk menghapus sel duplikat jika ada
$mergeCells = array_unique($mergeCells);

foreach ($mergeCells as $mergeRange) {
    $objPHPExcel->getActiveSheet()->mergeCells($mergeRange);
}

$objPHPExcel->getActiveSheet()
    ->getStyle('B3:O13')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('B3:O4')
    ->getFont()
    ->setBold(true);

$objPHPExcel->getActiveSheet()
    ->getStyle('C13:O13')
    ->getFont()
    ->setBold(true);

$styleArray = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        ),
    ),
);

$objPHPExcel->setActiveSheetIndex(4)
    ->setCellValue('B3', 'Golongan')
    ->setCellValue('C3', 'Kontrak Abis')
    ->setCellValue('E3', 'Dikeluarkan')
    ->setCellValue('G3', 'Kehendak Sendiri')
    ->setCellValue('I3', 'Mutasi Out')
    ->setCellValue('K3', 'Pensiun')
    ->setCellValue('M3', 'Meninggal')
    ->setCellValue('O3', 'Total')

    ->setCellValue('C4', 'Pria')
    ->setCellValue('D4', 'Wanita')
    ->setCellValue('E4', 'Pria')
    ->setCellValue('F4', 'Wanita')
    ->setCellValue('G4', 'Pria')
    ->setCellValue('H4', 'Wanita')
    ->setCellValue('I4', 'Pria')
    ->setCellValue('J4', 'Wanita')
    ->setCellValue('K4', 'Pria')
    ->setCellValue('L4', 'Wanita')
    ->setCellValue('M4', 'Pria')
    ->setCellValue('N4', 'Wanita')

    ->setCellValue('B5', 'Non Gol')
    ->setCellValue('B6', 'I')
    ->setCellValue('B7', 'II')
    ->setCellValue('B8', 'III')
    ->setCellValue('B9', 'IV')
    ->setCellValue('B10', 'VI')
    ->setCellValue('B11', 'VII')
    ->setCellValue('B12', 'TOTAL');

$resultStatusOut = mysqli_query($koneksi, $queryStatusOut);
$row = mysqli_fetch_assoc($resultStatusOut);
$objPHPExcel->setActiveSheetIndex(4)
    ->setCellValue('C5', $row['Gol0_Kontrak_Habis_Pria'] != 0 ? $row['Gol0_Kontrak_Habis_Pria'] : '')
    ->setCellValue('D5', $row['Gol0_Kontrak_Habis_Perempuan'] != 0 ? $row['Gol0_Kontrak_Habis_Perempuan'] : '')
    ->setCellValue('E5', $row['Gol0_Dikeluarkan_Pria'] != 0 ? $row['Gol0_Dikeluarkan_Pria'] : '')
    ->setCellValue('F5', $row['Gol0_Dikeluarkan_Perempuan'] != 0 ? $row['Gol0_Dikeluarkan_Perempuan'] : '')
    ->setCellValue('G5', $row['Gol0_Kehendak_Sendiri_Pria'] != 0 ? $row['Gol0_Kehendak_Sendiri_Pria'] : '')
    ->setCellValue('H5', $row['Gol0_Kehendak_Sendiri_Perempuan'] != 0 ? $row['Gol0_Kehendak_Sendiri_Perempuan'] : '')
    ->setCellValue('K5', $row['Gol0_Pensiun_Pria'] != 0 ? $row['Gol0_Pensiun_Pria'] : '')
    ->setCellValue('L5', $row['Gol0_Pensiun_Perempuan'] != 0 ? $row['Gol0_Pensiun_Perempuan'] : '')
    ->setCellValue('M5', $row['Gol0_Meninggal_Pria'] != 0 ? $row['Gol0_Meninggal_Pria'] : '')
    ->setCellValue('N5', $row['Gol0_Meninggal_Perempuan'] != 0 ? $row['Gol0_Meninggal_Perempuan'] : '')

    ->setCellValue('C6', $row['Gol1_Kontrak_Habis_Pria'] != 0 ? $row['Gol1_Kontrak_Habis_Pria'] : '')
    ->setCellValue('D6', $row['Gol1_Kontrak_Habis_Perempuan'] != 0 ? $row['Gol1_Kontrak_Habis_Perempuan'] : '')
    ->setCellValue('E6', $row['Gol1_Dikeluarkan_Pria'] != 0 ? $row['Gol1_Dikeluarkan_Pria'] : '')
    ->setCellValue('F6', $row['Gol1_Dikeluarkan_Perempuan'] != 0 ? $row['Gol1_Dikeluarkan_Perempuan'] : '')
    ->setCellValue('G6', $row['Gol1_Kehendak_Sendiri_Pria'] != 0 ? $row['Gol1_Kehendak_Sendiri_Pria'] : '')
    ->setCellValue('H6', $row['Gol1_Kehendak_Sendiri_Perempuan'] != 0 ? $row['Gol1_Kehendak_Sendiri_Perempuan'] : '')
    ->setCellValue('K6', $row['Gol1_Pensiun_Pria'] != 0 ? $row['Gol1_Pensiun_Pria'] : '')
    ->setCellValue('L6', $row['Gol1_Pensiun_Perempuan'] != 0 ? $row['Gol1_Pensiun_Perempuan'] : '')
    ->setCellValue('M6', $row['Gol1_Meninggal_Pria'] != 0 ? $row['Gol1_Meninggal_Pria'] : '')
    ->setCellValue('N6', $row['Gol1_Meninggal_Perempuan'] != 0 ? $row['Gol1_Meninggal_Perempuan'] : '')

    ->setCellValue('C7', $row['Gol2_Kontrak_Habis_Pria'] != 0 ? $row['Gol2_Kontrak_Habis_Pria'] : '')
    ->setCellValue('D7', $row['Gol2_Kontrak_Habis_Perempuan'] != 0 ? $row['Gol2_Kontrak_Habis_Perempuan'] : '')
    ->setCellValue('E7', $row['Gol2_Dikeluarkan_Pria'] != 0 ? $row['Gol2_Dikeluarkan_Pria'] : '')
    ->setCellValue('F7', $row['Gol2_Dikeluarkan_Perempuan'] != 0 ? $row['Gol2_Dikeluarkan_Perempuan'] : '')
    ->setCellValue('G7', $row['Gol2_Kehendak_Sendiri_Pria'] != 0 ? $row['Gol2_Kehendak_Sendiri_Pria'] : '')
    ->setCellValue('H7', $row['Gol2_Kehendak_Sendiri_Perempuan'] != 0 ? $row['Gol2_Kehendak_Sendiri_Perempuan'] : '')
    ->setCellValue('K7', $row['Gol2_Pensiun_Pria'] != 0 ? $row['Gol2_Pensiun_Pria'] : '')
    ->setCellValue('L7', $row['Gol2_Pensiun_Perempuan'] != 0 ? $row['Gol2_Pensiun_Perempuan'] : '')
    ->setCellValue('M7', $row['Gol2_Meninggal_Pria'] != 0 ? $row['Gol2_Meninggal_Pria'] : '')
    ->setCellValue('N7', $row['Gol2_Meninggal_Perempuan'] != 0 ? $row['Gol2_Meninggal_Perempuan'] : '')

    ->setCellValue('C8', $row['Gol3_Kontrak_Habis_Pria'] != 0 ? $row['Gol3_Kontrak_Habis_Pria'] : '')
    ->setCellValue('D8', $row['Gol3_Kontrak_Habis_Perempuan'] != 0 ? $row['Gol3_Kontrak_Habis_Perempuan'] : '')
    ->setCellValue('E8', $row['Gol3_Dikeluarkan_Pria'] != 0 ? $row['Gol3_Dikeluarkan_Pria'] : '')
    ->setCellValue('F8', $row['Gol3_Dikeluarkan_Perempuan'] != 0 ? $row['Gol3_Dikeluarkan_Perempuan'] : '')
    ->setCellValue('G8', $row['Gol3_Kehendak_Sendiri_Pria'] != 0 ? $row['Gol3_Kehendak_Sendiri_Pria'] : '')
    ->setCellValue('H8', $row['Gol3_Kehendak_Sendiri_Perempuan'] != 0 ? $row['Gol3_Kehendak_Sendiri_Perempuan'] : '')
    ->setCellValue('K8', $row['Gol3_Pensiun_Pria'] != 0 ? $row['Gol3_Pensiun_Pria'] : '')
    ->setCellValue('L8', $row['Gol3_Pensiun_Perempuan'] != 0 ? $row['Gol3_Pensiun_Perempuan'] : '')
    ->setCellValue('M8', $row['Gol3_Meninggal_Pria'] != 0 ? $row['Gol3_Meninggal_Pria'] : '')
    ->setCellValue('N8', $row['Gol3_Meninggal_Perempuan'] != 0 ? $row['Gol3_Meninggal_Perempuan'] : '')

    ->setCellValue('C9', $row['Gol4_Kontrak_Habis_Pria'] != 0 ? $row['Gol4_Kontrak_Habis_Pria'] : '')
    ->setCellValue('D9', $row['Gol4_Kontrak_Habis_Perempuan'] != 0 ? $row['Gol4_Kontrak_Habis_Perempuan'] : '')
    ->setCellValue('E9', $row['Gol4_Dikeluarkan_Pria'] != 0 ? $row['Gol4_Dikeluarkan_Pria'] : '')
    ->setCellValue('F9', $row['Gol4_Dikeluarkan_Perempuan'] != 0 ? $row['Gol4_Dikeluarkan_Perempuan'] : '')
    ->setCellValue('G9', $row['Gol4_Kehendak_Sendiri_Pria'] != 0 ? $row['Gol4_Kehendak_Sendiri_Pria'] : '')
    ->setCellValue('H9', $row['Gol4_Kehendak_Sendiri_Perempuan'] != 0 ? $row['Gol4_Kehendak_Sendiri_Perempuan'] : '')
    ->setCellValue('K9', $row['Gol4_Pensiun_Pria'] != 0 ? $row['Gol4_Pensiun_Pria'] : '')
    ->setCellValue('L9', $row['Gol4_Pensiun_Perempuan'] != 0 ? $row['Gol4_Pensiun_Perempuan'] : '')
    ->setCellValue('M9', $row['Gol4_Meninggal_Pria'] != 0 ? $row['Gol4_Meninggal_Pria'] : '')
    ->setCellValue('N9', $row['Gol4_Meninggal_Perempuan'] != 0 ? $row['Gol4_Meninggal_Perempuan'] : '')

    ->setCellValue('C10', $row['Gol5_Kontrak_Habis_Pria'] != 0 ? $row['Gol5_Kontrak_Habis_Pria'] : '')
    ->setCellValue('D10', $row['Gol5_Kontrak_Habis_Perempuan'] != 0 ? $row['Gol5_Kontrak_Habis_Perempuan'] : '')
    ->setCellValue('E10', $row['Gol5_Dikeluarkan_Pria'] != 0 ? $row['Gol5_Dikeluarkan_Pria'] : '')
    ->setCellValue('F10', $row['Gol5_Dikeluarkan_Perempuan'] != 0 ? $row['Gol5_Dikeluarkan_Perempuan'] : '')
    ->setCellValue('G10', $row['Gol5_Kehendak_Sendiri_Pria'] != 0 ? $row['Gol5_Kehendak_Sendiri_Pria'] : '')
    ->setCellValue('H10', $row['Gol5_Kehendak_Sendiri_Perempuan'] != 0 ? $row['Gol5_Kehendak_Sendiri_Perempuan'] : '')
    ->setCellValue('K10', $row['Gol5_Pensiun_Pria'] != 0 ? $row['Gol5_Pensiun_Pria'] : '')
    ->setCellValue('L10', $row['Gol5_Pensiun_Perempuan'] != 0 ? $row['Gol5_Pensiun_Perempuan'] : '')
    ->setCellValue('M10', $row['Gol5_Meninggal_Pria'] != 0 ? $row['Gol5_Meninggal_Pria'] : '')
    ->setCellValue('N10', $row['Gol5_Meninggal_Perempuan'] != 0 ? $row['Gol5_Meninggal_Perempuan'] : '')

    ->setCellValue('C11', $row['Gol6_Kontrak_Habis_Pria'] != 0 ? $row['Gol6_Kontrak_Habis_Pria'] : '')
    ->setCellValue('D11', $row['Gol6_Kontrak_Habis_Perempuan'] != 0 ? $row['Gol6_Kontrak_Habis_Perempuan'] : '')
    ->setCellValue('E11', $row['Gol6_Dikeluarkan_Pria'] != 0 ? $row['Gol6_Dikeluarkan_Pria'] : '')
    ->setCellValue('F11', $row['Gol6_Dikeluarkan_Perempuan'] != 0 ? $row['Gol6_Dikeluarkan_Perempuan'] : '')
    ->setCellValue('G11', $row['Gol6_Kehendak_Sendiri_Pria'] != 0 ? $row['Gol6_Kehendak_Sendiri_Pria'] : '')
    ->setCellValue('H11', $row['Gol6_Kehendak_Sendiri_Perempuan'] != 0 ? $row['Gol6_Kehendak_Sendiri_Perempuan'] : '')
    ->setCellValue('K11', $row['Gol6_Pensiun_Pria'] != 0 ? $row['Gol6_Pensiun_Pria'] : '')
    ->setCellValue('L11', $row['Gol6_Pensiun_Perempuan'] != 0 ? $row['Gol6_Pensiun_Perempuan'] : '')
    ->setCellValue('M11', $row['Gol6_Meninggal_Pria'] != 0 ? $row['Gol6_Meninggal_Pria'] : '')
    ->setCellValue('N11', $row['Gol6_Meninggal_Perempuan'] != 0 ? $row['Gol6_Meninggal_Perempuan'] : '')

    ->setCellValue('C12', $row['Gol7_Kontrak_Habis_Pria'] != 0 ? $row['Gol7_Kontrak_Habis_Pria'] : '')
    ->setCellValue('D12', $row['Gol7_Kontrak_Habis_Perempuan'] != 0 ? $row['Gol7_Kontrak_Habis_Perempuan'] : '')
    ->setCellValue('E12', $row['Gol7_Dikeluarkan_Pria'] != 0 ? $row['Gol7_Dikeluarkan_Pria'] : '')
    ->setCellValue('F12', $row['Gol7_Dikeluarkan_Perempuan'] != 0 ? $row['Gol7_Dikeluarkan_Perempuan'] : '')
    ->setCellValue('G12', $row['Gol7_Kehendak_Sendiri_Pria'] != 0 ? $row['Gol7_Kehendak_Sendiri_Pria'] : '')
    ->setCellValue('H12', $row['Gol7_Kehendak_Sendiri_Perempuan'] != 0 ? $row['Gol7_Kehendak_Sendiri_Perempuan'] : '')
    ->setCellValue('K12', $row['Gol7_Pensiun_Pria'] != 0 ? $row['Gol7_Pensiun_Pria'] : '')
    ->setCellValue('L12', $row['Gol7_Pensiun_Perempuan'] != 0 ? $row['Gol7_Pensiun_Perempuan'] : '')
    ->setCellValue('M12', $row['Gol7_Meninggal_Pria'] != 0 ? $row['Gol7_Meninggal_Pria'] : '')
    ->setCellValue('N12', $row['Gol7_Meninggal_Perempuan'] != 0 ? $row['Gol7_Meninggal_Perempuan'] : '');

setSumFormulas(5, 12, 'O', 'C', 'N', $objPHPExcel);



// Example usage
setSumFormula($objPHPExcel, 'C', 'O', 13, 5, 12);

// Terapkan gaya tepi di dalam sel (tipis)
$objPHPExcel->getActiveSheet()->getStyle('B3:O13')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getStyle('B3:O4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('B3:O4')->getFill()->getStartColor()->setRGB('FCE4D6');

$objPHPExcel->getActiveSheet()->getStyle('B3:O13')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('B13:O13')->getFill()->getStartColor()->setRGB('DEEAF6');

$objPHPExcel->setActiveSheetIndex(0);

// Set nama file dengan format .xls
$nama_file = "Real Time Man Power_$selectedDate.xls"; // Menggunakan format xls untuk Excel 97-2003

// Set header dengan nama file yang telah dibuat
header("Content-Disposition: attachment;filename=\"$nama_file\"");
header('Content-Type: application/vnd.ms-excel'); // Mengatur tipe konten untuk format xls
header('Cache-Control: max-age=0');

// Menyimpan file dalam format Excel 97-2003 (.xls) dan mengirimkannya ke output
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>