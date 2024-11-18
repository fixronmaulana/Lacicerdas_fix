<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $user = (new CreateNewUser())->create($request->all());

        Auth::login($user);

        // Redirect berdasarkan role
        if ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        } else {
            return redirect('/user/dashboard');
        }
    }

    public function login(Request $request)
{
    // Validasi input
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Cari user dengan email yang case-sensitive
    $user = \App\Models\User::whereRaw('BINARY email = ?', [$request->email])->first();

    // Cek apakah user ditemukan dan password cocok
    if ($user && Auth::validate(['email' => $user->email, 'password' => $request->password])) {
        Auth::login($user);
        $request->session()->regenerate();

        // Redirect berdasarkan role
        $role = $user->role;
        if ($role === 'admin') {
            return redirect()->intended('/admin/dashboard');
        } else {
            return redirect()->intended('/user/dashboard');
        }
    }

    // Jika gagal login, kirimkan pesan error
    return back()->withErrors(['email' => 'Email atau password tidak sesuai.']);
}


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
