<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;

class LogActivity
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Log only certain actions
        if ($request->isMethod('post') || $request->isMethod('put') || $request->isMethod('delete')) {
            ActivityLog::log('http_request', [
                'details' => "{$request->method()} {$request->path()}",
            ]);
        }

        return $response;
    }
}
