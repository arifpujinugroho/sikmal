<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Redirect;

class isPerekap
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
        if (auth()->check() && $request->user()->level == 'Perekap') {
            return $next($request);
        } elseif (auth()->check() && $request->user()->level != 'Perekap') {
            return redirect()->back()->with('login', 'notperekap');
        } else {
            Auth::logout();
            return Redirect::to('/')
                ->with('login', 'notperekap');
        }
    }
}
