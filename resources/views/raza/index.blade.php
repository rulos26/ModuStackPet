@extends('layouts.app')

@section('template_title')
    {{ __('Gestión de Razas') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                {{ __('Gestión de Razas') }}
                            </span>
                            <div class="float-right">
                                <a href="{{ route('razas.create') }}" class="btn btn-primary btn-sm float-right" data-placement="left">
                                    {{ __('Crear Nueva') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        <th>{{ __('Tipo Mascota') }}</th>
                                        <th>{{ __('Nombre') }}</th>
                                        <th>{{ __('Acciones') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($razas as $raza)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $raza->tipo_mascota }}</td>
                                            <td>{{ $raza->nombre }}</td>
                                            <td>
                                                <form action="{{ route('razas.destroy', $raza->id) }}" method="POST" class="d-inline">
                                                    <a class="btn btn-sm btn-primary" href="{{ route('razas.show', $raza->id) }}">
                                                        <i class="fa fa-fw fa-eye"></i> {{ __('Ver') }}
                                                    </a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('razas.edit', $raza->id) }}">
                                                        <i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}
                                                    </a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('¿Está seguro de eliminar?') }}')">
                                                        <i class="fa fa-fw fa-trash"></i> {{ __('Eliminar') }}
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
                {!! $razas->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
