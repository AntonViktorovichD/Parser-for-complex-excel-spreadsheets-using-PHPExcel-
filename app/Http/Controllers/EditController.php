<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Ramsey\Uuid\Uuid;

class EditController extends Controller {
    public function edit($name) {
        $json = json_encode(DB::table('tables')->where('table_name', $name)->value('json_val'));
        $highest_column_index = DB::table('tables')->where('table_name', $name)->value('highest_column_index');
        $highest_row = DB::table('tables')->where('table_name', $name)->value('highest_row');
        $table_uuid = DB::table('tables')->where('table_name', $name)->value('table_uuid');
        $arrCell = json_decode(json_decode($json), true);
        $arrLastRowId = [];
        $arrLastRowKeys = [];
        for ($i = 1; $i < $highest_row; $i++) {
            for ($k = 0; $k < $highest_column_index; $k++) {
                if ($arrCell[$i][$k]['rowEndView'] == $highest_row - 2) {
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
        $row_uuid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));

        $arrLR = array_unique(array_combine($arrLastRowId, $arrLastRowKeys));
        asort($arrLR);
        $addRowArr = json_encode($arrLR, JSON_UNESCAPED_UNICODE);
        return view('edit', ['json' => $json, 'highest_row' => $highest_row, 'highest_column_index' => $highest_column_index, 'addRowArr' => $addRowArr, 'name' => $name, 'row_uuid' => $row_uuid, 'table_uuid' => $table_uuid]);
    }
}
