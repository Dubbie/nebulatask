<?php

namespace App\Services;

use App\Models\BoardSection;
use App\Models\Issue;
use Exception;
use Illuminate\Support\Facades\Log;

class IssueService
{
    const STATUS_INCOMPLETE = 1;
    const STATUS_COMPLETE = 4;

    /**
     * Create a new issue.
     *
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function createIssue(array $data)
    {
        try {
            $issue = tap(Issue::create($this->prepareIssueData($data)))->load('status', 'assignee', 'subIssues');
            return $this->successResponse($issue);
        } catch (Exception $exception) {
            return $this->errorResponse($exception);
        }
    }

    /**
     * Delete an issue.
     *
     * @param \App\Models\Issue $issue
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteIssue(Issue $issue)
    {
        try {
            $issue->delete();
            return $this->successResponse();
        } catch (Exception $exception) {
            return $this->errorResponse($exception, 500);
        }
    }

    /**
     * Update the sequence of specific issue and adjust the position in the list.
     *
     * @param \App\Models\Issue $issue
     * @param int $sequence
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSequence(Issue $issue, int $sequence)
    {
        try {
            $board = $issue->boardSection;
            $originalPosition = $issue->sequence;

            $this->adjustIssueSequence($board, $originalPosition, $sequence);

            $issue->update(['sequence' => $sequence]);

            return $this->successResponse($issue);
        } catch (Exception $exception) {
            return $this->errorResponse($exception, 500);
        }
    }

    /**
     * Move an issue to a different board section and adjust the sequence.
     *
     * @param \App\Models\Issue $issue
     * @param \App\Models\BoardSection $boardSection
     * @param int $sequence
     * @return \Illuminate\Http\JsonResponse
     */
    public function moveIssueToBoardSection(Issue $issue, BoardSection $boardSection, int $sequence)
    {
        try {
            $originalPosition = $issue->sequence;

            // Move the issue to the new board section
            $boardSection->issues()->rootIssues()->where('sequence', '>=', $sequence)->increment('sequence');

            // Adjust sequence within the original board section
            $issue->boardSection->issues()->rootIssues()->where('sequence', '>', $originalPosition)->decrement('sequence');

            // Update the issue with the new board section and sequence
            $issue->update([
                'board_section_id' => $boardSection->id,
                'sequence' => $sequence,
            ]);

            return $this->successResponse();
        } catch (Exception $exception) {
            return $this->errorResponse($exception, 500);
        }
    }

    /**
     * Create a sub-issue for a given parent issue.
     *
     * @param int $parentIssueId
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function createSubIssue(int $parentIssueId, array $data)
    {
        try {
            $subIssue = tap(Issue::createSubIssue($parentIssueId, $this->prepareIssueData($data)))->load('status', 'assignee');
            return $this->successResponse($subIssue);
        } catch (Exception $exception) {
            return $this->errorResponse($exception, 500);
        }
    }

    /**
     * Toggle the completion status of an issue.
     *
     * @param \App\Models\Issue $issue
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleComplete(Issue $issue)
    {
        try {
            $issue->update(['issue_status_id' => $issue->issue_status_id == self::STATUS_INCOMPLETE ? self::STATUS_COMPLETE : self::STATUS_INCOMPLETE]);
            return $this->successResponse();
        } catch (Exception $exception) {
            return $this->errorResponse($exception, 500);
        }
    }

    /**
     * Prepare issue data by calculating the next sequence.
     *
     * @param array $data
     * @return array
     */
    protected function prepareIssueData(array $data)
    {
        $boardSection = $data['board_section_id'];
        $nextInSequence = $this->getNextSequence($boardSection);
        return array_merge($data, ['sequence' => $nextInSequence]);
    }

    /**
     * Get the next sequence for a given board section.
     *
     * @param mixed $boardSection
     * @return mixed
     */
    protected function getNextSequence($boardSection)
    {
        return Issue::where('board_section_id', $boardSection)->rootIssues()->max('sequence') + 1;
    }

    /**
     * Adjust the sequence of issues within a board section.
     *
     * @param \App\Models\BoardSection $board
     * @param int $originalPosition
     * @param int $newPosition
     * @return void
     */
    protected function adjustIssueSequence(BoardSection $board, int $originalPosition, int $newPosition)
    {
        if ($originalPosition > $newPosition) {
            // Move the issue up in the list
            $board->issues()->rootIssues()->whereBetween('sequence', [$newPosition, $originalPosition - 1])->increment('sequence');
        } elseif ($originalPosition < $newPosition) {
            // Move the issue down in the list
            $board->issues()->rootIssues()->whereBetween('sequence', [$originalPosition + 1, $newPosition])->decrement('sequence');
        }
    }

    /**
     * Generate a success JSON response.
     *
     * @param mixed $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successResponse($data = null)
    {
        return response()->json(['success' => true, 'data' => $data]);
    }

    /**
     * Generate an error JSON response.
     *
     * @param \Exception $exception
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse(Exception $exception, $statusCode = 400)
    {
        Log::error($exception->getMessage());
        return response()->json(['success' => false, 'message' => $exception->getMessage()], $statusCode);
    }
}
