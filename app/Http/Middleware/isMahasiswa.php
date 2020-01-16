<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Redirect;

class isMahasiswa
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
        if (auth()->check() && ($request->user()->level == 'Mahasiswa' || $request->user()->level == 'Perekap') && $request->user()->email_verified_at != '') {
            return $next($request);
        } elseif (auth()->check() && $request->user()->level != 'Mahasiswa') {
            return redirect()->back()->with('login', 'notmahasiswa');
        } elseif (auth()->check() && $request->user()->level == 'Mahasiswa' && $request->user()->email_verified_at == '') {
            return Redirect::to('/daftarakun');
        } else {
            Auth::logout();
            return Redirect::to('/')
                ->with('login', 'notmahasiswa');
        }
    }
}
