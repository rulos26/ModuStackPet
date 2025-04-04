@extends('adminlte::page')

@section('template_title')
    Datos Basicos Hijos
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Datos Basicos Hijos') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('datos-basicos-hijos.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
									<th >Numero Hijos</th>
									<th >Nombre</th>
									<th >Tipo Documento</th>
									<th >Documento</th>
									<th >Edad</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($datosBasicosHijos as $datosBasicosHijo)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $datosBasicosHijo->cedula_numero }}</td>
										<td >{{ $datosBasicosHijo->numero_hijos }}</td>
										<td >{{ $datosBasicosHijo->nombre }}</td>
										<td >{{ $datosBasicosHijo->tipo_documento }}</td>
										<td >{{ $datosBasicosHijo->documento }}</td>
										<td >{{ $datosBasicosHijo->edad }}</td>

                                            <td>
                                                <form action="{{ route('datos-basicos-hijos.destroy', $datosBasicosHijo->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('datos-basicos-hijos.show', $datosBasicosHijo->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('datos-basicos-hijos.edit', $datosBasicosHijo->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $datosBasicosHijos->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
