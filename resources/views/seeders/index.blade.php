@extends('layouts.app')

@section('template_title')
    Ejecutar Seeders
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-database"></i> Gestión de Seeders</span>
                        <a href="{{ route('superadmin.modules.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver a Módulos
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success m-3">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger m-3">{{ session('error') }}</div>
                    @endif

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Clase</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($seeders as $seeder)
                                    <tr>
                                        <td>{{ $seeder['name'] }}</td>
                                        <td><code>{{ $seeder['class'] }}</code></td>
                                        <td>
                                            <form method="POST" action="{{ route('superadmin.seeders.execute') }}" onsubmit="return confirm('¿Ejecutar {{ $seeder['name'] }}?');">
                                                @csrf
                                                <input type="hidden" name="seeder" value="{{ $seeder['class'] }}">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-play"></i> Ejecutar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="alert alert-warning mt-3">
                            <i class="fas fa-shield-alt"></i> Por seguridad, solo se pueden ejecutar seeders en lista blanca.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


