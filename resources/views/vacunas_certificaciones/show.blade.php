@extends('layouts.app')

@section('template_title')
    Detalles del Registro de Vacunas y Certificaciones
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                Detalles del Registro de Vacunas y Certificaciones
                            </span>
                            <div class="float-right">
                                <a href="{{ route('vacunas_certificaciones.index') }}" class="btn btn-primary btn-sm float-right" data-placement="left">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="form-group">
                            <strong>Mascota:</strong>
                            {{ $vacunasCertificacione->mascota->nombre ?? 'N/A' }}
                        </div>
                        <div class="form-group">
                            <strong>Fecha Última Vacuna:</strong>
                            {{ $vacunasCertificacione->fecha_ultima_vacuna->format('d/m/Y') }}
                        </div>
                        <div class="form-group">
                            <strong>Operaciones:</strong>
                            {{ $vacunasCertificacione->operaciones ?? 'N/A' }}
                        </div>
                        <div class="form-group">
                            <strong>Certificado Veterinario:</strong>
                            @if($vacunasCertificacione->certificado_veterinario)
                                <a href="{{ asset('storage/' . $vacunasCertificacione->certificado_veterinario) }}" target="_blank" class="btn btn-info btn-sm">
                                    <i class="fas fa-file-pdf"></i> Ver Certificado
                                </a>
                            @else
                                No disponible
                            @endif
                        </div>
                        <div class="form-group">
                            <strong>Cédula del Propietario:</strong>
                            @if($vacunasCertificacione->cedula_propietario)
                                <a href="{{ asset('storage/' . $vacunasCertificacione->cedula_propietario) }}" target="_blank" class="btn btn-info btn-sm">
                                    <i class="fas fa-file-pdf"></i> Ver Cédula
                                </a>
                            @else
                                No disponible
                            @endif
                        </div>
                        <div class="form-group">
                            <strong>Fecha de Creación:</strong>
                            {{ $vacunasCertificacione->created_at->format('d/m/Y H:i:s') }}
                        </div>
                        <div class="form-group">
                            <strong>Última Actualización:</strong>
                            {{ $vacunasCertificacione->updated_at->format('d/m/Y H:i:s') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
