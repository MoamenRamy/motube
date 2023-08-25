<?php

namespace App\Http\Controllers;

use App\Jobs\ConvertVideoForStreaming;
use App\Models\Video;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;




class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('videos.uploader');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'image' => 'image|required',
            'video' => 'required',
        ]);

        $randompath = Str::random(16);
        $videopath = $randompath . '.' . $request->video->getClientOriginalExtension();
        $imagepath = $randompath . '.' . $request->image->getClientOriginalExtension();

        $image = Image::make($request->image)->resize(320, 180);


        $path = Storage::put($imagepath, $image->stream());


        $request->video->storeAs('/', $videopath, 'public');

        $video = Video::create([
            'disc' => 'public',
            'video_path' => $videopath,
            'image_path' => $imagepath,
            'title' => $request->title,
            'user_id' => auth()->id()
        ]);

        ConvertVideoForStreaming::dispatch($video);

        return redirect()->back()->with(
            'success',
            'The video clip will be available in the shortest time when we finish processing it'
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
