@extends('adminlte::page') 

@section('template_title')
    Datos Basicos
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Datos Basicos') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('datos-basicos.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Create New') }}
                                </a>
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
                                        <th>No</th>
                                        
									<th >Cedula Numero</th>
									<th >Nombre Afiliado</th>
									<th >Caso</th>
									<th >Fecha</th>
									<th >Estado Civil</th>
									<th >Amparo</th>
									<th >Tipo De Convivencia</th>
									<th >Otro</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($datosBasicos as $datosBasico)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $datosBasico->cedula_numero }}</td>
										<td >{{ $datosBasico->nombre_afiliado }}</td>
										<td >{{ $datosBasico->caso }}</td>
										<td >{{ $datosBasico->fecha }}</td>
										<td >{{ $datosBasico->estado_civil }}</td>
										<td >{{ $datosBasico->amparo }}</td>
										<td >{{ $datosBasico->tipo_de_convivencia }}</td>
										<td >{{ $datosBasico->otro }}</td>

                                            <td>
                                                <form action="{{ route('datos-basicos.destroy', $datosBasico->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('datos-basicos.show', $datosBasico->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('datos-basicos.edit', $datosBasico->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>
                                                </form>
                                            </td>
                                            <td>
                                                <a href="{{ route('qr.generar', $datosBasico->cedula_numero) }}" class="btn btn-primary">
                                                    Generar QR
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $datosBasicos->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
