<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            color: #1f2937;
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
        }

        .panel {
            width: min(720px, calc(100% - 32px));
            background: #fff;
            border: 1px solid #d1d5db;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
        }

        .actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 18px;
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
            background: #e5f0ff;
            border-color: #93c5fd;
        }
    </style>
</head>
<body>
    <main class="panel">
        <h1>Admin panel</h1>
        <p>Je bent ingelogd als admin. Vanaf hier kun je doorgaan naar de toevoegpagina.</p>

        <div class="actions">
            <a class="btn primary" href="{{ route('watchlist.create') }}">Naar toevoegpagina</a>
            <a class="btn" href="{{ route('watchlist.index') }}">Naar watchlist</a>
        </div>
    </main>
</body>
</html>
