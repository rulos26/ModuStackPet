@extends('adminlte::page')

@section('template_title')
    {{ __('Update') }} Datos Basicos Hijo
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Update') }} Datos Basicos Hijo</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('datos-basicos-hijos.update', $datosBasicosHijo->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('datos-basicos-hijo.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
