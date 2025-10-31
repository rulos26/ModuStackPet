@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container my-5">
        <!-- Tarjeta para el contenido -->
        <div class="card shadow-lg" style="border: 2px solid #000; border-radius: 15px;">
            <div class="card-body">
                <!-- Logo centrado y grande -->
                <div class="text-center">
                    <img src="{{ asset($logo) }}" alt="Logo Fortaleza Animal" class="img-fluid" style="max-width: 200px; height: auto;">
                </div>

                <!-- Título centrado -->
                <h1 class="text-center mt-4">🐾 {{ $titulo }}</h1>

                <!-- Descripción en formato de párrafos (alineada a la izquierda) -->
                <div class="mt-4">
                    @foreach (explode('.', $descripcion) as $oracion)
                        @if (trim($oracion) !== '')
                            <p>{{ trim($oracion) }}.</p>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sección de Acciones Rápidas -->
        @if(isset($modules) && $modules->count() > 0)
        <div class="card shadow-lg mt-4" style="border: 2px solid #000; border-radius: 15px;">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">
                    <i class="fas fa-bolt"></i> Acciones Rápidas
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach($modules as $module)
                        @php
                            $routeMap = [
                                'mascotas' => 'mascotas.index',
                                'certificados' => 'vacunas_certificaciones.index',
                                'reportes' => 'pdf.generar',
                                'empresas' => 'empresas.index',
                                'configuracion' => 'superadmin.configuraciones.index',
                                'migraciones' => 'superadmin.migrations.index',
                                'clean' => 'superadmin.clean.index',
                                'seeders' => 'superadmin.seeders.index',
                                'modulos' => 'superadmin.modules.index',
                            ];
                            $route = $routeMap[$module->slug] ?? '#';
                            $iconMap = [
                                'mascotas' => 'fa-dog',
                                'certificados' => 'fa-syringe',
                                'reportes' => 'fa-file-pdf',
                                'empresas' => 'fa-building',
                                'configuracion' => 'fa-cogs',
                                'migraciones' => 'fa-database',
                                'clean' => 'fa-broom',
                                'seeders' => 'fa-seedling',
                                'modulos' => 'fa-puzzle-piece',
                            ];
                            $icon = $iconMap[$module->slug] ?? 'fa-puzzle-piece';
                        @endphp
                        @if($route !== '#')
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route($route) }}" class="text-decoration-none">
                                <div class="card border-primary h-100 hover-shadow" style="transition: all 0.3s;">
                                    <div class="card-body text-center">
                                        <i class="fas {{ $icon }} fa-3x text-primary mb-3"></i>
                                        <h5 class="card-title">{{ $module->name }}</h5>
                                        <p class="card-text small text-muted">{{ Str::limit($module->description, 50) }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <style>
        .hover-shadow:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
            transform: translateY(-2px);
        }
    </style>
@endsection