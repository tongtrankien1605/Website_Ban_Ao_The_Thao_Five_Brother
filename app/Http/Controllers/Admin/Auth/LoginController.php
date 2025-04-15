<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->role > 1) {
            return redirect()->intended(RouteServiceProvider::HOME_ADMIN);
        }
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            if (Auth::user()->role > 1) {
                $request->session()->regenerate();
                
                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'redirect' => route('admin.index')
                    ]);
                }
                
                return redirect()->intended(RouteServiceProvider::HOME_ADMIN);
            }
            
            Auth::logout();
            return back()->withErrors([
                'email' => 'Bạn không có quyền truy cập vào trang quản trị.',
            ])->withInput($request->only('email'));
        }

        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
} 