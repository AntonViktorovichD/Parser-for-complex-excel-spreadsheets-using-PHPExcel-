<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PHPExcel_Cell;
use PHPExcel_IOFactory;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class UploadController extends Controller {

    public function form() {
        if (Auth::user()->getRoleNames()[0] != 'user') {
            return view('upload', ['ulerror' => '']);
        } else {
            return view('denied');
        }
    }

    public function upload(Request $request) {

        date_default_timezone_set('Europe/Moscow');
        $date = date('Y_m_d_H_i_s_');

        $arrCell = [];

        $checkboxes = [];

        $user_id = Auth::id();

        try {
            DB::connection()->getPdo();

            if ($request->isMethod('post') && $request->file('userfile') && $request->input('reg_func')) {

                $names = DB::select('select table_name from tables');
                $namesArr = [];
                foreach ($names as $name) {
                    $namesArr[] = $name->table_name;
                }
                $check = \Request::get("org");
                foreach ($check as $checked) {
                    $checkboxes[] = $checked;
                }
                $file = $request->file('userfile');
                $radio = $request->input('reg_func');
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

                $table_uuid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));

                $created_at = $request->input('created_at');
                $updated_at = $request->input('updated_at');
                $comment = $request->input('comment');

                $json = json_encode($arrCell, JSON_UNESCAPED_UNICODE);

                $checked = json_encode($checkboxes, JSON_UNESCAPED_UNICODE);

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
                $strLetters = implode($arrLetters);

                for ($i = 0; $i < strlen($strLetters); $i++) {
                    foreach ($arrKeys as $key => $val) {
                        $strLetters = str_replace($key, $val, $strLetters);
                    }
                }

                $arrLetters = explode('|', $strLetters);
                unset($arrLetters[count($arrLetters) - 1]);
                $arrSum = array_combine($arrK, $arrLetters);
                $json_sum = json_encode($arrSum);
                $json_func = json_encode($arr, JSON_UNESCAPED_UNICODE);

                DB::insert('insert into tables (json_val, table_name, table_uuid, user_id, created_at, updated_at, highest_row, highest_column_index, departments, radio, read_only, comment, json_func, json_sum) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [$json, $filename, $table_uuid, $user_id, $created_at, $updated_at, $highestRow, $highestColumnIndex, $checked, $radio, 'disabled', $comment, $json_func, $json_sum]);

                unlink($tmpPath);

                return redirect()->action([JsonController::class, 'arrayToJson']);

            } else {
                return view('upload', ['ulerror' => 'Проверьте правильность введенных данных и наличие таблицы для загрузки']);
            }
        } catch (\Exception $e) {
            die("Нет подключения к базе данных.");
        }
    }
}
