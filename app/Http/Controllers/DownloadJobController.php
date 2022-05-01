<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDownloadJobRequest;
use App\Http\Requests\UpdateDownloadJobRequest;
use App\Models\DownloadJob;

class DownloadJobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreDownloadJobRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDownloadJobRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DownloadJob  $downloadJob
     * @return \Illuminate\Http\Response
     */
    public function show(DownloadJob $downloadJob)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DownloadJob  $downloadJob
     * @return \Illuminate\Http\Response
     */
    public function edit(DownloadJob $downloadJob)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDownloadJobRequest  $request
     * @param  \App\Models\DownloadJob  $downloadJob
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDownloadJobRequest $request, DownloadJob $downloadJob)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DownloadJob  $downloadJob
     * @return \Illuminate\Http\Response
     */
    public function destroy(DownloadJob $downloadJob)
    {
        //
    }
}
