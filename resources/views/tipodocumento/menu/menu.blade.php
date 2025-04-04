<form action="{{ route('tipodocumento.destroy', $dataTipoJornada->id) }}" method="POST">
    @can('tipodocumento.show')
        <a class="btn btn-sm btn-primary " href="{{ route('tipodocumento.show', $dataTipoJornada->id) }}"><i
                class="fa fa-fw fa-eye"></i> {{ __('Ver') }}</a>
    @endcan
    @can('tipodocumento.edit')
        <a class="btn btn-sm btn-success" href="{{ route('tipodocumento.edit', $dataTipoJornada->id) }}"><i
                class="fa fa-fw fa-edit"></i> {{ __('Editar') }}</a>
    @endcan
    @can('tipodocumento.destroy')
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> {{ __('Borrar') }}</button>
    @endcan
</form>
