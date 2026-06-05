<!doctype html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('titulo', 'Panel Admin') · {{ config('tienda.nombre_tienda') }}</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

  <style>
    :root {
      --bg:       #0d0d0d;
      --sidebar:  #111111;
      --card:     #1a1a1a;
      --border:   #2a2a2a;
      --accent:   #c5a059;
      --accent2:  #dfb668;
      --text:     #f0f0f0;
      --muted:    #888;
      --danger:   #e74c3c;
      --success:  #27ae60;
      --warning:  #f39c12;
      --serif:    'Playfair Display', serif;
      --sans:     'Inter', sans-serif;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { background: var(--bg); color: var(--text); font-family: var(--sans); min-height: 100vh; display: flex; }

    /* ── Sidebar ─────────────────────────── */
    .admin-sidebar {
      width: 240px; min-height: 100vh; background: var(--sidebar);
      border-right: 1px solid var(--border); display: flex; flex-direction: column;
      position: fixed; top: 0; left: 0; bottom: 0; z-index: 100;
    }
    .sidebar-brand {
      padding: 1.5rem; border-bottom: 1px solid var(--border);
      font-family: var(--serif); font-size: 1.4rem; color: var(--accent);
      letter-spacing: 3px; text-align: center; text-transform: uppercase;
    }
    .sidebar-brand small {
      display: block; font-family: var(--sans); font-size: 0.65rem;
      color: var(--muted); letter-spacing: 2px; margin-top: 0.2rem;
    }
    .sidebar-nav { flex: 1; padding: 1.5rem 0; }
    .nav-section-label {
      font-size: 0.65rem; color: var(--muted); letter-spacing: 2px;
      text-transform: uppercase; padding: 0 1.5rem; margin-bottom: 0.5rem; margin-top: 1rem;
    }
    .nav-link {
      display: flex; align-items: center; gap: 0.75rem;
      padding: 0.7rem 1.5rem; color: var(--muted); text-decoration: none;
      font-size: 0.9rem; font-weight: 500; transition: all 0.2s;
      border-left: 3px solid transparent;
    }
    .nav-link:hover, .nav-link.active {
      color: var(--text); background: rgba(197,160,89,0.07);
      border-left-color: var(--accent);
    }
    .nav-link .icon { font-size: 1rem; width: 20px; text-align: center; }
    .sidebar-footer {
      padding: 1rem 1.5rem; border-top: 1px solid var(--border);
    }
    .sidebar-user { font-size: 0.8rem; color: var(--muted); margin-bottom: 0.8rem; }
    .sidebar-user strong { color: var(--text); display: block; }
    .btn-logout {
      display: block; width: 100%; padding: 0.6rem;
      background: transparent; border: 1px solid var(--border);
      color: var(--muted); border-radius: 4px; font-size: 0.8rem;
      cursor: pointer; font-family: var(--sans); text-align: center;
      text-decoration: none; transition: all 0.2s;
    }
    .btn-logout:hover { border-color: var(--danger); color: var(--danger); }

    /* ── Main Content ─────────────────────── */
    .admin-main { margin-left: 240px; flex: 1; display: flex; flex-direction: column; min-height: 100vh; }
    .admin-topbar {
      background: var(--sidebar); border-bottom: 1px solid var(--border);
      padding: 1rem 2rem; display: flex; align-items: center; justify-content: space-between;
    }
    .topbar-title { font-family: var(--serif); font-size: 1.2rem; color: var(--text); }
    .topbar-breadcrumb { font-size: 0.8rem; color: var(--muted); }
    .admin-content { padding: 2rem; flex: 1; }

    /* ── Alerts ──────────────────────────── */
    .alert {
      padding: 0.9rem 1.2rem; border-radius: 4px; margin-bottom: 1.5rem;
      font-size: 0.875rem; display: flex; align-items: center; gap: 0.5rem;
    }
    .alert-success { background: rgba(39,174,96,0.12); border: 1px solid var(--success); color: var(--success); }
    .alert-error   { background: rgba(231,76,60,0.12);  border: 1px solid var(--danger);  color: var(--danger); }
    .alert-warning { background: rgba(243,156,18,0.12); border: 1px solid var(--warning); color: var(--warning); }

    /* ── Cards ───────────────────────────── */
    .card { background: var(--card); border: 1px solid var(--border); border-radius: 8px; }
    .card-header {
      padding: 1.2rem 1.5rem; border-bottom: 1px solid var(--border);
      display: flex; align-items: center; justify-content: space-between;
    }
    .card-header h2 { font-family: var(--serif); font-size: 1.1rem; color: var(--text); }
    .card-body { padding: 1.5rem; }

    /* ── Stat Cards ───────────────────────── */
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fill,minmax(200px,1fr)); gap: 1.5rem; margin-bottom: 2rem; }
    .stat-card { background: var(--card); border: 1px solid var(--border); border-radius: 8px; padding: 1.5rem; }
    .stat-label { font-size: 0.75rem; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem; }
    .stat-value { font-family: var(--serif); font-size: 2rem; color: var(--accent); }
    .stat-desc   { font-size: 0.8rem; color: var(--muted); margin-top: 0.3rem; }

    /* ── Table ───────────────────────────── */
    .table-wrapper { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
    thead th {
      padding: 0.75rem 1rem; text-align: left; font-size: 0.7rem;
      text-transform: uppercase; letter-spacing: 1px; color: var(--muted);
      border-bottom: 1px solid var(--border); background: var(--sidebar);
    }
    tbody td { padding: 0.9rem 1rem; border-bottom: 1px solid var(--border); vertical-align: middle; }
    tbody tr:hover { background: rgba(255,255,255,0.02); }
    .product-img-thumb { width: 44px; height: 44px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border); }
    .img-placeholder-thumb {
      width: 44px; height: 44px; background: var(--border); border-radius: 4px;
      display: flex; align-items: center; justify-content: center; font-size: 1.2rem;
    }

    /* ── Badges ──────────────────────────── */
    .badge {
      display: inline-block; padding: 0.25rem 0.6rem; border-radius: 20px;
      font-size: 0.7rem; font-weight: 600; letter-spacing: 0.5px;
    }
    .badge-active   { background: rgba(39,174,96,0.15);  color: var(--success); border: 1px solid var(--success); }
    .badge-inactive { background: rgba(136,136,136,0.15); color: var(--muted);   border: 1px solid var(--border); }
    .badge-discount { background: rgba(231,76,60,0.15);  color: #e74c3c;        border: 1px solid #e74c3c; }

    /* ── Buttons ─────────────────────────── */
    .btn {
      display: inline-flex; align-items: center; gap: 0.4rem;
      padding: 0.5rem 1rem; border-radius: 4px; font-size: 0.85rem;
      font-weight: 500; cursor: pointer; text-decoration: none;
      border: 1px solid transparent; font-family: var(--sans); transition: all 0.2s;
    }
    .btn-primary   { background: var(--accent); color: #000; border-color: var(--accent); }
    .btn-primary:hover { background: var(--accent2); }
    .btn-secondary { background: transparent; color: var(--text); border-color: var(--border); }
    .btn-secondary:hover { border-color: var(--text); }
    .btn-danger    { background: transparent; color: var(--danger); border-color: var(--danger); }
    .btn-danger:hover { background: var(--danger); color: #fff; }
    .btn-sm { padding: 0.3rem 0.7rem; font-size: 0.78rem; }
    .btn-toggle-off { background: transparent; color: var(--muted); border-color: var(--border); }
    .btn-toggle-off:hover { border-color: var(--success); color: var(--success); }
    .btn-toggle-on  { background: transparent; color: var(--success); border-color: var(--success); }
    .btn-toggle-on:hover { border-color: var(--danger); color: var(--danger); }

    /* ── Forms ───────────────────────────── */
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
    .form-group { display: flex; flex-direction: column; gap: 0.4rem; }
    .form-group.full { grid-column: 1 / -1; }
    .form-label { font-size: 0.85rem; font-weight: 500; color: var(--text); }
    .form-hint  { font-size: 0.75rem; color: var(--muted); margin-top: 0.2rem; }
    .form-control {
      padding: 0.7rem 0.9rem; background: var(--bg); border: 1px solid var(--border);
      border-radius: 4px; color: var(--text); font-size: 0.95rem;
      font-family: var(--sans); outline: none; transition: border-color 0.2s; width: 100%;
    }
    .form-control:focus { border-color: var(--accent); }
    .form-control.is-invalid { border-color: var(--danger); }
    .invalid-feedback { font-size: 0.78rem; color: var(--danger); margin-top: 0.25rem; }
    textarea.form-control { resize: vertical; min-height: 100px; }
    select.form-control { appearance: none; cursor: pointer; }
    .form-check { display: flex; align-items: center; gap: 0.5rem; }
    .form-check input { width: 16px; height: 16px; accent-color: var(--accent); cursor: pointer; }
    .form-check label { font-size: 0.875rem; color: var(--text); cursor: pointer; margin: 0; }
    .form-actions { display: flex; gap: 1rem; align-items: center; margin-top: 0.5rem; }

    /* ── Image Preview ───────────────────── */
    .image-upload-area {
      border: 2px dashed var(--border); border-radius: 8px;
      padding: 1.5rem; text-align: center; cursor: pointer;
      transition: border-color 0.2s; background: var(--bg);
    }
    .image-upload-area:hover { border-color: var(--accent); }
    .image-upload-area input[type="file"] { display: none; }
    .image-preview { max-width: 150px; max-height: 150px; border-radius: 4px; margin-top: 1rem; object-fit: cover; }
    .upload-icon { font-size: 2rem; color: var(--muted); }
    .upload-text { font-size: 0.85rem; color: var(--muted); margin-top: 0.5rem; }

    /* ── Pagination ──────────────────────── */
    .pagination { display: flex; gap: 0.5rem; align-items: center; justify-content: center; margin-top: 1.5rem; }
    .page-link {
      display: inline-flex; align-items: center; justify-content: center;
      width: 36px; height: 36px; border: 1px solid var(--border); border-radius: 4px;
      color: var(--muted); text-decoration: none; font-size: 0.875rem; transition: all 0.2s;
    }
    .page-link:hover, .page-link.active { border-color: var(--accent); color: var(--accent); }
  </style>
</head>
<body>

  {{-- ── Sidebar ── --}}
  <aside class="admin-sidebar">
    <div class="sidebar-brand">
      LUXE
      <small>Administración</small>
    </div>

    <nav class="sidebar-nav">
      <div class="nav-section-label">General</div>
      <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <span class="icon">🏠</span> Dashboard
      </a>

      <div class="nav-section-label">Catálogo</div>
      <a href="{{ route('admin.productos.index') }}" class="nav-link {{ request()->routeIs('admin.productos.*') ? 'active' : '' }}">
        <span class="icon">⌚</span> Productos
      </a>
      <a href="{{ route('admin.productos.create') }}" class="nav-link">
        <span class="icon">➕</span> Agregar Producto
      </a>

      <div class="nav-section-label">Tienda</div>
      <a href="{{ route('tienda.index') }}" class="nav-link" target="_blank">
        <span class="icon">🛍️</span> Ver Tienda
      </a>
    </nav>

    <div class="sidebar-footer">
      <div class="sidebar-user">
        <strong>{{ Auth::user()->name }}</strong>
        {{ Auth::user()->email }}
      </div>
      <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button type="submit" class="btn-logout">Cerrar Sesión</button>
      </form>
    </div>
  </aside>

  {{-- ── Main ── --}}
  <main class="admin-main">
    <div class="admin-topbar">
      <div class="topbar-title">@yield('titulo', 'Panel de Administración')</div>
      <div class="topbar-breadcrumb">@yield('breadcrumb', 'Admin')</div>
    </div>

    <div class="admin-content">

      {{-- Alertas globales --}}
      @if(session('exito'))
        <div class="alert alert-success">✅ {{ session('exito') }}</div>
      @endif
      @if(session('error'))
        <div class="alert alert-error">❌ {{ session('error') }}</div>
      @endif
      @if($errors->any())
        <div class="alert alert-error">
          ❌ {{ $errors->first() }}
        </div>
      @endif

      @yield('content')
    </div>
  </main>

</body>
</html>
