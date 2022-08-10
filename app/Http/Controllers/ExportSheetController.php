<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use PHPExcel;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;
use PHPExcel_Worksheet_PageSetup;
use PHPExcel_Writer_Excel2007;

class ExportSheetController extends Controller {
   public function export($table_uuid) {
      try {
         date_default_timezone_set('Europe/Moscow');

         $date = date('d_m_Y');

         $table = json_decode(DB::table('tables')->where('table_uuid', $table_uuid)->where('status', 0)->get(), true)[0];
         $report_values = json_decode(DB::table('report_values')->where('table_uuid', $table_uuid)->value('json_val'), true);

         $json_val = json_decode($table['json_val'], true);
         $table_name = $table['table_name'];
         $cells_arr = json_decode($table['json_markup'], true);
         $funcs = json_decode($table['func_coords'], true);
         $highest_row = $table['highest_row'];

         $xls = new PHPExcel();
         $xls->setActiveSheetIndex(0);
         $sheet = $xls->getActiveSheet();
         $sheet->setTitle('Название листа');
         $sheet->getPageSetup()->SetPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
         $sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
         $sheet->getPageMargins()->setTop(1);
         $sheet->getPageMargins()->setRight(0.75);
         $sheet->getPageMargins()->setLeft(0.75);
         $sheet->getPageMargins()->setBottom(1);
         $sheet->getHeaderFooter()->setOddHeader("Название листа");
         $sheet->getHeaderFooter()->setOddFooter('&L&B Название листа &R Страница &P из &N');

         foreach ($cells_arr as $key => $cell) {
            if (str_contains($key, ':')) {
               $sheet->mergeCells($key);
               $sheet->setCellValue(preg_replace('#:\w+#', '', $key), $cell);
            } else {
               $sheet->setCellValue($key, $cell);
            }
            $sheet->getStyle($key)->applyFromArray(array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000')))));
            $sheet->getStyle($key)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($key)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
         }
         $arrKeys = [];
         for ($i = "A", $k = 1; $i <= 'Z'; $i++) {
            $arrKeys[$k] = $i;
            $k++;
         }

         $arrKeys = array_slice($arrKeys, 0, count($report_values));

         $json_vals = [];

         foreach (array_combine($arrKeys, $report_values) as $k => $json_val) {
            $json_vals[$k . $highest_row] = $json_val;
         }
            foreach ($json_vals as $key => $val) {
                if (isset($funcs[$key])) {
                    $sheet->getStyle($key)->getNumberFormat()->setFormatCode('0%;-0%');
                    $sheet->setCellValue($key, $funcs[$key]);
                } else {
                    $sheet->setCellValue($key, $val);
                }
                $sheet->getStyle($key)->applyFromArray(array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000')))));
                $sheet->getStyle($key)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle($key)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            }

         header("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
         header("Last-Modified: " . date("D,d M YH:i:s"));
         header("Cache-Control: no-cache, must-revalidate");
         header("Pragma: no-cache");
         header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
         header("Content-Disposition: attachment; filename=$table_name-$date.xlsx");

         $objWriter = new PHPExcel_Writer_Excel2007($xls);
         $objWriter->save('php://output');
         exit();
         
      } catch (QueryException $e) {
         echo 'Ошибка: ' . $e->getMessage();
      }
   }
}
