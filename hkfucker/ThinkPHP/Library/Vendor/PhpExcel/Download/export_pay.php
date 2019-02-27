<?php
/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2012 PHPExcel
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
 * @copyright  Copyright (c) 2006 - 2012 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.7.7, 2012-05-19
 */

/** Error reporting */


error_reporting(E_ALL);

require dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'../Classes/PHPExcel.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");


// Add some data


$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '批次号')
            ->setCellValue('B1', '付款日期')
            ->setCellValue('C1', '付款人账户')
            ->setCellValue('D1', '账户名称')
            ->setCellValue('E1', '总金额（元）')
            ->setCellValue('F1', '总笔数')
            ->setCellValue('G1', '商户流水号')
            ->setCellValue('H1', '收款人账户')
            ->setCellValue('I1', '收款人姓名')
            ->setCellValue('J1', '付款金额（元）')
            ->setCellValue('K1', '付款理由');

			$all_money   = 0;
			$all_count   = 0;
			$line        = 1;
	foreach($export_list as $key=>$val)		{
		
		$line = $key  +  2;
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$line, '')
					->setCellValue('B'.$line, " ".date('Y-m-d H:i:s',$val['pay_time']))
					->setCellValue('C'.$line, " ".$val['user'])
					->setCellValue('D'.$line, " ".$val['name'])
					->setCellValue('E'.$line, " ".$val['money'])
					->setCellValue('F'.$line, " "."1")
					->setCellValue('G'.$line, " ".$val['back_order_num'])
					->setCellValue('H'.$line, " ".'四川撸游科技有限公司')
					->setCellValue('I'.$line, " ".'四川撸游科技有限公司')
					->setCellValue('J'.$line, " ".$val['pay_money'])
					->setCellValue('K'.$line, " ".'金币充值');
					
			$all_money  +=  		$val['money'];
			$all_count  ++;
		
	}
// Miscellaneous glyphs, UTF-8


$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('B'.($line+2), '总金额（元）')
					->setCellValue('B'.($line+3), '总笔数')
					->setCellValue('C'.($line+2), " ".$all_money)
					->setCellValue('C'.($line+3), " ".$all_count);

// $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
// $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
// $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
// $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
// $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
// $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
// $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
// $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);



// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle($excel_titel);


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$excel_titel.'.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
