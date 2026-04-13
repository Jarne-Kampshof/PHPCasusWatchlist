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
</style>

<div class="card">
    <h1>Film of serie toevoegen</h1>
    <form method="POST" action="{{ route('watchlist.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="field">
            <label for="title">Titel:</label>
            <input type="text" id="title" name="title" required>
        </div>
        <div class="field">
            <label for="type">Type:</label>
            <select id="type" name="type" required>
                <option value="film">Film</option>
                <option value="serie">Serie</option>
            </select>
        </div>
        <div class="field">
            <label for="year">Jaar:</label>
            <input type="number" id="year" name="year" required min="1900" max="{{ date('Y') }}">
        </div>
        <div class="field">
            <label for="image">Afbeelding kiezen:</label>
            <input type="file" id="image" name="image" accept="image/*">
        </div>
        <button type="submit">Toevoegen aan watchlist</button>
    </form>
</div>