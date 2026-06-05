@extends('layouts.app')

@section('title', config('tienda.nombre_tienda') . ' - Relojes de Lujo')

@php
  $carrito     = session('carrito', []);
  $totalItems  = array_sum(array_column($carrito, 'cantidad'));
  $totalCarrito = 0;
  foreach ($carrito as $item) {
      $totalCarrito += $item['precio'] * $item['cantidad'];
  }
@endphp

@section('content')

  {{-- ──────────── Navbar ──────────── --}}
  <nav class="navbar-custom">
    <div class="container navbar-container">
      <div class="logo-container">
        <img src="{{ asset('assets/logo.png') }}" alt="{{ config('tienda.nombre_tienda') }}" class="logo-image" />
      </div>
      <div class="menu-links d-none d-lg-flex">
        <a href="#coleccion" class="nav-link-custom">Colección</a>
        <a href="#ofertas"   class="nav-link-custom">Ofertas</a>
        <a href="#vender"    class="nav-link-custom">Vende tu Reloj</a>
        <a href="#nosotros"  class="nav-link-custom">Quiénes Somos</a>
        <a href="#contacto"  class="nav-link-custom">Contacto</a>
        <a href="{{ route('admin.login') }}" class="nav-link-custom">Admin</a>
      </div>
      <div class="actions-container">
        <button class="cart-btn-custom" id="cart-toggle-btn" aria-label="Abrir carrito">
          <span class="cart-icon-custom">🛒</span>
          @if($totalItems > 0)
            <span class="cart-badge-custom">{{ $totalItems }}</span>
          @endif
        </button>
      </div>
    </div>
  </nav>

  {{-- ──────────── Hero ──────────── --}}
  <section class="hero-section" style="background-image: linear-gradient(rgba(10,10,10,0.55), rgba(10,10,10,0.92)), url('{{ asset('assets/watch_submariner.png') }}'); background-size:cover; background-position:center;">
    <div class="hero-overlay"></div>
    <div class="hero-content animate-fade-in">
      <h1 class="hero-title">El Tiempo es un Lujo</h1>
      <p class="hero-subtitle">Descubre nuestra colección exclusiva de relojes de alta gama.</p>
      <a href="#coleccion" class="btn-primary">Ver Colección</a>
    </div>
  </section>

  {{-- ──────────── Colección Destacada ──────────── --}}
  <section id="coleccion" class="product-section">
    <div class="container">
      <h2 class="section-heading">Colección Destacada</h2>
      <div class="product-grid">
        @forelse($productos as $producto)
          <div class="product-card-custom animate-fade-in">
            <div class="img-container-custom">
              <img src="{{ $producto->url_imagen }}" alt="{{ $producto->nombre }}" class="img-custom" />
              @if($producto->porcentaje_descuento > 0)
                <span class="offer-badge-card">-{{ $producto->porcentaje_descuento }}% OFF</span>
              @endif
              @if($producto->stock <= 2 && $producto->stock > 0)
                <span class="stock-badge">¡Últimas {{ $producto->stock }}!</span>
              @endif
            </div>
            <div class="card-content-custom">
              <span class="product-brand-custom">{{ $producto->marca }}</span>
              <h3 class="product-title-custom">{{ $producto->nombre }}</h3>
              <p class="product-desc-custom">{{ Str::limit($producto->descripcion, 100) }}</p>
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
          <p class="text-center w-100" style="color:#888;">No hay productos disponibles en este momento.</p>
        @endforelse
      </div>
    </div>
  </section>

  {{-- ──────────── Ofertas Exclusivas ──────────── --}}
  @if($ofertas->count() > 0)
  <section id="ofertas" class="offers-section">
    <div class="container">
      <h2 class="section-heading">Ofertas Exclusivas</h2>
      <div class="product-grid justify-content-center">
        @foreach($ofertas as $producto)
          <div class="offer-wrapper">
            <div class="offer-badge">-{{ $producto->porcentaje_descuento }}% OFF</div>
            <div class="product-card-custom animate-fade-in">
              <div class="img-container-custom">
                <img src="{{ $producto->url_imagen }}" alt="{{ $producto->nombre }}" class="img-custom" />
              </div>
              <div class="card-content-custom">
                <span class="product-brand-custom">{{ $producto->marca }}</span>
                <h3 class="product-title-custom">{{ $producto->nombre }}</h3>
                <p class="product-desc-custom">{{ Str::limit($producto->descripcion, 100) }}</p>
                <div class="card-footer-custom">
                  <div class="price-block">
                    {{-- Precio original tachado --}}
                    <span class="price-original">$ {{ number_format($producto->precio, 0, ',', '.') }}</span>
                    {{-- Precio con descuento real desde la BD --}}
                    <span class="product-price-custom">$ {{ number_format($producto->precio_final, 0, ',', '.') }}</span>
                  </div>
                  {{-- Sin campo de precio oculto: el precio viene siempre desde la BD --}}
                  <form action="{{ route('carrito.agregar', $producto->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-comprar-custom">Comprar</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>
  @endif

  {{-- ──────────── Vende tu Reloj ──────────── --}}
  <section id="vender" class="sell-section">
    <div class="container">
      <div class="sell-content-wrapper">
        <h2 class="section-heading">Vende tu Reloj</h2>
        <p class="sell-description">
          Compramos relojes de alta gama. Completa el formulario con los detalles de tu reloj
          y nos pondremos en contacto con una tasación personalizada.
        </p>

        @if(session('vender_exito'))
          <div class="success-message-custom mb-4">{{ session('vender_exito') }}</div>
        @endif

        <form action="{{ route('vender.enviar') }}" method="POST" class="sell-form-custom">
          @csrf
          <div class="input-group-custom">
            <label class="label-custom" for="vender-nombre">Nombre completo</label>
            <input id="vender-nombre" type="text" name="nombre" class="input-custom"
              placeholder="Ej. Juan Pérez" required value="{{ old('nombre') }}" />
          </div>
          <div class="input-group-custom">
            <label class="label-custom" for="vender-email">Correo electrónico</label>
            <input id="vender-email" type="email" name="email" class="input-custom"
              placeholder="tu@email.com" required value="{{ old('email') }}" />
          </div>
          <div class="input-group-custom">
            <label class="label-custom" for="vender-telefono">Teléfono</label>
            <input id="vender-telefono" type="tel" name="telefono" class="input-custom"
              placeholder="+56 9 1234 5678" required value="{{ old('telefono') }}" />
          </div>
          <div class="input-group-custom">
            <label class="label-custom" for="vender-descripcion">Descripción del Reloj</label>
            <textarea id="vender-descripcion" name="descripcion" class="textarea-custom"
              placeholder="Marca, modelo, año, estado, si incluye caja y papeles..."
              rows="5" required>{{ old('descripcion') }}</textarea>
          </div>
          <button type="submit" class="btn-primary w-100">Enviar Solicitud</button>
        </form>
      </div>
    </div>
  </section>

  {{-- ──────────── Quiénes Somos ──────────── --}}
  <section id="nosotros" class="about-section">
    <div class="container">
      <div class="about-content-wrapper">
        <div class="about-text-content">
          <h2 class="about-heading">Quiénes Somos</h2>
          <p class="about-paragraph">
            En <strong style="color:#c5a059;">{{ config('tienda.nombre_tienda') }}</strong> nos especializamos en la alta relojería internacional.
            Contamos con años de trayectoria asesorando a coleccionistas y entusiastas en la adquisición
            y venta de piezas exclusivas de marcas de renombre mundial.
          </p>
          <p class="about-paragraph">
            Nuestra misión es resguardar el valor del tiempo, ofreciendo una experiencia de compra
            transparente, segura y totalmente personalizada para cada cliente.
            Cada reloj en nuestro catálogo es verificado meticulosamente por expertos.
          </p>
        </div>
        <div class="about-image-placeholder">
          <span class="about-placeholder-text">LUXE Heritage</span>
        </div>
      </div>
    </div>
  </section>

  {{-- ──────────── Contacto ──────────── --}}
  <section id="contacto" class="contact-section">
    <div class="container">
      <h2 class="section-heading">Contacto y Ubicación</h2>
      <div class="contact-content-wrapper">

        <div class="contact-form-container">
          @if(session('contacto_exito'))
            <div class="success-message-custom mb-4">{{ session('contacto_exito') }}</div>
          @endif
          <form action="{{ route('contacto.enviar') }}" method="POST" class="sell-form-custom">
            @csrf
            <h3 class="contact-subheading">Envíanos un mensaje</h3>
            <div class="input-group-custom">
              <label class="label-custom" for="contacto-nombre">Nombre</label>
              <input id="contacto-nombre" type="text" name="nombre" class="input-custom"
                required value="{{ old('nombre') }}" />
            </div>
            <div class="input-group-custom">
              <label class="label-custom" for="contacto-email">Correo electrónico</label>
              <input id="contacto-email" type="email" name="email" class="input-custom"
                required value="{{ old('email') }}" />
            </div>
            <div class="input-group-custom">
              <label class="label-custom" for="contacto-mensaje">Mensaje</label>
              <textarea id="contacto-mensaje" name="mensaje" class="textarea-custom"
                rows="4" required>{{ old('mensaje') }}</textarea>
            </div>
            <button type="submit" class="btn-primary">Enviar Mensaje</button>
          </form>
        </div>

        <div class="contact-info-container">
          <h3 class="contact-subheading">Nuestra Tienda</h3>
          <p class="contact-info-text"><strong>Dirección:</strong> {{ config('tienda.direccion') }}</p>
          <p class="contact-info-text"><strong>Teléfono:</strong> {{ config('tienda.telefono_contacto') }}</p>
          <p class="contact-info-text"><strong>Email:</strong> {{ config('tienda.correo_contacto') }}</p>
          <p class="contact-info-text"><strong>Horario:</strong> Lunes a Viernes de 10:00 a 19:00 hrs.</p>
          <div class="map-placeholder-custom">
            <iframe
              src="https://www.google.com/maps?q={{ urlencode(config('tienda.direccion')) }}&output=embed"
              width="100%" height="100%" style="border:0;"
              allowfullscreen="" loading="lazy"
              referrerpolicy="no-referrer-when-downgrade"
              title="Mapa de ubicación {{ config('tienda.nombre_tienda') }}"
            ></iframe>
          </div>
        </div>

      </div>
    </div>
  </section>

  {{-- ──────────── Carrito (Overlay + Drawer) ──────────── --}}
  <div class="cart-overlay-custom" id="cart-overlay"
       style="display: {{ session('carrito_abierto') ? 'block' : 'none' }};"></div>

  <div class="cart-drawer-custom {{ session('carrito_abierto') ? 'open' : 'closed' }}"
       id="cart-drawer"
       style="transform: {{ session('carrito_abierto') ? 'translateX(0)' : 'translateX(100%)' }};">
    <div class="cart-header-custom">
      <h2>Tu Carrito</h2>
      <button class="cart-close-btn-custom" id="cart-close-btn" aria-label="Cerrar carrito">✕</button>
    </div>

    <div class="cart-items-container-custom">
      @if(empty($carrito))
        <p class="cart-empty-text-custom">Tu carrito está vacío.</p>
      @else
        @foreach($carrito as $item)
          <div class="cart-item-custom">
            @if($item['imagen'])
              <img src="{{ asset('storage/' . $item['imagen']) }}" alt="{{ $item['nombre'] }}" class="cart-item-image-custom" />
            @else
              <div class="cart-item-image-custom" style="background:#1a1a1a; display:flex; align-items:center; justify-content:center; font-size:1.5rem;">⌚</div>
            @endif
            <div class="cart-item-details-custom">
              <h4>{{ $item['nombre'] }}</h4>
              <p class="cart-item-price-custom">
                $ {{ number_format($item['precio'], 0, ',', '.') }}
                @if(isset($item['porcentaje_descuento']) && $item['porcentaje_descuento'] > 0)
                  <span style="font-size:0.75rem; color:#e74c3c; margin-left:0.3rem;">(-{{ $item['porcentaje_descuento'] }}%)</span>
                @endif
              </p>
              <div class="cart-qty-controls-custom">
                <form action="{{ route('carrito.actualizar', $item['id']) }}" method="POST">
                  @csrf
                  <input type="hidden" name="cantidad" value="-1">
                  <button type="submit" class="cart-qty-btn-custom" aria-label="Disminuir cantidad">−</button>
                </form>
                <span>{{ $item['cantidad'] }}</span>
                <form action="{{ route('carrito.actualizar', $item['id']) }}" method="POST">
                  @csrf
                  <input type="hidden" name="cantidad" value="1">
                  <button type="submit" class="cart-qty-btn-custom" aria-label="Aumentar cantidad">+</button>
                </form>
              </div>
            </div>
            <form action="{{ route('carrito.eliminar', $item['id']) }}" method="POST">
              @csrf
              <button type="submit" class="cart-remove-btn-custom" aria-label="Eliminar del carrito">🗑</button>
            </form>
          </div>
        @endforeach
      @endif
    </div>

    @if(!empty($carrito))
      <div class="cart-footer-custom">
        <div class="cart-total-row-custom">
          <span>Total:</span>
          <span class="cart-total-price-custom">$ {{ number_format($totalCarrito, 0, ',', '.') }}</span>
        </div>

        @php
          $waNumero  = config('tienda.telefono_whatsapp');
          $waMensaje = "Hola, me gustaría concretar la compra de los siguientes relojes:\n\n";
          foreach ($carrito as $item) {
              $waMensaje .= "- {$item['cantidad']}x {$item['nombre']} (\${$item['precio']})\n";
          }
          $waMensaje .= "\n*Total:* $" . number_format($totalCarrito, 0, ',', '.') . "\n\nQuedo atento(a) para coordinar el pago.";
          $waUrl = "https://wa.me/{$waNumero}?text=" . urlencode($waMensaje);
        @endphp

        <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer"
           class="cart-checkout-btn-custom text-decoration-none">
          💬 Checkout vía WhatsApp
        </a>

        <form action="{{ route('carrito.vaciar') }}" method="POST" style="margin-top:0.75rem;">
          @csrf
          <button type="submit" class="btn-secondary w-100" style="font-size:0.8rem; padding:0.5rem;">
            Vaciar carrito
          </button>
        </form>
      </div>
    @endif
  </div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const toggleBtn = document.getElementById('cart-toggle-btn');
  const closeBtn  = document.getElementById('cart-close-btn');
  const overlay   = document.getElementById('cart-overlay');
  const drawer    = document.getElementById('cart-drawer');

  function openCart() {
    overlay.style.display = 'block';
    drawer.style.transform = 'translateX(0)';
    drawer.classList.replace('closed', 'open');
  }

  function closeCart() {
    overlay.style.display = 'none';
    drawer.style.transform = 'translateX(100%)';
    drawer.classList.replace('open', 'closed');

    // Notificar al backend para que la sesión refleje el estado cerrado
    fetch("{{ route('carrito.toggle') }}", {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Content-Type': 'application/json'
      }
    });
  }

  if (toggleBtn) {
    toggleBtn.addEventListener('click', function (e) {
      e.preventDefault();
      drawer.classList.contains('open') ? closeCart() : openCart();
    });
  }
  if (closeBtn) closeBtn.addEventListener('click', closeCart);
  if (overlay)  overlay.addEventListener('click', closeCart);
});
</script>
@endsection
