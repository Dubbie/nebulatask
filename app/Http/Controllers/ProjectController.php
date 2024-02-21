<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Models\Issue;
use App\Models\IssueStatus;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class ProjectController extends Controller
{
    private ProjectService $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    public function index()
    {
        return Inertia::render('Project/Index', [
            'projects' => Auth::user()->projects
        ]);
    }

    public function store(StoreProjectRequest $request)
    {
        $data = $request->validated();

        $response = $this->projectService->createProject(
            $data['name'],
            $data['code'],
            $data['description'] ?? null,
            Auth::user()->id
        )->getData(true);

        return redirect()->route('project.show', ['project' => $response['data']['id']]);
    }

    public function show(Project $project, string $issueId = null)
    {
        // Save to recents
        $recents = Session::get('recents', []);
        if (!in_array($project->id, $recents)) {
            $recents[] = $project->id;
            Session::put('recents', $recents);
        }

        return Inertia::render('Project/Show', [
            'project' => $project,
            'boardSections' => $project->boardSections,
            'issueId' => $issueId
        ]);
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->to(route('project.index'));
    }

    public function settings(Project $project)
    {
        return Inertia::render('Project/Settings', [
            'project' => $project
        ]);
    }
}
