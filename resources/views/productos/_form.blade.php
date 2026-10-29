<div class="mb-3">
    <label for="nombre" class="form-label">Nombre del producto</label>
    <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror"
        value="{{ old('nombre', $producto['nombre'] ?? '') }}" required minlength="3" maxlength="100">
    @error('nombre')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="categoria" class="form-label">Categoría</label>
    <select name="categoria" id="categoria" class="form-select @error('categoria') is-invalid @enderror" required>
        <option value="">Selecciona una categoría</option>

        @foreach (['Tecnología', 'Muebles', 'Hogar'] as $opcion)
            <option value="{{ $opcion }}"
                {{ old('categoria', $producto['categoria'] ?? '') == $opcion ? 'selected' : '' }}>
                {{ $opcion }}
            </option>
        @endforeach
    </select>

    @error('categoria')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="precio" class="form-label">Precio</label>
    <input type="number" name="precio" id="precio" class="form-control @error('precio') is-invalid @enderror"
        value="{{ old('precio', $producto['precio'] ?? '') }}" min="0" step="0.01" required>
    @error('precio')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
