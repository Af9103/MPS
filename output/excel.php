<?php
// Load PHPExcel library
require_once __DIR__ . '/../asset/PHPExcel/Classes/PHPExcel.php';
require_once __DIR__ . '/../query/koneksi.php';
require_once __DIR__ . '/../query/query.php';

$tanggal_hari_ini = date("d F Y");



// Eksekusi kueri menggunakan objek koneksi dari file koneksi.php
$resultUmur = mysqli_query($koneksi, $queryUmur);
$resultMK = mysqli_query($koneksi, $queryMK);
$resultJK = mysqli_query($koneksi, $queryJK);
$resultPend = mysqli_query($koneksi, $queryPend);

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
$objPHPExcel->getDefaultStyle()->getFont()->setName('Times New Roman');
$objPHPExcel->getSheet(0)->setTitle('Real Time Man Power'); // Mengatur judul lembar pertama
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(17);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(17);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(17);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(5);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(11);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(11);

// Set properties
$objPHPExcel->getProperties()->setCreator("Your Name")
    ->setLastModifiedBy("Your Name")
    ->setTitle("Sample Spreadsheet")
    ->setSubject("Sample")
    ->setDescription("Sample spreadsheet using PHPExcel")
    ->setKeywords("sample phpexcel")
    ->setCategory("Sample");

//MERGECELL
$objPHPExcel->getActiveSheet()->mergeCells('B3:B4');
$objPHPExcel->getActiveSheet()->mergeCells('C3:D3');
$objPHPExcel->getActiveSheet()->mergeCells('C12:D12');
$objPHPExcel->getActiveSheet()->mergeCells('K1:X1');
$objPHPExcel->getActiveSheet()->mergeCells('K2:M2');
$objPHPExcel->getActiveSheet()->mergeCells('K3:K5');
$objPHPExcel->getActiveSheet()->mergeCells('L3:L5');
$objPHPExcel->getActiveSheet()->mergeCells('M3:W3');
$objPHPExcel->getActiveSheet()->mergeCells('V4:W4');
$objPHPExcel->getActiveSheet()->mergeCells('K34:L34');
$objPHPExcel->getActiveSheet()->mergeCells('M4:M5');
$objPHPExcel->getActiveSheet()->mergeCells('B29:B30');
$objPHPExcel->getActiveSheet()->mergeCells('C29:D29');
$objPHPExcel->getActiveSheet()->mergeCells('C37:D37');
$objPHPExcel->getActiveSheet()->mergeCells('P4:Q4');
$objPHPExcel->getActiveSheet()->mergeCells('R4:S4');
$objPHPExcel->getActiveSheet()->mergeCells('U4:U5');

// Add data and format to the first sheet
foreach ($resultUmur as $row) {

    $jumlah_pria_kurang_dari_18 = $row['<18 Pria'];
    $jumlah_perempuan_kurang_dari_18 = $row['<18 Perempuan'];
    $jumlah_pria_18_25 = $row['18-25 Pria'];
    $jumlah_perempuan_18_25 = $row['18-25 Perempuan'];
    $jumlah_pria_26_35 = $row['26-35 Pria'];
    $jumlah_perempuan_26_35 = $row['26-35 Perempuan'];
    $jumlah_pria_36_45 = $row['36-45 Pria'];
    $jumlah_perempuan_36_45 = $row['36-45 Perempuan'];
    $jumlah_pria_46_55 = $row['46-55 Pria'];
    $jumlah_perempuan_46_55 = $row['46-55 Perempuan'];
    $jumlah_pria_lebih_dari_55 = $row['>55 Pria'];
    $jumlah_perempuan_lebih_dari_55 = $row['>55 Perempuan'];

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B3', 'Umur')
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
}


