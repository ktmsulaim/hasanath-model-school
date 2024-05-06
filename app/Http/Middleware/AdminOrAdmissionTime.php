<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminOrAdmissionTime
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$dates)
    {
        list($date1, $date2) = $dates;
        $date1 = Carbon::createFromFormat('Y-m-d', $date1);
        $date2 = Carbon::createFromFormat('Y-m-d', $date2)->addDay();

        if (Auth::check() || Carbon::now()->between($date1, $date2)) {
            return $next($request);
        }

        return redirect()->route('admission-ended');
    }
}