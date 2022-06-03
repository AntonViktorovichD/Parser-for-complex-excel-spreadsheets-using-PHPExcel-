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

        $mergeCells[] = $worksheet->getMergeCells();
        $mCells = [];

        for ($i = 0; $i < count($mergeCells); $i++) {
            foreach ($mergeCells[$i] as $mergeCell) {
                if (strpos($mergeCell, $highestRow)) {
                    $mCells[] = $mergeCell;
                }
            }
        }

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
                if (isset($arrTypes[$key]) && !str_contains($val, ')/')) {
                    $arrLetters[$key] = preg_replace('#[=\d\)\(]+#', '', $val . ' ' . 'rate' . '|'); //rate
                } else if (is_numeric($val)) {
                    $arrLetters[$key] = $val . '|'; //rate
                } elseif (isset($arrTypes[$key]) && str_contains($val, ')/')) {
                    $arrLetters[$key] = preg_replace('#[=\(\)\d]+#', '', $val . ' ' . 'crease' . '|'); //crease
                } elseif (str_contains($val, 'SUM')) {
                    $arrLetters[$key] = preg_replace('#[\(\)\d]+#', '', preg_replace('#=SUM#', '', $val . ' ' . 'sum' . '|')); //sum
                } elseif (str_contains($val, '-')) {
                    $arrLetters[$key] = preg_replace('#[\d=]#', '', $val . ' ' . 'diff' . '|'); //diff
                } elseif (str_contains($val, 'PRODUCT')) {
                    $arrLetters[$key] = preg_replace('#[\(\)\d]+#', '', preg_replace('#=PRODUCT#', '', $val . ' ' . 'prod' . '|')); //prod
                } elseif (str_contains($val, '/')) {
                    $arrLetters[$key] = preg_replace('#[\d=]+#', '', $val . ' ' . 'divide' . '|'); //div
                }
            }
        }
        $strMCells = [];
        foreach ($mCells as $key => $mCell) {
            $strMCells[] = preg_replace('#\d+#', '', $mCell . '|');
        }

        $strMC = implode($strMCells);

        for ($i = 0; $i < strlen($strMC); $i++) {
            foreach ($arrKeys as $key => $val) {
                $strMC = str_replace($key, $val, $strMC);
            }
        }

        $strMC = explode('|', $strMC);
        unset($strMC[count($strMC) - 1]);

        $arrMC = [];

        foreach ($strMC as $key => $str) {
            $arrMC[preg_replace('#:\d+#', '', $str)] = ' | ' . $str . ' merge';
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

        $finArr = [];

        for ($i = 1; $i <= $highestColumnIndex; $i++) {
            if (isset($arrSum[$i]) && isset($arrMC[$i])) {
                $finArr[$i] = $arrSum[$i] . $arrMC[$i];
            } elseif (isset($arrSum[$i])) {
                $finArr[$i] = $arrSum[$i];
            } elseif (isset($arrMC[$i])) {
                $finArr[$i] = $arrMC[$i];
            }
        }

        $arrj = json_encode($finArr);
        $json_func = json_encode($arr, JSON_UNESCAPED_UNICODE);
        return view('testul', ['test' => $json_func, 'json_sum' => $arrj, 'highestColumnIndex' => $highestColumnIndex]);
    }
}
