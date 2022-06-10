<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use PHPExcel_Style_Border;
use PHPExcel;
use PHPExcel_Worksheet_PageSetup;
use PHPExcel_Writer_Excel2007;
use App\Http\Requests;

class ExportSheetController extends Controller {
    public function export() {

        $coords = json_decode(DB::table('test')->pluck('coord')[0], true);
        $merge_cells = json_decode(DB::table('test')->pluck('merge_cells')[0], true);

        foreach ($merge_cells as $key => $merge_cell) {
            $merge_cells[$merge_cell] = preg_replace('#:\w+#', '', $merge_cell);;
        }

        foreach ($coords as $key => $coord) {
            foreach ($merge_cells as $k => $merge_arr) {
                if ($key == $merge_arr) {
                    $merge_cells[$k] = $coord;
                    unset($coords[$key]);
                }
            }
        }

        $cells_arr = array_merge($coords, $merge_cells);

//        foreach ($cells_arr as $key => $cell) {
//            if(str_contains($key, ':')) {
//                $sheet->mergeCells($key);
//                $merge = preg_replace('#:\w+#', '', $key);
//                $sheet->setCellValue($merge, $cell);
//                $sheet->getStyle($key)->applyFromArray($border);
//            } else {
//                $sheet->setCellValue($key, $cell);
//                $sheet->getStyle($key)->applyFromArray($border);
//            }
//        }

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

        $border = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000'))));

        foreach ($cells_arr as $key => $cell) {
            if(str_contains($key, ':')) {
                $sheet->mergeCells($key);
                $merge = preg_replace('#:\w+#', '', $key);
                $sheet->setCellValue($merge, $cell);
            } else {
                $sheet->setCellValue($key, $cell);
            }
            $sheet->getStyle($key)->applyFromArray($border);
        }

        header("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment; filename=file.xlsx");

        $objWriter = new PHPExcel_Writer_Excel2007($xls);
        $objWriter->save('php://output');
        exit();

        return view("export");
    }
}
