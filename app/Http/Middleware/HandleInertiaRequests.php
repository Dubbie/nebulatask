<?php

namespace App\Http\Middleware;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'tiny_api_key' => config('tiny.api_key'),
            'recent_projects' => fn () => $this->getRecentProjects(),
        ]);
    }

    /**
     * Get recent projects.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getRecentProjects()
    {
        // $projects will be an array of project IDs
        $projects = Session::get('recents', []);
        if ($projects) {
            // Check if any project is not in the DB, that means it was deleted
            $deletedProjects = array_diff($projects, Project::pluck('id')->toArray());
            if (!empty($deletedProjects)) {
                // Remove from session
                $projects = array_diff($projects, $deletedProjects);
                Session::put('recents', $projects);
            }

            // Return projects
            return Project::whereIn('id', $projects)->get(['id', 'name'])->makeHidden('lead')->toArray();
        }

        return [];
    }
}
