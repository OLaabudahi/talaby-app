<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! $request->user()) {
            return redirect('/login'); // أو abort(401);
        }

        foreach ($roles as $role) {
            if ($request->user()->role === $role) { // افترض أن لديك حقل 'role' في جدول المستخدمين
                return $next($request);
            }
        }

        abort(403, 'Unauthorized action.'); // إذا لم يكن لديه الدور المطلوب
    }
}