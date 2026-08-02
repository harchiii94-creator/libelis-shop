<?php

namespace App\Http\Controllers;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function (Request $request, Closure $next) {
            if (! Auth::guard('admin')->check() || ! Auth::guard('admin')->user()->is_admin) {
                return redirect()->route('admin.login');
            }

            return $next($request);
        });
    }
}
