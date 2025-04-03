@extends('adminlte::page')

@section('template_title', __('Métodos de Ahorro'))

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg border-0 bg-dark text-white" style="border-radius: 12px;">
                <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center" style="border-radius: 12px 12px 0 0;">
                    <h5 class="mb-0 text-uppercase font-weight-bold" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                        {{ __('Métodos de Ahorro') }}
                    </h5>
                    <a href="{{ route('metodos-ahorros.create') }}" class="btn btn-danger btn-sm" style="border-radius: 8px;">
                        <i class="fas fa-plus"></i> {{ __('Crear Nuevo') }}
                    </a>
                </div>

                @if ($message = Session::get('success'))
                    <div class="alert alert-success m-3 text-dark" style="border-radius: 8px;">
                        <p class="mb-0">{{ $message }}</p>
                    </div>
                @endif

                <div class="card-body bg-dark text-white p-4">
                    <div class="table-responsive">
                        <table class="table table-hover table-dark text-center align-middle" style="table-layout: fixed; width: 100%; border-radius: 12px;">
                            <thead class="bg-secondary text-white">
                                <tr>
                                   {{--  <th>#</th>
                                    <th>{{ __('Nombre del Método') }}</th>
                                    <th>{{ __('Descripción') }}</th>
                                    <th>{{ __('Acciones') }}</th> --}}
                                    <th style="width: 1%;">#</th>
                                    <th style="width: 20%;">{{ __('Nombre del Método') }}</th>
                                    <th style="width: 44%;">{{ __('Descripción') }}</th>
                                    <th style="width: 30%;">{{ __('Acciones') }}</th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($metodosAhorros as $index => $metodosAhorro)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="text-warning">{{ $metodosAhorro->nom_metodo }}</td>
                                        <td>{{ $metodosAhorro->descripcion }}</td>
                                        <td>
                                            <form action="{{ route('metodos-ahorros.destroy', $metodosAhorro->id) }}" method="POST">
                                                <a class="btn btn-sm btn-info mx-1" href="{{ route('metodos-ahorros.show', $metodosAhorro->id) }}">
                                                    <i class="fas fa-eye"></i> 
                                                </a>
                                                <a class="btn btn-sm btn-success mx-1" href="{{ route('metodos-ahorros.edit', $metodosAhorro->id) }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger mx-1" onclick="return confirm('¿Estás seguro de eliminar este método de ahorro?')">
                                                    <i class="fas fa-trash"></i> 
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer bg-secondary text-white d-flex justify-content-center mt-3">
                    {!! $metodosAhorros->withQueryString()->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection