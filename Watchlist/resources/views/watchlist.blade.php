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
        <h1>Watchlist</h1>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button class="btn" type="submit">Uitloggen</button>
        </form>
    </div>

    <form action="" class="topbar">
        <input type="text" name="search" placeholder="Zoeken...">
        <button class="btn" type="submit">Zoek</button>
    </form>

    <div class="topbar">
        <a class="btn primary" href="{{ route('watchlist.create') }}">Toevoegen</a>

        <a class="btn" href="{{ route('watchlist.index') }}">Alles</a>
        <a class="btn" href="{{ route('watchlist.index', ['type' => 'film']) }}">Films</a>
        <a class="btn" href="{{ route('watchlist.index', ['type' => 'serie']) }}">Series</a>
        <a class="btn" href="{{ route('watchlist.index', ['status' => 'bekeken']) }}">Bekeken</a>
        <a class="btn" href="{{ route('watchlist.index', ['status' => 'niet_bekeken']) }}">Nog te kijken</a>
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