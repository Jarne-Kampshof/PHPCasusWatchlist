<style>
body {
    margin: 0;
    font-family: 'Segoe UI', Arial, sans-serif;
    background: radial-gradient(circle at top, #0a0a0a, #000);
    color: #e5e7eb;
}

.card {
    max-width: 520px;
    margin: 60px auto;
    background: #0b0b0b;
    border: 1px solid #1f1f1f;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 0 25px rgba(255, 212, 0, 0.06);
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

.notice {
    border: 1px solid #1f1f1f;
    background: #0f0f0f;
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 10px;
    color: #ffd400;
}

.notice.error {
    border-color: #3a1a1a;
    background: #1a0a0a;
    color: #ff6b6b;
}

a {
    display: block;
    margin-top: 12px;
    text-align: center;
    color: #ffd400;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
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