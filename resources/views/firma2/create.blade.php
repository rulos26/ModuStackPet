@extends('adminlte::page')

@section('template_title')
    Firma
@endsection

{{-- @section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Firma</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('firmas.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('firma.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection --}}
@section('content')
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <div class="float-left">
                        <span class="card-title">{{ __('Show') }} Firma</span>
                    </div>
                   
                </div>

                <div class="card-body bg-white">
                    <div class="row">
                        <div class="col-12">
                            <canvas id="signature-pad" class="signature-pad" width=600 height=400 style="border: 1px solid black; "></canvas>
   
                        </div>
                        <div class="col-12">
                            <button id="save" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                          
                </div>
            </div>
        </div>
    </div>
</section>
{{-- <div class="container">
    <canvas id="signature-pad" class="signature-pad" width=600 height=400 style="border: 1px solid black; "></canvas>
    <button id="save" class="btn btn-primary">Guardar</button>
</div> --}}

{{-- <script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
<script>
    var canvas = document.getElementById('signature-pad');
    var signaturePad = new SignaturePad(canvas);

    document.getElementById('save').addEventListener('click', function () {
        if (signaturePad.isEmpty()) {
            return alert("Por favor, dibuja tu firma primero.");
        }

        var dataURL = signaturePad.toDataURL();
        var csrfToken = '{{ csrf_token() }}';

        fetch('{{ route('firmas.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                firma: dataURL
            })
        }).then(response => {
            
        }).catch(error => {
            console.error('Error:', error);
        });
    });
</script> --}}

<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
    <script>
        var canvas = document.getElementById('signature-pad');
        var signaturePad = new SignaturePad(canvas);

        
        document.getElementById('save').addEventListener('click', function () {
            
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
    </script>

@endsection
