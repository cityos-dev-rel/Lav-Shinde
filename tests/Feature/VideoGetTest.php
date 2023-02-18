<?php

namespace Tests\Feature;

use App\Models\Video;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
            '*' => [
                'fileid',
                'name',
                'size',
                'created_at',
            ]
        ]);
    }

    public function testGetSingleFileWhichDoesNotExist()
    {
        $response = $this->get('/files/123');

        $response->assertNotFound();
    }

    public function testSuccessfulGetSingleFile()
    {
        Storage::fake('s3');
        $video = Video::factory()->create();
        $file = UploadedFile::fake()->create('test.mp4', 100);
        Storage::disk('s3')->put($video->fileid, $file->getContent());

        $response = $this->get("/files/$video->fileid");

        $response->assertOk();
        $response->assertHeader('Content-Disposition');
        $response->assertDownload($video->name);
    }
}
