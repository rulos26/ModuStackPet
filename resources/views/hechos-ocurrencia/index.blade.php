@extends('adminlte::page') 

@section('template_title')
    Hechos Ocurrencias
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Hechos Ocurrencias') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('hechos-ocurrencias.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
									<th >Dia</th>
									<th >Horas</th>
									<th >Lugar</th>
									<th >Motivo Muerte</th>
									<th >Otros</th>
									<th >Deceso Se Origna</th>
									<th >Donde Fallese</th>
									<th >Funeraria</th>
									<th >Fallecido</th>
									<th >Cuerpo Fue</th>
									<th >Servicos Funerarios</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($hechosOcurrencias as $hechosOcurrencia)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $hechosOcurrencia->cedula_numero }}</td>
										<td >{{ $hechosOcurrencia->dia }}</td>
										<td >{{ $hechosOcurrencia->horas }}</td>
										<td >{{ $hechosOcurrencia->lugar }}</td>
										<td >{{ $hechosOcurrencia->motivo_muerte }}</td>
										<td >{{ $hechosOcurrencia->otros }}</td>
										<td >{{ $hechosOcurrencia->deceso_se_origna }}</td>
										<td >{{ $hechosOcurrencia->donde_fallese }}</td>
										<td >{{ $hechosOcurrencia->funeraria }}</td>
										<td >{{ $hechosOcurrencia->fallecido }}</td>
										<td >{{ $hechosOcurrencia->cuerpo_fue }}</td>
										<td >{{ $hechosOcurrencia->servicos_funerarios }}</td>

                                            <td>
                                                <form action="{{ route('hechos-ocurrencias.destroy', $hechosOcurrencia->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('hechos-ocurrencias.show', $hechosOcurrencia->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('hechos-ocurrencias.edit', $hechosOcurrencia->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $hechosOcurrencias->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
