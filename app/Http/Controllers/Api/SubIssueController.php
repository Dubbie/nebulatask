<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubIssueRequest;
use App\Models\Issue;
use App\Services\IssueService;

class SubIssueController extends Controller
{
    private IssueService $issueService;

    public function __construct(IssueService $issueService)
    {
        $this->issueService = $issueService;
    }

    public function destroy(Issue $subIssue)
    {
        try {
            $parentId = $subIssue->parent_issue_id;
            $subIssue->delete();

            $parent = Issue::find($parentId);

            return response()->json([
                'success' => true,
                'data' => $parent,
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ]);
        }
    }

    public function toggleComplete(Issue $subIssue)
    {
        return $this->issueService->toggleComplete($subIssue);
    }

    public function store(Issue $issue, StoreSubIssueRequest $request)
    {
        $data = $request->validated();

        return $this->issueService->createSubIssue($issue, $data['title']);
    }
}
