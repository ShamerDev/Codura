<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkillCategoryLink extends Model
{
    protected $fillable = [
        'skill_id',
        'skill_category_id',
    ];

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }

    public function category()
    {
        return $this->belongsTo(SkillCategory::class, 'skill_category_id');
    }
}
