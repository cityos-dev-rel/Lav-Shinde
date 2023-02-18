<?php

namespace Tests\Feature;

use App\Models\Video;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VideoDeleteTest extends TestCase
{
    use DatabaseTransactions;

    public function testVideoNotFoundForDeletion()
    {
        $response = $this->deleteJson('/files/123');

        $response->assertNotFound();
    }

    public function testSuccessfulVideoDeletion()
    {
        $video = Video::factory()->create();
        $response = $this->deleteJson("/files/$video->fileid");

        $response->assertNoContent();
        $this->assertDatabaseMissing('videos', [
            'fileid' => $video->fileid,
        ]);
    }
}
