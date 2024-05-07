<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class AdmissionTimeMiddleware
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

        if (Carbon::now()->between($adm_date_start, $adm_date_end)) {
            return $next($request);
        }

        return redirect()->route('admission-ended');
    }
}
