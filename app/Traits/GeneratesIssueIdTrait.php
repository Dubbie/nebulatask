<?php

namespace App\Traits;

trait GeneratesIssueIdTrait
{
    protected static function bootGeneratesIssueIdTrait()
    {
        static::saving(function ($model) {
            $model->id = $model->boardSection->project->code . '-' . ($model->boardSection->project->issues()->count() + 1);
        });
    }
}
