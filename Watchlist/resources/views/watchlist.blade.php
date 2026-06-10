<style>
body {
    margin: 0;
    font-family: 'Segoe UI', Arial, sans-serif;
    background: radial-gradient(circle at top, #0a0a0a, #000);
    color: #e5e7eb;
}

.page {
    max-width: 1200px;
    margin: 24px auto;
    padding: 20px;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.header h1 {
    color: #ffd400;
    text-transform: uppercase;
    letter-spacing: 3px;
    margin: 0;
    text-shadow: 0 0 12px rgba(255, 212, 0, 0.25);
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    border: 1px solid #1f1f1f;
    background: linear-gradient(145deg, #111, #0b0b0b);
    color: #e5e7eb;

    padding: 8px 12px;
    border-radius: 8px;

    text-decoration: none;
    cursor: pointer;

    transition: all 0.2s ease;
    font-size: 0.95rem;
    line-height: 1;
    white-space: nowrap;
}

.btn:hover {
    border-color: #ffd400;
    box-shadow: 0 0 10px rgba(255, 212, 0, 0.25);
    transform: translateY(-1px);
}

.btn.primary {
    background: #ffd400;
    color: #000;
    font-weight: 700;
    border: 1px solid #ffd400;
}

.btn[disabled] {
    opacity: 0.4;
    cursor: not-allowed;
    box-shadow: none;
}

.admin-callout {
    background: #0b0b0b;
    border: 1px solid #1f1f1f;
    padding: 14px;
    border-radius: 10px;
    margin-bottom: 15px;
}

.watchlist-switcher {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 15px;
}

.topbar {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    align-items: center;
}

input[type="text"] {
    flex: 1;
    background: #0f0f0f !important;
    border: 1px solid #1f1f1f !important;
    color: #e5e7eb !important;
    border-radius: 8px;
    padding: 8px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #111;
    color: #ffd400;
    text-align: left;
}

th, td {
    border: 1px solid #1f1f1f;
    padding: 8px;
    vertical-align: middle;
}

tr:hover {
    background: #151515;
}

td img {
    border-radius: 6px;
    transition: 0.2s;
}

td img:hover {
    transform: scale(1.05);
}
</style>

<div class="page">
    <div class="header">
        <div>
            <h1>Watchlist</h1>
            <div class="watchlist-name">Huidige lijst: {{ $selectedWatchlist->name }}</div>
        </div>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button class="btn" type="submit">Uitloggen</button>
        </form>
    </div>

    <div class="watchlist-switcher">
        @foreach ($watchlists as $watchlist)
            <a class="btn {{ $selectedWatchlist->id === $watchlist->id ? 'primary' : '' }}"
                href="{{ route('watchlist.index', array_merge(request()->except('watchlist_id'), ['watchlist_id' => $watchlist->id])) }}">
                {{ $watchlist->name }}
            </a>
        @endforeach
        <a class="btn primary" href="{{ route('watchlist.create', ['watchlist_id' => $selectedWatchlist->id]) }}">Toevoegen</a>
    </div>

    <form action="" class="topbar">
        <input type="hidden" name="watchlist_id" value="{{ $selectedWatchlist->id }}">
        <input type="text" name="search" placeholder="Zoeken...">
        <button class="btn" type="submit">Zoek</button>
    </form>

    <div class="topbar">
        <a class="btn" href="{{ route('watchlist.index', ['watchlist_id' => $selectedWatchlist->id]) }}">Alles</a>
        <a class="btn" href="{{ route('watchlist.index', array_merge(request()->except(['type', 'watchlist_id']), ['watchlist_id' => $selectedWatchlist->id, 'type' => 'film'])) }}">Films</a>
        <a class="btn" href="{{ route('watchlist.index', array_merge(request()->except(['type', 'watchlist_id']), ['watchlist_id' => $selectedWatchlist->id, 'type' => 'serie'])) }}">Series</a>
        <a class="btn" href="{{ route('watchlist.index', array_merge(request()->except(['status', 'watchlist_id']), ['watchlist_id' => $selectedWatchlist->id, 'status' => 'bekeken'])) }}">Bekeken</a>
        <a class="btn" href="{{ route('watchlist.index', array_merge(request()->except(['status', 'watchlist_id']), ['watchlist_id' => $selectedWatchlist->id, 'status' => 'niet_bekeken'])) }}">Nog te kijken</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Afbeelding</th>
                <th>Titel</th>
                <th>Type</th>
                <th>Jaar</th>
                <th>Status</th>
                <th>Rating</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>
                        @if ($item->image_path)
                            <img src="{{ $item->image_path }}" alt="{{ $item->title }}" width="100">
                        @endif
                    </td>
                    <td>{{ $item->title }}</td>
                    <td>{{ ucfirst($item->type) }}</td>
                    <td>{{ $item->year }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $item->status)) }}</td>
                    <td>{{ $item->rating ? $item->rating . '/5' : 'N/A' }}</td>
                    <td>
                        <a class="btn" href="{{ route('watchlist.edit', $item->id) }}">Bewerken</a>
                        <form method="POST" action="{{ route('watchlist.destroy', $item->id) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn" type="submit"
                                onclick="return confirm('Weet je zeker dat je dit item wilt verwijderen?')">Verwijderen</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>