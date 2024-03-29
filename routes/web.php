<?php

use App\Http\Controllers\AdminsController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\VideoController;
use App\Models\Video;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/admin', function () {
//     return view('theme.default');
// });

Route::get('/', [MainController::class, 'index'])->name('main');
Route::get('/main/{channel}/videos', [MainController::class, 'channelVideos'])->name('main.channels.videos');

Route::resource('/videos', VideoController::class);
Route::get('/video/search', [VideoController::class, 'search'])->name('video.search');

Route::post('/like', [LikeController::class, 'LikeVideo'])->name('like');

Route::post('/view', [VideoController::class, 'addView'])->name('view');

Route::post('/comment', [CommentController::class, 'saveComment'])->name('comment');
Route::get('/comment/{id}/edit', [CommentController::class, 'edit'])->name('comment.edit');
Route::patch('/comment/{id}',[CommentController::class,'update'])->name('comment.update');
Route::get('/comment/{id}', [CommentController::class, 'destroy'])->name('comment.destroy');

Route::get('/history', [HistoryController::class, 'index'])->name('history');
Route::delete('/history/{id}', [HistoryController::class, 'destroy'])->name('history.destroy');
Route::delete('/distroyAll', [HistoryController::class, 'distroyAll'])->name('history.distroyAll');

Route::get('/channel', [ChannelController::class, 'index'])->name('channels.index');
Route::get('/channel/search', [ChannelController::class, 'search'])->name('channel.search');


Route::prefix('/admin')->middleware('can:update-videos')->group(function() {
    Route::get('/', [AdminsController::class, 'index'])->name('admin.index');
    Route::get('/channels', [ChannelController::class, 'adminIndex'])->name('admin.channels.index');
    Route::patch('/{channel}/channels', [ChannelController::class, 'adminUpdate'])->name('admin.channels.update')->middleware('can:update-users');
    Route::delete('/channels/{channel}', [ChannelController::class, 'adminDestroy'])->name('admin.channels.delete')->middleware('can:update-users');
    Route::patch('/{channel}/block', [ChannelController::class, 'adminBlock'])->name('admin.channels.block')->middleware('can:update-users');
    Route::get('/channels/blocked', [ChannelController::class, 'blockedChannels'])->name('admin.blocked.channels');
    Route::patch('/{channel}/unblock', [ChannelController::class, 'adminUnBlock'])->name('admin.channels.unblock')->middleware('can:update-users');
    Route::get('/allChannels', [ChannelController::class, 'adminChannelsAll'])->name('admin.channels.all');
    Route::get('/MostViewedVideos', [VideoController::class, 'mostViewedVideos'])->name('most.viewed.videos');
});

Route::post('/Notifications', [NotificationController::class, 'index'])->name('notification');
Route::get('/Notifications', [NotificationController::class, 'allNotifications'])->name('all.notifications');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('layouts.main');
    })->name('dashboard');
});

