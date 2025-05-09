<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserSessionController extends Controller
{
    public function create(){
        return view('auth.login');
    }

    public function store(Request $request){
        $validated = $request->validate([
            'email' => ['required','email'],
            'password' => ['required']
        ]);

        //try to log in
        $loggedIn = Auth::attempt($validated);
            //back with errors
            if (!$loggedIn){
                throw ValidationException::withMessages([
                    "email" => "Those credentials do not match"
                ]);
            }

        $request->session()->regenerate();

        //redirect to dashboard
        return redirect('/');
    }

    public function destroy(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
