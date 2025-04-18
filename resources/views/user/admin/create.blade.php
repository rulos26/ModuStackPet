@extends('layouts.app')

@section('template_title')
    Crear Usuario
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                {{ __('Crear Usuario') }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.users.store') }}" role="form" enctype="multipart/form-data">
                            @csrf

                            @include('user.form')

                            <div class="row mt-4">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">{{ __('Crear Usuario') }}</button>
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">{{ __('Cancelar') }}</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
