<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Entry extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'title',
        'description',
        'category_id',
        'semester',
        'link',
        'thumbnail_path',
        'is_public',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function images()
    {
        return $this->hasMany(EntryImage::class);
    }

    public function skillTags()
    {
        return $this->hasMany(EntrySkillTag::class);
    }

    public function category()
    {
        return $this->belongsTo(EntryCategory::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'entry_skill_tags')->withPivot('confidence_score');
    }
}
