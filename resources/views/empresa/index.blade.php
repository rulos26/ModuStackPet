@extends('layouts.app')

@section('template_title')
    Empresas
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Empresas') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('empresas.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Nombre Legal</th>
									<th >Nombre Comercial</th>
									<th >Nit</th>
									<th >Dv</th>
									<th >Representante Legal</th>
									<th >Tipo Empresa Id</th>
									<th >Telefono</th>
									<th >Email</th>
									<th >Direccion</th>
									<th >Ciudad Id</th>
									<th >Departamento Id</th>
									<th >Sector Id</th>
									<th >Logo</th>
									<th >Estado</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($empresas as $empresa)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $empresa->nombre_legal }}</td>
										<td >{{ $empresa->nombre_comercial }}</td>
										<td >{{ $empresa->nit }}</td>
										<td >{{ $empresa->dv }}</td>
										<td >{{ $empresa->representante_legal }}</td>
										<td >{{ $empresa->tipo_empresa_id }}</td>
										<td >{{ $empresa->telefono }}</td>
										<td >{{ $empresa->email }}</td>
										<td >{{ $empresa->direccion }}</td>
										<td >{{ $empresa->ciudad_id }}</td>
										<td >{{ $empresa->departamento_id }}</td>
										<td >{{ $empresa->sector_id }}</td>
										<td >{{ $empresa->logo }}</td>
										<td >{{ $empresa->estado }}</td>

                                            <td>
                                                <form action="{{ route('empresas.destroy', $empresa->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('empresas.show', $empresa->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('empresas.edit', $empresa->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $empresas->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
