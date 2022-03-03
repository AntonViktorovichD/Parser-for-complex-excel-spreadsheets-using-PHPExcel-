<?php



namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NewController extends Controller {
    public function import() {
        $filePath = __DIR__ . "/test/test2.xls";
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xls');
        $reader->setReadDataOnly(TRUE);
        $spreadsheet = $reader->load($filePath);
        $loadedSheetNames = $spreadsheet->getSheetNames();

        foreach ($loadedSheetNames as $sheetIndex => $loadedSheetName) {
            $worksheet = $spreadsheet->getActiveSheet();
            $highestRow = $worksheet->getHighestRow(); // e.g. 10
            $highestColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($worksheet->getHighestColumn()); // e.g. 5
//            $arr = [];
//            $arr2 = [];
            $arrCol = [];
            $arrRow = [];
            $counter = 0;

//            for ($row = 1; $row <= $highestRow; ++$row) {
//                $rowCounter = 0;
//                $colCounter = 0;
//                for ($col = 1; $col <= $highestColumn; ++$col) {
//                    $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
//
//                    $counter++;
//
//                    if ($col == 1 && empty($value)) {
//                        continue;
//                    }
//
//                    if (isset($value)) {
//                        $rowCounter = $col;
//                        $arr[$row][$col]['title'] = $value;
//                        $arr[$row][$col]['rowStart'] = $col;
//                        $arr[$row][$col]['rowEnd'] = $col;
//                    } else {
//                        $arr[$row][$rowCounter]['rowEnd'] = $col;
//                    }
//
//                    if (isset($arr[$row][$col]['title'])) {
//                        $arr[$row][$col]['id'] = $counter;
//                    }
//
//                    if ($row == 1 && empty($value)) {
//                        continue;
//                    }
//
//                    if (isset($value)) {
//                        $colCounter = $row;
//                        $arr2[$col][$row]['title'] = $value;
//                        $arr2[$col][$row]['colStart'] = $row;
//                        $arr2[$col][$row]['colEnd'] = $row;
//                    } else {
//                        $arr2[$col][$colCounter]['colEnd'] = $row;
//                    }
//
//                    if ($arr[$row][$col]['title'] == $arr2[$col][$row]['title']) {
//                        $arr2[$col][$row]['colStart'] = $row;
//                        $arr2[$col][$row]['colEnd'] = $row;
//                    }
//                }
//            }

            for ($row = 1; $row <= $highestRow; ++$row) {
                $rowCounter = 0;
                for ($col = 1; $col <= $highestColumn; ++$col) {
                    $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();

                    $counter++;

                    if ($col == 1 && empty($value)) {
                        continue;
                    }

                    if (isset($value)) {
                        $rowCounter = $col;
                        $arrRow[$row][$col]['title'] = $value;
                        $arrRow[$row][$col]['rowStart'] = $row;
                        $arrRow[$row][$col]['rowEnd'] = $row;
                        $arrRow[$row][$col]['colStart'] = $col;
                        $arrRow[$row][$col]['colEnd'] = $col;
                    } else {
                        $arrRow[$row][$rowCounter]['colEnd'] = $col;

                    }
//
//                    if (isset($arrRow[$row][$col]['title'])) {
//
//                    }

                    if (isset($arrRow[$row][$col]['title'])) {
                        $arrRow[$row][$col]['id'] = $counter;
                    }

                    if ($row == 1 && empty($value)) {
                        continue;
                    }

                    if (isset($value)) {
                        $colCounter = $row;
                        $arrCol[$col][$row]['title'] = $value;
                        $arrCol[$col][$row]['colStart'] = $row;
                        $arrCol[$col][$row]['colEnd'] = $row;
                    } else {
                        $arrCol[$col][$colCounter]['colEnd'] = $row;
                    }

                    if (empty($arrCol[$col][$row]['title']) && isset($arrCol[$col][$row - 1]['title'])) {
                        $arrCol[$col][$row]['title'] = $arrCol[$col][$row - 1]['title'];
                        $arrCol[$col][$row]['colStart'] = $row;
                        $arrCol[$col][$row]['colEnd'] = $row;
                    }


//                    if ($arrRow[$row][$col]['title'] == $arrCol[$col][$row]['title']) {
//                        $arrRow[$row][$col]['colStart'] = $arrCol[$col][$row]['colStart'];
//                        $arrRow[$row][$col]['colEnd'] = $arrCol[$col][$row]['colEnd'];
//                    }
                }
            }

//            foreach ($arrRow as $arr) {
//                foreach ($arr as $item) {
//
//                    if ($item['title'] == $arrCol[$col][$row]['title']) {
//                        echo 11;
//                    }
////                    echo '<pre>';
////                    var_dump($item['title']);
////                    echo '</pre>';
//                }
//            }
            echo '<pre>';
            var_dump($arrRow);
            var_dump($arrCol);
            echo '</pre>';
        }
    }
}


