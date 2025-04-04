@extends('adminlte::page')

@section('template_title')
    Photos
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Photos') }}
                            </span>

                             <div class="float-right">
                                
                                @if ($creado == 0)
                                <a href="{{ route('photos.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                    {{ __('Create New') }}
                                  </a>
                                @else
                                <a href="{{ route('reporte.evaluado') }}" class="btn btn-primary btn-sm float-left" data-placement="left">
                                    {{ __('reporte') }}
                                </a>
                                @endif
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
                                         <th>Foto</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($photos as $photo)
                                        <tr>
                                            <td>
                                                <img src="{{ asset('images/' . $photo->cedula . '/' . $photo->path) }}" alt="Foto" style="width: 100px; height: auto;">
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-success" href="{{ route('photos.edit', $photo->id) }}">
                                                    <i class="fa fa-fw fa-edit"></i> {{ __('Cambiar Foto De perfil') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('informaciones-academicas.index') }}" class="btn btn-secondary">Atrás</a>
                    <a href=" {{ route('carta-autorizaciones.index') }}" class="btn btn-primary">Siguiente</a>
                </div>
            </div>
        </div>
    </div>
@endsection
