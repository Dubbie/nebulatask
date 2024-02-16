<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssueStatus extends Model
{
    use HasFactory;

    protected $appends = [
        'formatted_name'
    ];

    protected function formattedName(): Attribute
    {
        return new Attribute(
            get: fn () => str_replace('_', ' ', ucfirst(strtolower($this->name))),
        );
    }
}
