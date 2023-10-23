<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request) {
        $validate=$request->validate([
            'email'=>'required|email',
            'password'=>'required',
        ]);

        $credentials = $request->only('email', 'password');


        if (Auth::attempt($credentials)) {
            $user=Auth::user();
            return response()->json(auth()->user());
        }

        return response()->json(["errors"=>['credentials'=>[trans('auth.failed')]]],422);
    }

    public function logout() {
        Auth::logout();
    }

    public function mobileLogin(Request $request) {
        $validate=$request->validate([
            'email'=>'required|email',
            'password'=>'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = User::where('email', $request->email)->first();
            return $user->createToken('GqPhD+z54YYyZ0vd0eFfBt1Bb3Eb9S6ZZjtLtl/MVWg')->plainTextToken;
        }

        return response()->json(["errors"=>['credentials'=>[trans('auth.failed')]]],422);
    }
}
