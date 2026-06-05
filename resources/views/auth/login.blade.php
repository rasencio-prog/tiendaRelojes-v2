<!doctype html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Panel de Administración - LUXE Relojes de Lujo" />
  <title>Iniciar Sesión · {{ config('tienda.nombre_tienda') }}</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

  <style>
    :root {
      --color-bg:      #0a0a0a;
      --color-card:    #1a1a1a;
      --color-border:  #333;
      --color-accent:  #c5a059;
      --color-accent2: #dfb668;
      --color-text:    #f5f5f5;
      --color-muted:   #a3a3a3;
      --font-serif:    'Playfair Display', serif;
      --font-sans:     'Inter', sans-serif;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      background: var(--color-bg);
      color: var(--color-text);
      font-family: var(--font-sans);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
      background-image: radial-gradient(ellipse at 20% 50%, rgba(197,160,89,0.05) 0%, transparent 50%),
                        radial-gradient(ellipse at 80% 20%, rgba(197,160,89,0.03) 0%, transparent 50%);
    }
    .login-container {
      width: 100%;
      max-width: 420px;
    }
    .brand {
      text-align: center;
      margin-bottom: 2.5rem;
    }
    .brand-logo {
      font-family: var(--font-serif);
      font-size: 2.5rem;
      color: var(--color-accent);
      letter-spacing: 6px;
      text-transform: uppercase;
    }
    .brand-sub {
      font-size: 0.75rem;
      color: var(--color-muted);
      letter-spacing: 3px;
      text-transform: uppercase;
      margin-top: 0.3rem;
    }
    .card {
      background: var(--color-card);
      border: 1px solid var(--color-border);
      border-radius: 8px;
      padding: 2.5rem;
    }
    .card-title {
      font-family: var(--font-serif);
      font-size: 1.4rem;
      color: var(--color-text);
      margin-bottom: 0.5rem;
    }
    .card-desc {
      font-size: 0.85rem;
      color: var(--color-muted);
      margin-bottom: 2rem;
    }
    .field { margin-bottom: 1.5rem; }
    label {
      display: block;
      font-size: 0.85rem;
      font-weight: 500;
      color: var(--color-text);
      margin-bottom: 0.4rem;
    }
    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 0.8rem 1rem;
      background: var(--color-bg);
      border: 1px solid var(--color-border);
      border-radius: 4px;
      color: var(--color-text);
      font-size: 1rem;
      font-family: var(--font-sans);
      outline: none;
      transition: border-color 0.2s;
    }
    input:focus { border-color: var(--color-accent); }
    .checkbox-row {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      margin-bottom: 1.5rem;
    }
    .checkbox-row input { width: auto; }
    .checkbox-row label { margin: 0; font-size: 0.85rem; color: var(--color-muted); }
    .error-box {
      background: rgba(231,76,60,0.1);
      border: 1px solid #e74c3c;
      border-radius: 4px;
      padding: 0.8rem 1rem;
      margin-bottom: 1.5rem;
      font-size: 0.875rem;
      color: #e74c3c;
    }
    .alert-info {
      background: rgba(197,160,89,0.1);
      border: 1px solid var(--color-accent);
      border-radius: 4px;
      padding: 0.8rem 1rem;
      margin-bottom: 1.5rem;
      font-size: 0.875rem;
      color: var(--color-accent);
    }
    .btn-submit {
      width: 100%;
      padding: 0.9rem;
      background: var(--color-accent);
      color: var(--color-bg);
      border: none;
      border-radius: 4px;
      font-size: 1rem;
      font-weight: 600;
      letter-spacing: 1px;
      text-transform: uppercase;
      cursor: pointer;
      transition: background 0.2s;
      font-family: var(--font-sans);
    }
    .btn-submit:hover { background: var(--color-accent2); }
    .back-link {
      display: block;
      text-align: center;
      margin-top: 1.5rem;
      font-size: 0.85rem;
      color: var(--color-muted);
      text-decoration: none;
      transition: color 0.2s;
    }
    .back-link:hover { color: var(--color-accent); }
    .divider {
      height: 1px;
      background: var(--color-border);
      margin: 2rem 0;
    }
  </style>
</head>
<body>
  <div class="login-container">

    <div class="brand">
      <div class="brand-logo">LUXE</div>
      <div class="brand-sub">Panel de Administración</div>
    </div>

    <div class="card">
      <h1 class="card-title">Iniciar Sesión</h1>
      <p class="card-desc">Ingresa tus credenciales para acceder al panel.</p>

      {{-- Mensaje de cierre de sesión --}}
      @if(session('info'))
        <div class="alert-info">{{ session('info') }}</div>
      @endif

      {{-- Errores de validación --}}
      @if($errors->any())
        <div class="error-box">
          {{ $errors->first() }}
        </div>
      @endif

      <form method="POST" action="{{ route('admin.login.post') }}">
        @csrf

        <div class="field">
          <label for="email">Correo electrónico</label>
          <input
            id="email"
            type="email"
            name="email"
            value="{{ old('email') }}"
            autocomplete="email"
            autofocus
            required
          />
        </div>

        <div class="field">
          <label for="password">Contraseña</label>
          <input
            id="password"
            type="password"
            name="password"
            autocomplete="current-password"
            required
          />
        </div>

        <div class="checkbox-row">
          <input type="checkbox" id="recordar" name="recordar" value="1">
          <label for="recordar">Recordar sesión</label>
        </div>

        <button type="submit" class="btn-submit">Ingresar</button>
      </form>

      <div class="divider"></div>
    </div>

    <a href="{{ route('tienda.index') }}" class="back-link">← Volver a la tienda</a>

  </div>
</body>
</html>
