<?php

namespace App\Traits;

trait GeneratesIssueIdTrait
{
    protected static function bootGeneratesIssueIdTrait()
    {
        static::creating(function ($model) {
            $model->id = $model->boardSection->project->code . '-' . ($model->boardSection->project->issue_counter + 1);
        });

        static::created(function ($model) {
            $model->boardSection->project->increment('issue_counter');
        });
    }
}
