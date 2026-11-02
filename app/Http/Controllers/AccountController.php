<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function dashboard(Request $request): View
    {
        $factures = $request->user()
            ->factures()
            ->latest('date_emission')
            ->paginate(10);

        return view('account.dashboard', compact('factures'));
    }

    public function loginForm(): View
    {
        return view('account.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Ces identifiants ne correspondent pas à un compte.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('account.dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('facture.create');
    }
}
