@extends('admin.layouts.admin')

@section('titulo', 'Agregar Producto')
@section('breadcrumb', 'Admin / Productos / Nuevo')

@section('content')

<div style="max-width: 860px;">
  <div class="card">
    <div class="card-header">
      <h2>Agregar Nuevo Reloj</h2>
      <a href="{{ route('admin.productos.index') }}" class="btn btn-secondary btn-sm">← Volver</a>
    </div>
    <div class="card-body">

      <form method="POST" action="{{ route('admin.productos.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-grid">

          {{-- Código de Producto --}}
          <div class="form-group">
            <label class="form-label" for="codigo_producto">Código de Producto <span style="color:#e74c3c">*</span></label>
            <input id="codigo_producto" type="text" name="codigo_producto"
              class="form-control {{ $errors->has('codigo_producto') ? 'is-invalid' : '' }}"
              value="{{ old('codigo_producto') }}"
              placeholder="Ej: ROL-SUB-001" required>
            @error('codigo_producto')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <div class="form-hint">Código único para identificar el producto.</div>
          </div>

          {{-- Categoría --}}
          <div class="form-group">
            <label class="form-label" for="categoria">Categoría <span style="color:#e74c3c">*</span></label>
            <select id="categoria" name="categoria"
              class="form-control {{ $errors->has('categoria') ? 'is-invalid' : '' }}" required>
              <option value="">Selecciona una categoría...</option>
              @foreach($categorias as $cat)
                <option value="{{ $cat }}" {{ old('categoria') == $cat ? 'selected' : '' }}>
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
              value="{{ old('nombre') }}"
              placeholder="Ej: Submariner Date" required>
            @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          {{-- Marca --}}
          <div class="form-group">
            <label class="form-label" for="marca">Marca <span style="color:#e74c3c">*</span></label>
            <input id="marca" type="text" name="marca"
              class="form-control {{ $errors->has('marca') ? 'is-invalid' : '' }}"
              value="{{ old('marca') }}"
              placeholder="Ej: Rolex" required>
            @error('marca')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          {{-- Precio --}}
          <div class="form-group">
            <label class="form-label" for="precio">Precio ($ CLP) <span style="color:#e74c3c">*</span></label>
            <input id="precio" type="number" name="precio"
              class="form-control {{ $errors->has('precio') ? 'is-invalid' : '' }}"
              value="{{ old('precio') }}"
              min="0" step="1000" placeholder="Ej: 9500000" required>
            @error('precio')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          {{-- Porcentaje Descuento --}}
          <div class="form-group">
            <label class="form-label" for="porcentaje_descuento">Descuento (%) <span style="color:#e74c3c">*</span></label>
            <input id="porcentaje_descuento" type="number" name="porcentaje_descuento"
              class="form-control {{ $errors->has('porcentaje_descuento') ? 'is-invalid' : '' }}"
              value="{{ old('porcentaje_descuento', 0) }}"
              min="0" max="100" required>
            @error('porcentaje_descuento')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <div class="form-hint">0 = sin descuento. Aparece en la sección de Ofertas si es &gt; 0.</div>
          </div>

          {{-- Stock --}}
          <div class="form-group">
            <label class="form-label" for="stock">Stock <span style="color:#e74c3c">*</span></label>
            <input id="stock" type="number" name="stock"
              class="form-control {{ $errors->has('stock') ? 'is-invalid' : '' }}"
              value="{{ old('stock', 1) }}"
              min="0" required>
            @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          {{-- Imágenes --}}
          <div class="form-group full">
            <label class="form-label">
              Imágenes del Reloj
              <span id="img-counter" style="font-weight:400; color:#888; font-size:0.8rem; margin-left:0.4rem;">0 / 5 seleccionadas</span>
            </label>
            {{-- Inputs de archivo ocultos se inyectan aquí al seleccionar --}}
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

          {{-- Descripción (full width) --}}
          <div class="form-group full">
            <label class="form-label" for="descripcion">Descripción <span style="color:#e74c3c">*</span></label>
            <textarea id="descripcion" name="descripcion"
              class="form-control {{ $errors->has('descripcion') ? 'is-invalid' : '' }}"
              rows="4"
              placeholder="Describe el reloj: movimiento, materiales, certificaciones, historia..."
              oninput="actualizarContador('descripcion','contador-desc')"
              required>{{ old('descripcion') }}</textarea>
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
                {{ old('destacado') ? 'checked' : '' }}>
              <label for="destacado">⭐ Producto destacado (aparece primero en el catálogo)</label>
            </div>
            <div class="form-check">
              <input type="checkbox" id="activo" name="activo" value="1"
                {{ old('activo', true) ? 'checked' : '' }}>
              <label for="activo">🟢 Activo (visible en la tienda)</label>
            </div>
          </div>

        </div>{{-- /form-grid --}}

        <div class="form-actions" style="margin-top:2rem; padding-top:1.5rem; border-top:1px solid #2a2a2a;">
          <button type="submit" class="btn btn-primary">
            💾 Guardar Producto
          </button>
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

const MAX_IMGS = 5;
let imgCount = 0;

function agregarImagen() {
  if (imgCount >= MAX_IMGS) return;

  const input = document.createElement('input');
  input.type    = 'file';
  input.name    = 'imagenes[]';
  input.accept  = 'image/jpeg,image/png,image/webp';
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
  document.getElementById('img-counter').textContent = imgCount + ' / ' + MAX_IMGS + ' seleccionadas';
  const btn = document.getElementById('add-img-btn');
  btn.disabled = imgCount >= MAX_IMGS;
  btn.style.opacity = imgCount >= MAX_IMGS ? '0.5' : '1';
  btn.style.cursor  = imgCount >= MAX_IMGS ? 'not-allowed' : 'pointer';
}

document.getElementById('add-img-btn').addEventListener('mouseenter', function () {
  if (!this.disabled) this.style.borderColor = '#c5a059';
});
document.getElementById('add-img-btn').addEventListener('mouseleave', function () {
  this.style.borderColor = '#2a2a2a';
});
</script>

@endsection
