@extends('adminlte::page') 

@section('template_title')
    {{ __('Update') }} Verificacione
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Update') }} Verificacione</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('verificaciones.update', $verificacione->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('verificacione.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
