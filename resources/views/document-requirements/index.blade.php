@extends('layouts.app')

@section('template_title')
    Gestión de Requisitos Documentales
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span id="card_title">
                            <i class="fas fa-file-alt"></i> Requisitos Documentales
                        </span>
                        @hasanyrole('Superadmin|Admin')
                        <div class="float-right">
                            <a href="{{ route('admin.document-requirements.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Nuevo Requisito
                            </a>
                        </div>
                        @endhasanyrole
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show m-4" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead">
                                <tr>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Obligatorio</th>
                                    <th>Estado</th>
                                    <th>Documentos</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($requirements as $requirement)
                                <tr>
                                    <td><strong>{{ $requirement->codigo }}</strong></td>
                                    <td>{{ $requirement->nombre }}</td>
                                    <td>
                                        @if($requirement->obligatorio)
                                            <span class="badge bg-success">Sí</span>
                                        @else
                                            <span class="badge bg-secondary">No</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input toggle-status" 
                                                   type="checkbox" 
                                                   data-id="{{ $requirement->id }}"
                                                   {{ $requirement->activo ? 'checked' : '' }}
                                                   data-url="{{ route('admin.document-requirements.toggle-status', $requirement) }}">
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $requirement->mascota_documents_count }}</span>
                                    </td>
                                    <td>
                                        <a class="btn btn-sm btn-info" href="{{ route('admin.document-requirements.show', $requirement->id) }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a class="btn btn-sm btn-success" href="{{ route('admin.document-requirements.edit', $requirement->id) }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @hasanyrole('Superadmin')
                                        <form action="{{ route('admin.document-requirements.destroy', $requirement->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endhasanyrole
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.toggle-status').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            const requirementId = this.dataset.id;
            const url = this.dataset.url;
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    motivo: 'Cambio de estado desde interfaz'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Opcional: mostrar notificación
                    console.log(data.message);
                } else {
                    // Revertir cambio
                    this.checked = !this.checked;
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                this.checked = !this.checked;
                alert('Error al cambiar el estado');
            });
        });
    });
});
</script>
@endpush
@endsection

