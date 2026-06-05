<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;

/**
 * Seeder de Productos
 *
 * Inserta el catálogo inicial de relojes de lujo con todos los campos
 * en español, incluyendo precios, descuentos, stock y categorías.
 */
class ProductoSeeder extends Seeder
{
    /**
     * Inserta los productos iniciales de la tienda.
     */
    public function run(): void
    {
        $productos = [
            [
                'codigo_producto'      => 'OCE-SUB-001',
                'nombre'               => 'Océaniste Submersible',
                'marca'                => 'Océaniste',
                'categoria'            => 'Buceo',
                'descripcion'          => 'Clásico reloj de buceo con bisel de cerámica y esfera negra, resistente al agua hasta 300m. Movimiento automático de alta precisión certificado.',
                'precio'               => 9500000.00,
                'porcentaje_descuento' => 0,
                'stock'                => 3,
                'imagen'               => null,
                'destacado'            => true,
                'activo'               => true,
            ],
            [
                'codigo_producto'      => 'CHR-PRO-001',
                'nombre'               => 'Chronograph Professional',
                'marca'                => 'Chronomètre',
                'categoria'            => 'Cronógrafo',
                'descripcion'          => 'El legendario cronógrafo con historia espacial. Esfera negra, cristal de zafiro y brazalete de acero inoxidable de 316L.',
                'precio'               => 6200000.00,
                'porcentaje_descuento' => 15,
                'stock'                => 5,
                'imagen'               => null,
                'destacado'            => true,
                'activo'               => true,
            ],
            [
                'codigo_producto'      => 'NAU-AUT-001',
                'nombre'               => 'Nautilus Automatic',
                'marca'                => 'Geneve',
                'categoria'            => 'Deportivo',
                'descripcion'          => 'Reloj deportivo de lujo con bisel octogonal y una elegante esfera azul con relieve. Calibre automático de manufactura propia.',
                'precio'               => 45000000.00,
                'porcentaje_descuento' => 0,
                'stock'                => 1,
                'imagen'               => null,
                'destacado'            => true,
                'activo'               => true,
            ],
            [
                'codigo_producto'      => 'ROY-OAK-001',
                'nombre'               => 'Royal Oak Classic',
                'marca'                => 'Le Brassus',
                'categoria'            => 'Clásico',
                'descripcion'          => "Obra maestra de la alta relojería con tornillos expuestos y esfera texturizada 'Grande Tapisserie'. Integración perfecta entre caja y brazalete.",
                'precio'               => 38000000.00,
                'porcentaje_descuento' => 10,
                'stock'                => 2,
                'imagen'               => null,
                'destacado'            => true,
                'activo'               => true,
            ],
        ];

        foreach ($productos as $dato) {
            Producto::updateOrCreate(
                ['codigo_producto' => $dato['codigo_producto']],
                $dato
            );
        }

        $this->command->info('✅ ' . count($productos) . ' productos insertados correctamente.');
    }
}
