<?php

namespace App\Http\Controllers\Specie;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Kind;

class ShowKindController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        if (request()->ajax() && Auth::user()->getRole() == 'Administrador') {
            return Kind::all();
        } else {
            return view('home');
        }
    }
}
