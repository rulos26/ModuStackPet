@extends('layouts.app')

@section('template_title')
    {{ __('Crear Mascota') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">{{ __('Crear Mascota') }}</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('mascotas.store') }}" role="form" enctype="multipart/form-data">
                            @csrf
                            @include('mascota.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
