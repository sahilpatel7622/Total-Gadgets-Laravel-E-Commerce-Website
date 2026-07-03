<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {

            $user = User::find(Auth::id());

            if (!$user) {
                Auth::logout();
                return redirect('/login')->with('error', 'Your account has been deleted.');
            }

            if ($user->status == 'Inactive') {
                Auth::logout();
                return redirect('/login')->with('error', 'Your account has been deactivated.');
            }
        }

        return $next($request);
    }
}