<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Redirect;

class isKemahasiswaan
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
        if (auth()->check() && $request->user()->level == 'Kemahasiswaan') {
            return $next($request);
        } elseif (auth()->check() && $request->user()->level != 'Kemahasiswaan') {
            return redirect()->back()->with('login', 'notkemahasiswaan');
        } else {
            Auth::logout();
            return Redirect::to('/')
                ->with('login', 'notkemahasiswaan');
        }
    }
}
