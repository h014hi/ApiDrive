@extends('layout.home')
@section('content')
<div class="container mt-2">
    <div class="card mt-3">
        <div class="card-body">
            <form method="POST" action="{{ route('files.editar', ['id' => $id]) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="folder_name">Nombre del Archivo</label>
                    <input type="text" name="nombre_edit" class="form-control" value="">
                </div>
                <button class="btn btn-success" type="submit">Actualizar Archivo</button>
            </form>
        </div>
    </div>
</div>
@endsection
