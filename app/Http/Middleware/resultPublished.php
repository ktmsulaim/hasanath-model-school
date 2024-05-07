<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Setting;
use App\Models\Applicant;
use Illuminate\Support\Facades\Cache;

class resultPublished
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

        $results = ($settings->results_starting_at ?? false) && Carbon::now()->between(($settings->results_starting_at ?? Carbon::today()->format('Y-m-d')), ($settings->results_ending_at ?? Carbon::today()->format('Y-m-d')));
        if ($results && Applicant::where(['status' => 1])->count() > 0) {
            return $next($request);
        }
        return redirect()->route('apply');
    }
}
