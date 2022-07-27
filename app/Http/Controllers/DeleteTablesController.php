<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class DeleteTablesController extends Controller {
    public function delete_tables() {
        $tables = DB::table('tables')->orderBy('id', 'desc')->paginate(20);
        return view('delete_tables', compact('tables'));
    }

    public function delete_table($name, $stat) {
        $stat ? $stat = 0 : $stat = 1;
            DB::table('tables')->where('table_uuid', $name)->update(['status' => $stat]);
        return redirect('delete_tables');
    }
}
