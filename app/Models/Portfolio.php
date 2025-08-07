<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'public_link',
        'slug',
        'qr_code',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
