<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use PHPExcel_Writer_Excel2007;
use Illuminate\Support\Facades\DB;
use PHPExcel_Style_Border;
use PHPExcel;
use PHPExcel_Style_Alignment;
use PHPExcel_Cell_DataType;
use PHPExcel_Worksheet_PageSetup;
use App\Http\Requests;

class ExportSheetController extends Controller {
    public function export($row_uuid) {
        try {
            date_default_timezone_set('Europe/Moscow');

            $date = date('d_m_Y');

            $report_values = DB::table('report_values')->where('row_uuid', '=', $row_uuid)->where('status', 0)->get();
            $table_uuid = $report_values[0]->table_uuid;
            $json_val = $report_values[0]->json_val;
            $table_name = $report_values[0]->table_name;
            $cells_arr = $report_values[0]->json_markup;
            $funcs = $report_values[0]->func_coords;
            $highest_row = $report_values[0]->highest_row;

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

            $arrKeys = array_slice($arrKeys, 0, count($json_val));

            $json_vals = [];

            foreach (array_combine($arrKeys, $json_val) as $k => $json_val) {
                $json_vals[$k . $highest_row] = $json_val;
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

            header("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
            header("Last-Modified: " . date("D,d M YH:i:s"));
            header("Cache-Control: no-cache, must-revalidate");
            header("Pragma: no-cache");
            header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
            header("Content-Disposition: attachment; filename=$table_name-$date.xlsx");

            $objWriter = new PHPExcel_Writer_Excel2007($xls);
            $objWriter->save('php://output');
            exit();

            return view('router', ['alert' => 'Таблица успешно сохранена', 'route' => '/json']);
        } catch (QueryException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
    }
}
