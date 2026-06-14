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

          {{-- Imágenes actuales --}}
          @if($producto->imagenes->isNotEmpty())
          <div class="form-group full">
            <label class="form-label">Imágenes actuales</label>
            <p class="form-hint" style="margin-bottom:0.75rem;">Desmarca las imágenes que deseas eliminar.</p>
            <div style="display:flex; flex-wrap:wrap; gap:1rem;">
              @foreach($producto->imagenes as $imagen)
              <div style="text-align:center;">
                <img src="{{ $imagen->url_imagen }}" alt="Imagen {{ $loop->iteration }}"
                  style="width:110px; height:110px; object-fit:cover; border-radius:6px; border:1px solid #333; display:block; margin-bottom:0.5rem;">
                <label style="font-size:0.8rem; color:#a3a3a3; cursor:pointer; display:flex; align-items:center; gap:5px; justify-content:center;">
                  <input type="checkbox" name="imagenes_existentes[]" value="{{ $imagen->id }}" checked>
                  Mantener
                </label>
              </div>
              @endforeach
            </div>
          </div>
          @endif

          {{-- Agregar nuevas imágenes --}}
          <div class="form-group full">
            <label class="form-label">
              Agregar imágenes nuevas
              <span id="img-counter" style="font-weight:400; color:#888; font-size:0.8rem; margin-left:0.4rem;">0 seleccionadas</span>
            </label>
            <div id="file-inputs-container"></div>
            <button type="button" id="add-img-btn" onclick="agregarImagen()"
              style="width:100%; padding:1.5rem; border:2px dashed #2a2a2a; border-radius:8px;
                     background:#0d0d0d; color:#888; font-size:0.9rem; cursor:pointer;
                     transition:border-color .2s;">
              📷 &nbsp;Seleccionar imagen &nbsp;<small style="display:block; margin-top:0.3rem; font-size:0.78rem;">JPG, PNG o WebP — máx. 2MB — hasta 5 en total</small>
            </button>
            <div id="image-previews" class="image-previews-container"></div>
            @error('imagenes')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            @error('imagenes.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
          </div>

          {{-- Descripción --}}
          <div class="form-group full">
            <label class="form-label" for="descripcion">Descripción <span style="color:#e74c3c">*</span></label>
            <textarea id="descripcion" name="descripcion"
              class="form-control {{ $errors->has('descripcion') ? 'is-invalid' : '' }}"
              rows="4"
              oninput="actualizarContador('descripcion','contador-desc')"
              required>{{ old('descripcion', $producto->descripcion) }}</textarea>
            <div style="text-align:right; font-size:0.78rem; color:#888; margin-top:0.3rem;">
              <span id="contador-desc">0</span> de 100
            </div>
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
function actualizarContador(textareaId, spanId) {
  const len = document.getElementById(textareaId).value.length;
  const span = document.getElementById(spanId);
  span.textContent = len;
  span.style.color = len > 100 ? '#e74c3c' : '#888';
}

// Inicializar contador con el texto existente al cargar la página
document.addEventListener('DOMContentLoaded', function () {
  actualizarContador('descripcion', 'contador-desc');
});

let imgCount = 0;

function agregarImagen() {
  const input = document.createElement('input');
  input.type   = 'file';
  input.name   = 'imagenes[]';
  input.accept = 'image/jpeg,image/png,image/webp';
  input.style.display = 'none';

  input.addEventListener('change', function () {
    if (!this.files || !this.files[0]) {
      this.remove();
      return;
    }
    imgCount++;
    actualizarBoton();
    mostrarPreview(this.files[0], this);
  });

  document.getElementById('file-inputs-container').appendChild(input);
  input.click();
}

function mostrarPreview(file, inputEl) {
  const reader = new FileReader();
  reader.onload = (e) => {
    const wrapper = document.createElement('div');
    wrapper.className = 'image-preview-wrapper';

    const img = document.createElement('img');
    img.src = e.target.result;
    img.className = 'image-preview';
    img.alt = file.name;

    const btn = document.createElement('button');
    btn.type = 'button';
    btn.textContent = '✕';
    btn.className = 'img-remove-btn';
    btn.onclick = () => {
      inputEl.remove();
      wrapper.remove();
      imgCount--;
      actualizarBoton();
    };

    wrapper.appendChild(img);
    wrapper.appendChild(btn);
    document.getElementById('image-previews').appendChild(wrapper);
  };
  reader.readAsDataURL(file);
}

function actualizarBoton() {
  document.getElementById('img-counter').textContent = imgCount + ' seleccionadas';
  const btn = document.getElementById('add-img-btn');
  btn.style.opacity = imgCount >= 5 ? '0.5' : '1';
  btn.style.cursor  = imgCount >= 5 ? 'not-allowed' : 'pointer';
}

document.getElementById('add-img-btn').addEventListener('mouseenter', function () {
  if (imgCount < 5) this.style.borderColor = '#c5a059';
});
document.getElementById('add-img-btn').addEventListener('mouseleave', function () {
  this.style.borderColor = '#2a2a2a';
});
</script>

@endsection
