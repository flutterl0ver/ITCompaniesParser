<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function __invoke(Request $request)
    {
        $login = $request->input('login');
        $email = $request->input('email');
        $password = $request->input('password');
        $confirmPassword = $request->input('confirmPassword');

        $loginExists = User::where('login', $login)->exists();

        if($password != $confirmPassword || $loginExists) {
            return redirect()->back()->withInput(['login' => $login, 'email' => $email]);
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);
        User::create([
            'login' => $login,
            'email' => $email,
            'password' => $hash
        ]);
        $cookie = cookie('login', $login, 43200);
        return redirect('/companies')->withCookie($cookie);
    }

    public function login(Request $request)
    {
        $login = $request->input('login');
        $password = $request->input('password');

        $user = User::where('email', $login)->first();
        if($user == null) $user = User::where('login', $login)->first();
        if($user == null) return redirect()->back();

        $hash = $user->password;
        if(password_verify($password, $hash)) {
            $cookie = cookie('login', $login, 43200);
            return redirect('/companies')->withCookie($cookie);
        }
        else return redirect()->back();
    }

    public function leave(Request $request)
    {
        $cookie = cookie('login', '', -1);
        return redirect()->back()->withCookie($cookie);
    }
}
