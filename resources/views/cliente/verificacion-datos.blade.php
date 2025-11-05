@extends('layouts.app')

@section('title', 'Completa tu Perfil')

@section('content')
<div class="container my-5">
    <!-- Alerta de datos faltantes -->
    @if(!empty($missingData) && count($missingData) > 0)
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <h4 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> ¡Atención!</h4>
        <p>Tu perfil está <strong>{{ $completionPercentage }}% completo</strong>. Para disfrutar de todas las funcionalidades, necesitas completar algunos datos importantes.</p>
        <hr>
        <p class="mb-2"><strong>Datos faltantes ({{ count($missingData) }}):</strong></p>
        <ul class="mb-0">
            @foreach($missingData as $item)
                <li>{{ $item['label'] }} - {{ $item['description'] }}</li>
            @endforeach
        </ul>
        <p class="mt-2 mb-0"><strong>Completa los siguientes datos para continuar:</strong></p>
    </div>
    @else
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <h4 class="alert-heading"><i class="fas fa-check-circle"></i> ¡Excelente!</h4>
        <p class="mb-0">Tu perfil está <strong>100% completo</strong>. Puedes disfrutar de todas las funcionalidades.</p>
    </div>
    @endif

    <!-- Barra de progreso -->
    <div class="card shadow-lg mb-4" style="border: 2px solid #007bff; border-radius: 15px;">
        <div class="card-body">
            <h5 class="card-title mb-3">
                <i class="fas fa-chart-line"></i> Progreso de tu Perfil
            </h5>
            <div class="progress" style="height: 30px;">
                <div class="progress-bar progress-bar-striped progress-bar-animated 
                    @if($completionPercentage >= 80) bg-success
                    @elseif($completionPercentage >= 50) bg-warning
                    @else bg-danger
                    @endif" 
                    role="progressbar" 
                    style="width: {{ $completionPercentage }}%" 
                    aria-valuenow="{{ $completionPercentage }}" 
                    aria-valuemin="0" 
                    aria-valuemax="100">
                    <strong>{{ $completionPercentage }}%</strong>
                </div>
            </div>
            <p class="text-center mt-2 mb-0">
                @if($completionPercentage == 100)
                    <span class="badge badge-success"><i class="fas fa-check-circle"></i> ¡Perfil Completo!</span>
                @else
                    <span class="text-muted">Faltan {{ count($missingData) }} {{ count($missingData) == 1 ? 'dato' : 'datos' }} por completar</span>
                @endif
            </p>
        </div>
    </div>

    <!-- Lista de datos faltantes -->
    @if(!empty($missingData) && count($missingData) > 0)
    <div class="card shadow-lg" style="border: 2px solid #000; border-radius: 15px;">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="fas fa-tasks"></i> Datos por Completar ({{ count($missingData) }})</h4>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($missingData as $index => $item)
                    <div class="col-md-6 mb-3">
                        <div class="card h-100 
                            @if($item['priority'] == 1) border-danger
                            @elseif($item['priority'] <= 2) border-warning
                            @else border-info
                            @endif">
                            <div class="card-body">
                                <div class="d-flex align-items-start">
                                    <div class="mr-3">
                                        <i class="{{ $item['icon'] }} fa-2x 
                                            @if($item['priority'] == 1) text-danger
                                            @elseif($item['priority'] <= 2) text-warning
                                            @else text-info
                                            @endif"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="card-title mb-2">
                                            @if($item['priority'] == 1)
                                                <span class="badge badge-danger">OBLIGATORIO</span>
                                            @elseif($item['priority'] <= 2)
                                                <span class="badge badge-warning">IMPORTANTE</span>
                                            @else
                                                <span class="badge badge-info">RECOMENDADO</span>
                                            @endif
                                            {{ $item['label'] }}
                                        </h6>
                                        <p class="card-text text-muted mb-2">{{ $item['description'] }}</p>
                                        @if(isset($item['route']))
                                            <a href="{{ route($item['route'], $item['route_params'] ?? []) }}" 
                                               class="btn btn-sm 
                                                    @if($item['priority'] == 1) btn-danger
                                                    @elseif($item['priority'] <= 2) btn-warning
                                                    @else btn-info
                                                    @endif">
                                                <i class="fas fa-arrow-right"></i> Completar Ahora
                                            </a>
                                        @endif
                                    </div>
                                    <div>
                                        <i class="fas fa-times-circle text-danger"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @else
    <div class="card shadow-lg border-success" style="border-radius: 15px;">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0"><i class="fas fa-check-circle"></i> ¡Todos los datos están completos!</h4>
        </div>
        <div class="card-body text-center">
            <i class="fas fa-check-circle fa-5x text-success mb-3"></i>
            <h5>Tu perfil está 100% completo</h5>
            <p class="text-muted">Puedes continuar al dashboard para disfrutar de todas las funcionalidades.</p>
        </div>
    </div>
    @endif

    <!-- Botón para continuar al dashboard (solo si no hay datos críticos faltantes) -->
    @php
        $criticalMissing = array_filter($missingData, function($item) {
            return $item['priority'] <= 2;
        });
    @endphp
    
    @if(empty($criticalMissing))
        <div class="text-center mt-4">
            <a href="{{ route('cliente.dashboard') }}" class="btn btn-success btn-lg">
                <i class="fas fa-check"></i> Continuar al Dashboard
            </a>
        </div>
    @endif
</div>

<style>
    .progress {
        border-radius: 10px;
        overflow: hidden;
    }
    
    .card {
        transition: transform 0.2s;
    }
    
    .card:hover {
        transform: translateY(-5px);
    }
</style>
@endsection

