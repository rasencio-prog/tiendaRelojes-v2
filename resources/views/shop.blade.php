@extends('layouts.app')

@section('title', 'LUXE - Relojes de Lujo')

@php
  $cart = session('cart', []);
  $cartCount = array_sum(array_column($cart, 'quantity'));
  $cartTotal = 0;
  foreach ($cart as $item) {
      $cartTotal += $item['price'] * $item['quantity'];
  }
@endphp

@section('content')
  <!-- Navbar -->
  <nav class="navbar-custom">
    <div class="container navbar-container">
      <div class="logo-container">
        <img src="{{ asset('assets/logo.png') }}" alt="Rolejeria" class="logo-image" />
      </div>
      <div class="menu-links d-none d-lg-flex">
        <a href="#coleccion" class="nav-link-custom">Ver Colección</a>
        <a href="#ofertas" class="nav-link-custom">Ofertas</a>
        <a href="#vender" class="nav-link-custom">Vende tu reloj</a>
        <a href="#nosotros" class="nav-link-custom">Quiénes somos</a>
        <a href="#contacto" class="nav-link-custom">Contacto</a>
        <a href="#admin" class="nav-link-custom">Administración</a>
      </div>
      <div class="actions-container">
        <button class="cart-btn-custom" id="cart-toggle-btn">
          <span class="cart-icon-custom">🛒</span>
          @if($cartCount > 0)
            <span class="cart-badge-custom">{{ $cartCount }}</span>
          @endif
        </button>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero-section" style="background-image: linear-gradient(rgba(10, 10, 10, 0.6), rgba(10, 10, 10, 0.9)), url('{{ asset('assets/watch_submariner.png') }}'); background-size: cover; background-position: center;">
    <div class="hero-overlay"></div>
    <div class="hero-content animate-fade-in">
      <h1 class="hero-title">El Tiempo es un Lujo</h1>
      <p class="hero-subtitle">Descubre nuestra colección exclusiva de relojes de alta gama.</p>
      <a href="#coleccion" class="btn-primary">Ver Colección</a>
    </div>
  </section>

  <!-- Colección Destacada -->
  <section id="coleccion" class="product-section">
    <div class="container">
      <h2 class="section-heading">Colección Destacada</h2>
      <div class="product-grid">
        @forelse($products as $product)
          <div class="product-card-custom animate-fade-in">
            <div class="img-container-custom">
              <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="img-custom" />
            </div>
            <div class="card-content-custom">
              <span class="product-brand-custom">{{ $product->brand }}</span>
              <h3 class="product-title-custom">{{ $product->name }}</h3>
              <p class="product-desc-custom">{{ $product->description }}</p>
              <div class="card-footer-custom">
                <span class="product-price-custom">${{ number_format($product->price, 0, ',', '.') }}</span>
                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                  @csrf
                  <button type="submit" class="btn-comprar-custom">Comprar</button>
                </form>
              </div>
            </div>
          </div>
        @empty
          <p class="text-center w-100 text-muted">No hay productos en el catálogo.</p>
        @endforelse
      </div>
    </div>
  </section>

  <!-- Ofertas Exclusivas -->
  <section id="ofertas" class="offers-section">
    <div class="container">
      <h2 class="section-heading">Ofertas Exclusivas</h2>
      <div class="product-grid justify-content-center">
        @forelse($offers as $product)
          <div class="offer-wrapper">
            <div class="offer-badge">-15% OFF</div>
            <div class="product-card-custom animate-fade-in">
              <div class="img-container-custom">
                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="img-custom" />
              </div>
              <div class="card-content-custom">
                <span class="product-brand-custom">{{ $product->brand }}</span>
                <h3 class="product-title-custom">{{ $product->name }}</h3>
                <p class="product-desc-custom">{{ $product->description }}</p>
                <div class="card-footer-custom">
                  <span class="product-price-custom">
                    <span style="text-decoration: line-through; color: var(--color-text-secondary); font-size: 0.9rem; margin-right: 0.5rem;">
                      ${{ number_format($product->originalPrice, 0, ',', '.') }}
                    </span>
                    ${{ number_format($product->price, 0, ',', '.') }}
                  </span>
                  <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="price" value="{{ $product->price }}">
                    <button type="submit" class="btn-comprar-custom">Comprar</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        @empty
          <p class="text-center w-100 text-muted">No hay ofertas disponibles en este momento.</p>
        @endforelse
      </div>
    </div>
  </section>

  <!-- Vende tu Reloj -->
  <section id="vender" class="sell-section">
    <div class="container">
      <div class="sell-content-wrapper">
        <h2 class="section-heading">Vende tu Reloj</h2>
        <p class="sell-description">
          Compramos relojes de alta gama. Completa el formulario a continuación con los detalles 
          de tu reloj y nos pondremos en contacto contigo a la brevedad con una tasación.
        </p>
        
        @if(session('sell_success'))
          <div class="success-message-custom mb-4">
            {{ session('sell_success') }}
          </div>
        @endif

        <form action="{{ route('sell.submit') }}" method="POST" class="sell-form-custom">
          @csrf
          <div class="input-group-custom">
            <label class="label-custom">Nombre completo</label>
            <input type="text" name="name" class="input-custom" placeholder="Ej. Juan Pérez" required value="{{ old('name') }}" />
          </div>
          <div class="input-group-custom">
            <label class="label-custom">Correo electrónico</label>
            <input type="email" name="email" class="input-custom" placeholder="tu@email.com" required value="{{ old('email') }}" />
          </div>
          <div class="input-group-custom">
            <label class="label-custom">Teléfono</label>
            <input type="tel" name="phone" class="input-custom" placeholder="+56 9 1234 5678" required value="{{ old('phone') }}" />
          </div>
          <div class="input-group-custom">
            <label class="label-custom">Descripción del Reloj</label>
            <textarea 
              name="description" 
              class="textarea-custom" 
              placeholder="Marca, modelo, año de compra, estado general, si incluye caja y papeles..." 
              rows="5"
              required
            >{{ old('description') }}</textarea>
          </div>
          <button type="submit" class="btn-primary w-100">Enviar Solicitud</button>
        </form>
      </div>
    </div>
  </section>

  <!-- Quiénes Somos -->
  <section id="nosotros" class="about-section">
    <div class="container">
      <div class="about-content-wrapper">
        <div class="about-text-content">
          <h2 class="about-heading">Quiénes Somos</h2>
          <p class="about-paragraph">
            En LUXE nos especializamos en la alta relojería internacional. Contamos con años de trayectoria asesorando a coleccionistas y entusiastas en la adquisición y venta de piezas exclusivas de marcas de renombre mundial.
          </p>
          <p class="about-paragraph">
            Nuestra misión es resguardar el valor del tiempo, ofreciendo una experiencia de compra transparente, segura y totalmente personalizada para cada uno de nuestros clientes. Cada reloj en nuestro catálogo es verificado meticulosamente por expertos.
          </p>
        </div>
        <div class="about-image-placeholder">
          <span class="about-placeholder-text">LUXE Heritage</span>
        </div>
      </div>
    </div>
  </section>

  <!-- Contacto y Ubicación -->
  <section id="contacto" class="contact-section">
    <div class="container">
      <h2 class="section-heading">Contacto y Ubicación</h2>
      <div class="contact-content-wrapper">
        <div class="contact-form-container">
          @if(session('contact_success'))
            <div class="success-message-custom mb-4">
              {{ session('contact_success') }}
            </div>
          @endif
          <form action="{{ route('contact.submit') }}" method="POST" class="sell-form-custom">
            @csrf
            <h3 class="contact-subheading">Envíanos un mensaje</h3>
            <div class="input-group-custom">
              <label class="label-custom">Nombre</label>
              <input type="text" name="name" class="input-custom" required value="{{ old('name') }}" />
            </div>
            <div class="input-group-custom">
              <label class="label-custom">Correo electrónico</label>
              <input type="email" name="email" class="input-custom" required value="{{ old('email') }}" />
            </div>
            <div class="input-group-custom">
              <label class="label-custom">Mensaje</label>
              <textarea name="message" class="textarea-custom" rows="4" required>{{ old('message') }}</textarea>
            </div>
            <button type="submit" class="btn-primary">Enviar Mensaje</button>
          </form>
        </div>
        
        <div class="contact-info-container">
          <h3 class="contact-subheading">Nuestra Tienda</h3>
          <p class="contact-info-text"><strong>Dirección:</strong> Av. Vitacura 2808, Las Condes, Santiago, Chile</p>
          <p class="contact-info-text"><strong>Teléfono:</strong> +56 9 1234 5678</p>
          <p class="contact-info-text"><strong>Email:</strong> contacto@rolejeria.cl</p>
          <p class="contact-info-text"><strong>Horario:</strong> Lunes a Viernes de 10:00 a 19:00 hrs.</p>
          
          <div class="map-placeholder-custom">
            <iframe 
              src="https://www.google.com/maps?q=Vitacura+2808,+Las+Condes,+Santiago,+Chile&output=embed" 
              width="100%" 
              height="100%" 
              style="border: 0;" 
              allowfullscreen="" 
              loading="lazy" 
              referrerpolicy="no-referrer-when-downgrade"
              title="Mapa de ubicación"
            ></iframe>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Administración -->
  <section id="admin" class="admin-section">
    <div class="container">
      <div class="admin-content-wrapper">
        @if(session('admin_authenticated'))
          <!-- Panel de administración (Autenticado) -->
          <h2 class="section-heading">Panel de Administración</h2>
          <p class="text-center text-muted mb-4">Agrega nuevos relojes al catálogo en tiempo real.</p>
          
          @if(session('admin_success'))
            <div class="success-message-custom mb-4">
              {{ session('admin_success') }}
            </div>
          @endif

          <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="sell-form-custom">
            @csrf
            <div class="admin-form-row">
              <div class="input-group-custom flex-fill">
                <label class="label-custom">Nombre del Reloj</label>
                <input type="text" name="name" class="input-custom" required value="{{ old('name') }}" />
              </div>
              <div class="input-group-custom flex-fill">
                <label class="label-custom">Marca</label>
                <input type="text" name="brand" class="input-custom" required value="{{ old('brand') }}" />
              </div>
            </div>
            
            <div class="input-group-custom">
              <label class="label-custom">Precio ($ CLP)</label>
              <input type="number" name="price" class="input-custom" required min="0" value="{{ old('price') }}" />
            </div>

            <div class="input-group-custom">
              <label class="label-custom">Descripción</label>
              <textarea name="description" class="textarea-custom" rows="3" required>{{ old('description') }}</textarea>
            </div>

            <div class="input-group-custom">
              <label class="label-custom">Foto del Reloj</label>
              <input type="file" name="image" accept="image/*" class="file-input-custom" required />
            </div>

            <button type="submit" class="btn-primary w-100 mt-2">Agregar al Catálogo</button>
          </form>

          <form action="{{ route('admin.logout') }}" method="POST" class="mt-4 text-center">
            @csrf
            <button type="submit" class="btn-secondary">Cerrar Sesión Administrativa</button>
          </form>
        @else
          <!-- Formulario de acceso (No autenticado) -->
          <h2 class="section-heading">Acceso Restringido</h2>
          <p class="text-center text-muted mb-4">Ingresa tus credenciales para administrar el catálogo.</p>
          
          <form action="{{ route('admin.login') }}" method="POST" class="sell-form-custom">
            @csrf
            <div class="input-group-custom">
              <label class="label-custom">Usuario</label>
              <input type="text" name="username" class="input-custom" required />
            </div>
            <div class="input-group-custom">
              <label class="label-custom">Contraseña</label>
              <input type="password" name="password" class="input-custom" required />
            </div>
            
            @if(session('login_error'))
              <p class="error-message-custom">{{ session('login_error') }}</p>
            @endif

            <button type="submit" class="btn-primary w-100 mt-2">Ingresar</button>
          </form>
        @endif
      </div>
    </div>
  </section>

  <!-- Carrito (Overlay y Drawer) -->
  <div class="cart-overlay-custom" id="cart-overlay" style="display: {{ session('cart_open') ? 'block' : 'none' }};"></div>
  
  <div class="cart-drawer-custom {{ session('cart_open') ? 'open' : 'closed' }}" id="cart-drawer" style="transform: {{ session('cart_open') ? 'translateX(0)' : 'translateX(100%)' }};">
    <div class="cart-header-custom">
      <h2>Tu Carro</h2>
      <button class="cart-close-btn-custom" id="cart-close-btn">✕</button>
    </div>
    
    <div class="cart-items-container-custom">
      @if(empty($cart))
        <p class="cart-empty-text-custom">Tu carro está vacío.</p>
      @else
        @foreach($cart as $item)
          <div class="cart-item-custom">
            <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" class="cart-item-image-custom" />
            <div class="cart-item-details-custom">
              <h4>{{ $item['name'] }}</h4>
              <p class="cart-item-price-custom">${{ number_format($item['price'], 0, ',', '.') }}</p>
              <div class="cart-qty-controls-custom">
                <!-- Restar Cantidad -->
                <form action="{{ route('cart.update', $item['id']) }}" method="POST">
                  @csrf
                  <input type="hidden" name="amount" value="-1">
                  <button type="submit" class="cart-qty-btn-custom">-</button>
                </form>
                <span>{{ $item['quantity'] }}</span>
                <!-- Sumar Cantidad -->
                <form action="{{ route('cart.update', $item['id']) }}" method="POST">
                  @csrf
                  <input type="hidden" name="amount" value="1">
                  <button type="submit" class="cart-qty-btn-custom">+</button>
                </form>
              </div>
            </div>
            <!-- Eliminar del Carro -->
            <form action="{{ route('cart.remove', $item['id']) }}" method="POST">
              @csrf
              <button type="submit" class="cart-remove-btn-custom">🗑</button>
            </form>
          </div>
        @endforeach
      @endif
    </div>

    @if(!empty($cart))
      <div class="cart-footer-custom">
        <div class="cart-total-row-custom">
          <span>Total:</span>
          <span class="cart-total-price-custom">${{ number_format($cartTotal, 0, ',', '.') }}</span>
        </div>
        
        @php
          // Generar mensaje de WhatsApp
          $message = "Hola, me gustaría concretar la compra de los siguientes relojes:\n\n";
          foreach ($cart as $item) {
              $message .= "- " . $item['quantity'] . "x " . $item['name'] . " ($" . number_format($item['price'], 0, ',', '.') . ")\n";
          }
          $message .= "\n*Total Estimado:* $" . number_format($cartTotal, 0, ',', '.') . "\n\n";
          $message .= "Quedo atento(a) para coordinar el pago y envío.";
          $whatsappUrl = "https://wa.me/56912345678?text=" . urlencode($message);
        @endphp
        
        <a href="{{ $whatsappUrl }}" target="_blank" class="cart-checkout-btn-custom text-decoration-none">
          Checkout via WhatsApp
        </a>
      </div>
    @endif
  </div>
