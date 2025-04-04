@extends('adminlte::page') 

@section('template_title')
    Conclusiones
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Conclusiones') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('conclusiones.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
									<th >Documentos</th>
									<th >Nexos</th>
									<th >Muerte Origen</th>
									<th >Reclamante</th>
									<th >Nombre Reclamante</th>
									<th >Afiliado Deja Descendiente</th>
									<th >Descendientes Relacion</th>
									<th >Descendientes Afiliado</th>
									<th >Datos Hijo</th>
									<th >Presenta Condicion Discapacidad</th>
									<th >Observaciones</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($conclusiones as $conclusione)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $conclusione->cedula_numero }}</td>
										<td >{{ $conclusione->documentos }}</td>
										<td >{{ $conclusione->nexos }}</td>
										<td >{{ $conclusione->muerte_origen }}</td>
										<td >{{ $conclusione->reclamante }}</td>
										<td >{{ $conclusione->nombre_reclamante }}</td>
										<td >{{ $conclusione->afiliado_deja_descendiente }}</td>
										<td >{{ $conclusione->descendientes_relacion }}</td>
										<td >{{ $conclusione->descendientes_afiliado }}</td>
										<td >{{ $conclusione->datos_hijo }}</td>
										<td >{{ $conclusione->presenta_condicion_discapacidad }}</td>
										<td >{{ $conclusione->observaciones }}</td>

                                            <td>
                                                <form action="{{ route('conclusiones.destroy', $conclusione->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('conclusiones.show', $conclusione->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('conclusiones.edit', $conclusione->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $conclusiones->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
