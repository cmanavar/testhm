<?php

/**
 * PHPExcel
 *
 * Copyright (c) 2006 - 2015 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    ##VERSION##, ##DATE##
 */
/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('asia/kolkata');

if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once ROOT . '/vendor/PHPExcel/Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Scanmax")
        ->setLastModifiedBy("Scanmax")
        ->setTitle("Office 2007 XLSX Test Document")
        ->setSubject("Office 2007 XLSX Test Document")
        ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("Test result file");


// Add some data

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->mergeCells('A8:D8');
$objPHPExcel->getActiveSheet()->setCellValue('A8', 'USER REPORT');
$objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A8')->applyFromArray(array('font' => array('bold' => true, 'size' => 25)));
$objPHPExcel->getActiveSheet()->getStyle('A8:D8')->applyFromArray(array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),)));
$objPHPExcel->getActiveSheet()->getStyle('A9:D9')->applyFromArray(array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),)));

$objPHPExcel->getActiveSheet()->setCellValue('A9', 'NO');
$objPHPExcel->getActiveSheet()->setCellValue('B9', 'NAME');
$objPHPExcel->getActiveSheet()->setCellValue('C9', 'EMAIL ID');
$objPHPExcel->getActiveSheet()->setCellValue('D9', 'PHONE');



$objPHPExcel->getActiveSheet()->getStyle('A9:D9')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A9:D9')->applyFromArray(array('font' => array('bold' => true, 'size' => 11)));
$objPHPExcel->getActiveSheet()->getStyle('A9:D9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A9:D9')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(22);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(38);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);


$objPHPExcel->getActiveSheet()->getStyle('A10:D10')->applyFromArray(array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),)));
$j = 1;
$row = 10;
foreach ($users as $key => $data) { //pr($data);exit;   
    $i = 0;
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, $key + 1);
    $objPHPExcel->getActiveSheet()->getStyle('A' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $row, (isset($data['firstname']) && isset($data['lastname'])) ? $data['firstname'] . " " . $data['lastname'] : '');
     $objPHPExcel->getActiveSheet()->getStyle('B' . $row)->getAlignment()->setWrapText(true);
  //  $objPHPExcel->getActiveSheet()->getStyle('B' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
     $objPHPExcel->getActiveSheet()->setCellValue('C' . $row, $data['email']);
    // $objPHPExcel->getActiveSheet()->getStyle('C' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
     $objPHPExcel->getActiveSheet()->getStyle('C' . $row)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $row, $data['phone']);



    $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':D' . $row)->applyFromArray(array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),)));
    $j++;
    $row++;
}
//pr($row);exit;
//$objPHPExcel->getActiveSheet()->getStyle('H'.$row.':I'.$row)->applyFromArray(array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),)));

// set image object
$gdImage = ($_SERVER['HTTP_HOST'] == 'ashirvad.carescoop.com' || $_SERVER['HTTP_HOST'] == 'localhost') ? imagecreatefromjpeg(WWW_ROOT . "img/ashirvad_excel.jpg") : imagecreatefromjpeg(WWW_ROOT . "img/sattva-excel.jpg");
$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
$objDrawing->setName('SATTVA DENTAL CARE');
$objDrawing->setDescription('SATTVA DENTAL CARE');
$objDrawing->setImageResource($gdImage);
$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
$objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
$objDrawing->setWidth(194);
//$objDrawing->setHeight(105);
$objDrawing->setOffsetX(13);
$objDrawing->setOffsetY(1);
$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->applyFromArray(array('borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),)));
$objPHPExcel->getActiveSheet()->getStyle('A1:A6')->applyFromArray(array('borders' => array('left' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),)));
$objPHPExcel->getActiveSheet()->getStyle('A7:B7')->applyFromArray(array('borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),)));

//$objDrawing->setCoordinates('A1');

//$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray(array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),)));

$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

//set Address
$objPHPExcel->getActiveSheet()->mergeCells('C1:D1');
$objPHPExcel->getActiveSheet()->setCellValue('C1', $excel_line1);
$objPHPExcel->getActiveSheet()->getStyle('C1:D1')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('C1')->applyFromArray(array('font' => array('bold' => true, 'size' => 12, 'color' => array('rgb' => 'FFFFFF'))));
$objPHPExcel->getActiveSheet()->getStyle('C1')->applyFromArray(
        array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '000000')
            )
        )
);
$objPHPExcel->getActiveSheet()->getStyle('C1:D1')->applyFromArray(array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),)));
$objPHPExcel->getActiveSheet()->mergeCells('C2:D2');
$objPHPExcel->getActiveSheet()->setCellValue('C2', $excel_line2);
$objPHPExcel->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('C2')->applyFromArray(array('font' => array('size' => 10)));
//$objPHPExcel->getActiveSheet()->getStyle('D2:I2')->applyFromArray(array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),)));
$objPHPExcel->getActiveSheet()->getStyle('C2:D2')->applyFromArray(array('borders' => array('right' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),)));
$objPHPExcel->getActiveSheet()->getStyle('C2:D2')->applyFromArray(array('borders' => array('left' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),)));

