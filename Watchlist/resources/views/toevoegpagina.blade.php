<style>
    body {
        font-family: Arial, sans-serif;
        background: #f5f7fb;
        color: #1f2937;
    }

    .card {
        max-width: 980px;
        margin: 24px auto;
        background: #fff;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        padding: 16px;
    }

    .field {
        margin-bottom: 12px;
    }

    label {
        display: block;
        margin-bottom: 4px;
        font-weight: 600;
    }

    input,
    select,
    button {
        width: 100%;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 8px;
    }

    button {
        background: #e5f0ff;
        border-color: #93c5fd;
        cursor: pointer;
    }

    .search-bar {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 10px;
        margin-bottom: 16px;
    }

    .notice {
        border-radius: 8px;
        padding: 10px 12px;
        margin-bottom: 16px;
    }

    .notice.error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }

    .notice.info {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        color: #1d4ed8;
    }

    .results {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 12px;
        margin: 16px 0 24px;
    }

    .result {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
        background: #fff;
        display: flex;
        flex-direction: column;
    }

    .result img {
        width: 100%;
        aspect-ratio: 2 / 3;
        object-fit: cover;
        background: #e5e7eb;
    }

    .result-body {
        padding: 12px;
        display: grid;
        gap: 8px;
    }

    .result-title {
        font-weight: 700;
        line-height: 1.3;
    }

    .meta {
        color: #6b7280;
        font-size: 0.95rem;
    }

    .result form {
        margin-top: auto;
    }

    .manual-section {
        margin-top: 28px;
        padding-top: 20px;
        border-top: 1px solid #e5e7eb;
    }
</style>

<div class="card">
    <h1>Film of serie toevoegen</h1>

    <form method="GET" action="{{ route('watchlist.create') }}" class="search-bar">
        <input type="text" name="query" value="{{ $query }}" placeholder="Zoek op film- of serienaam">
        <button type="submit">Zoeken</button>
    </form>

    @if ($searchError)
        <div class="notice error">{{ $searchError }}</div>
    @endif

    @if ($query !== '' && !$searchError)
        <div class="notice info">
            Resultaten voor "{{ $query }}". Kies een item om het direct aan je watchlist toe te voegen.
        </div>
    @endif

    @if (!empty($searchResults))
        <div class="results">
            @foreach ($searchResults as $result)
                <article class="result">
                    @if ($result['image_path'])
                        <img src="{{ $result['image_path'] }}" alt="{{ $result['title'] }}">
                    @endif
                    <div class="result-body">
                        <div class="result-title">{{ $result['title'] }}</div>
                        <div class="meta">{{ ucfirst($result['tmdb_type'] ?? 'onbekend') }} · {{ $result['year'] }}</div>

                        <form method="POST" action="{{ route('watchlist.store') }}">
                            @csrf
                            <input type="hidden" name="title" value="{{ $result['title'] }}">
                            <input type="hidden" name="type" value="{{ $result['tmdb_type'] }}">
                            <input type="hidden" name="year" value="{{ $result['year'] }}">
                            <input type="hidden" name="tmdb_id" value="{{ $result['tmdb_id'] }}">
                            <input type="hidden" name="tmdb_type" value="{{ $result['tmdb_type'] }}">
                            <input type="hidden" name="image_path" value="{{ $result['image_path'] }}">
                            <button type="submit">Toevoegen</button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>
    @endif

    <div class="manual-section">
        <h2>Handmatig toevoegen</h2>
        <form method="POST" action="{{ route('watchlist.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="field">
                <label for="title">Titel:</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="field">
                <label for="type">Type:</label>
                <select id="type" name="type" required>
                    <option value="film">Film</option>
                    <option value="serie">Serie</option>
                </select>
            </div>
            <div class="field">
                <label for="year">Jaar:</label>
                <input type="number" id="year" name="year" required min="1900" max="{{ date('Y') }}">
            </div>
            <div class="field">
                <label for="image">Afbeelding kiezen:</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>
            <button type="submit">Toevoegen aan watchlist</button>
        </form>

        @role('admin')
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
            <a href="{{ route('watchlist.index') }}" style="display: inline-block; background: #dbeafe; border: 1px solid #93c5fd; padding: 8px 16px; border-radius: 6px; text-decoration: none; color: #1e40af; font-weight: 600;">
                Beheer watchlist
            </a>
        </div>
        @endrole
    </div>
</div>