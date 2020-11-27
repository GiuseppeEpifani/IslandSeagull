@extends('adminlte::page')

@section('title', 'Mi Perfil')


@section('content_header')
<h1 class="text-center mb-2 font-weight-bold"><span class="span-color">M</span>i <span class="span-color">P</span>erfil
</h1>
@stop

@section('content')
<div class="container">
    <profile-user :user_id="{{json_encode(Auth::user()->id)}}" :role="{{json_encode(Auth::user()->role)}}"
        :name="{{json_encode(Auth::user()->name)}}" />
</div>
@stop

@section('css')
@stop

@section('js')
@stop