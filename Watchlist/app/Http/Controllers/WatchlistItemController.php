<?php

namespace App\Http\Controllers;

use App\Models\WatchlistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class WatchlistItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Toon alleen items van de ingelogde gebruiker.
        $query = WatchlistItem::where('user_id', Auth::id());

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        // Eenvoudige filters op status en type.
        if ($request->filled('status') && in_array($request->status, ['niet_bekeken', 'bekeken'], true)) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type') && in_array($request->type, ['film', 'serie'], true)) {
            $query->where('type', $request->type);
        }

        $items = $query->orderBy('updated_at', 'desc')->get();

        return view('watchlist', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $searchQuery = trim((string) $request->query('query', ''));
        $searchResults = [];
        $searchError = null;

        if ($searchQuery !== '') {
            try {
                $searchResults = $this->searchTmdb($searchQuery);

                if ($searchResults === []) {
                    $searchError = 'Geen resultaten gevonden op TMDb.';
                }
            } catch (\Throwable $throwable) {
                Log::warning('TMDb search failed', [
                    'query' => $searchQuery,
                    'message' => $throwable->getMessage(),
                ]);
                $searchError = 'TMDb zoeken is tijdelijk niet beschikbaar.';
            }
        }

        return view('toevoegpagina', [
            'query' => $searchQuery,
            'searchResults' => $searchResults,
            'searchError' => $searchError,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Valideer formulierdata van de toevoegpagina.
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:film,serie',
            'year' => 'required|integer|min:1888|max:2100',
            'image' => 'nullable|image|max:4096',
            'image_path' => 'nullable|string|max:2048',
            'tmdb_id' => 'nullable|integer|min:1',
            'tmdb_type' => 'nullable|in:film,serie',
        ]);

        // Upload afbeelding (optioneel) en bewaar de publieke URL.
        $imageUrl = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('watchlist-images', 'public');
            $imageUrl = Storage::url($path);
        }

        $tmdbData = null;
        if ($request->filled('tmdb_id') && $request->filled('tmdb_type')) {
            try {
                $tmdbData = $this->fetchTmdbDetails((string) $validated['tmdb_type'], (int) $validated['tmdb_id']);
            } catch (\Throwable $throwable) {
                Log::warning('TMDb detail lookup failed', [
                    'tmdb_id' => $validated['tmdb_id'] ?? null,
                    'tmdb_type' => $validated['tmdb_type'] ?? null,
                    'message' => $throwable->getMessage(),
                ]);
                $tmdbData = null;
            }
        }

        $imageUrl ??= $validated['image_path'] ?? ($tmdbData['image_path'] ?? null);

        WatchlistItem::create([
            'user_id' => Auth::id(),
            'title' => $tmdbData['title'] ?? $validated['title'],
            'type' => $tmdbData['type'] ?? $validated['type'],
            'status' => 'niet_bekeken',
            'year' => $tmdbData['year'] ?? $validated['year'],
            'rating' => null,
            'image_path' => $imageUrl,
            'tmdb_id' => $validated['tmdb_id'] ?? null,
            'tmdb_type' => $validated['tmdb_type'] ?? null,
        ]);

        return to_route('watchlist.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(WatchlistItem $watchlistItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WatchlistItem $watchlistItem)
    {
        // Beveiliging: gebruiker mag alleen eigen item bewerken.
        if ($watchlistItem->user_id !== Auth::id()) {
            abort(403);
        }

        return view('bewerkpagina', compact('watchlistItem'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WatchlistItem $watchlistItem)
    {
        // Beveiliging: gebruiker mag alleen eigen item updaten.
        if ($watchlistItem->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:film,serie',
            'status' => 'required|in:niet_bekeken,bekeken',
            'year' => 'required|integer|min:1888|max:2100',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        if ($validated['status'] === 'niet_bekeken') {
            // Rating hoort leeg te zijn wanneer item niet bekeken is.
            $validated['rating'] = null;
        }

        $watchlistItem->update($validated);

        return to_route('watchlist.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WatchlistItem $watchlistItem)
    {
        // Beveiliging: gebruiker mag alleen eigen item verwijderen.
        if ($watchlistItem->user_id !== Auth::id()) {
            abort(403);
        }

        $watchlistItem->delete();

        return to_route('watchlist.index');
    }

    /**
     * Zoek films en series via TMDb.
     */
    private function searchTmdb(string $query): array
    {
        $apiKey = config('services.tmdb.key');

        if (! $apiKey) {
            return [];
        }

        $response = $this->tmdbRequest()->get($this->tmdbBaseUrl() . '/search/multi', [
            'api_key' => $apiKey,
            'query' => $query,
            'include_adult' => false,
            'language' => 'nl-NL',
        ])->throw();

        return collect($response->json('results', []))
            ->filter(fn (array $item) => in_array($item['media_type'] ?? null, ['movie', 'tv'], true))
            ->map(fn (array $item) => $this->formatTmdbSearchResult($item))
            ->values()
            ->all();
    }

    /**
     * Haal detaildata op voor een geselecteerd TMDb-resultaat.
     */
    private function fetchTmdbDetails(string $tmdbType, int $tmdbId): array
    {
        $apiKey = config('services.tmdb.key');

        if (! $apiKey) {
            return [];
        }

        $endpoint = $tmdbType === 'serie' ? '/tv/' . $tmdbId : '/movie/' . $tmdbId;

        $response = $this->tmdbRequest()->get($this->tmdbBaseUrl() . $endpoint, [
            'api_key' => $apiKey,
            'language' => 'nl-NL',
        ])->throw();

        return $this->formatTmdbDetailResult($response->json(), $tmdbType, $tmdbId);
    }

    /**
     * Normaliseer een TMDb zoekresultaat naar de velden van de watchlist.
     */
    private function formatTmdbSearchResult(array $item): array
    {
        $type = $this->mapTmdbType($item['media_type'] ?? null);

        return [
            'tmdb_id' => $item['id'] ?? null,
            'tmdb_type' => $type,
            'title' => $item['title'] ?? $item['name'] ?? 'Onbekende titel',
            'year' => $this->extractYear($item['release_date'] ?? $item['first_air_date'] ?? null),
            'image_path' => $this->buildTmdbPosterUrl($item['poster_path'] ?? null),
        ];
    }

    /**
     * Normaliseer detaildata van TMDb.
     */
    private function formatTmdbDetailResult(array $item, string $tmdbType, int $tmdbId): array
    {
        return [
            'tmdb_id' => $tmdbId,
            'tmdb_type' => $tmdbType,
            'title' => $item['title'] ?? $item['name'] ?? 'Onbekende titel',
            'type' => $tmdbType,
            'year' => $this->extractYear($item['release_date'] ?? $item['first_air_date'] ?? null),
            'image_path' => $this->buildTmdbPosterUrl($item['poster_path'] ?? null),
        ];
    }

    /**
     * Zet TMDb media types om naar watchlist types.
     */
    private function mapTmdbType(?string $mediaType): ?string
    {
        return match ($mediaType) {
            'movie' => 'film',
            'tv' => 'serie',
            default => null,
        };
    }

    /**
     * Extraheer het jaar uit een datumstring.
     */
    private function extractYear(?string $date): int
    {
        if (! $date) {
            return now()->year;
        }

        return (int) substr($date, 0, 4);
    }

    /**
     * Bouw een volledige poster-URL op voor TMDb.
     */
    private function buildTmdbPosterUrl(?string $posterPath): ?string
    {
        if (! $posterPath) {
            return null;
        }

        return rtrim(config('services.tmdb.image_base_url', 'https://image.tmdb.org/t/p/w342'), '/') . $posterPath;
    }

    /**
     * Basis-URL voor TMDb.
     */
    private function tmdbBaseUrl(): string
    {
        return rtrim(config('services.tmdb.base_url', 'https://api.themoviedb.org/3'), '/');
    }

    /**
     * Gedeelde HTTP-client instellingen voor TMDb.
     */
    private function tmdbRequest()
    {
        return Http::retry(2, 200)
            ->timeout(15)
            ->acceptJson()
            ->withoutVerifying();
    }
}
