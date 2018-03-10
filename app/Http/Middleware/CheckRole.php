<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        $segments = explode('--', $role);
        if (count($segments) === 2) {
            list($module, $action) = explode('--', $role);
            if (!checkAccess($module,$action)) {
                return response()->view('error.error404')->header('Content-Type', 'text/html');
            }
        } else {
            $module = $segments[0];
            if (!checkAccess($module)) {
                return response()->view('error.error404')->header('Content-Type', 'text/html');
            }
        }


        return $next($request);
    }
}
