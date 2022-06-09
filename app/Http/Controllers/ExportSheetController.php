<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PHPExcel_Cell;
use PHPExcel;
use PHPExcel_Worksheet_PageSetup;
use PHPExcel_Writer_Excel2007;
use PHPExcel_IOFactory;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class ExportSheetController extends Controller {
    public function export() {

        $data = DB::table('test')->get();

        var_dump($data);

//        $xls = new PHPExcel();
//
//        $xls->setActiveSheetIndex(0);
//        $sheet = $xls->getActiveSheet();
//        $sheet->setTitle('Название листа');
//
//        // Формат
//        $sheet->getPageSetup()->SetPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
//
//// Ориентация
//// ORIENTATION_PORTRAIT — книжная
//// ORIENTATION_LANDSCAPE — альбомная
//        $sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
//
//// Поля
//        $sheet->getPageMargins()->setTop(1);
//        $sheet->getPageMargins()->setRight(0.75);
//        $sheet->getPageMargins()->setLeft(0.75);
//        $sheet->getPageMargins()->setBottom(1);
//
//// Верхний колонтитул
//        $sheet->getHeaderFooter()->setOddHeader("Название листа");
//
//// Нижний колонтитул
//        $sheet->getHeaderFooter()->setOddFooter('&L&B Название листа &R Страница &P из &N');
//        $sheet->setCellValue("A1", "Значение");
////
////        echo '<pre>';
////        var_dump($sheet);
////        echo '</pre>';
//
//        header("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
//        header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
//        header("Cache-Control: no-cache, must-revalidate");
//        header("Pragma: no-cache");
//        header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
//        header("Content-Disposition: attachment; filename=file.xlsx");
//
////        $objWriter = new PHPExcel_Writer_Excel2007($xls);
////        $objWriter->save('php://output');
////        exit();

        return view("export");
    }
}
