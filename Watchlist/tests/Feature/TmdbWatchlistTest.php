<?php

use App\Models\User;
use App\Models\WatchlistItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

it('shows tmdb search results on the add page', function () {
    config(['services.tmdb.key' => 'test-key']);

    Http::fake([
        'api.themoviedb.org/3/search/multi*' => Http::response([
            'results' => [
                [
                    'media_type' => 'movie',
                    'id' => 603,
                    'title' => 'The Matrix',
                    'release_date' => '1999-03-31',
                    'poster_path' => '/matrix.jpg',
                ],
                [
                    'media_type' => 'tv',
                    'id' => 1396,
                    'name' => 'Breaking Bad',
                    'first_air_date' => '2008-01-20',
                    'poster_path' => null,
                ],
                [
                    'media_type' => 'person',
                    'id' => 1,
                    'name' => 'Ignored Person',
                ],
            ],
        ], 200),
    ]);

    $user = User::factory()->create();

    $response = actingAs($user)->get('/watchlist/create?query=matrix');

    $response->assertOk();
    $response->assertSee('The Matrix');
    $response->assertSee('Breaking Bad');
    $response->assertDontSee('Ignored Person');
});

it('stores a tmdb selection with tmdb details', function () {
    config(['services.tmdb.key' => 'test-key']);

    Http::fake([
        'api.themoviedb.org/3/movie/603*' => Http::response([
            'id' => 603,
            'title' => 'The Matrix',
            'release_date' => '1999-03-31',
            'poster_path' => '/matrix.jpg',
        ], 200),
    ]);

    $user = User::factory()->create();

    actingAs($user)->post('/watchlist', [
        'title' => 'Wrong title',
        'type' => 'film',
        'year' => 2000,
        'tmdb_id' => 603,
        'tmdb_type' => 'film',
    ])->assertRedirect('/watchlist');

    $item = WatchlistItem::query()->first();

    expect($item)->not()->toBeNull();
    expect($item->title)->toBe('The Matrix');
    expect($item->type)->toBe('film');
    expect($item->year)->toBe(1999);
    expect($item->tmdb_id)->toBe(603);
    expect($item->tmdb_type)->toBe('film');
    expect($item->image_path)->toBe('https://image.tmdb.org/t/p/w342/matrix.jpg');
});