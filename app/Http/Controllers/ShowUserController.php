<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class ShowUserController extends Controller {
    public function showUser() {
       if(empty(Auth::id())) {
          return redirect()->route('login');
       }
        DB::connection()->getPdo();
        $id = Auth::user()->id;
        $user = DB::table('users')->where('id', '$id')->first();
        return view('show', compact('user'));

    }
}
