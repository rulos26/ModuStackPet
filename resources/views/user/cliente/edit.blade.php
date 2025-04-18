@extends('layouts.app')

@section('template_title')
    Editar Perfil de Cliente
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                <i class="fas fa-user-edit"></i> Editar Perfil
                            </span>
                        </div>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('cliente.perfil.update', auth()->user()) }}" role="form" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            @include('user.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
