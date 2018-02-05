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
$objPHPExcel->getProperties()->setCreator("H-MEN")
        ->setLastModifiedBy("H-MEN")
        ->setTitle("Office 2007 XLSX Test Document")
        ->setSubject("Office 2007 XLSX Test Document")
        ->setDescription("H-MEN Sales Reports")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("Sales Reports");


// Add some data

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->mergeCells('A6:H6');
$objPHPExcel->getActiveSheet()->getStyle('A6:H6')->applyFromArray(array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),)));
$objPHPExcel->getActiveSheet()->setCellValue('A6', 'SURVEY REPORT');
$objPHPExcel->getActiveSheet()->getStyle('A6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A6')->applyFromArray(array('font' => array('bold' => true, 'size' => 16)));
$objPHPExcel->getActiveSheet()->getStyle('A6:H6')->applyFromArray(array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),)));
$objPHPExcel->getActiveSheet()->getStyle('A7:H7')->applyFromArray(array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),)));

$objPHPExcel->getActiveSheet()->setCellValue('A7', 'NO');
$objPHPExcel->getActiveSheet()->getStyle('A7')->applyFromArray(array('font' => array('bold' => true)));
$objPHPExcel->getActiveSheet()->setCellValue('B7', 'Membership Id');
$objPHPExcel->getActiveSheet()->getStyle('B7')->applyFromArray(array('font' => array('bold' => true)));
$objPHPExcel->getActiveSheet()->setCellValue('C7', 'Name');
$objPHPExcel->getActiveSheet()->getStyle('C7')->applyFromArray(array('font' => array('bold' => true)));
$objPHPExcel->getActiveSheet()->setCellValue('D7', 'Email id');
$objPHPExcel->getActiveSheet()->getStyle('D7')->applyFromArray(array('font' => array('bold' => true)));
$objPHPExcel->getActiveSheet()->setCellValue('E7', 'Phone no');
$objPHPExcel->getActiveSheet()->getStyle('E7')->applyFromArray(array('font' => array('bold' => true)));
$objPHPExcel->getActiveSheet()->setCellValue('F7', 'Plan');
$objPHPExcel->getActiveSheet()->getStyle('F7')->applyFromArray(array('font' => array('bold' => true)));
$objPHPExcel->getActiveSheet()->setCellValue('G7', 'Sales by');
$objPHPExcel->getActiveSheet()->getStyle('G7')->applyFromArray(array('font' => array('bold' => true)));
$objPHPExcel->getActiveSheet()->setCellValue('H7', 'Sales Time');
$objPHPExcel->getActiveSheet()->getStyle('H7')->applyFromArray(array('font' => array('bold' => true)));
//
//
$objPHPExcel->getActiveSheet()->getStyle('A8:H7')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A8:H7')->applyFromArray(array('font' => array('bold' => true, 'size' => 11)));
$objPHPExcel->getActiveSheet()->getStyle('A8:H7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A8:H7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
//
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);

$objPHPExcel->getActiveSheet()->getStyle('A8:H8')->applyFromArray(array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),)));
$j = 1;
$row = 8;
foreach ($members as $key => $data) {
//    pr($data);
//    exit;
    $i = 0;
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, $key + 1);
    $objPHPExcel->getActiveSheet()->getStyle('A' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $row, $data['membership_id']);
    $objPHPExcel->getActiveSheet()->getStyle('B' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $row, $data['name']);
    $objPHPExcel->getActiveSheet()->getStyle('C' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $row, $data['email']);
    $objPHPExcel->getActiveSheet()->getStyle('D' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $row, $data['phone_no']);
    $objPHPExcel->getActiveSheet()->getStyle('E' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $row, ucfirst(strtolower($data['plan_name'])));
    $objPHPExcel->getActiveSheet()->getStyle('F' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $row, $data['sales_by']);
    $objPHPExcel->getActiveSheet()->getStyle('G' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $row, $data['created']);
    $objPHPExcel->getActiveSheet()->getStyle('H' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':H' . $row)->applyFromArray(array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),)));
    $j++;
    $row++;
}


$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
$objDrawing->setName('H-MEN');
$objDrawing->setDescription('H-MEN Services');

$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

//set Address
$objPHPExcel->getActiveSheet()->mergeCells('A1:H1');
$objPHPExcel->getActiveSheet()->setCellValue('A1', COMPANY_NAME_EXCEL);
$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray(array('font' => array('bold' => true, 'size' => 12, 'color' => array('rgb' => 'FFFFFF'))));
$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray(
        array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '000000')
            )
        )
);
$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray(array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),)));
$objPHPExcel->getActiveSheet()->mergeCells('A2:H2');
$objPHPExcel->getActiveSheet()->setCellValue('A2', ADDRESS_1_EXCEL);
$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray(array('font' => array('size' => 10)));
$objPHPExcel->getActiveSheet()->getStyle('A1:H2')->applyFromArray(array('borders' => array('right' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),)));
$objPHPExcel->getActiveSheet()->getStyle('A1:H2')->applyFromArray(array('borders' => array('left' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),)));
$objPHPExcel->getActiveSheet()->mergeCells('A3:H3');
$objPHPExcel->getActiveSheet()->setCellValue('A3', ADDRESS_2_EXCEL);
$objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray(array('font' => array('size' => 9)));
$objPHPExcel->getActiveSheet()->getStyle('A3:H3')->applyFromArray(array('borders' => array('right' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),)));
$objPHPExcel->getActiveSheet()->getStyle('A3:H3')->applyFromArray(array('borders' => array('left' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),)));
$objPHPExcel->getActiveSheet()->mergeCells('A4:H4');
$objPHPExcel->getActiveSheet()->setCellValue('A4', 'MOBILE: ' . MOBILE_PHONE_EXCEL);
$objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('A4')->applyFromArray(array('font' => array('size' => 9)));
$objPHPExcel->getActiveSheet()->getStyle('A4:H4')->applyFromArray(array('borders' => array('right' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),)));
$objPHPExcel->getActiveSheet()->getStyle('A4:H4')->applyFromArray(array('borders' => array('left' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),)));
$objPHPExcel->getActiveSheet()->getStyle('A4:H4')->applyFromArray(array('borders' => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),)));

//pr($row);exit;
//$objPHPExcel->getActiveSheet()->getStyle('H'.$row.':I'.$row)->applyFromArray(array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),)));
// Miscellaneous glyphs, UTF-8
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Sales Report');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.sheet');
header('Content-Disposition: attachment;filename="SalesPerformanceReport_' . date('d_m_Y') . '.xls"');
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
