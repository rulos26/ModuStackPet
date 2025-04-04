@extends('adminlte::page')

@section('template_title')
    Afiliados Coberturas Saluds
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Afiliados Coberturas Saluds') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('afiliados-coberturas-saluds.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
									<th >Cobertura Salud</th>
									<th >Tipo Afiliacion</th>
									<th >Regimen</th>
									<th >Desde</th>
									<th >Registra Beneficiarios</th>
									<th >Quien Reclama Prestaciones Sociales</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($afiliadosCoberturasSaluds as $afiliadosCoberturasSalud)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $afiliadosCoberturasSalud->cedula_numero }}</td>
										<td >{{ $afiliadosCoberturasSalud->cobertura_salud }}</td>
										<td >{{ $afiliadosCoberturasSalud->tipo_afiliacion }}</td>
										<td >{{ $afiliadosCoberturasSalud->regimen }}</td>
										<td >{{ $afiliadosCoberturasSalud->desde }}</td>
										<td >{{ $afiliadosCoberturasSalud->registra_beneficiarios }}</td>
										<td >{{ $afiliadosCoberturasSalud->quien_reclama_prestaciones_sociales }}</td>

                                            <td>
                                                <form action="{{ route('afiliados-coberturas-saluds.destroy', $afiliadosCoberturasSalud->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('afiliados-coberturas-saluds.show', $afiliadosCoberturasSalud->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('afiliados-coberturas-saluds.edit', $afiliadosCoberturasSalud->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $afiliadosCoberturasSaluds->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
