<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller {

   use AuthenticatesUsers;

   protected $redirectTo = '/home';
   protected $loginType;

   public function __construct() {
      $this->middleware('guest')->except('logout');
      $this->loginType = $this->checkLoginInput();
   }


   public function login(Request $request) {
      $this->validate($request, [
         'login' => 'required|string',
         'password' => 'required|string'
      ]);

      $credentials = [
         $this->loginType => $request->login,
         'password' => $request->password
      ];


      if (Auth::attempt($credentials)) {
         return redirect()->intended($this->redirectTo);
      }

      return redirect()->back()
         ->withInput()
         ->withErrors([
            'login' => 'These credentials don\'t match our records.'
         ]);
   }

   protected function checkLoginInput() {
      $inputData = request()->get('login');

      return filter_var($inputData, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
   }

}
