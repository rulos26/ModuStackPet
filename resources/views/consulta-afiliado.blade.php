@extends('adminlte::page') 

@section('template_title')
Consulta de Afiliado
@endsection

@section('content')
<div class="container">
    <h2>Consulta de Afiliado</h2>

    <form action="{{ route('consulta.afiliado.buscar') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="cedula">Número de Cédula:</label>
            <input type="text" name="cedula" id="cedula" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Buscar</button>
    </form>

    @if(session('error'))
        <div class="alert alert-danger mt-3">
            {{ session('error') }}
        </div>
    @endif

    @if(isset($resultados))
        <h3 class="mt-4">Resultados para la cédula: {{ $cedula }}</h3>
        <table class="table table-bordered mt-2">
            <thead>
                <tr>
                    <th>Tabla</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($resultados as $resultado)
                    <tr>
                        <td>{{ $resultado['tabla'] }}</td>
                        <td>
                            @if ($resultado['estado'] === 'Lleno')
                                <span class="badge bg-success">Finalizado ✅</span>
                            @else
                                <span class="badge bg-danger">Pendiente ⏳</span>
                            @endif
                        </td>
                        <td>
                            @if ($resultado['estado'] === 'Vacío' && $resultado['ruta'])
                                <a href="{{ route($resultado['ruta']) }}" class="btn btn-danger btn-sm">
                                    Complementar ❌
                                </a>
                            @else
                                <span class="text-success">✔ No requiere acción</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
