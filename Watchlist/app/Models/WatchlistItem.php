<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WatchlistItem extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'type',
        'status',
        'year',
        'rating',
        'image_path',
    ];
}
