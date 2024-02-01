<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="editarNombreModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarNombreModalLabel">Editar Nombre</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Formulario dentro del modal -->
                <form action="{{ route('files.editar', ['id' => $id])}}">
                    <div class="form-group">
                        <label >Nuevo Nombre: {{$id}}</label>
                        <input type="text" class="form-control" id="nombreEdit">
                    </div>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>
