@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Escanear QR</h2>
    <video id="preview" style="width: 100%; max-width: 500px;"></video>

    <form id="qr-form" action="{{ route('qr.validate') }}" method="POST">
        @csrf
        <input type="hidden" name="cedula" id="cedula">
    </form>
</div>

<script>
    let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });

    scanner.addListener('scan', function(content) {
        document.getElementById('cedula').value = content;
        document.getElementById('qr-form').submit(); // Enviar formulario automáticamente
    });

    Instascan.Camera.getCameras().then(function(cameras) {
        if (cameras.length > 0) {
            scanner.start(cameras[0]); // Usa la primera cámara disponible
        } else {
            alert("No se encontró ninguna cámara.");
        }
    }).catch(function(e) {
        console.error(e);
    });
</script>
@endsection
