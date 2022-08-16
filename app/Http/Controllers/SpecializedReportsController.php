<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SpecializedReportsController extends Controller {
   public function spec_reps($table_uuid) {
               if(empty(Auth::id())) {
            return redirect()->route('login');
         }
      $row_uuid = [];
      $json_vals = [];
      $department = Auth::user()->department;
      date_default_timezone_set('Europe/Moscow');
      $table = DB::table('tables')->where('table_uuid', $table_uuid)->where('status', 0)->get();
      $specialized_reports = DB::table('report_values')->where('table_uuid', 'd7011723-8363-4c80-ba88-7e06ddb6856e')->orWhere('table_uuid', '09fdb928-b36a-4c5b-8979-8c5e9a62fe63')->orWhere('table_uuid', '7cb61534-3de1-44c7-8869-092d69165a92')->orWhere('table_uuid', 'f337ab33-f5b8-4471-814d-fdcde751c9aa')->where('user_dep', $department)->get();
      $dep_name = DB::table('org_helper')->where('id', $department)->value('title');
      $date_reports = [];
      $json = $table[0]->json_val;
      $name = $table[0]->table_name;
      $arrCell = json_decode($json, true);
      $highest_column_index = $table[0]->highest_column_index;
      $highest_row = $table[0]->highest_row;
      $radio = $table[0]->radio;
      $read_only = $table[0]->read_only;
      $json_func = $table[0]->json_func;

      $pattern = '';
      $reg_arr = [
         'v_text' => '[A-Za-zА-Яа-яЁё\s,.:;-]+',
         'v_int' => '[\s\d]+',
         'v_float' => '^\d+(?:,\d{0,2})?$',
         'v_all' => '^[^\/*?<>|+%@#№!=~\'`$^&]+',
      ];
      foreach ($reg_arr as $key => $reg) {
         if ($radio == $key) {
            $pattern = $reg;
         }
      }

      $arrLastRowId = [];
      $arrLastRowKeys = [];
      $arrFirstRowKeys = [];
      for ($i = 1; $i < $highest_row; $i++) {
         for ($k = 0; $k < $highest_column_index; $k++) {
            if ($arrCell[$i][$k]['rowEndView'] == $highest_row - 2) {
               $arrFirstRowKeys[] = $arrCell[$i][$k]['colStartView'];
               if ($arrCell[$i][$k]['rowStartView'] < $arrCell[$i][$k]['rowEndView']) {
                  $arrLastRowId[] = $arrCell[$i][$k]['id'];
                  $arrLastRowKeys[] = $arrCell[$i][$k]['colStartView'];
               } else if ($arrCell[$i][$k]['colEndView'] - $arrCell[$i][$k]['colStartView'] == 0) {
                  $arrLastRowId[] = $arrCell[$i][$k]['id'];
                  $arrLastRowKeys[] = $arrCell[$i][$k]['colStartView'];
               } else {
                  $arrLastRowId[] = $arrCell[$i][$k]['id'];
                  $arrLastRowKeys[] = $arrCell[$i][$k]['colStartView'];
               }
            }
         }
      }
      $arrLR = array_combine($arrFirstRowKeys, $arrLastRowKeys);
      asort($arrLR);
      $addRowArr = json_encode($arrLR, JSON_UNESCAPED_UNICODE);
      foreach ($specialized_reports as $specialized_report) {
         if (empty($specialized_report->row_uuid)) {
            $row_uuid[$specialized_report->table_uuid][$specialized_report->user_dep][] = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
         } else {
            $row_uuid[$specialized_report->table_uuid][$specialized_report->user_dep][] = $specialized_report->row_uuid;
         }
         $json_vals[$specialized_report->table_uuid][$specialized_report->user_dep][] = $specialized_report->json_val;
      }
      $row_uuid = json_encode($row_uuid, JSON_UNESCAPED_UNICODE);
      $json_vals = json_encode($json_vals, JSON_UNESCAPED_UNICODE);
      return view('spec_rep', compact('json', 'json_vals', 'json_func', 'highest_row', 'highest_column_index', 'addRowArr', 'name', 'row_uuid', 'table_uuid', 'pattern', 'read_only', 'department', 'dep_name'));
   }

   public function spec_reps_upload(Request $request) {
               if(empty(Auth::id())) {
            return redirect()->route('login');
         }
      try {
         date_default_timezone_set('Europe/Moscow');
         $date = date('Y-m-d H:i:s');
         $info = $request->except(['_token']);
         list($name, $table_uuid, $row_uuid, $user_id, $department) = explode(' + ', $info['table_information']);
         unset($info['table_information']);
         $json = json_encode($info, JSON_UNESCAPED_UNICODE);
         DB::table('report_values')->insert(['table_name' => $name, 'table_uuid' => $table_uuid, 'row_uuid' => $row_uuid, 'user_id' => $user_id, 'user_dep' => $department, 'json_val' => $json, 'created_at' => $date]);
         return view('router', ['alert' => 'Запись успешно добавлена', 'route' => '/daily_reports']);
      } catch (QueryException $e) {
         echo 'Ошибка: ' . $e->getMessage();
      }
   }
}
