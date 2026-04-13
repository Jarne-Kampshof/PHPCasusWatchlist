<style>
    body {
        font-family: Arial, sans-serif;
        background: #f5f7fb;
    }

    .card {
        max-width: 620px;
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
</style>

<div class="card">
    <h1>Aanmelden pagina</h1>

    @if ($errors->any())
        <div style="border: 1px solid #fecaca; background: #fef2f2; padding: 8px; border-radius: 6px; margin-bottom: 12px;">
            <p style="margin: 0 0 6px 0; font-weight: 600;">Aanmelden is mislukt:</p>
            <ul style="margin: 0; padding-left: 18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register.submit') }}">
        @csrf
        <div class="field">
            <label for="voornaam">Voornaam:</label>
            <input type="text" id="voornaam" name="voornaam" required>
        </div>
        <div class="field">
            <label for="achternaam">Achternaam:</label>
            <input type="text" id="achternaam" name="achternaam" required>
        </div>
        <div class="field">
            <label for="email">E-mailadres:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="field">
            <label for="telefoonnummer">Telefoonnummer:</label>
            <input type="text" id="telefoonnummer" name="telefoonnummer" required>
        </div>
        <div class="field">
            <label for="password">Wachtwoord:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Aanmelden</button>
    </form>
</div>