<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * DownloadJob
 *
 * This class represents a thing to be downloaded(e.g, a youtube video but not a playlist).
 * For playlists, see DownloadRequest.
 */
class DownloadJob extends Model
{
    use HasFactory;

    public function downloadRequest()
    {
        return $this->belongsTo(DownloadRequest::class);
    }

    public function dowloadAttempts()
    {
        return $this->hasMany(DownloadAttempt::class);
    }

    public function getStatusAttribute()
    {
        // Get the status of the latest DownloadAttempt
        $status = $this->dowloadAttempts()->latest()->first()->status;
        if ($status === "failed") {
            // If more than 3 attempts have failed, return failed.
            if ($this->dowloadAttempts()->where('status', 'failed')->count() > 3) {
                return "failed";
            } else {
                return "pending";
            }
        }
        return $status;
    }

    public function getTriesAttribute()
    {
        return $this->dowloadAttempts()->count();
    }
}
