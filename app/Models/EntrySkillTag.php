<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntrySkillTag extends Model
{
    protected $fillable = [
        'entry_id',
        'skill_id',
        'confidence_score',
    ];

    public function entry()
    {
        return $this->belongsTo(Entry::class);
    }

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }
}
