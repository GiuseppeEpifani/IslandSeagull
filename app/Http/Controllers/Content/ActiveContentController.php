<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Content;

class ActiveContentController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Content $content)
    {
        if (request()->ajax() && Auth::user()->getRole() == 'Administrador') {
            $content->update(['state' => Content::CONTENT_ACTIVE]);
        } else {
            return view('home');
        }
    }
}
