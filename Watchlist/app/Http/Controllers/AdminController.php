<?php

namespace App\Http\Controllers;

use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->with([
                'watchlistItems' => fn ($query) => $query->latest(),
            ])
            ->withCount(['watchlistItems'])
            ->latest()
            ->get();

        return view('admin', compact('users'));
    }

    public function show(User $user)
    {
        $user->load([
            'watchlistItems' => fn ($query) => $query->latest(),
        ])->loadCount(['watchlistItems']);

        return view('admin-user', compact('user'));
    }
}
