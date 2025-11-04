@extends('layouts.app')

@section('template_title')
    Resultados de Prueba OAuth
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                <i class="fas fa-clipboard-check"></i> Reporte de Prueba OAuth: {{ $provider->name }}
                            </span>
                            <div class="float-right">
                                <a href="{{ route('superadmin.oauth-providers.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                            </div>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                        </div>
                    @endif

                    <div class="card-body">
                        {{-- Resumen de la prueba --}}
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card text-center {{ $completed ? 'bg-success text-white' : ($hasError ? 'bg-danger text-white' : 'bg-warning') }}">
                                    <div class="card-body">
                                        <h5><i class="fas fa-{{ $completed ? 'check-circle' : ($hasError ? 'times-circle' : 'clock') }}"></i></h5>
                                        <h4>{{ $completed ? 'COMPLETADO' : ($hasError ? 'ERROR' : 'EN PROGRESO') }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h5><i class="fas fa-list-ol"></i></h5>
                                        <h4>{{ $completedSteps }}/{{ $totalSteps }}</h4>
                                        <p class="mb-0">Pasos Completados</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h5><i class="fas fa-user"></i></h5>
                                        <h4>{{ $user ? $user->name : 'N/A' }}</h4>
                                        <p class="mb-0">Usuario</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Información de la prueba --}}
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <strong><i class="fas fa-info-circle"></i> Información de la Prueba</strong>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Provider:</strong> {{ $provider->name }}</p>
                                        <p><strong>ID de Sesión:</strong> <code>{{ $sessionId }}</code></p>
                                        <p><strong>Fecha/Hora:</strong> {{ $logs->first()->created_at->format('d/m/Y H:i:s') }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        @if($user)
                                            <p><strong>Usuario ID:</strong> {{ $user->id }}</p>
                                            <p><strong>Email:</strong> {{ $user->email }}</p>
                                            <p><strong>Roles:</strong> 
                                                @foreach($user->roles as $role)
                                                    <span class="badge bg-primary">{{ $role->name }}</span>
                                                @endforeach
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Pasos del flujo --}}
                        <div class="card">
                            <div class="card-header bg-secondary text-white">
                                <strong><i class="fas fa-list"></i> Pasos del Flujo OAuth</strong>
                            </div>
                            <div class="card-body">
                                <div class="timeline">
                                    @foreach($logs as $index => $log)
                                        @php
                                            $stepNames = [
                                                'redirect_generated' => '1️⃣ URL de Autorización Generada',
                                                'user_clicked' => '2️⃣ Usuario Hizo Clic en "Continuar con ' . $provider->name . '"',
                                                'oauth_authorized' => '3️⃣ Usuario Autorizó en ' . $provider->name,
                                                'callback_received' => '4️⃣ Callback Recibido del Provider',
                                                'user_data_retrieved' => '5️⃣ Datos del Usuario Obtenidos',
                                                'user_created' => '6️⃣ Usuario Creado/Actualizado en BD',
                                                'user_logged_in' => '7️⃣ Sesión de Usuario Creada',
                                                'redirected_to_dashboard' => '8️⃣ Redirección al Dashboard',
                                                'session_verified' => '9️⃣ Sesión Verificada',
                                                'completed' => '✅ Flujo Completo Finalizado',
                                                'error' => '❌ Error en el Flujo',
                                            ];
                                            $stepName = $stepNames[$log->step] ?? $log->step;
                                            $isSuccess = !in_array($log->step, ['error']);
                                            $isError = $log->step === 'error';
                                        @endphp
                                        <div class="timeline-item mb-4 {{ $isError ? 'border-danger' : ($isSuccess ? 'border-success' : 'border-warning') }}">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    @if($isError)
                                                        <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            <i class="fas fa-times"></i>
                                                        </div>
                                                    @elseif($log->step === 'completed')
                                                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            <i class="fas fa-check"></i>
                                                        </div>
                                                    @else
                                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            {{ $index + 1 }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h5 class="mb-1">
                                                        {{ $stepName }}
                                                        @if($isError)
                                                            <span class="badge bg-danger">Error</span>
                                                        @elseif($log->step === 'completed')
                                                            <span class="badge bg-success">Completado</span>
                                                        @else
                                                            <span class="badge bg-success">OK</span>
                                                        @endif
                                                    </h5>
                                                    <p class="text-muted mb-1">
                                                        <small><i class="fas fa-clock"></i> {{ $log->created_at->format('H:i:s') }}</small>
                                                    </p>
                                                    
                                                    @if($log->step_data)
                                                        @php
                                                            $stepData = is_string($log->step_data) ? json_decode($log->step_data, true) : $log->step_data;
                                                        @endphp
                                                        <div class="mt-2">
                                                            <button class="btn btn-sm btn-outline-info" type="button" data-bs-toggle="collapse" data-bs-target="#stepData{{ $log->id }}">
                                                                <i class="fas fa-eye"></i> Ver Detalles
                                                            </button>
                                                            <div class="collapse mt-2" id="stepData{{ $log->id }}">
                                                                <div class="card card-body bg-light">
                                                                    <pre class="mb-0 small">{{ json_encode($stepData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    
                                                    @if($log->error_message)
                                                        <div class="alert alert-danger mt-2 mb-0">
                                                            <strong>Error:</strong> {{ $log->error_message }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Resumen final --}}
                        @if($completed)
                            <div class="alert alert-success mt-4">
                                <h5><i class="fas fa-check-circle"></i> ¡Prueba Completada Exitosamente!</h5>
                                <p class="mb-0">Todos los pasos del flujo OAuth se ejecutaron correctamente. El sistema está funcionando como se espera.</p>
                            </div>
                        @elseif($hasError)
                            <div class="alert alert-danger mt-4">
                                <h5><i class="fas fa-exclamation-circle"></i> Error en la Prueba</h5>
                                <p class="mb-0">Hubo un error durante el flujo OAuth. Revisa los detalles arriba para identificar el problema.</p>
                            </div>
                        @endif

                        <div class="mt-4 text-center">
                            <a href="{{ route('superadmin.oauth-providers.index') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left"></i> Volver a Proveedores OAuth
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .timeline-item {
            border-left: 3px solid #dee2e6;
            padding-left: 20px;
            position: relative;
        }
        .timeline-item.border-success {
            border-left-color: #28a745;
        }
        .timeline-item.border-danger {
            border-left-color: #dc3545;
        }
        .timeline-item.border-warning {
            border-left-color: #ffc107;
        }
    </style>
@endsection

