@extends('layout.home')

@section('content')

<div class="container mt-2">

    <x-forms :id="null"/>

    <h2 class="mb-4">Archivos en la Carpeta Raíz</h2>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Nombre</th>
                    <th>tipo</th>
                    <th style="width: 25%;">Acciones</th>
                </tr>
            </thead>
            @if(isset($archivos) && count($archivos) > 0)
                <tbody>
                    @foreach($archivos as $index => $archivo)
                        <tr>
                            <td>{{$index + 1 }}</td>
                            <td>{{$archivo->getName()}}</td>
                            <td>{{$archivo->getMimeType()}}</td>
                            <td>
                                <x-buttons :archivo="$archivo"/>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            @endif
        </table>
    </div>
</div>
@endsection

