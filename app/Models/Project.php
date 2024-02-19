<?php

namespace App\Models;

use App\Traits\GeneratesProjectIdTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory, GeneratesProjectIdTrait;

    public $incrementing = false;

    protected $fillable = [
        'name',
        'code',
        'description',
        'user_id'
    ];

    protected $with = [
        'lead'
    ];

    public function lead()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function boardSections()
    {
        return $this->hasMany(BoardSection::class)->orderBy('sequence');
    }

    public function issues()
    {
        return $this->hasManyThrough(Issue::class, BoardSection::class);
    }
}
