<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBoardSection;
use App\Http\Requests\UpdateBoardSectionsSequence;
use App\Models\BoardSection;
use App\Models\Project;
use App\Services\BoardSectionService;
use Illuminate\Http\Request;

class BoardSectionController extends Controller
{
    private BoardSectionService $boardSectionService;

    public function __construct(BoardSectionService $boardSectionService)
    {
        $this->boardSectionService = $boardSectionService;
    }

    public function store(Project $project, StoreBoardSection $request)
    {
        $project->boardSections()->create($request->validated());

        return redirect()->route('project.show', $project);
    }

    public function destroy(Project $project, BoardSection $boardSection)
    {
        $boardSection->delete();

        return redirect()->route('project.show', $project);
    }

    public function updateSequence(Project $project, UpdateBoardSectionsSequence $request)
    {
        $this->boardSectionService->updateSequence($request->validated('board_sections'));

        return redirect()->route('project.show', $project);
    }
}
