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
    input[type="password"],
    input.password-input {
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
    .password-wrapper {
      position: relative;
    }
    .password-wrapper input {
      padding-right: 3rem;
    }
    .toggle-password {
      position: absolute;
      right: 0.8rem;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      cursor: pointer;
      color: var(--color-muted);
      padding: 0;
      display: flex;
      align-items: center;
      transition: color 0.2s;
    }
    .toggle-password:hover { color: var(--color-accent); }
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
      <div class="brand-logo">rojeleria.cl</div>
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
          <div class="password-wrapper">
            <input
              id="password"
              type="password"
              name="password"
              class="password-input"
              autocomplete="current-password"
              required
            />
            <button type="button" class="toggle-password" onclick="togglePassword()" aria-label="Mostrar/ocultar contraseña">
              <svg id="icon-eye" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
              </svg>
              <svg id="icon-eye-off" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none">
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>
              </svg>
            </button>
          </div>
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
  <script>
    function togglePassword() {
      const input = document.getElementById('password');
      const eyeOn  = document.getElementById('icon-eye');
      const eyeOff = document.getElementById('icon-eye-off');
      if (input.type === 'password') {
        input.type = 'text';
        eyeOn.style.display  = 'none';
        eyeOff.style.display = 'block';
      } else {
        input.type = 'password';
        eyeOn.style.display  = 'block';
        eyeOff.style.display = 'none';
      }
    }
  </script>
</body>
</html>
