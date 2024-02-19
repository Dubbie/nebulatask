<?php

namespace App\Traits;

trait GeneratesIssueIdTrait
{
    protected static function bootGeneratesIssueIdTrait()
    {
        static::saving(function ($model) {
            // Combine 'code' and 'user_id' to generate the 'id' value
            $model->id = $model->boardSection->project->code . '-' . ($model->boardSection->project->issues()->count() + 1);
        });
    }
}
