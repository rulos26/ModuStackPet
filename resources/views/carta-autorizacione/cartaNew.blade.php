@extends('adminlte::page')

@section('title', 'Carta de Autorización de Datos Personales')

@section('content_header')
    <h1>Carta de Autorización de Datos Personales</h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Formulario de Autorización</h3>
                </div>
                <form action="{{ route('autorizacion.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="cedula">Cédula</label>
                            <input type="text" class="form-control" id="cedula" name="cedula" placeholder="Ingrese su cédula" required>
                        </div>
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese su nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="apellido">Apellido</label>
                            <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Ingrese su apellido" required>
                        </div>
                        <div class="form-group">
                            <label for="foto_perfil">Foto de la Perfil</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="foto_perfil" name="foto_perfil" accept="image/*" required>
                                    <label class="custom-file-label" for="foto_perfil">Seleccione archivo</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="foto_cedula">Foto de la Cédula</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="foto_cedula" name="foto_cedula" accept="image/*" required>
                                    <label class="custom-file-label" for="foto_cedula">Seleccione archivo</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="foto_firma">Foto de la Firma</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="foto_firma" name="foto_firma" accept="image/*" required>
                                    <label class="custom-file-label" for="foto_firma">Seleccione archivo</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="barrio">Barrio</label>
                            <input type="text" class="form-control" id="barrio" name="barrio" value="{{ old('barrio') }}">
                            @error('barrio')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="localidad">Localidad</label>
                            <input type="text" class="form-control" id="localidad" name="localidad" value="{{ old('localidad') }}">
                            @error('localidad')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="telefono_fijo">Teléfono Fijo</label>
                            <input type="text" class="form-control" id="telefono_fijo" name="telefono_fijo" value="{{ old('telefono_fijo') }}">
                            @error('telefono_fijo')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="celular">Celular</label>
                            <input type="text" class="form-control" id="celular" name="celular" value="{{ old('celular') }}">
                            @error('celular')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="correo_electronico">Correo Electrónico</label>
                            <input type="email" class="form-control" id="correo_electronico" name="correo_electronico" value="{{ old('correo_electronico') }}">
                            @error('correo_electronico')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="autorizacion">Autorización</label>
                            <textarea class="form-control" id="autorizacion" name="autorizacion" rows="5" readonly>
                                Hago constar de manera libre y voluntaria que la información procesada en el presente estudio obedece a la verdad y AUTORIZO plenamente a la empresa GRUPO DE TAREAS EMPRESARIALES con NIT. 830.142.258-3 contratada por FONDO DE PENSIONES PROTECCION S.A   para realizar  VISITA ONLINE, verificación y actualización según  ley 1581   del 2012 habeas data. con el fin de determinar derecho a las prestaciones pensionales correspondientes.
                            </textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
