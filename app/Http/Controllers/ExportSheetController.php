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
use Illuminate\Support\Facades\Auth;

class ExportSheetController extends Controller {
   public function export($table_uuid) {
      try {
         date_default_timezone_set('Europe/Moscow');
         $departs = [];
         $date = date('d_m_Y');
         $table = json_decode(DB::table('tables')->where('table_uuid', $table_uuid)->where('status', 0)->get(), true)[0];

         $user_role = Auth::user()->roles->first()->id;
         if ($user_role == 2 || $user_role == 3) {
            $user_dep = Auth::user()->department;

            $report_values = json_decode(DB::table('report_values')->where('table_uuid', $table_uuid)->where('user_dep', $user_dep)->value('json_val'), true);
         } else {
            $report_values = json_decode(DB::table('report_values')->where('table_uuid', $table_uuid)->pluck('json_val'), true);
            $orgs = json_decode(DB::table('report_values')->where('table_uuid', $table_uuid)->pluck('user_dep'), true);
            foreach ($orgs as $key => $org) {
               $departs[$key] = DB::table('org_helper')->where('id', $org)->value('title');
            }
         }
         $json_val = json_decode($table['json_val'], true);
         $table_name = $table['table_name'];
         $cells_arr = json_decode($table['json_markup'], true);
         $funcs = json_decode($table['func_coords'], true);
         $highest_row = $table['highest_row'];
         $highest_column_index = $table['highest_column_index'];
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

         $json_vals = [];
         $arrKeys = array_slice($arrKeys, 0, $highest_column_index);
         if ($user_role == 2 || $user_role == 3) {

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
         } else {
            foreach ($report_values as $count => $report_value) {
               foreach (array_combine($arrKeys, json_decode($report_value, true)) as $k => $json_val) {
                  $row = $highest_row + $count;
                  $json_vals[$k . $row] = $json_val;
               }

            }
            $func_i = [];
            for ($i = 0; $i < count($funcs); $i++) {
               foreach ($funcs as $key => $func) {
                  $row = $highest_row + $i;
                  $func_i[preg_replace('#\d+#', '' . $row . '', $key)] = preg_replace('#\d+#', '' . $row . '', $func);
               }
            }

            foreach ($json_vals as $key => $val) {
               if (isset($func_i[$key])) {
                  $sheet->getStyle($key)->getNumberFormat()->setFormatCode('0%;-0%');
                  $sheet->setCellValue($key, $func_i[$key]);
               } else {
                  $sheet->setCellValue($key, $val);
               }
               $sheet->getStyle($key)->applyFromArray(array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000')))));
               $sheet->getStyle($key)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
               $sheet->getStyle($key)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            }
         }
         $sheet->insertNewColumnBefore('A', 1);
         $merge = $highest_row - 1;
         $sheet->mergeCells('A1:A' . $merge . '');
         $sheet->setCellValue('A1', 'Учреждение');
         $sheet->getStyle('A1:A' . $merge . '')->applyFromArray(array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000')))));
         $sheet->getStyle('A1:A' . $merge . '')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
         $sheet->getStyle('A1:A' . $merge . '')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

         foreach ($departs as $key => $depart) {
            $row = $highest_row + $key;
            $sheet->setCellValue('A' . $row . '', $depart);
            $sheet->getStyle('A' . $row . '', $depart)->applyFromArray(array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000')))));
            $sheet->getStyle('A' . $row . '', $depart)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A' . $row . '', $depart)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
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
