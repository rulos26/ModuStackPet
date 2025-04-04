@extends('adminlte::page')

@section('template_title')
Carta Autorizaciones
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">

                        <span id="card_title">
                            {{ __('Carta Autorizaciones') }}
                        </span>

                        <div class="float-right">
                            
                        </div>
                    </div>
                </div>
                @if ($message = Session::get('success'))
                <div class="alert alert-success m-4">
                    <p>{{ $message }}</p>
                </div>
                @endif

                <div class="card-body bg-white">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead">
                                <tr>

                                    <th>Cedula</th>
                                    <th>Firmado</th>

                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cartaAutorizaciones as $cartaAutorizacione)
                                <tr>

                                    <td>{{ $cartaAutorizacione->cedula }}</td>
                                    @if ($cartaAutorizacione->firmado == 0)
                                    <td>
                                        <a class="btn btn-sm btn-danger "
                                        href="{{ route('carta-autorizaciones.show', $cartaAutorizacione->id) }}"><i
                                            class="fas fa-signature"></i> {{ __('Firmar') }}</a>
                                    </td>
                                    @else
                                   <td>
                                    <a class="btn btn-sm btn-primary"
                                    href="{{ route('reporte.carta', $cartaAutorizacione->id) }}"><i
                                        class="fa fa-fw fa-eye"></i> {{ __('Ver Carta') }}</a>
                                   </td>
                                    @endif
                                   
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between mt-3">
                {{-- <a href="{{ route('photos.index') }}" class="btn btn-secondary">Atrás</a>
                <a href=" {{ route('transitos.index') }}" class="btn btn-primary">Siguiente</a> --}}
            </div>
        </div>
    </div>
</div>
@endsection