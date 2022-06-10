<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PHPExcel_Cell;
use PHPExcel_IOFactory;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
class TestController extends Controller {
    public function test() {
        return view('test');
    }

    public function ultest(Request $request) {
        $file = $request->file('userfile');
        $newFileName = bin2hex(random_bytes(10)) . '.tmp';
        Storage::putFileAs('public/folder', $file, $newFileName);
        $tmpPath = base_path() . '/storage/app/public/folder' . '/' . $newFileName;

        $excel = PHPExcel_IOFactory::load($tmpPath);
        $worksheet = $excel->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();
        $highestColumn = $worksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        $arr = [];
        for ($row = 1; $row < $highestRow; $row++) {
            $colCounter = 0;
            for ($col = 0; $col < $highestColumnIndex; $col++) {
                $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                $coord = $worksheet->getCellByColumnAndRow($col, $row)->getCoordinate();
                if (isset($value)) {
                    $arr[$coord] = $value;
                }
            }
        }
        $mergeCells = $worksheet->getMergeCells();
        $mc = json_encode($mergeCells);
        $cv = json_encode($arr);
        DB::table('test')->insert(['coord' => $cv, 'merge_cells' => $mc]);
//        echo '<pre>';
//        var_dump($arr);
//        echo '</pre>';

        return view('test');
    }
}
