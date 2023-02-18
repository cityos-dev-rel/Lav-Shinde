<?php

namespace Tests\Feature;

use App\Models\Video;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VideoStoreTest extends TestCase
{
    use DatabaseTransactions;

    public function testVideoStoreWithoutData()
    {
        $response = $this->postJson('/files', []);

        $response->assertBadRequest();
    }

    public function testVideoStoreWithWrongContent()
    {
        $file = UploadedFile::fake()->create('test.txt', 100);
        $response = $this->postJson('/files', [
            'data' => $file,
        ]);

        $response->assertStatus(415);
    }

    public function testVideoStoreWithFileAlreadyExists()
    {
        Storage::fake('s3');
        $file = UploadedFile::fake()->create('test.mp4', 100);
        Video::factory()->create([
            'name' => $file->getClientOriginalName(),
        ]);

        $response = $this->postJson('/files', [
            'data' => $file,
        ]);

        $response->assertConflict();
    }

    public function testVideoStoreWithValidFile()
    {
        Storage::fake('s3');
        $file = UploadedFile::fake()->create('test.mp4', 100);

        $response = $this->postJson('/files', [
            'data' => $file,
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('videos', [
            'name' => $file->getClientOriginalName(),
        ]);
    }
}
