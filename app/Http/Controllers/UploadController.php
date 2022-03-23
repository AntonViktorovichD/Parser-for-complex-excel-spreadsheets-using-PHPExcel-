<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PHPExcel_Cell;
use PHPExcel_IOFactory;

class UploadController extends Controller {

    public function form() {
        return view('upload', ['ulerror' => '']);
    }

    public function upload(Request $request) {

        date_default_timezone_set('Europe/Moscow');
        $date = date('Y_m_d_H_i_s_');

        $arrCell = [];

        try {
            DB::connection()->getPdo();

            if ($request->isMethod('post') && $request->file('userfile')) {

                $names = DB::select('select table_name from tables');
                $namesArr = [];
                foreach ($names as $name) {
                    $namesArr[] = $name->table_name;
                }

                $file = $request->file('userfile');
                $filename = $request->input('filename');
                if (in_array($file->extension(), ['xls', 'xlsx'])) {
                    if ($file->getSize() < 5242880) {
                        if (preg_match("/^[а-я А-Я0-9]+$/u", $filename)) {
                            $upload_folder = 'public/folder';
                            $newFileName = $date . $filename . '.tmp';
                            Storage::putFileAs($upload_folder, $file, $newFileName);
                        } else {
                            sleep(0);
                            return view('upload', ['ulerror' => 'Введите название файла']);
                        }
                    } else {
                        sleep(0);
                        return view('upload', ['ulerror' => 'Таблица превышает размер 5мб']);
                    }
                } else {
                    sleep(0);
                    return view('upload', ['ulerror' => 'Загрузите файл с расширением xls или xlsx']);
                }

                $tmpPath = base_path() . '/storage/app/public/folder' . '/' . $newFileName;


                $excel = PHPExcel_IOFactory::load($tmpPath);
                $worksheet = $excel->getActiveSheet();
                $mergeCells[] = $worksheet->getMergeCells();
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();
                $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
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

                for ($column = 'A';
                     $column != $highestColumn;
                     $column++) {
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

                if (!empty($arrCoord)) {

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
                                $arrCell[$row][$col]['colStartView'] = $arr[$row][$col]['colStart'];
                                $arrCell[$row][$col]['rowStartView'] = $arr[$row][$col]['rowStart'];
                                $arrCell[$row][$col]['colEndView'] = $arr[$row][$col]['colEnd'];
                                $arrCell[$row][$col]['rowEndView'] = $arr[$row][$col]['rowEnd'];
                            } else {
                                $arrCell[$row][$col] = NULL;
                            }
                        }
                    }
                }

                $date = date('Y:m:d H:i:s');

                $json = json_encode($arrCell, JSON_UNESCAPED_UNICODE);

                DB::insert('insert into tables (json_val, table_name, created_at, highest_row, highest_column_index) values (?, ?, ?, ?, ?)', [$json, $filename, $date, $highestRow, $highestColumnIndex]);

                unlink($tmpPath);

                return redirect()->action([jsonController::class, 'arrayToJson']);

            } else {
                return view('upload', ['ulerror' => 'Таблица пуста']);
            }
        } catch (\Exception $e) {
            die("Нет подключения к базе данных.");
        }
    }
}


