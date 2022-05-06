<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDownloadRequestRequest;
use App\Http\Requests\UpdateDownloadRequestRequest;
use App\Jobs\ProcessDownloadRequest;
use App\Models\DownloadRequest;

class DownloadRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return DownloadRequest::all()->load(['downloadJobs', 'downloadJobs.downloadAttempts']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreDownloadRequestRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDownloadRequestRequest $request)
    {
        $downloadRequest = DownloadRequest::create($request->validated());
        ProcessDownloadRequest::dispatchSync($downloadRequest);
        $downloadRequest->load(['downloadJobs', 'downloadJobs.downloadAttempts']);
        return response()->json($downloadRequest, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DownloadRequest  $downloadRequest
     * @return \Illuminate\Http\Response
     */
    public function show(DownloadRequest $downloadRequest)
    {
        return $downloadRequest;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DownloadRequest  $downloadRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(DownloadRequest $downloadRequest)
    {
        return response()->json($downloadRequest->delete(), 204);
    }
}
