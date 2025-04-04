<div class="modal fade" id="miVentanaModal" tabindex="-1" role="dialog" aria-labelledby="miVentanaModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="miVentanaModalLabel">Seguridad De IncopreseR </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Contenido de la ventana modal, como formularios, texto, etc. -->
                <p>Estimado Administrador, está intentando acceder a las opciones avanzadas del sistema. Por favor,
                    introduzca el PIN de seguridad</p>
                <form action="{{ route('tipodocumento.modal') }} " method="POST">
                    @csrf
                    <!-- Otros campos del formulario -->

                    <label for="password">Pin:</label>
                    <input type="password" name="password" required>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
