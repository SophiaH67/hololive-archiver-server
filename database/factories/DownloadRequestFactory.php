<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DownloadRequest>
 */
class DownloadRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'url' => "https://youtube.com/watch?v=dQw4w9WgXcQ&random=" . urlencode($this->faker->text()),
            'output_folder' => "/tmp",
            'platform' => "youtube",
        ];
    }
}
