<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

use FFMpeg\Coordinate\Dimension;
use FFMpeg\Format\Video\X264;
use FFMpeg;

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


        $lowBitrateFormat = (new x264('acc', 'libx264'))->setKiloBitrate(500);
        $low2_BitrateFormat = (new x264('acc', 'libx264'))->setKiloBitrate(900);
        $mediumBitrateFormat = (new x264('acc', 'libx264'))->setKiloBitrate(1500);
        $highBitrateFormat = (new x264('acc', 'libx264'))->setKiloBitrate(3000);

        $convertedName =     '240-' .$video->video_path;
        $convertedName_360 = '360-' .$video->video_path;
        $convertedName_480 = '480-' .$video->video_path;
        $convertedName_720 = '720-' .$video->video_path;

        FFMpeg::fromDisk($video->disk)
        ->open($video->video_path)

        ->addFilter(function ($filters){
            $filters->resize(new Dimension(426, 240));
        })
        ->export()
        ->toDisk('public')
        ->inFormat($lowBitrateFormat)
        ->save($convertedName)

        ->addFilter(function ($filters){
            $filters->resize(new Dimension(640, 360));
        })
        ->export()
        ->toDisk('public')
        ->inFormat($low2_BitrateFormat)
        ->save($convertedName_360)

        ->addFilter(function ($filters){
            $filters->resize(new Dimension(854, 480));
        })
        ->export()
        ->toDisk('public')
        ->inFormat($mediumBitrateFormat)
        ->save($convertedName_480)

        ->addFilter(function ($filters){
            $filters->resize(new Dimension(1280, 720));
        })
        ->export()
        ->toDisk('public')
        ->inFormat($highBitrateFormat)
        ->save($convertedName_720);

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
