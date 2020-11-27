<?php

namespace App\Http\Controllers\Specie;

use App\Http\Controllers\Controller;
use App\Models\Kind;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class KindController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->getRole() == 'Administrador') {
            if (request()->ajax()) {

                return datatables()->of(Kind::all())->toJson();
            }
            return view('Specie/showKind');
        }
        return view('home');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (request()->ajax() && Auth::user()->getRole() == 'Administrador') {

            $request->validate([
                'name' => ['required', 'max:100'],
            ]);
            $kindMin = strtolower($request->name);
            $kind = ucwords($kindMin);
            $kindExist = Kind::where('name', $kind)->first();
            if ($kindExist != '' || $kindExist != null) {
                return 'false';
            } else {
                return Kind::create(['name' => $kind]);
            }
        } else {
            return view('home');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Kind  $kind
     * @return \Illuminate\Http\Response
     */
    public function show(Kind $kind)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Kind  $kind
     * @return \Illuminate\Http\Response
     */
    public function edit(Kind $kind)
    {
        if (request()->ajax() && Auth::user()->getRole() == 'Administrador') {
            return $kind;
        } else {
            return view('home');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Kind  $kind
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kind $kind)
    {
        if (request()->ajax() && Auth::user()->getRole() == 'Administrador') {

            $request->validate([
                'name' => ['required', 'max:100'],
            ]);
            $kindMin = strtolower($request->name);
            $kindFormat = ucwords($kindMin);
            $kindExist = Kind::where('name', $kindFormat)->first();
            if ($kindExist != '' || $kindExist != null) {
                return 'false';
            } else {
               $kind->update(['name' => $kindFormat]);
            }
        } else {
            return view('home');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Kind  $kind
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kind $kind)
    {
        if (request()->ajax() && Auth::user()->getRole() == 'Administrador') {
           $kind->delete();            
        } else {
            return view('home');
        }
    }
}
