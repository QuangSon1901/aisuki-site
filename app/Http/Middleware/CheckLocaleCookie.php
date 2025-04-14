<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\App;

class CheckLocaleCookie
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // If the URL already has a locale, proceed
        if ($request->segment(1) && in_array($request->segment(1), array_keys(get_languages()->keyBy('code')->toArray()))) {
            return $next($request);
        }
        
        // If there's a locale cookie, redirect to that locale
        $localeCookie = $request->cookie('locale');
        if ($localeCookie && in_array($localeCookie, array_keys(get_languages()->keyBy('code')->toArray()))) {
            return redirect($localeCookie . $request->getRequestUri());
        }
        
        // Otherwise, redirect to default locale
        $defaultLocale = default_language()->code;
        return redirect($defaultLocale . $request->getRequestUri());
    }
}