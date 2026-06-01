<?php

namespace App\Http\Controllers;

use App\Models\Watchlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WatchlistController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'query' => ['nullable', 'string', 'max:255'],
        ]);

        $watchlist = Watchlist::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
        ]);

        $redirectParameters = ['watchlist_id' => $watchlist->id];

        if (! empty($validated['query'])) {
            $redirectParameters['query'] = $validated['query'];
        }

        return to_route('watchlist.create', $redirectParameters);
    }
}
