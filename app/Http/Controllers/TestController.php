<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PHPExcel_Cell;
use PHPExcel_IOFactory;
use App\Http\Requests;

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

        for ($row = $highestRow; $row <= $highestRow; $row++) {
            $colCounter = 0;

            for ($col = 0; $col < $highestColumnIndex; $col++) {
                $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                $colCounter++;
                if (isset($value)) {
                    $arr[$colCounter] = $value;
                } else {
                    $arr[$colCounter] = NULL;
                }
            }
        }
        $arrLetters = [];
        $arrKeys = [];
        for ($i = "A"; $i <= 'Z'; $i++) {
            $arrKeys[] = $i;
        }

        $arrKeys = array_slice($arrKeys, 0, $highestColumnIndex);
        array_unshift($arrKeys, 1);
        unset($arrKeys[0]);
        $arrKeys = array_flip($arrKeys);

        foreach ($arr as $key => $val) {
            if (isset($val)) {
                $arrLetters[$key] = preg_replace('#[\d=SUM\(\)]#', '', $val . '|');;
            }
        }

        $strLetters = implode($arrLetters);

        for ($i = 0; $i < strlen($strLetters); $i++) {
            foreach ($arrKeys as $key => $val) {
                $strLetters = str_replace($key, $val, $strLetters);
            }
        }

        $arrLetters = explode('|', $strLetters);
        unset($arrLetters[count($arrLetters) - 1]);
        var_dump($arrLetters);

        $json = json_encode($arr, JSON_UNESCAPED_UNICODE);
        return view('ultest', ['test' => $json, 'highestColumnIndex' => $highestColumnIndex, 'highest_row' => $highestRow]);
    }
}
