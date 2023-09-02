<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\LikeController;
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

Route::get('/', function () {
    return view('welcome');
});

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


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('layouts.main');
    })->name('/');
});

