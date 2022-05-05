<?php

namespace App\Http\Controllers;

use App\Models\DownloadAttempt;
use App\Models\DownloadJob;

class DownloadJobController extends Controller
{
    public function pop(String $platform)
    {
        // Find a DownloadJob and join it with DownloadAttempt.
        $downloadJobs = DownloadJob::where(function ($query) use ($platform) {
            $query->where('platform', $platform)
                ->orWhereNull('platform');
        })->with(DownloadAttempt::class)->get();

        // If there are no DownloadJobs, return null.
        if ($downloadJobs->isEmpty()) {
            return null;
        }

        // Find the first DownloadJob that has a status of `pending`.
        $downloadJob = $downloadJobs->first(function ($downloadJob) {
            return $downloadJob->status === "pending";
        });

        // Start a new DownloadAttempt for the DownloadJob and return it.
        $downloadAttempt = $downloadJob->downloadAttempts()->create();
        return $downloadAttempt;
    }
}
