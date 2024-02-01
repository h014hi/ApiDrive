@props(['id'])
<div class="mt-3">
    <div class="row d-flex " >
        <div class="form-container  mb-3 ">
            <h3 class="mb-4">Crear Carpeta</h3>
            <form method="post" action="{{ route('files.crear',['id'=>$id])}}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="folder_name">Nombre:</label>
                    <input type="text" name="folder_name" class="form-control" id="folder_name">
                </div>
                <button class="btn btn-success" type="submit">Crear</button>
            </form>
        </div>

        <!-- Formulario para Subir Archivo en la carpeta raíz -->
        <div class="form-container  mb-3">
            <h3 class="mb-4">Subir Archivo(s)</h3>
            <form method="post" action="{{ route('files.subir',['id'=>$id])}}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <input type="file" name="file" class="form-control" id="file">
                    @error('file')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <button class="btn btn-success" type="submit">Subir</button>
            </form>
        </div>

        <!-- Formulario para Crear Archivo de Excel, Word o Presentación -->
        <div class="form-container  mb-3">
            <h4 class="mb-4">Crear Archivo (s)</h4>
            <form method="post" action="{{ route('files.crearArchivo',['id'=>$id]) }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="nombre_archivo">Nombre: </label>
                    <input type="text" name="nombre_archivo" class="form-control" id="nombre_archivo">
                    @error('nombre_archivo')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="tipo_archivo">Tipo:</label>
                    <select name="tipo_archivo" class="form-control" id="tipo_archivo">
                        <option value="xlsx">Excel</option>
                        <option value="docx">Word</option>
                        <option value="pptx">Presentación</option>
                    </select>
                    @error('tipo_archivo')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <button class="btn btn-success" type="submit">Crear</button>
            </form>
        </div>
    </div>
</div>

<style>
    .form-container{
       border: 1px solid rgba(77, 77, 77, 0.692);
       padding: 1rem;
       margin-left: 2rem;
       width: 20rem;
    }
</style>
