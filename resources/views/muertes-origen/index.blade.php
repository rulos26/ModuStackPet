@extends('adminlte::page') 

@section('template_title')
    Muertes Origens
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Muertes Origens') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('muertes-origens.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Nombre</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($muertesOrigens as $muertesOrigen)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $muertesOrigen->nombre }}</td>

                                            <td>
                                                <form action="{{ route('muertes-origens.destroy', $muertesOrigen->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('muertes-origens.show', $muertesOrigen->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('muertes-origens.edit', $muertesOrigen->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $muertesOrigens->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
