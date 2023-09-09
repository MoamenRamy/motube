<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Video;
use App\Models\View;
use Illuminate\Http\Request;
use DB;

class AdminsController extends Controller
{
    public function index()
    {
        $videos = Video::count();
        $channels = User::count();

        $mostViews = View::select('user_id', DB::raw('sum(views.views_number) as total'))
        ->groupBy('user_id')
        ->orderBy('total', 'Desc')
        ->take(5)
        ->get();

        $names = [];
        $totalViews = [];

        foreach($mostViews as $view)
        {
            array_push($names, User::find($view->user_id)->name);
            array_push($totalViews, $view->total);
        }

        return view('admin.index', compact('videos', 'channels'))->with('names', json_encode($names, JSON_NUMERIC_CHECK))->with('totalViews', json_encode($totalViews, JSON_NUMERIC_CHECK));
    }
}
