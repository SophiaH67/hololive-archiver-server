<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DownloadAttempt>
 */
class DownloadAttemptFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'logs' => $this->faker->text(),
            'heartbeat_at' => now(),
            'success' => $this->faker->boolean(),
        ];
    }
}
