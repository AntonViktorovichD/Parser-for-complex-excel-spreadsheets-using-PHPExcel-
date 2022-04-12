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
            date_default_timezone_set('Etc/GMT+3');
            $created_at = date('Y-m-d H:i:s');
            $name = $request->input('name');
            $email = $request->input('email');
            $department = $request->get('department');
            $password = password_hash($request->input('password'), PASSWORD_BCRYPT);
            DB::insert('insert into users (name, email, department, password, created_at) values (?, ?, ?, ?, ?)', [$name, $email, $department, $password, $created_at]);
        } catch (\Exception $e) {
            die("Нет подключения к базе данных.");
        }
    }
}

