<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
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
}
