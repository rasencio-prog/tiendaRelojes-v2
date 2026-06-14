<div class="coleccion-header">
  <span class="coleccion-count">
    {{ $productos->count() }} {{ $productos->count() == 1 ? 'reloj' : 'relojes' }}
  </span>
</div>

<div class="product-grid">
  @forelse($productos as $producto)
    <div class="product-card-custom animate-fade-in">
      @php $imgs = $producto->imagenes; $totalImgs = $imgs->count(); @endphp
      <div class="img-container-custom">
        @if($totalImgs > 0)
          @foreach($imgs as $idx => $img)
            <img src="{{ $img->url_imagen }}" alt="{{ $producto->nombre }}"
                 class="carousel-slide"
                 style="{{ $idx > 0 ? 'display:none;' : '' }}">
          @endforeach
        @else
          <img src="{{ asset('assets/watch_submariner.png') }}" alt="{{ $producto->nombre }}" class="carousel-slide">
        @endif
        @if($totalImgs > 1)
          <button class="carousel-btn carousel-prev" onclick="carouselNav(this,-1)" aria-label="Imagen anterior">&#8249;</button>
          <button class="carousel-btn carousel-next" onclick="carouselNav(this,1)" aria-label="Imagen siguiente">&#8250;</button>
          <div class="carousel-dots">
            @foreach($imgs as $idx => $img)
              <span class="carousel-dot {{ $idx === 0 ? 'active' : '' }}" onclick="carouselGoTo(this,{{ $idx }})"></span>
            @endforeach
          </div>
        @endif
        @if($producto->porcentaje_descuento > 0)
          <span class="offer-badge-card">-{{ $producto->porcentaje_descuento }}% OFF</span>
        @endif
      </div>
      <div class="card-content-custom">
        <span class="product-brand-custom">{{ $producto->marca }}</span>
        <h3 class="product-title-custom">{{ $producto->nombre }}</h3>
        <p class="product-desc-custom">
          {{ Str::limit($producto->descripcion, 100) }}
          @if(strlen($producto->descripcion) > 100)
            <button class="btn-mas-detalle"
              onclick="abrirDetalle({{ $producto->id }}, '{{ addslashes($producto->nombre) }}', '{{ addslashes($producto->descripcion) }}')">
              más detalle
            </button>
          @endif
        </p>
        <div class="card-footer-custom">
          <div class="price-block">
            @if($producto->porcentaje_descuento > 0)
              <span class="price-original">$ {{ number_format($producto->precio, 0, ',', '.') }}</span>
            @endif
            <span class="product-price-custom">$ {{ number_format($producto->precio_final, 0, ',', '.') }}</span>
          </div>
          @if($producto->stock > 0)
            <form action="{{ route('carrito.agregar', $producto->id) }}" method="POST">
              @csrf
              <button type="submit" class="btn-comprar-custom">Comprar</button>
            </form>
          @else
            <span class="badge-sin-stock">Agotado</span>
          @endif
        </div>
      </div>
    </div>
  @empty
    <p style="color:#888; grid-column:1/-1;">No hay relojes que coincidan con los filtros seleccionados.</p>
  @endforelse
</div>
