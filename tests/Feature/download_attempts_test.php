<?php

namespace Tests\Feature;

use App\Models\DownloadAttempt;
use Carbon\Factory;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class download_attempts_test extends TestCase
{
    /**
     * It should have a status attribute that is computed from the success and heartbeat_at attributes.
     * @test
     */
    public function it_should_have_a_status_attribute_that_is_computed_from_the_success_and_heartbeat_at_attributes()
    {
        $download_attempt = DownloadAttempt::factory()->create([
            'success' => true,
            'heartbeat_at' => Carbon::now()->subSeconds(60),
        ]);

        $download_attempt->refresh();

        $this->assertEquals('success', $download_attempt->status);
    }

    /**
     * It should have a status attribute that is computed from the success and heartbeat_at attributes.
     * @test
     */
    public function it_should_have_a_status_attribute_that_is_computed_from_the_success_and_heartbeat_at_attributes_2()
    {
        $download_attempt = DownloadAttempt::factory()->create([
            'success' => false,
            'heartbeat_at' => Carbon::now()->subSeconds(120),
        ]);

        $download_attempt->refresh();

        $this->assertEquals('failed', $download_attempt->status);
    }
}
