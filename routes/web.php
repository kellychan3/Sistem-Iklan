<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VideoController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('throttle:5,1');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'nocache'])->group(function () {
    // Video
    Route::get('/', [VideoController::class, 'index'])->name('videos');
    Route::get('/videos/create-modal', [VideoController::class, 'createModal'])->name('videos.create-modal');
    Route::get('/videos/{id}/edit-modal', [VideoController::class, 'editModal'])->name('videos.edit-modal');
    Route::resource('videos', VideoController::class)->only(['store', 'update', 'destroy']);
});