// Add background color to specific cells
$objPHPExcel->getActiveSheet()->getStyle('B3:D4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('B3:D4')->getFill()->getStartColor()->setRGB('BDD7EE');

$objPHPExcel->getActiveSheet()->getStyle('C12:D12')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('C12:D12')->getFill()->getStartColor()->setRGB('BDD7EE');



$objPHPExcel->getActiveSheet()
    ->getStyle('B3')
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

// Terapkan gaya tepi di luar sel (tebal)
$objPHPExcel->getActiveSheet()->getStyle('B3:D12')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('B11:D11')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('B12:D12')->applyFromArray($styleArrayOutside);


//MASA KERJA
$objPHPExcel->getActiveSheet()->mergeCells('B16:B17');
$objPHPExcel->getActiveSheet()->mergeCells('C16:D16');
$objPHPExcel->getActiveSheet()->mergeCells('C26:D26');

foreach ($resultMK as $row) {
    $jumlah_pria_kurang_dari_5 = $row['0-5 Pria'];
    $jumlah_perempuan_kurang_dari_5 = $row['0-5 Perempuan'];
    $jumlah_pria_6_10 = $row['6-10 Pria'];
    $jumlah_perempuan_6_10 = $row['6-10 Perempuan'];
    $jumlah_pria_11_15 = $row['11-15 Pria'];
    $jumlah_perempuan_11_15 = $row['11-15 Perempuan'];
    $jumlah_pria_16_20 = $row['16-20 Pria'];
    $jumlah_perempuan_16_20 = $row['16-20 Perempuan'];
    $jumlah_pria_21_25 = $row['21-25 Pria'];
    $jumlah_perempuan_21_25 = $row['21-25 Perempuan'];
    $jumlah_pria_26_30 = $row['26-30 Pria'];
    $jumlah_perempuan_26_30 = $row['26-30 Perempuan'];
    $jumlah_pria_lebih_dari_30 = $row['>30 Pria'];
    $jumlah_perempuan_lebih_dari_30 = $row['>30 Perempuan'];
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
}

// Add background color to specific cells
$objPHPExcel->getActiveSheet()->getStyle('B16:D17')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('B16:D17')->getFill()->getStartColor()->setRGB('BDD7EE');

$objPHPExcel->getActiveSheet()->getStyle('C26:D26')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('C26:D26')->getFill()->getStartColor()->setRGB('BDD7EE');

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
        ->setCellValue('F4', 'Gender')
        ->setCellValue('G4', 'Jumlah')
        ->setCellValue('F5', 'Pria')
        ->setCellValue('G5', $pria)
        ->setCellValue('F6', 'Wanita')
        ->setCellValue('G6', $perempuan)
        ->setCellValue('F7', 'Total')
        ->setCellValue('G7', $perempuan + $pria);
}

