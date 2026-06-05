<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Océaniste Submersible',
                'brand' => 'Océaniste',
                'price' => 9500,
                'description' => 'Un clásico reloj de buceo con bisel de cerámica y esfera negra, resistente al agua hasta 300m.',
                'image' => 'assets/watch_submariner.png'
            ],
            [
                'name' => 'Chronograph Professional',
                'brand' => 'Chronomètre',
                'price' => 6200,
                'description' => 'El legendario cronógrafo con historia espacial. Esfera negra y cristal de zafiro.',
                'image' => 'assets/watch_speedmaster.png'
            ],
            [
                'name' => 'Nautilus Automatic',
                'brand' => 'Geneve',
                'price' => 45000,
                'description' => 'Reloj deportivo de lujo con bisel octogonal y una elegante esfera azul con relieve.',
                'image' => 'assets/watch_nautilus.png'
            ],
            [
                'name' => 'Royal Oak Classic',
                'brand' => 'Le Brassus',
                'price' => 38000,
                'description' => "Obra maestra de la alta relojería con tornillos expuestos y esfera texturizada 'Grande Tapisserie'.",
                'image' => 'assets/watch_royaloak.png'
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
