<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBoardSectionRequest;
use App\Models\BoardSection;
use App\Models\Issue;
use App\Models\Project;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    public function getBoardSections(Project $project)
    {
        return $project->boardSections->load('issues');
    }
}
