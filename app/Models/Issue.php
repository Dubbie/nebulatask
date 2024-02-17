<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Issue extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'due_date',
        'user_id',
        'issue_status_id',
        'parent_issue_id',
        'board_section_id',
        'sequence'
    ];

    protected $with = [
        'status',
        'assignee',
        'subIssues',
    ];

    protected $appends = [
        'code'
    ];

    protected function code(): Attribute
    {
        $code = $this->project->code;

        return Attribute::make(
            get: fn () => $code . '-' . $this->id
        );
    }

    public function status()
    {
        return $this->hasOne(IssueStatus::class, 'id', 'issue_status_id');
    }

    public function assignee()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function subIssues()
    {
        return $this->hasMany(Issue::class, 'parent_issue_id')->orderBy('sequence');
    }

    public function boardSection()
    {
        return $this->belongsTo(BoardSection::class, 'board_section_id');
    }

    public function project()
    {
        return $this->hasOneThrough(Project::class, BoardSection::class, 'id', 'id', 'board_section_id', 'project_id');
    }

    public function scopeRootIssues($query)
    {
        return $query->whereNull('parent_issue_id');
    }

    protected static function booted()
    {
        static::deleted(function (Issue $issue) {
            // Reorder existing issues
            Log::info("Reordering issues for board section {$issue->boardSection->id}");
            $issue->boardSection->issues()->rootIssues()->where('sequence', '>=', $issue->sequence)->decrement('sequence');
        });
    }
}
