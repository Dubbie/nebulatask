<?php

namespace App\Services;

use App\Models\BoardSection;
use Exception;
use Illuminate\Support\Facades\Log;

class BoardSectionService
{
    public function updateSequence(array $boardSections)
    {
        try {
            foreach ($boardSections as $sequenceIndex => $boardSectionData) {
                $boardSection = BoardSection::find($boardSectionData['id']);
                $boardSection->update(['sequence' => $sequenceIndex]);
            }

            return true;
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return false;
        }
    }
}
