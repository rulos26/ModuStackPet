@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
<h1 class="m-0 text-dark"></h1>
@stop
@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Update') }} Firma</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('firmas.update', $firma->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('firma.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
