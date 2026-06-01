<?php

use App\Models\User;
use App\Models\Watchlist;
use App\Models\WatchlistItem;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

it('creates a new watchlist for the user', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->post('/watchlists', [
            'name' => 'Favorieten',
        ])
        ->assertRedirect('/watchlist/create?watchlist_id='.Watchlist::query()->where('user_id', $user->id)->where('name', 'Favorieten')->value('id'));

    expect(Watchlist::query()->where('user_id', $user->id)->where('name', 'Favorieten')->exists())->toBeTrue();
});

it('stores a watchlist item in the selected watchlist', function () {
    $user = User::factory()->create();
    $firstWatchlist = Watchlist::create([
        'user_id' => $user->id,
        'name' => 'Films',
    ]);
    $secondWatchlist = Watchlist::create([
        'user_id' => $user->id,
        'name' => 'Series',
    ]);

    actingAs($user)->post('/watchlist', [
        'title' => 'The Matrix',
        'type' => 'film',
        'year' => 1999,
        'watchlist_id' => $secondWatchlist->id,
    ])->assertRedirect('/watchlist');

    expect(WatchlistItem::query()->where('watchlist_id', $secondWatchlist->id)->count())->toBe(1);
    expect(WatchlistItem::query()->where('watchlist_id', $firstWatchlist->id)->count())->toBe(0);
});
