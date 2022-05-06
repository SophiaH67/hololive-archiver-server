<?php

namespace App\Jobs;

use App\Models\DownloadRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessDownloadRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var DownloadRequest
     */
    protected $downloadRequest;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(DownloadRequest $downloadRequest)
    {
        $this->downloadRequest = $downloadRequest;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $urls = [$this->downloadRequest->url]; // TODO: playlist support

        foreach ($urls as $url) {
            $this->downloadRequest->downloadJobs()->create([
                'url' => $url,
            ]);
        }
    }
}
