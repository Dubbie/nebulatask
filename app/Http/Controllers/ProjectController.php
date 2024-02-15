<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            $data['description'] ?? null,
            Auth::user()->id
        );

        return redirect()->back();
    }

    public function show(Project $project)
    {
        return Inertia::render('Project/Show', [
            'project' => $project->load('boardSections')
        ]);
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->back();
    }
}
