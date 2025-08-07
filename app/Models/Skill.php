<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable = [
        'name',
    ];

    public function entrySkillTags()
    {
        return $this->hasMany(EntrySkillTag::class);
    }

    public function skillCategoryLinks()
    {
        return $this->hasMany(SkillCategoryLink::class);
    }

    public function categories()
    {
        return $this->belongsToMany(SkillCategory::class, 'skill_category_link');
    }

    public function entries()
    {
        return $this->belongsToMany(Entry::class, 'entry_skill_tags')->withPivot('confidence_score');
    }
}
