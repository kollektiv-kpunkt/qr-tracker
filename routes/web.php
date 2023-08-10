<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CodeController;
use Illuminate\Support\Facades\Route;

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
    Config::set('route.name', "Home");
    return view('home');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get("/codes/export", [CodeController::class, 'export'])->name('codes.export')->middleware(['auth']);
Route::get("/codes/export-svg", [CodeController::class, 'exportSvg'])->name('codes.exportSvg')->middleware(['auth']);
Route::resource('codes', CodeController::class)->parameters([
    'codes' => 'code:uuid',
])->middleware(['auth']);

Route::get('/tools', function () {
    Config::set('route.name', "Tools");
    return view('tools.index');
})->name('tools')->middleware(['auth']);

Route::post("/tools/import", [CodeController::class, 'import'])->name('tools.import')->middleware(['auth']);
Route::delete("/tools/delete", [CodeController::class, 'deleteAll'])->name('tools.delete')->middleware(['auth']);

Route::get(env("APP_REDIRECT_BASE", "/r/") . '{code:uuid}', [CodeController::class, 'redirect'])->name('codes.redirect');