/////////////////////////////////////////////////////////////////////////////////



<?php

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NewController extends Controller {
    public function import() {
        $filePath = __DIR__ . "/test/test2.xls";
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xls');
        $reader->setReadDataOnly(TRUE);
        $spreadsheet = $reader->load($filePath);
        $loadedSheetNames = $spreadsheet->getSheetNames();

        foreach ($loadedSheetNames as $sheetIndex => $loadedSheetName) {
            $worksheet = $spreadsheet->getActiveSheet();
            $highestRow = $worksheet->getHighestRow(); // e.g. 10
            $highestColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($worksheet->getHighestColumn()); // e.g. 5
            $arr = [];
            $counter = 0;

            for ($row = 1; $row <= $highestRow; ++$row) {
                $colCounter = 1;

                for ($col = 1; $col <= $highestColumn; ++$col) {
                    $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                    $counter++;

                    if (isset($value)) {
                        $colCounter = $col;
                        $arr[$row][$col]['title'] = $value;
                        $arr[$row][$col]['rowStart'] = $col;
                        $arr[$row][$col]['rowEnd'] = $col;
                        $arr[$row][$col]['colStart'] = $row;
                        $arr[$row][$col]['colEnd'] = $row;
                    } elseif (isset($arr[$row])) {
                        $arr[$row][$colCounter]['rowEnd'] = $col;
                    }
                    if (isset($arr[$row][$col]['title'])) {
                        $arr[$row][$col]['id'] = $counter;
                        $arr[$row][$col]['coord'] = $arr[$row][$col]['rowStart'] . ' : ' . $arr[$row][$col]['colStart'];
                    }
                    if (!isset($arr[$row][$col]['title']) && isset($arr[$row][$col - 1]['title'])) {
                        $arr[$row][$col]['title'] = $arr[$row][$col - 1]['title'];
                    } elseif (!isset($arr[$row][$col]['title']) && isset($arr[$row  - 1][$col]['title'])) {
                        $arr[$row][$col]['title'] = $arr[$row - 1][$col]['title'];
                    }

                }
            }
        }
        for ($i = 1; $i <= $highestRow; $i++) {
            echo '<pre>';
            var_dump($arr[$i]);
            echo '</pre>';
        }
    }
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

public function import() {
    $filePath = __DIR__ . "/test/test5.xls";
    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xls');
    $reader->setReadDataOnly(TRUE);
    $spreadsheet = $reader->load($filePath);
    $loadedSheetNames = $spreadsheet->getSheetNames();

    foreach ($loadedSheetNames as $sheetIndex => $loadedSheetName) {
        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();
        $highestColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($worksheet->getHighestColumn());
        $arr = [];
        $arrCell = [];
        $counter = 0;

        for ($row = 1; $row < $highestRow; ++$row) {
            $colCounter = 1;

            for ($col = 1; $col <= $highestColumn; ++$col) {
                $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                $counter++;

                if (isset($value)) {
                    $colCounter = $col;
                    $arr[$row][$col]['title'] = $value;
                    $arr[$row][$col]['rowStart'] = $row;
                    $arr[$row][$col]['rowEnd'] = $row;
                    $arr[$row][$col]['colStart'] = $col;
                    $arr[$row][$col]['colEnd'] = $col;
                } elseif (isset($arr[$row])) {
                    $arr[$row][$colCounter]['colEnd'] = $col;
                }

                if (isset($arr[$row][$col]['title'])) {
                    $arr[$row][$col]['id'] = $counter;
//                        $arr[$row][$col]['coord'] = $arr[$row][$col]['colStart'] . ' : ' . $arr[$row][$col]['rowStart'];
                } elseif (!isset($arr[$row][$col]['title']) || !isset($arr[$row][$col]['title']) && isset($arr[$row - 1][$col - 1]['title'])) {
                    $arr[$row][$col]['title'] = NULL;
                    $arr[$row][$col]['rowStart'] = NULL;
                    $arr[$row][$col]['rowEnd'] = NULL;
                    $arr[$row][$col]['colStart'] = NULL;
                    $arr[$row][$col]['colEnd'] = NULL;
                }

                if (isset($arr[$row - 1][$col]['title']) && !isset($arr[$row][$col]['title'])) {
                    $arr[$row - 1][$col]['rowEnd'] = $row;
                } else {
                    for ($i = 2; $i < $highestColumn; $i++) {
                        if (isset($arr[$row - $i][$col]['title']) && !isset($arr[$row][$col]['title'])) {
                            $arr[$row - $i][$col]['rowEnd'] = $row;
                        }
                    }
                }
            }
        }
        $arrCell = $arr;
        for ($row = 1; $row < $highestRow; $row++) {
            for ($col = 1; $col <= $highestColumn; $col++) {
                if (isset($arr[$row][$col]['title'])) {
                    if (isset($arr[$row][$col]['title']) && !isset($arr[$row + 1][$col]['title'])) {
                        for ($k = 1; $k <= $col; $k++) {
                            if (isset($arr[$row + 1][$col - $k]['title'])) {
                                if ($arr[$row][$col]['colStart'] < $arr[$row + 1][$col - $k]['colEnd'] && $arr[$row][$col]['colStart'] > $arr[$row + 1][$col - $k]['colStart']) {
                                    $arrCell[$row][$col]['rowEnd'] = $arrCell[$row][$col]['rowStart'];
                                }
                            }
                        }
                    }
                }

            }
        }
    }

    echo '<table border="1">' . PHP_EOL;
    for ($i = 1;
         $i < $highestRow;
         $i++) {
        echo '<tr>' . PHP_EOL;
        for ($k = 1;
             $k <= $highestColumn;
             $k++) {
            echo '<td>' . $arrCell[$i][$k]['title']
                . '<br/>' . 'row' . ':' . $arrCell[$i][$k]['rowStart'] . ':'
                . $arrCell[$i][$k]['rowEnd'] . '<br/>' . 'col' . ':'
                . $arrCell[$i][$k]['colStart'] . ':'
                . $arrCell[$i][$k]['colEnd'] . '</td>' . PHP_EOL;
        }
        echo '</tr>' . PHP_EOL;
    }
    echo '</table>' . PHP_EOL;
}
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///
///
public function import() {

    $excel = PHPExcel_IOFactory::load(base_path() . '\Examples\test5.xls');
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

    for ($column = 'A'; $column != $highestColumn; $column++) {
        $arrKeys[] = $column;
        $arrVals[] = $counter++;
    }

    $arrPivot = array_combine($arrKeys, $arrVals);

    foreach ($mergeCells as $mergeCell) {
        $arrLett = array_slice(preg_split('#\d+:|\d+#', implode(':', $mergeCell)), 0, count($arrLett) - 1);
        foreach ($arrLett as $val) {
            foreach ($arrPivot as $key => $digit) {
                if ($val == $key) {
                    $arrCoord[] = $digit;
                }
            }
        }
    }

    foreach ($arrCoord as $key => $coord) {
        if ($key == ($key % 2 == 0) || $key == 0) {
            $colStart[] = $coord;
        } else {
            $colEnd[] = $coord;
        }
    }
    $arrLett = array_slice(preg_split('#\D+:|\D+#', implode(':', $mergeCell)), 1);

    foreach ($arrLett as $key => $coord) {
        if ($key == ($key % 2 == 0) || $key == 0) {
            $rowStart[] = $coord;
        } else {
            $rowEnd[] = $coord;
        }
    }

//        for ($i = 0; $i < count($arrCoord); $i++) {
//            $arrCoord[$i] = $arrCoord[$i] . ':' . $arrLett[$i];
//        }
//
//        $arrCoord = array_chunk($arrCoord, 2);
//
//        for ($i = 0; $i < count($arrCoord); $i++) {
//            $arrCoord[$i] = $arrCoord[$i][0] . ' -> ' . $arrCoord[$i][1];
//        }
//
//        echo '<pre>';
//        var_dump($arrCoord);
//        echo '</pre>';

    for ($row = 1; $row < $highestRow; ++$row) {
        $colCounter = 1;

        $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);

        for ($col = 0; $col < $highestColumnIndex - 1; ++$col) {
            $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();

            $counter++;

            if (isset($value)) {
                $colCounter = $col;
                $arr[$row][$col]['title'] = $value;
                $arr[$row][$col]['rowStart'] = $row;
                $arr[$row][$col]['rowEnd'] = $row;
                $arr[$row][$col]['colStart'] = $col + 1;
                $arr[$row][$col]['colEnd'] = $col + 1;
            } elseif (isset($arr[$row])) {
                $arr[$row][$colCounter]['colEnd'] = $col + 1;
            }

            if (isset($arr[$row][$col]['title'])) {
                $arr[$row][$col]['id'] = $counter;
            } elseif (!isset($arr[$row][$col]['title']) || !isset($arr[$row][$col]['title']) && isset($arr[$row - 1][$col - 1]['title'])) {
                $arr[$row][$col]['title'] = NULL;
                $arr[$row][$col]['rowStart'] = NULL;
                $arr[$row][$col]['rowEnd'] = NULL;
                $arr[$row][$col]['colStart'] = NULL;
                $arr[$row][$col]['colEnd'] = NULL;
            }

//                if (isset($arr[$row - 1][$col]['title']) && !isset($arr[$row][$col]['title'])) {
//                    $arr[$row - 1][$col]['rowEnd'] = $row;
//                } else {
//                    for ($i = 2; $i < $highestColumnIndex; $i++) {
//                        if (isset($arr[$row - $i][$col]['title']) && !isset($arr[$row][$col]['title'])) {
//                            $arr[$row - $i][$col]['rowEnd'] = $row;
//                        }
//                    }
//                }

//        $arrCell = $arr;
//        for ($row = 1; $row < $highestRow; $row++) {
//            for ($col = 1; $col <= $highestColumn; $col++) {
//                if (isset($arr[$row][$col]['title']) && !isset($arr[$row + 1][$col]['title'])) {
//                    for ($k = 1; $k <= $col; $k++) {
//                        if (isset($arr[$row + 1][$col - $k]['title'])) {
//                            if ($arr[$row][$col]['colStart'] < $arr[$row + 1][$col - $k]['colEnd'] && $arr[$row][$col]['colStart'] > $arr[$row + 1][$col - $k]['colStart']) {
//                                $arrCell[$row][$col]['rowEnd'] = $arrCell[$row][$col]['rowStart'];
//                            }
//                        }
//                    }
////                } elseif (isset($arr[$row][$col]['title']) && !isset($arr[$row][$col - 1]['title'])) {
////                    for ($k = 1; $k <= $col; $k++) {
////                        for ($l = 1; $l <= $col; $l++) {
////                            if (isset($arr[$row - $k][$col + $l]['title'])) {
////                                if ($arr[$row][$col]['colEnd'] > $arr[$row - $k][$col]['colStart']) {
////                                    $arrCell[$row][$col]['colEnd'];
////                                }
////                            }
////                        }
////                    }
//                }
//            }
//        }
        }
    }
    echo '<table border="1">' . PHP_EOL;
    for ($i = 1; $i < $highestRow; $i++) {
        echo '<tr>' . PHP_EOL;
        for ($k = 0; $k < $highestColumnIndex - 1; $k++) {
            echo '<td>' . $arr[$i][$k]['title']
                . '<br/>' . 'row' . ':' . $arr[$i][$k]['rowStart'] . ':'
                . $arr[$i][$k]['rowEnd'] . '<br/>' . 'col' . ':'
                . $arr[$i][$k]['colStart'] . ':'
                . $arr[$i][$k]['colEnd'] . '</td>' . PHP_EOL;
        }
        echo '</tr>' . PHP_EOL;
    }
    echo '</table>' . PHP_EOL;
}
}



