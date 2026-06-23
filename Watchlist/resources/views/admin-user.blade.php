<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gebruiker detail</title>
    <style>
        :root {
            --bg: #000;
            --panel: #0b0b0b;
            --text: #e5e7eb;
            --muted: #9ca3af;
            --border: #1f1f1f;
            --accent: #ffd400;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: radial-gradient(circle at top, #0a0a0a, #000);
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
            box-shadow: 0 0 25px rgba(255, 212, 0, 0.04);
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

        .topbar h1 {
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: 3px;
            margin: 0;
            text-shadow: 0 0 10px rgba(255, 212, 0, 0.25);
        }

        .actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            border: 1px solid var(--border);
            background: #111;
            padding: 10px 14px;
            border-radius: 8px;
            text-decoration: none;
            color: var(--text);
            font-weight: 600;
            transition: 0.2s;
        }

        .btn:hover {
            border-color: var(--accent);
            box-shadow: 0 0 10px rgba(255, 212, 0, 0.25);
            transform: translateY(-1px);
        }

        .btn.primary {
            background: var(--accent);
            color: #000;
            border: 1px solid var(--accent);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 12px;
            margin-top: 18px;
        }

        .info {
            background: #0f0f0f;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 14px;
        }

        .label {
            color: var(--muted);
            font-size: 0.9rem;
            margin-bottom: 6px;
        }

        .value {
            font-weight: 700;
            color: var(--accent);
        }

        .card {
            overflow: hidden;
            margin-bottom: 18px;
        }

        .card-header {
            padding: 18px 20px;
            border-bottom: 1px solid var(--border);
        }

        .card-header h2 {
            margin: 0;
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border-bottom: 1px solid var(--border);
            padding: 12px 14px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #111;
            color: var(--accent);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        tr:hover {
            background: #151515;
        }

        .subtle {
            color: var(--muted);
            font-size: 0.92rem;
        }

        .role {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            background: #111;
            border: 1px solid var(--accent);
            color: var(--accent);
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($user->watchlistItems as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->title }}</strong><br>
                                </td>
                                <td>{{ ucfirst($item->type) }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $item->status)) }}</td>
                                <td>{{ $item->year }}</td>
                                <td>{{ $item->rating ? $item->rating . '/5' : 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </section>
    </main>
</body>

</html>