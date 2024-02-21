<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MoveIssueRequest;
use App\Http\Requests\StoreIssueRequest;
use App\Http\Requests\StoreSubIssueRequest;
use App\Http\Requests\UpdateIssueSequenceRequest;
use App\Models\BoardSection;
use App\Models\Issue;
use App\Models\Project;
use App\Services\IssueService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IssueController extends Controller
{
    private IssueService $issueService;

    public function __construct(IssueService $issueService)
    {
        $this->issueService = $issueService;
    }

    public function updateDescription(Issue $issue, Request $request)
    {
        $request->validate([
            'description' => 'nullable'
        ]);

        try {
            $issue->update(['description' => $request->input('description')]);
            return response()->json($issue);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function updateStatus(Issue $issue, Request $request)
    {
        $request->validate([
            'issue_status_id' => 'required|exists:issue_statuses,id'
        ]);

        try {
            $issue->update(['issue_status_id' => $request->input('issue_status_id')]);
            return response()->json($issue);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function updateTitle(Issue $issue, Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255'
        ]);

        try {
            $issue->update(['title' => $request->input('title')]);
            return response()->json([
                'success' => true,
                'data' => $issue,
                'message' => 'Issue updated successfully',
            ]);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateDueDate(Issue $issue, Request $request)
    {
        $request->validate([
            'due_date' => 'nullable|date|after:today'
        ]);

        try {
            $issue->update(['due_date' => $request->input('due_date')]);
            return response()->json([
                'success' => true,
                'data' => $issue,
                'message' => 'Issue updated successfully',
            ]);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function fetchByProject(Project $project)
    {
        return $project->boardSections->map->issues->flatten(1);
    }

    public function fetch(Issue $issue)
    {
        return response()->json($issue);
    }

    public function handleUpdateSequence(Issue $issue, UpdateIssueSequenceRequest $request)
    {
        $data = $request->validated();

        return $this->issueService->updateSequence($issue, $data['sequence']);
    }

    public function handleMove(Issue $issue, MoveIssueRequest $request)
    {
        $data = $request->validated();

        return $this->issueService->moveIssueToBoardSection($issue, BoardSection::find($data['board_section_id']), $data['sequence']);
    }

    public function store(StoreIssueRequest $storeIssueRequest)
    {
        return $this->issueService->createIssue($storeIssueRequest->validated());
    }

    public function destroy(Issue $issue)
    {
        return $this->issueService->deleteIssue($issue);
    }

    public function storeSubIssue(Issue $issue, StoreSubIssueRequest $request)
    {
        $data = $request->validated();

        return $this->issueService->createSubIssue($issue->id, $data);
    }
}
