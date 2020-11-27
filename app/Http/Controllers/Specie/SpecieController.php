<?php

namespace App\Http\Controllers\Specie;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Specie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class SpecieController extends Controller
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

                return datatables()->of(Specie::all())->toJson();
            }
            return view('Specie/showSpecie');
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
        if ($request->ajax() && Auth::user()->getRole() == 'Administrador') {
            $request->validate([
                'name' => ['required', 'max:100'],
                'description' => ['required', 'max:1000'],
                'path' => ['required'],
                'path.*' => 'image',
                'kinds' => ['required'],
            ]);

            $specie = Specie::create($request->all());
            $specie->kinds()->attach($request->kinds);

            foreach ($request->path as $path) {
                $image = new Image;
                $image->path = $path->store('specie');
                $image->size = filesize($path);
                $specie->images()->save($image);
            }
        } else {
            return view('home');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Specie  $specie
     * @return \Illuminate\Http\Response
     */
    public function show(Specie $specie)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Specie  $specie
     * @return \Illuminate\Http\Response
     */
    public function edit(Specie $specie)
    {
        if (request()->ajax() && Auth::user()->getRole() == 'Administrador') {
            return $specie;
        } else {
            return view('home');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Specie  $specie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Specie $specie)
    {
        if ($request->ajax() && Auth::user()->getRole() == 'Administrador') {
            $request->validate([
                'name' => ['required', 'max:100'],
                'description' => ['required', 'max:1000'],
                'path' => ['nullable'],
                'path.*' => 'image',
                'kinds' => ['required'],
            ]);
                $specie->update($request->all());

                foreach ($specie->images as $pathDelete) {
                    $deleteImage = false;
                    if ($request->preserveImage) {
                        foreach ($request->preserveImage as $pathPreserve) {
                            if ($pathDelete->path == $pathPreserve) {
                                $deleteImage = false;
                                //rompemos el ciclo anidado para que quede en false $deleteImage para que no elimine la imagen
                                break;
                            } else {
                                $deleteImage = true;
                            }
                        }
                    } else {
                        $deleteImage = true;
                    }
                    if ($deleteImage) {
                        Storage::delete($pathDelete->path);
                    }
                }

                $specie->images()->delete();
                $specie->kinds()->detach();
                $specie->kinds()->attach($request->kinds);

                if ($request->has('preserveImage')) {
                    foreach ($request->preserveImage as $pathPreserve) {
                        $image = new Image;
                        $image->path = $pathPreserve;
                        $image->size = filesize("images/" . $pathPreserve);
                        $specie->images()->save($image);
                    }
                }

                if ($request->has('path')) {
                    foreach ($request->path as $path) {
                        $image = new Image;
                        $image->path = $path->store('specie');
                        $image->size = filesize($path);
                        $specie->images()->save($image);
                    }
                }
            
        } else {
            return view('home');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Specie  $specie
     * @return \Illuminate\Http\Response
     */
    public function destroy(Specie $specie)
    {
        if (request()->ajax() && Auth::user()->getRole() == 'Administrador') {  
                foreach ($specie->images as $pathDelete) {
                    Storage::delete($pathDelete->path);
                }
                $specie->images()->delete();
                $specie->kinds()->detach();
                $specie->delete();
            
        } else {
            return view('home');
        }
    }
}
