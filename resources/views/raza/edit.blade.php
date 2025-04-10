@extends('layouts.app')

@section('template_title')
    {{ __('Editar Raza') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Editar Raza') }}</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('razas.update', $raza->id) }}" role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf
                            @include('raza.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
