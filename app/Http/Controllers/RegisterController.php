<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\RegisterRequest;
use GuzzleHttp\Middleware;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $credentials = $request->only('name', 'email', 'password', 'confirmPassword');

        if ($credentials['password'] != $credentials['confirmPassword']) {
            return redirect()->back()
                ->with('error', 'Parola de confirmare este greșita')
                ->withInput();
        }

        User::create($credentials);

        return redirect()->back()
            ->with('message', 'Contul a fost creat cu success');
    }
}
