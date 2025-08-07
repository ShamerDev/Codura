<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntryImage extends Model
{
    protected $fillable = [
        'entry_id',
        'image_path',
        'position',
    ];

    public function entry()
    {
        return $this->belongsTo(Entry::class);
    }
}
