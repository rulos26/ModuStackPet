@extends('adminlte::page') 

@section('template_title')
    Siniestros
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Siniestros') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('siniestros.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
									<th >Fecha Siniestro</th>
									<th >Lugar</th>
									<th >Departamento</th>
									<th >Municipio</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($siniestros as $siniestro)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $siniestro->cedula_numero }}</td>
										<td >{{ $siniestro->fecha_siniestro }}</td>
										<td >{{ $siniestro->lugar }}</td>
										<td >{{ $siniestro->departamento }}</td>
										<td >{{ $siniestro->municipio }}</td>

                                            <td>
                                                <form action="{{ route('siniestros.destroy', $siniestro->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('siniestros.show', $siniestro->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('siniestros.edit', $siniestro->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $siniestros->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
