<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany; // Added this line

/**
 * Modelo Producto
 *
 * Representa un reloj en el catálogo de la tienda.
 * Todos los campos están nombrados en español.
 *
 * @property int         $id
 * @property string      $codigo_producto
 * @property string      $nombre
 * @property string      $marca
 * @property string      $descripcion
 * @property float       $precio
 * @property int         $porcentaje_descuento
 * @property int         $stock
 * @property string      $categoria
 * @property bool        $destacado
 * @property bool        $activo
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductoImagen> $imagenes
 * @property-read string $url_imagen
 * @property-read \Illuminate\Support\Collection<string> $imagenes_url
 */
class Producto extends Model
{
    use HasFactory;

    /**
     * Nombre explícito de la tabla en español.
     */
    protected $table = 'productos';

    /**
     * Campos asignables en masa.
     */
    protected $fillable = [
        'codigo_producto',
        'nombre',
        'marca',
        'descripcion',
        'precio',
        'porcentaje_descuento',
        'stock',
        'categoria',
        'destacado',
        'activo',
    ];

    /**
     * Conversión automática de tipos.
     */
    protected $casts = [
        'precio'               => 'decimal:2',
        'porcentaje_descuento' => 'integer',
        'stock'                => 'integer',
        'destacado'            => 'boolean',
        'activo'               => 'boolean',
    ];

    // ─────────────────────────────────────────────
    // Relationships
    // ─────────────────────────────────────────────

    /**
     * Get the images for the product.
     */
    public function imagenes(): HasMany
    {
        return $this->hasMany(ProductoImagen::class)->orderBy('orden');
    }

    // ─────────────────────────────────────────────
    // Scopes
    // ─────────────────────────────────────────────

    /**
     * Scope: solo productos activos (visibles en la tienda).
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope: solo productos con descuento (para la sección de ofertas).
     */
    public function scopeEnOferta($query)
    {
        return $query->where('porcentaje_descuento', '>', 0);
    }

    /**
     * Scope: solo productos destacados.
     */
    public function scopeDestacados($query)
    {
        return $query->where('destacado', true);
    }

    // ─────────────────────────────────────────────
    // Accessors
    // ─────────────────────────────────────────────

    /**
     * Retorna el precio final después de aplicar el descuento.
     */
    public function getPrecioFinalAttribute(): float
    {
        if ($this->porcentaje_descuento > 0) {
            return round($this->precio * (1 - $this->porcentaje_descuento / 100), 2);
        }
        return (float) $this->precio;
    }

    /**
     * Retorna la URL pública de la imagen principal del producto.
     * Si no tiene imágenes, devuelve una imagen por defecto.
     */
    public function getUrlImagenAttribute(): string
    {
        if ($this->imagenes->isNotEmpty()) {
            return $this->imagenes->first()->url_imagen;
        }
        return asset('assets/watch_submariner.png');
    }

    /**
     * Retorna una colección de URLs públicas de todas las imágenes del producto.
     */
    public function getImagenesUrlAttribute(): \Illuminate\Support\Collection
    {
        return $this->imagenes->map(fn ($imagen) => $imagen->url_imagen);
    }

    /**
     * Indica si el producto tiene descuento activo.
     */
    public function getTieneDescuentoAttribute(): bool
    {
        return $this->porcentaje_descuento > 0;
    }
}