$objPHPExcel->getActiveSheet()->getStyle('F4:G4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('F4:G4')->getFill()->getStartColor()->setRGB('FFFF00');

$objPHPExcel->getActiveSheet()->getStyle('F7:G7')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('F7:G7')->getFill()->getStartColor()->setRGB('BDD7EE');

$objPHPExcel->getActiveSheet()
    ->getStyle('F4:G7')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('F4:G4')
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
$objPHPExcel->getActiveSheet()->getStyle('F4:G7')->applyFromArray($styleArray);

// Terapkan gaya tepi di luar sel (tebal)
$objPHPExcel->getActiveSheet()->getStyle('F4:G7')->applyFromArray($styleArrayOutside);

//PENDIDIKAN
foreach ($resultPend as $row) {
    $jumlah_pria_SD = $row['Pria SD'];
    $jumlah_perempuan_SD = $row['Perempuan SD'];
    $jumlah_pria_SLTP = $row['Pria SLTP'];
    $jumlah_perempuan_SLTP = $row['Perempuan SLTP'];
    $jumlah_pria_SMA = $row['Pria SMA'];
    $jumlah_perempuan_SMA = $row['Perempuan SMA'];
    $jumlah_pria_Diploma = $row['Pria Diploma'];
    $jumlah_perempuan_Diploma = $row['Perempuan Diploma'];
    $jumlah_pria_S1 = $row['Pria S1'];
    $jumlah_perempuan_S1 = $row['Perempuan S1'];
    $jumlah_pria_S2 = $row['Pria S2'];
    $jumlah_perempuan_S2 = $row['Perempuan S2'];
    $jumlah_pria_S3 = $row['Pria S3'];
    $jumlah_perempuan_S3 = $row['Perempuan S3'];

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B29', 'Pendidikan')
        ->setCellValue('C29', 'QTY')
        ->setCellValue('C30', 'Pria')
        ->setCellValue('D30', 'Wanita')
        ->setCellValue('B31', 'SD,SLTP')
        ->setCellValue('B32', 'SLTA')
        ->setCellValue('B33', 'Diploma')
        ->setCellValue('B34', 'S-1')
        ->setCellValue('B35', 'S2,S3')
        ->setCellValue('B36', 'Sub Total')
        ->setCellValue('B37', 'Total')

        ->setCellValue('C31', $jumlah_pria_SD + $jumlah_pria_SLTP)
        ->setCellValue('D31', $jumlah_perempuan_SD + $jumlah_perempuan_SLTP)

        ->setCellValue('C32', $jumlah_pria_SMA)
        ->setCellValue('D32', $jumlah_perempuan_SMA)

        ->setCellValue('C33', $jumlah_pria_Diploma)
        ->setCellValue('D33', $jumlah_perempuan_Diploma)

        ->setCellValue('C34', $jumlah_pria_S1)
        ->setCellValue('D34', $jumlah_perempuan_S1)

        ->setCellValue('C35', $jumlah_pria_S2 + $jumlah_pria_S3)
        ->setCellValue('D35', $jumlah_perempuan_S2 + $jumlah_perempuan_S3)

        ->setCellValue('C36', '=SUM(C31:C35)')
        ->setCellValue('D36', '=SUM(D31:D35)')

        ->setCellValue('C37', '=SUM(C36:D36)');

}
// Add background color to specific cells
$objPHPExcel->getActiveSheet()->getStyle('B29:D30')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('B29:D30')->getFill()->getStartColor()->setRGB('BDD7EE');

$objPHPExcel->getActiveSheet()->getStyle('C37:D37')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('C37:D37')->getFill()->getStartColor()->setRGB('BDD7EE');

$objPHPExcel->getActiveSheet()
    ->getStyle('B29')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('B29:D37')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('B29:D30')
    ->getFont()
    ->setBold(true);

$objPHPExcel->getActiveSheet()
    ->getStyle('B36')
    ->getFont()
    ->setBold(true);

$objPHPExcel->getActiveSheet()
    ->getStyle('B36:C37')
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
$objPHPExcel->getActiveSheet()->getStyle('B29:D37')->applyFromArray($styleArray);

// Terapkan gaya tepi di luar sel (tebal)
$objPHPExcel->getActiveSheet()->getStyle('B29:D37')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('B36:D36')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('B37:D37')->applyFromArray($styleArrayOutside);

//HEADER
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('K1', 'REAL TIME MAN POWER TODAY')
    ->setCellValue('K2', $tanggal_hari_ini);

$objPHPExcel->getActiveSheet()->getStyle('K2:M2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('K2:M2')->getFill()->getStartColor()->setRGB('FFFF00');

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
$objPHPExcel->getActiveSheet()->getStyle('K3:W34')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('X6:X34')->applyFromArray($styleArray);

// Terapkan gaya tepi di luar sel (tebal)
$objPHPExcel->getActiveSheet()->getStyle('K1:X1')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('K3:X34')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('K3:X5')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('K34:X34')->applyFromArray($styleArrayOutside);

//DEPT
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('K3', 'NO')
    ->setCellValue('L3', 'DEPARTEMEN')
    ->setCellValue('M3', 'GOLONGAN')
    ->setCellValue('X3', 'GRAND')
    ->setCellValue('M4', 'KONTRAK')
    ->setCellValue('N4', 'I')
    ->setCellValue('O4', 'II')
    ->setCellValue('P4', 'III')
    ->setCellValue('R4', 'IV')
    ->setCellValue('T4', 'V')
    ->setCellValue('U4', 'VI-VII')
    ->setCellValue('V4', 'TOTAL')
    ->setCellValue('X4', 'TOTAL')
    ->setCellValue('N5', 'TETAP')
    ->setCellValue('O5', 'TETAP')
    ->setCellValue('P5', 'TETAP')
    ->setCellValue('Q5', 'TRAINEE')
    ->setCellValue('R5', 'TETAP')
    ->setCellValue('S5', 'TRAINEE')
    ->setCellValue('T5', 'TETAP')
    ->setCellValue('V5', 'TETAP')
    ->setCellValue('W5', 'KONTRAK')
    ->setCellValue('X34', 'TOTAL')
    ->setCellValue('K34', 'TOTAL')
    ->setCellValue('M34', '=SUM(M6:M33)')
    ->setCellValue('N34', '=SUM(N6:N33)')
    ->setCellValue('O34', '=SUM(O6:O33)')
    ->setCellValue('P34', '=SUM(P6:P33)')
    ->setCellValue('Q34', '=SUM(Q6:Q33)')
    ->setCellValue('R34', '=SUM(R6:R33)')
    ->setCellValue('S34', '=SUM(S6:S33)')
    ->setCellValue('T34', '=SUM(T6:T33)')
    ->setCellValue('U34', '=SUM(U6:U33)')
    ->setCellValue('V34', '=SUM(V6:V33)')
    ->setCellValue('W34', '=SUM(W6:W33)')
    ->setCellValue('X34', '=SUM(X6:X33)');

$no = 6; // Start row number
while ($user_data = mysqli_fetch_array($resultRMP)) {
    $cwoc = isset($user_data['cwoc']) ? $user_data['cwoc'] : null;
    $kontrak = isset($DashboardByCwoc[$cwoc]['Kontrak']) ? $DashboardByCwoc[$cwoc]['Kontrak'] : 0;
    $tetap1 = isset($DashboardByCwoc[$cwoc]['Tetap1']) ? $DashboardByCwoc[$cwoc]['Tetap1'] : 0;
    $tetap2 = isset($DashboardByCwoc[$cwoc]['Tetap2']) ? $DashboardByCwoc[$cwoc]['Tetap2'] : 0;
    $tetap3 = isset($DashboardByCwoc[$cwoc]['Tetap3']) ? $DashboardByCwoc[$cwoc]['Tetap3'] : 0;
    $trainee3 = isset($DashboardByCwoc[$cwoc]['Trainee3']) ? $DashboardByCwoc[$cwoc]['Trainee3'] : 0;
    $tetap4 = isset($DashboardByCwoc[$cwoc]['Tetap4']) ? $DashboardByCwoc[$cwoc]['Tetap4'] : 0;
    $trainee4 = isset($DashboardByCwoc[$cwoc]['Trainee4']) ? $DashboardByCwoc[$cwoc]['Trainee4'] : 0;
    $tetap5 = isset($DashboardByCwoc[$cwoc]['Tetap5']) ? $DashboardByCwoc[$cwoc]['Tetap5'] : 0;
    $tetap6_7 = isset($DashboardByCwoc[$cwoc]['Tetap6_7']) ? $DashboardByCwoc[$cwoc]['Tetap6_7'] : 0;
    $tetap = isset($DashboardByCwoc[$cwoc]['Tetap']) ? $DashboardByCwoc[$cwoc]['Tetap'] : 0;
    $total = $kontrak + $trainee3 + $trainee4 + $tetap;
    // Populate the rest of the variables similarly

    // Set cell values
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('L' . $no, $user_data['cwoc'])
        ->setCellValue('M' . $no, $kontrak)
        ->setCellValue('N' . $no, $tetap1)
        ->setCellValue('O' . $no, $tetap2)
        ->setCellValue('P' . $no, $tetap3)
        ->setCellValue('Q' . $no, $trainee3)
        ->setCellValue('R' . $no, $tetap4)
        ->setCellValue('S' . $no, $trainee4)
        ->setCellValue('T' . $no, $tetap5)
        ->setCellValue('U' . $no, $tetap6_7)
        ->setCellValue('V' . $no, $tetap)
        ->setCellValue('W' . $no, $kontrak)
        ->setCellValue('X' . $no, $total);

    $no++; // Increment row number
}


$objPHPExcel->getActiveSheet()
    ->getStyle('K3:L5')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('M4:M5')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('K3:X34')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()
    ->getStyle('U4')
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

// Array dengan nomor 1-28
$numbers = range(1, 28);

// Mendapatkan kolom K dari baris 6 hingga 33
$kolom_K = range('K', 'K');

// Loop untuk menetapkan nilai ke setiap sel
for ($i = 0; $i < count($numbers); $i++) {
    $cell = $kolom_K[0] . ($i + 6); // Memulai dari baris 7 dan mengubah nomor baris
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cell, $numbers[$i]); // Set nilai ke sel
}



$objPHPExcel->createSheet()->setTitle('LAH');
// Set active sheet index to the first sheet
$objPHPExcel->setActiveSheetIndex(0);


$nama_file = "Real Time Man Power_$tanggal_hari_ini.xlsx"; // Menggunakan format xlsx untuk Office 2019

// Set header dengan nama file yang telah dibuat
header("Content-Disposition: attachment;filename=\"$nama_file\"");
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); // Mengatur tipe konten untuk format xlsx
header('Cache-Control: max-age=0');

// Buat writer untuk format Office 2019 (xlsx)
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

// Simpan file ke output
$objWriter->save('php://output');