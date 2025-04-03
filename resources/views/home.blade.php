@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Bienvenido</h1>
@endsection

@section('content')
    <p>Estás autenticado en AdminLTE.</p>
    {{-- @livewire('contador') --}}  {{-- Ojo: si el nombre del archivo es Contador.php, se usa en minúsculas --}}


@endsection
