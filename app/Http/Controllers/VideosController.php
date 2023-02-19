<?php

namespace App\Http\Controllers;

use App\Http\Requests\VideoStoreRequest;
use App\Models\Video;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideosController extends Controller
{
    public function index()
    {
        return Video::all();
    }

    public function store(VideoStoreRequest $request)
    {
        $file = $request->file('data');
        $fileName = $file->getClientOriginalName();

        if (Video::whereName($fileName)->first()) {
            return response()->json(['error' => 'File exists'], Response::HTTP_CONFLICT);
        }

        $video = Video::create([
            'name' => $fileName,
            'size' => $file->getSize(),
        ]);
        Storage::disk('s3')->put($video->fileid, $file->getContent());
        $fileLocation = Storage::disk('s3')->url($video->fileid);

        return response()->json(null, 201)->header('Location', $fileLocation);
    }

    public function show(Video $file)
    {
        return Storage::disk('s3')->download($file->fileid, $file->name);
    }

    public function destroy(Video $file)
    {
        $file->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
