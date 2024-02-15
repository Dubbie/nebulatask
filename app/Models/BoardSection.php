<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardSection extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'sequence', 'project_id', 'is_collapsed'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
