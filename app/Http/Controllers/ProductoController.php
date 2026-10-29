<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductoController extends Controller
{
    private function productosIniciales(): array
    {
        return [
            1 => ['id' => 1, 'nombre' => 'Laptop', 'categoria' => 'Tecnología', 'precio' => 15999.00],
            2 => ['id' => 2, 'nombre' => 'Mouse', 'categoria' => 'Tecnología', 'precio' => 299.00],
            3 => ['id' => 3, 'nombre' => 'Escritorio', 'categoria' => 'Muebles', 'precio' => 2499.00],
            4 => ['id' => 4, 'nombre' => 'Silla', 'categoria' => 'Muebles', 'precio' => 1899.00],
            5 => ['id' => 5, 'nombre' => 'Teclado', 'categoria' => 'Tecnología', 'precio' => 599.00],
            6 => ['id' => 6, 'nombre' => 'Lámpara', 'categoria' => 'Hogar', 'precio' => 450.00],
            7 => ['id' => 7, 'nombre' => 'Monitor', 'categoria' => 'Tecnología', 'precio' => 3299.00],
            8 => ['id' => 8, 'nombre' => 'Librero', 'categoria' => 'Muebles', 'precio' => 1799.00],
        ];
    }

    private function obtenerProductos(): array
    {
        return session()->get('productos', $this->productosIniciales());
    }

    public function index(Request $request)
    {
        $todos = $this->obtenerProductos();

        $buscar = $request->query('buscar', '');
        $categoria = $request->query('categoria', '');

        $productos = collect($todos);

        if ($buscar !== '') {
            $productos = $productos->filter(function ($producto) use ($buscar) {
                return str_contains(
                    mb_strtolower($producto['nombre']),
                    mb_strtolower($buscar)
                );
            });
        }

        if ($categoria !== '') {
            $productos = $productos->where('categoria', $categoria);
        }

        $categorias = collect($todos)
            ->pluck('categoria')
            ->unique()
            ->values();

        $pagina = LengthAwarePaginator::resolveCurrentPage();
        $porPagina = 5;
        $total = $productos->count();

        $productosPagina = $productos
            ->slice(($pagina - 1) * $porPagina, $porPagina)
            ->values();

        $productos = new LengthAwarePaginator(
            $productosPagina,
            $total,
            $porPagina,
            $pagina,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view('productos.index', compact(
            'productos',
            'categorias',
            'buscar',
            'categoria'
        ));
    }

    public function create()
    {
        $producto = [
            'nombre' => '',
            'categoria' => '',
            'precio' => '',
        ];

        return view('productos.crear', compact('producto'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|min:3|max:100',
            'categoria' => 'required|string|max:50',
            'precio' => 'required|numeric|min:0',
        ]);

        $productos = $this->obtenerProductos();

        $id = empty($productos) ? 1 : max(array_keys($productos)) + 1;

        $productos[$id] = [
            'id' => $id,
            'nombre' => $datos['nombre'],
            'categoria' => $datos['categoria'],
            'precio' => (float) $datos['precio'],
        ];

        session()->put('productos', $productos);

        return redirect()->route('productos.index')
            ->with('success', 'Producto creado correctamente.');
    }

    public function edit(int $id)
    {
        $productos = $this->obtenerProductos();

        if (!isset($productos[$id])) {
            return redirect()->route('productos.index')
                ->with('error', 'El producto no existe.');
        }

        $producto = $productos[$id];

        return view('productos.editar', compact('producto'));
    }

    public function update(Request $request, int $id)
    {
        $productos = $this->obtenerProductos();

        if (!isset($productos[$id])) {
            return redirect()->route('productos.index')
                ->with('error', 'El producto no existe.');
        }

        $datos = $request->validate([
            'nombre' => 'required|string|min:3|max:100',
            'categoria' => 'required|string|max:50',
            'precio' => 'required|numeric|min:0',
        ]);

        $productos[$id] = [
            'id' => $id,
            'nombre' => $datos['nombre'],
            'categoria' => $datos['categoria'],
            'precio' => (float) $datos['precio'],
        ];

        session()->put('productos', $productos);

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(int $id)
    {
        $productos = $this->obtenerProductos();

        if (!isset($productos[$id])) {
            return redirect()->route('productos.index')
                ->with('error', 'El producto no existe.');
        }

        unset($productos[$id]);

        session()->put('productos', $productos);

        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado correctamente.');
    }
}
