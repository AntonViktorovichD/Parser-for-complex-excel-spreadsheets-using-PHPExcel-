<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class JsonController extends Controller {
   public function arrayToJson() {
      try {
         $rv_ja = [];
         $user_names = [];
         $user_phones = [];
         $user_role = Auth::user()->roles->first()->id;
         $user_id = Auth::id();
         $arrs = DB::table('tables')->where('status', 0)->where('periodicity', '=', 0)->orderBy('id', 'desc')->paginate(20);
         foreach (DB::table('tables')->orderBy('id', 'desc')->pluck('user_id') as $user) {
            $user_names[] = DB::table('users')->orderBy('id', 'desc')->where('id', $user)->first('name')->name;
            $user_phones[] = DB::table('users')->orderBy('id', 'desc')->where('id', $user)->first('city_phone')->city_phone;
         }
         $arr = json_encode($arrs);
         $table_user = json_encode($user_names);
         $user_phones = json_encode($user_phones);
         $arr_rows = json_encode(DB::table('report_values')->get());
         return view('json', ['arr' => $arr, 'tableload' => '', 'arr_rows' => $arr_rows, 'user_id' => $user_id, 'user_role' => $user_role, 'table_user' => $table_user, 'pages' => $arrs, 'user_phones' => $user_phones]);
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
