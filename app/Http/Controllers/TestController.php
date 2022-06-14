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
        $coords = [];
        for ($row = $highestRow; $row <= $highestRow; $row++) {
            for ($col = 0; $col < $highestColumnIndex; $col++) {
                $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                $coord = $worksheet->getCellByColumnAndRow($col, $row)->getCoordinate();
                if(isset($value)) {
                    $coords[$coord] = $value;
                }
            }
        }

        var_dump($coords);
//        $merge_cells = $worksheet->getMergeCells();
//        foreach ($merge_cells as $key => $merge_cell) {
//            $merge_cells[$merge_cell] = preg_replace('#:\w+#', '', $merge_cell);;
//        }
//
//        foreach ($coords as $key => $coord) {
//            foreach ($merge_cells as $k => $merge_arr) {
//                if ($key == $merge_arr) {
//                    $merge_cells[$k] = $coord;
//                    unset($coords[$key]);
//                }
//            }
//        }
//
//        DB::table('test')->insert(['cells' => json_encode(array_merge($coords, $merge_cells))]);

//        return view('export');
    }
}
