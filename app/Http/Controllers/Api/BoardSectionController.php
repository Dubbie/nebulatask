<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MoveBoardSectionRequest;
use App\Http\Requests\StoreBoardSectionRequest;
use App\Models\BoardSection;
use App\Models\Project;
use App\Services\BoardSectionService;

class BoardSectionController extends Controller
{
    private BoardSectionService $boardSectionService;

    public function __construct(BoardSectionService $boardSectionService)
    {
        $this->boardSectionService = $boardSectionService;
    }

    public function storeBoardSection(StoreBoardSectionRequest $request)
    {
        $data = $request->validated();
        $project = Project::find($data['project_id']);

        return $this->boardSectionService->create($project, $data['name']);
    }

    public function destroyBoardSection(BoardSection $boardSection)
    {
        return $this->boardSectionService->delete($boardSection);
    }

    public function handleMove(BoardSection $boardSection, MoveBoardSectionRequest $request)
    {
        $data = $request->validated();

        return $this->boardSectionService->updateSequence($boardSection, $data['sequence']);
    }
}
