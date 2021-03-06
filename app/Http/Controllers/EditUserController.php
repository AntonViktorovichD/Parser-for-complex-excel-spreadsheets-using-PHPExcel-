<?php

namespace App\Http\Controllers;


use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;

class EditUserController extends Controller {
    public function editUser(Request $request) {
        try {
            DB::connection()->getPdo();
            $department = $request->input('department');
            $id = Auth::id();
            $user = DB::table('users')->where('id', '$id')->value('department');
            if (empty($department)) {
                $department = $user;
            }
            DB::table('users')->where('id', '$id')->update(['department' => $department]);
        } catch (QueryException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
    }
}
