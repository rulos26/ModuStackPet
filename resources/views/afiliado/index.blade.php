@extends('adminlte::page') 

@section('template_title')
    Afiliados
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Afiliados') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('afiliados.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
									<th >Edad</th>
									<th >Fecha Nacimiento</th>
									<th >Departamento</th>
									<th >Municipio</th>
									<th >Fecha Expedicion</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($afiliados as $afiliado)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $afiliado->cedula_numero }}</td>
										<td >{{ $afiliado->edad }}</td>
										<td >{{ $afiliado->fecha_nacimiento }}</td>
										<td >{{ $afiliado->departamento }}</td>
										<td >{{ $afiliado->municipio }}</td>
										<td >{{ $afiliado->fecha_expedicion }}</td>

                                            <td>
                                                <form action="{{ route('afiliados.destroy', $afiliado->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('afiliados.show', $afiliado->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('afiliados.edit', $afiliado->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $afiliados->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
