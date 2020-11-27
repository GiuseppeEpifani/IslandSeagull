<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax() && Auth::user()->getRole() == 'Administrador') {
            return datatables()->of(Content::all())->toJson();

        } else if (request()->ajax() && Auth::user()->getRole() == 'Cientifico') {
            $content = Content::where('user_id', Auth::user()->id);
            return datatables()->of($content)->toJson();

        } else {
            
            if (Auth::user()->getRole() == 'Administrador' || Auth::user()->getRole() == 'Cientifico') {
                return view('Content/showContent');
            } else {
                return view('home');
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->getRole() == 'Administrador' ||  Auth::user()->getRole() == 'Cientifico') {
            return view('Content/addContent');
        } else {
            return view('home');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (
            $request->ajax() && Auth::user()->getRole() == 'Administrador' ||
            $request->ajax() && Auth::user()->getRole() == 'Cientifico'
        ) {
            $request->validate([
                'title' => ['required', 'max:100'],
                'description' => ['required', 'max:1000'],
                'path' => ['required'],
                'path.*' => 'image',
                'user_id' => 'required|integer',

            ]);

            $content = Content::create($request->all());

            foreach ($request->path as $path) {
                $image = new Image;
                $image->path = $path->store('content');
                $image->size = filesize($path);
                $content->images()->save($image);
            }
        } else {
            return view('home');
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function show(Content $content)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function edit(Content $content)
    {
        if (
            request()->ajax() && Auth::user()->getRole() == 'Administrador' ||
            request()->ajax() && Auth::user()->getRole() == 'Cientifico' && Auth::user()->id == $content->user_id
        ) {
            return $content;
        } else {
            return view('home');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Content $content)
    {
        if (
            request()->ajax() && Auth::user()->getRole() == 'Administrador' ||
            request()->ajax() && Auth::user()->getRole() == 'Cientifico' && Auth::user()->id == $content->user_id
        ) {
            $request->validate([
                'title' => ['required', 'max:100'],
                'description' => ['required', 'max:1000'],
                'user_id' => 'required|integer',
                'path' => ['nullable'],
                'path.*' => 'image',
            ]);
            $content->update($request->all());

            foreach ($content->images as $pathDelete) {
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

            $content->images()->delete();

            if ($request->has('preserveImage')) {
                foreach ($request->preserveImage as $pathPreserve) {
                    $image = new Image;
                    $image->path = $pathPreserve;
                    $image->size = filesize("images/" . $pathPreserve);
                    $content->images()->save($image);
                }
            }

            if ($request->has('path')) {
                foreach ($request->path as $path) {
                    $image = new Image;
                    $image->path = $path->store('content');
                    $image->size = filesize($path);
                    $content->images()->save($image);
                }
            }
        } else {
            return view('home');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function destroy(Content $content)
    {
        if (
            request()->ajax() && Auth::user()->getRole() == 'Administrador' ||
            request()->ajax() && Auth::user()->getRole() == 'Cientifico' && Auth::user()->id == $content->user_id
        ) {
            foreach ($content->images as $pathDelete) {
                Storage::delete($pathDelete->path);
            }
            $content->images()->delete();
            $content->delete();
        } else {
            return view('home');
        }
    }
}
