<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class L10n
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
        app()->setLocale($request->user('sanctum') ? ($request->user('sanctum')->locale->code ?? 'en') : ($request->hasHeader('X-L10n') ? $request->header('X-L10n') : 'en'));

        return $next($request);
    }
}
