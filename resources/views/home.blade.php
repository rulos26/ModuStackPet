@extends('adminlte::page') 
 
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p>Hola <strong>Yohanna</strong>,</p>
                    <p>Si estás leyendo esto, es porque ya deseas ver los <span class="fw-bold text-primary">avances del proyecto</span>. En este momento, se ha completado el <span class="fw-bold text-primary">menú de afiliados</span>. Ten en cuenta que el sitio está en <span class="fw-bold text-danger">construcción</span>, por lo que algunos enlaces pueden no funcionar correctamente, el menú está <span class="fw-bold text-danger">incompleto</span> y algunas funcionalidades aún no están disponibles. Sin embargo, a medida que continuemos trabajando en el proyecto, estas áreas se irán <span class="fw-bold text-success">mejorando</span> y <span class="fw-bold text-success">actualizando</span>.</p>
                  
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@include('sidebar.right-sidebar')
