<?php

namespace App\Http\Controllers;

use App\Models\WatchlistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WatchlistItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = WatchlistItem::where('user_id', Auth::id());

        if ($request->filled('status') && in_array($request->status, ['niet_bekeken', 'bekeken'], true)) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type') && in_array($request->type, ['film', 'serie'], true)) {
            $query->where('type', $request->type);
        }

        $items = $query->orderBy('updated_at', 'desc')->get();

        return view('watchlist', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('toevoegpagina');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:film,serie',
            'year' => 'required|integer|min:1888|max:2100',
            'image' => 'nullable|image|max:4096',
        ]);

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('watchlist-images', 'public');
            $imageUrl = Storage::url($path);
        }

        WatchlistItem::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'type' => $validated['type'],
            'status' => 'niet_bekeken',
            'year' => $validated['year'],
            'rating' => null,
            'image_path' => $imageUrl,
        ]);

        return to_route('watchlist.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(WatchlistItem $watchlistItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WatchlistItem $watchlistItem)
    {
        if ($watchlistItem->user_id !== Auth::id()) {
            abort(403);
        }

        return view('bewerkpagina', compact('watchlistItem'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WatchlistItem $watchlistItem)
    {
        if ($watchlistItem->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:film,serie',
            'status' => 'required|in:niet_bekeken,bekeken',
            'year' => 'required|integer|min:1888|max:2100',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        if ($validated['status'] === 'niet_bekeken') {
            $validated['rating'] = null;
        }

        $watchlistItem->update($validated);

        return to_route('watchlist.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WatchlistItem $watchlistItem)
    {
        if ($watchlistItem->user_id !== Auth::id()) {
            abort(403);
        }

        $watchlistItem->delete();

        return to_route('watchlist.index');
    }
}
