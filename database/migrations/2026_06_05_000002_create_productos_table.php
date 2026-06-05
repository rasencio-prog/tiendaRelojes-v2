<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crea la tabla principal de productos de la tienda de relojes.
     * Todos los campos de negocio están nombrados en español.
     */
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();

            // Código único del producto (ej: ROL-SUB-001)
            $table->string('codigo_producto')->unique();

            // Datos descriptivos del reloj
            $table->string('nombre');
            $table->string('marca');
            $table->text('descripcion');

            // Precio y descuento
            $table->decimal('precio', 12, 2);
            $table->unsignedTinyInteger('porcentaje_descuento')->default(0)
                  ->comment('Porcentaje de descuento entre 0 y 100');

            // Inventario y clasificación
            $table->unsignedInteger('stock')->default(0);
            $table->string('categoria')->default('Clásico')
                  ->comment('Ej: Clásico, Deportivo, Buceo, Cronógrafo, Edición Limitada');

            // Imagen: ruta relativa al disco de Storage (ej: productos/reloj.jpg)
            $table->string('imagen')->nullable();

            // Flags de estado
            $table->boolean('destacado')->default(false)
                  ->comment('Aparece en la sección de colección destacada');
            $table->boolean('activo')->default(true)
                  ->comment('Solo los productos activos se muestran en la tienda');

            $table->timestamps();
        });
    }

    /**
     * Elimina la tabla de productos.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
