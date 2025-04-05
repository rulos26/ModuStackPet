@extends('layouts.app')

@section('template_title')
    {{ __('Update') }} Estados Ciclo
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Update') }} Estados Ciclo</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('estados-ciclos.update', $estadosCiclo->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('estados-ciclo.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
