<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIssueRequest;
use App\Http\Requests\UpdateIssuesSequenceRequest;
use App\Models\BoardSection;
use App\Models\Issue;
use App\Services\IssueService;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    private IssueService $issueService;

    public function __construct(IssueService $issueService)
    {
        $this->issueService = $issueService;
    }

    public function store(StoreIssueRequest $request)
    {
        $this->issueService->createIssue($request->validated());

        return redirect()->back();
    }

    public function destroy(Issue $issue)
    {
        $issue->delete();
        return redirect()->back();
    }

    public function updateSequence(BoardSection $boardSection, UpdateIssuesSequenceRequest $request)
    {
        $this->issueService->updateSequence($request->validated('issues'));

        return redirect()->route('project.show', $boardSection->project);
    }

    public function handleMove(Issue $issue, Request $request)
    {
        $request->validate(['board_section_id' => 'required']);

        $issue->update(['board_section_id' => $request->input('board_section_id')]);
        return redirect()->back();
    }
}
