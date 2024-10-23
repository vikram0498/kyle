<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockSensitiveFiles
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
        $uri = $request->path();

        // Check if the URI matches a pattern like /.file_name
        if (preg_match('/^\.[\w\-]+$/', $uri)) {
            abort(Response::HTTP_FORBIDDEN); // 403 Forbidden
        }

        return $next($request);
    }
}
