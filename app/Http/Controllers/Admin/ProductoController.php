<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Controlador CRUD del panel de administración para la gestión de productos.
 *
 * Todas las rutas de este controlador están protegidas con middleware 'auth'.
 * Gestiona listado, creación, edición, eliminación y cambio de estado de productos.
 */
class ProductoController extends Controller
{
    /**
     * Muestra el listado de todos los productos.
     * Incluye productos activos e inactivos para la gestión completa.
     */
    public function index()
    {
        $productos = Producto::orderBy('created_at', 'desc')->paginate(15);

        return view('admin.productos.index', compact('productos'));
    }

    /**
     * Muestra el formulario de creación de un nuevo producto.
     */
    public function create()
    {
        $categorias = $this->getCategorias();
        return view('admin.productos.create', compact('categorias'));
    }

    /**
     * Almacena un nuevo producto en la base de datos.
     *
     * La imagen se sube al disco de Storage en la carpeta 'productos'.
     * El precio SIEMPRE se lee desde el formulario admin (nunca desde el carrito).
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'codigo_producto'      => ['required', 'string', 'max:50', 'unique:productos,codigo_producto'],
            'nombre'               => ['required', 'string', 'max:255'],
            'marca'                => ['required', 'string', 'max:100'],
            'categoria'            => ['required', 'string', 'max:100'],
            'descripcion'          => ['required', 'string'],
            'precio'               => ['required', 'numeric', 'min:0'],
            'porcentaje_descuento' => ['required', 'integer', 'min:0', 'max:100'],
            'stock'                => ['required', 'integer', 'min:0'],
            'imagen'               => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'destacado'            => ['boolean'],
            'activo'               => ['boolean'],
        ], $this->mensajesValidacion());

        // Procesar imagen si se subió
        if ($request->hasFile('imagen')) {
            $datos['imagen'] = $this->subirImagen($request->file('imagen'));
        }

        // Valores por defecto para checkboxes
        $datos['destacado'] = $request->boolean('destacado');
        $datos['activo']    = $request->boolean('activo', true);

        Producto::create($datos);

        return redirect()->route('admin.productos.index')
                         ->with('exito', '¡Producto "' . $datos['nombre'] . '" agregado exitosamente al catálogo!');
    }

    /**
     * Muestra el formulario de edición de un producto existente.
     */
    public function edit(Producto $producto)
    {
        $categorias = $this->getCategorias();
        return view('admin.productos.edit', compact('producto', 'categorias'));
    }

    /**
     * Actualiza los datos de un producto existente.
     *
     * Si se sube una nueva imagen, se elimina la anterior del Storage.
     */
    public function update(Request $request, Producto $producto)
    {
        $datos = $request->validate([
            'codigo_producto'      => ['required', 'string', 'max:50', 'unique:productos,codigo_producto,' . $producto->id],
            'nombre'               => ['required', 'string', 'max:255'],
            'marca'                => ['required', 'string', 'max:100'],
            'categoria'            => ['required', 'string', 'max:100'],
            'descripcion'          => ['required', 'string'],
            'precio'               => ['required', 'numeric', 'min:0'],
            'porcentaje_descuento' => ['required', 'integer', 'min:0', 'max:100'],
            'stock'                => ['required', 'integer', 'min:0'],
            'imagen'               => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'destacado'            => ['boolean'],
            'activo'               => ['boolean'],
        ], $this->mensajesValidacion());

        // Procesar nueva imagen si se subió
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior del Storage
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $datos['imagen'] = $this->subirImagen($request->file('imagen'));
        }

        // Valores por defecto para checkboxes
        $datos['destacado'] = $request->boolean('destacado');
        $datos['activo']    = $request->boolean('activo');

        $producto->update($datos);

        return redirect()->route('admin.productos.index')
                         ->with('exito', '¡Producto "' . $producto->nombre . '" actualizado correctamente!');
    }

    /**
     * Elimina un producto de la base de datos y su imagen del Storage.
     */
    public function destroy(Producto $producto)
    {
        $nombre = $producto->nombre;

        // Eliminar imagen del Storage si existe
        if ($producto->imagen) {
            Storage::disk('public')->delete($producto->imagen);
        }

        $producto->delete();

        return redirect()->route('admin.productos.index')
                         ->with('exito', '¡Producto "' . $nombre . '" eliminado correctamente!');
    }

    /**
     * Cambia el estado activo/inactivo de un producto.
     *
     * Permite activar o desactivar productos desde la lista sin necesidad
     * de entrar al formulario de edición completo.
     */
    public function toggleActivo(Producto $producto)
    {
        $producto->update(['activo' => !$producto->activo]);

        $estado = $producto->activo ? 'activado' : 'desactivado';

        return redirect()->back()
                         ->with('exito', 'Producto "' . $producto->nombre . '" ' . $estado . ' correctamente.');
    }

    // ─────────────────────────────────────────────
    // Métodos privados de apoyo
    // ─────────────────────────────────────────────

    /**
     * Sube una imagen al Storage de Laravel en la carpeta 'productos'.
     * Retorna la ruta relativa almacenada en la BD (ej: productos/nombre.jpg).
     */
    private function subirImagen($archivo): string
    {
        // Generar nombre único para evitar colisiones
        $extension  = $archivo->getClientOriginalExtension();
        $nombreBase = Str::slug(pathinfo($archivo->getClientOriginalName(), PATHINFO_FILENAME));
        $nombreFinal = time() . '_' . $nombreBase . '.' . $extension;

        // Guardar en storage/app/public/productos
        $archivo->storeAs('productos', $nombreFinal, 'public');

        return 'productos/' . $nombreFinal;
    }

    /**
     * Retorna la lista de categorías disponibles para los selectores de formulario.
     */
    private function getCategorias(): array
    {
        return [
            'Clásico',
            'Deportivo',
            'Buceo',
            'Cronógrafo',
            'Esqueleto',
            'Tourbillon',
            'Edición Limitada',
            'Vintage',
            'Smartwatch de Lujo',
        ];
    }

    /**
     * Retorna los mensajes de validación en español.
     */
    private function mensajesValidacion(): array
    {
        return [
            'codigo_producto.required' => 'El código de producto es obligatorio.',
            'codigo_producto.unique'   => 'Este código de producto ya existe.',
            'nombre.required'          => 'El nombre del reloj es obligatorio.',
            'marca.required'           => 'La marca es obligatoria.',
            'categoria.required'       => 'La categoría es obligatoria.',
            'descripcion.required'     => 'La descripción es obligatoria.',
            'precio.required'          => 'El precio es obligatorio.',
            'precio.numeric'           => 'El precio debe ser un número.',
            'precio.min'               => 'El precio no puede ser negativo.',
            'porcentaje_descuento.min' => 'El descuento no puede ser negativo.',
            'porcentaje_descuento.max' => 'El descuento no puede superar el 100%.',
            'stock.min'                => 'El stock no puede ser negativo.',
            'imagen.image'             => 'El archivo debe ser una imagen.',
            'imagen.mimes'             => 'La imagen debe ser JPG, PNG o WebP.',
            'imagen.max'               => 'La imagen no puede superar los 3MB.',
        ];
    }
}
