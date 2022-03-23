<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PHPExcel_Cell;
use PHPExcel_IOFactory;

class UserUploadController extends Controller {
    public function user_upload() {
        return view('user_upload', ['user_upload' => '"Данные успешно добавлены"']) . redirect()->action([jsonController::class, 'arrayToJson']);
    }
}
