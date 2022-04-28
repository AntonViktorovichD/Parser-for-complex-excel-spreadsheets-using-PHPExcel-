<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class showUserController extends Controller {
    public function showUser() {
        DB::connection()->getPdo();
        $id = Auth::user()->id;
        $user = DB::table('users')->where('id', '$id')->first();
        return view('show', compact('user'));

    }
}
