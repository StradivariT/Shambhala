<?php

namespace App\Http\Middleware;

use Closure;

class Cors {
    public function handle($request, Closure $next) {
        $response = $next($request);
        
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'HEAD, GET, POST, PATCH, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Accept, Application');

        return $response;
    }
}