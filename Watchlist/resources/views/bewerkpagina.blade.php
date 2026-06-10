<style>
body {
    margin: 0;
    font-family: 'Segoe UI', Arial, sans-serif;
    background: radial-gradient(circle at top, #0a0a0a, #000);
    color: #e5e7eb;
}

.card {
    max-width: 620px;
    margin: 40px auto;
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

input,
select {
    width: 100%;
    border: 1px solid #1f1f1f;
    border-radius: 8px;
    padding: 10px;
    background: #0f0f0f;
    color: #e5e7eb;
}

input:focus,
select:focus {
    outline: none;
    border-color: #ffd400;
    box-shadow: 0 0 8px rgba(255, 212, 0, 0.25);
}

input[readonly],
select[disabled] {
    background: #0a0a0a;
    color: #6b7280;
    border: 1px solid #1f1f1f;
    cursor: not-allowed;
    opacity: 0.8;
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
</style>

<div class="card">
    <h1>Film of serie bijwerken</h1>
    <form method="POST" action="{{ route('watchlist.update', $watchlistItem->id) }}">
        @csrf
        @method('PUT')
        <div class="field">
            <label for="title">Titel:</label>
            <input type="text" id="title" name="title" value="{{ $watchlistItem->title }}" required readonly>
        </div>
        <div class="field">
            <label for="type">Type:</label>
            <select id="type" name="type" required disabled>
                <option value="film" {{ $watchlistItem->type === 'film' ? 'selected' : '' }}>Film</option>
                <option value="serie" {{ $watchlistItem->type === 'serie' ? 'selected' : '' }}>Serie</option>
            </select>
            <input type="hidden" name="type" value="{{ $watchlistItem->type }}">
        </div>
        <div></div>
        <div class="field">
            <label for="status">Status:</label>
            <select id="status" name="status" required>
                <option value="niet_bekeken" {{ $watchlistItem->status === 'niet_bekeken' ? 'selected' : '' }}>Niet
                    bekeken</option>
                <option value="bekeken" {{ $watchlistItem->status === 'bekeken' ? 'selected' : '' }}>Bekeken</option>
            </select>
        </div>
        <div class="field">
            <label for="year">Jaar:</label>
            <input type="number" id="year" name="year" value="{{ $watchlistItem->year }}" required min="1900"
                max="{{ date('Y') }}" readonly>
        </div>
        <div class="field">
            <label for="rating">Rating:</label>
            <input type="number" id="rating" name="rating" value="{{ $watchlistItem->rating }}" min="0" max="5"
                step="0.5">
        </div>
        <button type="submit">Bijwerken</button>
    </form>
</div>