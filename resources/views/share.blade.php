@extends('layout.home')
@section('content')
<div class="container mt-2">
    <div class="card mt-3">
        <div class="card-body">
            <form method="POST" action="{{ route('files.compartir', ['id' => $id]) }}">
                @csrf
                @method('POST')
                <div class="form-group">
                    <label for="folder_name">Correo Destino: </label>
                    <input type="email" name="email" class="form-control" value="">
                </div>
                <div class="form-group">
                    <label for="rol">Rol:</label>
                    <select name="rolArchivo" class="form-control" id="rol">
                        <option value="reader">Lector</option>
                        <option value="commenter">Comentador</option>
                        <option value="writer">Escritor</option>
                    </select>
                </div>
                <button class="btn btn-success" type="submit">Compartir Archivo</button>
            </form>
        </div>
    </div>

    <h2>Compartidos con:</h2>
    @if(count($sharingInfo) > 0)
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Rol</th>
                        <th>Tipo de Archivo</th>
                        <th>Correo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sharingInfo as $index => $sharing)
                        @if($sharing->getRole() !== 'owner')
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $sharing->getRole() }}</td>
                                <td>{{ $sharing->getType() }}</td>
                                <td>{{ $sharing->getEmailAddress()}}</td>
                                <td>
                                    <a href="{{ route('files.sharedelete', ['id' => $id, 'permissionId' => $sharing->getId()]) }}"
                                    class="btn btn-danger">Eliminar Acceso</a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p>Esta archivo no ha sido compartido.</p>
    @endif
</div>
@endsection
