<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
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
        $is_admin = auth()->user()->is_admin;

        if (!$is_admin) {
            return redirect()->back()->with('error', 'Trebuie să fiți administrator ca să puteți accesa aceasta pagină');
        }

        return $next($request);
    }
}
