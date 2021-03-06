<?php

namespace App\Http\Middleware;

use Closure;

class CheckUser {

    public function handle($request, Closure $next) {
        if ($request->role == 'user') {
            return view('denied');
        } elseif (empty($request->role)) {
            redirect('denied');
        }
        return $next($request);
    }
}
