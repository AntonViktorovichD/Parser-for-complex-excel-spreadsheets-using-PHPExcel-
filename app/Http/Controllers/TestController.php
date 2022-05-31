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
        $arrK = [];
        $arrTypes = [];
        for ($row = $highestRow; $row <= $highestRow; $row++) {
            $colCounter = 0;
            for ($col = 0; $col < $highestColumnIndex; $col++) {
                $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                $colCounter++;
                if (isset($value)) {
                    $arr[$colCounter] = $value;
                    $arrK[] = $colCounter;
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
        $cell_type = 0;

        $arrKeys = array_slice($arrKeys, 0, $highestColumnIndex);
        array_unshift($arrKeys, 1);
        unset($arrKeys[0]);
        foreach ($arrKeys as $value) {
            $cell_type = $worksheet->getCell($value . $highestRow)->getStyle()->getNumberFormat()->getFormatCode();
            if ($cell_type != 'General') {
                $dig = array_search($value, $arrKeys);
                $arrTypes[$dig] = preg_replace('#\w+#', '', $cell_type);
            }
        }
        $arrKeys = array_flip($arrKeys);
        foreach ($arr as $key => $val) {
            if (isset($val)) {
                if (isset($arrTypes[$key])) {
                    $arrLetters[$key] = preg_replace('#[\d\)]#', '', preg_replace('#=#', '', $val . ' ' . $arrTypes[$key] . '|')); //rate
                } else if (is_numeric($val)) {
                    $arrLetters[$key] = $val . '|'; //rate
                } else if (strripos($val, 'SUM')) {
                    $arrLetters[$key] = preg_replace('#[\d\)]#', '', preg_replace('#=SUM\(#', '', $val . ' ' . 'sum' . '|'));
                } else {
                    $arrLetters[$key] = preg_replace('#[\d\)]#', '', preg_replace('#=#', '', $val . '|')); //rate
                }
//                $arrLetters[$key] = preg_replace('#[\d\)]#', '', preg_replace('#=#', '', $val . '|')); //div
//                $arrLetters[$key] = preg_replace('#[\d\)]#', '', preg_replace('#=PRODUCT\(#', '', $val . '|')); //prod
//                $arrLetters[$key] = preg_replace('#[\d\)]#', '', preg_replace('#=SUM\(#', '', $val . '|')); //sum
//                $arrLetters[$key] = preg_replace('#[\d=]#', '', $val . '|'); //diff
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
        $arrSum = array_combine($arrK, $arrLetters);
        $arrj = json_encode($arrSum);
        $json = json_encode($arr, JSON_UNESCAPED_UNICODE);
        return view('testul', ['test' => $json, 'jsum' => $arrj, 'sum' => $arrSum, 'highestColumnIndex' => $highestColumnIndex]);
    }
}
