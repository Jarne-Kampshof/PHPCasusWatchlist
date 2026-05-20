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
    select,
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

    input[readonly],
    select[disabled] {
        background: #f8fafc;
        color: #475569;
        cursor: not-allowed;
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