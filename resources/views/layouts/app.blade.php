<!doctype html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <link rel="icon" type="image/png" href="{{ asset('assets/logo.png') }}" />
  <link rel="shortcut icon" type="image/png" href="{{ asset('assets/logo.png') }}" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Descubre nuestra colección exclusiva de relojes de alta gama en {{ config('tienda.nombre_tienda') }}." />
  <title>@yield('title', config('tienda.nombre_tienda'))</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

  <!-- Bootstrap 5.3 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

  <!-- Custom Style -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

  @yield('content')

  {{-- ────────────────────────────────────────────────
       Botón Flotante de WhatsApp
       Visible en todas las páginas de la tienda.
       Número y mensaje configurados en config/tienda.php
  ─────────────────────────────────────────────────── --}}
  @php
    $waNumero  = config('tienda.telefono_whatsapp');
    $waMensaje = urlencode('Hola, me interesa obtener información sobre los relojes publicados en la tienda.');
    $waUrl     = "https://wa.me/{$waNumero}?text={$waMensaje}";
  @endphp

  <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer"
     class="whatsapp-fab" id="whatsapp-fab" aria-label="Contactar por WhatsApp">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="28" height="28">
      <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
    </svg>
  </a>

  <!-- Bootstrap 5.3 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

  @yield('scripts')
</body>
</html>