namespace App\Http\Controllers;

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use function Symfony\Component\String\b;

class NewController extends Controller {
    public function import() {

        $excel = PHPExcel_IOFactory::load(base_path() . '\Examples\test5.xls');
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
        $arrEnd = [];

//                echo '<pre>';
//        var_dump($mergeCell);
//        echo '</pre>';

        for ($column = 'A'; $column != $highestColumn; $column++) {
            $arrKeys[] = $column;
            $arrVals[] = $counter++;
        }

        $arrPivot = array_combine($arrKeys, $arrVals);

        foreach ($mergeCells as $mergeCell) {
            $arrLett = array_slice(preg_split('#\d+:|\d+#', implode(':', $mergeCell)), 0, count($arrLett) - 1);
            foreach ($arrLett as $val) {
                foreach ($arrPivot as $key => $digit) {
                    if ($val == $key) {
                        $arrCoord[] = $digit;
                    }
                }
            }
        }

        foreach ($arrCoord as $key => $coord) {
            if ($key == ($key % 2 == 0) || $key == 0) {
                $colStart[] = $coord;
            } else {
                $colEnd[] = $coord;
            }
        }
        $arrLett = array_slice(preg_split('#\D+:|\D+#', implode(':', $mergeCell)), 1);

        foreach ($arrLett as $key => $coord) {
            if ($key == ($key % 2 == 0) || $key == 0) {
                $rowStart[] = $coord;
            } else {
                $rowEnd[] = $coord;
            }
        }

        for ($i = 0; $i < count($arrCoord); $i++) {
            $arrCoord[$i] = $arrCoord[$i] . ':' . $arrLett[$i];
        }

        $arrCoord = array_chunk($arrCoord, 2);

        for ($i = 0; $i < count($arrCoord); $i++) {
            $arrStart[$i] = $arrCoord[$i][0];
            $arrEnd[$i] = $arrCoord[$i][1];
        }

        $arrCoord = array_combine($arrStart, $arrEnd);

        echo '<pre>';
        var_dump($arrCoord);
        echo '</pre>';

//
//        $result = [];
//
//        foreach ($arrCells as $arrCell) {
//            foreach ($arrCell as $key => $item) {
//                echo $item;
//            }
//        }
//
//        echo '<pre>';
//        var_dump($result);
//        echo '</pre>';


//        foreach ($arrCoord as $key => $coord) {
//            if ($key == ($key % 2 == 0) || $key == 0) {
//                $colStart[] = $coord;
//            } else {
//                $colEnd[] = $coord;
//            }
//        }
//
//        array_unshift($colStart, '');
//        array_unshift($colEnd, '');
//        $arrLett = array_slice(preg_split('#\D+:|\D+#', implode(':', $mergeCell)), 1);
//
//        foreach ($arrLett as $key => $coord) {
//            if ($key == ($key % 2 == 0) || $key == 0) {
//                $rowStart[] = $coord;
//            } else {
//                $rowEnd[] = $coord;
//            }
//        }
//        array_unshift($rowStart, '');
//        array_unshift($rowEnd, '');
//
//        for ($i = 1; $i < $arrCoord; $i++) {
//            $arrCell[] = $colStart[$i] . ' ' . $rowStart[$i];
//        }


//        for ($i = 0; $i < count($arrCoord); $i++) {
//            $arrCoord[$i] = $arrCoord[$i] . ':' . $arrLett[$i];
//        }
//
//        $arrCoord = array_chunk($arrCoord, 2);
//
//        for ($i = 0; $i < count($arrCoord); $i++) {
//            $arrCoord[$i] = $arrCoord[$i][0] . ' -> ' . $arrCoord[$i][1];
//        }


        for ($row = 1; $row < $highestRow; ++$row) {
            $colCounter = 1;

            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

            for ($col = 0; $col < $highestColumnIndex - 1; ++$col) {
                $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();

                $counter++;

                if (isset($value)) {
                    $colCounter = $col;
                    $arr[$row][$col]['title'] = $value;
                    $arr[$row][$col]['rowStart'] = $row;
                    $arr[$row][$col]['rowEnd'] = $row;
                    $arr[$row][$col]['colStart'] = $col + 1;
                    $arr[$row][$col]['colEnd'] = $col + 1;
                } elseif (isset($arr[$row])) {
                    $arr[$row][$colCounter]['colEnd'] = $col + 1;
                }

                if (isset($arr[$row][$col]['title'])) {
                    $arr[$row][$col]['id'] = $counter;

                } elseif (!isset($arr[$row][$col]['title']) || !isset($arr[$row][$col]['title']) && isset($arr[$row - 1][$col - 1]['title'])) {
                    $arr[$row][$col]['title'] = NULL;
                    $arr[$row][$col]['rowStart'] = NULL;
                    $arr[$row][$col]['rowEnd'] = NULL;
                    $arr[$row][$col]['colStart'] = NULL;
                    $arr[$row][$col]['colEnd'] = NULL;
                }
            }
        }
//        $cellCoord = $arr;

//        echo '<pre>';
//        var_dump($cellCoord);
//        echo '</pre>';

//        for ($k = 0; $k < $highestColumnIndex - 1; $k++) {
//            echo $cellCoord[$k];
//            echo '<pre>';
//            var_dump($arr[$k]);
//            echo '</pre>';
//        }
//        echo '<pre>';
//        var_dump($arr11);
//        echo '</pre>';


//        echo '<table border="1">' . PHP_EOL;
//        for ($i = 1; $i < $highestRow; $i++) {
//            echo '<tr>' . PHP_EOL;
//            for ($k = 0; $k < $highestColumnIndex - 1; $k++) {
//                echo '<td>' . $arr[$i][$k]['title']
//                    . '<br/>' . 'row' . ':' . $arr[$i][$k]['rowStart'] . ':'
//                    . $arr[$i][$k]['rowEnd'] . '<br/>' . 'col' . ':'
//                    . $arr[$i][$k]['colStart'] . ':'
//                    . $arr[$i][$k]['colEnd'] . '</td>' . PHP_EOL;
//            }
//            echo '</tr>' . PHP_EOL;
//        }
//        echo '</table>' . PHP_EOL;
    }
}


