<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $type
 * @property string $status
 * @property int $year
 * @property int|null $rating
 * @property string|null $image_path
 */
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
