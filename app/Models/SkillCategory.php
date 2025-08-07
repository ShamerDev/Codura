<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkillCategory extends Model
{
    protected $fillable = [
        'name',
    ];

    public function skillCategoryLinks()
    {
        return $this->hasMany(SkillCategoryLink::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'skill_category_link');
    }
}
