# LUXE - Tienda de Relojes de Lujo (Migración a Laravel 12)

Este proyecto ha sido migrado exitosamente desde un entorno React/Vite a una arquitectura moderna y robusta basada completamente en **Laravel 12** + **PHP 8.3** utilizando **Blade** + **Bootstrap 5.3** y **Sesiones de Laravel** para la gestión del carro de compras.

## Características de la Solución
- **Arquitectura Limpia**: Todo el código de presentación está construido con plantillas Blade nativas y componentes responsivos utilizando Bootstrap 5.3.
- **Estilo Luxury**: Mantiene la misma estética oscura y dorada premium original a través de hojas de estilo CSS en `public/css/style.css`.
- **Base de Datos Dinámica**: El catálogo se carga dinámicamente desde la base de datos usando migraciones y modelos Eloquent de Laravel.
- **Sesiones de Carro**: Toda la persistencia del carrito de compras se maneja en el servidor a través de sesiones de Laravel (`Session::get('cart')`), manteniendo la visualización reactiva del drawer de compras mediante Vanilla JS.
- **Panel Administrativo**: Acceso restringido con credenciales de administrador en la sección `#admin` para agregar nuevos productos al catálogo con carga de imágenes directa a la base de datos y al almacenamiento local (`public/uploads`).

---

## Requisitos Previos
- **PHP**: Versión 8.2 o superior (Recomendado 8.3)
- **Composer**: Para la instalación de dependencias de PHP.
- **Base de Datos**: SQLite (configurado por defecto para una ejecución instantánea) o MySQL.

---

## Instrucciones de Instalación y Ejecución en Windows

Sigue estos pasos en tu consola de Windows (Símbolo del sistema `CMD` o `PowerShell`) para poner en marcha el proyecto localmente:

### 1. Ingresar al directorio del proyecto
Abre la consola en el directorio donde clonaste la aplicación:
```cmd
cd tiendaRelojes
```

### 2. Instalar las dependencias de Laravel
Ejecuta Composer para instalar las dependencias de PHP definidas en `composer.json`:
```cmd
composer install
```

### 3. Configurar el Archivo de Entorno
Copia el archivo de configuración base `.env.example` para crear tu `.env`:

* **En Símbolo del Sistema (CMD):**
  ```cmd
  copy .env.example .env
  ```
* **En PowerShell:**
  ```powershell
  Copy-Item .env.example .env
  ```
> *Nota: Por defecto, el archivo `.env` está preconfigurado para usar **SQLite** como conexión de base de datos (`DB_CONNECTION=sqlite`), lo que evita la necesidad de instalar o configurar servidores de base de datos MySQL externos.*

### 4. Crear el archivo de Base de Datos SQLite
Crea el archivo vacío para SQLite ejecutando el comando correspondiente a tu consola:

* **En Símbolo del Sistema (CMD):**
  ```cmd
  type nul > database\database.sqlite
  ```
* **En PowerShell:**
  ```powershell
  New-Item -ItemType File -Path database\database.sqlite -Force
  ```

### 5. Generar la Clave de Aplicación de Laravel
Genera la clave única de encriptación para las sesiones y cookies:
```cmd
php artisan key:generate
```

### 6. Ejecutar las Migraciones y Poblar la Base de Datos (Seeders)
Crea las tablas de base de datos e inserta los 4 relojes de lujo iniciales ejecutando:
```cmd
php artisan migrate --seed
```

### 7. Levantar el Servidor de Desarrollo
Inicia el servidor local de desarrollo de Laravel:
```cmd
php artisan serve
```

Una vez ejecutado, abre tu navegador e ingresa a la dirección:
👉 **[http://localhost:8000](http://localhost:8000)**

---

## Funcionalidades Clave y Pruebas

### 🔒 Acceso al Panel de Administración
1. Navega a la sección **Administración** (enlace `#admin` en el menú).
2. Ingresa las credenciales demo:
   - **Usuario**: `admin`
   - **Contraseña**: `reloj123`
3. Al ingresar, verás el formulario para **Agregar nuevos relojes al catálogo**.
4. Rellena los campos y adjunta una imagen de tu reloj. Al hacer clic en **Agregar al Catálogo**, se subirá la imagen a `public/uploads` y se registrará dinámicamente en la base de datos, mostrándose de inmediato en la sección **Colección Destacada**.

### 🛒 Carrito de Compras y Checkout vía WhatsApp
1. Haz clic en **Comprar** en cualquier reloj del catálogo.
2. El producto se añadirá al carrito (persiste en la sesión de Laravel) y el cajón lateral del carrito se abrirá automáticamente en la derecha.
3. Puedes ajustar las cantidades (+/-) o eliminar los productos presionando el ícono de la papelera 🗑.
4. Presiona **Checkout via WhatsApp** para ser redirigido a WhatsApp con un mensaje estructurado automáticamente con los nombres de los relojes, cantidades y el total en pesos chilenos (CLP) para coordinar la entrega.
