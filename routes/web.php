<?php

use App\Http\Controllers\Api\IssueController as IssueApiController;
use App\Http\Controllers\Api\ProjectController as ProjectApiController;
use App\Http\Controllers\Api\SubIssueController;
use App\Http\Controllers\Api\BoardSectionController as BoardSectionApiController;
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

    // Inertia routes
    Route::get('/projects', [ProjectController::class, 'index'])->name('project.index');
    Route::get('/projects/{project}/issues/{issue}', [ProjectController::class, 'show'])->name('project.show.issue');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('project.show');
    Route::post('/projects/store', [ProjectController::class, 'store'])->name('project.store');
    Route::delete('/projects/{project}/destroy', [ProjectController::class, 'destroy'])->name('project.destroy');


    // FE API Routes
    Route::get('/api/project/{project}/issues', [IssueApiController::class, 'fetchByProject'])->name('api.issue.fetch-by-project');
    Route::post('/api/issue/{issue}/sub-issue/store', [IssueApiController::class, 'storeSubIssue'])->name('api.issue.sub-issue.store');
    Route::put('/api/issue/{issue}/description/update', [IssueApiController::class, 'updateDescription'])->name('api.issue.description.update');
    Route::put('/api/issue/{issue}/title/update', [IssueApiController::class, 'updateTitle'])->name('api.issue.title.update');
    Route::put('/api/issue/{issue}/status/update', [IssueApiController::class, 'updateStatus'])->name('api.issue.status.update');
    Route::get('/api/issue/{issue}', [IssueApiController::class, 'fetch'])->name('api.issue.fetch');
    Route::put('/api/issue/{issue}/moved', [IssueApiController::class, 'handleMove'])->name('api.issue.move');
    Route::delete('/api/issue/{issue}/destroy', [IssueApiController::class, 'destroy'])->name('api.issue.destroy');
    Route::put('/api/issue/{issue}/update-sequence', [IssueApiController::class, 'handleUpdateSequence'])->name('api.issue.sequence.update');
    Route::post('/api/issue/store', [IssueApiController::class, 'store'])->name('api.issue.store');

    Route::post('/api/issue/{issue}/sub-issue/store', [SubIssueController::class, 'store'])->name('api.sub-issue.store');
    Route::delete('/api/sub-issue/{subIssue}/destroy', [SubIssueController::class, 'destroy'])->name('api.sub-issue.destroy');
    Route::put('/api/sub-issue/{subIssue}/complete/toggle', [SubIssueController::class, 'toggleComplete'])->name('api.sub-issue.complete.toggle');

    Route::get('/api/project/{project}/board-sections', [ProjectApiController::class, 'getBoardSections'])->name('api.project.board-sections');
    Route::post('/api/board-section/store', [BoardSectionApiController::class, 'store'])->name('api.board-section.store');
    Route::put('/api/board-section/{boardSection}/update', [BoardSectionApiController::class, 'update'])->name('api.board-section.update');
    Route::put('/api/board-section/{boardSection}/move', [BoardSectionApiController::class, 'handleMove'])->name('api.board-section.move');
    Route::delete('/api/board-section/{boardSection}/destroy', [BoardSectionApiController::class, 'destroy'])->name('api.board-section.destroy');
});
