<?php

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
            $table->foreignIdFor('download_job');
            $table->boolean('success');
            $table->text('logs');
            $table->dateTime('heartbeat_at');
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
