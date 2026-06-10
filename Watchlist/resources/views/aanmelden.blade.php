<style>
body {
    margin: 0;
    font-family: 'Segoe UI', Arial, sans-serif;
    background: radial-gradient(circle at top, #0a0a0a, #000);
    color: #e5e7eb;
}

.card {
    max-width: 620px;
    margin: 50px auto;
    background: #0b0b0b;
    border: 1px solid #1f1f1f;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 0 25px rgba(255, 212, 0, 0.05);
}

.card h1 {
    color: #ffd400;
    text-transform: uppercase;
    letter-spacing: 3px;
    margin-bottom: 20px;
    text-align: center;
    text-shadow: 0 0 10px rgba(255, 212, 0, 0.25);
}

.field {
    margin-bottom: 14px;
}

label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #cfcfcf;
}

input {
    width: 100%;
    border: 1px solid #1f1f1f;
    border-radius: 8px;
    padding: 10px;
    background: #0f0f0f;
    color: #e5e7eb;
}

input:focus {
    outline: none;
    border-color: #ffd400;
    box-shadow: 0 0 8px rgba(255, 212, 0, 0.25);
}

button {
    width: 100%;
    border: none;
    border-radius: 8px;
    padding: 10px;
    background: #ffd400;
    color: #000;
    font-weight: 700;
    cursor: pointer;
    transition: 0.2s;
}

button:hover {
    box-shadow: 0 0 12px rgba(255, 212, 0, 0.35);
    transform: translateY(-1px);
}

.card > div {
    border: 1px solid #3a1a1a !important;
    background: #1a0a0a !important;
    color: #ff6b6b !important;
    border-radius: 8px !important;
    padding: 10px !important;
}

.card ul {
    margin: 0;
    padding-left: 18px;
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