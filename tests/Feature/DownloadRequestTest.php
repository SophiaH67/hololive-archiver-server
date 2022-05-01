<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DownloadRequestTest extends TestCase
{
    /**
     * It should return a list of download requests.
     * @test
     */
    public function it_should_return_a_list_of_download_requests()
    {
        $response = $this->get('/api/download-requests');

        $response->assertStatus(200);
    }

    /**
     * It should be able to create a new download request.
     * @test
     */
    public function it_should_be_able_to_create_a_new_download_request()
    {
        $response = $this->post('/api/download-requests', [
            'url' => 'https://youtube.com/watch?v=dQw4w9WgXcQ',
            'output_folder' => '/tmp/',
            'platform' => 'youtube',
        ]);

        $response->assertStatus(201);

        $response->assertJson([
            'url' => 'https://youtube.com/watch?v=dQw4w9WgXcQ',
            'output_folder' => '/tmp/',
            'platform' => 'youtube',
        ]);

        $this->assertDatabaseHas('download_requests', [
            'url' => 'https://youtube.com/watch?v=dQw4w9WgXcQ',
            'output_folder' => '/tmp/',
            'platform' => 'youtube',
        ]);
    }
}
