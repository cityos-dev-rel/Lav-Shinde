<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class VideoStoreTest extends TestCase
{
    use DatabaseTransactions;

    public function testVideoStoreWithoutData()
    {
        $response = $this->postJson('/files', []);

        $response->assertBadRequest();
    }

    public function testVideoStoreWithTextFile()
    {
        $file = UploadedFile::fake()->create('test.txt', 100);
        $response = $this->postJson('/files', [
            'data' => $file,
        ]);

        $response->assertBadRequest();
    }
}
