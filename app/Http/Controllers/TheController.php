<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class TheController extends Controller {
    public function import() {
//        require 'vendor/autoload.php';
        $filePath = __DIR__ . "/test/test5.xlsx";
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(TRUE);
        $spreadsheet = $reader->load($filePath);
        $loadedSheetNames = $spreadsheet->getSheetNames();

        foreach ($loadedSheetNames as $sheetIndex => $loadedSheetName) {
            $sheet = $spreadsheet->getSheet($sheetIndex);
            echo "<table border=\"1\">";
            $rows = $sheet->toArray();
            $mergeCell[] = $sheet->getMergeCells();
            var_dump($mergeCell);
//            foreach ($rows as $row) {
//                echo "<tr>";
//                foreach ($row as $cell) {
//                    echo "<td>" . $cell . "</td>";
//                }
//            }
        }
    }
}
