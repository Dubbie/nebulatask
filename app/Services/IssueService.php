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
}
