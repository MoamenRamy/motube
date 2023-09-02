<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = User::find(auth()->id());
        $videos = $user->videoInHistory()->get();
        $title = 'history';

        return view('history.history-index', compact('videos', 'title'));
    }

    public function destroy($id)
    {
        auth()->user()->videoInHistory()->wherePivot('id', $id)->detach();

        return back()->with('success', 'the video has been deleted from history');
    }

    public function distroyAll()
    {
        auth()->user()->videoInHistory()->detach();

        return back()->with('success', 'the videos have been deleted from history');
    }
}
