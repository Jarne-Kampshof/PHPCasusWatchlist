<?php

namespace App\Http\Controllers;

use App\Models\gebruikersgegevens;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class GebruikersgegevensController extends Controller
{
    public function register(Request $request)
    {
        // Valideer de registratie-invoer.
        $validated = $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'voornaam' => 'required|string',
            'achternaam' => 'required|string',
            'telefoonnummer' => 'nullable|string',
        ]);

        // Maak het hoofdaccount aan in de users tabel.
        $user = User::create([
            'name' => $validated['voornaam'] . ' ' . $validated['achternaam'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Sla extra profielinformatie op in gebruikersgegevens.
        gebruikersgegevens::create([
            'user_id' => $user->id,
            'voornaam' => $validated['voornaam'],
            'achternaam' => $validated['achternaam'],
            'email' => $validated['email'],
            'telefoonnummer' => $validated['telefoonnummer'],
            'wachtwoord' => Hash::make($validated['password']),
        ]);

        return to_route('login')->with('success', 'Account aangemaakt. Je kan nu inloggen.');
    }

    public function login(Request $request)
    {
        // Controleer of email en wachtwoord aanwezig zijn.
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Inloggen mislukt. Controleer je e-mailadres en wachtwoord.',
            ])->onlyInput('email');
        }

        // Vernieuw de sessie na succesvolle login.
        $request->session()->regenerate();

        $user = Auth::user();

        if ($user instanceof User && $user->hasRole('admin')) {
            return to_route('admin');
        }

        return to_route('watchlist.index');
    }

    public function logout(Request $request)
    {
        // Log uit en maak de sessie ongeldig.
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('login');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Toon alleen profieldata van de ingelogde gebruiker.
        $user_id = Auth::id();
        $gebruikersgegevens = gebruikersgegevens::where('user_id', $user_id)
            ->orderBy('updated_at', 'desc')
            ->paginate(6);

        return view('gebruikersgegevens.index', compact('gebruikersgegevens'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('aanmelden');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'voornaam' => 'required|string|max:120',
            'achternaam' => 'required|string|max:120',
            'email' => 'required|email',
            'telefoonnummer' => 'nullable|string|max:30',
            'wachtwoord' => 'required|string|min:6',
        ]);

        $gebruiker = new gebruikersgegevens();
        $gebruiker->user_id = Auth::id();
        $gebruiker->voornaam = $request->input('voornaam');
        $gebruiker->achternaam = $request->input('achternaam');
        $gebruiker->email = $request->input('email');
        $gebruiker->telefoonnummer = $request->input('telefoonnummer');
        $gebruiker->wachtwoord = Hash::make($request->input('wachtwoord'));
        $gebruiker->save();

        return $this->index();
    }

    /**
     * Display the specified resource.
     */
    public function show(gebruikersgegevens $gebruikersgegevens)
    {
        // Blokkeer toegang tot gegevens van andere gebruikers.
        if ($gebruikersgegevens->user_id !== Auth::id()) {
            abort(403);
        }

        return view('gebruikersgegevens.show', compact('gebruikersgegevens'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(gebruikersgegevens $gebruikersgegevens)
    {
        // Blokkeer toegang tot gegevens van andere gebruikers.
        if ($gebruikersgegevens->user_id !== Auth::id()) {
            abort(403);
        }

        return view('gebruikersgegevens.edit', compact('gebruikersgegevens'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, gebruikersgegevens $gebruikersgegevens)
    {
        // Blokkeer toegang tot gegevens van andere gebruikers.
        if ($gebruikersgegevens->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'voornaam' => 'required|string|max:120',
            'achternaam' => 'required|string|max:120',
            'email' => 'required|email',
            'telefoonnummer' => 'nullable|string|max:30',
            'wachtwoord' => 'nullable|string|min:6',
        ]);

        $gebruikersgegevens->voornaam = $request->voornaam;
        $gebruikersgegevens->achternaam = $request->achternaam;
        $gebruikersgegevens->email = $request->email;
        $gebruikersgegevens->telefoonnummer = $request->telefoonnummer;

        if ($request->filled('wachtwoord')) {
            // Hash wachtwoord opnieuw als gebruiker dit wijzigt.
            $gebruikersgegevens->wachtwoord = Hash::make($request->wachtwoord);
        }

        $gebruikersgegevens->save();

        return to_route('gebruikersgegevens.show', $gebruikersgegevens);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(gebruikersgegevens $gebruikersgegevens)
    {
        // Blokkeer toegang tot gegevens van andere gebruikers.
        if ($gebruikersgegevens->user_id !== Auth::id()) {
            abort(403);
        }

        $gebruikersgegevens->delete();

        return to_route('gebruikersgegevens.index');
    }
}
