@extends('adminlte::page')

@section('template_title')
    Link
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Link</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('links.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('link.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@include('sidebar.right-sidebar')