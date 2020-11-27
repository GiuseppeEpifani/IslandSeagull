@extends('adminlte::page')

@section('title', 'Ver Contenido')


@section('content_header')
<h1 class="text-center mb-2 font-weight-bold"><span class="span-color">C</span>ontenidos</h1>
@stop

@section('content')

<div class="container">
    <table-data-content :user_id="{{json_encode(Auth::user()->id)}}" :role="{{json_encode(Auth::user()->role)}}" />
</div>

@stop