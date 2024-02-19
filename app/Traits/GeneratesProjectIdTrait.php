<?php

namespace App\Traits;

trait GeneratesProjectIdTrait
{
    protected static function bootGeneratesProjectIdTrait()
    {
        static::creating(function ($model) {
            $model->id = $model->code . '-' . $model->user_id;
        });
    }
}
