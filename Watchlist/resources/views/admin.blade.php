<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin overzicht</title>
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

.hero,
.card {
    background: var(--panel);
    border: 1px solid var(--border);
    border-radius: 14px;
    box-shadow: 0 0 25px rgba(255, 212, 0, 0.04);
}

.hero {
    padding: 24px;
    margin-bottom: 18px;
}

.hero h1 {
    color: var(--accent);
    text-transform: uppercase;
    letter-spacing: 3px;
    margin: 0;
    text-shadow: 0 0 10px rgba(255, 212, 0, 0.25);
}

.hero-top {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    align-items: center;
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

.actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 12px;
    margin-top: 18px;
}

.stat {
    background: #0f0f0f;
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 14px;
}

.label {
    color: var(--muted);
    font-size: 0.9rem;
}

.value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--accent);
}

.card {
    overflow: hidden;
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

th {
    background: #111;
    color: var(--accent);
    text-align: left;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

th, td {
    border-bottom: 1px solid var(--border);
    padding: 12px 14px;
    vertical-align: top;
}

tr:hover {
    background: #151515;
}

.subtle {
    color: var(--muted);
    font-size: 0.9rem;
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

.link {
    color: var(--accent);
    text-decoration: none;
    font-weight: 600;
}

.link:hover {
    text-decoration: underline;
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
