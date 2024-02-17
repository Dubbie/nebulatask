<?php

namespace App\Services;

use App\Models\Issue;
use Exception;
use Illuminate\Support\Facades\Log;

class IssueService
{
    public function createIssue(array $data)
    {
        Issue::create($data);
    }

    public function updateSequence(array $issues)
    {
        try {
            foreach ($issues as $sequenceIndex => $issueData) {
                $issue = Issue::find($issueData['id']);
                $issue->update(['sequence' => $sequenceIndex]);
            }

            return true;
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return false;
        }
    }

    public function createSubIssue(int $parentIssueId, array $data)
    {
        $parentIssue = Issue::find($parentIssueId);
        Issue::create(array_merge($data, [
            'parent_issue_id' => $parentIssueId,
            'sequence' => Issue::where('parent_issue_id', $parentIssueId)->max('sequence') + 1,
            'user_id' => auth()->user()->id,
            'issue_status_id' => 1,
            'project_id' => $parentIssue->project_id,
            'board_section_id' => $parentIssue->board_section_id,
        ]));
    }

    public function toggleComplete(Issue $issue)
    {
        Log::info($issue);
        try {
            $issue->update([
                'issue_status_id' => $issue->issue_status_id == 1 ? 4 : 1
            ]);

            return response()->json([
                'success' => true
            ]);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 500);
        }
    }
}
