<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class PhonesController extends Controller {
   public function phones() {
      $user = [];
      $elev_users = DB::table('model_has_roles')->where('role_id', 2)->paginate(20);
      foreach ($elev_users as $key => $elev_user) {
         $users[] = json_decode(DB::table('users')->where('id', $elev_user->model_id)->get(), true);
      }
      return view('phones', ['users' => $users, 'elev_users' => $elev_users]);
   }
}
