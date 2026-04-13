<style>
    body {
        font-family: Arial, sans-serif;
        background: #f5f7fb;
    }

    .card {
        max-width: 520px;
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

    .notice {
        border: 1px solid #bfdbfe;
        background: #eff6ff;
        padding: 8px;
        border-radius: 6px;
        margin-bottom: 10px;
    }

    .error {
        border-color: #fecaca;
        background: #fef2f2;
    }
</style>

<div class="card">
    <h1>Login pagina</h1>
    @if (session('success'))
        <p class="notice">{{ session('success') }}</p>
    @endif

    @if ($errors->any())
        <p class="notice error">{{ $errors->first() }}</p>
    @endif

    <form method="POST" action="{{ route('login.submit') }}">
        @csrf
        <div class="field">
            <label for="email">E-mailadres:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="field">
            <label for="password">Wachtwoord:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Inloggen</button>
    </form>
    <a href="{{ route('register') }}">Registreer hier</a>
</div>