namespace App\Http\Controllers;

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use function Symfony\Component\String\b;

class NewController extends Controller {
    public function import() {

        $excel = PHPExcel_IOFactory::load(base_path() . '\Examples\test5.xls');
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
        $arrEnd = [];

//                echo '<pre>';
//        var_dump($mergeCell);
//        echo '</pre>';

        for ($column = 'A'; $column != $highestColumn; $column++) {
            $arrKeys[] = $column;
            $arrVals[] = $counter++;
        }

        $arrPivot = array_combine($arrKeys, $arrVals);

        foreach ($mergeCells as $mergeCell) {
            $arrLett = array_slice(preg_split('#\d+:|\d+#', implode(':', $mergeCell)), 0, count($arrLett) - 1);
            foreach ($arrLett as $val) {
                foreach ($arrPivot as $key => $digit) {
                    if ($val == $key) {
                        $arrCoord[] = $digit;
                    }
                }
            }
        }

        foreach ($arrCoord as $key => $coord) {
            if ($key == ($key % 2 == 0) || $key == 0) {
                $colStart[] = $coord;
            } else {
                $colEnd[] = $coord;
            }
        }
        $arrLett = array_slice(preg_split('#\D+:|\D+#', implode(':', $mergeCell)), 1);

        foreach ($arrLett as $key => $coord) {
            if ($key == ($key % 2 == 0) || $key == 0) {
                $rowStart[] = $coord;
            } else {
                $rowEnd[] = $coord;
            }
        }

        for ($i = 0; $i < count($arrCoord); $i++) {
            $arrCoord[$i] = $arrCoord[$i] . ':' . $arrLett[$i];
        }

        $arrCoord = array_chunk($arrCoord, 2);

        for ($i = 0; $i < count($arrCoord); $i++) {
            $arrStart[$i] = $arrCoord[$i][0];
            $arrEnd[$i] = $arrCoord[$i][1];
        }

        $arrCoord = array_combine($arrStart, $arrEnd);

        echo '<pre>';
        var_dump($arrCoord);
        echo '</pre>';

//
//        $result = [];
//
//        foreach ($arrCells as $arrCell) {
//            foreach ($arrCell as $key => $item) {
//                echo $item;
//            }
//        }
//
//        echo '<pre>';
//        var_dump($result);
//        echo '</pre>';


//        foreach ($arrCoord as $key => $coord) {
//            if ($key == ($key % 2 == 0) || $key == 0) {
//                $colStart[] = $coord;
//            } else {
//                $colEnd[] = $coord;
//            }
//        }
//
//        array_unshift($colStart, '');
//        array_unshift($colEnd, '');
//        $arrLett = array_slice(preg_split('#\D+:|\D+#', implode(':', $mergeCell)), 1);
//
//        foreach ($arrLett as $key => $coord) {
//            if ($key == ($key % 2 == 0) || $key == 0) {
//                $rowStart[] = $coord;
//            } else {
//                $rowEnd[] = $coord;
//            }
//        }
//        array_unshift($rowStart, '');
//        array_unshift($rowEnd, '');
//
//        for ($i = 1; $i < $arrCoord; $i++) {
//            $arrCell[] = $colStart[$i] . ' ' . $rowStart[$i];
//        }


//        for ($i = 0; $i < count($arrCoord); $i++) {
//            $arrCoord[$i] = $arrCoord[$i] . ':' . $arrLett[$i];
//        }
//
//        $arrCoord = array_chunk($arrCoord, 2);
//
//        for ($i = 0; $i < count($arrCoord); $i++) {
//            $arrCoord[$i] = $arrCoord[$i][0] . ' -> ' . $arrCoord[$i][1];
//        }


        for ($row = 1; $row < $highestRow; ++$row) {
            $colCounter = 1;

            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

            for ($col = 0; $col < $highestColumnIndex - 1; ++$col) {
                $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();

                $counter++;

                if (isset($value)) {
                    $colCounter = $col;
                    $arr[$row][$col]['title'] = $value;
                    $arr[$row][$col]['rowStart'] = $row;
                    $arr[$row][$col]['rowEnd'] = $row;
                    $arr[$row][$col]['colStart'] = $col + 1;
                    $arr[$row][$col]['colEnd'] = $col + 1;
                } elseif (isset($arr[$row])) {
                    $arr[$row][$colCounter]['colEnd'] = $col + 1;
                }

                if (isset($arr[$row][$col]['title'])) {
                    $arr[$row][$col]['id'] = $counter;

                } elseif (!isset($arr[$row][$col]['title']) || !isset($arr[$row][$col]['title']) && isset($arr[$row - 1][$col - 1]['title'])) {
                    $arr[$row][$col]['title'] = NULL;
                    $arr[$row][$col]['rowStart'] = NULL;
                    $arr[$row][$col]['rowEnd'] = NULL;
                    $arr[$row][$col]['colStart'] = NULL;
                    $arr[$row][$col]['colEnd'] = NULL;
                }
            }
        }
//        $cellCoord = $arr;

//        echo '<pre>';
//        var_dump($cellCoord);
//        echo '</pre>';

//        for ($k = 0; $k < $highestColumnIndex - 1; $k++) {
//            echo $cellCoord[$k];
//            echo '<pre>';
//            var_dump($arr[$k]);
//            echo '</pre>';
//        }
//        echo '<pre>';
//        var_dump($arr11);
//        echo '</pre>';


//        echo '<table border="1">' . PHP_EOL;
//        for ($i = 1; $i < $highestRow; $i++) {
//            echo '<tr>' . PHP_EOL;
//            for ($k = 0; $k < $highestColumnIndex - 1; $k++) {
//                echo '<td>' . $arr[$i][$k]['title']
//                    . '<br/>' . 'row' . ':' . $arr[$i][$k]['rowStart'] . ':'
//                    . $arr[$i][$k]['rowEnd'] . '<br/>' . 'col' . ':'
//                    . $arr[$i][$k]['colStart'] . ':'
//                    . $arr[$i][$k]['colEnd'] . '</td>' . PHP_EOL;
//            }
//            echo '</tr>' . PHP_EOL;
//        }
//        echo '</table>' . PHP_EOL;
    }
}






