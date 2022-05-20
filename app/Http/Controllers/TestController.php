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

//        $arrStr = [];
//        $j = 0;
//        foreach ($arrLetters as $letter) {
//            for ($i = 0; $i < count(str_split($letter)); $i++) {
//                $arrStr[] = $letter[$i];
//            }
//        }
//
//        for ($i = 0; $i < count($arrStr); $i++) {
//            foreach ($arrKeys as $key => $val) {
//                if ($arrStr[$i] == $key) {
//                    $arrDigits[$i] = $val;
//                } elseif ($arrStr[$i] != $key && ($arrStr[$i] == ',' || $arrStr[$i] == ':' || $arrStr[$i] == ';')) {
//                    $arrDigits[$i] = $arrStr[$i];
//                    break;
//                }
//            }
//        }
//
//        foreach ($mergeCells as $mergeCell) {
//            $arrLett = array_slice(preg_split('#\d+:|\d+#', implode(':', $mergeCell)), 0, count($arrLett) - 1);
//            foreach ($arrLett as $val) {
//                foreach (array_combine($arrKeys, $arrVals) as $key => $digit) {
//                    if ($val == $key) {
//                        $arrCoord[] = $digit;
//                    }
//                }
//            }
//        }
//
//        $arrLett = array_slice(preg_split('#\D+:|\D+#', implode(':', $mergeCell)), 1);
//
//        foreach ($arrCoord as $key => $coord) {
//            if ($key == ($key % 2 == 0) || $key == 0) {
//                $colStart[] = $coord;
//            } else {
//                $colEnd[] = $coord;
//            }
//        }
//
//        foreach ($arrLett as $key => $coord) {
//            if ($key == ($key % 2 == 0) || $key == 0) {
//                $rowStart[] = $coord;
//            } else {
//                $rowEnd[] = $coord;
//            }
//        }
//
//        for ($i = 0; $i < count($colEnd); $i++) {
//            $arrColEnd[$i] = $colEnd[$i];
//        }
//
//        for ($i = 0; $i < count($rowEnd); $i++) {
//            $arrRowEnd[$i] = $rowEnd[$i];
//        }
//
//        for ($i = 0; $i < count($arrCoord); $i++) {
//            $arrCoord[$i] = $arrCoord[$i] . ':' . $arrLett[$i];
//        }
//
//        $arrCoord = array_chunk($arrCoord, 2);
//
//        for ($i = 0; $i < count($arrCoord); $i++) {
//            $arrStart[$i] = $arrCoord[$i][0];
//            $arrEnd[$i] = $arrCoord[$i][1];
//        }
//
//        $arrCoord = array_combine($arrEnd, $arrStart);
//
//        if (!empty($arrCoord)) {
//
//            for ($row = 1; $row < $highestRow; $row++) {
//                $colCounter = 1;
//
//                $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
//
//                for ($col = 0; $col < $highestColumnIndex + 1; $col++) {
//                    $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
//
//                    $counter++;
//
//                    if (isset($value)) {
//                        $colCounter = $col;
//                        $arr[$row][$col]['title'] = $value;
//                        $arr[$row][$col]['rowStart'] = $row;
//                        $arr[$row][$col]['rowEnd'] = $row;
//                        $arr[$row][$col]['colStart'] = $col + 1;
//                        $arr[$row][$col]['colEnd'] = $col + 1;
//                    } elseif (isset($arr[$row][$col])) {
//                        $arr[$row][$colCounter]['colEnd'] = $col;
//                    } elseif (empty($arr[1][0]['title'])) {
//                        $arr[$row][$col]['rowStart'] = $row;
//                        $arr[$row][$col]['colStart'] = $col + 1;
//                    }
//
//                    if (isset($arr[$row][$col]['title'])) {
//                        $arr[$row][$col]['id'] = $counter - $highestColumnIndex;
//                    } elseif (empty($arr[1][0]['title'])) {
//                        $arr[$row][$col]['title'] = 'empty';
//                        $arr[$row][$col]['rowStart'] = $row;
//                        $arr[$row][$col]['colStart'] = $col + 1;
//                        $arr[$row][$col]['rowEnd'] = $row;
//                        $arr[$row][$col]['colEnd'] = $col + 1;
//                    } else {
//                        unset($arr[$row][$col]);
//                    }
//                }
//            }
//
//            $arrCell = $arr;
//
//            for ($row = 1; $row < $highestRow; $row++) {
//
//                $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
//
//                for ($col = 0; $col < $highestColumnIndex; $col++) {
//
//                    if (isset($arr[$row][$col]['title'])) {
//                        if ($arr[$row][$col]['title'] == 'empty') {
//                            $arrCell[1][0]['title'] = NULL;
//                        }
//                        $colStart = array_keys($arrCoord, $arr[$row][$col]['colStart'] . ':' . $arr[$row][$col]['rowStart']);
//                        foreach ($colStart as $cs) {
//                            $ce = explode(':', $cs);
//                            $arr[$row][$col]['colEnd'] = $ce[0];
//                            $arr[$row][$col]['rowEnd'] = $ce[1];
//                        }
//                        $arr[$row][$col]['colSpan'] = $arr[$row][$col]['colEnd'] - $arr[$row][$col]['colStart'] + 1;
//                        $arr[$row][$col]['rowSpan'] = $arr[$row][$col]['rowEnd'] - $arr[$row][$col]['rowStart'] + 1;
//                        $arrCell[$row][$col]['cell'] = '<td rowspan= ' . $arr[$row][$col]["rowSpan"] . ' colspan= ' . $arr[$row][$col]["colSpan"] . '>' . $arrCell[$row][$col]['title'] . '</td>';
//                        $arrCell[$row][$col]['colStartView'] = $arr[$row][$col]['colStart'];
//                        $arrCell[$row][$col]['rowStartView'] = $arr[$row][$col]['rowStart'];
//                        $arrCell[$row][$col]['colEndView'] = $arr[$row][$col]['colEnd'];
//                        $arrCell[$row][$col]['rowEndView'] = $arr[$row][$col]['rowEnd'];
//                    } else {
//                        $arrCell[$row][$col] = NULL;
//                    }
//                }
//            }
//        }
        $json = json_encode($arr, JSON_UNESCAPED_UNICODE);
        return view('ultest', ['test' => $json, 'highestColumnIndex' => $highestColumnIndex, 'highest_row' => $highestRow]);
    }
}
