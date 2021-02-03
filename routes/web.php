<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\HomepageController;

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

Route::get('/', [HomepageController::class, 'index'])->name('homepage');
Route::get('/sayfa', [HomepageController::class, 'index']);
Route::get('/kategori/{category}', [HomepageController::class, 'category'])->name('category');
Route::get('/{category}/{slug}', [HomepageController::class, 'single'])->name('single');
Route::get('/{sayfa}', [HomepageController::class, 'page'])->name('page');