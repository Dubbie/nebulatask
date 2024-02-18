<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubIssueRequest;
use App\Models\Issue;
use App\Services\IssueService;
use Illuminate\Support\Facades\Log;

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
            $subIssue->delete();

            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
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
