<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PHPExcel_Cell;
use PHPExcel_IOFactory;

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
            $checkboxes = explode(" , ", $request->input('checked'));

            $file = $request->file('userfile');
            $radio = $request->input('reg_func');
            $filename = $request->input('filename');
            if (in_array($file->extension(), ['xls', 'xlsx'])) {
               if ($file->getSize() < 5242880) {
                  if (preg_match("/^[а-я А-Я0-9,]+$/u", $filename)) {
                     $upload_folder = 'public/folder';
                     $newFileName = $date . mb_strimwidth($filename, 0, 32) . '.tmp';
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

            $sms = $request->input('sms');

            $periodicity = $request->input('periodicity');

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

            if (empty($created_at)) {
               $created_at = date('Y-m-d H:i:s');
            }

            $json = json_encode($arrCell, JSON_UNESCAPED_UNICODE);

            $checked = json_encode($checkboxes, JSON_UNESCAPED_UNICODE);

            // EXCEL FUNCTIONS //

            $arr = [];
            $arrK = [];
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn) - 1;
            $arrFin = array_combine(range(1, $highestColumnIndex), array_fill(1, $highestColumnIndex, NULL));
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

            $strLetters = implode($arrLetters);
            for ($i = 0; $i < strlen($strLetters); $i++) {
               foreach ($arrKeys as $key => $val) {
                  $strLetters = str_replace($key, $val, $strLetters);
               }
            }
            $arrLetters = explode('|', $strLetters);
            unset($arrLetters[count($arrLetters) - 1]);

            $arrSum = array_combine($arrK, $arrLetters);

            $filledArr = [];

            for ($i = 1; $i <= $highestColumnIndex; $i++) {
               if (isset($arrSum[$i]) && isset($arrMC[$i])) {
                  $filledArr[$i] = $arrSum[$i] . $arrMC[$i];
               } elseif (isset($arrSum[$i])) {
                  $filledArr[$i] = $arrSum[$i];
               } elseif (isset($arrMC[$i])) {
                  $filledArr[$i] = $arrMC[$i];
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
            foreach ($strMC as $val) {
               $str = explode(':', $val);
               $colspan = ($str[1] - $str[0]) + 1;
               if (array_key_exists($str[0], $filledArr)) {
                  $arrFin[$str[0]] = ' colspan ' . $colspan . ' | ' . $filledArr[$str[0]];
               } else {
                  $arrFin[$str[0]] = 'colspan ' . $colspan;
               }
               foreach (range(($str[0] + 1), $str[1]) as $el) {
                  unset($arrFin[$el]);
               }
            }

            for ($i = 1; $i <= count($arrFin); $i++) {
               if (isset($filledArr[$i]) && $arrFin[$i] == NULL) {
                  $arrFin[$i] = $filledArr[$i];
               }
            }

            $coords = [];
            for ($row = 1; $row < $highestRow; $row++) {
               for ($col = 0; $col < $highestColumnIndex; $col++) {
                  $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                  $coord = $worksheet->getCellByColumnAndRow($col, $row)->getCoordinate();
                  if (isset($value)) {
                     $coords[$coord] = $value;
                  }
               }
            }
            $merge_cells = $worksheet->getMergeCells();
            foreach ($merge_cells as $key => $merge_cell) {
               $merge_cells[$merge_cell] = preg_replace('#:\w+#', '', $merge_cell);;
            }

            foreach ($coords as $key => $coord) {
               foreach ($merge_cells as $k => $merge_arr) {
                  if ($key == $merge_arr) {
                     $merge_cells[$k] = $coord;
                     unset($coords[$key]);
                  }
               }
            }

            $func_coords = [];

            for ($row = $highestRow; $row <= $highestRow; $row++) {
               for ($col = 0; $col < $highestColumnIndex; $col++) {
                  $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                  $coord = $worksheet->getCellByColumnAndRow($col, $row)->getCoordinate();
                  if (isset($value)) {
                     $func_coords[$coord] = $value;
                  }
               }
            }

            $json_func_coords = json_encode($func_coords);

            $json_markup = json_encode(array_merge($coords, $merge_cells));

            $json_func = json_encode($arrFin);

            DB::insert('insert into tables (json_val, table_name, table_uuid, user_id, created_at, updated_at, highest_row, highest_column_index, departments, radio, read_only, comment, json_func, json_markup, func_coords, status, periodicity) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [$json, $filename, $table_uuid, $user_id, $created_at, $updated_at, $highestRow, $highestColumnIndex, $checked, $radio, 'disabled', $comment, $json_func, $json_markup, $json_func_coords, 0, $periodicity]);

            unlink($tmpPath);

            if ($sms == 'yes') {
               return redirect()->action([SmsController::class, 'send_sms'], ['table_uuid' => $table_uuid]);
            } else {
               return redirect()->action([MailController::class, 'send_mail'], ['table_uuid' => $table_uuid]);
            }
         } else {
            return view('upload', ['ulerror' => 'Проверьте правильность введенных данных и наличие таблицы для загрузки']);
         }
      } catch (QueryException $e) {
         echo 'Ошибка: ' . $e->getMessage();
      }
   }
}
