<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use function Symfony\Component\String\b;

class NewController extends Controller {
    public function excelToArray() {

        $excel = PHPExcel_IOFactory::load(base_path() . '\Examples\test3.xls');
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

            for ($col = 0; $col < $highestColumnIndex - 1; $col++) {
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
                }

                if (isset($arr[$row][$col]['title'])) {
                    $arr[$row][$col]['id'] = $counter - ($highestColumnIndex * 2) + 1;
                } else {
                    $arr[$row][$col]['title'] = NULL;
                    $arr[$row][$col]['rowStart'] = NULL;
                    $arr[$row][$col]['rowEnd'] = NULL;
                    $arr[$row][$col]['colStart'] = NULL;
                    $arr[$row][$col]['colEnd'] = NULL;
                }
            }
        }

        $arrCell = $arr;

        for ($row = 1; $row < $highestRow; $row++) {

            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

            for ($col = 0; $col < $highestColumnIndex; $col++) {

                if (isset($arr[$row][$col]['title'])) {
                    $colStart = array_keys($arrCoord, $arr[$row][$col]['colStart'] . ':' . $arr[$row][$col]['rowStart']);
                    foreach ($colStart as $cs) {
                        $ce = explode(':', $cs);

                        $arrCell[$row][$col]['colEnd'] = $ce[0];
                        $arrCell[$row][$col]['rowEnd'] = $ce[1];
                    }
                    $arrCell[$row][$col]['colSpan'] = $arrCell[$row][$col]['colEnd'] - $arrCell[$row][$col]['colStart'] + 1;
                    $arrCell[$row][$col]['rowSpan'] = $arrCell[$row][$col]['rowEnd'] - $arrCell[$row][$col]['rowStart'] + 1;
                    if ($arrCell[$row][$col]['colSpan'] == 1) {
                        $arrCell[$row][$col]['colSpan'] = NULL;
                    }
                    if ($arrCell[$row][$col]['rowSpan'] == 1) {
                        $arrCell[$row][$col]['rowSpan'] = NULL;
                    }

                } else {
                    $arrCell[$row][$col] = NULL;
                }
            }
        }

//                echo '<pre>';
//        var_dump($arrCell);
//        echo '</pre>';


        echo json_encode($arrCell, JSON_UNESCAPED_UNICODE) . '<br />';

        echo '<table border="1">' . PHP_EOL;
        for ($i = 1; $i < $highestRow; $i++) {
            echo '<tr>' . PHP_EOL;
            for ($k = 0; $k < $highestColumnIndex - 1; $k++) {
                echo '<td>' . $arrCell[$i][$k]['title'] . '<br/>'
                    . 'col' . ':' . $arrCell[$i][$k]['colStart'] . ':'
                    . $arrCell[$i][$k]['colEnd'] .  '<br />'
                    . 'colspan: ' . $arrCell[$i][$k]['colSpan'] . '<br />'
                    . 'row' . ':' . $arrCell[$i][$k]['rowStart'] . ':'
                    . $arrCell[$i][$k]['rowEnd'] . '<br/>'
                    . 'rowspan: ' . $arrCell[$i][$k]['rowSpan'] . '<br />'
                    . 'id' . ':' . $arrCell[$i][$k]['id']
                    . '</td>' . PHP_EOL;
            }
            echo '</tr>' . PHP_EOL;
        }
        echo '</table>' . PHP_EOL;
    }
}
