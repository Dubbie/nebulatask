<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class BoardSection extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'sequence', 'project_id', 'is_collapsed', 'type'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function issues()
    {
        return $this->hasMany(Issue::class)->rootIssues()->orderBy('sequence');
    }

    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }

    protected static function booted()
    {
        static::deleted(function (BoardSection $boardSection) {
            // Reorder existing boards
            Log::info("Reordering board sections for project {$boardSection->project_id}");
            $boardSection->project->boardSections()->where('sequence', '>=', $boardSection->sequence)->decrement('sequence');
        });
    }
}
