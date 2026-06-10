<style>
body {
    margin: 0;
    font-family: 'Segoe UI', Arial, sans-serif;
    background: #000;
    color: #e5e7eb;
}

.card {
    max-width: 980px;
    margin: 24px auto;
    background: #0b0b0b;
    border: 1px solid #1f1f1f;
    border-radius: 12px;
    padding: 18px;
    box-shadow: 0 0 25px rgba(255, 212, 0, 0.05);
}

h1, h2 {
    color: #ffd400;
    letter-spacing: 2px;
    text-transform: uppercase;
}

label {
    display: block;
    margin-bottom: 4px;
    font-weight: 600;
    color: #cfcfcf;
}

input,
select,
button {
    width: 100%;
    border: 1px solid #1f1f1f;
    border-radius: 8px;
    padding: 10px;
    background: #0f0f0f;
    color: #e5e7eb;
}

input:focus,
select:focus {
    outline: none;
    border-color: #ffd400;
    box-shadow: 0 0 8px rgba(255,212,0,0.3);
}

button {
    cursor: pointer;
    background: #111;
    transition: 0.2s;
}

button:hover {
    border-color: #ffd400;
    box-shadow: 0 0 10px rgba(255,212,0,0.3);
}

button[type="submit"] {
    background: #ffd400;
    color: #000;
    font-weight: 700;
    border: none;
}

button[disabled] {
    opacity: 0.4;
    cursor: not-allowed;
}

.search-bar {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 10px;
    margin: 16px 0;
}

.notice {
    border-radius: 10px;
    padding: 10px 12px;
    margin-bottom: 12px;
    border: 1px solid #1f1f1f;
}

.notice.error {
    background: #1a0a0a;
    color: #ff6b6b;
    border-color: #3a1a1a;
}

.notice.info {
    background: #0f0f0f;
    color: #ffd400;
}

.watchlist-tools {
    display: grid;
    gap: 14px;
    margin: 14px 0 18px;
    padding: 14px;
    border: 1px solid #1f1f1f;
    border-radius: 10px;
    background: #0a0a0a;
}

.inline {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 10px;
    align-items: end;
}

.results {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 14px;
    margin: 16px 0;
}

.result {
    background: #0d0d0d;
    border: 1px solid #1f1f1f;
    border-radius: 12px;
    overflow: hidden;
    transition: 0.2s;
}

.result:hover {
    transform: translateY(-5px);
    border-color: #ffd400;
    box-shadow: 0 0 15px rgba(255,212,0,0.2);
}

.result img {
    width: 100%;
    aspect-ratio: 2 / 3;
    object-fit: cover;
    background: #111;
}

.result-body {
    padding: 12px;
    display: grid;
    gap: 8px;
}

.result-title {
    font-weight: 700;
}

.meta {
    color: #9ca3af;
    font-size: 0.9rem;
}

.manual-section {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #1f1f1f;
}
</style>

<div class="card">

    <h1>🎬 Film of serie toevoegen</h1>
    <p class="meta">
        Toevoegen aan lijst: <strong style="color:#ffd400;">
            {{ $selectedWatchlist->name }}
        </strong>
    </p>

    <div class="watchlist-tools">

        <form method="GET" action="{{ route('watchlist.create') }}" class="inline">
            <div>
                <label>Kies lijst</label>
                <select name="watchlist_id">
                    @foreach ($watchlists as $watchlist)
                        <option value="{{ $watchlist->id }}"
                            @selected($selectedWatchlist->id === $watchlist->id)>
                            {{ $watchlist->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit">Wissel</button>
        </form>

        <form method="POST" action="{{ route('watchlists.store') }}" class="inline">
            @csrf
            <div>
                <label>Nieuwe lijst</label>
                <input type="text" name="name" placeholder="Bijv. Films, Series..." required>
            </div>
            <button type="submit">Maak</button>
        </form>

    </div>

    <form method="GET" action="{{ route('watchlist.create') }}" class="search-bar">
        <input type="hidden" name="watchlist_id" value="{{ $selectedWatchlist->id }}">
        <input type="text" name="query" value="{{ $query }}" placeholder="Zoek films of series...">
        <button type="submit">Zoeken</button>
    </form>

    @if ($searchError)
        <div class="notice error">{{ $searchError }}</div>
    @endif

    @if ($query !== '' && !$searchError)
        <div class="notice info">
            Resultaten voor "<strong>{{ $query }}</strong>"
        </div>
    @endif

    @if (!empty($searchResults))
        <div class="results">
            @foreach ($searchResults as $result)
                <div class="result">

                    @if ($result['image_path'])
                        <img src="{{ $result['image_path'] }}" alt="{{ $result['title'] }}">
                    @endif

                    <div class="result-body">
                        <div class="result-title">{{ $result['title'] }}</div>
                        <div class="meta">
                            {{ ucfirst($result['tmdb_type'] ?? 'onbekend') }} · {{ $result['year'] }}
                        </div>

                        <form method="POST" action="{{ route('watchlist.store') }}">
                            @csrf

                            <input type="hidden" name="title" value="{{ $result['title'] }}">
                            <input type="hidden" name="type" value="{{ $result['tmdb_type'] }}">
                            <input type="hidden" name="year" value="{{ $result['year'] }}">
                            <input type="hidden" name="tmdb_id" value="{{ $result['tmdb_id'] }}">
                            <input type="hidden" name="tmdb_type" value="{{ $result['tmdb_type'] }}">
                            <input type="hidden" name="image_path" value="{{ $result['image_path'] }}">
                            <input type="hidden" name="watchlist_id" value="{{ $selectedWatchlist->id }}">

                            @if ($result['tmdb_id'] && $existingItems->has($result['tmdb_id']))
                                <button type="button" disabled>Al toegevoegd</button>
                            @else
                                <button type="submit">Toevoegen</button>
                            @endif
                        </form>

                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>