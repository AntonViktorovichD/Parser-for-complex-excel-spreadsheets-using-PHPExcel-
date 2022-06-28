<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;


class JsonController extends Controller {
    public function arrayToJson() {
        try {
            $rv_ja = [];
            $user_names = [];
            $user_role = Auth::user()->roles->first()->id;
            $user_id = Auth::id();
            $arrs = DB::table('tables')->where('periodicity', '=', 0)->orderBy('id', 'desc')->paginate(20);
            foreach (DB::table('tables')->orderBy('id', 'desc')->pluck('user_id') as $user) {
                $user_names[] = DB::table('users')->orderBy('id', 'desc')->where('id', $user)->first('name')->name;
            }
            $arr = json_encode($arrs);
            $table_user = json_encode($user_names);
            $arr_rows = json_encode(DB::select('select * from report_values'));
            return view('json', ['arr' => $arr, 'tableload' => '', 'arr_rows' => $arr_rows, 'user_id' => $user_id, 'user_role' => 'user_role', 'table_user' => $table_user, 'pages' => $arrs]);
        } catch (QueryException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
    }

    public function tables($name) {
        $table = DB::table('tables')->where('table_name', $name)->get();
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

