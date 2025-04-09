@extends('layouts.app')

@section('template_title')
    {{ $user->name ?? __('Perfil de Usuario') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <!-- Estilo 1: Profile Widget -->
            <div class="col-md-4">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Perfil de Usuario (Widget)') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            @if($user->avatar && file_exists(public_path($user->avatar)))
                                <img class="profile-user-img img-fluid img-circle"
                                     src="{{ asset('public/' . $user->avatar) }}"
                                     alt="Foto de Perfil">
                            @else
                                <img class="profile-user-img img-fluid img-circle"
                                     src="{{ asset('default-avatar.png') }}" 
                                     alt="Foto de Perfil">
                            @endif
                        </div>
                        <h3 class="profile-username text-center">{{ $user->name }}</h3>
                        <p class="text-muted text-center">{{ __('Usuario Activo') }}</p>
                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>{{ __('Correo Electrónico') }}</b> <a class="float-right">{{ $user->email }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>{{ __('Teléfono') }}</b> <a class="float-right">{{ $user->telefono }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>{{ __('WhatsApp') }}</b> <a class="float-right">{{ $user->whatsapp }}</a>
                            </li>
                        </ul>
                        <a href="{{ route('users.index') }}" class="btn btn-primary btn-block"><b>{{ __('Volver') }}</b></a>
                    </div>
                </div>
            </div>

            <!-- Estilo 2: User Card -->
            <div class="col-md-4">
                <div class="card card-widget widget-user">
                    <div class="widget-user-header bg-primary">
                        <h3 class="widget-user-username">{{ $user->name }}</h3>
                        <h5 class="widget-user-desc">{{ __('Usuario Activo') }}</h5>
                    </div>
                    <div class="widget-user-image">
                        @if($user->avatar && file_exists(public_path($user->avatar)))
                            <img class="img-circle elevation-2" src="{{ asset('public/' . $user->avatar) }}" alt="Foto de Perfil">
                        @else
                            <img class="img-circle elevation-2" src="{{ asset('default-avatar.png') }}" alt="Foto de Perfil">
                        @endif
                    </div>
                    <div class="card-footer">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <span class="nav-link">
                                    {{ __('Correo Electrónico') }} <span class="float-right">{{ $user->email }}</span>
                                </span>
                            </li>
                            <li class="nav-item">
                                <span class="nav-link">
                                    {{ __('Teléfono') }} <span class="float-right">{{ $user->telefono }}</span>
                                </span>
                            </li>
                            <li class="nav-item">
                                <span class="nav-link">
                                    {{ __('WhatsApp') }} <span class="float-right">{{ $user->whatsapp }}</span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Estilo 3: Profile Overlay -->
            <div class="col-md-4">
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            @if($user->avatar && file_exists(public_path($user->avatar)))
                                <img class="profile-user-img img-fluid img-circle"
                                     src="{{ asset('public/' . $user->avatar) }}"
                                     alt="Foto de Perfil">
                            @else
                                <img class="profile-user-img img-fluid img-circle"
                                     src="{{ asset('default-avatar.png') }}" 
                                     alt="Foto de Perfil">
                            @endif
                        </div>
                        <h3 class="profile-username text-center">{{ $user->name }}</h3>
                        <p class="text-muted text-center">{{ __('Usuario Activo') }}</p>
                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>{{ __('Correo Electrónico') }}</b> <a class="float-right">{{ $user->email }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>{{ __('Teléfono') }}</b> <a class="float-right">{{ $user->telefono }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>{{ __('WhatsApp') }}</b> <a class="float-right">{{ $user->whatsapp }}</a>
                            </li>
                        </ul>
                        <a href="{{ route('users.index') }}" class="btn btn-primary btn-block"><b>{{ __('Volver') }}</b></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
