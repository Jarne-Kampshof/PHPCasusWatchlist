<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin overzicht</title>
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

        .hero,
        .card {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
        }

        .hero {
            padding: 24px;
            margin-bottom: 18px;
        }

        .hero-top {
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

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 12px;
            margin-top: 18px;
        }

        .stat {
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
            font-size: 1.4rem;
            font-weight: 700;
        }

        .card {
            overflow: hidden;
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

        .link {
            color: #1d4ed8;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <main class="page">
        <section class="hero">
            <div class="hero-top">
                <div>
                    <h1>Admin overzicht</h1>
                    <p class="subtle">Bekijk alle gebruikers en klik door naar detailinformatie.</p>
                </div>
                <div class="actions">
                    <a class="btn primary" href="{{ route('watchlist.create') }}">Toevoegen aan watchlist</a>
                    <a class="btn" href="{{ route('watchlist.index') }}">Naar watchlist</a>
                </div>
            </div>

            <div class="stats">
                <div class="stat">
                    <div class="label">Gebruikers</div>
                    <div class="value">{{ $users->count() }}</div>
                </div>
                <div class="stat">
                    <div class="label">Met watchlist items</div>
                    <div class="value">{{ $users->where('watchlist_items_count', '>', 0)->count() }}</div>
                </div>

            </div>
        </section>

        <section class="card">
            <div class="card-header">
                <h2>Alle gebruikers</h2>
            </div>

            @if ($users->isEmpty())
                <div class="empty">Er zijn nog geen gebruikers gevonden.</div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Naam</th>
                            <th>E-mail</th>
                            <th>Rollen</th>
                            <th>Watchlist</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>
                                    <strong>{{ $user->name }}</strong><br>
                                    <span class="subtle">Aangemaakt: {{ $user->created_at?->format('d-m-Y H:i') ?? 'Onbekend' }}</span>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @forelse ($user->getRoleNames() as $role)
                                        <span class="role">{{ $role }}</span>
                                    @empty
                                        <span class="subtle">Geen rol</span>
                                    @endforelse
                                </td>
                                <td>{{ $user->watchlist_items_count }}</td>
                                <td>
                                    <a class="link" href="{{ route('admin.show', $user) }}">Bekijk detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </section>
    </main>
</body>
</html>
