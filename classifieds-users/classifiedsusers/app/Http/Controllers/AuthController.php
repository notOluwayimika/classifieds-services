<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
        // dd($credentials);
        if (Auth::attempt($credentials, true)) {
            // $request->session()->regenerate();
            return  response()->json([
                'message' => 'logged in',
                'user' => Auth::user()
            ]);
        }
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function register(Request $request)
    {
        try {
            $credentials = $request->validate([
                'name' => 'required',
                'email' => 'required',
                'password' => 'required'
            ]);
            $credentials['password'] = bcrypt($credentials['password']);
            $user = User::create($credentials);
            // dd($request->input('shop'));
            if ($request->input('shop')) {
                $user->role = "shop";
                $user->shopId = $request->input('index')+5;
            } else {
                $user->role = "user";
            }
            $user->email_verified_at = now();
            $user->setRememberToken($token = Str::random(60));
            $user->save();
            return  response()->json([
                'message' => 'Registered',
                'user' => $user
            ]);
        } catch (Exception $e) {
            return response('failure', $e->getMessage());
        }
    }
    public function update($id, Request $request)
    {
        try {
            $credentials = $request->validate([
                'name' => 'required',
                'email' => 'required',
                'password'=>'required'
            ]);
            $user = User::findOrFail($id);
            $user->name=$credentials['name'];
            $user->email=$credentials['email'];
            $user->password=$credentials['password'];
            $user->save();
            return  response()->json([
                'message' => 'Updated',
                'user' => $user
            ]);
        } catch (Exception $e) {
            return response('failure', $e->getMessage());
        }
    }

    public function destroy($id){
        User::findOrFail($id)->delete();
    }
}
