<?php

use App\Models\DownloadJob;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('download_attempts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(DownloadJob::class);
            $table->boolean('success')->default(false);
            $table->text('logs')->nullable();
            $table->timestamp('heartbeat_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('download_attempts');
    }
};
