@extends('adminlte::page') 

@section('template_title')
    {{ __('Create') }} Regimenes Salud
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Create') }} Regimenes Salud</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('regimenes-saluds.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('regimenes-salud.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
