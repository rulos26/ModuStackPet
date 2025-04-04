@extends('adminlte::page')

@section('template_title')
    Personas Afiliadas
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Personas Afiliadas') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('personas-afiliadas.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Nombres Apellidos</th>
									<th >Cedula</th>
									<th >Edad</th>
									<th >Fecha Nacimiento</th>
									<th >Lugar Nacimiento Id</th>
									<th >Fecha Siniestro</th>
									<th >Lugar Siniestro Id</th>
									<th >Estado Civil Siniestro Id</th>
									<th >Estado Civil Desde</th>
									<th >Estado Civil Hasta</th>
									<th >Hijos</th>
									<th >Edad Hijos</th>
									<th >Relacion Con</th>
									<th >Convivencia Con</th>
									<th >Tiempo Convivencia</th>
									<th >Direccion Residencia</th>
									<th >Titular Trabaja</th>
									<th >Empresa</th>
									<th >Cargo</th>
									<th >Tiempo Laboral</th>
									<th >Salario</th>
									<th >Telefono</th>
									<th >Cobertura Salud Id</th>
									<th >Tipo Afiliacion Id</th>
									<th >Registra Beneficiarios</th>
									<th >Observaciones</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($personasAfiliadas as $personasAfiliada)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $personasAfiliada->nombres_apellidos }}</td>
										<td >{{ $personasAfiliada->cedula }}</td>
										<td >{{ $personasAfiliada->edad }}</td>
										<td >{{ $personasAfiliada->fecha_nacimiento }}</td>
										<td >{{ $personasAfiliada->lugar_nacimiento_id }}</td>
										<td >{{ $personasAfiliada->fecha_siniestro }}</td>
										<td >{{ $personasAfiliada->lugar_siniestro_id }}</td>
										<td >{{ $personasAfiliada->estado_civil_siniestro_id }}</td>
										<td >{{ $personasAfiliada->estado_civil_desde }}</td>
										<td >{{ $personasAfiliada->estado_civil_hasta }}</td>
										<td >{{ $personasAfiliada->hijos }}</td>
										<td >{{ $personasAfiliada->edad_hijos }}</td>
										<td >{{ $personasAfiliada->relacion_con }}</td>
										<td >{{ $personasAfiliada->convivencia_con }}</td>
										<td >{{ $personasAfiliada->tiempo_convivencia }}</td>
										<td >{{ $personasAfiliada->direccion_residencia }}</td>
										<td >{{ $personasAfiliada->titular_trabaja }}</td>
										<td >{{ $personasAfiliada->empresa }}</td>
										<td >{{ $personasAfiliada->cargo }}</td>
										<td >{{ $personasAfiliada->tiempo_laboral }}</td>
										<td >{{ $personasAfiliada->salario }}</td>
										<td >{{ $personasAfiliada->telefono }}</td>
										<td >{{ $personasAfiliada->cobertura_salud_id }}</td>
										<td >{{ $personasAfiliada->tipo_afiliacion_id }}</td>
										<td >{{ $personasAfiliada->registra_beneficiarios }}</td>
										<td >{{ $personasAfiliada->observaciones }}</td>

                                            <td>
                                                <form action="{{ route('personas-afiliadas.destroy', $personasAfiliada->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('personas-afiliadas.show', $personasAfiliada->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('personas-afiliadas.edit', $personasAfiliada->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $personasAfiliadas->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
