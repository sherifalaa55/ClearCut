<?php

namespace SherifAI\ClearCut\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use SherifAI\ClearCut\Jobs\ProcessRequest;
use SherifAI\ClearCut\RequestLog;

class LogRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Config::get('clearcut.enabled')) {
            $request->starts_at = time();

            $response = $next($request);

            $request->ends_at = time();

            $uuid = (string)\Webpatser\Uuid\Uuid::generate();

            ProcessRequest::dispatch($request->all(), $request->headers->all(), $request->method(), $request->starts_at, $request->ends_at, $response->getContent(), $response->status(), $uuid, $request->path(), $request->wantsJson())
                ->onQueue(Config::get("clearcut.queue_name"));

            $response->header("X-Request-Id", $uuid);
            
            return $response;
        }

        return $next($request);
    }
}