$objPHPExcel->getActiveSheet()->mergeCells('C3:D3');
$objPHPExcel->getActiveSheet()->setCellValue('C3', $excel_line3);
$objPHPExcel->getActiveSheet()->getStyle('C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('C3')->applyFromArray(array('font' => array('size' => 9)));
//$objPHPExcel->getActiveSheet()->getStyle('D3:I3')->applyFromArray(array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),)));
$objPHPExcel->getActiveSheet()->getStyle('C3:D3')->applyFromArray(array('borders' => array('right' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),)));
$objPHPExcel->getActiveSheet()->getStyle('C3:D3')->applyFromArray(array('borders' => array('left' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),)));

$objPHPExcel->getActiveSheet()->mergeCells('C4:D4');
$objPHPExcel->getActiveSheet()->setCellValue('C4', $excel_line4);
$objPHPExcel->getActiveSheet()->getStyle('C4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('C4')->applyFromArray(array('font' => array('size' => 9)));
//$objPHPExcel->getActiveSheet()->getStyle('D4:I4')->applyFromArray(array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),)));
$objPHPExcel->getActiveSheet()->getStyle('C4:D4')->applyFromArray(array('borders' => array('right' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),)));
$objPHPExcel->getActiveSheet()->getStyle('C4:D4')->applyFromArray(array('borders' => array('left' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),)));

$objPHPExcel->getActiveSheet()->mergeCells('C5:D5');
$objPHPExcel->getActiveSheet()->setCellValue('C5', $excel_line5);
$objPHPExcel->getActiveSheet()->getStyle('C5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('C5')->applyFromArray(array('font' => array('size' => 9)));
$objPHPExcel->getActiveSheet()->getStyle('C5:D5')->applyFromArray(array('borders' => array('right' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),)));
$objPHPExcel->getActiveSheet()->getStyle('C5:D5')->applyFromArray(array('borders' => array('left' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),)));

//$objPHPExcel->getActiveSheet()->getStyle('D5:I5')->applyFromArray(array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),)));
$objPHPExcel->getActiveSheet()->mergeCells('C6:D6');
$objPHPExcel->getActiveSheet()->setCellValue('C6', $excel_line6);
$objPHPExcel->getActiveSheet()->getStyle('C6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('C6')->applyFromArray(array('font' => array('size' => 9)));
$objPHPExcel->getActiveSheet()->getStyle('C6:D6')->applyFromArray(array('borders' => array('right' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),)));
$objPHPExcel->getActiveSheet()->getStyle('C6:D6')->applyFromArray(array('borders' => array('left' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),)));
$objPHPExcel->getActiveSheet()->getStyle('C6:D6')->applyFromArray(array('borders' => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),)));

 
// // Miscellaneous glyphs, UTF-8
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('USERS');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.sheet');
header('Content-Disposition: attachment;filename="Users_' . date('d_m_Y') . '.xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

$objWriter->save('php://output');
exit;
