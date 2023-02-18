<?php

namespace Tests\Feature;

use App\Models\Video;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VideoGetTest extends TestCase
{
    use DatabaseTransactions;

    public function testGetListOfUploadedFiles()
    {
        Video::factory()->count(4)->create();

        $response = $this->get('/files');

        $response->assertOk();
        $response->assertJsonCount(4);
        $response->assertJsonStructure([
            'fileid',
            'name',
            'size',
            'created_at',
        ]);
    }
}