@endsection

@section('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const toggleBtn = document.getElementById('cart-toggle-btn');
      const closeBtn = document.getElementById('cart-close-btn');
      const overlay = document.getElementById('cart-overlay');
      const drawer = document.getElementById('cart-drawer');

      // Función para abrir
      function openCart() {
        overlay.style.display = 'block';
        drawer.style.transform = 'translateX(0)';
        drawer.classList.remove('closed');
        drawer.classList.add('open');
      }

      // Función para cerrar
      function closeCart() {
        overlay.style.display = 'none';
        drawer.style.transform = 'translateX(100%)';
        drawer.classList.remove('open');
        drawer.classList.add('closed');
        
        // Hacer una petición de fondo silenciosa para actualizar el estado del carrito en la sesión
        // y evitar que vuelva a abrirse tras recargar.
        fetch("{{ route('cart.toggle') }}", {
          method: "POST",
          headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Content-Type": "application/json"
          }
        });
      }

      toggleBtn.addEventListener('click', function(e) {
        e.preventDefault();
        if (drawer.classList.contains('open')) {
          closeCart();
        } else {
          openCart();
        }
      });

      if (closeBtn) {
        closeBtn.addEventListener('click', closeCart);
      }
      if (overlay) {
        overlay.addEventListener('click', closeCart);
      }
    });
  </script>
@endsection
