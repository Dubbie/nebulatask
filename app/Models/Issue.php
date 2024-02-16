<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'due_date',
        'user_id',
        'issue_status_id',
        'board_section_id',
        'sequence'
    ];

    protected $with = [
        'status',
        'assignee'
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

    public function boardSection()
    {
        return $this->belongsTo(BoardSection::class, 'board_section_id');
    }

    public function project()
    {
        return $this->hasOneThrough(Project::class, BoardSection::class, 'id', 'id', 'board_section_id', 'project_id');
    }
}
