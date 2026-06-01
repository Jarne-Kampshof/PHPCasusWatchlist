<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int|null $watchlist_id
 * @property string $title
 * @property string $type
 * @property string $status
 * @property int $year
 * @property int|null $rating
 * @property string|null $image_path
 * @property int|null $tmdb_id
 * @property string|null $tmdb_type
 */
class WatchlistItem extends Model
{
    protected $fillable = [
        'user_id',
        'watchlist_id',
        'title',
        'type',
        'status',
        'year',
        'rating',
        'image_path',
        'tmdb_id',
        'tmdb_type',
    ];

    public function watchlist(): BelongsTo
    {
        return $this->belongsTo(Watchlist::class);
    }
}
