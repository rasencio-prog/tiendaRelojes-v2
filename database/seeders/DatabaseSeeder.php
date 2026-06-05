<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

/**
 * Seeder principal de la base de datos.
 *
 * Crea el usuario administrador inicial y llama al seeder de productos.
 * Credenciales por defecto:
 *   Email:      admin@luxe.cl
 *   Contraseña: Admin1234!
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuario administrador inicial
        User::updateOrCreate(
            ['email' => 'admin@luxe.cl'],
            [
                'name'     => 'Administrador LUXE',
                'email'    => 'admin@luxe.cl',
                'password' => Hash::make('Admin1234!'),
            ]
        );

        $this->command->info('✅ Usuario administrador creado: admin@luxe.cl / Admin1234!');

        // Seeder de productos del catálogo inicial
        $this->call([
            ProductoSeeder::class,
        ]);
    }
}
