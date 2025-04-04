@extends('adminlte::page') 

@section('template_title')
    {{ __('Update') }} Hechos Ocurrencia
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Update') }} Hechos Ocurrencia</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('hechos-ocurrencias.update', $hechosOcurrencia->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('hechos-ocurrencia.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