namespace App\Http\Controllers;

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use function Symfony\Component\String\b;

class NewController extends Controller {
    public function import() {

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

        $arrPivot = array_combine($arrKeys, $arrVals);

        foreach ($mergeCells as $mergeCell) {
            $arrLett = array_slice(preg_split('#\d+:|\d+#', implode(':', $mergeCell)), 0, count($arrLett) - 1);
            foreach ($arrLett as $val) {
                foreach ($arrPivot as $key => $digit) {
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

        for ($row = 0; $row < $highestRow; $row++) {
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

        for ($row = 0; $row < $highestRow; $row++) {

            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

            for ($col = 0; $col < $highestColumnIndex; $col++) {

                if (isset($arr[$row][$col]['title'])) {
                    $colStart = array_keys($arrCoord, $arr[$row][$col]['colStart'] . ':' . $arr[$row][$col]['rowStart']);
                    foreach ($colStart as $cs) {
                        $ce = explode(':', $cs);

                        $arrCell[$row][$col]['colEnd'] = $ce[0];
                        $arrCell[$row][$col]['rowEnd'] = $ce[1];
                    }
                } else {
                    $arrCell[$row][$col] = NULL;
                }
            }
        }

        echo '<table border="1">' . PHP_EOL;
        for ($i = 1; $i < $highestRow; $i++) {
            echo '<tr>' . PHP_EOL;
            for ($k = 0; $k < $highestColumnIndex - 1; $k++) {
                echo '<td>' . $arrCell[$i][$k]['title'] . '<br/>'
                    . 'col' . ':' . $arrCell[$i][$k]['colStart'] . ':'
                    . $arrCell[$i][$k]['colEnd'] . '<br />'
                    . 'row' . ':' . $arrCell[$i][$k]['rowStart'] . ':'
                    . $arrCell[$i][$k]['rowEnd'] . '<br/>'
                    . 'id' . ':' . $arrCell[$i][$k]['id']
                    . '</td>' . PHP_EOL;
            }
            echo '</tr>' . PHP_EOL;
        }
        echo '</table>' . PHP_EOL;
    }
}
