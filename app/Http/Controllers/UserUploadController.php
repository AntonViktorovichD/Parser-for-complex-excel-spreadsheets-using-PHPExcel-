<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserUploadController extends Controller {
    public function user_upload(Request $request) {
//        date_default_timezone_set('Europe/Moscow');
//        $date = date('Y_m_d_H_i_s_');
//        DB::connection()->getPdo();
        $input = $request->all();
        unset($input['_token']);
//        $hidden = $request->input('password');
//        echo $hidden;
        var_dump($input);
//        return view('user_upload');
    }

}
