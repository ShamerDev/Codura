<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntryCategory extends Model
{
    protected $fillable = [
        'name',
    ];

    public function entries()
    {
        return $this->hasMany(Entry::class, 'category_id');
    }
}
