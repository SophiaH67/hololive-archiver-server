<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        $latestAttempt = $this->dowloadAttempts()->latest()->first();
        return $latestAttempt->status;
    }
}
