@extends('admin.layouts.admin')

@section('titulo', 'Productos')
@section('breadcrumb', 'Admin / Productos')

@section('content')

<div class="card">
  <div class="card-header">
    <h2>Catálogo de Productos ({{ $productos->total() }})</h2>
    <a href="{{ route('admin.productos.create') }}" class="btn btn-primary">
      ➕ Agregar Producto
    </a>
  </div>

  <div class="table-wrapper">
    <table>
      <thead>
        <tr>
          <th style="width:60px;"></th>
          <th>Código</th>
          <th>Nombre / Marca</th>
          <th>Categoría</th>
          <th>Precio</th>
          <th>Descuento</th>
          <th>Stock</th>
          <th>Estado</th>
          <th style="text-align:right;">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($productos as $producto)
        <tr>
          {{-- Imagen --}}
          <td>
            @if($producto->imagen)
              <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="product-img-thumb">
            @else
              <div class="img-placeholder-thumb">⌚</div>
            @endif
          </td>

          {{-- Código --}}
          <td style="font-size:0.8rem; color:#888;">{{ $producto->codigo_producto }}</td>

          {{-- Nombre / Marca --}}
          <td>
            <strong>{{ $producto->nombre }}</strong>
            <div style="font-size:0.78rem; color:#888;">{{ $producto->marca }}</div>
          </td>

          {{-- Categoría --}}
          <td style="font-size:0.85rem; color:#888;">{{ $producto->categoria }}</td>

          {{-- Precio --}}
          <td style="color: #c5a059; font-weight:600;">
            $ {{ number_format($producto->precio, 0, ',', '.') }}
          </td>

          {{-- Descuento --}}
          <td>
            @if($producto->porcentaje_descuento > 0)
              <span class="badge badge-discount">-{{ $producto->porcentaje_descuento }}%</span>
            @else
              <span style="color:#555;">—</span>
            @endif
          </td>

          {{-- Stock --}}
          <td>
            <span style="{{ $producto->stock == 0 ? 'color:#e74c3c;' : '' }}">
              {{ $producto->stock }}
            </span>
          </td>

          {{-- Estado --}}
          <td>
            @if($producto->activo)
              <span class="badge badge-active">Activo</span>
            @else
              <span class="badge badge-inactive">Inactivo</span>
            @endif
          </td>

          {{-- Acciones --}}
          <td style="text-align:right; white-space:nowrap;">
            {{-- Toggle Activo/Inactivo --}}
            <form method="POST" action="{{ route('admin.productos.toggle-activo', $producto) }}" style="display:inline;">
              @csrf
              @method('PATCH')
              <button type="submit" class="btn btn-sm {{ $producto->activo ? 'btn-toggle-on' : 'btn-toggle-off' }}"
                title="{{ $producto->activo ? 'Desactivar' : 'Activar' }}">
                {{ $producto->activo ? '🔴 Desact.' : '🟢 Activar' }}
              </button>
            </form>

            {{-- Editar --}}
            <a href="{{ route('admin.productos.edit', $producto) }}" class="btn btn-secondary btn-sm">✏️ Editar</a>

            {{-- Eliminar --}}
            <form method="POST" action="{{ route('admin.productos.destroy', $producto) }}" style="display:inline;"
              onsubmit="return confirm('¿Eliminar el producto «{{ addslashes($producto->nombre) }}»? Esta acción no se puede deshacer.')">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger btn-sm">🗑 Eliminar</button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="9" style="text-align:center; color:#888; padding:3rem;">
            No hay productos en el catálogo.
            <a href="{{ route('admin.productos.create') }}" style="color:#c5a059;">Crear el primero</a>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Paginación --}}
  @if($productos->hasPages())
  <div style="padding: 1.5rem; border-top: 1px solid #2a2a2a;">
    {{ $productos->links() }}
  </div>
  @endif
</div>

@endsection
