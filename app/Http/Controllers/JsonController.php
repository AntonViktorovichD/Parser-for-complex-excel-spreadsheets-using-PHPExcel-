<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JsonController extends Controller {
   public function arrayToJson() {
      try {
         $user_role = Auth::user()->roles->first()->id;
         $rv_ja = [];
         $user_names = [];
         $user_phones = [];
         $user_id = Auth::id();
         $arrs = DB::table('tables')->where('status', 0)->where('periodicity', '=', 0)->orderBy('id', 'desc')->paginate(20);
         foreach (DB::table('tables')->orderBy('id', 'desc')->pluck('user_id') as $user) {
            $user_names[] = DB::table('users')->orderBy('id', 'desc')->where('id', $user)->value('name');
            $user_phones[] = DB::table('users')->orderBy('id', 'desc')->where('id', $user)->value('city_phone');
         }
         $user_dep = DB::table('users')->where('id', $user_id)->value('department');
         $arr_rows = DB::table('report_values')->get();

         $arr_values_count = [];
         foreach ($arrs as $arr) {
            $arr_values_count[$arr->table_uuid] = count(json_decode($arr->departments, true)) * $arr->highest_column_index;
         }
var_dump($arr_values_count);
         return view('json', ['arr' => $arrs, 'tableload' => '', 'arr_rows' => $arr_rows, 'user_id' => $user_id, 'user_role' => $user_role, 'table_user' => $user_names, 'pages' => $arrs, 'user_phones' => $user_phones, 'user_dep' => $user_dep, 'rv_ja' => $rv_ja]);
      } catch (QueryException $e) {
         echo 'Ошибка: ' . $e->getMessage();
      }
   }

   public function tables($table_uuid) {
      $table = DB::table('tables')->where('status', 0)->where('table_uuid', $table_uuid)->get();
      $json = $table[0]->json_val;
      $highest_column_index = $table[0]->highest_column_index;
      $highest_row = $table[0]->highest_row;
      $radio = $table[0]->radio;
      return view('table', compact('json', 'highest_row', 'highest_column_index', 'radio'));
   }

   public function handler(Request $request) {
      DB::table('tables')->where('table_uuid', '=', $request->target)->update(['read_only' => $request->changer]);
   }
}
