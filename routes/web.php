<?php

use App\Http\Controllers\Api\IssueController as IssueApiController;
use App\Http\Controllers\Api\SubIssueController;
use App\Http\Controllers\BoardSectionController;
use App\Http\Controllers\IssueController;
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

    Route::post('/issue/store', [IssueController::class, 'store'])->name('issue.store');
    Route::delete('/issue/{issue}}destroy', [IssueController::class, 'destroy'])->name('issue.destroy');
    Route::put('/board-section/{boardSection}/issues/sequence/update', [IssueController::class, 'updateSequence'])->name('issue.sequence.update');
    Route::put('/issue/{issue}/moved', [IssueController::class, 'handleMove'])->name('issue.move');

    Route::get('/api/project/{project}/issues', [IssueApiController::class, 'fetchByProject'])->name('api.issue.fetch-by-project');
    Route::put('/api/issues/sequence/update', [IssueApiController::class, 'updateSequence'])->name('api.issue.sequence.update');
    Route::post('/api/issue/{issue}/sub-issue/store', [IssueApiController::class, 'storeSubIssue'])->name('api.issue.sub-issue.store');
    Route::put('/api/issue/{issue}/description/update', [IssueApiController::class, 'updateDescription'])->name('api.issue.description.update');
    Route::put('/api/issue/{issue}/title/update', [IssueApiController::class, 'updateTitle'])->name('api.issue.title.update');
    Route::put('/api/issue/{issue}/status/update', [IssueApiController::class, 'updateStatus'])->name('api.issue.status.update');
    Route::get('/api/issue/{issue}', [IssueApiController::class, 'fetch'])->name('api.issue.fetch');
    Route::put('/api/issue/{issue}/moved', [IssueApiController::class, 'handleMove'])->name('api.issue.move');

    Route::delete('/api/sub-issue/{subIssue}/destroy', [SubIssueController::class, 'destroy'])->name('api.sub-issue.destroy');
});
