<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestión de productos')</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('productos.index') }}">
                <i class="bi bi-box-seam me-2"></i>Gestión de productos
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal"
                aria-controls="menuPrincipal" aria-expanded="false" aria-label="Abrir menú">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="menuPrincipal">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('productos.index') }}">
                            Productos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('productos.create') }}">
                            <i class="bi bi-plus-circle me-1"></i>Nuevo producto
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        @yield('content')
    </main>

    <footer class="border-top py-3 mt-4">
        <div class="container text-center text-muted">
            <small>Práctica de peticiones HTTP · Laravel 12 · Bootstrap 5</small>
        </div>
    </footer>
</body>

</html>
