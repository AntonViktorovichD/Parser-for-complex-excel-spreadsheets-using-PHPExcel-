<?php

namespace App\Http\Controllers;

use App\Mail\Email;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MonthlyReportsController extends Controller {
    public function monthly_report($name, $year) {
                if(empty(Auth::id())) {
            return redirect()->route('login');
         }
       $table_uuid = $name;
        $user_id = Auth::id();
        $user_dep = Auth::user()->department;
        $table = DB::table('tables')->where('status', 0)->where('table_uuid', $name)->get();
        if (Auth::user()->roles->first()->id == 1 || Auth::user()->roles->first()->id == 4) {
            $departments = [];
            foreach (json_decode($table[0]->departments, true) as $department) {
                $departments[$department] = DB::table('org_helper')->where('id', $department)->value('title');
            }
        } else {
            $departments[$user_dep] = DB::table('org_helper')->where('id', $user_dep)->value('title');
        }
        return view('monthly_report', compact('table', 'departments', 'name', 'year', 'table_uuid'));
    }
    public function monthly_user_report($table_uuid, $year, $month, $department) {
                if(empty(Auth::id())) {
            return redirect()->route('login');
         }
       $user_role = Auth::user()->roles->first()->id;
        $user_dep = Auth::user()->department;
        $table = DB::table('tables')->where('status', 0)->where('table_uuid', $table_uuid)->get();
        $monthly_reports = DB::table('monthly_reports')->where('table_uuid', $table_uuid)->where('user_dep', $department)->where('month', $month)->where('year', $year)->get();
        $json = $table[0]->json_val;
        $name = $table[0]->table_name;
        $comment = $table[0]->comment;
        $arrCell = json_decode($json, true);
        $highest_column_index = $table[0]->highest_column_index;
        $highest_row = $table[0]->highest_row;
        $radio = $table[0]->radio;
        $read_only = $table[0]->read_only;
        $json_func = $table[0]->json_func;

        $pattern = '';
        $reg_arr = [
            'v_text' => '[A-Za-zА-Яа-яЁё\s,.:;-]+',
            'v_int' => '[\s\d]+',
            'v_float' => '^\d+(?:,\d{0,2})?$',
            'v_all' => '^[^\/*?<>|+%@#№!=~\'`$^&]+',
        ];
        foreach ($reg_arr as $key => $reg) {
            if ($radio == $key) {
                $pattern = $reg;
            }
        }

        $arrLastRowId = [];
        $arrLastRowKeys = [];
        $arrFirstRowKeys = [];
        for ($i = 1; $i < $highest_row; $i++) {
            for ($k = 0; $k < $highest_column_index; $k++) {
                if ($arrCell[$i][$k]['rowEndView'] == $highest_row - 2) {
                    $arrFirstRowKeys[] = $arrCell[$i][$k]['colStartView'];
                    if ($arrCell[$i][$k]['rowStartView'] < $arrCell[$i][$k]['rowEndView']) {
                        $arrLastRowId[] = $arrCell[$i][$k]['id'];
                        $arrLastRowKeys[] = $arrCell[$i][$k]['colStartView'];
                    } else if ($arrCell[$i][$k]['colEndView'] - $arrCell[$i][$k]['colStartView'] == 0) {
                        $arrLastRowId[] = $arrCell[$i][$k]['id'];
                        $arrLastRowKeys[] = $arrCell[$i][$k]['colStartView'];
                    } else {
                        $arrLastRowId[] = $arrCell[$i][$k]['id'];
                        $arrLastRowKeys[] = $arrCell[$i][$k]['colStartView'];
                    }
                }
            }
        }
        if (empty($row_uuid)) {
            $row_uuid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
        }
        $arrLR = array_combine($arrFirstRowKeys, $arrLastRowKeys);
        asort($arrLR);
        $addRowArr = json_encode($arrLR, JSON_UNESCAPED_UNICODE);
        if (count($monthly_reports) > 0) {
            $row_uuid = $monthly_reports[0]->row_uuid;
            $json_vals = $monthly_reports[0]->json_val;
            return view('monthly_user_report', compact('json', 'json_vals', 'json_func', 'highest_row', 'highest_column_index', 'addRowArr', 'name', 'row_uuid', 'table_uuid', 'pattern', 'read_only', 'department', 'year', 'month', 'user_role', 'comment'));
        } else {
            return view('monthly_user_report', compact('json', 'json_func', 'highest_row', 'highest_column_index', 'addRowArr', 'name', 'row_uuid', 'table_uuid', 'pattern', 'read_only', 'department', 'year', 'month', 'user_role', 'comment'));
        }
    }
    public function monthly_upload(Request $request) {
                if(empty(Auth::id())) {
            return redirect()->route('login');
         }
        try {
            date_default_timezone_set('Europe/Moscow');
            $date = date('Y-m-d H:i:s');
            $info = $request->except(['_token']);
            list($name, $table_uuid, $row_uuid, $user_id, $department, $month, $year) = explode(' + ', $info['table_information']);
            unset($info['table_information']);
            $json = json_encode($info, JSON_UNESCAPED_UNICODE);
            DB::table('monthly_reports')->insert(['table_name' => $name, 'table_uuid' => $table_uuid, 'row_uuid' => $row_uuid, 'user_id' => $user_id, 'user_dep' => $department, 'json_val' => $json, 'month' => $month, 'created_at' => $date, 'year' => $year]);
            return view('router', ['alert' => 'Запись успешно добавлена', 'route' => '/monthly_reports']);
        } catch (QueryException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
    }

    public function monthly_update(Request $request) {
                if(empty(Auth::id())) {
            return redirect()->route('login');
         }
        try {
            date_default_timezone_set('Europe/Moscow');
            $date = date('Y-m-d H:i:s');
            $info = $request->except(['_token']);
            echo '<pre>';
            echo '</pre>';
            list($table_uuid, $row_uuid) = explode(' + ', $info['table_information']);
            unset($info['table_information']);
            $json = json_encode($info, JSON_UNESCAPED_UNICODE);
            DB::table('monthly_reports')->where('table_uuid', $table_uuid)->where('row_uuid', $row_uuid)->update(['json_val' => $json]);
            return view('router', ['alert' => 'Запись успешно добавлена', 'route' => '/monthly_reports']);
        } catch (QueryException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
    }

   public function clear(Request $request) {
      $rows_information = $request->input('rows_information');
      DB::table('monthly_reports')->where('row_uuid',)->truncate();
      $notification_rights = DB::table('notification_rights')->where('id', 1)->value('e_mail');
      if ($notification_rights != 0) {
         $department = DB::table('monthly_reports')->where('row_uuid', $rows_information)->value('user_dep');
         $table_name = DB::table('monthly_reports')->where('row_uuid', $rows_information)->value('table_name');
      }
      $notification = DB::table('user_noifications')->where('org_id', $department)->value('e_mail');
      $objDemo = new \stdClass();
      $objDemo->table_name = $table_name;
      if ($notification == 1) {
         $mail = DB::table('users')->where('department', $key)->value('email');
         if (isset($mail)) {
            Mail::to($mail)->send(new Email($objDemo));
         }
      }
      return redirect()->action([QuarterlyReportsController::class, 'quarterly_reports']);
   }

   public function accept(Request $request) {
      $read_only = DB::table('tables')->where('table_uuid', $request->input('table_information'))->value('read_only');
      if ($read_only === 'disabled') {
         DB::table('tables')->where('table_uuid', $request->input('table_information'))->upload('read_only', 'enabled');
      } else {
         DB::table('tables')->where('table_uuid', $request->input('table_information'))->upload('read_only', 'disabled');
      }
   }

   public function revalid(Request $request) {
      $rows_information = $request->input('rows_information');
      $notification_rights = DB::table('notification_rights')->where('id', 1)->value('e_mail');
      if ($notification_rights != 0) {
         $department = DB::table('monthly_reports')->where('row_uuid', $rows_information)->value('user_dep');
         $table_name = DB::table('monthly_reports')->where('row_uuid', $rows_information)->value('table_name');
      }
      $notification = DB::table('user_noifications')->where('org_id', $department)->value('e_mail');
      $objDemo = new \stdClass();
      $objDemo->table_name = $table_name;
      if ($notification == 1) {
         $mail = DB::table('users')->where('department', $key)->value('email');
         if (isset($mail)) {
            Mail::to($mail)->send(new Email($objDemo));
         }
      }
      return redirect()->action([QuarterlyReportsController::class, 'quarterly_reports']);
   }
}
