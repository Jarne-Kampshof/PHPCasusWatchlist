<style>
    body {
        font-family: Arial, sans-serif;
        background: #f5f7fb;
        color: #1f2937;
    }

    .page {
        max-width: 1100px;
        margin: 24px auto;
        background: #fff;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        padding: 16px;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .topbar {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 12px;
    }

    .watchlist-switcher {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
        margin: 14px 0 16px;
    }

    .watchlist-name {
        color: #6b7280;
        font-size: 0.95rem;
        margin: 4px 0 0;
    }

    .btn {
        border: 1px solid #9ca3af;
        background: #f9fafb;
        padding: 6px 10px;
        border-radius: 6px;
        text-decoration: none;
        color: #111827;
    }

    .btn.primary {
        background: #e5f0ff;
        border-color: #93c5fd;
    }

    .admin-callout {
        margin: 0 0 14px;
        padding: 14px 16px;
        border: 1px solid #bfdbfe;
        border-radius: 10px;
        background: #eff6ff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .admin-callout strong {
        display: block;
        margin-bottom: 4px;
    }

    input[type="text"] {
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 6px 8px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    th,
    td {
        border: 1px solid #e5e7eb;
        padding: 8px;
        text-align: left;
        vertical-align: middle;
    }

    th {
        background: #f3f4f6;
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

    @auth
        <div class="admin-callout">
            <div>
                <strong>Toevoegen aan je watchlist</strong>
                <span>Ga direct naar de toevoegpagina om een film of serie toe te voegen.</span>
            </div>
            <a class="btn primary" href="{{ route('watchlist.create') }}">Naar toevoegpagina</a>
        </div>
    @endauth

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