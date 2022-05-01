<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDownloadAttemptRequest;
use App\Http\Requests\UpdateDownloadAttemptRequest;
use App\Models\DownloadAttempt;

class DownloadAttemptController extends Controller
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
     * @param  \App\Http\Requests\StoreDownloadAttemptRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDownloadAttemptRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DownloadAttempt  $downloadAttempt
     * @return \Illuminate\Http\Response
     */
    public function show(DownloadAttempt $downloadAttempt)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DownloadAttempt  $downloadAttempt
     * @return \Illuminate\Http\Response
     */
    public function edit(DownloadAttempt $downloadAttempt)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDownloadAttemptRequest  $request
     * @param  \App\Models\DownloadAttempt  $downloadAttempt
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDownloadAttemptRequest $request, DownloadAttempt $downloadAttempt)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DownloadAttempt  $downloadAttempt
     * @return \Illuminate\Http\Response
     */
    public function destroy(DownloadAttempt $downloadAttempt)
    {
        //
    }
}
