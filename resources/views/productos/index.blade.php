@extends('layouts.app')

@section('title', 'Listado de productos')

@section('content')
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h1 class="mb-1">Productos</h1>
                <p class="text-muted mb-0">Administra tu catálogo de productos.</p>
            </div>

            <a href="{{ route('productos.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Nuevo producto
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                Revisa los campos del formulario. Hay datos que necesitan corregirse.
            </div>
        @endif

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('productos.index') }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-6">
                            <label for="buscar" class="form-label">Buscar por nombre</label>
                            <input type="text" name="buscar" id="buscar" class="form-control"
                                placeholder="Ej. Laptop" value="{{ $buscar }}">
                        </div>

                        <div class="col-md-4">
                            <label for="categoria" class="form-label">Categoría</label>
                            <select name="categoria" id="categoria" class="form-select">
                                <option value="">Todas las categorías</option>
                                @foreach ($categorias as $opcion)
                                    <option value="{{ $opcion }}" {{ $categoria == $opcion ? 'selected' : '' }}>
                                        {{ $opcion }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-dark flex-grow-1">
                                Buscar
                            </button>
                            <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary">
                                Limpiar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Listado de productos</span>
                <span class="badge text-bg-primary">
                    {{ $productos->total() }} productos
                </span>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($productos as $producto)
                            <tr>
                                <td>{{ $producto['id'] }}</td>
                                <td>{{ $producto['nombre'] }}</td>
                                <td>
                                    <span class="badge text-bg-light border">
                                        {{ $producto['categoria'] }}
                                    </span>
                                </td>
                                <td>${{ number_format((float) $producto['precio'], 2) }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('productos.edit', $producto['id']) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            Editar
                                        </a>

                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                            data-bs-target="#modalEliminar" data-id="{{ $producto['id'] }}"
                                            data-nombre="{{ $producto['nombre'] }}">
                                            Eliminar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    No se encontraron productos.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <small class="text-muted">
                        Mostrando {{ $productos->count() }} de {{ $productos->total() }} productos
                    </small>

                    <nav aria-label="Paginación de productos">
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item {{ $productos->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $productos->previousPageUrl() ?? '#' }}"
                                    aria-label="Anterior">
                                    Anterior
                                </a>
                            </li>

                            @for ($pagina = 1; $pagina <= $productos->lastPage(); $pagina++)
                                <li class="page-item {{ $productos->currentPage() == $pagina ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $productos->url($pagina) }}">
                                        {{ $pagina }}
                                    </a>
                                </li>
                            @endfor

                            <li class="page-item {{ $productos->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link" href="{{ $productos->nextPageUrl() ?? '#' }}" aria-label="Siguiente">
                                    Siguiente
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="tituloModalEliminar" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="formEliminar" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="modal-header">
                        <h5 class="modal-title" id="tituloModalEliminar">Eliminar producto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>

                    <div class="modal-body">
                        ¿Seguro que deseas eliminar <strong id="nombreEliminar"></strong>?
                        Esta acción no se puede deshacer.
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-danger">
                            Sí, eliminar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modalEliminar = document.getElementById('modalEliminar');

            modalEliminar.addEventListener('show.bs.modal', function(event) {
                const boton = event.relatedTarget;
                const id = boton.getAttribute('data-id');
                const nombre = boton.getAttribute('data-nombre');

                document.getElementById('nombreEliminar').textContent = nombre;
                document.getElementById('formEliminar').action = `/productos/${id}`;
            });
        });
    </script>
@endsection
