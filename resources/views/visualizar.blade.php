@extends('layout.home')

@section('content')

<div class="container">

    <x-forms :id="$id"/>

    <h2>Archivos de la Carpeta</h2>
    @if(count($archivos) > 0)
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Nombre</th>
                        <th>Tipo de Archivo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($archivos as $index => $archivo)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $archivo->getName() }}</td>
                            <td>{{ $archivo->getMimeType() }}</td>
                            <td>
                               <x-buttons :archivo="$archivo"/>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p>No hay archivos en esta carpeta.</p>
    @endif
</div>

@endsection
