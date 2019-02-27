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
			$all_money   = 0;
			$all_count   = 0;
			$line        = 3;
			
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '批次号')
            ->setCellValue('B1', '付款日期')
            ->setCellValue('C1', '付款人email')
            ->setCellValue('D1', '账户名称')
            ->setCellValue('E1', '总金额（元）')
			->setCellValue('A2', '')
			->setCellValue('B2', " ".date('Ymd'))
			->setCellValue('C2', " "."lyoogame@163.com")
			->setCellValue('D2', " "."四川撸游科技有限公司")
			->setCellValue('A3', '商户流水号')
            ->setCellValue('B3', '收款人email')
            ->setCellValue('C3', '收款人姓名')
            ->setCellValue('D3', '付款金额（元）')
            ->setCellValue('E3', '付款理由');

	foreach($export_list as $key=>$val)		{
		
		$line ++ ;
			
		if($val['money']<=499){
			$hook = ($val['money']-($val['money']*0.029));
		}else if($val['money']<=999 && $val['money']>=500){
			$hook = ($val['money']-($val['money']*0.025));
		}else{
			$hook = ($val['money']-($val['money']*0.02));
		}
		
		$ali_pay_num  =  str_replace(" 支付宝账户 ------ ","",$val['pay_bank']);
		
		$val['order_num'] = preg_replace('/(^\d)/i','',$val['order_num']);
				
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.($line), " ".$val['order_num'])
					->setCellValue('B'.($line), trim(preg_replace('/([\x80-\xff]+)/','',$ali_pay_num)) )
					->setCellValue('C'.($line), " ".$val['name'])
					->setCellValue('D'.($line), "".$hook)
					->setCellValue('E'.($line), "四川撸游")			;
					
			$all_money  +=  		$hook;
			$all_count  ++;
		
	}
// Miscellaneous glyphs, UTF-8


$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('F1', '总笔数')
					->setCellValue('F2', " ".$all_count)
					->setCellValue('E1', '总金额（元）')
					->setCellValue('E2', " ".$all_money);

$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);

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
