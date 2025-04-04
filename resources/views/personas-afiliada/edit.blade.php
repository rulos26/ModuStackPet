@extends('adminlte::page')

@section('template_title')
    {{ __('Update') }} Personas Afiliada
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Update') }} Personas Afiliada</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('personas-afiliadas.update', $personasAfiliada->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('personas-afiliada.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
