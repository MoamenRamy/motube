<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public function index()
    {
        $channels = User::all()->sortByDesc('created_at');
        $title = 'Latest channels';
        return view('channels', compact('title', 'channels'));
    }

    public function search(Request $request)
    {
        $channels = User::where('name', 'like', "%{$request->term}%")->paginate(12);
        $title = 'Display search results for :'. $request->term;
        return view('channels', compact('channels', 'title'));
    }
}
