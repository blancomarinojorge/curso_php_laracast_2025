<?php

namespace App\Http\Controllers;

use App\Http\Requests\auth\StoreRegistrationRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserRegistrationController extends Controller
{
    public function create(){
        return view('auth.register');
    }

    public function store(StoreRegistrationRequest $request){
        $user = User::create($request->validated());

        Auth::login($user);

        return redirect('/jobs');
    }
}
