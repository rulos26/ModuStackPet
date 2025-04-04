@extends('adminlte::page') 

@section('template_title')
    Verificaciones
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Verificaciones') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('verificaciones.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
									<th >Cedula Afiliado</th>
									<th >Registro Civil Nacimiento Afiliado</th>
									<th >Registro Defuncion Afiliado</th>
									<th >Cedula Reclamante</th>
									<th >Registro Civil Nacimiento Descendiente</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($verificaciones as $verificacione)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $verificacione->cedula_numero }}</td>
										<td >{{ $verificacione->cedula_afiliado }}</td>
										<td >{{ $verificacione->registro_civil_nacimiento_afiliado }}</td>
										<td >{{ $verificacione->registro_defuncion_afiliado }}</td>
										<td >{{ $verificacione->cedula_reclamante }}</td>
										<td >{{ $verificacione->registro_civil_nacimiento_descendiente }}</td>

                                            <td>
                                                <form action="{{ route('verificaciones.destroy', $verificacione->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('verificaciones.show', $verificacione->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('verificaciones.edit', $verificacione->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $verificaciones->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
