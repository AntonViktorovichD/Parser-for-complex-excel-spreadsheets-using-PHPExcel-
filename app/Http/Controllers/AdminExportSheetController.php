<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use PHPExcel;
use PHPExcel_Cell_DataType;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;
use PHPExcel_Worksheet_PageSetup;
use PHPExcel_Writer_Excel2007;
use function json_decode;

class AdminExportSheetController extends Controller {
   public function admin_export($table_uuid) {
      try {

         date_default_timezone_set('Europe/Moscow');

         $date = date('d_m_Y');

         $json_values = json_decode(DB::table('report_values')->where('table_uuid', '=', $table_uuid)->value('json_val'), true);
         $table = DB::table('tables')->where('table_uuid', $table_uuid)->get();
         $table_name = $table[0]->table_name;
         $highest_row = $table[0]->highest_row;
         $highest_col = $table[0]->highest_column_index;
         $cells_arr = $table[0]->json_markup;
         $funcs = $table[0]->func_coords;

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


         foreach (json_decode($cells_arr, true) as $key => $cell) {
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

         $arrKeys = array_slice($arrKeys, 0, $highest_col);

         $json_vals = [];
         $json_val = [];

         for ($j = 1; $j <= count($json_values); $j++) {
            if(isset($json_values[$j])) {
               $json_val[$j] = $json_values[$j];
            } else {
               $json_val[$j] = NULL;
            }
            }
         var_dump($json_val);
        $vals = array_combine($arrKeys, $json_val);
         for ($j = 1; $j <= count($json_values); $j++) {
            foreach ($vals as $k => $json_val) {
               $json_vals[$k . ($highest_row + $j)] = $json_val;
            }

            foreach ($json_vals as $key => $val) {
               if (isset($funcs[$key])) {
                  $sheet->getStyle($key)->getNumberFormat()->setFormatCode('0%;-0%');
                  $sheet->setCellValue($key, $funcs[$key], PHPExcel_Cell_DataType::TYPE_NUMERIC);
               } else {
                  $sheet->setCellValue($key, $val);
               }
               $sheet->getStyle($key)->applyFromArray(array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000')))));
               $sheet->getStyle($key)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
               $sheet->getStyle($key)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            }
         }

         header("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
         header("Last-Modified: " . date("D,d M YH:i:s"));
         header("Cache-Control: no-cache, must-revalidate");
         header("Pragma: no-cache");
         header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
         header("Content-Disposition: attachment; filename=$table_name-$date.xlsx");

         $objWriter = new PHPExcel_Writer_Excel2007($xls);
         $objWriter->save('php://output');
      } catch (QueryException $e) {
         echo 'Ошибка: ' . $e->getMessage();
      }
   }
}
