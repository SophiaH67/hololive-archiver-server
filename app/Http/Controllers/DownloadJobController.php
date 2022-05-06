<?php

namespace App\Http\Controllers;

use App\Models\DownloadAttempt;
use App\Models\DownloadJob;
use App\Models\DownloadRequest;

class DownloadJobController extends Controller
{
    public function pop(String $platform)
    {
        // Find a DownloadJob and join it with DownloadAttempt.
        $downloadRequests = DownloadRequest::where('platform', $platform)
            ->with(['downloadJobs' => function ($query) {
                $query->with(['downloadAttempts' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                }]);
            }])
            ->get();
        // If there are no DownloadJobs, return null.
        if ($downloadRequests->isEmpty()) {
            return response()->json(null, 204);
        }

        // Find the first DownloadJob that has a status of `pending`.
        $downloadRequest = $downloadRequests->first(function ($downloadRequest) {
            return $downloadRequest->downloadJobs->first(function ($downloadJob) {
                return $downloadJob->status === 'pending';
            }) !== null;
        });
        if ($downloadRequest === null) {
            return response()->json(null, 204);
        }
        // Get the first DownloadJob that has a status of `pending`.
        $downloadJob = $downloadRequest->downloadJobs->first(function ($downloadJob) {
            return $downloadJob->status === 'pending';
        });
        // Create a new DownloadAttempt for the DownloadJob.
        $downloadAttempt = $downloadJob->downloadAttempts()->create([
            'heartbeat_at' => now(),
        ]);
        // Return the DownloadAttempt.
        return $downloadAttempt;
    }
}
