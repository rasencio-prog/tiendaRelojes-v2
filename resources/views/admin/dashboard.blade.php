@extends('admin.layouts.admin')

@section('titulo', 'Dashboard')
@section('breadcrumb', 'Admin / Dashboard')

@section('content')

@php
  use App\Models\Producto;
  $totalProductos  = Producto::count();
  $productosActivos   = Producto::activos()->count();
  $productosInactivos = Producto::where('activo', false)->count();
  $productosEnOferta  = Producto::activos()->enOferta()->count();
  $sinStock           = Producto::activos()->where('stock', 0)->count();
@endphp

{{-- Estadísticas --}}
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-label">Total Productos</div>
    <div class="stat-value">{{ $totalProductos }}</div>
    <div class="stat-desc">en el catálogo</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">Activos</div>
    <div class="stat-value" style="color: #27ae60">{{ $productosActivos }}</div>
    <div class="stat-desc">visibles en la tienda</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">En Oferta</div>
    <div class="stat-value" style="color: #e74c3c">{{ $productosEnOferta }}</div>
    <div class="stat-desc">con descuento activo</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">Sin Stock</div>
    <div class="stat-value" style="color: #f39c12">{{ $sinStock }}</div>
    <div class="stat-desc">productos agotados</div>
  </div>
</div>

{{-- Últimos productos --}}
<div class="card">
  <div class="card-header">
    <h2>Últimos Productos Agregados</h2>
    <a href="{{ route('admin.productos.create') }}" class="btn btn-primary btn-sm">
      ➕ Nuevo Producto
    </a>
  </div>
  <div class="card-body" style="padding: 0;">
    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th></th>
            <th>Código</th>
            <th>Nombre</th>
            <th>Marca</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Estado</th>
          </tr>
        </thead>
        <tbody>
          @forelse(Producto::orderBy('created_at','desc')->take(8)->get() as $producto)
          <tr>
            <td>
              @if($producto->imagen)
                <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="product-img-thumb">
              @else
                <div class="img-placeholder-thumb">⌚</div>
              @endif
            </td>
            <td style="color: #888; font-size: 0.8rem;">{{ $producto->codigo_producto }}</td>
            <td><strong>{{ $producto->nombre }}</strong></td>
            <td style="color: #888;">{{ $producto->marca }}</td>
            <td style="color: #c5a059;">$ {{ number_format($producto->precio, 0, ',', '.') }}</td>
            <td>{{ $producto->stock }}</td>
            <td>
              @if($producto->activo)
                <span class="badge badge-active">Activo</span>
              @else
                <span class="badge badge-inactive">Inactivo</span>
              @endif
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" style="text-align:center; color:#888; padding: 2rem;">
              No hay productos. <a href="{{ route('admin.productos.create') }}" style="color:#c5a059;">Agregar el primero</a>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection
