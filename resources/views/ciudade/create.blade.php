@extends('layouts.app')

@section('template_title')
    {{ __('Crear Nuevo Municipio') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Crear Nuevo Municipio') }}</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('ciudades.index') }}">{{ __('Volver') }}</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('ciudades.store') }}" role="form" enctype="multipart/form-data">
                            @csrf
                            @include('ciudade.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
