<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showFormLogin()
    {
        if (Auth::id() > 0) {
            return redirect(route('index'));
        }
        return view('client.login');
    }
    public function showFormRegister()
    {
        if (Auth::id() > 0) {
            return redirect(route('index'));
        }
        return view('client.register');
    }
    public function showFormReset()
    {
        if (Auth::id() > 0) {
            return redirect(route('index'));
        }
        return view('client.reset-password');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect(route('index'));
        }

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không chính xác'
        ])->onlyInput('email');
    }
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect(route('login'));
    }
    public function register(RegisterRequest $request)
    {
        // dd($request->toArray());

        $user = User::create($request->toArray());

        Auth::login($user);

        $request->session()->regenerate();

        return redirect(route('index'));
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return false;
        }

        $user->password = Hash::make($request->password);

        $user->update($user->toArray());

        Auth::login($user);

        $request->session()->regenerate();

        return redirect(route('index'));
    }
}
