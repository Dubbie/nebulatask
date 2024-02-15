<?php

use App\Http\Controllers\BoardSectionController;
use App\Http\Controllers\ProjectController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/projects', [ProjectController::class, 'index'])->name('project.index');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('project.show');
    Route::post('/projects/store', [ProjectController::class, 'store'])->name('project.store');
    Route::delete('/projects/{project}/destroy', [ProjectController::class, 'destroy'])->name('project.destroy');

    Route::post('/projects/{project}/board-section/store', [BoardSectionController::class, 'store'])->name('board-section.store');
    Route::put('/projects/{project}/board-section/sequence/update', [BoardSectionController::class, 'updateSequence'])->name('board-section.sequence.update');
    Route::delete('/projects/{project}/board-section/{boardSection}/destroy', [BoardSectionController::class, 'destroy'])->name('board-section.destroy');
});
