<?php

namespace Tests\Feature;

use App\Models\Video;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Support\Str;

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

    public function testVideoStoreWithFileAlreadyExists()
    {
        Storage::fake('s3');
        $file = UploadedFile::fake()->create('test.mp4', 100);
        Video::factory()->create([
            'title' => $file->getClientOriginalName(),
        ]);

        $response = $this->postJson('/files', [
            'data' => $file,
        ]);

        $response->assertStatus(409);
    }
}
