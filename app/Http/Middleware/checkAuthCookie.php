<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;

class CheckAuthCookie
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->hasCookie('user_id')) {
            $cookie = $request->cookie('user_id');
            $user_id = decrypt($cookie);
            
            $user = User::find($user_id);
            $last_loggedin_at = Carbon::parse($user->loggedin_at);
            $now = Carbon::now();

            $differInDays = $now->diffInDays($last_loggedin_at);
            // dd($user->loggedin_at);
            if ($user && $differInDays < 31 && $user->loggedin_at != NULL) {
                Auth::guard("web")->loginUsingId($user_id);
                $user = Auth::guard("web")->user();
                $user->loggedin_at = Carbon::now();
                $user->save();
            }
            else {
                Auth::guard("web")->logout();
            }
        }

        return $next($request);
    }
}
