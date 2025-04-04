@extends('adminlte::page') 

@section('template_title')
    Afiliados Convivencias
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Afiliados Convivencias') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('afiliados-convivencias.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
									<th >Estado Civil Al Siniestro</th>
									<th >Desde Estado Civil</th>
									<th >Hasta Estado Civil</th>
									<th >Relacion Con</th>
									<th >Quien Convivía</th>
									<th >Tiempo Convivencia</th>
									<th >Desde Convivencia</th>
									<th >Hasta Convivencia</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($afiliadosConvivencias as $afiliadosConvivencia)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $afiliadosConvivencia->cedula_numero }}</td>
										<td >{{ $afiliadosConvivencia->estado_civil_al_siniestro }}</td>
										<td >{{ $afiliadosConvivencia->desde_estado_civil }}</td>
										<td >{{ $afiliadosConvivencia->hasta_estado_civil }}</td>
										<td >{{ $afiliadosConvivencia->relacion_con }}</td>
										<td >{{ $afiliadosConvivencia->quien_convivía }}</td>
										<td >{{ $afiliadosConvivencia->tiempo_convivencia }}</td>
										<td >{{ $afiliadosConvivencia->desde_convivencia }}</td>
										<td >{{ $afiliadosConvivencia->hasta_convivencia }}</td>

                                            <td>
                                                <form action="{{ route('afiliados-convivencias.destroy', $afiliadosConvivencia->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('afiliados-convivencias.show', $afiliadosConvivencia->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('afiliados-convivencias.edit', $afiliadosConvivencia->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $afiliadosConvivencias->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
