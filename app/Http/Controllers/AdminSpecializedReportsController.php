<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Http\Request;

class AdminSpecializedReportsController extends Controller {
   public function admin_spec_reps_view($table_uuid) {
      try {
         $table = DB::table('tables')->where('table_uuid', $table_uuid)->get();
         $json = $table[0]->json_val;
         $name = $table[0]->table_name;
         $highest_column_index = $table[0]->highest_column_index;
         $highest_row = $table[0]->highest_row;
         $radio = $table[0]->radio;
         $read_only = $table[0]->read_only;
         $json_func = $table[0]->json_func;
         $daily_reports = DB::table('daily_reports')->where('table_uuid', $table_uuid)->get();
         if (count($daily_reports) > 0) {
            $row_uuid = $daily_reports[0]->row_uuid;
            $user_id = $daily_reports[0]->user_id;
            $user_dep = DB::table('users')->where('id', $user_id)->value('department');
            $dep = DB::table('org_helper')->where('id', $user_dep)->value('title');
            $json_vals = $daily_reports[0]->json_val;
         }
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
         $arrCell = json_decode($json, true);
         $arrLastRowId = [];
         $arrLastRowKeys = [];
         $rep_value = [];
         $rep_key = [];
         $daily_report = [];

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
         $daily_reports = json_decode(DB::table('daily_reports')->where('table_uuid', $table_uuid)->get(), true);
         if (count($daily_reports) > 0) {
            foreach ($daily_reports as $i => $value) {
               $val = json_decode($daily_reports[$i]['json_val'], true);
               $key = explode('+', $daily_reports[$i]['row_uuid'] . '+');
               unset($key[1]);
               foreach ($key as $k => $item) {
                  $rep_key[] = $key[$k];
               }
               $rep_value[] = $val;
            }
            $daily_report = (json_encode(array_combine($rep_key, $rep_value)));
            return view('admin_spec_reps', compact('json', 'highest_row', 'highest_column_index', 'addRowArr', 'name', 'table_uuid', 'user_id', 'row_uuid', 'daily_report', 'user_dep', 'pattern', 'json_func', 'json_vals', 'dep'));
         } else {
            return view('admin_spec_reps_view', compact('json', 'highest_row', 'highest_column_index', 'addRowArr', 'name', 'table_uuid'));
         }
      } catch
      (QueryException $e) {
         echo 'Ошибка: ' . $e->getMessage();
      }
   }
}
