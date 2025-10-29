@extends('layouts.app')

@section('template_title')
    Editar Configuración
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                <i class="fas fa-edit"></i> Editar Configuración
                            </span>
                            <a href="{{ route('configuraciones.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show m-4" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                        </div>
                    @endif

                    <div class="card-body">
                        <form method="POST" action="{{ route('configuraciones.update', $configuracion->id) }}" role="form">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="clave" class="form-label">Clave de Configuración</label>
                                <input type="text" class="form-control" id="clave" name="clave" value="{{ $configuracion->clave }}" disabled>
                                <div class="form-text">La clave no se puede modificar</div>
                            </div>

                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <input type="text" class="form-control" id="descripcion" name="descripcion" value="{{ $configuracion->descripcion }}" disabled>
                                <div class="form-text">La descripción no se puede modificar desde aquí</div>
                            </div>

                            <div class="mb-3">
                                <label for="valor" class="form-label">Valor</label>
                                @if($configuracion->tipo == 'number')
                                    <input type="number" class="form-control @error('valor') is-invalid @enderror"
                                           id="valor" name="valor" value="{{ $configuracion->valor }}"
                                           required>
                                @elseif($configuracion->tipo == 'boolean')
                                    <select class="form-control @error('valor') is-invalid @enderror"
                                            id="valor" name="valor" required>
                                        <option value="1" {{ $configuracion->valor == '1' ? 'selected' : '' }}>Activo (true)</option>
                                        <option value="0" {{ $configuracion->valor == '0' ? 'selected' : '' }}>Inactivo (false)</option>
                                    </select>
                                @else
                                    <input type="text" class="form-control @error('valor') is-invalid @enderror"
                                           id="valor" name="valor" value="{{ $configuracion->valor }}"
                                           required>
                                @endif
                                @error('valor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <strong>Tipo:</strong> {{ $configuracion->tipo }} |
                                    <strong>Categoría:</strong> {{ $configuracion->categoria }}
                                </div>
                            </div>

                            @if($configuracion->clave == 'session_timeout')
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Nota:</strong> Este valor debe estar en segundos.
                                    <ul class="mb-0 mt-2">
                                        <li>60 segundos = 1 minuto</li>
                                        <li>1800 segundos = 30 minutos (valor por defecto)</li>
                                        <li>3600 segundos = 1 hora</li>
                                        <li>14400 segundos = 4 horas (máximo permitido)</li>
                                    </ul>
                                </div>
                            @endif

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('configuraciones.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Guardar Cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

