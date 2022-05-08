<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DownloadRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'output_folder',
        'output_file',
        'platform',
    ];

    public function downloadJobs()
    {
        return $this->hasMany(DownloadJob::class);
    }
}
