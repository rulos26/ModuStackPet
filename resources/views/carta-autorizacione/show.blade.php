@extends('adminlte::page')

@section('template_title')
{{ $cartaAutorizacione->name ?? __('Show') . " " . __('Carta Autorizacione') }}
@endsection

@section('content')
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <div class="float-left">
                        <span class="card-title">Carta Autorizacion</span>
                    </div>

                </div>

                <div class="card-body bg-white">
                    <div class="row ">
                        <div class="col-3">
                            <label for="fecha" class="form-label"><strong>Fecha:</strong></label>
                        </div>
                        <div class="col-3">
                            <p id="fecha" class="form-control-plaintext">20/12/2023</p>
                        </div>
                        <div class="col-3">
                            <label for="ciudad" class="form-label"><strong>Ciudad:</strong></label>
                        </div>
                        <div class="col-3">
                            <p id="ciudad" class="form-control-plaintext">Bogotá</p>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-12">
                            <p class="text-justify">
                                Yo, <strong>{{$aspirantes->nombres}}</strong> identificado(a) con cédula de ciudadanía
                                No. <strong>{{$aspirantes->cedula}}</strong> expedida en <strong>{{$aspirantes->lugar_expedicion->nombre}}</strong>,
                                constar de manera libre y voluntaria que la información procesada en el presente
                                estudio, obedece
                                a una AUTORIZO plenariamente a la empresa <strong>GRUPO DE TAREAS EMPRESARIALES</strong>
                                con NIT
                                <strong>830.142.258-3</strong> para realizar VERIFICACIÓN ACADÉMICA, VERIFICACIÓN
                                JUDICIAL, CENTRAL
                                DE RIESGOS LEY 1266. Para Tomar las pruebas necesarias y suficientes, a fin de
                                establecer la
                                veracidad de la información suministrada, para lo cual se anexan copias de los
                                certificados de estudio
                                como prueba. Cumpliendo con el DECRETO 1266 DE 2008.
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <label for="direccion" class="form-label"><strong>Dirección Residencia:</strong></label>
                        </div>
                        <div class="col-3">
                            <p id="direccion" class="form-control-plaintext">{{$aspirantes->direccion}}</p>
                        </div>
                        <div class="col-3">
                            <label for="barrio" class="form-label"><strong>Barrio:</strong></label>
                        </div>
                        <div class="col-3">
                            <p id="barrio" class="form-control-plaintext">{{$aspirantes->barrio}}</p>
                        </div>
                        <div class="col-3">
                            <label for="localidad" class="form-label"><strong>Localidad:</strong></label>
                        </div>
                        <div class="col-3">
                            <p id="localidad" class="form-control-plaintext">{{$aspirantes->id}}</p>
                        </div>
                        <div class="col-3">
                            <label for="telefono" class="form-label"><strong>Teléfono Fijo:</strong></label>
                        </div>
                        <div class="col-3">
                            <p id="telefono" class="form-control-plaintext">{{$aspirantes->telefono_fijo}}728.9837</p>
                        </div>
                        <div class="col-3">
                            <label for="celular" class="form-label"><strong>Celular:</strong></label>
                        </div>
                        <div class="col-3">
                            <p id="celular" class="form-control-plaintext">{{$aspirantes->móvil}}301 733.0180</p>
                        </div>

                        <div class="col-3">
                            <label for="email" class="form-label"><strong>Correo electrónico:</strong></label>
                        </div>
                        <div class="col-3">
                            <p id="email" class="form-control-plaintext">{{$aspirantes->id}} guillem@hotmail.com</p>
                        </div>

                        <div class="col-3">
                            <label for="cedula" class="form-label"><strong>Cédula No.:</strong></label>
                        </div>
                        <div class="col-3">
                            <p id="cedula" class="form-control-plaintext">{{$aspirantes->cedula}}</p>
                        </div>
                        <div class="col-3">
                            <label for="expedida" class="form-label"><strong>Expedida:</strong></label>
                        </div>
                        <div class="col-3">
                            <p id="expedida" class="form-control-plaintext">{{$aspirantes->lugar_expedicion->nombre}}</p>
                        </div>
                    </div>
                    <div class="text-center">
                        <a class="btn btn-sm btn-danger" href="{{ route('firmas.create') }}">
                            <i class="fas fa-signature"></i> {{ __('Firmar') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

