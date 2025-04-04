@extends('adminlte::page') 

@section('template_title')
    Direcciones Viviendas
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Direcciones Viviendas') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('direcciones-viviendas.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
									<th >Direccion Residencia</th>
									<th >Tipo De Vivienda</th>
									<th >Tipo De Propiedad</th>
									<th >Vive Desde</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($direccionesViviendas as $direccionesVivienda)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $direccionesVivienda->cedula_numero }}</td>
										<td >{{ $direccionesVivienda->direccion_residencia }}</td>
										<td >{{ $direccionesVivienda->tipo_de_vivienda }}</td>
										<td >{{ $direccionesVivienda->tipo_de_propiedad }}</td>
										<td >{{ $direccionesVivienda->vive_desde }}</td>

                                            <td>
                                                <form action="{{ route('direcciones-viviendas.destroy', $direccionesVivienda->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('direcciones-viviendas.show', $direccionesVivienda->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('direcciones-viviendas.edit', $direccionesVivienda->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $direccionesViviendas->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
