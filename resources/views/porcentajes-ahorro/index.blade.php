@extends('adminlte::page')

@section('template_title', 'Porcentajes Ahorros')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0 bg-dark text-white" style="border-radius: 12px;">
                <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center" style="border-radius: 12px 12px 0 0;">
                    <h5 class="mb-0 text-uppercase font-weight-bold" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                        {{ __('Ahorros & Porcentajes') }}
                    </h5>
                    <a href="{{ route('porcentajes-ahorros.create') }}" class="btn btn-danger btn-sm">
                        <i class="fas fa-plus-circle"></i> {{ __('Nuevo') }}
                    </a>
                </div>

                @if ($message = Session::get('success'))
                    <div class="alert alert-success m-3 text-dark" style="border-radius: 8px;">{{ $message }}</div>
                @endif

                <div class="card-body bg-dark text-white p-4" style="border-radius: 0 0 12px 12px;">
                    <div class="table-responsive">
                        <table class="table table-hover table-dark text-center align-middle" style="table-layout: fixed; width: 100%; border-radius: 12px;">
                            <thead class="bg-secondary text-white">
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 20%;">Método</th>
                                    <th style="width: 13%;">% 1</th>
                                    <th style="width: 13%;">% 2</th>
                                    <th style="width: 13%;">% 3</th>
                                    <th style="width: 13%;">% 4</th>
                                    <th style="width: 23%;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($porcentajesAhorros as $index => $porcentajesAhorro)
                                    <tr style="border-radius: 8px;">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $porcentajesAhorro->metodo?->nom_metodo }}</td>
                                        <td>{{ $porcentajesAhorro->porcentaje_1 }}%</td>
                                        <td>{{ $porcentajesAhorro->porcentaje_2 }}%</td>
                                        <td>{{ $porcentajesAhorro->porcentaje_3 }}%</td>
                                        <td>{{ $porcentajesAhorro->porcentaje_4 }}%</td>
                                        <td>
                                            <a href="{{ route('porcentajes-ahorros.show', $porcentajesAhorro->id) }}" class="btn btn-sm btn-info mx-1">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('porcentajes-ahorros.edit', $porcentajesAhorro->id) }}" class="btn btn-sm btn-success mx-1">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('porcentajes-ahorros.destroy', $porcentajesAhorro->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este registro?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger mx-1">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {!! $porcentajesAhorros->withQueryString()->links() !!}
            </div>
        </div>
    </div>
</div>
@endsection
