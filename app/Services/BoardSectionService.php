<?php

namespace App\Services;

use App\Models\BoardSection;
use App\Models\Project;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BoardSectionService
{
    public function updateSequence(BoardSection $boardSection, int $newPosition)
    {
        $originalPosition = $boardSection->sequence;
        DB::beginTransaction();

        try {
            // Move previous and next board sections
            if ($originalPosition < $newPosition) {
                BoardSection::where('project_id', $boardSection->project_id)
                    ->where('sequence', '>', $originalPosition)
                    ->where('sequence', '<=', $newPosition)
                    ->decrement('sequence');
            } else {
                BoardSection::where('project_id', $boardSection->project_id)
                    ->where('sequence', '>=', $newPosition)
                    ->where('sequence', '<', $originalPosition)
                    ->increment('sequence');
            }

            $boardSection->update([
                'sequence' => $newPosition
            ]);

            DB::commit();

            $this->updateBoardTypes($boardSection->project);

            return response()->json([
                'success' => true
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(Project $project, string $name)
    {
        try {
            $nextInSequence = $project->boardSections()->count() > 0 ? $project->boardSections()->max('sequence') + 1 : 0;

            $boardSection = $project->boardSections()->create([
                'name' => $name,
                'sequence' => $nextInSequence
            ])->load('issues');

            $this->updateBoardTypes($boardSection->project);

            return response()->json([
                'success' => true,
                'data' => $boardSection
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function delete(BoardSection $boardSection)
    {
        try {
            $project = $boardSection->project;
            $boardSection->delete();

            $this->updateBoardTypes($project);

            return true;
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            return false;
        }
    }

    protected function updateBoardTypes(Project $project)
    {
        $projectId = $project->id;

        // Update backlog directly without fetching the model instance
        $project->boardSections()->where('sequence', 0)->update(['type' => 'BACKLOG']);

        // Update done directly without fetching the model instance
        $project->boardSections()->where('sequence', $project->boardSections()->max('sequence'))->update(['type' => 'DONE']);

        // Update intermediate directly without fetching the model instances
        $project->boardSections()
            ->where('sequence', '>', 0)
            ->where('sequence', '<', $project->boardSections()->max('sequence'))
            ->update(['type' => 'INTERMEDIATE']);
    }
}
