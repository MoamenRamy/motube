<?php

namespace App\Http\Controllers;

use App\Jobs\ConvertVideoForStreaming;
use App\Models\Convertedvideo;
use App\Models\Like;
use App\Models\Video;
use App\Models\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Intervention\Image\ImageManagerStatic as Image;
use Storage;
use Illuminate\Support\Str;

class VideoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['show', 'addView']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $videos = auth()->user()->videos->sortByDesc('created_at');
        $title = 'Latest videos uploaded';
        return view('videos.my-videos', compact('videos', 'title'));
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

        $randomPath = Str::random(16);
        $videoPath = $randomPath . '.' . $request->video->getClientOriginalExtension();
        $imagePath = $randomPath . '.' . $request->image->getClientOriginalExtension();

        $image = Image::make($request->image)->resize(320, 180);

        $path = Storage::put($imagePath, $image->stream());

        $request->video->storeAs('/', $videoPath, 'public');

        $video = Video::create([
            'disk'        => 'public',
            'video_path'  => $videoPath,
            'image_path'  => $imagePath,
            'title'       => $request->title,
            'user_id'     => auth()->id(),
        ]);

        $view = View::create([
            'video_id' => $video->id,
            'user_id' => auth()->id(),
            'views_number' => 0,
        ]);

        Bus::dispatch(new ConvertVideoForStreaming($video));

        return redirect()->back()->with(
            'success',
            "The video will be available next season when he hasn't finished processing it."
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Video $video)
    {
        $countLike = Like::where('video_id', $video->id)->where('like', '1')->count();
        $countDislike = Like::where('video_id', $video->id)->where('like', '0')->count();

        $user = Auth::user();
        if(Auth::check()) {
            $userLike = $user->likes()->where('video_id', $video->id)->first();
        } else {
            $userLike = 0;
        }

        if(Auth::check()) {
            auth()->user()->videoInHistory()->attach($video->id);
        }

        $comments = $video->comments->sortByDesc('created_at');

        return view('videos.show-video', compact('video', 'countLike', 'countDislike', 'userLike', 'comments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $video= Video::whereId($id)->first();
        return view('videos.edit-video', compact('video'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request,[
            'title' => 'required',
        ]);

        $video = Video::whereId($id)->first();

        if($request->has('image'))
        {
            $randomPath = Str::random(16);
            $newPath = $randomPath. '.' . $request->image->getClientOriginalExtension();

            Storage::delete($video->image_path);

            $image = Image::make($request->image)->resize(320, 180);

            $path = Storage::put($newPath, $image->stream());

            $video->image_path = $newPath;
        }

        $video->title = $request->title;

        $video->save();

        return redirect('/videos')->with('success', 'The video clip has been modified successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $video = Video::whereId($id)->first();

        $convertVideos = Convertedvideo::where('video_id', $id)->get();

        foreach($convertVideos as $convertVideo)
        {
            Storage::delete([
                $convertVideo->mp4_Format_240,
                $convertVideo->mp4_Format_360,
                $convertVideo->mp4_Format_480,
                $convertVideo->mp4_Format_720,
                $convertVideo->mp4_Format_1080,
                $convertVideo->webm_Format_240,
                $convertVideo->webm_Format_360,
                $convertVideo->webm_Format_480,
                $convertVideo->webm_Format_720,
                $convertVideo->webm_Format_1080,
                $video->image_path,
            ]);
        }

        $video->delete();

        return back()->with('success', 'The video has been deleted successfully');
    }

    public function search(Request $request)
    {
        $videos = Video::where('title', 'like', "%{$request->term}%")->paginate(12);
        $title = 'Display search results for :'. $request->term;
        return view('videos.my-videos', compact('videos', 'title'));
    }

    public function addView(Request $request)
    {
        $views = View::where('video_id', $request->videoId)->first();

        $views->views_number++;

        $views->save();

        $viewsNumbers = $views->views_number;
        return response()->json(['viewsNumbers' => $viewsNumbers]);
    }
}
