<?php

namespace App\Http\Controllers;

use App\Http\Requests\VideoStoreRequest;
use App\Models\Video;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Video::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
        Storage::disk('s3')->put($video->fileid, file_get_contents($file));
        $fileLocation = Storage::disk('s3')->url($video->fileid);

        return response()->json(null, 201)->header('Location', $fileLocation);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Video $file)
    {
        return response()->json(null, 201)->header('Location', $file->url);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Video $file)
    {
        $file->delete();
    }
}
