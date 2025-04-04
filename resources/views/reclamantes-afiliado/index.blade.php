@extends('adminlte::page') 

@section('template_title')
    Reclamantes Afiliados
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Reclamantes Afiliados') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('reclamantes-afiliados.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
									<th >Reclamante</th>
									<th >Nombre</th>
									<th >Tipo Documento</th>
									<th >Cedula Reclamante</th>
									<th >Fecha Nacimiento</th>
									<th >Ciudad Nacimiento</th>
									<th >Departamento Nacimiento</th>
									<th >Edad</th>
									<th >Fecha Expedicion</th>
									<th >Ciudad Expedicion</th>
									<th >Departamento Expedicion</th>
									<th >Estado Civil</th>
									<th >Desde Convivencia</th>
									<th >Hasta Convivencia</th>
									<th >Compartieron Techo Mesa Lecho</th>
									<th >Afiliado Relacion Quedaron Hijos</th>
									<th >Datos Basicos Hijo</th>
									<th >Direccion Siniestro</th>
									<th >Direccion Actual</th>
									<th >Barrio</th>
									<th >Ciudad</th>
									<th >Vivienda</th>
									<th >Canon Arrendamiento</th>
									<th >Tiempo Residencia</th>
									<th >Movil</th>
									<th >Activa Laboralmente Siniestro</th>
									<th >Trabajaba Empresa</th>
									<th >Ocupacion</th>
									<th >Salario</th>
									<th >Tiempo Trabajo</th>
									<th >Coberturas Salud</th>
									<th >Tipos Afiliaciones</th>
									<th >Regimen</th>
									<th >Estado Afiliacion</th>
									<th >Registra Beneficiarios Eps</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reclamantesAfiliados as $reclamantesAfiliado)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $reclamantesAfiliado->cedula_numero }}</td>
										<td >{{ $reclamantesAfiliado->reclamante }}</td>
										<td >{{ $reclamantesAfiliado->nombre }}</td>
										<td >{{ $reclamantesAfiliado->tipo_documento }}</td>
										<td >{{ $reclamantesAfiliado->cedula_reclamante }}</td>
										<td >{{ $reclamantesAfiliado->fecha_nacimiento }}</td>
										<td >{{ $reclamantesAfiliado->ciudad_nacimiento }}</td>
										<td >{{ $reclamantesAfiliado->departamento_nacimiento }}</td>
										<td >{{ $reclamantesAfiliado->edad }}</td>
										<td >{{ $reclamantesAfiliado->fecha_expedicion }}</td>
										<td >{{ $reclamantesAfiliado->ciudad_expedicion }}</td>
										<td >{{ $reclamantesAfiliado->departamento_expedicion }}</td>
										<td >{{ $reclamantesAfiliado->estado_civil }}</td>
										<td >{{ $reclamantesAfiliado->desde_convivencia }}</td>
										<td >{{ $reclamantesAfiliado->hasta_convivencia }}</td>
										<td >{{ $reclamantesAfiliado->compartieron_techo_mesa_lecho }}</td>
										<td >{{ $reclamantesAfiliado->afiliado_relacion_quedaron_hijos }}</td>
										<td >{{ $reclamantesAfiliado->datos_basicos_hijo }}</td>
										<td >{{ $reclamantesAfiliado->direccion_siniestro }}</td>
										<td >{{ $reclamantesAfiliado->direccion_actual }}</td>
										<td >{{ $reclamantesAfiliado->barrio }}</td>
										<td >{{ $reclamantesAfiliado->ciudad }}</td>
										<td >{{ $reclamantesAfiliado->vivienda }}</td>
										<td >{{ $reclamantesAfiliado->canon_arrendamiento }}</td>
										<td >{{ $reclamantesAfiliado->tiempo_residencia }}</td>
										<td >{{ $reclamantesAfiliado->movil }}</td>
										<td >{{ $reclamantesAfiliado->activa_laboralmente_siniestro }}</td>
										<td >{{ $reclamantesAfiliado->trabajaba_empresa }}</td>
										<td >{{ $reclamantesAfiliado->ocupacion }}</td>
										<td >{{ $reclamantesAfiliado->salario }}</td>
										<td >{{ $reclamantesAfiliado->tiempo_trabajo }}</td>
										<td >{{ $reclamantesAfiliado->coberturas_salud }}</td>
										<td >{{ $reclamantesAfiliado->tipos_afiliaciones }}</td>
										<td >{{ $reclamantesAfiliado->regimen }}</td>
										<td >{{ $reclamantesAfiliado->estado_afiliacion }}</td>
										<td >{{ $reclamantesAfiliado->registra_beneficiarios_eps }}</td>

                                            <td>
                                                <form action="{{ route('reclamantes-afiliados.destroy', $reclamantesAfiliado->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('reclamantes-afiliados.show', $reclamantesAfiliado->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('reclamantes-afiliados.edit', $reclamantesAfiliado->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $reclamantesAfiliados->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
