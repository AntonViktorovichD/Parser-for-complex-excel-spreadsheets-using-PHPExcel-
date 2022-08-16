<?php

namespace App\Http\Controllers;


use App\Http\Requests;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EditUserController extends Controller {
   public function editUser(Request $request) {
      try {
         if (empty(Auth::id())) {
            return redirect()->route('login');
         }
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
