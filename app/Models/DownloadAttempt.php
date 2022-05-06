<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DownloadAttempt extends Model
{
    use HasFactory;
    // Register `heartbeat_at` as a carbon instance.
    protected $casts = [
        'heartbeat_at' => 'datetime',
    ];

    protected $appends = [
        'status',
        'url',
        'output_folder',
    ];

    protected $fillable = [
        'heartbeat_at',
        'logs',
        'success',
    ];

    public function downloadJob()
    {
        return $this->belongsTo(DownloadJob::class);
    }

    public function getStatusAttribute()
    {
        if ($this->success) {
            return 'success';
        }
        if ($this->heartbeat_at->diffInSeconds() < 60) {
            return 'processing';
        }
        return 'failed';
    }

    public function getUrlAttribute()
    {
        return $this->downloadJob->url;
    }

    public function getOutputFolderAttribute()
    {
        return $this->downloadJob->output_folder;
    }
}
