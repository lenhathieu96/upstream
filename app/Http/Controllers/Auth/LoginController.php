<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        $pageConfigs = ['myLayout' => 'blank'];

        return view('auth.auth-login-basic', ['pageConfigs' => $pageConfigs]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:5',
        ]);
    
        if ($validator->fails()) {
            //flash($validator->messages()->first())->error();
            return back()->withErrors($validator)->withInput();
        }

        $credential = [
            'username' => $request->input('username'),
            'password' => $request->input('password'),
            'user_type' => 'super_admin',
        ];
        
        if (auth()->attempt($credential)) {
            return redirect()->route('dashboard');
        }

        //flash('The credentials did not match')->error();

        return redirect()->back()
            ->withErrors(['message' => 'The credentials did not match'])
            ->withInput();
    }

    public function logout(Request $request)
    {
        auth()->logout();
        return redirect()->route('login');
    }
}
