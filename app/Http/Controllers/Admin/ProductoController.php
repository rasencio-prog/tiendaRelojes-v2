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
            'imagenes'             => ['nullable', 'array', 'max:5'],
            'imagenes.*'           => ['image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'destacado'            => ['boolean'],
            'activo'               => ['boolean'],
        ], $this->mensajesValidacion());

        // Valores por defecto para checkboxes
        $datos['destacado'] = $request->boolean('destacado');
        $datos['activo']    = $request->boolean('activo', true);

        $producto = Producto::create($datos);

        // Procesar imágenes si se subieron
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $orden => $imagen) {
                $rutaImagen = $this->subirImagen($imagen);
                $producto->imagenes()->create([
                    'ruta_imagen' => $rutaImagen,
                    'orden'       => $orden,
                ]);
            }
        }

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
            'imagenes'             => ['nullable', 'array', 'max:5'],
            'imagenes.*'           => ['image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'imagenes_existentes'  => ['nullable', 'array'],
            'imagenes_existentes.*' => ['exists:producto_imagenes,id'],
            'destacado'            => ['boolean'],
            'activo'               => ['boolean'],
        ], $this->mensajesValidacion());

        // Eliminar imágenes existentes que no estén en 'imagenes_existentes'
        $imagenesExistentesIds = $request->input('imagenes_existentes', []);
        foreach ($producto->imagenes as $imagen) {
            if (!in_array($imagen->id, $imagenesExistentesIds)) {
                Storage::disk('public')->delete($imagen->ruta_imagen);
                $imagen->delete();
            }
        }

        // Procesar nuevas imágenes si se subieron
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $orden => $imagen) {
                $rutaImagen = $this->subirImagen($imagen);
                $producto->imagenes()->create([
                    'ruta_imagen' => $rutaImagen,
                    'orden'       => $producto->imagenes()->count() + $orden, // Append new images
                ]);
            }
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

        // Eliminar todas las imágenes asociadas del Storage
        foreach ($producto->imagenes as $imagen) {
            Storage::disk('public')->delete($imagen->ruta_imagen);
            $imagen->delete(); // Eliminar el registro de la BD
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
     * Sube una imagen al Storage con marca de agua incrustada mediante GD.
     * Retorna la ruta relativa almacenada en la BD (ej: productos/nombre.jpg).
     */
    private function subirImagen($archivo): string
    {
        $extension   = strtolower($archivo->getClientOriginalExtension());
        $nombreBase  = Str::slug(pathinfo($archivo->getClientOriginalName(), PATHINFO_FILENAME));
        $nombreFinal = time() . '_' . $nombreBase . '.' . $extension;
        $carpeta     = storage_path('app/public/productos');
        $rutaFinal   = $carpeta . '/' . $nombreFinal;

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0755, true);
        }

        $tmpPath = $archivo->getRealPath();

        $img = match($extension) {
            'jpg', 'jpeg' => imagecreatefromjpeg($tmpPath),
            'png'         => imagecreatefrompng($tmpPath),
            'webp'        => imagecreatefromwebp($tmpPath),
            default       => null,
        };

        if ($img) {
            imagealphablending($img, true);
            $this->agregarMarcaAgua($img);

            match($extension) {
                'jpg', 'jpeg' => imagejpeg($img, $rutaFinal, 90),
                'png'         => imagepng($img, $rutaFinal),
                'webp'        => imagewebp($img, $rutaFinal, 90),
                default       => null,
            };
        } else {
            $archivo->storeAs('productos', $nombreFinal, 'public');
        }

        return 'productos/' . $nombreFinal;
    }

    /**
     * Incrusta la marca de agua "Rolejeria.cl" diagonal y centrada en la imagen.
     */
    private function agregarMarcaAgua($img): void
    {
        $texto    = 'Rolejeria.cl';
        $fontPath = '/Library/Fonts/Arial Unicode.ttf';
        $w        = imagesx($img);
        $h        = imagesy($img);
        $angulo   = -30;
        $fontSize = max(14, (int)($w * 0.06));

        if (file_exists($fontPath)) {
            $bbox = imagettfbbox($fontSize, $angulo, $fontPath, $texto);
            // Centrar el texto en la imagen
            $cx = ($bbox[0] + $bbox[4]) / 2;
            $cy = ($bbox[1] + $bbox[5]) / 2;
            $x  = (int)($w / 2 - $cx);
            $y  = (int)($h / 2 - $cy);

            // Sombra sutil oscura
            $sombra = imagecolorallocatealpha($img, 0, 0, 0, 90);
            imagettftext($img, $fontSize, $angulo, $x + 2, $y + 2, $sombra, $fontPath, $texto);

            // Texto blanco semitransparente (alpha 0=opaco, 127=transparente en GD)
            $color = imagecolorallocatealpha($img, 255, 255, 255, 60);
            imagettftext($img, $fontSize, $angulo, $x, $y, $color, $fontPath, $texto);
        } else {
            // Fallback sin fuente TTF
            $color = imagecolorallocatealpha($img, 255, 255, 255, 60);
            $fw = imagefontwidth(5) * strlen($texto);
            $fh = imagefontheight(5);
            imagestring($img, 5, (int)(($w - $fw) / 2), (int)(($h - $fh) / 2), $texto, $color);
        }
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
            'imagenes.max'             => 'No puedes subir más de 5 imágenes por producto.',
            'imagenes.*.image'         => 'Cada archivo debe ser una imagen.',
            'imagenes.*.mimes'         => 'Cada imagen debe ser JPG, PNG o WebP.',
            'imagenes.*.max'           => 'Cada imagen no puede superar los 3MB.',
            'imagenes_existentes.*.exists' => 'Una de las imágenes existentes seleccionadas no es válida.',
        ];
    }
}
