<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateIssuesSequenceRequest;
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
            return response()->json($issue);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
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

    public function updateSequence(UpdateIssuesSequenceRequest $request)
    {
        try {
            $this->issueService->updateSequence($request->validated('issues'));

            return response()->json([
                'success' => true
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function handleMove(Issue $issue, Request $request)
    {
        $request->validate(['board_section_id' => 'required', 'sequence' => 'required|integer']);

        try {
            $start = microtime(true);

            $previousBoard = $issue->boardSection;
            $targetBoardId = $request->input('board_section_id');
            $issueSequence = $request->input('sequence');

            // Update the sequence for the previous board
            $previousBoard->issues()->where('sequence', '>', $issue->sequence)->decrement('sequence');

            // Update the sequence for the target board
            BoardSection::find($targetBoardId)
                ->issues()
                ->where('sequence', '>=', $issueSequence)
                ->increment('sequence');

            // Update the issue with the new board section and sequence
            $issue->update([
                'board_section_id' => $targetBoardId,
                'sequence' => $issueSequence,
            ]);

            // $responseData = $issue->project->boardSections->map->issues->flatten(1);
            $responseData = null;
            $end = microtime(true);
            $timeElapsed = $end - $start;
            Log::info("Time elapsed: $timeElapsed seconds");

            return response()->json([
                'success' => true,
                'data' => $responseData
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
