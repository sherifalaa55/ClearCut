<?php

namespace SherifAI\ClearCut\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
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
        $request->starts_at = time();

        $response = $next($request);

        $request->ends_at = time();

        if ($this->skippedUrl($request) || $this->skippedMethod($request)) {
            return $response;
        }

        $log = $this->logToDB($request, $response);

        $response->header("X-Request-Id", $log->uuid);

        return $response;
    }

    private function hideSensetiveData($payload)
    {
        $sensitive_keys = Config::get("clearcut.sensitive_data");

        array_walk_recursive($payload, function (&$value, $array_key, $sensitive_keys) {
            foreach ($sensitive_keys as $key) {

                $key = "/{$key}/i";
                if (preg_match($key, $array_key)) {
                    $value = "******";
                }
            }
        }, $sensitive_keys);

        return $payload;
    }

    private function logToDB(Request $request, $response)
    {
        $payload = $this->hideSensetiveData($request->all());
        
        $log = new RequestLog;
        $log->setTable(Config::get("clearcut.table_name"));
        $log->duration = $request->ends_at - $request->starts_at;
        $log->payload = json_encode($payload);
        $log->request_headers = json_encode($request->headers->all());
        $log->method = $request->method();
        
        if (strlen($response->getContent()) < 20000 || $request->wantsJson()) {
            $log->response = $response->getContent();
        }

        $log->response_status = $response->status();
        $log->uuid = (string)\Webpatser\Uuid\Uuid::generate();
        $log->save();

        return $log;
    }

    private function skippedUrl(Request $request)
    {
        $skipped_urls = Config::get("clearcut.skip_urls");

        $url = $request->path();

        foreach ($skipped_urls as $skipped_url) {
            $skipped_url = "/{$skipped_url}/i";

            if (preg_match($skipped_url, $url)) {
                return true;
            }
        }
    }

    private function skippedMethod(Request $request)
    {
        return in_array($request->method(), Config::get("clearcut.skip_methods"));
    }
}
