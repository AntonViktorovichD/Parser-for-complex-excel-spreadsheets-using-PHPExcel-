<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Mail\Email;
use App\Models\report_value;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class EditController extends Controller {
   public function edit($table_uuid) {
      if (empty(Auth::id())) {
         return redirect()->route('login');
      }
      try {
         $table = DB::table('tables')->where('table_uuid', $table_uuid)->where('status', 0)->get();
         $json = $table[0]->json_val;
         $name = $table[0]->table_name;
         $highest_column_index = $table[0]->highest_column_index;
         $highest_row = $table[0]->highest_row;
         $radio = $table[0]->radio;
         $read_only = $table[0]->read_only;
         $json_func = $table[0]->json_func;
         $user_role = Auth::user()->roles->first()->id;
         if ($user_role == 1 || $user_role == 4) {
            $report_values = DB::table('report_values')->where('table_uuid', $table_uuid)->get();
            $row_uuid = [];
            $user_dep = [];
            $dep = [];
            foreach ($report_values as $key => $report_value) {
               $row_uuid[$key] = $report_value->row_uuid;
               $user_id[$key] = $report_value->user_id;
               $user_dep[$key] = DB::table('users')->where('id', $user_id[$key])->value('department');
               $dep[$key] = DB::table('org_helper')->where('id', $user_dep[$key])->value('title');
               $json_vals = $report_values[0]->json_val;
            }
         } else {
            $report_values = DB::table('report_values')->where('table_uuid', $table_uuid)->where('user_dep', Auth::user()->department)->get();
            $row_uuid = $report_values[0]->row_uuid;
            $user_id = Auth::user()->id;
            $user_dep = DB::table('users')->where('id', $user_id)->value('department');
            $dep = DB::table('org_helper')->where('id', Auth::user()->department)->value('title');
            $json_vals = $report_values[0]->json_val;
         }

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
         $arrCell = json_decode($json, true);
         $arrLastRowId = [];
         $arrLastRowKeys = [];
         $rep_value = [];
         $rep_key = [];
         $report_value = [];

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
         $arrLR = array_combine($arrFirstRowKeys, $arrLastRowKeys);
         asort($arrLR);
         $addRowArr = json_encode($arrLR, JSON_UNESCAPED_UNICODE);
         if (Auth::user()->roles->first()->id == 1 || Auth::user()->roles->first()->id == 4) {
            $report_values = json_decode(DB::table('report_values')->where('table_uuid', $table_uuid)->get(), true);
            if (count($report_values) > 0) {
               foreach ($report_values as $i => $value) {
                  $val = json_decode($report_values[$i]['json_val'], true);
                  $key = explode('+', $report_values[$i]['row_uuid'] . '+');
                  unset($key[1]);
                  foreach ($key as $k => $item) {
                     $rep_key[] = $key[$k];
                  }
                  $rep_value[] = $val;
               }
               $report_value = (json_encode(array_combine($rep_key, $rep_value)));

               return view('admin_edit', compact('json', 'highest_row', 'highest_column_index', 'addRowArr', 'name', 'table_uuid', 'user_id', 'row_uuid', 'report_value', 'user_dep', 'pattern', 'json_func', 'json_vals', 'dep'));
            } else {
               return view('admin_view', compact('json', 'highest_row', 'highest_column_index', 'addRowArr', 'name', 'table_uuid'));
            }
         } else {
            $report_value = json_encode(DB::table('report_values')->where('table_uuid', $table_uuid)->where('user_id', $user_id)->value('json_val'));
            return view('edit', compact('json', 'highest_row', 'highest_column_index', 'addRowArr', 'name', 'table_uuid', 'row_uuid', 'user_id', 'report_value', 'user_dep', 'pattern', 'read_only', 'json_func', 'json_vals', 'dep'));
         }
      } catch
      (QueryException $e) {
         echo 'Ошибка: ' . $e->getMessage();
      }
   }

   public function clear(Request $request) {
      $rows_information = explode(',', $request->input('rows_information'));
      foreach ($rows_information as $truncated) {
         DB::table('report_values')->where('row_uuid', $truncated)->truncate();
      }
      $notification_rights = DB::table('notification_rights')->where('id', 1)->get()[0];
      $notifications = [];
      $departments = [];
      if ($notification_rights->e_mail != 0) {
         foreach ($rows_information as $row) {
            $departments[] = DB::table('report_values')->where('row_uuid', $row)->value('user_dep');
         }
         $table_name = DB::table('report_values')->where('row_uuid', $rows_information[0])->value('table_name');
      }
      foreach ($departments as $department) {
         $notification = DB::table('user_noifications')->where('org_id', $department)->get()[0];
         $notifications[$department]['e_mail'] = $notification->e_mail;
      }
      $objDemo = new \stdClass();
      $objDemo->table_name = $table_name;
      foreach ($notifications as $key => $notification) {
         if ($notification['e_mail'] == 1) {
            $mail = DB::table('users')->where('department', $key)->value('email');
            if (isset($mail)) {
               Mail::to($mail)->send(new Email($objDemo));
            }
         }
      }
      return redirect()->action([JsonController::class, 'arrayToJson']);
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
      $rows_information = explode(',', $request->input('rows_information'));
      $notification_rights = DB::table('notification_rights')->where('id', 1)->get()[0];
      $notifications = [];
      $departments = [];
      if ($notification_rights->e_mail != 0) {
         foreach ($rows_information as $row) {
           $departments[] = DB::table('report_values')->where('row_uuid', $row)->value('user_dep');
         }
         $table_name = DB::table('report_values')->where('row_uuid', $rows_information[0])->value('table_name');
      }
      foreach ($departments as $department) {
         $notification = DB::table('user_noifications')->where('org_id', $department)->get()[0];
         $notifications[$department]['e_mail'] = $notification->e_mail;
      }
      $objDemo = new \stdClass();
      $objDemo->table_name = $table_name;
      foreach ($notifications as $key => $notification) {
         if ($notification['e_mail'] == 1) {
            $mail = DB::table('users')->where('department', $key)->value('email');
            if (isset($mail)) {
               Mail::to($mail)->send(new Email($objDemo));
            }
         }
      }
      return redirect()->action([JsonController::class, 'arrayToJson']);
   }
}
