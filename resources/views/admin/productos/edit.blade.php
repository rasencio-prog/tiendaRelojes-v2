@extends('admin.layouts.admin')

@section('titulo', 'Editar: ' . $producto->nombre)
@section('breadcrumb', 'Admin / Productos / Editar')

@section('content')

<div style="max-width: 860px;">
  <div class="card">
    <div class="card-header">
      <h2>Editar Reloj: <em style="color:#c5a059;">{{ $producto->nombre }}</em></h2>
      <a href="{{ route('admin.productos.index') }}" class="btn btn-secondary btn-sm">← Volver</a>
    </div>
    <div class="card-body">

      <form method="POST" action="{{ route('admin.productos.update', $producto) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-grid">

          {{-- Código de Producto --}}
          <div class="form-group">
            <label class="form-label" for="codigo_producto">Código de Producto <span style="color:#e74c3c">*</span></label>
            <input id="codigo_producto" type="text" name="codigo_producto"
              class="form-control {{ $errors->has('codigo_producto') ? 'is-invalid' : '' }}"
              value="{{ old('codigo_producto', $producto->codigo_producto) }}"
              required>
            @error('codigo_producto')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          {{-- Categoría --}}
          <div class="form-group">
            <label class="form-label" for="categoria">Categoría <span style="color:#e74c3c">*</span></label>
            <select id="categoria" name="categoria"
              class="form-control {{ $errors->has('categoria') ? 'is-invalid' : '' }}" required>
              @foreach($categorias as $cat)
                <option value="{{ $cat }}" {{ old('categoria', $producto->categoria) == $cat ? 'selected' : '' }}>
                  {{ $cat }}
                </option>
              @endforeach
            </select>
            @error('categoria')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          {{-- Nombre --}}
          <div class="form-group">
            <label class="form-label" for="nombre">Nombre del Reloj <span style="color:#e74c3c">*</span></label>
            <input id="nombre" type="text" name="nombre"
              class="form-control {{ $errors->has('nombre') ? 'is-invalid' : '' }}"
              value="{{ old('nombre', $producto->nombre) }}" required>
            @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          {{-- Marca --}}
          <div class="form-group">
            <label class="form-label" for="marca">Marca <span style="color:#e74c3c">*</span></label>
            <input id="marca" type="text" name="marca"
              class="form-control {{ $errors->has('marca') ? 'is-invalid' : '' }}"
              value="{{ old('marca', $producto->marca) }}" required>
            @error('marca')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          {{-- Precio --}}
          <div class="form-group">
            <label class="form-label" for="precio">Precio ($ CLP) <span style="color:#e74c3c">*</span></label>
            <input id="precio" type="number" name="precio"
              class="form-control {{ $errors->has('precio') ? 'is-invalid' : '' }}"
              value="{{ old('precio', $producto->precio) }}"
              min="0" step="1000" required>
            @error('precio')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          {{-- Descuento --}}
          <div class="form-group">
            <label class="form-label" for="porcentaje_descuento">Descuento (%)</label>
            <input id="porcentaje_descuento" type="number" name="porcentaje_descuento"
              class="form-control {{ $errors->has('porcentaje_descuento') ? 'is-invalid' : '' }}"
              value="{{ old('porcentaje_descuento', $producto->porcentaje_descuento) }}"
              min="0" max="100" required>
            @error('porcentaje_descuento')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          {{-- Stock --}}
          <div class="form-group">
            <label class="form-label" for="stock">Stock</label>
            <input id="stock" type="number" name="stock"
              class="form-control {{ $errors->has('stock') ? 'is-invalid' : '' }}"
              value="{{ old('stock', $producto->stock) }}"
              min="0" required>
            @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          {{-- Imagen --}}
          <div class="form-group">
            <label class="form-label" for="imagen">Imagen del Reloj</label>
            @if($producto->imagen)
              <div style="margin-bottom:0.75rem;">
                <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}"
                  style="width:100px; height:100px; object-fit:cover; border-radius:4px; border:1px solid #333;">
                <div class="form-hint">Imagen actual. Sube una nueva para reemplazarla.</div>
              </div>
            @endif
            <label class="image-upload-area" for="imagen">
              <input id="imagen" type="file" name="imagen" accept="image/jpeg,image/png,image/webp"
                onchange="previewImage(this)">
              <div class="upload-icon">📷</div>
              <div class="upload-text">Nueva imagen (opcional)<br>
                <small>JPG, PNG o WebP — máx. 3MB</small>
              </div>
              <img id="img-preview" class="image-preview" style="display:none;" alt="Vista previa">
            </label>
            @error('imagen')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          {{-- Descripción --}}
          <div class="form-group full">
            <label class="form-label" for="descripcion">Descripción <span style="color:#e74c3c">*</span></label>
            <textarea id="descripcion" name="descripcion"
              class="form-control {{ $errors->has('descripcion') ? 'is-invalid' : '' }}"
              rows="4" required>{{ old('descripcion', $producto->descripcion) }}</textarea>
            @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          {{-- Checkboxes --}}
          <div class="form-group">
            <label class="form-label">Opciones</label>
            <div class="form-check" style="margin-bottom:0.6rem;">
              <input type="checkbox" id="destacado" name="destacado" value="1"
                {{ old('destacado', $producto->destacado) ? 'checked' : '' }}>
              <label for="destacado">⭐ Producto destacado</label>
            </div>
            <div class="form-check">
              <input type="checkbox" id="activo" name="activo" value="1"
                {{ old('activo', $producto->activo) ? 'checked' : '' }}>
              <label for="activo">🟢 Activo (visible en la tienda)</label>
            </div>
          </div>

        </div>{{-- /form-grid --}}

        <div class="form-actions" style="margin-top:2rem; padding-top:1.5rem; border-top:1px solid #2a2a2a;">
          <button type="submit" class="btn btn-primary">💾 Guardar Cambios</button>
          <a href="{{ route('admin.productos.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>

      </form>
    </div>
  </div>
</div>

<script>
function previewImage(input) {
  const preview = document.getElementById('img-preview');
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; };
    reader.readAsDataURL(input.files[0]);
  }
}
</script>

@endsection
