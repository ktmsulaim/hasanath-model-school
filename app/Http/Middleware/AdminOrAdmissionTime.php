<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\Setting;

class AdminOrAdmissionTime
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
        $settings = Cache::rememberForever('settings', function () {
            return (object) Setting::pluck('value', 'name')->toArray();
        });

        $adm_date_start = $settings->starting_at ?? Carbon::today()->format('Y-m-d');
        $adm_date_end = $settings->ending_at ?? Carbon::today()->format('Y-m-d');


        $date1 = Carbon::createFromFormat('Y-m-d', $adm_date_start);
        $date2 = Carbon::createFromFormat('Y-m-d', $adm_date_end)->addDay();

        if (Auth::check() || Carbon::now()->between($date1, $date2)) {
            return $next($request);
        }

        return redirect()->route('admission-ended');
    }
}
