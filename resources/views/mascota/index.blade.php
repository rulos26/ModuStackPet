@extends('layouts.app')

@section('template_title')
    {{ __('Gestión de Mascotas') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                {{ __('Gestión de Mascotas') }}
                            </span>
                            <div class="float-right">
                                <a href="{{ route('mascotas.create') }}" class="btn btn-primary btn-sm float-right" data-placement="left">
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
                                        <th>{{ __('Avatar') }}</th>
                                        <th>{{ __('Nombre') }}</th>
                                        <th>{{ __('Raza') }}</th>
                                        <th>{{ __('Barrio') }}</th>
                                        <th>{{ __('Acciones') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mascotas as $mascota)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>
                                                @if($mascota->avatar)
                                                    <img src="{{ asset('public/' . $mascota->avatar) }}" alt="Avatar de {{ $mascota->nombre }}" class="img-thumbnail" style="max-width: 50px;">
                                                @else
                                                    <i class="fa fa-paw"></i>
                                                @endif
                                            </td>
                                            <td>{{ $mascota->nombre }}</td>
                                            <td>{{ $mascota->raza->nombre ?? 'No especificada' }}</td>
                                            <td>{{ $mascota->barrio->nombre ?? 'No especificado' }}</td>
                                            <td>
                                                <form action="{{ route('mascotas.destroy', $mascota->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary" href="{{ route('mascotas.show', $mascota->id) }}">
                                                        <i class="fa fa-fw fa-eye"></i> {{ __('Ver') }}
                                                    </a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('mascotas.edit', $mascota->id) }}">
                                                        <i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}
                                                    </a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('{{ __('¿Está seguro de eliminar?') }}') ? this.closest('form').submit() : false;">
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
                {!! $mascotas->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
