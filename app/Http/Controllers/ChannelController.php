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

    public function adminIndex()
    {
        $channels = User::all();

        return view('admin.channels.index', compact('channels'));
    }

    public function adminUpdate(Request $request, User $channel)
    {
        $channel->administration_level = $request->administration_level;
        $channel->save();

        session()->flash('flash_message', 'the channel permissions have been updated successfuly');

        return redirect(route('admin.channels.index'));
    }

    public function adminDestroy(User $channel)
    {
        $channel->delete();

        session()->flash('flash_message', 'the channel has been deleted successfuly');

        return redirect(route('admin.channels.index'));
    }

    public function adminBlock(User $channel)
    {
        $channel->block = 1;
        $channel->save();

        session()->flash('flash_message', 'channel has been blocked successfuly');

        return redirect(route('admin.channels.index'));
    }

    public function blockedChannels()
    {
        $channels = User::where('block', 1)->get();

        return view('admin.channels.blocked-channel', compact('channels'));
    }

    public function adminUnBlock(Request $request, User $channel)
    {
        $channel->block = 0;
        $channel->save();

        session()->flash('flash_message', 'channel has been Unblocked successfuly');

        return redirect(route('admin.blocked.channels'));
    }

    public function adminChannelsAll()
    {
        $channels = User::all()->sortByDesc('created_at');
        
        return view('admin.channels.all', compact('channels'));
    }
}
