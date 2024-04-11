<?php

namespace App\Http\Middleware;

use Closure;

class RemovePortFromUrl
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
        $url = $request->url();
        $parsedUrl = parse_url($url);

        if (isset($parsedUrl['port'])) {
            $url = str_replace(':' . $parsedUrl['port'], '', $url);
            $request->headers->set('host', $parsedUrl['host']); // Update the 'host' header to remove the port
            $request->server->set('SERVER_NAME', $parsedUrl['host']); // Update the 'SERVER_NAME' to remove the port
            $request->server->set('HTTP_HOST', $parsedUrl['host']); // Update the 'HTTP_HOST' to remove the port
            $request->headers->set('x-forwarded-host', $parsedUrl['host']); // Update 'x-forwarded-host' to remove the port if using a proxy

            $request->url = $url; // Update the request URL
        }

        return $next($request);
    }
}
