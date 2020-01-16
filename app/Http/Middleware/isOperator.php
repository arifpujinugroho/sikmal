<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Redirect;

class isOperator
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
        if (auth()->check() && $request->user()->level == 'Operator'){
            return $next($request);
        } elseif (auth()->check() && $request->user()->level != 'Operator'){
            return redirect()->back()->with('login','notoperator');            
        }else{
            Auth::logout();
            return Redirect::to('/')
            ->with('login', 'notoperator');
        }
    }
}
