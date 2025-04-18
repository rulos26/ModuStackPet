@extends('layouts.app')

@section('template_title')
    Editar Usuario
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                {{ __('Editar Usuario') }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.users.update', $user->id) }}" role="form" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            @include('user.form')

                            <div class="row mt-4">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">{{ __('Actualizar Usuario') }}</button>
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
