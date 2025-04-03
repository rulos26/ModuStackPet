@extends('layouts.app')

@section('template_title')
    Datos Ahorros
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Datos Ahorros') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('datos-ahorros.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >User Id</th>
									<th >Sueldo</th>
									<th >Metodo Ahorro Id</th>
									<th >Fecha Inicio</th>
									<th >Fecha Fin</th>
									<th >Mes Id</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($datosAhorros as $datosAhorro)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $datosAhorro->user_id }}</td>
										<td >{{ $datosAhorro->sueldo }}</td>
										<td >{{ $datosAhorro->metodo_ahorro_id }}</td>
										<td >{{ $datosAhorro->fecha_inicio }}</td>
										<td >{{ $datosAhorro->fecha_fin }}</td>
										<td >{{ $datosAhorro->mes_id }}</td>

                                            <td>
                                                <form action="{{ route('datos-ahorros.destroy', $datosAhorro->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('datos-ahorros.show', $datosAhorro->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('datos-ahorros.edit', $datosAhorro->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $datosAhorros->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
