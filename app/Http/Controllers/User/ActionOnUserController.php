<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ActionOnUserController extends Controller
{

    public function activeUser(User $user)
    {
        if (request()->ajax() && Auth::user()->getRole() == 'Administrador') {
            $user->update(['state' => User::USER_ACTIVE]);
        } else {
            return view('home');
        }
    }

    public function deactiveUser(User $user)
    {
        if (request()->ajax() && Auth::user()->getRole() == 'Administrador') {
            $user->update(['state' => User::USER_INACTIVE]);
        } else {
            return view('home');
        }
    }
}
