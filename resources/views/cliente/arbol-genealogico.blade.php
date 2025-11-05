@extends('layouts.app')

@section('template_title')
    {{ __('Árbol Genealógico - Mi Familia de Mascotas') }}
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-lg border-0" style="background: linear-gradient(135deg, #1E40AF 0%, #3B82F6 50%, #60A5FA 100%); border-radius: 20px; position: relative; overflow: hidden;">
                <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.08); border-radius: 50%;"></div>
                
                <div class="card-body p-4" style="position: relative; z-index: 1;">
                    <div class="row align-items-center">
                        <div class="col">
                            <h2 class="text-white mb-1" style="font-weight: 700; text-shadow: 2px 2px 4px rgba(0,0,0,0.3); font-size: 2rem;">
                                <i class="fas fa-project-diagram"></i> {{ __('Árbol Genealógico') }}
                            </h2>
                            <p class="text-white mb-0" style="opacity: 0.95; font-size: 1.1rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.2);">
                                {{ __('Visualiza tu familia de mascotas de forma interactiva') }}
                            </p>
                        </div>
                        <div class="col-auto">
                            <span class="badge px-4 py-2" style="font-size: 1rem; background: rgba(255,255,255,0.3); color: white; border: 1px solid rgba(255,255,255,0.4); backdrop-filter: blur(10px);">
                                <i class="fas fa-paw"></i> {{ count($arbolData['mascotas']) }} {{ __('Mascota(s)') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenedor del Árbol -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0" style="border-radius: 20px; overflow: hidden;">
                <div class="card-body p-0" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); min-height: 600px;">
                    <div id="arbol-container" style="width: 100%; height: 700px; position: relative; overflow: hidden;">
                        <svg id="arbol-svg" style="width: 100%; height: 100%;"></svg>
                        <!-- Overlay de carga -->
                        <div id="loading-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(248, 249, 250, 0.9); display: flex; align-items: center; justify-content: center; z-index: 10;">
                            <div class="text-center">
                                <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>
                                <p class="mt-3 text-primary" style="font-size: 1.2rem; font-weight: 600;">{{ __('Cargando tu árbol genealógico...') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensaje si no hay mascotas -->
    @if(count($arbolData['mascotas']) == 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-info shadow-sm" style="border-radius: 15px; border: none;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle fa-2x me-3 text-primary"></i>
                    <div>
                        <h5 class="alert-heading mb-1">{{ __('No tienes mascotas registradas') }}</h5>
                        <p class="mb-0">{{ __('Agrega tus mascotas para visualizar tu árbol genealógico.') }}</p>
                        <a href="{{ route('mascotas.create') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-plus"></i> {{ __('Agregar Primera Mascota') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Botón de acción -->
    <div class="row mt-4">
        <div class="col-12 text-center">
            <a href="{{ route('cliente.dashboard') }}" class="btn btn-lg px-5 shadow-sm" style="background: linear-gradient(135deg, #1E40AF 0%, #3B82F6 100%); color: white; font-weight: 600; border: none;">
                <i class="fas fa-arrow-left"></i> {{ __('Volver al Dashboard') }}
            </a>
        </div>
    </div>
</div>

<!-- D3.js desde CDN -->
<script src="https://d3js.org/d3.v7.min.js"></script>

<style>
    .node-card {
        cursor: pointer;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .node-card:hover {
        transform: scale(1.05);
        filter: brightness(1.1);
    }
    
    .node-card.cliente {
        filter: drop-shadow(0 8px 16px rgba(30, 64, 175, 0.4));
    }
    
    .node-card.mascota {
        filter: drop-shadow(0 6px 12px rgba(0, 0, 0, 0.2));
    }
    
    .connection-line {
        stroke: #3B82F6;
        stroke-width: 3;
        opacity: 0.6;
        transition: opacity 0.3s ease;
    }
    
    .connection-line:hover {
        opacity: 1;
        stroke-width: 4;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.8);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    .node-card {
        animation: fadeIn 0.5s ease-out;
    }
</style>

<script>
    // Datos del árbol
    const arbolData = @json($arbolData);
    
    // Ocultar overlay de carga después de un momento
    setTimeout(() => {
        document.getElementById('loading-overlay').style.display = 'none';
    }, 500);
    
    // Configuración del SVG
    const svg = d3.select('#arbol-svg');
    const width = document.getElementById('arbol-container').clientWidth;
    const height = 700;
    
    svg.attr('width', width).attr('height', height);
    
    const g = svg.append('g').attr('transform', `translate(${width / 2}, ${height / 2})`);
    
    // Si no hay mascotas, mostrar mensaje
    if (arbolData.mascotas.length === 0) {
        g.append('text')
            .attr('text-anchor', 'middle')
            .attr('dy', '0.35em')
            .attr('fill', '#6B7280')
            .style('font-size', '24px')
            .style('font-weight', '600')
            .text('Agrega mascotas para visualizar tu árbol genealógico');
        return;
    }
    
    // Crear el layout radial
    const angleStep = (2 * Math.PI) / (arbolData.mascotas.length || 1);
    const radius = Math.min(width, height) * 0.3;
    
    // Dibujar el nodo del cliente (centro)
    const clienteGroup = g.append('g')
        .attr('class', 'node-card cliente')
        .attr('transform', `translate(0, 0)`);
    
    // Círculo de fondo del cliente
    clienteGroup.append('circle')
        .attr('r', 80)
        .attr('fill', arbolData.cliente.color)
        .attr('stroke', 'white')
        .attr('stroke-width', 4);
    
    // Avatar del cliente
    clienteGroup.append('image')
        .attr('xlink:href', arbolData.cliente.avatar)
        .attr('x', -50)
        .attr('y', -50)
        .attr('width', 100)
        .attr('height', 100)
        .attr('clip-path', 'url(#clienteClip)');
    
    // Clip path para el avatar del cliente
    svg.append('defs').append('clipPath')
        .attr('id', 'clienteClip')
        .append('circle')
        .attr('r', 50)
        .attr('cx', 0)
        .attr('cy', 0);
    
    // Nombre del cliente
    clienteGroup.append('text')
        .attr('text-anchor', 'middle')
        .attr('y', 100)
        .attr('fill', 'white')
        .style('font-size', '16px')
        .style('font-weight', '700')
        .style('text-shadow', '2px 2px 4px rgba(0,0,0,0.5)')
        .text(arbolData.cliente.nombre);
    
    // Etiqueta "Dueño"
    clienteGroup.append('text')
        .attr('text-anchor', 'middle')
        .attr('y', 120)
        .attr('fill', 'white')
        .style('font-size', '12px')
        .style('opacity', '0.9')
        .style('text-shadow', '1px 1px 2px rgba(0,0,0,0.5)')
        .text('Dueño');
    
    // Dibujar las líneas de conexión y los nodos de mascotas
    arbolData.mascotas.forEach((mascota, index) => {
        const angle = (index * angleStep) - (Math.PI / 2);
        const x = Math.cos(angle) * radius;
        const y = Math.sin(angle) * radius;
        
        // Línea de conexión
        g.append('line')
            .attr('class', 'connection-line')
            .attr('x1', 0)
            .attr('y1', 0)
            .attr('x2', x)
            .attr('y2', y)
            .attr('marker-end', 'url(#arrowhead)');
        
        // Grupo de la mascota
        const mascotaGroup = g.append('g')
            .attr('class', 'node-card mascota')
            .attr('transform', `translate(${x}, ${y})`)
            .style('cursor', 'pointer')
            .on('click', function() {
                window.location.href = `/mascotas/${mascota.id}`;
            });
        
        // Círculo de fondo de la mascota
        mascotaGroup.append('circle')
            .attr('r', 60)
            .attr('fill', mascota.color)
            .attr('stroke', 'white')
            .attr('stroke-width', 3);
        
        // Avatar de la mascota
        mascotaGroup.append('image')
            .attr('xlink:href', mascota.avatar)
            .attr('x', -35)
            .attr('y', -35)
            .attr('width', 70)
            .attr('height', 70)
            .attr('clip-path', `url(#mascotaClip${index})`);
        
        // Clip path para el avatar de la mascota
        svg.append('defs').append('clipPath')
            .attr('id', `mascotaClip${index}`)
            .append('circle')
            .attr('r', 35)
            .attr('cx', 0)
            .attr('cy', 0);
        
        // Nombre de la mascota
        mascotaGroup.append('text')
            .attr('text-anchor', 'middle')
            .attr('y', 80)
            .attr('fill', '#1F2937')
            .style('font-size', '14px')
            .style('font-weight', '700')
            .text(mascota.nombre);
        
        // Raza de la mascota
        mascotaGroup.append('text')
            .attr('text-anchor', 'middle')
            .attr('y', 95)
            .attr('fill', '#6B7280')
            .style('font-size', '11px')
            .text(mascota.raza);
        
        // Badge con tipo de mascota
        mascotaGroup.append('circle')
            .attr('r', 12)
            .attr('cx', 45)
            .attr('cy', -45)
            .attr('fill', mascota.color)
            .attr('stroke', 'white')
            .attr('stroke-width', 2);
        
        mascotaGroup.append('text')
            .attr('text-anchor', 'middle')
            .attr('x', 45)
            .attr('y', -42)
            .attr('fill', 'white')
            .style('font-size', '10px')
            .style('font-weight', 'bold')
            .text(mascota.tipo_mascota.charAt(0));
    });
    
    // Flecha para las conexiones
    svg.append('defs').append('marker')
        .attr('id', 'arrowhead')
        .attr('viewBox', '0 -5 10 10')
        .attr('refX', 0)
        .attr('refY', 0)
        .attr('markerWidth', 8)
        .attr('markerHeight', 8)
        .attr('orient', 'auto')
        .append('path')
        .attr('d', 'M0,-5L10,0L0,5')
        .attr('fill', '#3B82F6');
    
    // Animación de entrada
    g.selectAll('.node-card')
        .transition()
        .duration(800)
        .delay((d, i) => i * 100)
        .style('opacity', 1);
    
    // Zoom y pan
    const zoom = d3.zoom()
        .scaleExtent([0.5, 2])
        .on('zoom', (event) => {
            g.attr('transform', event.transform);
        });
    
    svg.call(zoom);
    
    // Ajustar el zoom inicial
    const initialScale = Math.min(width / 800, height / 700, 1);
    svg.call(zoom.transform, d3.zoomIdentity.translate(width / 2, height / 2).scale(initialScale));
</script>
@endsection

