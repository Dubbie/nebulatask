<?php

namespace App\Traits;

trait GeneratesProjectIdTrait
{
    protected static function bootGeneratesProjectIdTrait()
    {
        static::saving(function ($model) {
            // Combine 'code' and 'user_id' to generate the 'id' value
            $model->id = $model->code . '-' . $model->user_id;
        });
    }
}
