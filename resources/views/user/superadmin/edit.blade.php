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
                                <i class="fas fa-user-edit"></i> Editar Usuario
                            </span>
                            <div class="float-right">
                                <a href="{{ route('superadmin.users.show') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('superadmin.users.update', $user->id) }}" role="form" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            @include('user.superadmin.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#roles').select2({
                theme: 'bootstrap-5',
                placeholder: 'Seleccione los roles',
                allowClear: true
            });
        });
    </script>
@endpush
