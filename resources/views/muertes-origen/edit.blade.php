@extends('adminlte::page') 

@section('template_title')
    {{ __('Update') }} Muertes Origen
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Update') }} Muertes Origen</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('muertes-origens.update', $muertesOrigen->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('muertes-origen.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
