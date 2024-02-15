<?php

namespace App\Services;

use App\Models\Project;
use Exception;

class ProjectService
{
    public function createProject(string $name, ?string $description, int $userId)
    {
        try {
            $project = Project::create([
                'name' => $name,
                'description' => $description,
                'user_id' => $userId
            ]);

            return response()->json($project, 201);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}