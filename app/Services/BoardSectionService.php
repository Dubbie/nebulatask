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
            $boardSection = $project->boardSections()->create([
                'name' => $name,
                'sequence' => $project->boardSections()->max('sequence') + 1
            ])->load('issues');

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
            $boardSection->delete();

            return true;
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            return false;
        }
    }
}
