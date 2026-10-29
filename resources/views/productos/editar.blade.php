@extends('layouts.app')

@section('title', 'Editar producto')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Editar producto</h1>

            <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                Volver
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-header">
                Actualizar producto
            </div>

            <div class="card-body">
                <form action="{{ route('productos.update', $producto['id']) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @include('productos._form')

                    <button type="submit" class="btn btn-primary">
                        Guardar cambios
                    </button>

                    <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary">
                        Cancelar
                    </a>
                </form>
            </div>
        </div>
    </div>
@endsection
