<?php

namespace App\Http\Controllers;

use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->with([
                'profileRecords' => fn ($query) => $query->latest(),
                'watchlistItems' => fn ($query) => $query->latest(),
            ])
            ->withCount(['profileRecords', 'watchlistItems'])
            ->latest()
            ->get();

        return view('admin', compact('users'));
    }

    public function show(User $user)
    {
        $user->load([
            'profileRecords' => fn ($query) => $query->latest(),
            'watchlistItems' => fn ($query) => $query->latest(),
        ])->loadCount(['profileRecords', 'watchlistItems']);

        return view('admin-user', compact('user'));
    }
}
