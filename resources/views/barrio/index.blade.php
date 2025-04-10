@extends('layouts.app')

@section('template_title')
    Barrios
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                {{ __('Gestión de Barrios') }}
                            </span>
                            <div class="float-right">
                                <a href="{{ route('barrios.create') }}" class="btn btn-primary" data-placement="left">
                                    {{ __('Crear Nuevo') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>

                                        <th>{{ __('Nombre') }}</th>
                                        <th>{{ __('Localidad') }}</th>
                                        <th>{{ __('Fecha de Creación') }}</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($barrios as $barrio)
                                        <tr>
                                            <td>{{ ++$i ?? $loop->iteration }}</td>

                                            <td>{{ $barrio->nombre }}</td>
                                            <td>{{ $barrio->localidad }}</td>
                                            <td>{{ $barrio->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                <form action="{{ route('barrios.destroy',$barrio->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-success" href="{{ route('barrios.edit',$barrio->id) }}">
                                                        <i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}
                                                    </a>
                                                    <a class="btn btn-sm btn-info" href="{{ route('barrios.show',$barrio->id) }}">
                                                        <i class="fa fa-fw fa-eye"></i> {{ __('Ver') }}
                                                    </a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
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
                    <div class="card-footer">
                        {!! $barrios->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
