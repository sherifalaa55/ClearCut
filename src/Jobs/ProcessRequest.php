<?php

namespace SherifAI\ClearCut\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use SherifAI\ClearCut\Factories\DumperFactory;
use SherifAI\ClearCut\RequestLog;

class ProcessRequest implements ShouldQueue
{
    private $payload;
    private $headers;
    private $method;
    private $starts_at;
    private $ends_at;
    private $response;
    private $uuid;
    private $path;
    private $wantsJson;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($payload, $headers, $method, $starts_at, $ends_at, $response, $uuid, $path, $wantsJson)
    {
        $this->payload = $payload;
        $this->headers = $headers;
        $this->method = $method;
        $this->starts_at = $starts_at;
        $this->ends_at = $ends_at;
        $this->response = $response;
        $this->uuid = $uuid;
        $this->path = $path;
        $this->wantsJson = $wantsJson;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->skippedUrl($this->path) || $this->skippedMethod($this->method)) {
            return $response;
        }

        $log = $this->logToDB($this->payload, $this->response);

        if (RequestLog::count() >= Config::get("clearcut.dump_every")) {
            $this->dumpDb();
        }
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

    private function logToDB($payload, $response)
    {
        $payload = $this->hideSensetiveData($payload);
        
        $log = new RequestLog;
        $log->setTable(Config::get("clearcut.table_name"));
        $log->duration = $this->ends_at - $this->starts_at;
        $log->payload = json_encode($payload);
        $log->request_headers = json_encode($this->headers);
        $log->method = $this->method;
        
        if (strlen($response->getContent()) < 20000 || $this->wantsJson) {
            $log->response = $response->getContent();
        }

        $log->response_status = $response->status();
        $log->uuid = $this->uuid;
        $log->path = $this->path;
        $log->save();

        return $log;
    }

    private function skippedUrl($path)
    {
        $skipped_urls = Config::get("clearcut.skip_urls");

        foreach ($skipped_urls as $skipped_url) {
            $skipped_url = "/{$skipped_url}/i";

            if (preg_match($skipped_url, $path)) {
                return true;
            }
        }
    }

    private function skippedMethod($method)
    {
        return in_array($method, Config::get("clearcut.skip_methods"));
    }

    private function dumpDb()
    {
        $dumper = DumperFactory::getDumper(Config::get("database.default"));

        $date = date("Ymdhis");

        $dump_name = "dump-{$date}.sql";

        $dumper->setDbName(env("DB_DATABASE"))
            ->setUserName(env("DB_USERNAME"))
            ->setPassword(env("DB_PASSWORD"))
            ->includeTables([Config::get("clearcut.table_name")])
            ->dumpToFile($dump_name);

        RequestLog::truncate();

        Storage::disk(Config::get("clearcut.storage_disk"))->put("clearcut/{$dump_name}", file_get_contents($dump_name));

        unlink($dump_name);
    }
}