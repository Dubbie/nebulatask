<?php

namespace App\Services;

use App\Models\BoardSection;
use App\Models\Issue;
use Exception;

class IssueService extends ApiService
{
    /**
     * Create a new issue.
     *
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function createIssue(array $data)
    {
        try {
            $issue = tap(Issue::create($this->prepareIssueData($data)))->load('assignee', 'subIssues');
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

            // Adjust sequence within the new board section
            $boardSection->issues()->rootIssues()->where('sequence', '>=', $sequence)->increment('sequence');

            // Adjust sequence within the original board section
            $issue->boardSection->issues()->rootIssues()->where('sequence', '>', $originalPosition)->decrement('sequence');

            // Update the issue with the new board section and sequence
            $issue->update([
                'board_section_id' => $boardSection->id,
                'sequence' => $sequence,
            ]);

            return $this->successResponse($issue);
        } catch (Exception $exception) {
            return $this->errorResponse($exception, 500);
        }
    }

    public function createSubIssue(Issue $issue, string $title)
    {
        try {
            $issue->subIssues()->create([
                'title' => $title,
                'board_section_id' => $issue->board_section_id,
                'sequence' => $issue->subIssues()->max('sequence') + 1,
                'parent_issue_id' => $issue->id,
                'user_id' => $issue->user_id,
            ]);

            $issue->refresh();

            return $this->successResponse($issue);
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
            $backlogBoardSection = $issue->project->boardSections()->type('backlog')->first();
            $doneBoardSection = $issue->project->boardSections()->type('done')->first();

            if ($issue->is_complete) {
                $issue->update(['board_section_id' => $backlogBoardSection->id]);
            } else {
                $issue->update(['board_section_id' => $doneBoardSection->id]);
            }

            return $this->successResponse($issue);
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
}
