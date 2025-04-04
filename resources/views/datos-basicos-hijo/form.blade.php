<div class="row padding-1 p-1">
    <div class="col-md-12">
        <input type="hidden" name="cedula_numero" value="{{ $cedula ?? old('cedula_numero') }}">

    <div class="form-group mb-3">
        <label for="numero_hijos" class="form-label">Número de Hijos</label>
        <input type="number" name="numero_hijos" class="form-control" id="numero_hijos" 
               placeholder="Ingrese el número de hijos" min="0">
    </div>

    <!-- Contenedor dinámico para los hijos -->
    <div id="hijos_inputs_container"></div>

    <div class="mt-3">
        <button type="submit" class="btn btn-primary">Enviar</button>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const numeroHijosInput = document.getElementById("numero_hijos");
        const container = document.getElementById("hijos_inputs_container");
    
        numeroHijosInput.addEventListener("input", function () {
            const numeroHijos = parseInt(this.value) || 0;
            container.innerHTML = ""; // Limpiar los inputs previos
    
            for (let i = 0; i < numeroHijos; i++) {
                container.appendChild(crearCamposHijo(i + 1));
            }
        });
    
        function crearCamposHijo(index) {
            const hijoDiv = document.createElement("div");
            hijoDiv.classList.add("row", "mb-3");
    
            hijoDiv.innerHTML = `
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="nombre_hijo_${index}" class="form-label">Nombre Hijo ${index}</label>
                        <input type="text" name="nombre_hijos[]" class="form-control" id="nombre_hijo_${index}" placeholder="Nombre">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="tipo_documento_hijo_${index}" class="form-label">Tipo Documento ${index}</label>
                        <select name="tipo_documento_hijos[]" class="form-control" id="tipo_documento_hijo_${index}">
                            <option value="" disabled selected>Seleccione un Tipo de Documento</option>
                            @foreach ($tipo_docu as $tipo)
                                <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="documento_hijo_${index}" class="form-label">Documento ${index}</label>
                        <input type="text" name="documento_hijos[]" class="form-control" id="documento_hijo_${index}" placeholder="Documento">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="edad_hijo_${index}" class="form-label">Edad ${index}</label>
                        <input type="number" name="edad_hijos[]" class="form-control" id="edad_hijo_${index}" placeholder="Edad" min="0">
                    </div>
                </div>
            `;
            return hijoDiv;
        }
    });
    </script>
    
