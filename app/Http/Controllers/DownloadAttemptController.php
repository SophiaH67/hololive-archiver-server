<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateDownloadAttemptRequest;
use App\Models\DownloadAttempt;

class DownloadAttemptController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DownloadAttempt  $downloadAttempt
     * @return \Illuminate\Http\Response
     */
    public function show(DownloadAttempt $downloadAttempt)
    {
        return $downloadAttempt;
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
        if ($downloadAttempt->status !== 'processing') {
            return response()->json(null, 400);
        }
        $values = $request->validated();
        $values['heartbeat_at'] = now();
        $downloadAttempt->update($values);
        return $downloadAttempt;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DownloadAttempt  $downloadAttempt
     * @return \Illuminate\Http\Response
     */
    public function destroy(DownloadAttempt $downloadAttempt)
    {
        $downloadAttempt->delete();
        return response()->json(null, 204);
    }
}
