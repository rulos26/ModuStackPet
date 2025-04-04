@extends('adminlte::page')

@section('template_title')
    Empleos Afiliados
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Empleos Afiliados') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('empleos-afiliados.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
									<th >Afiliado Trabaja</th>
									<th >Empresa</th>
									<th >Cargo</th>
									<th >Tiempo</th>
									<th >Salario</th>
									<th >No Telefonico</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($empleosAfiliados as $empleosAfiliado)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $empleosAfiliado->cedula_numero }}</td>
										<td >{{ $empleosAfiliado->afiliado_trabaja }}</td>
										<td >{{ $empleosAfiliado->empresa }}</td>
										<td >{{ $empleosAfiliado->cargo }}</td>
										<td >{{ $empleosAfiliado->tiempo }}</td>
										<td >{{ $empleosAfiliado->salario }}</td>
										<td >{{ $empleosAfiliado->no_telefonico }}</td>

                                            <td>
                                                <form action="{{ route('empleos-afiliados.destroy', $empleosAfiliado->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('empleos-afiliados.show', $empleosAfiliado->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('empleos-afiliados.edit', $empleosAfiliado->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $empleosAfiliados->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
