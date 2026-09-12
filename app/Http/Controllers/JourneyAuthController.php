<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JourneyAuthController extends Controller
{
    public function form()
    {
        return view('journey.auth');
    }

    public function login(Request $r)
    {
        $data = $r->validate(['email' => 'required|email', 'password' => 'required|string']);
        if (! Auth::attempt($data)) {
            return back()->withErrors(['email' => 'Email atau kata sandi tidak cocok.'])->onlyInput('email');
        }
        $r->session()->regenerate();

        return redirect()->intended('/pkl/dashboard');
    }

    public function register(Request $r)
    {
        $data = $r->validate(['name' => 'required|string|max:100', 'email' => 'required|email|max:255|unique:users', 'password' => 'required|string|min:8|confirmed']);
        $user = User::create($data);
        Auth::login($user);
        $r->session()->regenerate();

        return redirect('/pkl/dashboard')->with('success', 'Akun siswa siap digunakan.');
    }

    public function logout(Request $r)
    {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();

        return redirect('/');
    }
}
