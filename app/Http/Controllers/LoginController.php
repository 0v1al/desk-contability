<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct()
    {
        // $this->middleware('guest');
    }

    public function index()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            if (Auth::user()->is_admin) {
                return redirect()->intended('/admin/user/file/upload');
            } else {
                return redirect()->intended('/user/files');
            }
        }

        return redirect()->back()
            ->with('error', 'Datele de autentificare introduse nu sunt corecte')
            ->withInput();
    }

    public function logout()
    {
        Auth::logout();
        return redirect(route('login'));
    }
}
