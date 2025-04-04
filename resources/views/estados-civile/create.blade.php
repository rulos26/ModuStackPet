@extends('adminlte::page')

@section('template_title')
    Estados Civile
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Estados Civile</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('estados-civiles.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('estados-civile.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
