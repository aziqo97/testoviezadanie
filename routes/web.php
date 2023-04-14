<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Models\Application;

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


Route::get('/dashboard', [ApplicationController::class, 'dashboard'])->name('dashboard')->middleware('auth');
Route::get('/', function () {
    return redirect('dashboard');
})->middleware('auth');

Route::resource('applications', ApplicationController::class);
require __DIR__.'/auth.php';
