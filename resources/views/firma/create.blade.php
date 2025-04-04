@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
<h1 class="m-0 text-dark"></h1>
@stop

@section('content')
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-default">
                <div class="card-header">
                    <span class="card-title">Firmassssssss</span>
                </div>
                <div class="card-body bg-white">
                    <form method="POST" action="{{ route('firmas.store') }}" role="form" enctype="multipart/form-data">
                        @csrf

                        @include('firma.form')

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@section('js')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var canvas = document.getElementById('signature-pad');
        if (canvas) {
            var signaturePad = new SignaturePad(canvas);

            document.getElementById('clear').addEventListener('click', function() {
                signaturePad.clear();
            });

            document.getElementById('save').addEventListener('click', function() {
                if (signaturePad.isEmpty()) {
                    alert("Por favor, dibuja tu firma primero.");
                } else {
                    var dataUrl = signaturePad.toDataURL('image/png');
                    // Enviar dataUrl al servidor
                    fetch('/save-signature', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            signature: dataUrl
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            var imgElement = document.createElement('img');
                            imgElement.src = data.url;
                            imgElement.alt = "Firma guardada";
                            imgElement.style.border = "1px solid #000";
                            document.getElementById('signature-image').innerHTML = '';
                            document.getElementById('signature-image').appendChild(imgElement);
                            // Redireccionar a la URL especificada
                            window.location.href = data.redirect;
                        } else {
                            alert("Error al guardar la firma.");
                        }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
                }
            });
        }
    });
</script>
@endsection

@stop
