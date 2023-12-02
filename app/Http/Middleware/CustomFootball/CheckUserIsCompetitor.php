<?php

namespace App\Http\Middleware\CustomFootball;

use Closure;
use Illuminate\Http\Request;

class CheckUserIsCompetitor
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
        $abort = false;

        if(! in_array($request->user()->id, $request->route()->parameter('competition')->competitors()->get()->pluck('id')->toArray())) {
            $abort = true;
        }

        if($request->route()->parameter('competition')->user->id == $request->user()->id){
            $abort = false;
        }

        if($abort){
            abort(401);
        }

        return $next($request);
    }
}
