<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gebruiker detail</title>
    <style>
        :root {
            --bg: #f5f7fb;
            --panel: #fff;
            --text: #1f2937;
            --muted: #6b7280;
            --border: #d1d5db;
            --accent: #e5f0ff;
            --accent-border: #93c5fd;
        }

        body {
            font-family: Arial, sans-serif;
            background: var(--bg);
            color: var(--text);
            margin: 0;
        }

        .page {
            max-width: 1180px;
            margin: 24px auto;
            padding: 0 16px 24px;
        }

        .panel,
        .card {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
        }

        .panel {
            padding: 24px;
            margin-bottom: 18px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            align-items: center;
        }

        .actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            border: 1px solid #9ca3af;
            background: #f9fafb;
            padding: 10px 14px;
            border-radius: 8px;
            text-decoration: none;
            color: #111827;
            font-weight: 600;
        }

        .btn.primary {
            background: var(--accent);
            border-color: var(--accent-border);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 12px;
            margin-top: 18px;
        }

        .info {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 14px;
            background: #fff;
        }

        .label {
            color: var(--muted);
            font-size: 0.9rem;
            margin-bottom: 6px;
        }

        .value {
            font-weight: 700;
        }

        .card {
            overflow: hidden;
            margin-bottom: 18px;
        }

        .card-header {
            padding: 18px 20px;
            border-bottom: 1px solid #e5e7eb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border-bottom: 1px solid #e5e7eb;
            padding: 12px 14px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #f9fafb;
            font-size: 0.92rem;
        }

        .subtle {
            color: var(--muted);
            font-size: 0.92rem;
        }

        .role {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            font-size: 0.85rem;
            margin-right: 6px;
        }

        .empty {
            padding: 20px;
            color: var(--muted);
        }
    </style>
</head>
<body>
    <main class="page">
        <section class="panel">
            <div class="topbar">
                <div>
                    <h1>{{ $user->name }}</h1>
                    <p class="subtle">Detailoverzicht van gebruiker en gekoppelde watchlist.</p>
                </div>
                <div class="actions">
                    <a class="btn" href="{{ route('admin') }}">Terug naar overzicht</a>
                    <a class="btn primary" href="{{ route('watchlist.create') }}">Toevoegen aan watchlist</a>
                </div>
            </div>

            <div class="grid">
                <div class="info">
                    <div class="label">Naam</div>
                    <div class="value">{{ $user->name }}</div>
                </div>
                <div class="info">
                    <div class="label">E-mail</div>
                    <div class="value">{{ $user->email }}</div>
                </div>
                <div class="info">
                    <div class="label">Rol</div>
                    <div class="value">
                        @forelse ($user->getRoleNames() as $role)
                            <span class="role">{{ $role }}</span>
                        @empty
                            Geen rol
                        @endforelse
                    </div>
                </div>
                <div class="info">
                    <div class="label">Watchlisten</div>
                    <div class="value">{{ $user->watchlist_items_count }}</div>
                </div>
                <div class="info">
                    <div class="label">Aangemaakt</div>
                    <div class="value">{{ $user->created_at?->format('d-m-Y H:i') ?? 'Onbekend' }}</div>
                </div>
            </div>
        </section>

        <section class="card">
            <div class="card-header">
                <h2>Watchlist items</h2>
            </div>

            @if ($user->watchlistItems->isEmpty())
                <div class="empty">Deze gebruiker heeft nog geen watchlist items.</div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Titel</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Jaar</th>
                            <th>Rating</th>
                            <th>Bijgewerkt</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($user->watchlistItems as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->title }}</strong><br>
                                    <span class="subtle">TMDb: {{ $item->tmdb_type ?? 'n.v.t.' }} {{ $item->tmdb_id ? '#'.$item->tmdb_id : '' }}</span>
                                </td>
                                <td>{{ ucfirst($item->type) }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $item->status)) }}</td>
                                <td>{{ $item->year }}</td>
                                <td>{{ $item->rating ? $item->rating . '/5' : 'N/A' }}</td>
                                <td>{{ $item->updated_at?->format('d-m-Y H:i') ?? 'Onbekend' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </section>
    </main>
</body>
</html>