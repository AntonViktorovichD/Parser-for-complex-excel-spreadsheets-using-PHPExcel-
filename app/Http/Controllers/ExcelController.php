<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use PhpParser\Node\Stmt\If_;
use function Symfony\Component\String\b;

class ExcelController extends Controller {

    public function excelToArray() {
        date_default_timezone_set('Europe/Moscow');
        $excel = PHPExcel_IOFactory::load(base_path() . $filename);
        var_dump(base_path() . $filename);
        $worksheet = $excel->getActiveSheet();
        $mergeCells[] = $worksheet->getMergeCells();
        $highestRow = $worksheet->getHighestRow();
        $highestColumn = $worksheet->getHighestColumn();
        $highestColumn++;

        $counter = 1;
        $arrKeys = [];
        $arrVals = [];
        $arrLett = [];
        $arrCoord = [];
        $colStart = [];
        $colEnd = [];
        $rowStart = [];
        $rowEnd = [];
        $arrStart = [];
        $arrColEnd = [];
        $arrRowEnd = [];
        $arrEnd = [];

        for ($column = 'A'; $column != $highestColumn; $column++) {
            $arrKeys[] = $column;
            $arrVals[] = $counter++;
        }

        foreach ($mergeCells as $mergeCell) {
            $arrLett = array_slice(preg_split('#\d+:|\d+#', implode(':', $mergeCell)), 0, count($arrLett) - 1);
            foreach ($arrLett as $val) {
                foreach (array_combine($arrKeys, $arrVals) as $key => $digit) {
                    if ($val == $key) {
                        $arrCoord[] = $digit;
                    }
                }
            }
        }

        $arrLett = array_slice(preg_split('#\D+:|\D+#', implode(':', $mergeCell)), 1);

        foreach ($arrCoord as $key => $coord) {
            if ($key == ($key % 2 == 0) || $key == 0) {
                $colStart[] = $coord;
            } else {
                $colEnd[] = $coord;
            }
        }

        foreach ($arrLett as $key => $coord) {
            if ($key == ($key % 2 == 0) || $key == 0) {
                $rowStart[] = $coord;
            } else {
                $rowEnd[] = $coord;
            }
        }

        for ($i = 0; $i < count($colEnd); $i++) {
            $arrColEnd[$i] = $colEnd[$i];
        }

        for ($i = 0; $i < count($rowEnd); $i++) {
            $arrRowEnd[$i] = $rowEnd[$i];
        }

        for ($i = 0; $i < count($arrCoord); $i++) {
            $arrCoord[$i] = $arrCoord[$i] . ':' . $arrLett[$i];
        }

        $arrCoord = array_chunk($arrCoord, 2);

        for ($i = 0; $i < count($arrCoord); $i++) {
            $arrStart[$i] = $arrCoord[$i][0];
            $arrEnd[$i] = $arrCoord[$i][1];
        }

        $arrCoord = array_combine($arrEnd, $arrStart);

        for ($row = 1; $row < $highestRow; $row++) {
            $colCounter = 1;

            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

            for ($col = 0; $col < $highestColumnIndex; $col++) {
                $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();

                $counter++;

                if (isset($value)) {
                    $colCounter = $col;
                    $arr[$row][$col]['title'] = $value;
                    $arr[$row][$col]['rowStart'] = $row;
                    $arr[$row][$col]['rowEnd'] = $row;
                    $arr[$row][$col]['colStart'] = $col + 1;
                    $arr[$row][$col]['colEnd'] = $col + 1;
                } elseif (isset($arr[$row][$col])) {
                    $arr[$row][$colCounter]['colEnd'] = $col;
                } elseif (empty($arr[1][0]['title'])) {
                    $arr[$row][$col]['rowStart'] = $row;
                    $arr[$row][$col]['colStart'] = $col + 1;
                }

                if (isset($arr[$row][$col]['title'])) {
                    $arr[$row][$col]['id'] = $counter - $highestColumnIndex;
                } elseif (empty($arr[1][0]['title'])) {
                    $arr[$row][$col]['title'] = 'empty';
                    $arr[$row][$col]['rowStart'] = $row;
                    $arr[$row][$col]['colStart'] = $col + 1;
                    $arr[$row][$col]['rowEnd'] = $row;
                    $arr[$row][$col]['colEnd'] = $col + 1;
                } else {
                    unset($arr[$row][$col]);
                }
            }
        }

        $arrCell = $arr;

        for ($row = 1; $row < $highestRow; $row++) {

            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

            for ($col = 0; $col < $highestColumnIndex; $col++) {

                if (isset($arr[$row][$col]['title'])) {
                    if ($arr[$row][$col]['title'] == 'empty') {
                        $arrCell[1][0]['title'] = NULL;
                    }
                    $colStart = array_keys($arrCoord, $arr[$row][$col]['colStart'] . ':' . $arr[$row][$col]['rowStart']);
                    foreach ($colStart as $cs) {
                        $ce = explode(':', $cs);

                        $arr[$row][$col]['colEnd'] = $ce[0];
                        $arr[$row][$col]['rowEnd'] = $ce[1];
                    }
                    $arr[$row][$col]['colSpan'] = $arr[$row][$col]['colEnd'] - $arr[$row][$col]['colStart'] + 1;
                    $arr[$row][$col]['rowSpan'] = $arr[$row][$col]['rowEnd'] - $arr[$row][$col]['rowStart'] + 1;
                    $arrCell[$row][$col]['cell'] = '<td rowspan= ' . $arr[$row][$col]["rowSpan"] . ' colspan= ' . $arr[$row][$col]["colSpan"] . '>' . $arrCell[$row][$col]['title'] . '</td>';
                } else {
                    $arrCell[$row][$col] = NULL;
                }
            }
        }
    }
}
