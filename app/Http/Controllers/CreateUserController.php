<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class CreateUserController extends Controller {
    public function addUser(Request $request) {
        try {
            DB::connection()->getPdo();
            $name = $request->input('name');
            $email = $request->input('email');
            $department = $request->input('department');
            $password = password_hash($request->input('password'), PASSWORD_BCRYPT);
            DB::insert('insert into users (name, email, department, password) values (?, ?, ?, ?)', [$name, $email, $department, $password]);
        } catch (\Exception $e) {
            die("Нет подключения к базе данных.");
        }
    }
}
