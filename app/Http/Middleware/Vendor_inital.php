<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;

use App\Models\StoreLocations;
use App\Models\ReturnPolicy;

class Vendor_inital
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        $location = StoreLocations::where('user_id', $user->id)->get();
        $policy = ReturnPolicy::where('user_id', $user->id)->get();

        if($location->count() == 0) {
            return redirect()->route('vendor-location-create');
        }
        else if($policy->count() == 0) {
            return redirect()->route('vendor-return-policy-create');
        }
        else {
            return $next($request);
        }

    }
}
